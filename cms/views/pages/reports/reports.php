<?php

ob_start();

use Dompdf\Dompdf;
use Dompdf\Options;

// Configuración para imágenes remotas y otras opciones
$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

/*=============================================
Traer info de la orden
========================================|=====*/

$url = "relations?rel=orders,clients,admins,offices&type=order,client,admin,office&linkTo=id_order&equalTo=".base64_decode($_GET["id_order"]);
$method = "GET";
$fields = array();

$getOrder = CurlController::request($url,$method,$fields);

if($getOrder->status == 200){

	$order = $getOrder->results[0];
	$order->products = [];

	/*=============================================
	Agregarle los productos a la orden
	=============================================*/

	$url = "relations?rel=sales,products&type=sale,product&linkTo=id_order_sale&equalTo=".base64_decode($_GET["id_order"]);
	$method = "GET";
	$fields = array();

	$getProducts = CurlController::request($url,$method,$fields);

	if($getProducts->status == 200){

		$products = $getProducts->results;

		foreach ($products as $key => $value) {
			
			array_push($order->products, $value);
			
		}
	}

	// echo '<pre>$order '; print_r($order); echo '</pre>';

}

?>

<!-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        h1 { color: #007BFF; }
        p { font-size: 14px; }
    </style>
</head>
<body>
    <h1>Factura de Compra</h1>
    <p>Gracias por tu compra. Este es tu comprobante en PDF.</p>
</body>
</html> -->
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Factura <?= $order->transaction_order ?></title>
<style>
  body { font-family: Arial, sans-serif; font-size: 11px; color:#000; }
  .box { border:1px solid #000; padding:6px; margin-bottom:6px; }
  .bold { font-weight:700; }
  .right { text-align:right; }
  .center { text-align:center; }
  .small { font-size:10px; }

  table { width:100%; border-collapse:collapse; }
  th, td { border:1px solid #000; padding:4px; }
  th { background:#f2f2f2; text-align:center; }
  .no-border td { border:none; padding:2px; }

  .w-50 { width:50%; vertical-align:top; }
  .w-70 { width:70%; }
  .w-30 { width:30%; }
</style>
</head>

<body>

<!-- HEADER: 2 columnas -->
<table class="no-border" style="width:100%; margin-bottom:6px;">
  <tr>
    <td class="w-50" style="padding-right:6px;">
      <div class="box">
        <div class="bold" style="font-size:14px; margin-bottom:4px;">
          <?= strtoupper($order->company_name_office) ?>
        </div>

        <div><span class="bold">RUC:</span> <?= $order->dni_office ?></div>
        <div><span class="bold">DIR MATRIZ:</span> <?= $order->address_matriz_office ?: $order->address_office ?></div>
        <div><span class="bold">Sucursal:</span> <?= $order->title_office ?></div>
        <div><span class="bold">Tel:</span> <?= $order->phone_office ?></div>
        <div class="small" style="margin-top:6px;">
          <span class="bold">Transacción:</span> <?= $order->transaction_order ?>
        </div>
      </div>
    </td>

    <td class="w-50" style="padding-left:6px;">
      <div class="box">
        <table class="no-border" style="width:100%;">
          <tr>
            <td style="width:40%; vertical-align:top;">
              <?php if (!empty($logo_base64)): ?>
                <img src="<?= $logo_base64 ?>" style="width:180px;">
              <?php else: ?>
                <div class="center bold">LOGO</div>
              <?php endif; ?>
            </td>
            <td style="width:60%; vertical-align:top;">
              <div class="center bold" style="font-size:13px;">FACTURA</div>
              <div class="center">No. <?= $order->transaction_order ?></div>
              <hr style="border:none; border-top:1px solid #000; margin:6px 0;">

              <div><span class="bold">AUTORIZACIÓN:</span></div>
              <div style="word-break:break-all;"><?= $order->authorization ?></div>

              <div style="margin-top:6px;">
                <span class="bold">FECHA AUT.:</span> <?= $order->authorization_date ?>
              </div>

              <div style="margin-top:6px;" class="bold">CLAVE DE ACCESO</div>
              <div style="word-break:break-all;"><?= $order->access_key ?></div>

              <!-- Barcode: si tienes base64 png, ponlo aquí -->
              <?php if (!empty($order->barcode_base64)): ?>
                <div style="margin-top:6px;">
                  <img src="<?= $order->barcode_base64 ?>" style="width:100%; height:55px;">
                </div>
              <?php endif; ?>
            </td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>

<!-- CLIENTE -->
<div class="box">
  <table class="no-border">
    <tr>
      <td class="w-70">
        <div><span class="bold">RAZÓN SOCIAL / NOMBRES Y APELLIDOS:</span>
          <?= $order->name_client . ' ' . $order->surname_client ?>
        </div>
        <div><span class="bold">RUC/CI:</span> <?= $order->dni_client ?></div>
      </td>
      <td class="w-30">
        <div><span class="bold">FECHA DE EMISIÓN:</span></div>
        <div><?= date("d/m/Y", strtotime($order->date_order)) ?></div>
      </td>
    </tr>
  </table>
</div>

<!-- ITEMS -->
<table>
  <thead>
    <tr>
      <th style="width:12%;">Cod</th>
      <th style="width:8%;">Cant</th>
      <th>Descripción</th>
      <th style="width:14%;">P.Unit</th>
      <th style="width:10%;">Desc</th>
      <th style="width:14%;">Total</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $subtotal_calc = 0;
      foreach ($order->products as $p):
        $line_total = (float)$p->subtotal_sale;
        $subtotal_calc += $line_total;
    ?>
      <tr>
        <td class="center"><?= $p->sku_product ?? '' ?></td>
        <td class="center"><?= $p->qty_sale ?? 1 ?></td>
        <td><?= urldecode($p->title_product ?? '') ?></td>
        <td class="right">$<?= number_format((float)($p->price_sale ?? 0), 2, '.', ',') ?></td>
        <td class="right"><?= number_format((float)($p->discount_sale ?? 0), 2, '.', ',') ?></td>
        <td class="right">$<?= number_format($line_total, 2, '.', ',') ?></td>
      </tr>
    <?php endforeach; ?>

    <?php if (empty($order->products)): ?>
      <tr>
        <td colspan="6" class="center">No hay productos en la respuesta (te falta cargarlos)</td>
      </tr>
    <?php endif; ?>
  </tbody>
</table>

<br>

<?php
  $subtotal = (float)$order->subtotal_order;
  $discount = (float)$order->discount_order;
  $tax = (float)$order->tax_order;
  $total = (float)$order->total_order;

  $base_imponible = max(0, $subtotal - $discount);
  $iva_rate = ($base_imponible > 0) ? round(($tax / $base_imponible) * 100) : 0; // ej: 15
?>

<!-- INFO ADICIONAL + TOTALES -->
<table class="no-border" style="width:100%;">
  <tr>
    <td class="w-50" style="padding-right:6px; vertical-align:top;">
      <div class="box">
        <div class="bold">INFORMACIÓN ADICIONAL</div>
        <div style="margin-top:6px;">Email: <?= $order->email_client ?></div>
        <div>Dirección: <?= $order->address_client ?></div>
        <div>Tel: <?= $order->phone_client ?></div>
        <div style="margin-top:6px;">Observaciones: Gracias por su compra</div>
      </div>

      <div class="box">
        <table>
          <thead>
            <tr>
              <th>Forma de Pago</th>
              <th style="width:35%;">Valor</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?= strtoupper($order->method_order) ?></td>
              <td class="right">$<?= number_format($total, 2, '.', ',') ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </td>

    <td class="w-50" style="padding-left:6px; vertical-align:top;">
      <table>
        <tbody>
          <tr>
            <td class="right bold">SUBTOTAL 0%:</td>
            <td class="right">$<?= number_format(0, 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td class="right bold">SUBTOTAL <?= $iva_rate ?>%:</td>
            <td class="right">$<?= number_format($base_imponible, 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td class="right bold">DESCUENTO:</td>
            <td class="right">$<?= number_format($discount, 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td class="right bold">IVA <?= $iva_rate ?>%:</td>
            <td class="right">$<?= number_format($tax, 2, '.', ',') ?></td>
          </tr>
          <tr>
            <td class="right bold">VALOR TOTAL:</td>
            <td class="right bold">$<?= number_format($total, 2, '.', ',') ?></td>
          </tr>
        </tbody>
      </table>
    </td>
  </tr>
</table>

</body>
</html>


<?php

$html = ob_get_clean();

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Limpia el búfer de salida y establece el tipo de contenido
ob_clean();
header("Content-Type: application/pdf");

// Envía el archivo al navegador sin forzar la descarga
$dompdf->stream("archivo_generado.pdf", ["Attachment" => false]);

?>
