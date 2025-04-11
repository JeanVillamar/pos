<?php 
require_once 'xml.controller.php';

class OrdersController{

	/*=============================================
	Gestionar Órdenes
	=============================================*/

	public function manageOrder(){

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
				echo "<script>console.log('$url');</script>";

				$getSales = CurlController::request($url,$method,$fields);

				if($getSales->status == 200){
					//Obtenemos la cantidad de ventas que se han actualizado
					$countSales = 0;
					
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
							//cuando estemos en la última iteración de ventas, se ejecuta el siguiente bloque de código
							if($countSales == count($getSales->results)){

								//ENVIAR INFO DE FACTURA AL SRI
								$controller = new xmlController();
								try {
									$archivo = $controller->generarXMLComprobante($getSales, 'factura_001', './xml/facturas_no_firmadas/');
									echo "<script>console.log('" . json_encode($_SESSION['admin']) . "');</script>";
									
								} catch (Exception $e) {
									echo "Error al generar XML: " . $e->getMessage();
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