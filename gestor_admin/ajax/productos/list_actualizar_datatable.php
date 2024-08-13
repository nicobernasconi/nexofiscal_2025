<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
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
$url = $ruta . 'gestor_admin/api/actualizar_precios';

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
        'familia_id' => $_GET['familia_id'] ,
        'porcentaje1' => isset($_GET['porcentaje1']) ? $_GET['porcentaje1'] : null,
        'porcentaje2' => isset($_GET['porcentaje2']) ? $_GET['porcentaje2'] : null,
        'porcentaje3' => isset($_GET['porcentaje3']) ? $_GET['porcentaje3'] : null,
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

        $formattedItem = [
            'descripcion' => $item['descripcion'],
            'codigo' => $item['codigo'],
            'precio1' => ($item['precio1'] != '') ? '$'.$item['precio1'] : 0,
            'precio2' => ($item['precio2'] != '') ? '$'.$item['precio2'] : 0,
            'precio3' => ($item['precio3'] != '') ? '$'.$item['precio3'] : 0,



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
