<?php

include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
session_start();
}

// Importar la clase GuzzleHTTP\Client
require '../../../vendor/autoload.php';

use GuzzleHttp\Client;

// Datos del usuario para autenticación (proporcionados por el formulario o de alguna otra fuente)
$usuario = $_POST['usuario'] ?? '';
$password = $_POST['password'] ?? '';

// URL del endpoint de inicio de sesión
$url = $ruta  .'gestor_admin/api/login_gestor';

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
            $_SESSION['empresa_id'] =999999999;
             $_SESSION['usuario_id'] = $response_data['gestor']['id'];
            $_SESSION['login_gestor'] = true;
            $_SESSION['nombre_gestor'] = $response_data['gestor']['nombre'];
            $_SESSION['gestor_id'] = $response_data['gestor']['id'];
            $_SESSION['logo_gestor'] = $response_data['gestor']['logo'];
            //gaudar tiempo actual en la sesion
            $_SESSION['time'] = time();
            echo $body;
        } else {
            echo $body;
        }
    } else {
        // Si ocurrió un error en la autenticación, mostrar el mensaje de error
        echo $body;
    }
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
