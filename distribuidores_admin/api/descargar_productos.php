<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

try {


    $headers = apache_request_headers();

    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $data = json_decode(file_get_contents('php://input'), true);
        $empresa_id = $data['empresa_id'];
        $query = "SELECT  
        codigo,
        descripcion,
        precio1,
        precio2,
        precio3,
        (tasa_iva.tasa) as tasa_iva,
        productos.empresa_id FROM productos 
        LEFT JOIN tasa_iva ON productos.tasa_iva_id = tasa_iva.id where productos.empresa_id = $empresa_id";
       $result = $con->query($query);
       //generar un xls con el encabezado CODIGO, DESCRIPCION, PRECIO1, PRECIO2, PRECIO3, TASA_IVA
         $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'CODIGO');
            $sheet->setCellValue('B1', 'DESCRIPCION');
            $sheet->setCellValue('C1', 'PRECIO1');
            $sheet->setCellValue('D1', 'PRECIO2');
            $sheet->setCellValue('E1', 'PRECIO3');
            $sheet->setCellValue('F1', 'TASA_IVA');
            $i = 2;
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $sheet->setCellValue('A' . $i, $row['codigo']);
                $sheet->setCellValue('B' . $i, $row['descripcion']);
                $sheet->setCellValue('C' . $i, $row['precio1']);
                $sheet->setCellValue('D' . $i, $row['precio2']);
                $sheet->setCellValue('E' . $i, $row['precio3']);
                $sheet->setCellValue('F' . $i, round($row['tasa_iva']*100,1));
                $i++;
            }
            //agregar bordes
            $styleArray = [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A1:F' . $i)->applyFromArray($styleArray);
            $sheet->setTitle('Productos');
            //ajustar el ancho de las columnas
            foreach (range('A', 'F') as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
            }
            //primer columna es texto
            $spreadsheet->getActiveSheet()->getStyle('A')->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);

            $writer = new Xls($spreadsheet);
            $filename = 'produstos_descargar' . $empresa_id. '.xls';
            $filepath = './../comprobantes/' . $filename;
            $writer->save($filepath);
            $response = array("status" => 200, "status_message" => "Archivo generado", "url" => $ruta.'distribuidores_admin/comprobantes/'.$filename);

    } else {
        throw new Exception("Método no permitido", 405);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    $error_msg = $errores_mysql[$e->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}{$e->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
