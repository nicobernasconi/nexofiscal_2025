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
$url = $ruta . 'gestor_admin/api/productos/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'param' => isset($_GET['param']) ? $_GET['param'] :null,
        'codigo' => isset($_GET['codigo']) ? $_GET['codigo'] :null,
        'descripcion' => isset($_GET['descripcion']) ? $_GET['descripcion'] :null,
        'descripcion_ampliada' => isset($_GET['descripcion_ampliada']) ? $_GET['descripcion_ampliada'] :null,
        'familia_id' => isset($_GET['familia_id']) ? $_GET['familia_id'] :null,
        'subfamilia_id' => isset($_GET['subfamilia_id']) ? $_GET['subfamilia_id'] :null,
        'agrupacion_id' => isset($_GET['agrupacion_id']) ? $_GET['agrupacion_id'] :null,
        'marca_id' => isset($_GET['marca_id']) ? $_GET['marca_id'] :null,
        'codigo_barra' => isset($_GET['codigo_barra']) ? $_GET['codigo_barra'] :null,
        'proveedor_id' => isset($_GET['proveedor_id']) ? $_GET['proveedor_id'] :null,
        'fecha_alta' => isset($_GET['fecha_alta']) ? $_GET['fecha_alta'] :null,
        'fecha_actualizacion' => isset($_GET['fecha_actualizacion']) ? $_GET['fecha_actualizacion'] :null,
        'articulo_activado' => isset($_GET['articulo_activado']) ? $_GET['articulo_activado'] :null,
        'tipo_id' => isset($_GET['tipo_id']) ? $_GET['tipo_id'] :null,
        'producto_balanza' => isset($_GET['producto_balanza']) ? $_GET['producto_balanza'] :null,
        'precio1' => isset($_GET['precio1']) ? $_GET['precio1'] :null,
        'moneda_id' => isset($_GET['moneda_id']) ? $_GET['moneda_id'] :null,
        'tasa_iva' => isset($_GET['tasa_iva']) ? $_GET['tasa_iva'] :null,
        'incluye_iva' => isset($_GET['incluye_iva']) ? $_GET['incluye_iva'] :null,
        'impuesto_interno' => isset($_GET['impuesto_interno']) ? $_GET['impuesto_interno'] :null,
        'agrupacion_numero' => isset($_GET['agrupacion_numero']) ? $_GET['agrupacion_numero'] :null,
        'agrupacion_nombre' => isset($_GET['agrupacion_nombre']) ? $_GET['agrupacion_nombre'] :null,
        'familia_numero' => isset($_GET['familia_numero']) ? $_GET['familia_numero'] :null,
        'familia_nombre' => isset($_GET['familia_nombre']) ? $_GET['familia_nombre'] :null,
        'subfamilia_numero' => isset($_GET['subfamilia_numero']) ? $_GET['subfamilia_numero'] :null,
        'subfamilia_descripcion' => isset($_GET['subfamilia_descripcion']) ? $_GET['subfamilia_descripcion'] :null,
        'moneda_simbolo' => isset($_GET['moneda_simbolo']) ? $_GET['moneda_simbolo'] :null,
        'moneda_nombre' => isset($_GET['moneda_nombre']) ? $_GET['moneda_nombre'] :null,
        'moneda_cotizacion' => isset($_GET['moneda_cotizacion']) ? $_GET['moneda_cotizacion'] :null,
        'tipo_numero' => isset($_GET['tipo_numero']) ? $_GET['tipo_numero'] :null,
        'tipo_nombre' => isset($_GET['tipo_nombre']) ? $_GET['tipo_nombre'] :null,
        'proveedores_razon_social' => isset($_GET['proveedores_razon_social']) ? $_GET['proveedores_razon_social'] :null,
        'proveedores_direccion' => isset($_GET['proveedores_direccion']) ? $_GET['proveedores_direccion'] :null,
        'proveedores_localidad_id' => isset($_GET['proveedores_localidad_id']) ? $_GET['proveedores_localidad_id'] :null,
        'proveedores_telefono' => isset($_GET['proveedores_telefono']) ? $_GET['proveedores_telefono'] :null
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
    

    // Establecer la cabecera de respuesta como JSON
    header('Content-Type: application/json');

    // Devolver los datos en formato JSON
    echo json_encode($data);
} catch (Exception $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
     $productosError = [
    
];
echo json_encode($productosError);

}
