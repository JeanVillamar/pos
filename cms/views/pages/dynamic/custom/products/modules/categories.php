
<?php

/*=============================================
Traer categorías desde la BD
=============================================*/
//la url trae todas las categorías activas de la BD
//hay que tener en cuenta que para que funcione correctamente el jdslider debe haber una cantidad mínima para funcionar correctamente el cual depende de 'slideShow'
// $url = "categories?linkTo=status_category,id_office_category&equalTo=1," . $_SESSION["admin"]->id_office_admin;
$url = "categories?linkTo=status_category&equalTo=1";

$method = "GET";
$fields = array();

$categories = CurlController::request($url, $method, $fields);

if ($categories->status == 200) {

	$categories = $categories->results;
	//filtración agregada debido a que las categorias con id_office_category = 0 son categorias generales y no pertenecen a ninguna sucursal,
	// además de que las categorias con id_office_category = $_SESSION["admin"]->id_office_admin son las categorias que pertenecen a la sucursal del usuario
	$categories = array_filter($categories, function ($category) {
		return $category->id_office_category == 0 || $category->id_office_category == $_SESSION["admin"]->id_office_admin;
	});

	// Reindexar el array para evitar claves numéricas salteadas
	$categories = array_values($categories);
} else {

	$categories = array();
}
// echo "<pre>";
// print_r($categories);
// echo "</pre>";
?>



<!--===================================
JD SLIDER
=====================================-->

<div class="jd-slider mb-0 pb-0">

	<div class="slide-inner">

		<ul class="slide-area">

			<?php if (!empty($categories)): ?>

				<li>

					<div class="border-0 rounded text-center bg-white mx-1 p-3 pb-0 loadCategory" idCategory="all">

						<img src="http://cms.pos.com/views/assets/files/67aa9cde270846.png" class="img-fluid mx-auto" style="width:50px; cursor:pointer">
						<p class="pt-2 mb-0 lead" style="cursor:move"><strong>Todo</strong></p>

						<?php
						//en caso de que no tenga una sucursal asignada se colocará que se tiene 0 preuctos en el catalogo
						if ($_SESSION['admin']->id_office_admin > 0) {
							//traer todos los id productos activos
							//$url = "products?linkTo=status_product,id_office_product&equalTo=1,".$_SESSION["admin"]->id_office_admin."&select=id_product";
							$url = "products?linkTo=status_product,id_office_product&equalTo=1," . $_SESSION["admin"]->id_office_admin . "&select=id_product";
							$totalProducts = CurlController::request($url, $method, $fields)->total;
							// $response = CurlController::request($url,$method,$fields);
							// Verificar la respuesta antes de acceder a 'total'
							// echo "<pre>";
							// print_r($response);
							// echo "</pre>";
						} else {
							$totalProducts = 0;
						}



						?>

						<p class="small pb-3" style="cursor:move"><?php echo $totalProducts ?> items</p>

					</div>


				</li>


				<?php foreach ($categories as $key => $value): ?>
					<li>

						<div class="border-0 rounded text-center bg-white mx-1 p-3 pb-0 loadCategory" idCategory="<?php echo $value->id_category ?>">

							<img src="<?php echo urldecode($value->img_category) ?>" class="img-fluid mx-auto" style="width:50px; cursor:pointer">
							<p class="pt-2 mb-0 lead" style="cursor:move"><strong><?php echo urldecode($value->title_category) ?></strong></p>

							<?php
							// Determinar la cantidad de productos en el catálogo según la sucursal asignada
							$totalProducts = 0;

							if ($_SESSION['admin']->id_office_admin > 0) {
								$url = "products?linkTo=id_category_product,status_product,id_office_product&equalTo="
									. $value->id_category . ",1," . $_SESSION["admin"]->id_office_admin . "&select=id_product";

								$response = CurlController::request($url, $method, $fields);

								if ($response->status !== 404) {
									$totalProducts = $response->total;

								}
							}

							?>

							<p class="small pb-3" style="cursor:move"><?php echo $totalProducts ?> items</p>

						</div>
					</li>

				<?php endforeach ?>

			<?php endif ?>


		</ul>

		<a href="#" class="prev ps-1">
			<i class="bi bi-chevron-left"></i>
		</a>

		<a href="#" class="next ps-1">
			<i class="bi bi-chevron-right"></i>
		</a>

	</div>

	<div class="controller d-none">
		<div class="indicate-area"></div>
	</div>

</div>