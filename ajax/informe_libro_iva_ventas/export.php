<?php
//show all errors php

// Iniciar la sesión si no está iniciada
include("../../includes/config.php");
include("../../includes/session_parameters.php");

// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {

    session_start();
}


// Importar la clase GuzzleHttp\Client
require '../../distribuidores_admin/vendor/autoload.php';


use GuzzleHttp\Client;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


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
   // Crear un nuevo documento EXCEL para el libro iva ventas
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->setCellValue('A1', 'DIA');
    $sheet->setCellValue('B1', 'NUMERO FACTURA');
    $sheet->setCellValue('C1', 'CUIT');
    $sheet->setCellValue('D1', 'CLIENTE');
    $sheet->setCellValue('E1', 'NG21');
    $sheet->setCellValue('F1', 'NG10.5');
    $sheet->setCellValue('G1', 'NG0');
    $sheet->setCellValue('H1', 'INT');
    $sheet->setCellValue('I1', 'IIBB');
    $sheet->setCellValue('J1', 'IVA21');
    $sheet->setCellValue('K1', 'IVA10.5');
    $sheet->setCellValue('L1', 'IVA0');
    $sheet->setCellValue('M1', 'TOTAL');
    $i = 2;

    foreach ($formattedData as $item) {
        $sheet->setCellValue('A' . $i, $item['dia']);
        $sheet->setCellValue('B' . $i, $item['numero_factura']);
        $sheet->setCellValue('C' . $i, $item['cuit']);
        $sheet->setCellValue('D' . $i, $item['cliente']);
        $sheet->setCellValue('E' . $i, $item['ng21']);
        $sheet->setCellValue('F' . $i, $item['ng105']);
        $sheet->setCellValue('G' . $i, $item['ng0']);
        $sheet->setCellValue('H' . $i, $item['int']);
        $sheet->setCellValue('I' . $i, $item['iibb']);
        $sheet->setCellValue('J' . $i, $item['iva21']);
        $sheet->setCellValue('K' . $i, $item['iva105']);
        $sheet->setCellValue('L' . $i, $item['iva0']);
        $sheet->setCellValue('M' . $i, $item['total']);
        $i++;
    }

    $sheet->setCellValue('A' . $i, 'TOTAL');
    $sheet->setCellValue('E' . $i, $total_ng21);
    $sheet->setCellValue('F' . $i, $total_ng105);
    $sheet->setCellValue('G' . $i, $total_ng0);
    $sheet->setCellValue('H' . $i, $total_int);
    $sheet->setCellValue('I' . $i, $total_iibb);
    $sheet->setCellValue('J' . $i, $total_iva21);
    $sheet->setCellValue('K' . $i, $total_iva105);
    $sheet->setCellValue('L' . $i, $total_iva0);
    $sheet->setCellValue('M' . $i, $total_total);

// dar formato a las celdas
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getFill()->getStartColor()->setARGB('FFA0A0A0');
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setWrapText(true);
    $spreadsheet->getActiveSheet()->getStyle('A1:M1')->getAlignment()->setShrinkToFit(true);
 // bordes
    $spreadsheet->getActiveSheet()->getStyle('A1:M' . $i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle('A1:M' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A1:M' . $i)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
    $spreadsheet->getActiveSheet()->getStyle('A1:M' . $i)->getAlignment()->setWrapText(true);

    // usar auto size para ajustar el tamaño de las columnas
    foreach (range('A', 'M') as $columnID) {
        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }

    // Crear un objeto Writer para exportar el libro de Excel
    $writer = new Xlsx($spreadsheet);
    
    // Guardar el libro de Excel en la carpeta comprobantes


    // Guardar el PDF en la carpeta comprobantes
    $pdfPath = "comprobantes/comprobante.xlsx";
    $writer->save('../../comprobantes/comprobante.xlsx');

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
