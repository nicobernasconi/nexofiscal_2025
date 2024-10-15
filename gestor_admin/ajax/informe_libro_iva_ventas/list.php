<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
session_start();
}



// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'gestor_admin/api/informe_libro_iva_ventas/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ],
    "query" => [
        'param' => isset($_GET['param']) ? $_GET['param'] : null,
        'punto_venta' => isset($_GET['punto_venta']) ? $_GET['punto_venta'] : null,
        'cliente_id' => isset($_GET['cliente_id']) ? $_GET['cliente_id'] : null,
        'tipo_factura' => isset($_GET['tipo_factura']) ? $_GET['tipo_factura'] : null,
        'tipo_documento' => isset($_GET['tipo_documento']) ? $_GET['tipo_documento'] : null,
        'numero_de_documento' => isset($_GET['numero_de_documento']) ? $_GET['numero_de_documento'] : null,
        'fecha' => isset($_GET['fecha']) ? $_GET['fecha'] : null,
        'hora' => isset($_GET['hora']) ? $_GET['hora'] : null,
        'numero_factura' => isset($_GET['numero_factura']) ? $_GET['numero_factura'] : null,
        'total' => isset($_GET['total']) ? $_GET['total'] : null,
        'importe_iva' => isset($_GET['importe_iva']) ? $_GET['importe_iva'] : null,
        'vendedor_id' => isset($_GET['vendedor_id']) ? $_GET['vendedor_id'] : null,
        'observaciones_1' => isset($_GET['observaciones_1']) ? $_GET['observaciones_1'] : null,
        'observaciones_2' => isset($_GET['observaciones_2']) ? $_GET['observaciones_2'] : null,
        'usuario_id' => isset($_GET['usuario_id']) ? $_GET['usuario_id'] : null,
        'fecha_baja' => isset($_GET['fecha_baja']) ? $_GET['fecha_baja'] : null,
        'motivo_baja' => isset($_GET['motivo_baja']) ? $_GET['motivo_baja'] : null,
        'cierre_caja_id' => isset($_GET['cierre_caja_id']) ? $_GET['cierre_caja_id'] : null,
        'sucursal_id' => isset($_GET['sucursal_id']) ? $_GET['sucursal_id'] : null,
        'cliente_numero' => isset($_GET['cliente_numero']) ? $_GET['cliente_numero'] : null,
        'cliente_nombre' => isset($_GET['cliente_nombre']) ? $_GET['cliente_nombre'] : null,
        'tipo_iva_id' => isset($_GET['tipo_iva_id']) ? $_GET['tipo_iva_id'] : null,
        'cliente_cuit' => isset($_GET['cliente_cuit']) ? $_GET['cliente_cuit'] : null,
        'tipo_documento_id' => isset($_GET['tipo_documento_id']) ? $_GET['tipo_documento_id'] : null,
        'cliente_documento' => isset($_GET['cliente_documento']) ? $_GET['cliente_documento'] : null,
        'cliente_direccion' => isset($_GET['cliente_direccion']) ? $_GET['cliente_direccion'] : null,
        'cliente_telefono' => isset($_GET['cliente_telefono']) ? $_GET['cliente_telefono'] : null,
        'cliente_email' => isset($_GET['cliente_email']) ? $_GET['cliente_email'] : null,
        'tipo_iva_nombre' => isset($_GET['tipo_iva_nombre']) ? $_GET['tipo_iva_nombre'] : null,
        'vendedor_nombre' => isset($_GET['vendedor_nombre']) ? $_GET['vendedor_nombre'] : null,
        'sucursal_nombre' => isset($_GET['sucursal_nombre']) ? $_GET['sucursal_nombre'] : null,
        'sucursal_direccion' => isset($_GET['sucursal_direccion']) ? $_GET['sucursal_direccion'] : null,
        'sucursal_telefono' => isset($_GET['sucursal_telefono']) ? $_GET['sucursal_telefono'] : null,
        'sucursal_email' => isset($_GET['sucursal_email']) ? $_GET['sucursal_email'] : null,
        'sucursal_contacto_nombre' => isset($_GET['sucursal_contacto_nombre']) ? $_GET['sucursal_contacto_nombre'] : null,
        'sucursal_contacto_telefono' => isset($_GET['sucursal_contacto_telefono']) ? $_GET['sucursal_contacto_telefono'] : null,
        'sucursal_contacto_email' => isset($_GET['sucursal_contacto_email']) ? $_GET['sucursal_contacto_email'] : null,
        'tipo_comprobante_nombre' => isset($_GET['tipo_comprobante_nombre']) ? $_GET['tipo_comprobante_nombre'] : null,
        'tipo_comprobante_id' => isset($_GET['tipo_comprobante_id']) ? $_GET['tipo_comprobante_id'] : null,

    ],


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

    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($data);
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    echo "Error: " . $e->getMessage();
}
