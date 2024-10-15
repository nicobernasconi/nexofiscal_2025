<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_distribuidor');
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'distribuidores_admin/api/usuarios/' ;

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'param' => isset($_GET['param']) ? $_GET['param'] :null,
        'nombre_usuario' => isset($_GET['nombre_usuario']) ? $_GET['nombre_usuario'] : null,
        'nombre_completo' => isset($_GET['nombre_completo']) ? $_GET['nombre_completo'] : null,
        'distribuidor_id' => $_SESSION['distribuidor_id']
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
            'nombre_usuario' => $item['nombre_usuario'],
            'nombre_completo' => $item['nombre_completo'],
            'rol_id' => $item['rol']['id'],
            'rol_nombre' => $item['rol']['descripcion'],
            'estado' => $item['estado']??0,
            'empresa_id' => $item['empresa_id'],
            'sucursal_id' => $item['sucursal']['id'],
            'lista_precios' => $item['lista_precios']??1,
            'venta_rapida' => $item['venta_rapida']??0,
            'imprimir' => $item['imprimir']??1,
            'tipo_comprobante_imprimir' => $item['tipo_comprobante_imprimir']??'1',
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
