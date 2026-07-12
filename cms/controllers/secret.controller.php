<?php

class SecretController
{
	private const PREFIX = 'enc:v1:';

	static public function encrypt($plainText)
	{
		if ($plainText === null || $plainText === '') {
			return '';
		}

		if (self::isEncrypted($plainText)) {
			return $plainText;
		}

		$key = self::key();
		$iv = random_bytes(16);
		$cipherText = openssl_encrypt($plainText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
		$mac = hash_hmac('sha256', $iv . $cipherText, $key, true);

		return self::PREFIX . base64_encode($iv . $mac . $cipherText);
	}

	static public function decrypt($value)
	{
		if ($value === null || $value === '') {
			return '';
		}

		if (!self::isEncrypted($value)) {
			return $value;
		}

		$payload = base64_decode(substr($value, strlen(self::PREFIX)), true);

		if ($payload === false || strlen($payload) <= 48) {
			throw new Exception('La clave cifrada del certificado no tiene un formato válido.');
		}

		$key = self::key();
		$iv = substr($payload, 0, 16);
		$mac = substr($payload, 16, 32);
		$cipherText = substr($payload, 48);
		$expectedMac = hash_hmac('sha256', $iv . $cipherText, $key, true);

		if (!hash_equals($expectedMac, $mac)) {
			throw new Exception('No se pudo validar la clave cifrada del certificado.');
		}

		$plainText = openssl_decrypt($cipherText, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

		if ($plainText === false) {
			throw new Exception('No se pudo descifrar la clave del certificado.');
		}

		return $plainText;
	}

	static public function isEncrypted($value)
	{
		return is_string($value) && strpos($value, self::PREFIX) === 0;
	}

	static private function key()
	{
		$configPath = __DIR__ . '/../config/facturacion.config.php';
		$config = file_exists($configPath) ? require $configPath : array();
		$appKey = getenv('POS_APP_KEY') ?: ($config['app_key'] ?? '');

		if ($appKey === '') {
			throw new Exception('Define app_key en cms/config/facturacion.config.php para cifrar secretos.');
		}

		return hash('sha256', $appKey, true);
	}
}
