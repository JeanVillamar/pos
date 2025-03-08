<?php 

$xAxis = array();
$yAxis = array();

$content = json_decode($module->content_module);
// echo '<pre>'; print_r($content); echo '</pre>';
// stdClass Object
// (
//     [type] => bar
//     [table] => orders
//     [xAxis] => date_created_order
//     [yAxis] => total_order
//     [color] => 134, 153, 163
// )
echo '<pre>'; print_r($module); echo '</pre>';
// stdClass Object
// (
//     [id_module] => 28
//     [id_page_module] => 13
//     [type_module] => graphics
//     [title_module] => gráfico de ventas diarias
//     [suffix_module] => 
//     [content_module] => {"type":"bar","table":"orders","xAxis":"date_created_order","yAxis":"total_order","color":"134, 153, 163"}
//     [width_module] => 100
//     [editable_module] => 1
//     [date_created_module] => 2025-01-02
//     [date_updated_module] => 2025-01-02 16:28:51
//     [id_page] => 13
//     [title_page] => Informes
//     [url_page] => informes
//     [icon_page] => bi bi-file-earmark-bar-graph
//     [type_page] => modules
//     [order_page] => 1000
//     [date_created_page] => 2025-01-02
//     [date_updated_page] => 2025-01-02 16:06:15
// )
$suffix = explode("_",$content->yAxis);
$suffix = end($suffix);

if($_SESSION["admin"]->id_office_admin > 0){
	// se ingresa a la página del módulo de informes que es el 13 y el que tenga el título de gráfico de ventas(aunque también se puedo haber usado el id_module) , 
	// además de filtrar por la sucursal del usuario, se filtra por el estado de la orden.
	if($module->title_module == "gráfico de ventas diarias" && $module->id_page_module == 13){
		//se accede a la tabla de orders y se filtra por la sucursal del usuario y el estado de la orden.
		//además se agrega la selección de los valores que se van a mostrar en el eje X(date_created_order) y Y(total_order).
		$url = $content->table."?linkTo=status_order,id_office_order&equalTo=Completada,".$_SESSION["admin"]->id_office_admin."&select=".$content->xAxis.",".$content->yAxis;

	}else if($module->title_module == "ventas por sucursal" || $module->title_module == "compras por sucursal"){

		$content->xAxis = "title_office";
		//se filtra por la relación entre la tabla de orders y la tabla de offices, además de filtrar por la sucursal del usuario.
		$url = "relations?rel=".$content->table.",offices&type=".$suffix.",office&linkTo=id_office_".$suffix."&equalTo=".$_SESSION["admin"]->id_office_admin."&select=".$content->xAxis.",".$content->yAxis;

	}else{

		$url = $content->table."?linkTo=id_office_".$suffix."&equalTo=".$_SESSION["admin"]->id_office_admin."&select=".$content->xAxis.",".$content->yAxis;
	}

}else{//cuando es multisucursal, se toma la lista de todas las sucursales y se filtra por el estado de la orden.

	if($module->title_module == "gráfico de ventas diarias" && $module->id_page_module == 13){

		$url = $content->table."?linkTo=status_order&equalTo=Completada&select=".$content->xAxis.",".$content->yAxis;

	}else if($module->title_module == "ventas por sucursal" || $module->title_module == "compras por sucursal"){

		$content->xAxis = "title_office";

		$url = "relations?rel=".$content->table.",offices&type=".$suffix.",office&select=".$content->xAxis.",".$content->yAxis;

	}else{

		$url = $content->table."?select=".$content->xAxis.",".$content->yAxis;
	}

}

$method = "GET";
$fields = array();

$response = CurlController::request($url,$method,$fields);
// echo '<pre>'; print_r($response); echo '</pre>';
// stdClass Object
// (
//     [status] => 200
//     [total] => 3
//     [results] => Array
//         (
//             [0] => stdClass Object
//                 (
//                     [date_created_order] => 2024-11-20
//                     [total_order] => 6607.52
//                 )

//             [1] => stdClass Object
//                 (
//                     [date_created_order] => 2024-12-25
//                     [total_order] => 997.93
//                 )

//             [2] => stdClass Object
//                 (
//                     [date_created_order] => 2024-12-27
//                     [total_order] => 617.25
//                 )

//         )

// )
//el response varía según el tipo de gráfico que se esté mostrando, en el caso del ejemplo se muestra el gráfico de ventas diarias.
if($response->status == 200){

	$graphic = $response->results;

	foreach (json_decode(json_encode($graphic),true) as $index => $item) {
		if($module->title_module == "gráfico de ventas mensuales"){
			//eliminarmos el día de la fecha para obtener solo el mes y año.
			array_push($xAxis, substr($item[$content->xAxis],0,-3));	
			$yAxis[substr($item[$content->xAxis],0,-3)] = 0;
			// echo '<pre>'; print_r($xAxis); echo '</pre>';
			// echo '<pre>'; print_r($yAxis); echo '</pre>';
			// Array
			// (
			// 	[0] => 2024-10
			// 	[1] => 2024-11
			// 	[2] => 2024-12
			// 	[3] => 2024-12
			// )
			// Array
			// (
			// 	[2024-10] => 0
			// 	[2024-11] => 0
			// 	[2024-12] => 0
			// )
		
		}else{
			// array_push() es una función de PHP que agrega uno o más elementos al final de un array, en este caso se agrega el valor al array $xAxis.
			array_push($xAxis, $item[$content->xAxis]);
			
			//La clave del array $yAxis será el mismo valor usado para el eje X ($item[$content->xAxis]), y el valor asociado se establece en 0.
			$yAxis[$item[$content->xAxis]] = 0;
			// echo '<pre>'; print_r($xAxis); echo '</pre>';
			// echo '<pre>'; print_r($yAxis); echo '</pre>';
			// Array
			// (
			// 	[0] => 2024-11-20
			// 	[1] => 2024-12-25
			// 	[2] => 2024-12-27
			// )
			// Array
			// (
			// 	[2024-11-20] => 0
			// 	[2024-12-25] => 0
			// 	[2024-12-27] => 0
			// )
		}
	
	}
	// se eliminan los valores duplicados del eje X, en el eje Y no es necesario este proceso debido a que el eje Y agrupa automáticamente los valores en el mismo índice(DEBIDO A QUE LAS LLAVES NO PUEDEN SER DUPLICADAS), 

	$xAxis = array_values(array_unique($xAxis));

	foreach (json_decode(json_encode($graphic),true) as $index => $item) {
		
		for($i = 0; $i < count($xAxis); $i++){

			if($module->title_module == "gráfico de ventas mensuales"){

				if($xAxis[$i] == substr($item[$content->xAxis],0,-3)){

					$yAxis[substr($item[$content->xAxis],0,-3)] +=  $item[$content->yAxis];
					
				}

			}else{

				if($xAxis[$i] == $item[$content->xAxis]){
					//se agrega el valor del eje Y al valor correspondiente del eje X en el array $yAxis
					$yAxis[$item[$content->xAxis]] +=  $item[$content->yAxis];
					
				}
			}
		}

	}

}

?>

<div class="<?php if ($module->width_module == "100"): ?> col-lg-12 <?php endif ?><?php if ($module->width_module == "75"): ?> col-lg-9 <?php endif ?><?php if ($module->width_module == "50"): ?> col-lg-6 <?php endif ?><?php if ($module->width_module == "33"): ?> col-lg-4 <?php endif ?><?php if ($module->width_module == "25"): ?> col-lg-3 <?php endif ?> col-12 mb-3 position-relative">

	<?php if ($_SESSION["admin"]->rol_admin == "superadmin"): ?>

		<div class="position-absolute border rounded bg-white" style="top:0px; right:12px; z-index:100">
			
			<button type="button" class="btn btn-sm text-muted rounded m-0 px-1 py-0 border-0 myModule" item='<?php echo json_encode($module) ?>' idPage="<?php echo $page->results[0]->id_page ?>">
				<i class="bi bi-pencil-square"></i>
			</button>

			<button type="button" class="btn btn-sm text-muted rounded m-0 px-1 py-0 border-0 deleteModule" idModule=<?php echo base64_encode($module->id_module) ?> >
				<i class="bi bi-trash"></i>
			</button>


		</div>
		
	<?php endif ?>

	
	<div class="card rounded">
		
		<div class="card-header bg-white rounded-top h4 font-weight-bold text-capitalize py-3">
			<?php echo $module->title_module ?>
		</div>

		<div class="card-body p-4">
			<canvas id="chart-<?php echo str_replace(" ","_",$module->title_module) ?>" height="500"></canvas>
		</div>

	</div>

</div>

<script>
	
if($("#chart-<?php echo str_replace(" ","_",$module->title_module) ?>").length > 0){

	var graphicChart = $("#chart-<?php echo str_replace(" ","_",$module->title_module) ?>");
	var tagsChart = new Chart(graphicChart, {

		type: "<?php echo $content->type ?>",
		data: {
			labels:[

				<?php 

					foreach ($xAxis as $index => $item){

						echo "'".urldecode($item)."',";

					}

				?>

				],
			datasets:[
				{
					backgroundColor: 'rgba(<?php echo $content->color ?>,.55)',
					borderColor: 'rgb(<?php echo $content->color ?>)',
					data: [

						<?php 

							foreach ($xAxis as $index => $item){

								echo "'".$yAxis[$item]."',";

							}

						?>


					]
				}
			]
		},//close data
		options: {
	        maintainAspectRatio: false,
	        tooltips: {
	          mode: 'index',
	          intersect: true
	        },
	        hover: {
	          mode: 'index',
	          intersect: true
	        },
	        legend: {
	          display: false
	        },
	        scales: {
	        	yAxes: [{
        		 	display: true,
		            gridLines: {
		              display: true
		            },
		            ticks: $.extend({
         			  beginAtZero: true,
		              // Include a dollar sign in the ticks
		              callback: function (value) {
		                if (value >= 1000) {
		                  value /= 1000
		                  value += 'k'
		                }

		                return  value
		              }
		            }, 
		            {
	                  fontColor: '#495057',
	                  fontStyle: 'bold'
            		})

            	}],
            	xAxes: [{
		            display: true,
		            gridLines: {
		              display: true
		            },
		            ticks: {
	                  fontColor: '#495057',
	                  fontStyle: 'bold'
	                }
	          	}]

	        }//close scales

	    }//close options

	})
}

</script>