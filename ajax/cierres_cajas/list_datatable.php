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
$url = $ruta.'api/cierres_cajas/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'usuario_id' => isset($searchValue) ? $searchValue :null,
        'fecha' => isset($searchValue) ? $searchValue :null,
        'usuario_nombre_completo' => isset($searchValue) ? $searchValue :null,
        'order_by' => $columnName,
        'sort_order' => $columnSortOrder,
        'limit'=>$rowperpage,
        'offset'=>$row 

    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);
     // Obtener los headers de respuesta
 
    $xTotalCount = $response->getHeaderLine('X-Total-Count')??0;
    $XPerPage= $response->getHeaderLine('X-Per-Page')??0;



    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();


    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);

    // Transformar los datos según el nuevo formato requerido
    $formattedData = [];
    foreach ($data as $item) {
 
        $formattedItem = [
            'id' => $item['id'],
            'total_gastos' => $item['total_gastos'] ,
            'total_ventas' => $item['total_ventas'] ,
            'efectivo_inicial' => $item['efectivo_inicial'] ,
            'efectivo_final' => $item['efectivo_final'] ,
            'usuario_id'=> $item['usuario']['id'],
            'usuario_nombre_completo'=> $item['usuario']['nombre'],
            'fecha'=> $item['fecha'],

            
        ];
        $formattedData[] = $formattedItem;
    }


    $response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $XPerPage,
    "iTotalDisplayRecords" => $xTotalCount,
    "aaData" => $formattedData,
    "url"=>$url


);
    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($response);
} catch (Exception $e) {

    echo json_encode($response);
}


