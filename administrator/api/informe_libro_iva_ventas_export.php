<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
        day(comprobantes.fecha) as dia,
        comprobantes.tipo_comprobante_id,
        comprobantes.numero_factura,
        comprobantes.punto_venta,
        comprobantes.numero_de_documento,
        clientes.nombre,
        comprobantes.no_gravado_iva_21,
        comprobantes.no_gravado_iva_105,
        comprobantes.no_gravado_iva_0,
        comprobantes.importe_impuesto_interno,
        comprobantes.importe_iibb,
        comprobantes.importe_iva_21,
        comprobantes.importe_iva_105,
        comprobantes.importe_iva_0,
        comprobantes.fecha,
        comprobantes.total,
        comprobantes.tipo_factura,
        tipo_comprobante.nombre as tipo_comprobante_nombre
        FROM
        comprobantes
        LEFT JOIN clientes ON comprobantes.cliente_id = clientes.id
        LEFT JOIN tipo_comprobante ON comprobantes.tipo_comprobante_id = tipo_comprobante.id
        WHERE (comprobantes.tipo_comprobante_id = 1 or  comprobantes.tipo_comprobante_id = 4)";

        $query_param = array();
        $order_by = 'comprobantes.id';
        $sort_order = ' ASC ';
        //recibir todos los posibles parametros por GET
        if (isset($_GET['param'])) {
            $id = sanitizeInput($_GET['param']);
            array_push($query_param, "comprobantes.id=$id");
        }
        if (isset($_GET['punto_venta'])) {
            $punto_venta = sanitizeInput($_GET['punto_venta']);
            array_push($query_param, "comprobantes.punto_venta=$punto_venta");
        }
        if (isset($_GET['fecha_inicio'])) {
            $fecha_desde = sanitizeInput($_GET['fecha_inicio']);
            array_push($query_param, "comprobantes.fecha >= '$fecha_desde'");
        }

        if (isset($_GET['fecha_fin'])) {
            $fecha_hasta = sanitizeInput($_GET['fecha_fin']);
            array_push($query_param, "comprobantes.fecha <= '$fecha_hasta'");
        }
        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
        }


        if (count($query_param) > 0) {
            $query_product = $query_product . " and (" . implode(" AND ", $query_param) . ") AND comprobantes.empresa_id = $empresa_id";
        } else {
            $query_product = $query_product . " and comprobantes.empresa_id = $empresa_id";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM comprobantes WHERE (comprobantes.tipo_comprobante_id = 1 OR comprobantes.tipo_comprobante_id = 4)AND comprobantes.empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;

        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;
        $query_product = $query_product . " ORDER BY   $order_by  $sort_order";


        $result = $con->query($query_product);
        $comprobantes = array();
        $total_ng21 = 0;
        $total_ng105 = 0;
        $total_ng0 = 0;
        $total_int = 0;
        $total_iibb = 0;
        $total_iva21 = 0;
        $total_iva105 = 0;
        $total_iva0 = 0;
        $total_ventas = 0;

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $multiplicardor_montos = 1;
            //si el tipo de comprobante es 4= NC multiplico los montos por -1
            if ($row['tipo_comprobante_id'] == 4) {
                $multiplicardor_montos = -1;
            }
            $letra = '';
            if ($row['tipo_factura'] == 1) {
                $letra = 'A';
            } else if ($row['tipo_factura'] == 6) {
                $letra = 'B';
            } else if ($row['tipo_factura'] == 11) {
                $letra = 'C';
            } else if ($row['tipo_factura'] == 3) {
                $letra = 'A';
            } else if ($row['tipo_factura'] == 8) {
                $letra = 'B';
            } else if ($row['tipo_factura'] == 13) {
                $letra = 'C';
            }



            $comprobante = array(
                "dia" => $row['dia'],
                "numero_factura" => $row['tipo_comprobante_nombre'] . ' ' . $letra . ' ' . sprintf("%04d %08d", $row['punto_venta'], $row['numero_factura']),
                "cuit" => $row['numero_de_documento'],
                "cliente" => $row['nombre'],
                "ng21" => round($multiplicardor_montos * $row['no_gravado_iva_21'], 2),
                "ng105" => round($multiplicardor_montos * $row['no_gravado_iva_105'], 2),
                "ng0" => round($multiplicardor_montos * $row['no_gravado_iva_0'], 2),
                "int" => round($multiplicardor_montos * $row['importe_impuesto_interno'], 2),
                "iibb" => round($multiplicardor_montos * $row['importe_iibb'], 2),
                "iva21" => round($multiplicardor_montos * $row['importe_iva_21'], 2),
                "iva105" => round($multiplicardor_montos * $row['importe_iva_105'], 2),
                "iva0" => round($multiplicardor_montos * $row['importe_iva_0'], 2),
                "total" => round($multiplicardor_montos * $row['total'], 2)
            );
            $total_ng21 += $multiplicardor_montos * $row['no_gravado_iva_21'];
            $total_ng105 += $multiplicardor_montos * $row['no_gravado_iva_105'];
            $total_ng0 += $multiplicardor_montos * $row['no_gravado_iva_0'];
            $total_int += $multiplicardor_montos * $row['importe_impuesto_interno'];
            $total_iibb += $multiplicardor_montos * $row['importe_iibb'];
            $total_iva21 += $multiplicardor_montos * $row['importe_iva_21'];
            $total_iva105 += $multiplicardor_montos * $row['importe_iva_105'];
            $total_iva0 += $multiplicardor_montos * $row['importe_iva_0'];
            $total_ventas += $multiplicardor_montos * $row['total'];
            array_push($comprobantes, $comprobante);
        }
        $resumen = array(
            "total_ng21" => round($total_ng21, 2),
            "total_ng105" => round($total_ng105, 2),
            "total_ng0" => round($total_ng0, 2),
            "total_int" => round($total_int, 2),
            "total_iibb" => round($total_iibb, 2),
            "total_iva21" => round($total_iva21, 2),
            "total_iva105" => round($total_iva105, 2),
            "total_iva0" => round($total_iva0, 2),
            "total" => $total_ventas
        );

        $type = $_GET['type'];
        if ($type == 'excel') {
            
            $iva_ventas = [];
            foreach ($comprobantes as $item) {


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
                $iva_ventas[] = $formattedItem;
            }
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Dia');
            $sheet->setCellValue('B1', 'Numero Factura');
            $sheet->setCellValue('C1', 'Cuit');
            $sheet->setCellValue('D1', 'Cliente');
            $sheet->setCellValue('E1', 'NG.21');
            $sheet->setCellValue('F1', 'NG.10.5');
            $sheet->setCellValue('G1', 'NG.0');
            $sheet->setCellValue('H1', 'Int.');
            $sheet->setCellValue('I1', 'IIBB');
            $sheet->setCellValue('J1', 'IVA.21');
            $sheet->setCellValue('K1', 'IVA.10.5');
            $sheet->setCellValue('L1', 'IVA.0');
            $sheet->setCellValue('M1', 'Total');
            $i = 2;
            foreach ($iva_ventas as $iva_venta) {
                $sheet->setCellValue('A' . $i, $iva_venta['dia']);
                $sheet->setCellValue('B' . $i, $iva_venta['numero_factura']);
                $sheet->setCellValue('C' . $i, $iva_venta['cuit']);
                $sheet->setCellValue('D' . $i, $iva_venta['cliente']);
                $sheet->setCellValue('E' . $i, $iva_venta['ng21']);
                $sheet->setCellValue('F' . $i, $iva_venta['ng105']);
                $sheet->setCellValue('G' . $i, $iva_venta['ng0']);
                $sheet->setCellValue('H' . $i, $iva_venta['int']);
                $sheet->setCellValue('I' . $i, $iva_venta['iibb']);
                $sheet->setCellValue('J' . $i, $iva_venta['iva21']);
                $sheet->setCellValue('K' . $i, $iva_venta['iva105']);
                $sheet->setCellValue('L' . $i, $iva_venta['iva0']);
                $sheet->setCellValue('M' . $i, $iva_venta['total']);
                $i++;
            }
            //totales 
            $sheet->setCellValue('A' . $i, 'Totales');
            $sheet->setCellValue('E' . $i, $resumen['total_ng21']);
            $sheet->setCellValue('F' . $i, $resumen['total_ng105']);
            $sheet->setCellValue('G' . $i, $resumen['total_ng0']);
            $sheet->setCellValue('H' . $i, $resumen['total_int']);
            $sheet->setCellValue('I' . $i, $resumen['total_iibb']);
            $sheet->setCellValue('J' . $i, $resumen['total_iva21']);
            $sheet->setCellValue('K' . $i, $resumen['total_iva105']);
            $sheet->setCellValue('L' . $i, $resumen['total_iva0']);
            $sheet->setCellValue('M' . $i, $resumen['total']);

            //estilos
            $styleArray = [
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'outline' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ];
            $sheet->getStyle('A1:M1')->applyFromArray($styleArray);
            $sheet->getStyle('A' . $i . ':M' . $i)->applyFromArray($styleArray);
            $sheet->getStyle('A1:M' . $i)->getAlignment()->setWrapText(true);
            $sheet->getStyle('A1:M' . $i)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle('A1:M' . $i)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            //fondos
            $sheet->getStyle('A1:M1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');
            //autodimensionar columnas
            foreach (range('A', 'M') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }
            //bordes
            $sheet->getStyle('A1:M' . $i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

            //guardar archivo


            $writer = new Xlsx($spreadsheet);
            $filename = 'iva_ventas' . $empresa_id. '.xlsx';
            $filepath = './../comprobantes/' . $filename;
            $writer->save($filepath);
            $response = array("status" => 200, "status_message" => "Archivo generado", "url" => $filepath);
        } else {
         
            $filename = 'iva_ventas' .  $empresa_id . '.pdf';
            $filepath = './../comprobantes/' . $filename;
            $pdf = new FPDF();

           //crear paginas en horizontal
            $pdf->AddPage('L');
            //agragar titulo
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Informe de IVA Ventas - Periodo: ' . date('d-m-Y', strtotime($fecha_desde)) . ' - ' . date('d-m-Y', strtotime($fecha_hasta)), 0, 1, 'C');
            //agregar tabla
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(10, 10, 'Dia', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Numero Factura', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Cuit', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Cliente', 1, 0, 'C');
            $pdf->Cell(20, 10, 'NG.21', 1, 0, 'C');
            $pdf->Cell(20, 10, 'NG.10.5', 1, 0, 'C');
            $pdf->Cell(20, 10, 'NG.0', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Int.', 1, 0, 'C');
            $pdf->Cell(20, 10, 'IIBB', 1, 0, 'C');
            $pdf->Cell(20, 10, 'IVA.21', 1, 0, 'C');
            $pdf->Cell(20, 10, 'IVA.10.5', 1, 0, 'C');
            $pdf->Cell(20, 10, 'IVA.0', 1, 0, 'C');
            $pdf->Cell(20, 10, 'Total', 1, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            foreach ($comprobantes as $comprobante) {
                $pdf->Cell(10, 10, $comprobante['dia'], 1, 0, 'C');
                $pdf->Cell(30, 10, $comprobante['numero_factura'], 1, 0, 'C');
                $pdf->Cell(30, 10, $comprobante['cuit'], 1, 0, 'C');
                $pdf->Cell(50, 10, $comprobante['cliente'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['ng21'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['ng105'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['ng0'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['int'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['iibb'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['iva21'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['iva105'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['iva0'], 1, 0, 'C');
                $pdf->Cell(20, 10, $comprobante['total'], 1, 1, 'C');
            }
            $pdf->Cell(140, 10, 'Totales', 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_ng21'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_ng105'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_ng0'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_int'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_iibb'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_iva21'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_iva105'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total_iva0'], 1, 0, 'C');
            $pdf->Cell(20, 10, $resumen['total'], 1, 1, 'C');

            $pdf->Output('F', $filepath);
            $response = array("status" => 200, "status_message" => "Archivo generado", "url" => $filepath);

        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
