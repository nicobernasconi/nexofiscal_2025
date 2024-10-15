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
$empresa_id=$_GET['id'];

// Importar la clase GuzzleHttp\Client
require '../../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta.'/api/sucursales/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'empresa_id' => $empresa_id,
        'nombre' => isset($searchValue) ? $searchValue :null,
        'distribuidor_id' => $_SESSION['distribuidor_id'],
        'order_by' => $columnName,
        'sort_order' => $columnSortOrder,
'limit'=>$rowperpage,
        'offset'=>$row 

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
  
        $boton_modificar_datos  = '<button class="btn btn-success btn-seleccionar-sucursal  data-toggle="modal" data-target=".bs-sucursal-crear-modal-lg" data-id="'.$item['id'].'" ><i class="fa fa-pencil"></i></button>';
        $boton_borrar = '<button class="btn btn-danger btn-eliminar-sucursal" data-id="'.$item['id'].'" ><i class="fa fa-trash"></i></button>';
        $boto_usuario = '<a href="usuarios.php?id='.$item['id'].'&empresa_id='.$item['empresa_id'].'" class="btn btn-info" data-id="'.$item['id'].'" ><i class="fa fa-users"></i></a>';
        $formattedItem = [
            'id' => $item['id'],
            'empresa_id' => $empresa_id,
            'nombre' => $item['nombre'],
            'direccion' => $item['direccion'],
            'telefono' => $item['telefono'],
            'email' => $item['email'],
            'contacto_nombre' => $item['contacto_nombre'],
            'contacto_telefono' => $item['contacto_telefono'],
            'contacto_email' => $item['contacto_email'],
            'referente_nombre' => $item['referente_nombre'],
            'referente_telefono' => $item['referente_telefono'],
            'referente_email' => $item['referente_email'],
            'acciones' => $boton_modificar_datos.$boton_borrar.$boto_usuario
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


