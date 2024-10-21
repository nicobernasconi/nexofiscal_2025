<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/database.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {

    session_start();
}

// Obtener los datos del arreglo $_POST
$post_data = $_POST;
//quitar id
unset($post_data['id']);

// Eliminar comillas simples de los valores numéricos, siempre y cuando no sean codigo, codigo de barras o codigo de barras 2
foreach ($post_data as $key => $value) {
    if (is_numeric($value) && $key != 'codigo' && $key != 'codigo_barras' && $key != 'codigo_barras2') {
        $post_data[$key] = (float)$value; // Convertir a número en formato float
    }
}

// Convertir los datos a formato JSON
$post_json = json_encode($post_data);

// URL de la API
$url = $ruta . 'api/productos';

// Importar la clase GuzzleHTTP\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {
    // Enviar la solicitud POST
    $response = $client->request('POST', $url, [
        'body' => $post_json,
        'headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ]
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();
    $id = json_decode($body)->id;



    $url = $ruta . 'api/productos/' . $id.'/';

    // Parámetros de la solicitud
    $params = [
        'headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ],
        "query" => [
            'param' => $id,

        ],
    ];

    // Crear una instancia del cliente Guzzle
    $client = new Client([
        'verify' => false,
    ]);


    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);

    // Obtener el cuerpo de la respuesta en formato JSON
    $bodyprudu = $response->getBody()->getContents();

    $url_remoto = str_replace($ruta, '', $url);
    insertUpdateRemoto($con, $post_json, 'productos', 'add', 'POST', $id, $url_remoto, $_SESSION['token'], $local);


    $stock = json_decode($bodyprudu)[0]->stock;
    $id = json_decode($bodyprudu)[0]->id;
    $sucursal_id = $_SESSION['sucursal_id'];

    $client_stock = new Client( ['verify' => false,]);
            $movimiento_data = array(
                'producto_id' => $id,
                'sucursal_id' => $sucursal_id,
                'cantidad' =>  $stock,
            );


     
            $url_movimientos_stock = $ruta . 'api/stocks/';
            $url_movimientos_stock_remoto = $ruta_remota . 'api/stocks/';

            $post_json = json_encode($movimiento_data);

            $response_stock = $client_stock->request('POST', $url_movimientos_stock, [
                'body' => $post_json,
                'headers' => [
                    'Content-Type' => 'application/json',
                    // Obtener el token de seguridad de las variables de sesión
                    'Authorization' => 'Bearer ' . $_SESSION['token']
                ]
            ]);

            insertUpdateRemoto($con, $post_json, 'stocks', 'add', 'POST', $id, $url_movimientos_stock_remoto, $_SESSION['token'], $local);

    //quito la ruta para generar la url de la api
   

    // Mostrar la respuesta
    echo $body;
} catch (RequestException $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    if ($e->hasResponse()) {
        echo "Error: " . $e->getResponse()->getBody()->getContents();
    } else {
        echo "Error: " . $e->getMessage();
    }
}
