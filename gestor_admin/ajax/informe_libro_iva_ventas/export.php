<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
session_start();
}


// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'gestor_admin/api/informe_libro_iva_ventas_export/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ],
     "query" => [
        'fecha_inicio' => isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null,
        'fecha_fin' => isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null,
        'empresa_id' => isset($_GET['empresa_id']) ? $_GET['empresa_id'] : null,
        'type' => isset($_GET['type']) ? $_GET['type'] : null,

    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);


try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);
    // Obtener los headers de respuesta

    $xTotalCount = $response->getHeaderLine('X-Total-Count') ?? 0;
    $XPerPage = $response->getHeaderLine('X-Per-Page') ?? 0;



    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();
    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($body);
} catch (Exception $e) {
    echo json_encode($body);
}

