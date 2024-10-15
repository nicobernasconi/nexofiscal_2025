<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

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
$url = $ruta . 'distribuidores_admin/api/informe_libro_iva_ventas/';

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
        'order_by' => 'id',
        'sort_order' => 'ASC',
        'param' => isset($_GET['param']) ? $_GET['param'] : null,
        'fecha_inicio' => isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null,
        'fecha_fin' => isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null,
        'empresa_id' => isset($_GET['empresa_id']) ? $_GET['empresa_id'] : null,

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
    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true)['data'];
    $resumen= json_decode($body, true)['resumen'];

    // Transformar los datos según el nuevo formato requerido
    $formattedData = [];
    foreach ($data as $item) {
   

        $formattedItem = [
            "dia" => $item['dia'],
            "numero_factura" => $item['numero_factura'],
            "cuit" => $item['cuit'],
            "cliente" => $item['cliente'],
            "ng21" => round($item['ng21'], 2),
            "ng105" => round($item['ng105'], 2),
            "ng0" => round($item['ng0'], 2),
            "int" => round($item['int'], 2),
            "iibb" => round($item['iibb'], 2),
            "iva21" => round($item['iva21'], 2),
            "iva105" => round($item['iva105'], 2),
            "iva0" => round($item['iva0'], 2),
            "total" => round($item['total'], 2),
        ];
        $formattedData[] = $formattedItem;
    }


    $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $XPerPage,
        "iTotalDisplayRecords" => $xTotalCount,
        "aaData" => $formattedData,
        "resumen" => $resumen,
        "url" => $url


    );
    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($response);
} catch (Exception $e) {
    $response = array(
        "status" => 500,
        "status_message" => "Error en el servidor",
        "error" => $e->getMessage()
    );

    echo json_encode($response);
}
