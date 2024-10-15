<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/database.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchValue=$_GET['q']??'';

// URL de la API
$url = $ruta.'api/productos';

// Parámetros de la solicitud
$params = ['headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ],'query'=>
        [
            'descripcion' =>  isset($searchValue) ? $searchValue  : '',
            'codigo' =>  isset($searchValue) ? $searchValue  : '',
        ]
    
];

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);

    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();
    
    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);


    // Transformar los datos según el nuevo formato requerido
    $formattedData = array_map(function($item) {
        return [
            'id' => $item['id'],
            'text' => $item['codigo'].' - '.$item['descripcion'],
            'descripcion' => $item['descripcion'],
            'codigo' => $item['codigo'],
            'precio1' => $item['precio1'],
            'stock' => $item['stock'],

        ];
    }, $data);
    
    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($formattedData);
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    $productosError = [
   
];
echo json_encode($productosError);
}

