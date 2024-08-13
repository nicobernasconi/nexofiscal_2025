<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_distribuidor');
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
$url = $ruta . 'administrator/api/productos/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'descripcion' => isset($searchValue) ? $searchValue : null,

        'distribuidor_id' => $_SESSION['distribuidor_id'],
        'empresa_id' => $_GET['empresa_id'],
        'sucursal_id' => $_GET['sucursal_id'],
        'familia_id' => isset($_GET['familia_id']) ? $_GET['familia_id'] : null,
        'order_by'=> $columnName,
        'sort_order'=> $columnSortOrder,
        'limit' => $rowperpage,
        'offset' => $row

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

    // Transformar los datos según el nuevo formato requerido
    $formattedData = [];
    foreach ($data as $item) {
        //consultar si el stock_actual contiene la sucursal actual
        $stock_actual_array = $item['stock_actual'];
        $stock_actual_array = array_filter($stock_actual_array, function ($v) {
            return $v['sucursal_id'] == $_GET['sucursal_id'];
        });
        $stock_actual = $stock_actual_array[0]['stock_actual'] ?? 0;
        $formattedItem = [
            'id' => $item['id'],
            'codigo' => $item['codigo'],
            'codigo_barra' => $item['codigo_barra'],
            'descripcion' => $item['descripcion'],
            'stock' => $stock_actual,
            'precio1' => ($item['precio1'] != '') ? '$'.$item['precio1'] : 0,
            'precio2' => ($item['precio2'] != '') ? '$'.$item['precio2'] : 0,
            'precio3' => ($item['precio3'] != '') ? '$'.$item['precio3'] : 0,
            'producto_balanza' => ($item['producto_balanza']==1)?'Si':'No',
            'tasa_iva' => $item['tasa_iva']['nombre'],
            'stock_minimo' => $item['stock_minimo'] ?? 0,
            'stock_pedido' => $item['stock_pedido'] ?? 0,


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
