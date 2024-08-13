<?php
// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {

    session_start();
}


// Importar la clase GuzzleHttp\Client
require '../../vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'api/informe_libro_iva_ventas/';

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

// Añadir una página A4 horizontal
$pdf->AddPage('L', 'Letter');

// Establecer la fuente y el tamaño del texto
$pdf->SetFont('Arial', 'B', 10);

// Título del libro IVA ventas
$pdf->Cell(0, 10, 'Libro IVA Ventas', 0, 1, 'L');
$pdf->Ln(5);

// Establecer la fuente y el tamaño del texto
$pdf->SetFont('Arial', 'B', 8);

// Calcular el ancho disponible para las celdas
$anchoTotal = $pdf->GetPageWidth() - 20; // 20 para márgenes
$anchosColumnas = [
    'dia' => 5,
    'numero_factura' => 25,
    'cuit' => 25,
    'cliente' => 50,
    'ng21' => 20,
    'ng105' => 20,
    'ng0' => 20,
    'int' => 20,
    'iibb' => 20,
    'iva21' => 20,
    'iva105' => 20,
    'iva0' => 20,
    'total' => 20
];

$sumaAnchos = array_sum($anchosColumnas);
$factorAjuste = $anchoTotal / $sumaAnchos;

// Ajustar los anchos de las columnas
foreach ($anchosColumnas as $key => $ancho) {
    $anchosColumnas[$key] = $ancho * $factorAjuste;
}

// Encabezados de las columnas
$pdf->Cell($anchosColumnas['dia'], 10, 'Dia', 1, 0, 'L');
$pdf->Cell($anchosColumnas['numero_factura'], 10, 'Nro Factura', 1, 0, 'L');
$pdf->Cell($anchosColumnas['cuit'], 10, 'CUIT', 1, 0, 'L');
$pdf->Cell($anchosColumnas['cliente'], 10, 'Cliente', 1, 0, 'L');
$pdf->Cell($anchosColumnas['ng21'], 10, 'NG.21', 1, 0, 'L');
$pdf->Cell($anchosColumnas['ng105'], 10, 'NG.10.5', 1, 0, 'L');
$pdf->Cell($anchosColumnas['ng0'], 10, 'NG.0', 1, 0, 'L');
$pdf->Cell($anchosColumnas['int'], 10, 'Int.', 1, 0, 'L');
$pdf->Cell($anchosColumnas['iibb'], 10, 'IIBB', 1, 0, 'L');
$pdf->Cell($anchosColumnas['iva21'], 10, 'IVA 21%', 1, 0, 'L');
$pdf->Cell($anchosColumnas['iva105'], 10, 'IVA 10.5%', 1, 0, 'L');
$pdf->Cell($anchosColumnas['iva0'], 10, 'IVA 0%', 1, 0, 'L');
$pdf->Cell($anchosColumnas['total'], 10, 'Total', 1, 1, 'L');

// Función para ajustar la fuente
function ajustarFuente($pdf, $texto, $anchoColumna)
{
    $maxSize = 8;
    $pdf->SetFont('Arial', '', $maxSize);
    while ($pdf->GetStringWidth($texto) > $anchoColumna) {
        $maxSize--;
        $pdf->SetFont('Arial', '', $maxSize);
        if ($maxSize <= 5) { // Tamaño mínimo de fuente
            break;
        }
    }
}

// Establecer la fuente y el tamaño del texto
$pdf->SetFont('Arial', '', 8);

// Recorrer los datos y añadirlos al PDF
foreach ($formattedData as $item) {
    ajustarFuente($pdf, $item['dia'], $anchosColumnas['dia']);
    $pdf->Cell($anchosColumnas['dia'], 10, $item['dia'], 1, 0, 'L');

    ajustarFuente($pdf, $item['numero_factura'], $anchosColumnas['numero_factura']);
    $pdf->Cell($anchosColumnas['numero_factura'], 10, $item['numero_factura'], 1, 0, 'L');

    ajustarFuente($pdf, $item['cuit'], $anchosColumnas['cuit']);
    $pdf->Cell($anchosColumnas['cuit'], 10, $item['cuit'], 1, 0, 'R');

    ajustarFuente($pdf, $item['cliente'], $anchosColumnas['cliente']);
    $pdf->Cell($anchosColumnas['cliente'], 10, $item['cliente'], 1, 0, 'L');

    ajustarFuente($pdf, $item['ng21'], $anchosColumnas['ng21']);
    $pdf->Cell($anchosColumnas['ng21'], 10, $item['ng21'], 1, 0, 'R');

    ajustarFuente($pdf, $item['ng105'], $anchosColumnas['ng105']);
    $pdf->Cell($anchosColumnas['ng105'], 10, $item['ng105'], 1, 0, 'R');

    ajustarFuente($pdf, $item['ng0'], $anchosColumnas['ng0']);
    $pdf->Cell($anchosColumnas['ng0'], 10, $item['ng0'], 1, 0, 'R');

    ajustarFuente($pdf, $item['int'], $anchosColumnas['int']);
    $pdf->Cell($anchosColumnas['int'], 10, $item['int'], 1, 0, 'R');

    ajustarFuente($pdf, $item['iibb'], $anchosColumnas['iibb']);
    $pdf->Cell($anchosColumnas['iibb'], 10, $item['iibb'], 1, 0, 'R');

    ajustarFuente($pdf, $item['iva21'], $anchosColumnas['iva21']);
    $pdf->Cell($anchosColumnas['iva21'], 10, $item['iva21'], 1, 0, 'R');

    ajustarFuente($pdf, $item['iva105'], $anchosColumnas['iva105']);
    $pdf->Cell($anchosColumnas['iva105'], 10, $item['iva105'], 1, 0, 'R');

    ajustarFuente($pdf, $item['iva0'], $anchosColumnas['iva0']);
    $pdf->Cell($anchosColumnas['iva0'], 10, $item['iva0'], 1, 0, 'R');

    ajustarFuente($pdf, $item['total'], $anchosColumnas['total']);
    $pdf->Cell($anchosColumnas['total'], 10, $item['total'], 1, 1, 'R');
}

// Totales
ajustarFuente($pdf, 'Totales', $anchosColumnas['dia']);
$pdf->Cell($anchosColumnas['dia'], 10, 'Totales', 1, 0, 'L');
ajustarFuente($pdf, '', $anchosColumnas['numero_factura']);
$pdf->Cell($anchosColumnas['numero_factura'], 10, '', 1, 0, 'R');
ajustarFuente($pdf, '', $anchosColumnas['cuit']);
$pdf->Cell($anchosColumnas['cuit'], 10, '', 1, 0, 'R');
ajustarFuente($pdf, '', $anchosColumnas['cliente']);
$pdf->Cell($anchosColumnas['cliente'], 10, '', 1, 0, 'R');
ajustarFuente($pdf, round($total_ng21, 2), $anchosColumnas['ng21']);
$pdf->Cell($anchosColumnas['ng21'], 10, round($total_ng21, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_ng105, 2), $anchosColumnas['ng105']);
$pdf->Cell($anchosColumnas['ng105'], 10, round($total_ng105, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_ng0, 2), $anchosColumnas['ng0']);
$pdf->Cell($anchosColumnas['ng0'], 10, round($total_ng0, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_int, 2), $anchosColumnas['int']);
$pdf->Cell($anchosColumnas['int'], 10, round($total_int, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_iibb, 2), $anchosColumnas['iibb']);
$pdf->Cell($anchosColumnas['iibb'], 10, round($total_iibb, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_iva21, 2), $anchosColumnas['iva21']);
$pdf->Cell($anchosColumnas['iva21'], 10, round($total_iva21, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_iva105, 2), $anchosColumnas['iva105']);
$pdf->Cell($anchosColumnas['iva105'], 10, round($total_iva105, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_iva0, 2), $anchosColumnas['iva0']);
$pdf->Cell($anchosColumnas['iva0'], 10, round($total_iva0, 2), 1, 0, 'R');
ajustarFuente($pdf, round($total_total, 2), $anchosColumnas['total']);
$pdf->Cell($anchosColumnas['total'], 10, round($total_total, 2), 1, 1, 'R');


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
