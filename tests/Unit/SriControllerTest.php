<?php

use PHPUnit\Framework\TestCase;

/*=============================================
Doble de prueba del SoapClient real: nunca toca la red, solo
devuelve respuestas enlatadas con la misma forma que el SRI.
=============================================*/
class FakeSoapClient
{
    private array $respuestas;
    private int $llamada = 0;

    public function __construct(array $respuestas)
    {
        $this->respuestas = $respuestas;
    }

    public function validarComprobante($args)
    {
        return $this->respuestas[$this->llamada++];
    }

    public function autorizacionComprobante($args)
    {
        return $this->respuestas[$this->llamada++];
    }
}

/*=============================================
Permite inyectar el FakeSoapClient sin tocar red ni WSDL real.
soapClient() se volvió protected específicamente para esto.
=============================================*/
class TestableSriController extends SriController
{
    private $fakeClient;

    public function setFakeClient($client)
    {
        $this->fakeClient = $client;
    }

    protected function soapClient($wsdl)
    {
        return $this->fakeClient;
    }
}

class SriControllerTest extends TestCase
{
    private string $xmlFirmadoPath;

    protected function setUp(): void
    {
        $this->xmlFirmadoPath = tempnam(sys_get_temp_dir(), 'xml_firmado_') . '.xml';
        file_put_contents($this->xmlFirmadoPath, '<factura>contenido de prueba</factura>');
    }

    protected function tearDown(): void
    {
        if (file_exists($this->xmlFirmadoPath)) {
            unlink($this->xmlFirmadoPath);
        }
    }

    private function respuestaRecepcion(string $estado, $comprobantes = null): stdClass
    {
        $resp = new stdClass();
        $resp->RespuestaRecepcionComprobante = new stdClass();
        $resp->RespuestaRecepcionComprobante->estado = $estado;
        if ($comprobantes !== null) {
            $resp->RespuestaRecepcionComprobante->comprobantes = $comprobantes;
        }
        return $resp;
    }

    private function respuestaAutorizacion($autorizaciones): stdClass
    {
        $resp = new stdClass();
        $resp->RespuestaAutorizacionComprobante = new stdClass();
        if ($autorizaciones !== null) {
            $resp->RespuestaAutorizacionComprobante->autorizaciones = $autorizaciones;
        }
        return $resp;
    }

    /*=============================================
    enviarComprobante()
    =============================================*/

    public function testEnviarComprobanteLanzaExcepcionSiElArchivoNoExiste()
    {
        $sri = new TestableSriController();
        $this->expectException(Exception::class);
        $sri->enviarComprobante('/ruta/que/no/existe.xml');
    }

    public function testEnviarComprobanteRecibida()
    {
        $sri = new TestableSriController();
        $sri->setFakeClient(new FakeSoapClient([
            $this->respuestaRecepcion('RECIBIDA'),
        ]));

        $resultado = $sri->enviarComprobante($this->xmlFirmadoPath);

        $this->assertSame('RECIBIDA', $resultado['estado']);
        $this->assertSame([], $resultado['mensajes']);
    }

    public function testEnviarComprobanteDevueltaConcatenaMensajeDeUnSoloComprobante()
    {
        // El SRI a veces devuelve un único <comprobante> (no array) cuando
        // solo se envía un documento: el código debe soportar ambas formas.
        $mensaje = new stdClass();
        $mensaje->identificador = '70';
        $mensaje->mensaje = 'CLAVE ACCESO REGISTRADA';
        $mensaje->informacionAdicional = 'la clave ya fue recibida';

        $comprobante = new stdClass();
        $comprobante->mensajes = new stdClass();
        $comprobante->mensajes->mensaje = $mensaje;

        $comprobantes = new stdClass();
        $comprobantes->comprobante = $comprobante;

        $sri = new TestableSriController();
        $sri->setFakeClient(new FakeSoapClient([
            $this->respuestaRecepcion('DEVUELTA', $comprobantes),
        ]));

        $resultado = $sri->enviarComprobante($this->xmlFirmadoPath);

        $this->assertSame('DEVUELTA', $resultado['estado']);
        $this->assertSame(['70 CLAVE ACCESO REGISTRADA la clave ya fue recibida'], $resultado['mensajes']);
    }

    /*=============================================
    autorizarComprobante()
    =============================================*/

    public function testAutorizarComprobanteAutorizadoEnElPrimerIntentoNoReintenta()
    {
        $aut = new stdClass();
        $aut->estado = 'AUTORIZADO';
        $aut->numeroAutorizacion = '1234567890';
        $aut->fechaAutorizacion = '2026-07-12T10:00:00-05:00';
        $aut->ambiente = 'PRUEBAS';
        $aut->comprobante = '<factura>autorizada</factura>';

        $autorizaciones = new stdClass();
        $autorizaciones->autorizacion = $aut;

        $sri = new TestableSriController();
        $sri->setFakeClient(new FakeSoapClient([
            $this->respuestaAutorizacion($autorizaciones),
        ]));

        $inicio = microtime(true);
        $resultado = $sri->autorizarComprobante('clave-de-prueba');
        $duracion = microtime(true) - $inicio;

        $this->assertSame('AUTORIZADO', $resultado['estado']);
        $this->assertSame('1234567890', $resultado['numeroAutorizacion']);
        $this->assertLessThan(1, $duracion, 'un estado final no debería disparar sleep() de reintento');
    }

    public function testAutorizarComprobanteNoAutorizadoEsEstadoFinalConMensajes()
    {
        $mensaje = new stdClass();
        $mensaje->identificador = '43';
        $mensaje->mensaje = 'FIRMA INVALIDA';
        $mensaje->informacionAdicional = '';

        $aut = new stdClass();
        $aut->estado = 'NO AUTORIZADO';
        $aut->mensajes = new stdClass();
        $aut->mensajes->mensaje = $mensaje;

        $autorizaciones = new stdClass();
        $autorizaciones->autorizacion = $aut;

        $sri = new TestableSriController();
        $sri->setFakeClient(new FakeSoapClient([
            $this->respuestaAutorizacion($autorizaciones),
        ]));

        $resultado = $sri->autorizarComprobante('clave-de-prueba');

        $this->assertSame('NO AUTORIZADO', $resultado['estado']);
        $this->assertSame(['43 FIRMA INVALIDA'], $resultado['mensajes']);
    }

    public function testAutorizarComprobanteReintentaHastaObtenerEstadoFinal()
    {
        // Primer intento: el SRI todavía no tiene el comprobante procesado
        // (sin nodo autorizaciones) -> el código debe reintentar y no reventar.
        $sinProcesar = $this->respuestaAutorizacion(null);

        $aut = new stdClass();
        $aut->estado = 'AUTORIZADO';
        $aut->numeroAutorizacion = '999';
        $aut->fechaAutorizacion = '2026-07-12T10:00:05-05:00';
        $aut->ambiente = 'PRUEBAS';
        $aut->comprobante = '<factura>autorizada</factura>';
        $autorizaciones = new stdClass();
        $autorizaciones->autorizacion = $aut;
        $segundoIntento = $this->respuestaAutorizacion($autorizaciones);

        $sri = new TestableSriController();
        $sri->setFakeClient(new FakeSoapClient([$sinProcesar, $segundoIntento]));

        $resultado = $sri->autorizarComprobante('clave-de-prueba');

        $this->assertSame('AUTORIZADO', $resultado['estado']);
        $this->assertSame('999', $resultado['numeroAutorizacion']);
    }
}
