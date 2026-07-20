<?php

require_once __DIR__ . '/curl.controller.php';

/*=============================================
Envoltorio delgado sobre CurlController::request() que elimina el
boilerplate repetido en cada llamada (declarar $method/$fields,
codificar el body en PUT). No cambia el contrato de retorno: sigue
devolviendo el mismo stdClass con ->status/->results que ya devuelve
CurlController::request(), así que es un reemplazo directo.
=============================================*/
class ApiClient
{
    public static function get($url)
    {
        return CurlController::request($url, "GET", []);
    }

    public static function post($url, $fields = [])
    {
        return CurlController::request($url, "POST", $fields);
    }

    public static function put($url, $fields = [])
    {
        return CurlController::request($url, "PUT", http_build_query($fields));
    }

    public static function delete($url)
    {
        return CurlController::request($url, "DELETE", []);
    }
}
