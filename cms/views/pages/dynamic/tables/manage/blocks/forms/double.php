<?php if ($module->columns[$i]->type_column == "double" || $module->columns[$i]->type_column == "money"): ?>

<?php

$readonly = "";
//se imprime toda la información dinámica de la tabla
// echo '<pre>'; print_r($data); echo '</pre>';
// Array
// (
//     [id_cash] => 4
//     [start_cash] => 1500
//     [bills_cash] => 0
//     [money_cash] => 0
//     [diff_cash] => 0
//     [end_cash] => 0
//     [gap_cash] => 0
//     [status_cash] => 1
//     [date_start_cash] => 2025-03-03 14:03:00
//     [date_end_cash] => 0000-00-00 00:00:00
//     [id_admin_cash] => 1
//     [id_office_cash] => 1
//     [date_created_cash] => 2025-03-03
//     [date_updated_cash] => 2025-03-03 14:46:40
// )
if(!empty($data) && $routesArray[0] == "caja"){

	/*=============================================
	Dinero Inicial
	=============================================*/

	
	// if($module->columns[$i]->title_column == "start_cash" && $data[$module->columns[$i]->title_column] > 0){
	if($module->columns[$i]->title_column == "start_cash" ){
		//lo configuramos para que el dinero inicial no sea modificable
		$readonly = "readonly";
	}

	/*=============================================
	Gastos
	=============================================*/

	if($module->columns[$i]->title_column == "bills_cash"){

		$url = "bills?linkTo=date_created_bill,id_office_bill&equalTo=".date("Y-m-d").",".$_SESSION["admin"]->id_office_admin;
		$method = "GET";
		$fields = array();

		$bills = CurlController::request($url,$method,$fields);

		if($bills->status == 200){

			foreach ($bills->results as $key => $value) {
				
				$data[$module->columns[$i]->title_column] += $value->cost_bill;
			}

			$data[$module->columns[$i]->title_column] = $data[$module->columns[$i]->title_column] * -1;
		}

		$readonly = "readonly";

	}

	/*=============================================
	Ingresos en Efectivo
	=============================================*/

	if($module->columns[$i]->title_column == "money_cash"){

		$url = "orders?linkTo=date_created_order,id_office_order,method_order,status_order&equalTo=".date("Y-m-d").",".$_SESSION["admin"]->id_office_admin.",efectivo,Completada";
		$method = "GET";
		$fields = array();
	
		$orders = CurlController::request($url,$method,$fields);

		if($orders->status == 200){

			foreach ($orders->results as $key => $value) {
				
				$data[$module->columns[$i]->title_column] += $value->total_order;
			}

		}

		$readonly = "readonly";

	}

	/*=============================================
	Diferencia
	=============================================*/

	if($module->columns[$i]->title_column == "diff_cash"){

		$totalBills = 0;

		$url = "bills?linkTo=date_created_bill,id_office_bill&equalTo=".date("Y-m-d").",".$_SESSION["admin"]->id_office_admin;
		$method = "GET";
		$fields = array();

		$bills = CurlController::request($url,$method,$fields);

		if($bills->status == 200){

			foreach ($bills->results as $key => $value) {
				
				$totalBills += $value->cost_bill;
			}

		}

		$totalOrders  = 0;

		$url = "orders?linkTo=date_created_order,id_office_order,method_order,status_order&equalTo=".date("Y-m-d").",".$_SESSION["admin"]->id_office_admin.",efectivo,Completada";
		$method = "GET";
		$fields = array();

		$orders = CurlController::request($url,$method,$fields);

		if($orders->status == 200){

			foreach ($orders->results as $key => $value) {
				
				$totalOrders += $value->total_order;
			}

		}

		$data[$module->columns[$i]->title_column] = $data["start_cash"] + $totalOrders - $totalBills;

		$readonly = "readonly";

	}

	/*=============================================
	Brecha
	=============================================*/

	if($module->columns[$i]->title_column == "gap_cash"){

		$readonly = "readonly";
	}

}

if(	$routesArray[0] == "compras"){
	$readonly = "readonly";

}

?>

 	<input 
	type="number" 
	step="any"
	class="form-control rounded"
	id="<?php echo $module->columns[$i]->title_column ?>"
	name="<?php echo $module->columns[$i]->title_column ?>"
	value="<?php if (!empty($data)): ?><?php echo urldecode($data[$module->columns[$i]->title_column]) ?><?php endif ?>"
	<?php echo $readonly ?>>

	<script>
		
		$(document).on("change","#end_cash",function(){

			$("#gap_cash").val((Number($(this).val())-Number($("#diff_cash").val())).toFixed(2));
		
		})

	</script>
 	
<?php endif ?>
