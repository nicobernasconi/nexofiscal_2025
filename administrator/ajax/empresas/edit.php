<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_distribuidor');
session_start();
}

// Obtener los datos del arreglo $_POST
$post_data = $_POST;

// Eliminar comillas simples de los valores numéricos
foreach ($post_data as $key => $value) {
    if (is_numeric($value)) {
        $post_data[$key] = (float)$value; // Convertir a número en formato float
    }
}

// Convertir los datos a formato JSON
$post_json = json_encode($post_data);
// Nuevo arreglo con los nombres de las columnas de la tabla empresas
$empresa_data = array(
    'id' => $post_data['id'],
    'nombre' => $post_data['nombre_empresa'],
    'direccion' => $post_data['direccion_empresa'],
    'telefono' => $post_data['telefono_empresa'],
    'tipo_iva' => $post_data['tipo_iva_id'],
    'cuit' => $post_data['cuit'],
    'responsable' => $post_data['responsable_empresa'],
    'email' => $post_data['email_empresa'],
    'fecha_inicio_actividades' => $post_data['fecha_inicio_actividades'],
    'descripcion' => $post_data['descripcion_empresa'],
    'razon_social' => $post_data['razon_social'],
    'iibb' => $post_data['iibb'],
);


// URL de la API
$url = $ruta . 'administrator/api/empresas/'.$_POST['id'].'/';
//eliminar el id del arreglo
unset($empresa_data['id']);

//convertir el arreglo a formato JSON
$empresa_json = json_encode($empresa_data);

// Importar la clase GuzzleHTTP\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud POST
    $response = $client->request('PUT', $url, [
        'body' => $empresa_json,
        'headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ]
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();

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
?>
