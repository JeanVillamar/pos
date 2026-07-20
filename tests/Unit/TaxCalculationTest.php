<?php

use PHPUnit\Framework\TestCase;

/*=============================================
Cubre las fórmulas puras de xml.controller.php que alimentan
el comprobante enviado al SRI: dígito verificador (módulo 11)
y el cálculo de IVA/descuento por línea de venta.
=============================================*/
class TaxCalculationTest extends TestCase
{
    private function callPrivate($object, $method, array $args)
    {
        $reflection = new ReflectionMethod($object, $method);
        $reflection->setAccessible(true);
        return $reflection->invokeArgs($object, $args);
    }

    /*=============================================
    modulo11(): vectores calculados a mano siguiendo el algoritmo
    (pesos cíclicos 2,3,4,5,6,7 aplicados de derecha a izquierda).
    =============================================*/

    public function testModulo11RamaDondeModuloEsOnceDevuelveCero()
    {
        // "0": digito=0, peso=2, suma=0, 0 % 11 = 0, modulo = 11 - 0 = 11 -> 0
        $xml = new xmlController();
        $this->assertSame(0, $this->callPrivate($xml, 'modulo11', ['0']));
    }

    public function testModulo11RamaDondeModuloEsDiezDevuelveUno()
    {
        // "6": digito=6, peso=2, suma=12, 12 % 11 = 1, modulo = 11 - 1 = 10 -> 1
        $xml = new xmlController();
        $this->assertSame(1, $this->callPrivate($xml, 'modulo11', ['6']));
    }

    public function testModulo11ConClaveMultidigito()
    {
        // "123456781": suma = 158 (ver cálculo manual), 158 % 11 = 4, modulo = 7
        $xml = new xmlController();
        $this->assertSame(7, $this->callPrivate($xml, 'modulo11', ['123456781']));
    }

    public function testGenerarClaveAccesoAgregaUnSoloDigitoVerificadorAlFinal()
    {
        $xml = new xmlController();
        $clave = $this->callPrivate($xml, 'generarClaveAcceso', [
            '12072026', '01', '0106316441001', '1', '001', '001', '000000001', '12345678', '1'
        ]);

        $sinDigito = '12072026' . '01' . '0106316441001' . '1' . '001' . '001' . '000000001' . '12345678' . '1';
        $digitoEsperado = $this->callPrivate($xml, 'modulo11', [$sinDigito]);

        $this->assertSame($sinDigito . $digitoEsperado, $clave);
        $this->assertSame(49, strlen($clave)); // 48 dígitos base (SRI) + 1 dígito verificador
    }

    /*=============================================
    calcularItemFactura(): IVA por línea de venta.
    =============================================*/

    public function testIva15PorcientoSinDescuento()
    {
        $xml = new xmlController();
        $item = ['subtotal_sale' => 100.00, 'qty_sale' => 2];
        $producto = ['tax_product' => 'IVA_15', 'discount_product' => 0];

        $resultado = $this->callPrivate($xml, 'calcularItemFactura', [$item, $producto]);

        $this->assertSame('4', $resultado['codigoPorcentaje']);
        $this->assertSame('15.00', $resultado['tarifa']);
        $this->assertSame(15.00, $resultado['valorImpuesto']); // 100 * 0.15
        $this->assertSame(0.00, $resultado['descuento']);
        $this->assertSame(50.00, $resultado['precioUnitario']); // 100 / 2
    }

    public function testIva0PorcientoUsaTarifaCero()
    {
        $xml = new xmlController();
        $item = ['subtotal_sale' => 50.00, 'qty_sale' => 1];
        $producto = ['tax_product' => 'IVA_0', 'discount_product' => 0];

        $resultado = $this->callPrivate($xml, 'calcularItemFactura', [$item, $producto]);

        $this->assertSame('0', $resultado['codigoPorcentaje']);
        $this->assertSame(0.00, $resultado['valorImpuesto']);
    }

    public function testProductoSinTaxProductConocidoCaeEnIva15PorDefecto()
    {
        $xml = new xmlController();
        $item = ['subtotal_sale' => 20.00, 'qty_sale' => 1];
        $producto = ['tax_product' => 'ALGO_INEXISTENTE', 'discount_product' => 0];

        $resultado = $this->callPrivate($xml, 'calcularItemFactura', [$item, $producto]);

        $this->assertSame('15.00', $resultado['tarifa']);
    }

    public function testDescuentoDeProductoAjustaPrecioUnitarioYDescuento()
    {
        $xml = new xmlController();
        // subtotal_sale ya viene con el descuento aplicado (precio de venta real).
        // Con 20% de descuento: precioUnitario = (subtotal/qty * 100) / 80
        $item = ['subtotal_sale' => 80.00, 'qty_sale' => 1];
        $producto = ['tax_product' => 'IVA_15', 'discount_product' => 20];

        $resultado = $this->callPrivate($xml, 'calcularItemFactura', [$item, $producto]);

        $this->assertEqualsWithDelta(100.00, $resultado['precioUnitario'], 0.000001);
        $this->assertEqualsWithDelta(20.00, $resultado['descuento'], 0.000001);
        // El IVA se calcula sobre el subtotal ya descontado, no sobre el precio de lista.
        $this->assertSame(12.00, $resultado['valorImpuesto']); // 80 * 0.15
    }

    public function testRedondeoDeIvaAEndosDecimales()
    {
        $xml = new xmlController();
        // 33.33 * 0.15 = 4.9995 -> debe redondear a 5.00, no truncar a 4.99
        $item = ['subtotal_sale' => 33.33, 'qty_sale' => 1];
        $producto = ['tax_product' => 'IVA_15', 'discount_product' => 0];

        $resultado = $this->callPrivate($xml, 'calcularItemFactura', [$item, $producto]);

        $this->assertSame(5.00, $resultado['valorImpuesto']);
    }
}
