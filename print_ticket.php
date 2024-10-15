<?php
// Incluye el autoload generado por Composer
require_once 'vendor/autoload.php';

include("includes/session_parameters.php");


use GuzzleHttp\Client;
// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

// Si se reciben datos por POST, se asignan a variables
$nombre_tienda = isset($_POST['nombre_tienda']) ? $_POST['nombre_tienda'] : '';
$direccion_tienda = isset($_POST['direccion_tienda']) ? $_POST['direccion_tienda'] : '';
$telefono_tienda = isset($_POST['telefono_tienda']) ? $_POST['telefono_tienda'] : '';
$articulos = isset($_POST['articulos']) ? $_POST['articulos'] : '';
$total = isset($_POST['total']) ? $_POST['total'] : '';
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : '';
$monto_pagado = isset($_POST['monto_pagado']) ? $_POST['monto_pagado'] : '';
$monto_vuelto = isset($_POST['monto_vuelto']) ? $_POST['monto_vuelto'] : '';
$fecha = isset($_POST['fecha']) ? date('d-m-Y', strtotime($_POST['fecha'])) : '';
$hora = isset($_POST['hora']) ? $_POST['hora'] : '';
$cliente_nombre = isset($_POST['cliente_nombre']) ? $_POST['cliente_nombre'] : 'Ocacional';
$cliente_cuit = isset($_POST['cliente_cuit']) ? $_POST['cliente_cuit'] : '0';
$cliente_domicilio = isset($_POST['cliente_domicilio']) ? $_POST['cliente_domicilio'] : '-';
$tipo_comprobante_id = isset($_POST['tipo_comprobante_id']) ? $_POST['tipo_comprobante_id'] : '0';
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : '';
$vendedor_id = ($_POST['vendedor_id'] != '') ? $_POST['vendedor_id'] : null;
$numero_factura = isset($_POST['numero_factura']) ? $_POST['numero_factura'] : '';
$cae= isset($_POST['cae']) ? $_POST['cae'] : '';
$fecha_vencimiento= isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';
$letra_factura= isset($_POST['letra_factura']) ? $_POST['letra_factura'] : '';
$punto_venta= isset($_POST['punto_venta']) ? $_POST['punto_venta'] : '';




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

if($tipo_comprobante=='FACTURA'){
// Crear un array con los datos de la comprobantes
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
    'cae' => $cae,
    'fecha_vencimiento' => $fecha_vencimiento,
    'numero_factura' => $numero_factura,
);}else{
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

);
}

$client = new Client([
    'verify' => false,
]);

// Convertir los datos a formato JSON
$post_json = json_encode($comprobantes_data);


// URL de la API
$url = $ruta . 'api/comprobantes';

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
    $data = json_decode($bodyContents, true);
    
    $id = $data['id'];
    if (($tipo_comprobante=='PEDIDO')) {
        //obtener año
        $year = date('Y');
        $nro_comprobante = "OC-" . $year . "-PV" . str_pad($punto_venta, 4, '0', STR_PAD_LEFT) . "-" . str_pad($id, 8, '0', STR_PAD_LEFT);
    } else {
        $nro_comprobante = "FC-".$letra_factura.'-'.str_pad($punto_venta, 4, '0', STR_PAD_LEFT) . "-" . str_pad($numero_factura, 8, '0', STR_PAD_LEFT);
    }
    $url = $ruta . 'api/renglones_comprobantes/' . $id . '/';

    // Crear una instancia del cliente Guzzle
    $client = new Client([
    'verify' => false,
]);

    // Obtener el array de productos enviado por POST
    $productos = json_decode($_POST['productos'], true);

    // Iterar sobre cada producto
    foreach ($productos as $producto) {
        // Crear el arreglo de datos para el producto
        $comprobantes_data = array(
            "producto_id" => $producto['id'],
            "descripcion" => $producto['name'],
            "cantidad" => $producto['quantity'],
            "precio_unitario" => $producto['price'],
            "descuento" => 0,
            "total_linea" => $producto['price'] * $producto['quantity'] // Calcular el total de la línea
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
        $data = json_decode($bodyContents, true);
        $status = $data['status'];

        if ($status == 201) {
            $producto_data = array(
                "stock" => $producto['quantity'], // Calcular el total de la línea
            );

            // Convertir el arreglo de datos a JSON
            $post_json = json_encode($producto_data);
            $url_stock = $ruta . 'api/productos/' . $producto['id'] . '/?venta=1';
            // Enviar la solicitud POST
            $response = $client->request('PUT', $url_stock, [
                'body' => $post_json,
                'headers' => [
                    'Content-Type' => 'application/json',
                    // Obtener el token de seguridad de las variables de sesión
                    'Authorization' => 'Bearer ' . $_SESSION['token']
                ]
            ]);
        }
    }
} catch (\Throwable $th) {
}


// Divide los artículos y los imprime
$lineas_articulos = explode("\n", $articulos);

// Calcula la altura de la página en función del número de líneas de artículos
$alto_pagina = count($lineas_articulos) * 10 + 130; // Ajustado para incluir los datos del cliente, pago y vuelto

// Crea una instancia de la clase FPDF con las dimensiones del papel (58mm de ancho)
$pdf = new FPDF('P', 'mm', array(75, $alto_pagina)); // 58mm de ancho, alto variable según la cantidad de artículos

// Establecer los márgenes
$pdf->SetMargins(5, 5, 5);

// Añade una página al documento
$pdf->AddPage();

// Establece la fuente para el texto (Arial, tamaño 8)
$pdf->SetFont('Arial', '', 10);
// Agrega los datos de la tienda
$pdf->Cell(72, 5, mb_convert_encoding($nombre_tienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(72, 5, mb_convert_encoding($direccion_tienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(72, 5, mb_convert_encoding('Teléfono: ' . $telefono_tienda, 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->Cell(72, 5, '--------------------------------', 0, 1, 'C');

// Agrega la fecha y hora al inicio del ticket
$pdf->Cell(72, 5, 'Fecha y hora: ' . date('d-m-Y', strtotime($fecha)) . ' ' . $hora, 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('Tipo de Comprobante: ' . $tipo_comprobante, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('N°: ' . $nro_comprobante, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('CAE: ' . $cae, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('Vencimiento: ' . $fecha_vencimiento, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, '--------------------------------', 0, 1, 'C');

// Agrega los datos del cliente
$pdf->Cell(72, 5, mb_convert_encoding($cliente_nombre, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding($cliente_cuit, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding($cliente_domicilio, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, '--------------------------------', 0, 1, 'C');
$pdf->Cell(72, 5, 'Productos:', 0, 1, 'C');
// Divide los artículos y los imprime
foreach ($lineas_articulos as $linea) {
    $pdf->MultiCell(72, 5, mb_convert_encoding($linea, 'ISO-8859-1', 'UTF-8'), 0, 'L');
}
// descuentos
$pdf->Cell(72, 5, mb_convert_encoding('Promociones:', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
$pdf->MultiCell(72, 5, mb_convert_encoding($promociones_string, 'ISO-8859-1', 'UTF-8'), 0, 'L');
$pdf->Cell(72, 5, '--------------------------------', 0, 1, 'C');
$pdf->Cell(72, 5, mb_convert_encoding('Total: $' . $total, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('Pagado: $' . $monto_pagado, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('Vuelto: $' . $monto_vuelto, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, '--------------------------------', 0, 1, 'C');
$pdf->Cell(72, 5, mb_convert_encoding('Gracias por su compra!', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');


$directorio = 'comprobantes/';

// Obtener la hora actual
$horaActual = time();

// Calcular la hora hace una hora
$horaHaceUnaHora = $horaActual - 3600; // 3600 segundos = 1 hora

// Obtener una lista de archivos en el directorio
$archivos = scandir($directorio);

// Iterar sobre los archivos
foreach ($archivos as $archivo) {
    // Ignorar los archivos especiales . y ..
    if ($archivo != '.' && $archivo != '..') {
        // Obtener la fecha de modificación del archivo
        $fechaModificacion = filemtime("$directorio/$archivo");

        // Verificar si el archivo fue modificado hace una hora o más
        if ($fechaModificacion <= $horaHaceUnaHora) {
            // Eliminar el archivo
            unlink("$directorio/$archivo");
        }
    }
}


// Salida del documento y envío a la impresora
$pdfPath = "comprobantes/comprobante_{$id}.pdf";
$pdf->Output($pdfPath, 'F');

// Devolver la URL del PDF en la respuesta AJAX
echo json_encode(['pdfUrl' => $ruta_https . $pdfPath]);
exit;
