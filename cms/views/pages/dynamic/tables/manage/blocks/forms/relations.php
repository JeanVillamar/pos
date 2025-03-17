<?php if ($module->columns[$i]->type_column == "relations"): ?>

	<?php

	/*=============================================
	Traemos todas las tablas
	=============================================*/

	require_once "controllers/install.controller.php";
	$tables = InstallController::getTables();

	?>

	<select
		class="form-select rounded mb-3 select2 changeRelations"
		idColumn="<?php echo $module->columns[$i]->id_column ?>">

		<?php if ($module->columns[$i]->matrix_column != null): ?>

			<option value="<?php echo $module->columns[$i]->matrix_column ?>"><?php echo $module->columns[$i]->matrix_column ?></option>

		<?php else: ?>

			<option value="">Seleccione Tabla</option>


		<?php endif	?>

		<?php foreach ($tables as $index => $item): ?>

			<option value="<?php echo $item ?>" <?php if (!empty($data) && $module->columns[$i]->matrix_column == $item): ?> selected <?php endif ?>><?php echo $item ?></option>

		<?php endforeach ?>


	</select>

	<div class="mb-3"></div>

	<select
		class="form-select rounded select2 selectRelations"
		name="<?php echo $module->columns[$i]->title_column ?>"
		id="<?php echo $module->columns[$i]->title_column ?>">

		<?php if ($module->columns[$i]->matrix_column != null): ?>

			<?php



			echo '<script>console.log(' . json_encode($_SESSION) . ');</script>';

			//filramos las relaciones que se hagan con la tabla admin y sucursales
			//pero antes se debe filtrar si es el superadmin, el admin o un vendedor que solo deben tener
			//acceso a su sucursal asignada
			if ($_SESSION['admin']->rol_admin == 'superadmin' || ($_SESSION['admin']->rol_admin == 'admin' && !isset($_SESSION['admin']->phone_office))) {
				$url = $module->columns[$i]->matrix_column; //como es el superadmin y un admin general se obtendra la data de todas las sucursales y usuarios



			} else {
				$idAdmin = $_SESSION['admin']->id_admin;
				//si es un usuario de una sucursal se obtendra la data de la sucursal a la que pertenece
				// echo '<script>';
				// echo 'console.log('.json_encode($module).');';
				// echo '</script>';
				$idOffice = $_SESSION['admin']->id_office_admin;
				$nombre_tabla = $module->columns[$i]->matrix_column;
				//{{endpoint}}admins?select=*&linkTo=id_admin&equalTo=3
				$url = $nombre_tabla
					. '?select=*&linkTo=id_' . substr($nombre_tabla, 0, -1)
					. '&equalTo=' . ${'id' . ucfirst(substr($nombre_tabla, 0, -1))};
				// echo '<script>console.log(' . json_encode($url) . ');</script>';
			}


			$method = "GET";
			$fields = array();

			$columnsTable = CurlController::request($url, $method, $fields);

			if ($columnsTable->status == 200) {

				$columnsTable = $columnsTable->results;

				echo '<script>console.log(' . json_encode($columnsTable) . ');</script>';
			} else {

				$columnsTable = array();
			}

			?>

			<?php if (!empty($columnsTable)): ?>


				<!-- Recorremos los resultados para mostrarlos como opciones en el select -->
				<?php foreach ($columnsTable as $index => $item): ?>

					<?php
					// Convertimos el item en un array para acceder fácilmente a sus claves y valores
					$itemArray = json_decode(json_encode($item), true);
					$firstKey = array_keys($itemArray)[0];
					$secondKey = array_keys($itemArray)[1];
					?>

					<option value="<?php echo $itemArray[$firstKey] ?>"
						<?php if (!empty($data) && $itemArray[$firstKey] == $data[$module->columns[$i]->title_column]): ?> selected <?php endif ?>>
						<?php echo $itemArray[$firstKey] ?> - <?php echo urldecode($itemArray[$secondKey]) ?>
					</option>

				<?php endforeach ?>

			<?php endif ?>

		<?php endif ?>


	</select>

<?php endif ?>