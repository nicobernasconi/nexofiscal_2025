<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/database.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    
session_start();
}

// Obtener los datos del arreglo $_POST
$post_data = $_POST;

// Convertir los datos a formato JSON
$post_json = json_encode($post_data);

// URL de la API
$url = $ruta.'api/promociones/'.$_POST['id'].'/';


// Importar la clase GuzzleHTTP\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud POST
    $response = $client->request('DELETE', $url, [
        'body' => $post_json,
        'headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ]
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();
    $id = json_decode($body)->id;
    //quito la ruta para generar la url de la api
    $url_remoto = str_replace($ruta,'',$url); 
    insertUpdateRemoto($con, $post_json,'promociones','delete','DELETE',$id, $url_remoto, $_SESSION['token'], $local);
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