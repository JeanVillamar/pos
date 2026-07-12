<?php

/*=============================================
Configuración de Facturación Electrónica (SRI Ecuador)

Copiar este archivo como facturacion.config.php y completar
los valores reales. facturacion.config.php NO se sube a git.
=============================================*/

return [

	// 1 = pruebas (celcer), 2 = producción (cel)
	"ambiente" => "1",

	// Endpoints SOAP del SRI por ambiente
	"sri" => [
		"1" => [
			"recepcion"    => "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl",
			"autorizacion" => "https://celcer.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"
		],
		"2" => [
			"recepcion"    => "https://cel.sri.gob.ec/comprobantes-electronicos-ws/RecepcionComprobantesOffline?wsdl",
			"autorizacion" => "https://cel.sri.gob.ec/comprobantes-electronicos-ws/AutorizacionComprobantesOffline?wsdl"
		]
	],

	// Reintentos al consultar la autorización (el SRI tarda unos segundos en procesar)
	"autorizacion" => [
		"reintentos"      => 5,
		"espera_segundos" => 2
	],

	// Ruta al binario de java. null = detección automática
	// macOS (Homebrew): /opt/homebrew/opt/openjdk/bin/java
	// Windows: C:\\Program Files\\Java\\jdk-24.0.1\\bin\\java.exe
	"java_bin" => null,

	// Certificados de firma por RUC del emisor.
	// El archivo .p12 debe estar en cms/certificados/
	"certificados" => [
		// "0101063164001" => [
		// 	"archivo"  => "0101063164001.p12",
		// 	"password" => "CAMBIAR"
		// ]
	],

	// Configuración SMTP para envío del comprobante al cliente
	"smtp" => [
		"host"     => "smtp.gmail.com",
		"user"     => "CAMBIAR@gmail.com",
		"pass"     => "CAMBIAR (app password)",
		"port"     => 587,
		"secure"   => "tls",
		"from"     => "CAMBIAR@gmail.com",
		"fromName" => "smartposline"
	]

];
