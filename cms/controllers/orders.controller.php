<?php 
require_once 'xml.controller.php';

class OrdersController{

	/*=============================================
	Gestionar Órdenes
	=============================================*/

	public function manageOrder(){
		// echo '<pre>';
		// print_r($_SESSION);
		// echo '</pre>';

		if(isset($_POST["idOrderPay"])){

			echo '<script>
				fncMatPreloader("on");
				fncSweetAlert("loading", "Procesando la orden...", "");
			</script>';

			$url = "orders?id=".$_POST["idOrderPay"]."&nameId=id_order&token=".$_SESSION["admin"]->token_admin."&table=admins&suffix=admin";
			$method = "PUT";
			$fields = array(
				"method_order" => $_POST["methodPay"],
				"transfer_order" => $_POST["transferPay"],
				"status_order" => "Completada"
			);

			$fields = http_build_query($fields);

			$updateOrder = CurlController::request($url,$method,$fields);

			if($updateOrder->status == 200){

				/*=============================================
				Actualizar las ventas como completadas
				=============================================*/

				$url = "relations?rel=sales,orders&type=sale,order&linkTo=id_order_sale&equalTo=".$_POST["idOrderPay"]."&select=*";
				$method = "GET";
				$fields = array();
				

				$getSales = CurlController::request($url,$method,$fields);

				if($getSales->status == 200){
					//Obtenemos la cantidad de ventas que se han actualizado
					$countSales = 0;
					$arrayProducts = array();
					foreach ($getSales->results as $key => $value) {

						$url = "sales?id=".$value->id_sale."&nameId=id_sale&token=".$_SESSION["admin"]->token_admin."&table=admins&suffix=admin";
						$method = "PUT";
						$fields = array(
							"status_sale" => "Completada"
						);

						$fields = http_build_query($fields);

						$updateSale = CurlController::request($url,$method,$fields);

						if($updateSale->status == 200){

							$countSales ++;

							/*============================================= información de los productos =============================================*/
							$url = "products?linkTo=id_product&equalTo=" . $value->id_product_sale;
							$method = "GET";
							$fields = array();
							$getProducts = CurlController::request($url, $method, $fields);
							if ($getProducts->status == 200) {
								$product = $getProducts->results[0];
							}

							$id_product = $product->id_product;

							$arrayProducts[$id_product] = array(
								"id_product"      => $id_product,
								"sku_product"      => $product->sku_product,
								"title_product"    => urldecode($product->title_product),
								"discount_product" => $product->discount_product,
								"tax_product"      => $product->tax_product,
								"unit_product"     => $product->unit_product
							);


							

							//cuando estemos en la última iteración de ventas, se ejecuta el siguiente bloque de código
							if($countSales == count($getSales->results)){
																
								
								
						
								// validar si el cliente es facturador, la cual se valida cuando el usuario da click en el check (modals.php)
								if(isset($_POST["clientInvoice"]) && $_POST["clientInvoice"] == "yes"){
									$controller = new xmlController();

									/*============================================= información de la oficina =============================================*/
									$url = "offices?select=*&linkTo=id_office&equalTo=" . $_SESSION["admin"]->id_office_admin;
									$method = "GET";
									$fields = array();
									$getoffices = CurlController::request($url, $method, $fields);

									/*============================================= información del cliente =============================================*/
									$url = "clients?linkTo=id_client&equalTo=".$getSales->results[0]->id_client_order;
									$method = "GET";
									$fields = array();
									$getClients = CurlController::request($url,$method,$fields);


									$url = "secuencials";
									$fields = array(
										// Asegúrate que estos valores coincidan con los que tienes
										// en id_office_secuencial y id_admin_secuencial
										"oficina_secuencial" => $_SESSION["admin"]->id_local_office, // OFICINA LOCAL
										"caja_secuencial" => $_SESSION["admin"]->cash_admin,    //NUMERO DE CAJA
										"office_secuencial" => $getoffices->results[0]->id_office  //IF OFICINA REAL
									);

									$response = CurlController::request("secuencials", "POST", $fields); // La ruta es correcta


									if (isset($response->status) && $response->status == 200 && isset($response->results)) {
										$siguienteSecuencial = $response->results;
										echo "\nSiguiente Secuencial Obtenido: " . $siguienteSecuencial;
										// ¡Usar este número formateado!
									} else {
										echo '<div class="alert alert-danger mt-3 p-3 rounded alertPos">Error secuencial no obtenido </div>
									<script>

										fncMatPreloader("off");
										fncSweetAlert("close", "", "");
										fncFormatInputs();
									
									</script>';
										return;
									}

									
									$secuencial = str_pad($siguienteSecuencial, 9, "0", STR_PAD_LEFT);

									
									try{
										$xmlGenerado = $controller->generarXMLComprobante($getSales, $getoffices, './xml/facturas_no_firmadas', $arrayProducts, $getClients, $secuencial);
										$ruc = (string)$getoffices->results[0]->dni_office;
										echo "✅ XML generado correctamente en: $xmlGenerado\n";
										$archivoFirmado = $controller->firmarXML($xmlGenerado, $ruc);
										echo "✅ Firmado correctamente en: $archivoFirmado";
										

									}catch (Exception $e) {
										echo'<div class="alert alert-danger mt-3 p-3 rounded alertPos">'.$e.'</div>
										<script>
											fncMatPreloader("off");
											fncSweetAlert("close", "", "");
											fncFormatInputs();									
										</script>
										';
									}									
								}
								
								


								/*=============================================
								Abrimos cajón Monedero
								=============================================*/


								/*=============================================
								Imprimos el Ticket
								=============================================*/


								/*=============================================
								Devolvemos respuesta al vendedor
								=============================================*/

								echo '

								<script>

									fncMatPreloader("off");
									fncSweetAlert("success", "La órden #'.$getSales->results[0]->transaction_order.' ha sido completada con éxito", "/pos");
									fncFormatInputs();
								 
								</script>

								';


							}



						}

					}

				}


			}else{

				echo'<div class="alert alert-danger mt-3 p-3 rounded alertPos">Error al procesar la orden</div>

				<script>

					fncMatPreloader("off");
					fncSweetAlert("close", "", "");
					fncFormatInputs();
				 
				</script>

				';

			}

		}

	}

}