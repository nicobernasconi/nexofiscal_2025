<?php
// Incluye el autoload generado por Composer
require_once 'vendor/autoload.php';

include("includes/session_parameters.php");
include("includes/database.php");



use GuzzleHttp\Client;
// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

// Si se reciben datos por POST, se asignan a variables
$nombre_tienda = $_SESSION['empresa_razon_social'];
$direccion_tienda = $_SESSION['direccion'];
$telefono_tienda = isset($_POST['telefono_tienda']) ? $_POST['telefono_tienda'] : '';
$articulos = isset($_POST['articulos']) ? $_POST['articulos'] : '';
$total = isset($_POST['total']) ? $_POST['total'] : '';
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : '';
$monto_pagado = isset($_POST['monto_pagado']) ? $_POST['monto_pagado'] : '';
$monto_vuelto = isset($_POST['monto_vuelto']) ? $_POST['monto_vuelto'] : '';
$fecha = $fecha = date('Y-m-d');
$hora = date('H:i:s');
$cliente_nombre = isset($_POST['cliente_nombre']) ? $_POST['cliente_nombre'] : 'Ocacional';
$cliente_cuit = isset($_POST['cliente_cuit']) ? $_POST['cliente_cuit'] : '0';
$cliente_domicilio = isset($_POST['cliente_domicilio']) ? $_POST['cliente_domicilio'] : '-';
$tipo_comprobante_id = isset($_POST['tipo_comprobante_id']) ? $_POST['tipo_comprobante_id'] : '0';
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : '';
$vendedor_id = ($_POST['vendedor_id'] != '') ? $_POST['vendedor_id'] : null;
$numero_factura = isset($_POST['numero_factura']) ? $_POST['numero_factura'] : '';
$cae = isset($_POST['cae']) ? $_POST['cae'] : '';
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';
$letra_factura = isset($_POST['letra_factura']) ? $_POST['letra_factura'] : '';
$punto_venta = $_SESSION['punto_venta'];
$iibb = $_SESSION['iibb'];
$inicio_actividades = $_SESSION['fecha_inicio_actividades'];
$tipo_contribuyente = ($_SESSION['tipo_iva'] == 1) ? 'RESPONSABLE INSCRIPTO' : 'MONOTRIBUTO';
$url_pdf = $_POST['url_pdf'];
$sucursal_id = $_SESSION['sucursal_id'];
$concepto = isset($_POST['concepto']) ? $_POST['concepto'] : '';
$tipo_de_documento = isset($_POST['tipo_de_documento']) ? $_POST['tipo_de_documento'] : '';
$numero_de_documento = isset($_POST['numero_de_documento']) ? $_POST['numero_de_documento'] : '';
$tipo_factura = $_POST['tipo_factura'];
$qr = isset($_POST['qr']) ? $_POST['qr'] : '';
$importe_iva = isset($_POST['importe_iva']) ? $_POST['importe_iva'] : 0;
$importe_iva_105 = isset($_POST['importe_iva_105']) ? $_POST['importe_iva_105'] : 0;
$importe_iva_21 = isset($_POST['importe_iva_21']) ? $_POST['importe_iva_21'] : 0;
$importe_iva_0 = isset($_POST['importe_iva_27']) ? $_POST['importe_iva_27'] : 0;
$no_gravado_iva_105 = isset($_POST['no_gravado_iva_105']) ? $_POST['no_gravado_iva_105'] : 0;
$no_gravado_iva_21 = isset($_POST['no_gravado_iva_21']) ? $_POST['no_gravado_iva_21'] : 0;
$no_gravado_iva_0 = isset($_POST['no_gravado_iva_0']) ? $_POST['no_gravado_iva_0'] : 0;
$importe_impuesto_interno = isset($_POST['importe_impuesto_interno']) ? $_POST['importe_impuesto_interno'] : 0;




$cliente_id = (isset($_POST['cliente_id']) && $_POST['cliente_id'] != '') ? $_POST['cliente_id'] : 1;
$productos = isset($_POST['productos']) ? $_POST['productos'] : array();
$promociones = isset($_POST['promociones']) ? json_decode($_POST['promociones'], true) : array();


$url_clientes = $ruta . 'api/clientes/' . $cliente_id . '/';
// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ]
];

// Enviar la solicitud GET
$response = $client->request('GET', $url_clientes, $params);
// Obtener el cuerpo de la respuesta en formato JSON
$body = $response->getBody()->getContents();


// Decodificar el JSON en un array asociativo
$data = json_decode($body, true);

//generenar un string con las promociones ejemplo: [{"id":"1","name":"PROMO 1( 10%)","discount":"10"}] debe tener el formato name y un salto de linea
$promociones_string = '';
foreach ($promociones as $promocion) {
    $promociones_string .= $promocion['name'] . ' (' . $promocion['discount'] . '%)' . "\n";
}


$fecha = date('Ymd');

if ($tipo_comprobante == 'FACTURA') {
    // Crear un array con los datos de la comprobantes
    $comprobantes_data = array(
        'cliente_id' => $cliente_id,
        'fecha' => $fecha,
        'hora' => $hora,
        'fecha_proceso' => $fecha,
        'letra' =>  $letra_factura,
        'prefijo_factura' => $punto_venta,
        'total' => $total,
        'importe_iva' => $importe_iva,
        'total_pagado' => $monto_pagado,
        'tipo_comprobante_id' => $tipo_comprobante_id,
        'vendedor_id' => $vendedor_id,
        'observaciones_1' => $promociones_string,
        'cae' => $cae,
        'fecha_vencimiento' => $fecha_vencimiento,
        'numero_factura' => $numero_factura,
        'url_pdf' => $url_pdf,
        'sucursal_id' => $sucursal_id,
        'concepto' => $concepto,
        'tipo_documento' => $tipo_de_documento,
        'numero_de_documento' => $numero_de_documento,
        'tipo_factura' => $tipo_factura,
        'punto_venta' => $punto_venta,
        'qr' => $qr,
        'importe_iva_105' => $importe_iva_105,
        'importe_iva_21' => $importe_iva_21,
        'importe_iva_0' => $importe_iva_0,
        'no_gravado_iva_105' => $no_gravado_iva_105,
        'no_gravado_iva_21' => $no_gravado_iva_21,
        'no_gravado_iva_0' => $no_gravado_iva_0,
        'importe_impuesto_interno' => $importe_impuesto_interno,
        
    );
} else {
    $comprobantes_data = array(
        'cliente_id' => $cliente_id,
        'fecha' => $fecha,
        'hora' => $hora,
        'fecha_proceso' => $fecha,
        'letra' =>  $letra_factura,
        'prefijo_factura' => $punto_venta,
        'total' => $total,
        'total_pagado' => $monto_pagado,
        'tipo_comprobante_id' => $tipo_comprobante_id,
        'vendedor_id' => $vendedor_id,
        'observaciones_1' => $promociones_string,
        'url_pdf' => $url_pdf,
        'sucursal_id' => $sucursal_id,
        

    );
}

$client = new Client([
    'verify' => false,
]);

// Convertir los datos a formato JSON
$post_json = json_encode($comprobantes_data);


// URL de la API
$url = $ruta . 'api/comprobantes';
$url_remota = $ruta_remota . 'api/comprobantes';

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

    $bodyContents = $response->getBody()->getContents();

    //insertUpdateRemoto($con, $post_json, $url_remota, $_SESSION['token'],$local);
    $data = json_decode($bodyContents, true);


    $id = $data['id'];



    $url = $ruta . 'api/renglones_comprobantes/' . $id . '/';
    $url_remota = $ruta_remota . 'api/renglones_comprobantes/' . $id . '/';

    // Crear una instancia del cliente Guzzle
    $client = new Client([
    'verify' => false,
]);

    // Obtener el array de productos enviado por POST
    $productos = json_decode($_POST['productos'], true);
    $promociones = json_decode($_POST['promociones'], true);
    //sumar todos los discount de  promociones
    $total_discount = 0;
    foreach ($promociones as $promocion) {
        $total_discount += $promocion['discount'];
    }


    // Iterar sobre cada producto
    foreach ($productos as $producto) {
        // Crear el arreglo de datos para el producto
        $comprobantes_data = array(
            "producto_id" => $producto['id'],
            "descripcion" => $producto['name'],
            "cantidad" => $producto['quantity'],
            "precio_unitario" => $producto['price'],
            "tasa_iva" => $producto['tasa_iva'],
            "descuento" => $total_discount,
            "total_linea" => $producto['price'] * $producto['quantity'], // Calcular el total de la línea
            'tasa_iva' => $producto['tasa_iva'],

        );

        // Convertir el arreglo de datos a JSON
        $post_json = json_encode($comprobantes_data);



        // Enviar la solicitud POST
        $response = $client->request('POST', $url, [
            'body' => $post_json,
            'headers' => [
                'Content-Type' => 'application/json',
                // Obtener el token de seguridad de las variables de sesión
                'Authorization' => 'Bearer ' . $_SESSION['token']
            ]
        ]);
        $bodyContents = $response->getBody()->getContents();
        //insertUpdateRemoto($con, $post_json, $url_remota, $_SESSION['token'],$local);
        $data = json_decode($bodyContents, true);
        $status = $data['status'];

        if ($status == 201) {
            $client_stock = new Client([
    'verify' => false,
]);
            $movimiento_data = array(
                'producto_id' => $producto['id'],
                'sucursal_id' => $sucursal_id,
                'cantidad' => ((-1) * $producto['quantity']),
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

            //insertUpdateRemoto($con, $post_json, $url_movimientos_stock_remoto, $_SESSION['token'],$local);
        }
    }
} catch (\Throwable $th) {
}


echo json_encode(['pdfUrl' => $url_pdf]);
