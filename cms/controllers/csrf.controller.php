<?php

/*=============================================
Protección CSRF para los <form method="POST"> que se envían por
navegación completa (login, pago del POS) y dependen únicamente
de la cookie de sesión para autenticar la petición.
=============================================*/
class CsrfController
{
    public static function token()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    public static function validate($token)
    {
        return !empty($_SESSION['csrf_token'])
            && is_string($token)
            && hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function field()
    {
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::token(), ENT_QUOTES) . '">';
    }
}
