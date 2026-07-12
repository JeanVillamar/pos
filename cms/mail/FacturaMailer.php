<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



class FacturaMailer
{
    private PHPMailer $mailer;

    public function __construct(array $smtpConfig)
    {        
        $this->mailer = new PHPMailer(true);
        $this->mailer->CharSet = 'UTF-8';
        $this->mailer->Encoding = 'base64';
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

    public function setSenderName(string $name): self
    {
        if (trim($name) !== '') {
            $this->mailer->FromName = $name;
        }

        return $this;
    }

    public function addReplyTo(string $email, string $name = ''): self
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->mailer->addReplyTo($email, $name);
        }

        return $this;
    }

    public function addRecipient(string $email, string $name = ''): self
    {
        $this->mailer->addAddress($email, $name);
        return $this;
    }

    public function attachFactura(string $pdfPath, string $xmlPath, string $numeroFactura = ''): self
    {
        $suffix = $numeroFactura !== '' ? '-' . preg_replace('/[^0-9A-Za-z_-]/', '-', $numeroFactura) : '';

        $this->mailer->addAttachment($pdfPath, 'factura' . $suffix . '.pdf');
        $this->mailer->addAttachment($xmlPath, 'factura' . $suffix . '.xml');
        return $this;
    }

    public function embedImage(string $path, string $cid, string $name = 'logo'): self
    {
        if (file_exists($path)) {
            $this->mailer->addEmbeddedImage($path, $cid, $name);
        }

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
