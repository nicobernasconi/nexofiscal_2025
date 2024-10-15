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
$url_install = $_POST['url'] ?? '';


// URL del endpoint de inicio de sesión
$url = $ruta . 'api/instalar';

// Datos para enviar en la solicitud POST
$post_data = [
    'url' => $url_install
];

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {
    // Enviar la solicitud POST
    $response = $client->request('POST', $url, [
        'json' => $post_data
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();

    header('Content-Type: application/json');

    echo $body;
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
