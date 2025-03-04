<?php

$limit = 6; // cantidad de registros a presentar 
$url = "relations?rel=products,categories&type=product,category&linkTo=id_office_product,status_product&equalTo=".$_SESSION["admin"]->id_office_admin.",1&orderBy=id_product&orderMode=DESC&startAt=0&endAt=".$limit;
$method = "GET";
$fields = array();

$products = CurlController::request($url,$method,$fields);

if($products->status == 200){

	$products = $products->results;	

	/*=============================================
	Traer Total de productos
	=============================================*/

	$url = "relations?rel=products,categories&type=product,category&linkTo=id_office_product,status_product&equalTo=".$_SESSION["admin"]->id_office_admin.",1";
	//existe una clave en el json que tiene la cantidad de resultados que retorna cual es total de registros
	$totalPageProducts = ceil(CurlController::request($url,$method,$fields)->total/$limit);//ceil redondea los decimales hacia el digito superior
	// echo '<pre>'; print_r($totalPageProducts); echo '</pre>';
}else{
	//Cabe mencionar que cuando es un un admin general, saldrá error dado que no hay productos asociados con id_office_admin = 0, debido a que
	$products = array();
}


?>
<!-- si hay productos se genera el HTML para mostrarlos -->
<?php if (!empty($products)): ?>

	<div class="row p-2 viewProducts">
		
		<?php foreach ($products as $key => $value): ?>
			<!-- se agrega la clase btn para que parezca el cursor, cada producto se muestra en una card -->
			<div class="col-12 col-lg-6 col-xl-4 p-2 btn addProductPos" idProduct='<?php echo $value->id_product?>'>
				
				
				<div class="card rounded border-0 position-relative">

					<?php if ($value->discount_product > 0): ?>

						<div class="position-absolute small bg-red p-1 shadow-sm rounded" style="top:4px; left:4px; font-size:10px"><?php echo $value->discount_product ?>% OFF</div>
						
					<?php endif ?>
					
					<div class="position-absolute small bg-white p-1 shadow-sm rounded" style="top:4px; right:4px; font-size:10px"><?php echo $value->sku_product ?></div>

					<img src="<?php echo urldecode($value->img_product) ?>" class="card-img-top px-5 py-3 mx-auto" style="width:200px !important">

					<div class="card-body">
						
						<h6 class="font-weight-bold text-gray samll"><?php echo urldecode($value->title_category) ?></h6>
						<h6 class="card-title pb-2 font-weight-bold"><?php echo urldecode($value->title_product) ?></h6>

						<div class="d-flex justify-content-between">

							<?php 
							// calculo del stock con colores dinámicos
							if($value->stock_product < 50){

								$colorStock = "bg-maroon";
							}

							if($value->stock_product >= 50 && $value->stock_product < 100){

								$colorStock = "bg-indigo";
							}

							if($value->stock_product >= 100){

								$colorStock = "bg-teal";
							}

							?>

							<div class="card-text small h6 badge badge-default pb-0 <?php echo $colorStock  ?>" style="font-size:10px; padding-top:6px">
								
								<?php echo $value->stock_product ?>

							</div>

							<?php 
							// obtener el precio del producto segun la id del producto
							$url = "purchases?linkTo=id_product_purchase&equalTo=".$value->id_product."&select=price_purchase";

							$price = CurlController::request($url,$method,$fields);

							if($price->status == 200){

								$price = $price->results[0]->price_purchase;
								//si el producto tiene descuento, se calcula el precio con descuento, si el precio no tiene precio se asigna $0
								if($value->discount_product > 0){

									$discount = $price-($price*($value->discount_product/100));
								}

							}else{
								$price = 0;
							}

							?>
							<!-- se muestra el precio normal o con descuento, si hay descuento se tacha el precio original (<s>) y se muestra el nuevo -->
							<?php if ($value->discount_product > 0): ?>

								<span class="small ms-auto pe-1 h6 mt-1 text-red font-weight-bold" style="font-size:12px"><s>$ <?php echo number_format($price,2) ?></s></span>


								<div class="small h6 mt-1 textColor font-weight-bold"><strong>$ <?php echo number_format($discount,2) ?></strong></div>

							<?php else: ?>

								<div class="small h6 mt-1 textColor font-weight-bold"><strong>$ <?php echo number_format($price,2) ?></strong></div>

							<?php endif ?>

						</div>

					</div>

				</div>
			</div>
			
		<?php endforeach ?>

	</div>
	<!-- si se tiene mas de una pagina se agrega el boton -->
	<?php if ($totalPageProducts > 1): ?>

		<div id="loadPageProducts" class="d-flex justify-content-center mb-5">	
			<div><button class="btn btn-sm rounded bg-blue px-3 py-2">Cargar más productos</button></div>
		</div>
		
	<?php endif ?>
	<!-- inputs ocultos para trabajar en el archivo pos.js -->
	<input type="hidden" id="totalPagesProducts" value="<?php echo $totalPageProducts ?>">
	<input type="hidden" id="currentPageProducts" value="1">
	<input type="hidden" id="limitProduct" value="<?php echo $limit ?>">
	<input type="hidden" id="idOffice" value="<?php echo $_SESSION["admin"]->id_office_admin ?>">
	<input type="hidden" id="filterByCategory" value="all">

<?php else: ?>

	<div class="row p-2 my-5 text-center">
		
		<?php include "svg.php" ?>

		<p>No hay productos agregados a esta Sucursal</p>

	</div>
	
<?php endif ?>