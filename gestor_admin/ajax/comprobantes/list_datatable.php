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
$url = $ruta . 'gestor_admin/api/comprobantes';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'numero_factura' => isset($searchValue) ? $searchValue :null,
        'cliente_id' => isset($searchValue) ? $searchValue :null,
        'sucursal_id'=> isset($_SESSION['sucursal_id']) ? $_SESSION['sucursal_id'] :null,
        'distribuidor_id' => $_SESSION['distribuidor_id'],
'limit'=>$rowperpage,
        'offset'=>$row ,
        'order_by'=>$columnName,
        'sort_order'=>$columnSortOrder 

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
            'numero' => $item['numero'],
            'numero_factura'=>($item['numero_factura']==0)?'--':$item['numero_factura'],
            'fecha' => date("d-m-Y", strtotime($item['fecha'])),
            'fecha_baja' =>$item['fecha_baja'],
            'cliente_nombre' => $item['cliente']['nombre'] .' ('.$item['cliente']['nro_cliente'].')',
            'total' => ($item['total']!='')?'$'.$item['total']:'$0',
            'tipo_comprobante' => $item['tipo_comprobante']['nombre'],
            'anulado'=>($item['fecha_baja']!=null),
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


