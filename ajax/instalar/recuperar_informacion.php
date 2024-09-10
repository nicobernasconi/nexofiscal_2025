<?php

// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/database.php");
// Iniciar la sesión si no está iniciada
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
    session_start();
}

// Importar la clase GuzzleHTTP\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// Datos del usuario para autenticación (proporcionados por el formulario o de alguna otra fuente)
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// URL del endpoint de inicio de sesión
$url = $ruta_remota . 'api/recuperar_informacion';

// Datos para enviar en la solicitud POST
$post_data = [
    'usuario' => $usuario,
    'password' => $password
];

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud POST
    $response = $client->request('POST', $url, [
        'json' => $post_data
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();
    // Obtener el código de respuesta HTTP
    $http_status = $response->getStatusCode();
    header('Content-Type: application/json');
    // Manejar la respuesta
  
        // Si ocurrió un error en la autenticación, mostrar el mensaje de error
        echo $body;

} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
