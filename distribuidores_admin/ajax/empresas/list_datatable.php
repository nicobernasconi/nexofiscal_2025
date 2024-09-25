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
require '../../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'distribuidores_admin/api/empresas/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'nombre' => isset($searchValue) ? $searchValue : null,
        'distribuidor_id' => $_SESSION['distribuidor_id'],
        'order_by' => $columnName,
        'sort_order' => $columnSortOrder,
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
        $boton_borrar = '';
        $boton_modificar = '';
        $boton_modificar_datos  = '<button class="btn btn-success btn-seleccionar-empresa" data-toggle="modal" data-target=".bs-empresa-editar-modal-lg" data-id="' . $item['id'] . '" ><i class="fa fa-pencil"></i></button>';
        $boton_modificar_certificados = '<button class="btn btn-info btn-editar-empresa-certificado" data-toggle="modal" data-target=".bs-empresa-certificado-editar-modal-lg" data-id="' . $item['id'] . '" ><i class="fa fa-certificate"></i></button>';
        $boton_sucursales = '<a href="sucursales.php?id=' . $item['id'] . '" class="btn btn-warning" ><i class="fa fa-building"></i></a>';
        $boton_puntos_ventas = '<a href="puntos_ventas.php?id=' . $item['id'] . '" class="btn btn-primary" ><i class="fa fa-money"></i></a>';
        $boton_licencias = '<a href="licencias.php?id=' . $item['id'] . '" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Licencias"><i class="fa fa-key"></i></a>';
        $formattedItem = [
            'id' => $item['id'],
            'nombre' => $item['nombre'],
            'email' => $item['email'],
            'telefono' => $item['telefono'],
            'cuit' => $item['cuit'],
            'tipo_iva' => $item['tipo_iva_nombre'],
            'inicio_actividad' => $item['fecha_inicio_actividades'],
            'acciones' => $boton_modificar_datos . $boton_modificar_certificados . $boton_sucursales . $boton_puntos_ventas. $boton_licencias
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
