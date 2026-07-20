<?php

use PHPUnit\Framework\TestCase;

class CsrfControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
    }

    public function testTokenGeneraUnoNuevoLaPrimeraVez()
    {
        $this->assertArrayNotHasKey('csrf_token', $_SESSION);

        $token = CsrfController::token();

        $this->assertNotEmpty($token);
        $this->assertSame(64, strlen($token)); // bin2hex(random_bytes(32))
        $this->assertSame($token, $_SESSION['csrf_token']);
    }

    public function testTokenReutilizaElMismoValorDentroDeLaSesion()
    {
        $primero = CsrfController::token();
        $segundo = CsrfController::token();

        $this->assertSame($primero, $segundo);
    }

    public function testValidateAceptaElTokenCorrecto()
    {
        $token = CsrfController::token();

        $this->assertTrue(CsrfController::validate($token));
    }

    public function testValidateRechazaTokenIncorrecto()
    {
        CsrfController::token();

        $this->assertFalse(CsrfController::validate('token-falso'));
    }

    public function testValidateRechazaCuandoNoHaySesionIniciada()
    {
        $this->assertFalse(CsrfController::validate('cualquier-cosa'));
    }

    public function testValidateRechazaValoresNoString()
    {
        CsrfController::token();

        $this->assertFalse(CsrfController::validate(null));
        $this->assertFalse(CsrfController::validate(['array']));
    }

    public function testFieldDevuelveInputHiddenConElToken()
    {
        $html = CsrfController::field();

        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('name="csrf_token"', $html);
        $this->assertStringContainsString($_SESSION['csrf_token'], $html);
    }
}
