<?php
// Incluye el autoload generado por Composer
require_once 'vendor/autoload.php';

include("includes/session_parameters.php");



// Si se reciben datos por POST, se asignan a variables
$nombre_tienda = isset($_POST['nombre_tienda']) ? $_POST['nombre_tienda'] : '';
$direccion_tienda = isset($_POST['direccion_tienda']) ? $_POST['direccion_tienda'] : '';
$telefono_tienda = isset($_POST['telefono_tienda']) ? $_POST['telefono_tienda'] : '';
$comprobante_id = isset($_POST['comprobante_id']) ? $_POST['comprobante_id'] : '';


$cliente_id = (isset($_POST['cliente_id']) && $_POST['cliente_id'] != '') ? $_POST['cliente_id'] : 1;
$productos = isset($_POST['productos']) ? $_POST['productos'] : array();
$fecha = date('Ymd');

// URL de la API
$url = $ruta . 'api/comprobantes/' . $comprobante_id;

// Importar la clase GuzzleHTTP\Client

use GuzzleHttp\Client;
// Crear una instancia del cliente Guzzle
$client = new Client();



// Enviar la solicitud POST
$response = $client->request('GET', $url, [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ]
]);

$bodyContents = $response->getBody()->getContents();
$comprobante = json_decode($bodyContents, true);



$total = $comprobante[0]['total'];
$tipo_comprobante = $comprobante[0]['tipo_comprobante']['nombre'] ?? 0;
$baja=( $comprobante[0]['fecha_baja']!=null)?'(ANULADO)':'';
$monto_pagado =  $comprobante[0]['total_pagado'];
$monto_vuelto =   round($monto_pagado - $total, 1);
$fecha = date('d-m-Y', strtotime($comprobante[0]['fecha']));
$hora = $comprobante[0]['hora'];
$cliente_nombre = 'Cliente: ' . $comprobante[0]['cliente']['nro_cliente'] . ' / ' . $comprobante[0]['cliente']['nombre'];
$cliente_cuit = 'CUIT: ' . $comprobante[0]['cliente']['cuit'];
$cliente_domicilio = 'Domicilio: ' . $comprobante[0]['cliente']['direccion_comercial'];
$descuentos = $comprobante[0]['observaciones_1'] ?? '';
$prefijo = $comprobante[0]['prefijo_factura'] ?? '';
$numero_factura = $comprobante[0]['numero_factura'] ?? '';
$factura_letra = $comprobante[0]['letra'] ?? '';
$punto_venta = $_SESSION['punto_venta'];

if (($tipo_comprobante=='PEDIDO')) {
        //obtener año
        $year = date('Y');
        $nro_comprobante = "OC-" . $year . "-PV" . str_pad($punto_venta, 4, '0', STR_PAD_LEFT) . "-" . str_pad($comprobante_id, 8, '0', STR_PAD_LEFT);
    } else {
        $nro_comprobante = "FC-" . $factura_letra . "-" . str_pad($punto_venta, 4, '0', STR_PAD_LEFT) . "-" . str_pad($numero_factura, 8, '0', STR_PAD_LEFT);
    }


$url = $ruta . 'api/renglones_comprobantes/' . $comprobante_id . '/';

// Enviar la solicitud POST
$response = $client->request('GET', $url, [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ]
]);


$bodyContents = $response->getBody()->getContents();
$renglones_comprobante = json_decode($bodyContents, true);


$lineas_articulos = [];

foreach ($renglones_comprobante as $item) {
    $descripcion = $item['descripcion'];
    $cantidad = $item['cantidad'];
    $precioUnitario = $item['precio_unitario'];

    // Concatenar los valores en el formato deseado
    $lineas_articulos[] = $descripcion . " : $" . round($cantidad * $precioUnitario, 2);
}



// Divide los artículos y los imprime
//$lineas_articulos = explode("\n", $articulos);

// Calcula la altura de la página en función del número de líneas de artículos
$alto_pagina = count($lineas_articulos ?? []) * 10 + 130; // Ajustado para incluir los datos del cliente, pago y vuelto

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
$pdf->Cell(72, 5, ('Fecha y hora: ' . $fecha . ' ' . $hora), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('Tipo de Comprobante: ' . $tipo_comprobante.' '.$baja, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
$pdf->Cell(72, 5, mb_convert_encoding('N° ' . $nro_comprobante, 'ISO-8859-1', 'UTF-8'), 0, 1, 'L');
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
if ($descuentos != '') {

    $pdf->Cell(72, 5, mb_convert_encoding('Promociones:', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');
    $pdf->MultiCell(72, 5, mb_convert_encoding($descuentos, 'ISO-8859-1', 'UTF-8'), 0, 'L');
}
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
$pdfPath = "comprobantes/comprobante_{$comprobante_id}.pdf";
$pdf->Output($pdfPath, 'F');

// Devolver la URL del PDF en la respuesta AJAX
echo json_encode(['pdfUrl' => $ruta_https . $pdfPath]);
exit;
