<?php 

require_once "../controllers/curl.controller.php";

class StockAjax{

	public $id_office_admin;
	public $token_admin;

	public function updateStock(){

	    /*=============================================
	    Traer los productos de la sucursal
	    =============================================*/
	    $url = "products?linkTo=id_office_product&equalTo=".$this->id_office_admin."&select=id_product";
	    $method = "GET";
	    $fields = array();

    	$productsStock = CurlController::request($url,$method,$fields);
    
    	if($productsStock->status == 200){

        	$countStockProducts = 0;
        	$countTotalStock= 0;

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

            	$arrayStock[$value->id_product] = ($totalPurchaseProduct - $totalSaleProduct);

            	$countStockProducts++;

            	if($countStockProducts == count($productsStock->results)){

	                /*=============================================
	                Actualizar stock en base de datos
	                =============================================*/

	                foreach ($arrayStock as $key => $value) {

	                    $url = "products?id=".$key."&nameId=id_product&token=".$this->token_admin."&table=admins&suffix=admin";
	                    $method = "PUT";
	                    $fields = array(
	                        "stock_product" => $value
	                    );

	                    $fields = http_build_query($fields);
	                    $updateStock = CurlController::request($url,$method,$fields);

	                    if($updateStock->status == 200){

	                    	$countTotalStock++;

	                    	if($countTotalStock == count($arrayStock)){

	                    		echo 200;
	                    	}
	                    
	                    }

                	}
             
            	}
           
        	}

    	}

	}

}

if(isset($_POST["id_office_admin"])){

	$ajax = new StockAjax();
	$ajax -> id_office_admin = $_POST["id_office_admin"];
	$ajax -> token_admin = $_POST["token_admin"];
	$ajax -> updateStock();
}