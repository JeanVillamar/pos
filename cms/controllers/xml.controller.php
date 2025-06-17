<?php

class xmlController
{
    public function generarXMLComprobante($jsonData, $jsonOffice, $rutaSalida, $productos, $jsonCliente, $secuencial)
    {
        $data = is_array($jsonData) ? $jsonData : json_decode(json_encode($jsonData), true);
        $office = is_array($jsonOffice) ? $jsonOffice : json_decode(json_encode($jsonOffice), true);
        $productos = is_array($productos) ? $productos : json_decode(json_encode($productos), true);

        if (!isset($data['results'][0]) || !isset($office['results'][0])) {
            throw new Exception("Datos incompletos para generar XML.");
        }

        $venta = $data['results'][0];
        $ventas = $data['results'];
        $oficina = $office['results'][0];

        // === DATOS ESTABLECIMIENTO ===
        $estab = str_pad($oficina['id_local_office'], 3, "0", STR_PAD_LEFT);
        $ptoEmi = str_pad($_SESSION["admin"]->cash_admin, 3, "0", STR_PAD_LEFT);
        $fecha = $this->formatearFecha($venta['date_created_order']);
        $tipoComprobante = "01";
        $ruc = $oficina['dni_office'];
        $ambiente = "1";
        $tipoEmision = "1";
        $codigoNumerico = rand(10000000, 99999999);

        $claveAcceso = $this->generarClaveAcceso($fecha, $tipoComprobante, $ruc, $ambiente, $estab, $ptoEmi, $secuencial, $codigoNumerico, $tipoEmision);

        // === DATOS CLIENTE ===
        $cliente = $this->procesarCliente($jsonCliente);
        $razonSocialComprador = $cliente['razonSocial'];
        $identificacionComprador = $cliente['identificacion'];
        $tipoIdentificacion = $cliente['tipoIdentificacion'];

        $clienteData = is_array($jsonCliente) ? $jsonCliente : json_decode(json_encode($jsonCliente), true);
        $correoCliente = $clienteData['results'][0]['email_client'] ?? null;

        // === INICIAR XML ===
        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        $factura = $doc->createElement("factura");
        $factura->setAttribute("id", "comprobante");
        $factura->setAttribute("version", "1.1.0");
        $doc->appendChild($factura);

        // === infoTributaria ===
        $infoTributaria = $doc->createElement("infoTributaria");
        $factura->appendChild($infoTributaria);
        $infoTributaria->appendChild($doc->createElement("ambiente", $ambiente));
        $infoTributaria->appendChild($doc->createElement("tipoEmision", $tipoEmision));
        $infoTributaria->appendChild($doc->createElement("razonSocial", $oficina['company_name_office']));
        $infoTributaria->appendChild($doc->createElement("nombreComercial", $oficina['title_office']));
        $infoTributaria->appendChild($doc->createElement("ruc", $ruc));
        $infoTributaria->appendChild($doc->createElement("claveAcceso", $claveAcceso));
        $infoTributaria->appendChild($doc->createElement("codDoc", $tipoComprobante));
        $infoTributaria->appendChild($doc->createElement("estab", $estab));
        $infoTributaria->appendChild($doc->createElement("ptoEmi", $ptoEmi));
        $infoTributaria->appendChild($doc->createElement("secuencial", $secuencial));
        $infoTributaria->appendChild($doc->createElement("dirMatriz", $oficina['address_office']));

        // === infoFactura ===
        $infoFactura = $doc->createElement("infoFactura");
        $factura->appendChild($infoFactura);
        $infoFactura->appendChild($doc->createElement("fechaEmision", date('d/m/Y', strtotime($venta['date_created_order']))));
        $infoFactura->appendChild($doc->createElement("dirEstablecimiento", $oficina['address_office']));
        $infoFactura->appendChild($doc->createElement("tipoIdentificacionComprador", $tipoIdentificacion));
        $infoFactura->appendChild($doc->createElement("razonSocialComprador", $razonSocialComprador));
        $infoFactura->appendChild($doc->createElement("identificacionComprador", $identificacionComprador));
        $infoFactura->appendChild($doc->createElement("totalSinImpuestos", $venta['subtotal_order']));
        $infoFactura->appendChild($doc->createElement("totalDescuento", $venta['discount_order']));

        // === totalConImpuestos ===
        $totalConImpuestos = $doc->createElement("totalConImpuestos");
        $impuesto = $doc->createElement("totalImpuesto");
        $impuesto->appendChild($doc->createElement("codigo", "2")); // IVA
        $impuesto->appendChild($doc->createElement("codigoPorcentaje", "4")); // Ej: IVA 15%
        $impuesto->appendChild($doc->createElement("baseImponible", $venta['subtotal_order']));
        $impuesto->appendChild($doc->createElement("valor", $venta['tax_order']));
        $totalConImpuestos->appendChild($impuesto);
        $infoFactura->appendChild($totalConImpuestos);

        $infoFactura->appendChild($doc->createElement("propina", "0.00"));
        $infoFactura->appendChild($doc->createElement("importeTotal", $venta['total_order']));
        $infoFactura->appendChild($doc->createElement("moneda", "DOLAR"));

        // === pagos ===
        $pagos = $doc->createElement("pagos");
        $infoFactura->appendChild($pagos);

        $pago = $doc->createElement("pago");
        $pagos->appendChild($pago);

        // mapa sencillo de métodos → códigos SRI
        $metodo = $venta['method_order']; // 'efectivo','transferencia','tarjeta'
        $codigos = [
            'efectivo'      => '01',
            'transferencia' => '20',  // OTROS CON UTILIZACIÓN DEL SISTEMA FINANCIERO
            'tarjeta'       => '16',  // 19=Tarjeta de crédito (o ajustá si querés prepago=18) 16 = Tarjeta de débito
        ];
        $formPago = isset($codigos[$metodo]) ? $codigos[$metodo] : '01';

        $pago->appendChild($doc->createElement("formaPago", $formPago));
        $pago->appendChild(
            $doc->createElement(
                "total",
                number_format($venta['total_order'], 2, '.', '')
            )
        );

        // sólo para tarjeta le pones plazo y unidadTiempo
        if ($metodo === 'tarjeta') {
            $pago->appendChild($doc->createElement("plazo", "30"));
            $pago->appendChild($doc->createElement("unidadTiempo", "dias"));
        }


        // === detalles ===
        $detalles = $doc->createElement("detalles");

        foreach ($ventas as $item) {
            $idProducto = $item['id_product_sale'];
            $producto = $productos[$idProducto] ?? null;
            if (!$producto) continue;

            $mapaIVA = [
                'IVA_0' => ['codigoPorcentaje' => '0', 'tarifa' => '0.00'],
                'IVA_12' => ['codigoPorcentaje' => '2', 'tarifa' => '12.00'],
                'IVA_15' => ['codigoPorcentaje' => '4', 'tarifa' => '15.00'],
                'EXENTO DE IVA' => ['codigoPorcentaje' => '7', 'tarifa' => '0.00'],
                'NO OBJETO DE IMPUESTO' => ['codigoPorcentaje' => '6', 'tarifa' => '0.00']
            ];
            $iva = $mapaIVA[$producto['tax_product']] ?? $mapaIVA['IVA_15'];

            $detalle = $doc->createElement("detalle");
            $detalle->appendChild($doc->createElement("codigoPrincipal", $producto['sku_product']));
            $detalle->appendChild($doc->createElement("descripcion", $producto['title_product']));
            $detalle->appendChild($doc->createElement("cantidad", $item['qty_sale']));
            if ($producto['discount_product'] > 0) {
                $detalle->appendChild(
                    $doc->createElement('precioUnitario', ($item['subtotal_sale'] / $item['qty_sale'] * 100)/(100 - $producto['discount_product']))
                );
                $detalle->appendChild($doc->createElement("descuento", round( ($item['subtotal_sale'] / $item['qty_sale'] * 100)/(100 - $producto['discount_product']) * $item['qty_sale'] * (($producto['discount_product'])/100) , 2)));
                // $detalle->appendChild($doc->createElement("descuento", ($item['subtotal_sale']*$item['qty_sale']) - $item['subtotal_sale']));
                // $detalle->appendChild($doc->createElement("descuento", $item['discount_product']));
              
            } else {
                $detalle->appendChild($doc->createElement("precioUnitario", $item['subtotal_sale']/$item['qty_sale'])); 
                $detalle->appendChild($doc->createElement("descuento", 0));
            }
            
            // $detalle->appendChild($doc->createElement("descuento", $producto['discount_product']));
            $detalle->appendChild($doc->createElement("precioTotalSinImpuesto", $item['subtotal_sale']));

            $impuestos = $doc->createElement("impuestos");
            $imp = $doc->createElement("impuesto");
            $imp->appendChild($doc->createElement("codigo", "2"));
            $imp->appendChild($doc->createElement("codigoPorcentaje", $iva['codigoPorcentaje']));
            $imp->appendChild($doc->createElement("tarifa", $iva['tarifa']));
            $imp->appendChild($doc->createElement("baseImponible", $item['subtotal_sale']));
            $imp->appendChild($doc->createElement("valor", round($item['subtotal_sale'] * ($iva['tarifa'] / 100), 2)));
            $impuestos->appendChild($imp);
            $detalle->appendChild($impuestos);

            $detalles->appendChild($detalle);
        }

        $factura->appendChild($detalles);

        // === infoAdicional ===
        $infoAdicional = $doc->createElement("infoAdicional");

        if ($correoCliente) {
            $campoCorreo = $doc->createElement("campoAdicional", $correoCliente);
            $campoCorreo->setAttribute("nombre", "Email");
            $infoAdicional->appendChild($campoCorreo);
        }

        $campoObs = $doc->createElement("campoAdicional", "Gracias por su compra");
        $campoObs->setAttribute("nombre", "Observaciones");
        $infoAdicional->appendChild($campoObs);

        $factura->appendChild($infoAdicional);

        // === Guardar XML ===
        if (!file_exists($rutaSalida)) {
            mkdir($rutaSalida, 0777, true);
        }
        $numeroFactura = $estab . $ptoEmi . $secuencial;
        $rutaCompleta = rtrim($rutaSalida, '/') . '/' . $numeroFactura . '.xml';
        $doc->save($rutaCompleta);

        $array = array(
            'claveAcceso' => $claveAcceso,
            'numeroFactura' => $numeroFactura . '.xml'
        );
        return $array;
    }


    private function generarClaveAcceso($fecha, $tipoComprobante, $ruc, $ambiente, $estab, $ptoEmi, $secuencial, $codigoNumerico, $tipoEmision)
    {
        $clave = $fecha . $tipoComprobante . $ruc . $ambiente . $estab . $ptoEmi . $secuencial . $codigoNumerico . $tipoEmision;
        $digitoVerificador = $this->modulo11($clave);
        return $clave . $digitoVerificador;
    }

    private function modulo11($clave)
    {
        $baseMultiplicador = [2, 3, 4, 5, 6, 7];
        $suma = 0;
        $multiplicador = 0;
        for ($i = strlen($clave) - 1; $i >= 0; $i--) {
            $digito = (int) $clave[$i];
            $suma += $digito * $baseMultiplicador[$multiplicador];
            $multiplicador = ($multiplicador + 1) % count($baseMultiplicador);
        }
        $modulo = 11 - ($suma % 11);
        return ($modulo == 11) ? 0 : (($modulo == 10) ? 1 : $modulo);
    }

    private function formatearFecha($fechaISO)
    {
        $fecha = date_create($fechaISO);
        return date_format($fecha, 'dmY');
    }

    /**
     * Procesa y normaliza los datos del cliente.
     * Si no se recibe cliente válido, retorna los valores de consumidor final.
     */
    private function procesarCliente($jsonCliente): array
    {

        $cliente = ($jsonCliente) ? (is_string($jsonCliente) ? json_decode($jsonCliente, true) : json_decode(json_encode($jsonCliente), true)) : null;

        // Valores por defecto: consumidor final
        $razonSocial = "Consumidor Final";
        $identificacion = "9999999999999";
        $tipoIdentificacion = "07"; // Venta a consumidor final

        if ($cliente && isset($cliente['results'][0])) {
            $c = $cliente['results'][0];

            $razonSocial = trim(($c['name_client'] ?? '') . ' ' . ($c['surname_client'] ?? '')) ?: "Cliente";

            // Normalizar tipo (mayúsculas, sin tildes)
            $rawTipo = strtoupper(trim($c['dni_type_client'] ?? ''));
            $rawTipo = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $rawTipo
            );

            // Mapeo oficial SRI
            $mapaTipo = [
                "RUC" => "04",
                "CEDULA" => "05",
                "PASAPORTE" => "06",
                "VENTA A CONSUMIDOR FINAL" => "07",
                "IDENTIFICACION DEL EXTERIOR" => "08"
            ];

            $tipoIdentificacion = $mapaTipo[$rawTipo] ?? "07";

            // Limpiar identificación de cualquier carácter no numérico
            $identificacion = preg_replace('/[^0-9]/', '', $c['dni_client'] ?? '');

            // Validar según tipo
            $esValido = match ($tipoIdentificacion) {
                "04" => preg_match('/^\d{13}$/', $identificacion), // RUC
                "05" => preg_match('/^\d{10}$/', $identificacion), // Cédula
                default => strlen($identificacion) >= 5
            };

            // Fallback si no cumple
            if (!$esValido) {
                $razonSocial = "Consumidor Final";
                $identificacion = "9999999999999";
                $tipoIdentificacion = "07";
            }
        }

        return [
            'razonSocial' => $razonSocial,
            'identificacion' => $identificacion,
            'tipoIdentificacion' => $tipoIdentificacion,
            'correo' => $correo ?? null
        ];
    }

    public function firmarXML($archivoXML, $certificadoSinP12)
{
    $rutaBase     = realpath(__DIR__ . '/../');
    $cert         = "$rutaBase/certificados/{$certificadoSinP12}.p12";
    $entrada      = "$rutaBase/xml/facturas_no_firmadas/{$archivoXML}";
    $salida       = "$rutaBase/xml/firmados";
    $archivoFinal = "firmado_{$archivoXML}";
    $password     = "Marcelo6441";

    // Ruta absoluta a java.exe
    $javaBin = '"C:\\Program Files\\Java\\jdk-24.0.1\\bin\\java.exe"';

    // Classpath con backslashes
    $jar       = "$rutaBase\\sri.jar";
    $libDir    = "$rutaBase\\lib\\*";
    $classpath = "\"$jar;$libDir\"";

    // Construir comando Windows
    $cmd = implode(' ', [
        $javaBin,
        '-cp',
        $classpath,
        'sri.DevelopedSignature',
        escapeshellarg($cert),
        escapeshellarg($password),
        escapeshellarg($entrada),
        escapeshellarg($salida),
        escapeshellarg($archivoFinal),
    ]) . ' 2>&1';

    exec($cmd, $output, $status);
    file_put_contents("$rutaBase/firmado_log.txt", $cmd . PHP_EOL . implode(PHP_EOL, $output));

    $rutaFirmado = "$salida/$archivoFinal";
    if ($status === 0 && file_exists($rutaFirmado)) {
        return $rutaFirmado;
    }

    throw new Exception("❌ Error al firmar el XML. Revisa firmado_log.txt");
}

    
}
