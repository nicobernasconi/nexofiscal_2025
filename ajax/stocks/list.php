<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/database.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta.'api/stocks/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'param' => isset($_GET['param']) ? $_GET['param'] :null,
        'sucursal_id' => isset($_GET['sucursal_id']) ? $_GET['sucursal_id'] :null,
        'codigo' => isset($_GET['codigo']) ? $_GET['codigo'] :null,
        
    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);

    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();

    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);

    // Transformar los datos según el nuevo formato requerido
    

    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($data);
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
     $productosError = [
    
];
echo json_encode($productosError);

}
