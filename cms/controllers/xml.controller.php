<?php

class xmlController
{
    public function generarXMLComprobante($jsonData, $jsonOffice, $rutaSalida, $jsonCliente = null)
    {

        $data = is_string($jsonData) ? json_decode($jsonData, true) : json_decode(json_encode($jsonData), true);
        $office = is_string($jsonOffice) ? json_decode($jsonOffice, true) : json_decode(json_encode($jsonOffice), true);

        if (!isset($data['results'][0]) || !isset($office['results'][0])) {
            throw new Exception("Datos incompletos para generar XML.");
        }

        $venta = $data['results'][0];
        $ventas = $data['results'];
        $oficina = $office['results'][0];

        // === DATOS ESTABLECIMIENTO ===
        $estab = str_pad($oficina['id_local_office'], 3, "0", STR_PAD_LEFT);
        $ptoEmi = "001";
        $secuencial = "000000123";
        $fecha = $this->formatearFecha($venta['date_created_order']);
        $tipoComprobante = "01"; // Factura
        $ruc = $oficina['dni_office'];
        $ambiente = "1"; // Pruebas
        $tipoEmision = "1";
        $codigoNumerico = rand(10000000, 99999999);

        $claveAcceso = $this->generarClaveAcceso($fecha, $tipoComprobante, $ruc, $ambiente, $estab, $ptoEmi, $secuencial, $codigoNumerico, $tipoEmision);

        // === DATOS CLIENTE ===
        $cliente = $this->procesarCliente($jsonCliente);
        $razonSocialComprador = $cliente['razonSocial'];
        $identificacionComprador = $cliente['identificacion'];
        $tipoIdentificacion = $cliente['tipoIdentificacion'];

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

        //fecha formato dd/mm/aaaa
        $fechaEmision = date('d/m/Y', strtotime($venta['date_created_order']));
        $infoFactura->appendChild($doc->createElement("fechaEmision", $fechaEmision));


        $infoFactura->appendChild($doc->createElement("dirEstablecimiento", $oficina['address_office']));
        $infoFactura->appendChild($doc->createElement("tipoIdentificacionComprador", $tipoIdentificacion));
        $infoFactura->appendChild($doc->createElement("razonSocialComprador", $razonSocialComprador));
        $infoFactura->appendChild($doc->createElement("identificacionComprador", $identificacionComprador));
        $infoFactura->appendChild($doc->createElement("totalSinImpuestos", $venta['subtotal_order']));
        $infoFactura->appendChild($doc->createElement("totalDescuento", $venta['discount_order']));

        // === totalConImpuestos ===
        $totalConImpuestos = $doc->createElement("totalConImpuestos");
        $impuesto = $doc->createElement("totalImpuesto");
        $impuesto->appendChild($doc->createElement("codigo", "2"));
        $impuesto->appendChild($doc->createElement("codigoPorcentaje", "2"));
        $impuesto->appendChild($doc->createElement("baseImponible", $venta['subtotal_order']));
        $impuesto->appendChild($doc->createElement("valor", $venta['tax_order']));
        $totalConImpuestos->appendChild($impuesto);
        $infoFactura->appendChild($totalConImpuestos);

        $infoFactura->appendChild($doc->createElement("propina", "0.00"));
        $infoFactura->appendChild($doc->createElement("importeTotal", $venta['total_order']));
        $infoFactura->appendChild($doc->createElement("moneda", "DOLAR"));

        // === detalles ===
        $detalles = $doc->createElement("detalles");
        foreach ($ventas as $item) {
            $detalle = $doc->createElement("detalle");
            $detalle->appendChild($doc->createElement("codigoPrincipal", "P-{$item['id_product_sale']}"));
            $detalle->appendChild($doc->createElement("descripcion", "Producto {$item['id_product_sale']}"));
            $detalle->appendChild($doc->createElement("cantidad", $item['qty_sale']));
            $detalle->appendChild($doc->createElement("precioUnitario", $item['subtotal_sale']));
            $detalle->appendChild($doc->createElement("descuento", $item['discount_sale']));
            $detalle->appendChild($doc->createElement("precioTotalSinImpuesto", $item['subtotal_sale']));

            $impuestos = $doc->createElement("impuestos");
            $imp = $doc->createElement("impuesto");
            $imp->appendChild($doc->createElement("codigo", "2"));
            $imp->appendChild($doc->createElement("codigoPorcentaje", "2"));
            $imp->appendChild($doc->createElement("tarifa", "12.00"));
            $imp->appendChild($doc->createElement("baseImponible", $item['subtotal_sale']));
            $imp->appendChild($doc->createElement("valor", round($item['subtotal_sale'] * 0.12, 2)));

            $impuestos->appendChild($imp);
            $detalle->appendChild($impuestos);
            $detalles->appendChild($detalle);
        }
        $factura->appendChild($detalles);

        // === infoAdicional ===
        $infoAdicional = $doc->createElement("infoAdicional");
        $campo = $doc->createElement("campoAdicional", "Gracias por su compra");
        $campo->setAttribute("nombre", "Observaciones");
        $infoAdicional->appendChild($campo);
        $factura->appendChild($infoAdicional);

        // === Guardar XML ===
        if (!file_exists($rutaSalida)) {
            mkdir($rutaSalida, 0777, true);
        }

        $rutaCompleta = rtrim($rutaSalida, '/') . '/' . $estab . $ptoEmi . $secuencial . '.xml';
        $doc->save($rutaCompleta);

        return $rutaCompleta;
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
            'tipoIdentificacion' => $tipoIdentificacion
        ];
    }

    public function firmarXML($archivoXML, $certificadoSinP12)
    {
        // Detectar raíz del proyecto (donde están sri.jar, lib/, certificados/, xml/)
        $rutaBase = realpath(__DIR__ . '/../'); // ← asumiendo que esta clase está en /controllers
        echo 'Ruta base: ' . $rutaBase . PHP_EOL;
        echo '<script>console.log("Ruta base: ' . $rutaBase . '")</script>';
    
        // Armado de rutas absolutas
        $cert = $rutaBase . "/certificados/{$certificadoSinP12}.p12";
        $entrada = $rutaBase . "/xml/facturas_no_firmadas/{$archivoXML}";
        $salida = $rutaBase . "/xml/firmados";
        $archivoFinal = "firmado_{$archivoXML}";
        $pass = "Marcelo6441";
    
        // Separador de classpath según sistema operativo
        $sep = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? ';' : ':';
    
        // Clase principal
        $jar = $rutaBase . "/sri.jar";
        $lib = $rutaBase . "/lib/*";
    
        // Comando con rutas absolutas
        $comando = "java -cp \"$jar{$sep}$lib\" sri.DevelopedSignature \"$cert\" $pass \"$entrada\" \"$salida\" \"$archivoFinal\"";
    
        // Ejecutar y capturar salida
        exec($comando . " 2>&1", $output, $status);
    
        // Log para depuración
        file_put_contents($rutaBase . '/firmado_log.txt', implode(PHP_EOL, $output));
    
        $rutaFirmado = "$salida/$archivoFinal";
    
        if ($status === 0 && file_exists($rutaFirmado)) {
            return $rutaFirmado;
        } else {
            throw new Exception("❌ Error al firmar el XML\nComando ejecutado:\n$comando\nOutput:\n" . implode("\n", $output));
        }
    }
    
    
}
