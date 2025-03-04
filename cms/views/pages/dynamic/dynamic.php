<?php 

/*=============================================
Asignar sucursal a un administrador general
=============================================*/

if($_SESSION["admin"]->id_office_admin == 0 && isset($_GET["offices"])){

    $_SESSION["admin"]->id_office_admin = explode("_",$_GET["offices"])[0];
    $_SESSION["admin"]->title_office = explode("_",$_GET["offices"])[1];

}

if(isset($_GET["offices"]) && $_SESSION["admin"]->id_office_admin > 0){

    $_SESSION["admin"]->id_office_admin = explode("_",$_GET["offices"])[0];
   
}

// variable para actualizar el stock
$updateStock = false;

/*=============================================
Abrir la página correspondiente del Dashboard
=============================================*/
//para saber si en la url viene alguna ruta es decir si la url http://cms.pos.com/admins entonces traería los modulos relacionados a la pagina admin
if (!empty($routesArray[0])){//Si routesArray[0] tiene un valor, se obtiene la relación de módulos según la URL (url_page).

    $url = "relations?rel=modules,pages&type=module,page&linkTo=url_page&equalTo=".$routesArray[0];
    if($routesArray[0] == "pos" || $routesArray[0] == "productos"){
        $updateStock = true;
    }

}else{
    //Si routesArray[0] está vacío, se carga el módulo con order_page=1 (la página principal, que es la de pos).
    $url = "relations?rel=modules,pages&type=module,page&linkTo=order_page&equalTo=1";
    // Si el usuario es multi-sucursal (id_office_admin == 0) y no ha seleccionado una sucursal (!isset($_GET["offices"])), se abre automáticamente el modal de selección de sucursal.
    if($_SESSION["admin"]->id_office_admin == 0 && !isset($_GET["offices"])){

        echo '<script>

        setTimeout(()=>{

            $("#myOffices").modal("show");

        },100);

        </script>';
    }
    
    $updateStock = true;
}


$method = "GET";
$fields = array();

$modules = CurlController::request($url,$method,$fields);

if($modules->status == 200){

    $modules = $modules->results;

}else{

    $modules = array();

}

/*=============================================
Actualizar el stock
=============================================*/

if($updateStock && $_SESSION["admin"]->id_office_admin > 0){

    /*=============================================
    Traer los productos de la sucursal
    =============================================*/
    $url = "products?linkTo=id_office_product&equalTo=".$_SESSION["admin"]->id_office_admin."&select=id_product";
    $method = "GET";
    $fields = array();

    $productsStock = CurlController::request($url,$method,$fields);
    
    if($productsStock->status == 200){
        //variable que lo utilizaremos para saber cuando se termina el bucle for-each
        $countStockProducts = 0;

        foreach ($productsStock->results as $key => $value) {

            /*=============================================
            Traer total de compras
            =============================================*/

            $url = "purchases?linkTo=id_product_purchase&equalTo=".$value->id_product."&select=qty_purchase";
            $purchases = CurlController::request($url,$method,$fields);
            
            $totalPurchaseProduct = 0;
         
            if($purchases->status == 200){

                foreach ($purchases->results as $index => $item) {

                    $totalPurchaseProduct += $item->qty_purchase;
                }
            }

            /*=============================================
            Traer total de ventas
            =============================================*/

            $url = "sales?linkTo=id_product_sale&equalTo=".$value->id_product."&select=qty_sale";
            $sales = CurlController::request($url,$method,$fields);

             $totalSaleProduct = 0;

            if($sales->status == 200){

              foreach ($sales->results as $index => $item) {

                $totalSaleProduct += $item->qty_sale;

              } 

            }

            /*=============================================
            Calcular compras menos ventas
            =============================================*/
            //la clave del arraystock seria el id del producto y el valor sería el stock(compra - venta)
            $arrayStock[$value->id_product] = ($totalPurchaseProduct - $totalSaleProduct);

            $countStockProducts++;
            //count($productsStock->results) contiene la cantidad de productos que itera nuestro for
            if($countStockProducts == count($productsStock->results)){

                /*=============================================
                Actualizar stock en base de datos
                =============================================*/

                foreach ($arrayStock as $key => $value) {

                    $url = "products?id=".$key."&nameId=id_product&token=".$_SESSION["admin"]->token_admin."&table=admins&suffix=admin";
                    $method = "PUT";
                    $fields = array(
                        "stock_product" => $value
                    );

                    $fields = http_build_query($fields);
                    $updateStock = CurlController::request($url,$method,$fields);

                }
             
            }
           
        }

    }
   
}


/*=============================================
Buscar orden iniciada
=============================================*/

$url = "orders?linkTo=id_admin_order,status_order,id_office_order,date_created_order&equalTo=".$_SESSION["admin"]->id_admin.",Pendiente,".$_SESSION["admin"]->id_office_admin.",".date("Y-m-d");
$method = "GET";
$fields = array();

$order = CurlController::request($url,$method,$fields);

if($order->status == 200){

    $order = $order->results[0];
  
}else{

    $order = null;
}

?>

?>
<!-- Cargar dinamicamente los modulos -->
<div class="container-fluid py-3 p-lg-4">
          
    <div class="row">

        <?php if (!empty($modules)): ?>

            <?php foreach ($modules as $key => $value): $module = $value ?>

                <!--=========================================
                Cuando el módulo es un breadcrumb
                ===========================================-->

                <?php if ($module->type_module == "breadcrumbs"): ?>

                    <?php include "breadcrumbs/breadcrumbs.php" ?>
                    
                <?php endif ?>

                <!--=========================================
                Cuando el módulo es una métrica
                ===========================================-->

                <?php if ($module->type_module == "metrics"): ?>

                    <?php include "metrics/metrics.php" ?>
                    
                <?php endif ?>

                <!--=========================================
                Cuando el módulo es un gráfico
                ===========================================-->

                <?php if ($module->type_module == "graphics"): ?>

                    <?php include "graphics/graphics.php" ?>
                    
                <?php endif ?>

                <!--=========================================
                Cuando el módulo es una tabla
                ===========================================-->

                <?php if ($module->type_module == "tables"): ?>

                    <?php include "tables/tables.php" ?>
                    
                <?php endif ?>

                <!--=========================================
                Cuando el módulo es personalizado
                ===========================================-->

                <?php if ($module->type_module == "custom"): ?>

                    <?php include "custom/".str_replace(" ","_",$module->title_module)."/".str_replace(" ","_",$module->title_module).".php" ?>
                    
                <?php endif ?>
   
            <?php endforeach ?>
            
        <?php endif ?>

        <?php if ($_SESSION["admin"]->rol_admin == "superadmin"): ?>

                <div class="text-center <?php if (!empty($routesArray[1]) && $routesArray[1] == "manage"): ?> d-none  <?php endif ?>">
                
                    <button class="btn btn-default bg-white border rounded btn-sm ms-3 menu-text mt-1 py-2 px-3 myModule" idPage="<?php echo $page->results[0]->id_page ?>">Agregar Módulo</button>

                </div>
        
        <?php endif ?>

    </div>

</div>
<!-- Si phone_office no está en la sesión, se incluye el modal offices.php, permitiendo que el usuario elija su sucursal. -->
<?php if (!isset($_SESSION["admin"]->phone_office)): ?>

<?php include "views/modules/modals/offices.php"; ?>

<?php endif ?>

<script src="/views/assets/js/pos/pos.js"></script>

