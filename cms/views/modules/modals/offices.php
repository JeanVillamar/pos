<?php
//obtener las sucursales de la base de datos 
$urlOffices = "offices?select=id_office,title_office";
$method = "GET";
$fields = array(); 

$offices = CurlController::request($urlOffices,$method,$fields);

if($offices->status == 200){

	$offices = $offices->results;

}else{

	$offices = array();
}

?>

<!-- The Modal -->
 <!-- la clase fade es necesaria para trabajar con js -->
<div class="modal fade" id="myOffices">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content rounded">

			<form method="GET">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Elegir Sucursal</h4>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					
					<div class="form-group mb-3">
							
							<select 
							class="form-select rounded"
							name="offices" 
							id="offices">
								
								<option value="">Elige Sucursal</option>

								<?php if (!empty($offices)): ?>

									<?php foreach ($offices as $key => $value): ?>
										<!-- El valor tiene el formato id_office_title_office (ejemplo: "3_Sucursal Centro"). para asi ser enviado al navbar y poder etraer el nombre de la sucursal -->
										<option value="<?php echo $value->id_office ?>_<?php echo urldecode($value->title_office) ?>"><?php echo urldecode($value->title_office) ?></option>
										
									<?php endforeach ?>
									
								<?php endif ?>
								<!-- cuando el id_office_admin es igual a 0 quiere decir que es un usuario multisucursal, se hace de esta forma porque
								 al momento de que el usario multisucursal seleccionar una sucursal se le asociado a este parametro por lo tanto si quiere
								 volver a multisucursal lo podra hacer. -->
								<?php if ($_SESSION["admin"]->id_office_admin > 0): ?>

									<option value="0_Multi-Sucursal">Multi-Sucursal</option>
									
								<?php endif ?>

							</select>

					</div>

				</div>

				<!-- Modal footer -->
				<div class="modal-footer d-flex justify-content-betwween">
					<div><button type="button" class="btn btn-dark rounded" data-bs-dismiss="modal">Cerrar</button></div>
					<!-- 2️⃣ Cuando el usuario presiona "Guardar", el formulario GET envía la selección en la URL, ej:
						http://cms.pos.com/pagina.php?offices=3_Sucursal%20Centro -->
					<div><button type="submit" class="btn btn-default backColor rounded" >Guardar</button></div>
				</div>

			</form>

		</div>
	</div>
</div>