<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

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
$url = $ruta . 'gestor_admin/api/ventas/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'nombre' => isset($searchValue) ? $searchValue :null,
        'distribuidor_id' => $_SESSION['distribuidor_id'],
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
        $boton_borrar='';
        $boton_modificar='';
        if (in_array('modificar', $permisos_asignados['agrupaciones'])) {
        $boton_borrar = '<button class="btn-cancelar btn-eliminar-agrupacion" data-id="'.$item['id'].'"><i class="fas fa-trash"></i></button>';
        }
         if (in_array('modificar', $permisos_asignados['agrupaciones'])) {
        $boton_modificar = '<button class="btn-guardar btn-seleccionar-agrupacion" data-id="'.$item['id'].'" ><i class="fas fa-pencil"></i></button>';
         }
        $formattedItem = [
            'id' => $item['id'],
            'nombre' => $item['nombre'],
            'numero' => $item['numero'],
            'acciones' => $boton_modificar.$boton_borrar
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


