<?php
require_once 'xml.controller.php';

class OrdersController
{

	/*=============================================
    Gestionar Órdenes
    =============================================*/
	public function manageOrder()
	{
		if (!isset($_POST["idOrderPay"])) return;

		// Iniciar preloader y alerta de carga
		echo '<script>
                fncMatPreloader("on");
                fncSweetAlert("loading", "Procesando la orden...", "");
              </script>';

		// Actualizar la orden
		$orderUpdated = $this->updateOrder($_POST["idOrderPay"], [
			"method_order"   => $_POST["methodPay"],
			"transfer_order" => $_POST["transferPay"],
			"status_order"   => "Completada"
		]);

		if (!$orderUpdated) {
			$this->outputError("Error al procesar la orden");
			return;
		}

		// Obtener las ventas relacionadas a la orden
		$salesResponse = CurlController::request(
			"relations?rel=sales,orders&type=sale,order&linkTo=id_order_sale&equalTo=" . $_POST["idOrderPay"] . "&select=*",
			"GET",
			[]
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
				$productResponse = CurlController::request(
					"products?linkTo=id_product&equalTo=" . $sale->id_product_sale,
					"GET",
					[]
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
				}

				// Dar respuesta exitosa al vendedor
				echo '<script>
                        fncMatPreloader("off");
                        fncSweetAlert("success", "La órden #' . $salesResults[0]->transaction_order . ' ha sido completada con éxito", "/pos");
                        fncFormatInputs();
                      </script>';
			}
		}
	}

	// Actualiza la orden a través de la API
	private function updateOrder($idOrder, $fieldsData)
	{
		$url = "orders?id=" . $idOrder . "&nameId=id_order&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
		$fields = http_build_query($fieldsData);
		$response = CurlController::request($url, "PUT", $fields);
		return ($response->status === 200);
	}

	// Actualiza el estado de una venta
	private function updateSaleStatus($idSale, $status)
	{
		$url = "sales?id=" . $idSale . "&nameId=id_sale&token=" . $_SESSION["admin"]->token_admin . "&table=admins&suffix=admin";
		$fields = http_build_query(["status_sale" => $status]);
		$response = CurlController::request($url, "PUT", $fields);
		return ($response->status === 200);
	}

	private function processInvoice($salesResponse, $arrayProducts)
	{
		$xmlController = new xmlController();

		// Obtener información de la oficina
		$officesResponse = CurlController::request(
			"offices?select=*&linkTo=id_office&equalTo=" . $_SESSION["admin"]->id_office_admin,
			"GET",
			[]
		);
		if ($officesResponse->status !== 200) {
			$this->outputError("Error obteniendo información de la oficina");
			exit;
		}

		// Obtener información del cliente
		$clientsResponse = CurlController::request(
			"clients?linkTo=id_client&equalTo=" . $salesResponse->results[0]->id_client_order,
			"GET",
			[]
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
		$secuencialResponse = CurlController::request("secuencials", "POST", $secuencialFields);
		if (!isset($secuencialResponse->status) || $secuencialResponse->status !== 200 || !isset($secuencialResponse->results)) {
			$this->outputError("Error secuencial no obtenido");
			exit;
		}
		$siguienteSecuencial = $secuencialResponse->results;
		$siguienteSecuencialFormateado = str_pad($siguienteSecuencial, 9, "0", STR_PAD_LEFT);

		// Intentamos generar y firmar el XML
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
			$ruc = (string)$officesResponse->results[0]->dni_office;
			$archivoFirmado = $xmlController->firmarXML($xmlGenerado['numeroFactura'], $ruc);

			// Supongamos que $archivoFirmado tiene la ruta del XML firmado y $claveAcceso se obtiene en otro proceso o viene del XML
			$pythonScript = "C:/xampp/htdocs/facturaEC/facturacion-electronica/integrated.py";
			$command = escapeshellcmd("python " . $pythonScript . " --xml " . escapeshellarg($archivoFirmado) . " --clave " . escapeshellarg($xmlGenerado['claveAcceso']));
			$output = shell_exec($command);

			// Opcional: imprimir la salida para depuración
			echo "\nSalida de Python:\n" . $output;

			// Puedes procesar $output para determinar si la validación y autorización fueron exitosas





		} catch (Exception $e) {
			// Si hubo error en generar o firmar el XML, se cancela el proceso
			$this->outputError("Error al procesar factura: " . $e->getMessage());
			exit;
		}
	}

	// Función para mostrar errores de forma consistente
	private function outputError($message)
	{
		echo '<div class="alert alert-danger mt-3 p-3 rounded alertPos">' . $message . '</div>
              <script>
                fncMatPreloader("off");
                fncSweetAlert("close", "", "");
                fncFormatInputs();
              </script>';
	}
}
