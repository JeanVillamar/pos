<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class FacturaMailer
{
    private PHPMailer $mailer;

    public function __construct(array $smtpConfig)
    {        
        $this->mailer = new PHPMailer(true);
        $this->configureSMTP($smtpConfig);
    }

    private function configureSMTP(array $cfg): void
    {
        $this->mailer->isSMTP();
        $this->mailer->Host       = $cfg['host'];
        $this->mailer->SMTPAuth   = true;
        $this->mailer->Username   = $cfg['user'];
        $this->mailer->Password   = $cfg['pass'];
        $this->mailer->SMTPSecure = $cfg['secure'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port       = $cfg['port']   ?? 587;
        $this->mailer->setFrom($cfg['from'], $cfg['fromName'] ?? '');
    }

    public function addRecipient(string $email, string $name = ''): self
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    public function attachFactura(string $pdfPath, string $xmlPath): self
    {
        $this->mailer->addAttachment($pdfPath, 'factura.pdf');
        $this->mailer->addAttachment($xmlPath, 'factura.xml');
        return $this;
    }

    public function setBody(string $subject, string $htmlBody, string $altBody = ''): self
    {
        $this->mailer->isHTML(true);
        $this->mailer->Subject = $subject;
        $this->mailer->Body    = $htmlBody;
        if ($altBody) {
            $this->mailer->AltBody = $altBody;
        }
        return $this;
    }

    public function send(): bool
    {
        try {
            return $this->mailer->send();
        } catch (Exception $e) {
            // acá podrías loguear $e->getMessage()
            return false;
        }
    }
}
