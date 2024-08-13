<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    
session_start();
}

$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'api/informe_compras/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ],
     "query" => [
        'nombre' => isset($searchValue) ? $searchValue : null,
        'limit' => $rowperpage,
        'offset' => $row,
        'order_by' => $columnName,
        'sort_order' => $columnSortOrder,
        'param' => isset($_GET['param']) ? $_GET['param'] : null,
        'fecha_inicio' => isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null,
        'fecha_fin' => isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null,
        'sucursal_id' => isset($_GET['sucursal_id']) ? $_GET['sucursal_id'] : null,
        'producto_id' => isset($_GET['producto_id']) ? $_GET['producto_id'] : null,
        'proveedor_id' => isset($_GET['proveedor_id']) ? $_GET['proveedor_id'] : null,
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
    $data = json_decode($body, true)['data'];


    // Transformar los datos según el nuevo formato requerido
    $formattedData = [];
    foreach ($data as $item) {

        $formattedItem = [

            "fecha" => date('d-m-Y', strtotime($item['fecha'])),
            "costo" => '$'.$item['costo'],
            "cantidad" => $item['cantidad'],
            "nro_factura" => $item['nro_factura']??'Sin Asignar',
            "sucursal_id" => $item['sucursal']['nombre'],
            "proveedor_id" => $item['proveedor']['razon_social'],
            "producto_codigo" => $item['producto'],
            
        ];
        $formattedData[] = $formattedItem;
    }


    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $XPerPage,
        "iTotalDisplayRecords" => $xTotalCount,
        "aaData" => $formattedData,
        "url" => $url


    );
    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($response);
} catch (Exception $e) {

    echo json_encode($response);
}
