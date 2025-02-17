<?php

require_once "../controllers/curl.controller.php";

class PosController{

	/*=============================================
	Función para cargar productos
	=============================================*/

	public $limit;
	public $startAt;
	public $category;
	public $search;
	public $idOffice;	

	public function loadProducts(){

		if($this->category == "all"){

			if($this->search == ""){

				$url = "relations?rel=products,categories&type=product,category&linkTo=id_office_product,status_product&equalTo=".$this->idOffice.",1&orderBy=id_product&orderMode=DESC&startAt=".$this->startAt."&endAt=".$this->limit;
				$method = "GET";
				$fields = array();

				$products = CurlController::request($url,$method,$fields);

				if($products->status == 200){

					$products = $products->results;	

					/*=============================================
					Traer Total de productos
					=============================================*/

					$url = "relations?rel=products,categories&type=product,category&linkTo=id_office_product,status_product&equalTo=".$this->idOffice.",1";

					$totalPageProducts = ceil(CurlController::request($url,$method,$fields)->total/$this->limit);

				}else{

					$products = array();
					$totalPageProducts = 0;
				}

			}else{

				/*=============================================
				Columnas de búsqueda
				=============================================*/

				$linkTo = ["sku_product","title_product"];

				/*=============================================
				Itineración de búsqueda
				=============================================*/

				foreach ($linkTo as $key => $value) {
					
					$url = "relations?rel=products,categories&type=product,category&linkTo=".$value.",id_office_product,status_product&search=".str_replace(" ", "_",$this->search).",".$this->idOffice.",1&orderBy=id_product&orderMode=DESC&startAt=".$this->startAt."&endAt=".$this->limit;

					$method = "GET";
					$fields = array();

					$products = CurlController::request($url,$method,$fields);

					if($products->status == 200){

						$products = $products->results;

						/*=============================================
						Traer Total de productos
						=============================================*/

						$url = "relations?rel=products,categories&type=product,category&linkTo=".$value.",id_office_product,status_product&search=".str_replace(" ", "_",$this->search).",".$this->idOffice.",1";

						$totalPageProducts = ceil(CurlController::request($url,$method,$fields)->total/$this->limit);
					
						break;
					
					}else{

						$products = array();
						$totalPageProducts = 0;

					}
				}

			}

		}else{

			$url = "relations?rel=products,categories&type=product,category&linkTo=id_office_product,status_product,id_category_product&equalTo=".$this->idOffice.",1,".$this->category."&orderBy=id_product&orderMode=DESC&startAt=".$this->startAt."&endAt=".$this->limit;
			$method = "GET";
			$fields = array();

			$products = CurlController::request($url,$method,$fields);

			if($products->status == 200){

				$products = $products->results;	

				/*=============================================
				Traer Total de productos
				=============================================*/

				$url = "relations?rel=products,categories&type=product,category&linkTo=id_office_product,status_product,id_category_product&equalTo=".$this->idOffice.",1,".$this->category;

				$totalPageProducts = ceil(CurlController::request($url,$method,$fields)->total/$this->limit);

			}else{

				$products = array();
				$totalPageProducts = 0;
			}

		}

		$htmlProducts = "";

		if (!empty($products)){
		
			foreach ($products as $key => $value){

				$htmlProducts .= '<div class="col-12 col-lg-6 col-xl-4 p-2 btn">
					
					<div class="card rounded border-0 position-relative">';

						if ($value->discount_product > 0){

							$htmlProducts .= '<div class="position-absolute small bg-red p-1 shadow-sm rounded" style="top:4px; left:4px; font-size:10px">'.$value->discount_product.'% OFF</div>';
							
						}
						
						$htmlProducts .= '<div class="position-absolute small bg-white p-1 shadow-sm rounded" style="top:4px; right:4px; font-size:10px">'.$value->sku_product.'</div>

						<img src="'.urldecode($value->img_product).'" class="card-img-top px-5 py-3 mx-auto" style="width:200px !important">

						<div class="card-body">
							
							<h6 class="font-weight-bold text-gray samll">'.urldecode($value->title_category).'</h6>
							<h6 class="card-title pb-2 font-weight-bold">'.urldecode($value->title_product).'</h6>

							<div class="d-flex justify-content-between">';

								if($value->stock_product < 50){

									$colorStock = "bg-maroon";
								}

								if($value->stock_product >= 50 && $value->stock_product < 100){

									$colorStock = "bg-indigo";
								}

								if($value->stock_product >= 100){

									$colorStock = "bg-teal";
								}

								$htmlProducts .= '<div class="card-text small h6 badge badge-default pb-0 '.$colorStock .'" style="font-size:10px; padding-top:6px">
									
									'.$value->stock_product.'

								</div>';

								$url = "purchases?linkTo=id_product_purchase&equalTo=".$value->id_product."&select=price_purchase";

								$price = CurlController::request($url,$method,$fields);

								if($price->status == 200){

									$price = $price->results[0]->price_purchase;

									if($value->discount_product > 0){

										$discount = $price-($price*($value->discount_product/100));
									}

								}else{

									$price = 0;
								}

								if ($value->discount_product > 0){

									$htmlProducts .= '<span class="small ms-auto pe-1 h6 mt-1 text-red font-weight-bold" style="font-size:12px"><s>$ '.number_format($price,2).'</s></span>


									<div class="small h6 mt-1 textColor font-weight-bold"><strong>$ '.number_format($discount,2).'</strong></div>';

								}else{

									$htmlProducts .= '<div class="small h6 mt-1 textColor font-weight-bold"><strong>$ '.number_format($price,2).'</strong></div>';

								}

							$htmlProducts .= '</div>

						</div>

					</div>
				</div>';
				
			}

		}

		$response = array(
			"htmlProducts" => $htmlProducts,
			"totalPagesProducts" => $totalPageProducts
		);

		echo json_encode($response);

	}	

}

/*=============================================
Función para cargar productos
=============================================*/

if(isset($_POST["limit"])){

	$ajax = new PosController();
	$ajax -> limit = $_POST["limit"];
	$ajax -> startAt = $_POST["startAt"];
	$ajax -> category = $_POST["category"];
	$ajax -> search = $_POST["search"];
	$ajax -> idOffice = $_POST["idOffice"];
	$ajax -> loadProducts();

}
