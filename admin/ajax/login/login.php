<?php

include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Importar la clase GuzzleHTTP\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// Datos del usuario para autenticación (proporcionados por el formulario o de alguna otra fuente)
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// URL del endpoint de inicio de sesión
$url = $ruta . 'api/login';

// Datos para enviar en la solicitud POST
$post_data = [
    'usuario' => $usuario,
    'password' => $password
];

// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {
    // Enviar la solicitud POST
    $response = $client->request('POST', $url, [
        'json' => $post_data
    ]);

    // Obtener el cuerpo de la respuesta
    $body = $response->getBody()->getContents();

    // Obtener el código de respuesta HTTP
    $http_status = $response->getStatusCode();
    header('Content-Type: application/json');
    // Manejar la respuesta
    if ($http_status == 200) {
        // Si la autenticación fue exitosa, guardar el token en las variables de sesión

        $response_data = json_decode($body, true);
        if ($response_data['status'] == '200') {
            $_SESSION['token'] = $response_data['token'];
            $_SESSION['usuario_id'] = $response_data['usuario_id'];
            $_SESSION['login'] = true;
            $empresa = $response_data['usuario']['empresa'];
            $_SESSION['empresa_id'] = $empresa['id'];
            $_SESSION['fecha_inicio_actividades'] = $empresa['fecha_inicio_actividades'];
            $_SESSION['iibb'] = $empresa['iibb'];
            $_SESSION['empresa_razon_social'] = $empresa['razon_social'];
            $_SESSION['direccion'] = $empresa['direccion'];
            $_SESSION['cert'] = $empresa['cert'];
            $_SESSION['key'] = $empresa['key'];
            $_SESSION['empresa_nombre'] = $empresa['nombre'];
            $_SESSION['tipo_iva'] = $empresa['tipo_iva'];
            $_SESSION['cuit'] = $empresa['cuit'];
            $_SESSION['direccion'] = $empresa['direccion'];
            $_SESSION['logo'] = $empresa['logo'];
            $_SESSION['punto_venta'] = 99;
            $configuraciones = $response_data['usuario']['configuraciones'];
            $_SESSION['permisos'] = $response_data['usuario']['permisos'];
            foreach ($configuraciones as $configuracion) {
                $_SESSION[$configuracion['campo']] = $configuracion['value'];
            }
            //gaudar tiempo actual en la sesion
            $_SESSION['time'] = time();
            echo $body;
        } else {
            $body;
        }
    } else {
        // Si ocurrió un error en la autenticación, mostrar el mensaje de error
        echo $body;
    }
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
