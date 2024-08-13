<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_distribuidor');
session_start();
}


// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'administrator/api/informe_libro_iva_ventas/';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ],
    "query" => [

        'param' => isset($_GET['param']) ? $_GET['param'] : null,
        'fecha_inicio' => isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null,
        'fecha_fin' => isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null,

    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client();

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);
    // Obtener los headers de respuesta

    $xTotalCount = $response->getHeaderLine('X-Total-Count') ?? 0;
    $XPerPage = $response->getHeaderLine('X-Per-Page') ?? 0;



    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();

    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true)['data'];
    $resumen = json_decode($body, true)['resumen'];

    // Transformar los datos según el nuevo formato requerido
    $formattedData = [];
    $total_ng21 = 0;
    $total_ng105 = 0;
    $total_ng0 = 0;
    $total_int = 0;
    $total_iibb = 0;
    $total_iva21 = 0;
    $total_iva105 = 0;
    $total_iva0 = 0;
    $total_total = 0;
    foreach ($data as $item) {


        $formattedItem = [
            "dia" => $item['dia'],
            "numero_factura" => $item['numero_factura'],
            "cuit" => $item['cuit'],
            "cliente" => $item['cliente'],
            "ng21" => round($item['ng21'], 2),
            "ng105" => round($item['ng105'], 2),
            "ng0" => round($item['ng0'], 2),
            "int" => round($item['int'], 2),
            "iibb" => round($item['iibb'], 2),
            "iva21" => round($item['iva21'], 2),
            "iva105" => round($item['iva105'], 2),
            "iva0" => round($item['iva0'], 2),
            "total" => round($item['total'], 2),
        ];
        $total_ng21 += $item['ng21'];
        $total_ng105 += $item['ng105'];
        $total_ng0 += $item['ng0'];
        $total_int += $item['int'];
        $total_iibb += $item['iibb'];
        $total_iva21 += $item['iva21'];
        $total_iva105 += $item['iva105'];
        $total_iva0 += $item['iva0'];
        $total_total += $item['total'];
        $formattedData[] = $formattedItem;
    }
    // Crear un nuevo documento PDF para el libro iva ventas
    $pdf = new FPDF();

    // Añade A4 horizontal una página al documento
    $pdf->AddPage('L', 'A4');

    // Establecer la fuente y el tamaño del texto
    $pdf->SetFont('Arial', 'B', 10);

    // Título del libro iva ventas
    $pdf->Cell(0, 10, 'Libro IVA Ventas', 0, 1, 'L');
    $pdf->Ln(5);

    // Establecer la fuente y el tamaño del texto
    $pdf->SetFont('Arial', 'B',8);

    // Encabezados de las columnas
    $pdf->Cell(5, 10, 'Dia', 0, 0, 'L');
    $pdf->Cell(25, 10, 'Nro Factura', 0, 0, 'L');
    $pdf->Cell(25, 10, 'CUIT', 0, 0, 'L');
    $pdf->Cell(50, 10, 'Cliente', 0, 0, 'L');
    $pdf->Cell(20, 10, 'NG.21', 0, 0, 'L');
    $pdf->Cell(20, 10, 'NG.10.5', 0, 0, 'L');
    $pdf->Cell(20, 10, 'NG.0', 0, 0, 'L');
    $pdf->Cell(20, 10, 'Int.', 0, 0, 'L');
    $pdf->Cell(20, 10, 'IIBB', 0, 0, 'L');
    $pdf->Cell(20, 10, 'IVA 21%', 0, 0, 'L');
    $pdf->Cell(20, 10, 'IVA 10.5%', 0, 0, 'L');
    $pdf->Cell(20, 10, 'IVA 0%', 0, 0, 'L');
    $pdf->Cell(20, 10, 'Total', 0, 1, 'L');

    // Establecer la fuente y el tamaño del texto
    $pdf->SetFont('Arial', '', 8);

    // Recorrer los datos y añadirlos al PDF
    foreach ($formattedData as $item) {
        $pdf->Cell(5, 10, $item['dia'], 0, 0, 'L');
        $pdf->Cell(25, 10, $item['numero_factura'], 0, 0, 'L');
        $pdf->Cell(25, 10, $item['cuit'], 0, 0, 'R');
        $pdf->Cell(50, 10, $item['cliente'], 0, 0, 'L');
        $pdf->Cell(20, 10, $item['ng21'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['ng105'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['ng0'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['int'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['iibb'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['iva21'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['iva105'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['iva0'], 0, 0, 'R');
        $pdf->Cell(20, 10, $item['total'], 0, 1, 'R');
    }
    //totales
    $pdf->Cell(5, 10, 'Totales', 0, 0, 'L');
    $pdf->Cell(25, 10, '', 0, 0, 'R');
    $pdf->Cell(20, 10, '', 0, 0, 'R');
    $pdf->Cell(50, 10, '', 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_ng21, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_ng105, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_ng0, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_int, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_iibb, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_iva21, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_iva105, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_iva0, 2), 0, 0, 'R');
    $pdf->Cell(20, 10, round($total_total, 2), 0, 1, 'R');

    // Guardar el PDF en la carpeta comprobantes
    $pdfPath = "comprobantes/comprobante.pdf";
    $pdf->Output("../../comprobantes/comprobante.pdf", 'F');

    // Devolver la URL del PDF en la respuesta AJAX
    echo json_encode(['pdfUrl' => $ruta_https . $pdfPath]);
} catch (Exception $e) {
    $response = array(
        "status" => 500,
        "status_message" => "Error en el servidor",
        "error" => $e->getMessage()
    );

    echo json_encode($response);
}
