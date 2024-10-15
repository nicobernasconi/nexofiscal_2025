<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
session_start();
}

// Obtener los datos del arreglo $_POST
$post_data = $_POST;

// Eliminar comillas simples de los valores numéricos
foreach ($post_data as $key => $value) {
    if (is_numeric($value)) {
        $post_data[$key] = (float)$value; // Convertir a número en formato float
    }
}

// Convertir los datos a formato JSON
$post_json = json_encode($post_data);

// URL de la API
$url = $ruta . 'gestor_admin/api/sucursales/'.$_POST['id'].'/';
//eliminar el id del arreglo
unset($post_data['id']);

// Importar la clase GuzzleHTTP\Client
require '../../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {
    // Enviar la solicitud POST
    $response = $client->request('PUT', $url, [
        'body' => $post_json,
        'headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ]
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();

    // Mostrar la respuesta
    echo $body;
} catch (RequestException $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    if ($e->hasResponse()) {
        echo "Error: " . $e->getResponse()->getBody()->getContents();
    } else {
        echo "Error: " . $e->getMessage();
    }
}
?>
