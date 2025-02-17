<!-- ?php
	echo '<pre>$_SESSION';
	print_r($_SESSION['admin']);
	echo '<pre>';

?> -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom d-lg-flex justify-content-lg-between">
					
	<div>
		<button class="btn btn-default border-0" id="menu-toggle">
			<i class="bi bi-list"></i>
		</button>
	</div>

	<div class="d-flex">

		<div class="p-2">

			<?php if ($_SESSION["admin"]->id_office_admin > 0): ?>
				<!-- La propiedad phone_office solo se asigna cuando un usuario está asociado a una sucursal específica. Esta información se almacena en 
				 la variable de sesión dentro de admins.controller.
				Si el usuario es multi-sucursal, no se le asocian parámetros relacionados con una sucursal en particular.
				Esta condición es necesaria porque, cuando un administrador general selecciona una sucursal, id_office_admin cambia a un valor mayor que 0.
				Si posteriormente desea volver a operar en modo multi-sucursal, podemos identificar que tiene acceso a múltiples sucursales porque no tiene la propiedad phone_office asociada en la sesión. -->
				<?php if (!isset($_SESSION["admin"]->phone_office)): ?>

					<?php if (isset($_GET["offices"])): ?>

						<a href="#myOffices" data-bs-toggle="modal" class="badge badge-default backColor small rounded py-2 px-3"><?php echo urldecode(explode("_",$_GET["offices"])[1]) ?></a>

					<?php else: ?>

						<a href="#myOffices" data-bs-toggle="modal" class="badge badge-default backColor small rounded py-2 px-3"><?php echo urldecode($_SESSION["admin"]->title_office) ?></a>

					<?php endif ?>

				<?php else: ?>

					<span class="badge badge-default backColor small rounded py-2 px-3"><?php echo urldecode($_SESSION["admin"]->title_office) ?></span>

				<?php endif ?>

			<?php else: ?>

				<?php if (isset($_GET["offices"])): ?>

					<a href="#myOffices" data-bs-toggle="modal" class="badge badge-default backColor small rounded py-2 px-3"><?php echo urldecode(explode("_",$_GET["offices"])[1]) ?></a>

				<?php else: ?>

					<a href="#myOffices" data-bs-toggle="modal" class="badge badge-default backColor small rounded py-2 px-3">Multi-Sucursal</a>
					
				<?php endif ?>


			<?php endif ?>

			<a href="#myProfile" class="ms-2" data-bs-toggle="modal" style="color:inherit;">
				<i class="bi bi-person-circle"></i>
				<?php echo urldecode($_SESSION["admin"]->name_admin) ?>
			</a>

		</div>

		<div class="p-2 mx-2">
			
			<a href="/logout" class="text-dark">				
				<i class="bi bi-box-arrow-right"></i>
			</a>

		</div>

	</div>

</nav>