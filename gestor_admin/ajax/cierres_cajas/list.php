<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'gestor_admin/api/cierres_cajas';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'param' => isset($_GET['param']) ? $_GET['param'] :null,
        'usuario_id' => isset($_GET['usuario_id']) ? $_GET['usuario_id'] :null,

        
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
    $formattedData = array_map(function ($item) {
        return [
            'id' => $item['id'],
            'total_gastos' => $item['total_gastos'] ,
            'total_ventas' => $item['total_ventas'] ,
            'efectivo_inicial' => $item['efectivo_inicial'] ,
            'efectivo_final' => $item['efectivo_final'] ,
            'usuario_id'=> $item['usuario']['id'],
            'usuario_nombre'=> $item['usuario']['nombre'],
            'fecha'=> $item['fecha'],
            'comentarios'=> $item['comentarios'],
       
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
