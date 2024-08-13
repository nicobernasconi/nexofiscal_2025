<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_distribuidor');
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'distribuidores_admin/api/productos_export/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'descripcion' => isset($searchValue) ? $searchValue : null,
        'codigo' => isset($searchValue) ? $searchValue : null,
        'codigo_barra' => isset($searchValue) ? $searchValue : null,
        'distribuidor_id' => $_SESSION['distribuidor_id'],
        'empresa_id' => $_GET['empresa_id'],
        'sucursal_id' => $_GET['sucursal_id'],
        'type' => $_GET['type'],
    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);
    // Obtener los headers de respuesta

    $xTotalCount = $response->getHeaderLine('X-Total-Count') ?? 0;
    $XPerPage = $response->getHeaderLine('X-Per-Page') ?? 0;



    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();


    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);



    // Devolver los datos en formato JSON
    echo json_encode($data);
} catch (Exception $e) {

    echo json_encode($data);
}
