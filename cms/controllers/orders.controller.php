<?php
require_once 'xml.controller.php';
require_once 'apiclient.controller.php';
require_once 'csrf.controller.php';
// en /controllers/OrdenesController.php
require_once __DIR__ . '/../GeneratePDFfromXML/FacturaXMLParser.php';
require_once __DIR__ . '/../GeneratePDFfromXML/FacturaPDFGenerator.php';
require_once __DIR__ . '/../GeneratePDFfromXML/facturapdf.php';

class OrdersController
{

	/*=============================================
    Gestionar Órdenes
    =============================================*/
	public function manageOrder()
	{

		if (!isset($_POST["idOrderPay"])) return;

		if (!CsrfController::validate($_POST["csrf_token"] ?? null)) {
			$this->outputError("Sesión expirada o solicitud inválida. Refresca la página e intenta de nuevo.");
			return;
		}

		// Iniciar preloader y alerta de carga
		echo '<script>
                fncMatPreloader("on");
                fncSweetAlert("loading", "Procesando la orden...", "");
              </script>';



		// Obtener las ventas relacionadas a la orden
		$salesResponse = ApiClient::get(
			"relations?rel=sales,orders&type=sale,order&linkTo=id_order_sale&equalTo=" . $_POST["idOrderPay"] . "&select=*"
		);

		if ($salesResponse->status !== 200) {
			$this->outputError("Error al obtener las ventas");
			return;
		}

		$salesResults = $salesResponse->results;
		$countSales = 0;
		$arrayProducts = [];
		$totalSales = count($salesResults);

		// Procesar cada venta
		foreach ($salesResults as $sale) {
			if ($this->updateSaleStatus($sale->id_sale, "Completada")) {
				$countSales++;

				// Recuperar información del producto
				$productResponse = ApiClient::get(
					"products?linkTo=id_product&equalTo=" . $sale->id_product_sale
				);
				if ($productResponse->status === 200) {
					$product = $productResponse->results[0];
					$id_product = $product->id_product;
					$arrayProducts[$id_product] = [
						"id_product"      => $id_product,
						"sku_product"     => $product->sku_product,
						"title_product"   => urldecode($product->title_product),
						"discount_product" => $product->discount_product,
						"tax_product"     => $product->tax_product,
						"unit_product"    => $product->unit_product
					];
				}
			}

			// Al terminar de procesar todas las ventas, si aplica, generar factura
			if ($countSales === $totalSales) {
				if (isset($_POST["clientInvoice"]) && $_POST["clientInvoice"] == "yes") {
					$this->processInvoice($salesResponse, $arrayProducts);
					// Actualizar la orden
					$orderUpdated = $this->updateOrder($_POST["idOrderPay"], [
						"method_order"   => $_POST["methodPay"],
						"transfer_order" => $_POST["transferPay"],
						"status_order"   => "Completada"
					]);

					if (!$orderUpdated) {
						$this->outputError("Error al procesar la orden");
						exit;
					} else {
						$print = CurlController::ticketPrintLocal($_POST["idOrderPay"], $_SESSION['admin']->name_admin);
					}
				} else {
					// Si no se requiere factura, simplemente actualizar el estado de la orden

					// Actualizar la orden
					$orderUpdated = $this->updateOrder($_POST["idOrderPay"], [
						"method_order"   => $_POST["methodPay"],
						"transfer_order" => $_POST["transferPay"],
						"status_order"   => "Completada",
						
					]);
					}

					// Dar respuesta exitosa al vendedor
					$this->outputSuccess($salesResults[0]->transaction_order);
				}
			}
		}

	// Actualiza la orden a través de la API
	private function updateOrder($idOrder, $fieldsData)
	{
		$url = "orders?id=" . $idOrder . "&nameId=id_order&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
		$response = ApiClient::put($url, $fieldsData);
		return ($response->status === 200);
	}

	// Actualiza el estado de una venta
	private function updateSaleStatus($idSale, $status)
	{
		$url = "sales?id=" . $idSale . "&nameId=id_sale&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
		$response = ApiClient::put($url, ["status_sale" => $status]);
		return ($response->status === 200);
	}

	private function processInvoice($salesResponse, $arrayProducts)
	{
		$xmlController = new xmlController();

		// Obtener información de la oficina
		$officesResponse = ApiClient::get(
			"offices?select=*&linkTo=id_office&equalTo=" . $_SESSION["admin"]->id_office_admin
		);
		if ($officesResponse->status !== 200) {
			$this->outputError("Error obteniendo información de la oficina");
			exit;
		}

		// Obtener información del cliente
		$clientsResponse = ApiClient::get(
			"clients?linkTo=id_client&equalTo=" . $salesResponse->results[0]->id_client_order
		);
		if ($clientsResponse->status !== 200) {
			$this->outputError("Error obteniendo información del cliente");
			exit;
		}

		// Obtener secuencial
		$secuencialFields = [
			"oficina_secuencial" => $_SESSION["admin"]->id_local_office,
			"caja_secuencial"    => $_SESSION["admin"]->cash_admin,
			"office_secuencial"  => $officesResponse->results[0]->id_office
		];
		$secuencialResponse = ApiClient::post("secuencials", $secuencialFields);
		if (!isset($secuencialResponse->status) || $secuencialResponse->status !== 200 || !isset($secuencialResponse->results)) {
			$this->outputError("Error secuencial no obtenido");
			exit;
		}
		$siguienteSecuencial = $secuencialResponse->results;
		$siguienteSecuencialFormateado = str_pad($siguienteSecuencial, 9, "0", STR_PAD_LEFT);

		/*=============================================
		Parte RÁPIDA (síncrona): generar XML y firmarlo localmente.
		La parte LENTA (SRI, PDF, correo) se delega a un worker en
		segundo plano para no hacer esperar al cajero.
		=============================================*/
		try {

			$xmlGenerado = $xmlController->generarXMLComprobante(
				$salesResponse,
				$officesResponse,
				'./xml/facturas_no_firmadas',
				$arrayProducts,
				$clientsResponse,
				$siguienteSecuencialFormateado
			);
			if (!$xmlGenerado) {
				throw new Exception("No se pudo generar el XML de comprobante");
			}
			$archivoFirmado = $xmlController->firmarXML($xmlGenerado['numeroFactura'], $xmlGenerado['ruc']);

			/*=============================================
			Registrar la factura con estado PENDIENTE
			(el worker la irá actualizando: AUTORIZADO / DEVUELTA / ERROR)
			=============================================*/
			$invoiceFields = [
				"id_order_invoce"      => $_POST["idOrderPay"],
				"access_key_invoice"   => $xmlGenerado['claveAcceso'],
				"status_invoice"       => "PENDIENTE",
				"date_created_invoice" => date("Y-m-d")
			];
			ApiClient::post(
				"invoices?token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin",
				$invoiceFields
			);

			/*=============================================
			Lanzar el worker en segundo plano
			=============================================*/
			$emailCliente = $clientsResponse->results[0]->email_client ?? "";
			$emailEmisor  = $_SESSION["admin"]->email_office ?? "";

			$this->lanzarWorkerFactura(
				$archivoFirmado,
				$xmlGenerado['claveAcceso'],
				$emailCliente,
				$emailEmisor
			);

			echo '<div class="alert alert-success mt-3 p-3 rounded">
					Factura ' . $xmlGenerado['numeroFactura'] . ' firmada.
					La autorización del SRI y el envío al cliente se completan en segundo plano.
				  </div>';

		} catch (Exception $e) {
			// Si hubo error en generar o firmar el XML, se cancela el proceso
			$this->outputError("Error al procesar factura: " . $e->getMessage());
			exit;
		}
	}

	/*=============================================
	Ejecuta el worker de facturación sin bloquear la respuesta.
	Compatible con macOS/Linux (&) y Windows (start /B).
	=============================================*/
	private function lanzarWorkerFactura($rutaFirmado, $claveAcceso, $emailCliente, $emailEmisor)
	{
		$worker = realpath(__DIR__ . '/../workers/procesar_factura.php');

		$args = ' --firmado=' . escapeshellarg($rutaFirmado)
			. ' --clave=' . escapeshellarg($claveAcceso)
			. ' --email-cliente=' . escapeshellarg($emailCliente)
			. ' --email-emisor=' . escapeshellarg($emailEmisor);

		if (PHP_OS_FAMILY === 'Windows') {
			pclose(popen('start /B "" ' . escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($worker) . $args, 'r'));
		} else {
			exec(escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($worker) . $args . ' > /dev/null 2>&1 &');
		}
	}

	// Función para mostrar errores de forma consistente
	private function outputError($message)
	{
		echo '<div class="alert alert-danger mt-3 p-3 rounded alertPos">' . $message . '</div>
	          <script>
	            fncMatPreloader("off");
	            fncSweetAlert("error", "[ERROR]: no se pudo procesar la orden", "/pos");
	            fncFormatInputs();
	          </script>';
	}

	private function outputSuccess($transactionOrder)
	{
		$message = json_encode("La orden #" . $transactionOrder . " ha sido completada con éxito");

		echo '<script>
			(function(){
				function fallbackResetPosOrder(){
					if (window.jQuery) {
						$("#modalPayMethod").modal("hide");
						$("#orderHeader").attr("mode", "off").attr("idOrder", "").removeClass("backColor").addClass("bg-light");
						$("#orderHeader h6").html("Orden # 0000000000");
						$(".removeOrder").attr("idOrder", "");
						$("#cleanListProduct").attr("idOrder", "").addClass("d-none");
						$("#addClient").addClass("d-none");
						$("#clientList").val("").trigger("change.select2");
						$("#addProduct").html("");
						$("#countProduct").html("0").removeClass("backColor").addClass("bg-light");
						$("#subtotal").attr("subtotal", "0.00").html("$ 0.00");
						$("#discount").attr("discount", "0.00").html("$ 0.00");
						$("#tax").attr("tax", "0.00").html("$ 0.00");
						$("#granTotal").removeClass("backColor bg-blue").addClass("bg-light");
						$("#granTotal span").attr("granTotal", "0.00").html("$ 0.00");
						$("#payMethods").hide();
						$("#idOrderPay, #methodPay, #transferPay, #cashPay, #returnPay, #idTransferPay").val("");
						$("#clientInvoice").prop("checked", false);
						return;
					}

					var addProduct = document.getElementById("addProduct");
					if (addProduct) addProduct.innerHTML = "";

					var orderHeader = document.getElementById("orderHeader");
					if (orderHeader) {
						orderHeader.setAttribute("mode", "off");
						orderHeader.setAttribute("idOrder", "");
						orderHeader.classList.remove("backColor");
						orderHeader.classList.add("bg-light");
						var title = orderHeader.querySelector("h6");
						if (title) title.textContent = "Orden # 0000000000";
					}
				}

				function cleanCompletedOrder(){
					if (typeof fncMatPreloader === "function") fncMatPreloader("off");

					if (typeof resetPosOrder === "function") {
						resetPosOrder();
					} else {
						fallbackResetPosOrder();
					}

					if (typeof fncSweetAlert === "function") {
						fncSweetAlert("success", ' . $message . ', "");
					}
					if (typeof fncFormatInputs === "function") fncFormatInputs();
				}

				if (document.readyState === "loading") {
					document.addEventListener("DOMContentLoaded", cleanCompletedOrder);
				} else {
					cleanCompletedOrder();
				}
			})();
		</script>';
	}
	}
