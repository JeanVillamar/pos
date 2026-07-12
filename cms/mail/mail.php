<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/FacturaMailer.php';

if (!class_exists('FacturaXMLParser')) {
    require_once __DIR__ . '/../GeneratePDFfromXML/FacturaXMLParser.php';
}

if (!class_exists('CurlController')) {
    require_once __DIR__ . '/../controllers/curl.controller.php';
}

function enviarcorreo(string $rutaPDF, string $rutaXML, string $mailClient, string $mailtransmitter): void
{
    $rutaConfig = __DIR__ . '/../config/facturacion.config.php';

    if (!file_exists($rutaConfig)) {
        throw new Exception("No existe cms/config/facturacion.config.php con la configuración SMTP.");
    }

    $config = require $rutaConfig;
    $smtpConfig = $config['smtp'] ?? null;

    if (!$smtpConfig) {
        throw new Exception("No existe configuración SMTP en cms/config/facturacion.config.php.");
    }

    $parser = new FacturaXMLParser($rutaXML);
    $factura = $parser->datos;
    $emisor = obtenerInformationCorreo($factura['ruc'] ?? '');
    $marca = primerValorCorreo($emisor, [
        'name_comercial_information',
        'name_information'
    ]) ?: ($factura['razonSocial'] ?? 'Emisor');
    $razonSocial = primerValorCorreo($emisor, ['name_information']) ?: ($factura['razonSocial'] ?? $marca);
    $emailEmisor = primerValorCorreo($emisor, ['email_information']) ?: $mailtransmitter;
    $logoPath = resolverLogoCorreo(primerValorCorreo($emisor, [
        'logo_information',
        'img_information',
        'image_information',
        'picture_information'
    ]));
    $logoCid = $logoPath ? 'logo_emisor_factura' : null;
    $cliente = trim($factura['razonSocialComprador'] ?? '') ?: 'cliente';
    $numeroFactura = $factura['nFactura'] ?? '';
    $asunto = "Factura electrónica {$numeroFactura} - {$marca}";
    $datosCorreo = [
        'marca' => $marca,
        'razonSocial' => $razonSocial,
        'ruc' => primerValorCorreo($emisor, ['ruc_information']) ?: ($factura['ruc'] ?? ''),
        'email' => $emailEmisor,
        'telefono' => primerValorCorreo($emisor, ['phone_information', 'phone_office']),
        'direccionMatriz' => primerValorCorreo($emisor, ['address_matriz_information']) ?: ($factura['dirEmpresa'] ?? ''),
        'direccionEstablecimiento' => primerValorCorreo($emisor, ['address_establishment_information']),
        'sitioWeb' => primerValorCorreo($emisor, [
            'website_information',
            'web_information',
            'site_information',
            'url_information'
        ]),
        'logoCid' => $logoCid
    ];

    $cuerpoHTML = construirCorreoFactura($factura, $parser->claveAcceso, $datosCorreo);
    $altText = construirTextoFactura($factura, $parser->claveAcceso, $datosCorreo);

    $mailer = new FacturaMailer($smtpConfig);
    $mailer->setSenderName($marca);

    if (filter_var($emailEmisor, FILTER_VALIDATE_EMAIL)) {
        $mailer->addReplyTo($emailEmisor, $marca);
    }

    if ($logoPath && $logoCid) {
        $mailer->embedImage($logoPath, $logoCid, $marca);
    }

    if (
        $mailer->addRecipient($mailClient, $cliente)
            ->attachFactura($rutaPDF, $rutaXML, $numeroFactura)
            ->setBody($asunto, $cuerpoHTML, $altText)
            ->send()
    ) {
        echo "Comprobante enviado a {$mailClient}\n";
    } else {
        fwrite(STDERR, "Error al enviar comprobante a {$mailClient}\n");
    }
}

function obtenerInformationCorreo($ruc): array
{
    if (!$ruc || !class_exists('CurlController')) {
        return [];
    }

    $response = CurlController::request(
        "informations?select=*&linkTo=ruc_information&equalTo=" . urlencode($ruc),
        "GET",
        []
    );

    if (!$response || $response->status !== 200 || empty($response->results[0])) {
        return [];
    }

    return (array)$response->results[0];
}

function primerValorCorreo(array $data, array $keys): string
{
    foreach ($keys as $key) {
        if (!isset($data[$key])) {
            continue;
        }

        $value = trim((string)$data[$key]);

        if ($value !== '') {
            return urldecode($value);
        }
    }

    return '';
}

function resolverLogoCorreo($logo): ?string
{
    if (!$logo) {
        return null;
    }

    $logo = urldecode($logo);
    $path = parse_url($logo, PHP_URL_PATH) ?: $logo;
    $rutaCms = realpath(__DIR__ . '/..');
    $candidatos = [
        $logo,
        $rutaCms . '/' . ltrim($path, '/'),
        $rutaCms . '/views/assets/files/' . basename($path)
    ];

    foreach ($candidatos as $candidato) {
        if (!is_string($candidato) || !file_exists($candidato)) {
            continue;
        }

        $info = @getimagesize($candidato);
        $tipo = $info[2] ?? null;

        if (in_array($tipo, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF, IMAGETYPE_WEBP], true)) {
            return $candidato;
        }
    }

    return null;
}

function construirCorreoFactura(array $factura, string $claveAcceso, array $emisor): string
{
    $marca = htmlCorreo($emisor['marca'] ?? 'Emisor');
    $razonSocial = htmlCorreo($emisor['razonSocial'] ?? '');
    $ruc = htmlCorreo($emisor['ruc'] ?? '');
    $cliente = htmlCorreo($factura['razonSocialComprador'] ?? 'Cliente');
    $numeroFactura = htmlCorreo($factura['nFactura'] ?? '');
    $fechaEmision = htmlCorreo($factura['fechaEmision'] ?? '');
    $fechaAutorizacion = htmlCorreo($factura['fechaAutorizacion'] ?? '');
    $valorTotal = monedaCorreo($factura['valorTotal'] ?? 0);
    $direccion = htmlCorreo($emisor['direccionEstablecimiento'] ?: ($emisor['direccionMatriz'] ?? ''));
    $telefono = htmlCorreo($emisor['telefono'] ?? '');
    $email = htmlCorreo($emisor['email'] ?? '');
    $sitioWeb = htmlCorreo($emisor['sitioWeb'] ?? '');
    $claveAcceso = htmlCorreo($claveAcceso);
    $logo = !empty($emisor['logoCid'])
        ? '<img src="cid:' . htmlCorreo($emisor['logoCid']) . '" alt="' . $marca . '" style="display:block; max-width:180px; max-height:72px; margin:0 auto 14px;">'
        : '<div style="font-size:22px; line-height:28px; font-weight:700; color:#222; margin-bottom:14px;">' . $marca . '</div>';

    $contactItems = '';

    if ($direccion !== '') {
        $contactItems .= '<div style="margin-top:4px;">' . $direccion . '</div>';
    }
    if ($telefono !== '') {
        $contactItems .= '<div style="margin-top:4px;">Tel. ' . $telefono . '</div>';
    }
    if ($email !== '') {
        $contactItems .= '<div style="margin-top:4px;">' . $email . '</div>';
    }
    if ($sitioWeb !== '') {
        $contactItems .= '<div style="margin-top:4px;">' . $sitioWeb . '</div>';
    }

    return '<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Factura electrónica</title>
</head>
<body style="margin:0; padding:0; background:#f3f5f7; font-family:Arial, Helvetica, sans-serif; color:#1f2933;">
  <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="background:#f3f5f7; padding:24px 12px;">
    <tr>
      <td align="center">
        <table width="640" cellpadding="0" cellspacing="0" role="presentation" style="width:100%; max-width:640px; background:#ffffff; border-radius:10px; overflow:hidden; border:1px solid #e2e8f0; border-top:4px solid #16a34a;">
          <tr>
            <td style="padding:28px 28px 22px; text-align:center; background:#ffffff;">
              ' . $logo . '
              <div style="display:inline-block; padding:6px 12px; border-radius:999px; background:#ecfdf5; color:#047857; font-size:13px; font-weight:700;">Comprobante electrónico autorizado</div>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 26px;">
              <h1 style="margin:0 0 12px; font-size:22px; line-height:30px; color:#111827;">Hola, ' . $cliente . '</h1>
              <p style="margin:0; font-size:15px; line-height:24px; color:#334155;">
                Te compartimos tu factura electrónica emitida por <strong>' . $marca . '</strong>. Adjuntamos el RIDE en PDF y el XML autorizado por el SRI para tu respaldo.
              </p>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 26px;">
              <table width="100%" cellpadding="0" cellspacing="0" role="presentation" style="border-collapse:separate; border-spacing:0; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
                ' . filaResumenCorreo('Factura', $numeroFactura) . '
                ' . filaResumenCorreo('Fecha de emisión', $fechaEmision) . '
                ' . filaResumenCorreo('Fecha de autorización', $fechaAutorizacion) . '
                ' . filaResumenCorreo('Valor total', $valorTotal, true) . '
              </table>
            </td>
          </tr>
          <tr>
            <td style="padding:0 28px 26px;">
              <div style="font-size:13px; color:#64748b; margin-bottom:8px;">Clave de acceso</div>
              <div style="font-family:Consolas, Menlo, Monaco, monospace; font-size:12px; line-height:20px; color:#111827; background:#f8fafc; border:1px solid #e5e7eb; border-radius:8px; padding:12px; word-break:break-all;">
                ' . $claveAcceso . '
              </div>
            </td>
          </tr>
          <tr>
            <td style="padding:22px 28px; background:#f8fafc; border-top:3px solid #16a34a;">
              <div style="font-size:14px; line-height:22px; color:#334155;">
                <strong>' . $marca . '</strong><br>
                ' . ($razonSocial !== '' ? $razonSocial . '<br>' : '') . '
                ' . ($ruc !== '' ? 'RUC: ' . $ruc : '') . '
                ' . $contactItems . '
              </div>
              <p style="margin:18px 0 0; font-size:12px; line-height:19px; color:#64748b;">
                Este correo fue generado automáticamente. Si necesitas ayuda con tu comprobante, responde a este mensaje o contacta al emisor.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>';
}

function construirTextoFactura(array $factura, string $claveAcceso, array $emisor): string
{
    $marca = $emisor['marca'] ?? 'Emisor';
    $cliente = $factura['razonSocialComprador'] ?? 'Cliente';
    $numeroFactura = $factura['nFactura'] ?? '';
    $valorTotal = monedaCorreo($factura['valorTotal'] ?? 0);

    return "Hola, {$cliente}\n\n"
        . "{$marca} te comparte tu factura electrónica {$numeroFactura}.\n"
        . "Valor total: {$valorTotal}\n"
        . "Clave de acceso: {$claveAcceso}\n\n"
        . "Adjuntamos el RIDE en PDF y el XML autorizado por el SRI.";
}

function filaResumenCorreo(string $label, string $value, bool $strong = false): string
{
    $fontWeight = $strong ? '700' : '400';
    $valueColor = $strong ? '#047857' : '#111827';

    return '<tr>
      <td style="padding:12px 14px; width:42%; background:#f8fafc; border-bottom:1px solid #e5e7eb; font-size:13px; color:#64748b;">' . htmlCorreo($label) . '</td>
      <td style="padding:12px 14px; border-bottom:1px solid #e5e7eb; font-size:14px; color:' . $valueColor . '; font-weight:' . $fontWeight . ';">' . $value . '</td>
    </tr>';
}

function monedaCorreo($valor): string
{
    return '$ ' . number_format((float)$valor, 2, '.', ',');
}

function htmlCorreo($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
