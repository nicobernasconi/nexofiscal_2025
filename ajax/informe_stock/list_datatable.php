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
$url = $ruta . 'api/informe_stock/';

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
        'sucursal_id' => isset($_GET['sucursal_id']) ? $_GET['sucursal_id'] : null,
        'codigo' => isset($_GET['codigo']) ? $_GET['codigo'] : null,
        
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
            'id' => $item['id'],
            'codigo' => $item['codigo'],
            'descripcion' => $item['descripcion'],
            'precio1' => (is_null($item['precio1']))? '0': $item['precio1'],
            'precio2' => (is_null($item['precio2']))? '0': $item['precio2'],
            'precio3' => (is_null($item['precio3']))? '0': $item['precio3'],
            'stock_minimo' => $item['stock_minimo']?? '0',
            'stock_pedido' => $item['stock_pedido']?? '0',
            'sucursal_id' => $item['sucursal']['nombre'],
            'stock_actual' => $item['sucursal']['stock_actual'] ?? '0',
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
