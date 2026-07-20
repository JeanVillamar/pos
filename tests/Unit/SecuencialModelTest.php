<?php

use PHPUnit\Framework\TestCase;

/*=============================================
Tests de integración contra la BD real (no mockeados): la garantía
que importa acá es el comportamiento transaccional real de MySQL
(FOR UPDATE + AUTO_INCREMENT), no solo qué métodos se llamaron.
Usa identificadores de oficina/caja fuera de rango real para no
tocar datos de producción, y limpia lo que crea en tearDown.
=============================================*/
class SecuencialModelTest extends TestCase
{
    private const OFICINA_TEST = 900001;
    private const CAJA_TEST = 900001;
    private const OFFICE_TEST = 900001;

    private SecuencialModel $model;

    protected function setUp(): void
    {
        $this->model = new SecuencialModel();
        $this->limpiarDatosDePrueba();
    }

    protected function tearDown(): void
    {
        $this->limpiarDatosDePrueba();
    }

    private function limpiarDatosDePrueba(): void
    {
        $stmt = Connection::connect()->prepare(
            "DELETE FROM secuencials WHERE oficina_secuencial = :o AND caja_secuencial = :c AND office_secuencial = :io"
        );
        $stmt->execute([":o" => self::OFICINA_TEST, ":c" => self::CAJA_TEST, ":io" => self::OFFICE_TEST]);
    }

    /*=============================================
    Regresión: antes de agregar AUTO_INCREMENT a id_secuencial, la
    primera vez que se pedía un secuencial para una combinación
    oficina/caja/sucursal totalmente nueva fallaba con "Duplicate
    entry '0' for key secuencials.PRIMARY" porque el INSERT nunca
    especifica id_secuencial y la columna no autoincrementaba.
    =============================================*/
    public function testCombinacionNuevaCreaElPrimerSecuencialEnUno()
    {
        $numero = $this->model->obtenerProximoNumero(self::OFICINA_TEST, self::CAJA_TEST, self::OFFICE_TEST);

        $this->assertSame(1, $numero);
    }

    public function testLlamadasSucesivasIncrementanElSecuencial()
    {
        $primero = $this->model->obtenerProximoNumero(self::OFICINA_TEST, self::CAJA_TEST, self::OFFICE_TEST);
        $segundo = $this->model->obtenerProximoNumero(self::OFICINA_TEST, self::CAJA_TEST, self::OFFICE_TEST);
        $tercero = $this->model->obtenerProximoNumero(self::OFICINA_TEST, self::CAJA_TEST, self::OFFICE_TEST);

        $this->assertSame(1, $primero);
        $this->assertSame(2, $segundo);
        $this->assertSame(3, $tercero);
    }

    public function testCombinacionesDeOficinaDiferentesNoSeMezclan()
    {
        $oficinaA = $this->model->obtenerProximoNumero(self::OFICINA_TEST, self::CAJA_TEST, self::OFFICE_TEST);
        $oficinaB = $this->model->obtenerProximoNumero(self::OFICINA_TEST, self::CAJA_TEST + 1, self::OFFICE_TEST);

        $this->assertSame(1, $oficinaA);
        $this->assertSame(1, $oficinaB); // caja distinta -> su propio contador desde 1

        // limpiar también la caja auxiliar usada en este test
        $stmt = Connection::connect()->prepare(
            "DELETE FROM secuencials WHERE oficina_secuencial = :o AND caja_secuencial = :c AND office_secuencial = :io"
        );
        $stmt->execute([":o" => self::OFICINA_TEST, ":c" => self::CAJA_TEST + 1, ":io" => self::OFFICE_TEST]);
    }
}
