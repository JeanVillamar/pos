<?php

/*=============================================
Cliente SOAP para los Web Services del SRI (Ecuador)
Recepción y Autorización de comprobantes electrónicos.

Reemplaza al script Python (autorizacion/integrated.py):
usa la extensión soap nativa de PHP, sin dependencias externas.
=============================================*/

class SriController
{

	private $config;

	public function __construct()
	{
		$rutaConfig = __DIR__ . "/../config/facturacion.config.php";

		if (!file_exists($rutaConfig)) {
			throw new Exception("No existe cms/config/facturacion.config.php. Copia facturacion.config.example.php y completa los datos.");
		}

		$this->config = require $rutaConfig;
	}

	public function ambiente()
	{
		return $this->config["ambiente"];
	}

	protected function soapClient($wsdl)
	{
		return new SoapClient($wsdl, array(
			"connection_timeout" => 15,
			"exceptions"         => true,
			"trace"              => false,
			"cache_wsdl"         => WSDL_CACHE_BOTH
		));
	}

	/*=============================================
	Enviar el XML firmado a Recepción.
	Retorna array: ["estado" => "RECIBIDA"|"DEVUELTA", "mensajes" => [...]]
	=============================================*/

	public function enviarComprobante($rutaXmlFirmado)
	{
		if (!file_exists($rutaXmlFirmado)) {
			throw new Exception("No existe el XML firmado: " . $rutaXmlFirmado);
		}

		$wsdl = $this->config["sri"][$this->ambiente()]["recepcion"];
		$client = $this->soapClient($wsdl);

		// SoapClient codifica base64Binary automáticamente: se envía el contenido crudo
		$respuesta = $client->validarComprobante(array(
			"xml" => file_get_contents($rutaXmlFirmado)
		));

		$resultado = $respuesta->RespuestaRecepcionComprobante;

		$mensajes = array();

		if (isset($resultado->comprobantes->comprobante)) {
			$comprobantes = is_array($resultado->comprobantes->comprobante)
				? $resultado->comprobantes->comprobante
				: array($resultado->comprobantes->comprobante);

			foreach ($comprobantes as $comprobante) {
				if (!isset($comprobante->mensajes->mensaje)) continue;

				$lista = is_array($comprobante->mensajes->mensaje)
					? $comprobante->mensajes->mensaje
					: array($comprobante->mensajes->mensaje);

				foreach ($lista as $m) {
					$mensajes[] = trim(
						($m->identificador ?? "") . " " .
						($m->mensaje ?? "") . " " .
						($m->informacionAdicional ?? "")
					);
				}
			}
		}

		return array(
			"estado"   => $resultado->estado ?? "SIN_RESPUESTA",
			"mensajes" => $mensajes
		);
	}

	/*=============================================
	Consultar la Autorización por clave de acceso (con reintentos,
	el SRI tarda unos segundos en procesar el comprobante recibido).

	Retorna array:
	["estado","numeroAutorizacion","fechaAutorizacion","ambiente","comprobante","mensajes"]
	=============================================*/

	public function autorizarComprobante($claveAcceso)
	{
		$wsdl = $this->config["sri"][$this->ambiente()]["autorizacion"];
		$client = $this->soapClient($wsdl);

		$reintentos = (int)($this->config["autorizacion"]["reintentos"] ?? 5);
		$espera     = (int)($this->config["autorizacion"]["espera_segundos"] ?? 2);

		$ultimo = array(
			"estado"             => "SIN_RESPUESTA",
			"numeroAutorizacion" => null,
			"fechaAutorizacion"  => null,
			"ambiente"           => null,
			"comprobante"        => null,
			"mensajes"           => array()
		);

		for ($intento = 1; $intento <= $reintentos; $intento++) {

			$respuesta = $client->autorizacionComprobante(array(
				"claveAccesoComprobante" => $claveAcceso
			));

			$resultado = $respuesta->RespuestaAutorizacionComprobante;

			if (isset($resultado->autorizaciones->autorizacion)) {

				$autorizaciones = is_array($resultado->autorizaciones->autorizacion)
					? $resultado->autorizaciones->autorizacion
					: array($resultado->autorizaciones->autorizacion);

				$aut = $autorizaciones[0];

				$mensajes = array();
				if (isset($aut->mensajes->mensaje)) {
					$lista = is_array($aut->mensajes->mensaje)
						? $aut->mensajes->mensaje
						: array($aut->mensajes->mensaje);

					foreach ($lista as $m) {
						$mensajes[] = trim(
							($m->identificador ?? "") . " " .
							($m->mensaje ?? "") . " " .
							($m->informacionAdicional ?? "")
						);
					}
				}

				$ultimo = array(
					"estado"             => $aut->estado ?? "SIN_RESPUESTA",
					"numeroAutorizacion" => $aut->numeroAutorizacion ?? null,
					"fechaAutorizacion"  => $aut->fechaAutorizacion ?? null,
					"ambiente"           => $aut->ambiente ?? null,
					"comprobante"        => $aut->comprobante ?? null,
					"mensajes"           => $mensajes
				);

				// AUTORIZADO o NO AUTORIZADO son estados finales: no seguir reintentando
				if ($ultimo["estado"] == "AUTORIZADO" || $ultimo["estado"] == "NO AUTORIZADO") {
					return $ultimo;
				}
			}

			if ($intento < $reintentos) {
				sleep($espera);
			}
		}

		return $ultimo;
	}

	/*=============================================
	Guardar el XML autorizado con la misma estructura
	que generaba el script Python (compatible con FacturaXMLParser):
	<autorizacion> estado / numeroAutorizacion / fechaAutorizacion / ambiente / comprobante(CDATA)
	=============================================*/

	public function guardarXmlAutorizado($autorizacion, $rutaSalida, $claveAcceso)
	{
		if (!file_exists($rutaSalida)) {
			mkdir($rutaSalida, 0777, true);
		}

		$fecha = $autorizacion["fechaAutorizacion"];
		if ($fecha instanceof DateTime) {
			$fecha = $fecha->format("c");
		}

		$xml  = '<?xml version="1.0" encoding="utf-8"?>' . "\n";
		$xml .= "<autorizacion>";
		$xml .= "<estado>" . htmlspecialchars($autorizacion["estado"]) . "</estado>";
		$xml .= "<numeroAutorizacion>" . htmlspecialchars($autorizacion["numeroAutorizacion"]) . "</numeroAutorizacion>";
		$xml .= "<fechaAutorizacion>" . htmlspecialchars($fecha) . "</fechaAutorizacion>";
		$xml .= "<ambiente>" . htmlspecialchars($autorizacion["ambiente"]) . "</ambiente>";
		$xml .= "<comprobante><![CDATA[" . $autorizacion["comprobante"] . "]]></comprobante>";
		$xml .= "</autorizacion>";

		$rutaArchivo = rtrim($rutaSalida, "/") . "/" . $claveAcceso . ".xml";
		file_put_contents($rutaArchivo, $xml);

		return $rutaArchivo;
	}
}
