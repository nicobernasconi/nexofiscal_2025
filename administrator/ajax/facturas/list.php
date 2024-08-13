<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta.'api/facturas';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
       
        'nro_factura' => isset($_GET['nro_factura']) ? $_GET['nro_factura'] :null,
        'proveedor_id' => isset($_GET['proveedor_id']) ? $_GET['proveedor_id'] :null,
        'sin_pagar' => '1',
    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);

    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();

    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);
    

    // Transformar los datos según el nuevo formato requerido
    $formattedData = array_map(function ($item) {
        return [
            'nro_factura' => $item['nro_factura'],
            'proveedor_id' => $item['proveedor_id'],
            'total' => $item['total'],
            'empresa_id' => $item['empresa_id'],
            'pagado' => $item['pagado']
        ];
    }, $data);

    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($formattedData);
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
