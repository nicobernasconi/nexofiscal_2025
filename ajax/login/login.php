<?php

// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/database.php");
// Iniciar la sesión si no está iniciada
// Verificar si la sesión ya está iniciada
if (session_status() == PHP_SESSION_NONE) {
    // Iniciar la sesión
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
$client = new Client();

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
            $sucursal = $response_data['usuario']['sucursal'];
            $vendedor = $response_data['usuario']['vendedor'];
            $punto_venta = $response_data['usuario']['punto_venta'];
            $_SESSION['usuario_nombre'] = $response_data['usuario']['nombre_usuario'];

            $_SESSION['empresa_id'] = $empresa['id'];
            $_SESSION['fecha_inicio_actividades'] = $empresa['fecha_inicio_actividades'];
            $_SESSION['iibb'] = $empresa['iibb'];
            $_SESSION['empresa_razon_social'] = $empresa['razon_social'];
            $_SESSION['cert'] = $empresa['cert'];
            $_SESSION['key'] = $empresa['key'];
            $_SESSION['empresa_nombre'] = $empresa['nombre'];
            $_SESSION['empresa_direccion'] = $empresa['direccion'];
            $_SESSION['tipo_iva'] = $empresa['tipo_iva'];
            $_SESSION['cuit'] = $empresa['cuit'];
            $_SESSION['logo'] = $empresa['logo'];
            $_SESSION['punto_venta'] =$punto_venta['numero'];
            $_SESSION['codigos_barras_inicio'] = $empresa['codigos_barras_inicio'];
            $_SESSION['codigos_barras_payload_int'] = $empresa['codigos_barras_payload_int'];
            $_SESSION['codigos_barras_payload_type'] = $empresa['codigos_barras_payload_type'];
            $_SESSION['codigos_barras_id_long'] = $empresa['codigos_barras_id_long'];
            $_SESSION['codigos_barras_long'] = $empresa['codigos_barras_long'];
            $_SESSION['sucursal_id'] = $sucursal['id'];
            $_SESSION['sucursal_nombre'] = $sucursal['nombre'];
            $_SESSION['direccion'] = $sucursal['direccion'];
            $_SESSION['vendedor_id'] = $vendedor['id'];
            $_SESSION['vendedor_nombre'] = $vendedor['nombre'];
            $_SESSION['permisos'] = $response_data['usuario']['permisos'];
            $_SESSION['venta_rapida'] = $response_data['usuario']['venta_rapida'];
            $_SESSION['imprimir'] = $response_data['usuario']['imprimir'];
            $_SESSION['tipo_comprobante_imprimir'] = $response_data['usuario']['tipo_comprobante_imprimir'];
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
