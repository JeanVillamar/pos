<?php

/*=============================================
Worker de facturación electrónica (se ejecuta en segundo plano)

Recibe un XML ya firmado y hace la parte lenta del flujo
sin bloquear la venta en el POS:

  1. Envía el comprobante a Recepción del SRI
  2. Consulta la Autorización (con reintentos)
  3. Guarda el XML autorizado
  4. Genera el PDF (RIDE)
  5. Envía el comprobante por correo al cliente
  6. Actualiza el estado en la tabla invoices (vía API)

Uso:
  php procesar_factura.php --firmado=/ruta/firmado.xml --clave=CLAVE_ACCESO [--email-cliente=x@y.com] [--email-emisor=x@y.com]
=============================================*/

if (php_sapi_name() !== "cli") {
	die("Este script solo puede ejecutarse por línea de comandos.");
}

date_default_timezone_set("America/Guayaquil");

$rutaCms = realpath(__DIR__ . "/..");

require_once $rutaCms . "/controllers/curl.controller.php";
require_once $rutaCms . "/controllers/sri.controller.php";
require_once $rutaCms . "/GeneratePDFfromXML/facturapdf.php";

/*=============================================
Parámetros
=============================================*/

$opciones = getopt("", array("firmado:", "clave:", "email-cliente::", "email-emisor::"));

$rutaFirmado  = $opciones["firmado"] ?? null;
$claveAcceso  = $opciones["clave"] ?? null;
$emailCliente = $opciones["email-cliente"] ?? null;
$emailEmisor  = $opciones["email-emisor"] ?? null;

if (!$rutaFirmado || !$claveAcceso) {
	fwrite(STDERR, "Faltan parámetros --firmado y/o --clave\n");
	exit(1);
}

/*=============================================
Log del proceso (un archivo por comprobante)
=============================================*/

$rutaLogs = $rutaCms . "/xml/logs";
if (!file_exists($rutaLogs)) {
	mkdir($rutaLogs, 0777, true);
}
$archivoLog = $rutaLogs . "/" . $claveAcceso . ".log";

function logFactura($mensaje)
{
	global $archivoLog;
	file_put_contents($archivoLog, "[" . date("Y-m-d H:i:s") . "] " . $mensaje . PHP_EOL, FILE_APPEND);
}

/*=============================================
Actualizar estado en la tabla invoices (vía API, sin sesión)
=============================================*/

function actualizarInvoice($claveAcceso, $campos)
{
	$url = "invoices?id=" . urlencode($claveAcceso) . "&nameId=access_key_invoice&token=no&except=id_invoice";
	$respuesta = CurlController::request($url, "PUT", http_build_query($campos));

	if (!$respuesta || $respuesta->status != 200) {
		logFactura("ADVERTENCIA: no se pudo actualizar la tabla invoices (" . json_encode($campos) . ")");
	}
}

logFactura("Inicio de procesamiento. XML: " . $rutaFirmado);

try {

	$sri = new SriController();

	/*=============================================
	1. Recepción
	=============================================*/

	$recepcion = $sri->enviarComprobante($rutaFirmado);
	logFactura("Recepción SRI: " . $recepcion["estado"]);

	if ($recepcion["estado"] != "RECIBIDA") {

		foreach ($recepcion["mensajes"] as $m) {
			logFactura("  - " . $m);
		}

		// "CLAVE ACCESO REGISTRADA" (código 43) significa que ya fue recibida antes:
		// se continúa con la autorización en lugar de abortar
		$yaRegistrada = false;
		foreach ($recepcion["mensajes"] as $m) {
			if (stripos($m, "CLAVE ACCESO REGISTRADA") !== false || strpos($m, "43 ") === 0) {
				$yaRegistrada = true;
				break;
			}
		}

		if (!$yaRegistrada) {
			actualizarInvoice($claveAcceso, array("status_invoice" => "DEVUELTA"));
			logFactura("Proceso terminado: comprobante DEVUELTO por el SRI.");
			exit(2);
		}

		logFactura("La clave ya estaba registrada en el SRI, se continúa con la autorización.");
	}

	/*=============================================
	2. Autorización (con reintentos internos)
	=============================================*/

	$autorizacion = $sri->autorizarComprobante($claveAcceso);
	logFactura("Autorización SRI: " . $autorizacion["estado"]);

	foreach ($autorizacion["mensajes"] as $m) {
		logFactura("  - " . $m);
	}

	if ($autorizacion["estado"] != "AUTORIZADO") {
		actualizarInvoice($claveAcceso, array("status_invoice" => $autorizacion["estado"]));
		logFactura("Proceso terminado: comprobante no autorizado.");
		exit(3);
	}

	/*=============================================
	3. Guardar XML autorizado
	=============================================*/

	$rutaAutorizado = $sri->guardarXmlAutorizado(
		$autorizacion,
		$rutaCms . "/xml/autorizados",
		$claveAcceso
	);
	logFactura("XML autorizado guardado en: " . $rutaAutorizado);

	/*=============================================
	4. Generar PDF (RIDE)
	=============================================*/

	$parser = generarPdfDesdeXml($rutaAutorizado, true);
	$rutaPDF = $rutaCms . "/xml/PDF/" . $parser->claveAcceso . ".pdf";
	logFactura("PDF generado en: " . $rutaPDF);

	actualizarInvoice($claveAcceso, array(
		"status_invoice" => "AUTORIZADO",
		"pdf_invoice"    => "xml/PDF/" . $parser->claveAcceso . ".pdf"
	));

	/*=============================================
	5. Enviar correo al cliente (si tiene email)
	=============================================*/

	if ($emailCliente && filter_var($emailCliente, FILTER_VALIDATE_EMAIL)) {

		try {
			enviarcorreo($rutaPDF, $rutaAutorizado, $emailCliente, $emailEmisor ?: "");
			logFactura("Correo enviado a: " . $emailCliente);
		} catch (Throwable $e) {
			logFactura("ADVERTENCIA: falló el envío de correo: " . $e->getMessage());
		}

	} else {
		logFactura("Cliente sin correo válido, no se envía email.");
	}

	logFactura("Proceso completado con éxito.");
	exit(0);

} catch (Throwable $e) {

	logFactura("ERROR: " . $e->getMessage());
	actualizarInvoice($claveAcceso, array("status_invoice" => "ERROR"));
	exit(10);
}
