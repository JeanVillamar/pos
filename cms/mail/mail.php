<?php
// main.php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/FacturaMailer.php';

function enviarcorreo(string $rutaPDF, string $rutaXML, string $mailClient, string $mailtransmitter ): void {
    
    // 1) Config SMTP
    $smtpConfig = [
        'host'     => 'smtp.gmail.com',
        'user'     => $mailtransmitter,
        'pass'     => 'viyo ompz gxcv zvvy',     // ojo: tenés que crear un “App Password” en tu cuenta Google
        'port'     =>  587,
        'secure'   =>  'tls',
        'from'     => $mailtransmitter,
        'fromName' => 'smartposline',
    ];

    // 2) Rutas y mailClient
    // $rutaPDF   = __DIR__ . '/factura.pdf';
    // $rutaXML   = __DIR__ . '/factura.xml';
    //$mailClient    = $mailClient; // o pásalo por argv, $_GET, etc.
    $asunto    = 'Tu comprobante electronico';
    $cuerpoHTML = '
    <p>Hola,</p>
    <p>Adjunto tenés tu factura en PDF y el XML para enviar al SRI.</p>
    <p>Saludos.</p>
';
    $altText   = 'Hola, adjunto tu factura en PDF y XML.';

    // 3) Ejecutar envío
    $mailer = new FacturaMailer($smtpConfig);

    if (
        $mailer->addRecipient($mailClient)
        ->attachFactura($rutaPDF, $rutaXML)
        ->setBody($asunto, $cuerpoHTML, $altText)
        ->send()
    ) {
        echo "✅ mailClient enviado a {$mailClient}\n";
    } else {
        fwrite(STDERR, "❌ Error al enviar mailClient a {$mailClient}\n");
    }
    
}

// enviarmailClient('factura.xml', 'factura.pdf','jeanfrank_2020@hotmail.com');