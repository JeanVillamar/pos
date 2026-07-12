<?php

/*=============================================
Carga variables de entorno desde el .env de la raíz del proyecto.
Sin dependencias externas (composer no está garantizado en todos los entornos).
=============================================*/

if (!function_exists("loadEnv")) {

	function loadEnv($path)
	{
		if (!file_exists($path)) {
			return;
		}

		foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {

			$line = trim($line);

			if ($line === "" || strpos($line, "#") === 0) {
				continue;
			}

			[$key, $value] = array_pad(explode("=", $line, 2), 2, "");

			$key = trim($key);
			$value = trim($value, " \t\n\r\0\x0B\"'");

			if ($key !== "" && getenv($key) === false) {
				putenv("$key=$value");
				$_ENV[$key] = $value;
			}
		}
	}
}

loadEnv(__DIR__ . "/../../.env");
