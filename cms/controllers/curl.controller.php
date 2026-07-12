<?php

class CurlController
{

	/*=============================================
	Peticiones a la API
	=============================================*/

	static public function request($url, $method, $fields)
	{

		$curl = curl_init();

		//configuramos el cURL con varias opciones al mismo tiempo mediante un array gracias a la función curl_setopt_array
		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://127.0.0.1:8001/' . $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $method,
			CURLOPT_POSTFIELDS => $fields,
			CURLOPT_HTTPHEADER => array(
				'Authorization: kbaksdhaisdh912312837sajhd12093ke'
			),
		));

		//ejecuta la petición y almacena la respuesta en $response
		$response = curl_exec($curl);
		//Se cierra la sesión de cURL para liberar recursos.
		curl_close($curl);
		//Convierte la respuesta JSON de la API en un objeto PHP.
		$response = json_decode($response);
		return $response;
	}

	/*=============================================
	Peticiones a la API de ChatGPT
	=============================================*/

	static public function chatGPT($content, $token, $org)
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
		    "model": "gpt-4-0613",
		    "messages":[{"role": "user", "content": "' . $content . '"}]
		}',
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $token,
				'OpenAI-Organization: ' . $org,
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$response = json_decode($response);
		return $response->choices[0]->message->content;
	}

	/*=============================================
	Conexión a la impresora
	=============================================*/

	static public function ticketPrint($idOrder, $name)
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://b47a-2800-bf0-80e6-e3e-49da-25c6-1777-1ea1.ngrok-free.app/pos/printer/?order=' . urlencode($idOrder) . "&name=" . urlencode($name),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
		));

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			error_log('ticketPrint: ' . curl_error($curl));
			curl_close($curl);
			return null;
		}

		curl_close($curl);
		return json_decode($response);
	}


	/*=============================================
	Impresión local: requiere un servidor/spooler de impresión
	escuchando en http://localhost/pos/printer/ (no disponible
	en este entorno de desarrollo macOS). Ante cualquier fallo
	se registra en el log y se continúa sin interrumpir la venta.
	=============================================*/
	static public function ticketPrintLocal($idOrder, $name)
	{

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'http://localhost/pos/printer/?order=' . urlencode($idOrder) . "&name=" . urlencode($name),
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 5,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Authorization: kbaksdhaisdh912312837sajhd12093ke'
			),
		));

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			error_log('ticketPrintLocal: ' . curl_error($curl));
			curl_close($curl);
			return null;
		}

		curl_close($curl);
		return json_decode($response);
	}
}
