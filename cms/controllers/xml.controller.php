<?php
class xmlController
{

    /*=============================================
    Crear XML
    =============================================*/

    public function generarXMLComprobante($jsonData, $nombreArchivo, $rutaSalida)
    {
        if (is_object($jsonData)) {
            $data = json_decode(json_encode($jsonData), true);
        } elseif (is_string($jsonData)) {
            $data = json_decode($jsonData, true);
        } else {
            throw new Exception("El dato ingresado no es un JSON válido ni un objeto.");
        }
        
        if (!isset($data['results']) || count($data['results']) === 0) {
            throw new Exception("No hay datos de venta.");
        }

        $venta = $data['results'][0];
        $ventas = $data['results'];

        $doc = new DOMDocument('1.0', 'UTF-8');
        $doc->formatOutput = true;

        // Nodo raíz
        $factura = $doc->createElement("factura");
        $factura->setAttribute("id", "comprobante");
        $factura->setAttribute("version", "1.1.0");
        $doc->appendChild($factura);

        // === infoTributaria ===
        $infoTributaria = $doc->createElement("infoTributaria");
        $factura->appendChild($infoTributaria);

        $infoTributaria->appendChild($doc->createElement("ambiente", "1"));
        $infoTributaria->appendChild($doc->createElement("tipoEmision", "1"));
        $infoTributaria->appendChild($doc->createElement("razonSocial", "EMPRESA DE EJEMPLO S.A."));
        $infoTributaria->appendChild($doc->createElement("nombreComercial", "Comercial Ejemplo"));
        $infoTributaria->appendChild($doc->createElement("ruc", "0999999999001"));
        $infoTributaria->appendChild($doc->createElement("claveAcceso", "1234567890123456789012345678901234567890123456789")); // Generar según algoritmo del SRI
        $infoTributaria->appendChild($doc->createElement("codDoc", "01"));
        $infoTributaria->appendChild($doc->createElement("estab", "001"));
        $infoTributaria->appendChild($doc->createElement("ptoEmi", "001"));
        $infoTributaria->appendChild($doc->createElement("secuencial", "000000123"));
        $infoTributaria->appendChild($doc->createElement("dirMatriz", "Av. Ejemplo 123"));

        // === infoFactura ===
        $infoFactura = $doc->createElement("infoFactura");
        $factura->appendChild($infoFactura);

        $infoFactura->appendChild($doc->createElement("fechaEmision", $venta['date_created_order']));
        $infoFactura->appendChild($doc->createElement("dirEstablecimiento", "Sucursal Ejemplo"));
        $infoFactura->appendChild($doc->createElement("razonSocialComprador", "Consumidor Final"));
        $infoFactura->appendChild($doc->createElement("identificacionComprador", "9999999999999"));
        $infoFactura->appendChild($doc->createElement("totalSinImpuestos", $venta['subtotal_order']));
        $infoFactura->appendChild($doc->createElement("totalDescuento", $venta['discount_order']));

        // totalConImpuestos
        $totalConImpuestos = $doc->createElement("totalConImpuestos");
        $impuesto = $doc->createElement("totalImpuesto");
        $impuesto->appendChild($doc->createElement("codigo", "2")); // IVA
        $impuesto->appendChild($doc->createElement("codigoPorcentaje", "2")); // 12%
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
            $impuesto = $doc->createElement("impuesto");
            $impuesto->appendChild($doc->createElement("codigo", "2"));
            $impuesto->appendChild($doc->createElement("codigoPorcentaje", "2"));
            $impuesto->appendChild($doc->createElement("tarifa", "12.00"));
            $impuesto->appendChild($doc->createElement("baseImponible", $item['subtotal_sale']));
            $impuesto->appendChild($doc->createElement("valor", round($item['subtotal_sale'] * 0.12, 2)));

            $impuestos->appendChild($impuesto);
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
        $rutaCompleta = rtrim($rutaSalida, '/') . '/' . $nombreArchivo . '.xml';
        $doc->save($rutaCompleta);

        return $rutaCompleta;
    }
}
