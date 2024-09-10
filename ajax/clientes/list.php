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

// URL de la API
$url = $ruta . 'api/clientes';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'param' => isset($_GET['param']) ? $_GET['param'] : null,
        'nro_cliente' => isset($_GET['nro_cliente']) ? $_GET['nro_cliente'] : null,
        'nombre' => isset($_GET['nombre']) ? $_GET['nombre'] : null,
        'tipo_iva_id' => isset($_GET['tipo_iva_id']) ? $_GET['tipo_iva_id'] : null,
        'cuit' => isset($_GET['cuit']) ? $_GET['cuit'] : null,
        'tipo_documento_id' => isset($_GET['tipo_documento_id']) ? $_GET['tipo_documento_id'] : null,
        'numero_documento' => isset($_GET['numero_documento']) ? $_GET['numero_documento'] : null,
        'direccion_comercial' => isset($_GET['direccion_comercial']) ? $_GET['direccion_comercial'] : null,
        'direccion_entrega' => isset($_GET['direccion_entrega']) ? $_GET['direccion_entrega'] : null,
        'localidad_id' => isset($_GET['localidad_id']) ? $_GET['localidad_id'] : null,
        'telefono' => isset($_GET['telefono']) ? $_GET['telefono'] : null,
        'celular' => isset($_GET['celular']) ? $_GET['celular'] : null,
        'email' => isset($_GET['email']) ? $_GET['email'] : null,
        'contacto' => isset($_GET['contacto']) ? $_GET['contacto'] : null,
        'telefono_contacto' => isset($_GET['telefono_contacto']) ? $_GET['telefono_contacto'] : null,
        'categoria_id' => isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null,
        'vendedor_id' => isset($_GET['vendedor_id']) ? $_GET['vendedor_id'] : null,
        'porcentaje_descuento' => isset($_GET['porcentaje_descuento']) ? $_GET['porcentaje_descuento'] : null,
        'limite_credito' => isset($_GET['limite_credito']) ? $_GET['limite_credito'] : null,
        'saldo_inicial' => isset($_GET['saldo_inicial']) ? $_GET['saldo_inicial'] : null,
        'saldo_actual' => isset($_GET['saldo_actual']) ? $_GET['saldo_actual'] : null,
        'fecha_ultima_compra' => isset($_GET['fecha_ultima_compra']) ? $_GET['fecha_ultima_compra'] : null,
        'fecha_ultimo_pago' => isset($_GET['fecha_ultimo_pago']) ? $_GET['fecha_ultimo_pago'] : null,
        'percepcion_iibb' => isset($_GET['percepcion_iibb']) ? $_GET['percepcion_iibb'] : null,
        'desactivado' => isset($_GET['desactivado']) ? $_GET['desactivado'] : null,
        'categoria_iibb_id' => isset($_GET['categoria_iibb_id']) ? $_GET['categoria_iibb_id'] : null,
    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);

    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();

    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);

    // Transformar los datos según el nuevo formato requerido
    $formattedData = array_map(function ($item) {
        return [
            'id' => $item['id'],
            'nro_cliente' => $item['nro_cliente'],
            'nombre' => $item['nombre'],
            'tipo_iva_id' => $item['tipo_iva']['id'],
            'tipo_iva_nombre' => $item['tipo_iva']['nombre'],
            'cuit' => $item['cuit'],
            'tipo_documento_id' => $item['tipo_documento']['id'],
            'tipo_documento_nombre' => $item['tipo_documento']['nombre'],
            'numero_documento' => $item['numero_documento'],
            'direccion_comercial' => $item['direccion_comercial'],
            'direccion_entrega' => $item['direccion_entrega'],
            'localidad_id' => $item['localidad']['id'],
            'localidad_nombre' => $item['localidad']['nombre'],
            'telefono' => $item['telefono'],
            'celular' => $item['celular'],
            'email' => $item['email'],
            'contacto' => $item['contacto'],
            'telefono_contacto' => $item['telefono_contacto'],
            'categoria_id' => $item['categoria']['id'],
            'categoria_nombre' => $item['categoria']['nombre'],
            'porcentaje_descuento' => $item['porcentaje_descuento'],
            'limite_credito' => $item['limite_credito'],
            'saldo_inicial' => $item['saldo_inicial'],
            'saldo_actual' => $item['saldo_actual'],
            'fecha_ultima_compra' => $item['fecha_ultima_compra'],
            'fecha_ultimo_pago' => $item['fecha_ultimo_pago'],
            'percepcion_iibb' => $item['percepcion_iibb'],
            'desactivado' => $item['desactivado'],

        ];
    }, $data);

    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($formattedData);
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
