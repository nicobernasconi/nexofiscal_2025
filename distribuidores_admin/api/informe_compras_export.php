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
                            compras.fecha,
                            Sum( compras.cantidad * compras.precio_costo ) AS costo,
                             ROUND(Sum( compras.cantidad * (compras.precio_costo/(1+tasa_iva.tasa)) ),2) AS iva,
                            ROUND(Sum( compras.cantidad * (compras.precio_costo-(compras.precio_costo/(1+tasa_iva.tasa)))),2) AS costo_sin_iva,
                            Sum( compras.cantidad ) AS cantidad,
                            compras.nro_factura,
                            Min( compras.sucursal_id ) AS sucursal_id,
                            Min( proveedores.razon_social ) AS proveedor_razon_social,
                            Min( compras.proveedor_id ) AS proveedor_id,
                            Min( sucursales.nombre ) AS sucursal_nombre,
                            CONCAT( MIN( productos.codigo ), ' - ', MIN( productos.descripcion ) ) AS producto,
                            Min(distribuidores_empresas.distribuidor_id) as distribuidor_id
                        FROM
                            compras
                            LEFT JOIN proveedores ON compras.proveedor_id = proveedores.id
                            LEFT JOIN sucursales ON compras.sucursal_id = sucursales.id
                            LEFT JOIN productos ON productos.id = compras.producto_id 
                            LEFT JOIN tasa_iva ON tasa_iva.id = productos.tasa_iva_id
                            LEFT JOIN distribuidores_empresas ON compras.empresa_id = distribuidores_empresas.empresa_id

                         ##WHERE## 
                        GROUP BY
                            compras.fecha,
                            compras.nro_factura,
                            compras.producto_id";

        $query_param = array();
        if (isset($_GET['order_by'])) {
            $order_by = sanitizeInput($_GET['order_by']);
        } else {
            $order_by = 'id';
        }
        if (isset($_GET['sort_order'])) {
            $sort_order = sanitizeInput($_GET['sort_order']);
        } else {
            $sort_order = ' ASC ';
        }

        //recibir todos los posibles parametros por GET
        if (isset($_GET['param'])) {
            $id = sanitizeInput($_GET['param']);
            array_push($query_param, "compras.id=$id");
        }

        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
            $fecha_inicio = sanitizeInput($_GET['fecha_inicio']);
            $fecha_fin = sanitizeInput($_GET['fecha_fin']);
            array_push($query_param, "compras.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'");
        }

        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);
            array_push($query_param, "compras.sucursal_id=$sucursal_id");
        }

        if (isset($_GET['proveedor_id'])) {
            $proveedor_id = sanitizeInput($_GET['proveedor_id']);
            array_push($query_param, "compras.proveedor_id=$proveedor_id");
        }

        if (isset($_GET['nro_factura'])) {
            $nro_factura = sanitizeInput($_GET['nro_factura']);
            array_push($query_param, "compras.nro_factura=$nro_factura");
        }
        if (isset($_GET['producto_id'])) {
            $producto_id = sanitizeInput($_GET['producto_id']);
            array_push($query_param, "compras.producto_id=$producto_id");
        }
        if (isset($_GET['distribuidor_id'])) {
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
            array_push($query_param, "distribuidores_empresas.distribuidor_id=$distribuidor_id");
        }
        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
            array_push($query_param, "distribuidores_empresas.empresa_id=$empresa_id");
        }

        if (count($query_param) > 0) {
            $where =  " WHERE (" . implode(" AND ", $query_param) . ")";
            $query_product = str_replace("##WHERE##", $where, $query_product);
        } else {
            $where = " ";
            $query_product = str_replace("##WHERE##", $where, $query_product);
        }



        //obtener el total de registros
        $query_total = "SELECT
                        COUNT(*) AS total
                        FROM
                            (
                            SELECT
                                COUNT( * ) AS total 
                            FROM
                                compras
                                LEFT JOIN distribuidores_empresas ON compras.empresa_id = distribuidores_empresas.empresa_id 
                            WHERE
                                distribuidores_empresas.distribuidor_id = $distribuidor_id
                            GROUP BY
                                producto_id,
                                fecha,
                            nro_factura 
                            ) AS subconsulta;";

        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;

        $query_total_resumen = "SELECT COUNT( subconsulta.total ) AS total,
                                        SUM( subconsulta.costo) AS costo,
                                        SUM( subconsulta.cantidad) AS cantidad
                                    FROM
                                        (
                                        SELECT
                                            COUNT( * ) AS total ,
                                            SUM( compras.cantidad * compras.precio_costo ) AS costo,
                                            SUM( compras.cantidad ) AS cantidad
                                        FROM
                                            compras
                                            LEFT JOIN distribuidores_empresas ON compras.empresa_id = distribuidores_empresas.empresa_id 
                                         ##WHERE## 
                                        GROUP BY
                                            producto_id,
                                            fecha,
                                        nro_factura 
                                        ) AS subconsulta";

        $query_total_resumen = str_replace("##WHERE##", $where, $query_total_resumen);

        $result_total_resumen = $con->query($query_total_resumen);
        $row_total = $result_total_resumen->fetch(PDO::FETCH_ASSOC);

        $costo = $row_total['costo'] ?? 0;
        $cantidad = $row_total['cantidad'] ?? 0;
        $resumen = array("costo" => $costo, "cantidad" => $cantidad);
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;
        $query_product = $query_product . " ORDER BY   compras.$order_by  $sort_order";

        $result = $con->query($query_product);
        $sucursal = array();
        $proveedor = array();
        $compras = array();

        $response = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sucursal['id'] = $row['sucursal_id'];
            $sucursal['nombre'] = $row['sucursal_nombre'];
            $proveedor['id'] = $row['proveedor_id'];
            $proveedor['razon_social'] = $row['proveedor_razon_social'];
            $compra['producto'] = $row['producto'];
            $compra['fecha'] = $row['fecha'];
            $compra['costo'] = $row['costo'];
            $compra['costo_sin_iva'] = $row['costo_sin_iva'];
            $compra['iva'] = $row['iva'];
            $compra['cantidad'] = $row['cantidad'];
            $compra['nro_factura'] = $row['nro_factura'];
            $compra['sucursal'] = $sucursal;
            $compra['proveedor'] = $proveedor;
            array_push($compras, $compra);
        }

        $compras_ = [];
        foreach ($compras as $item) {

            $formattedItem = [

                "fecha" => date('d-m-Y', strtotime($item['fecha'])),
                "costo" => '$' . $item['costo'],
                "cantidad" => $item['cantidad'],
                "costo_sin_iva" => '$' . $item['costo_sin_iva'],
                "iva" => '$' . $item['iva'],
                "nro_factura" => $item['nro_factura'] ?? 'Sin Asignar',
                "sucursal_id" => $item['sucursal']['nombre'],
                "proveedor_id" => $item['proveedor']['razon_social'],
                "producto_codigo" => $item['producto'],

            ];
            $compras_[] = $formattedItem;
        }

        $type = $_GET['type'];
        if ($type == 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'Fecha');
            $sheet->setCellValue('B1', 'Nro Factura');
            $sheet->setCellValue('C1', 'Producto');
            $sheet->setCellValue('D1', 'Total Sin Iva');
            $sheet->setCellValue('E1', 'Iva');
            $sheet->setCellValue('F1', 'Total');
            $sheet->setCellValue('G1', 'Sucursal');
            $sheet->setCellValue('H1', 'Proveedor');
            $i = 2;

            foreach ($compras_ as $compra) {
                $sheet->setCellValue('A' . $i, $compra['fecha']);
                $sheet->setCellValue('B' . $i, $compra['nro_factura']);
                $sheet->setCellValue('C' . $i, $compra['producto_codigo']);
                $sheet->setCellValue('D' . $i, $compra['costo_sin_iva']);
                $sheet->setCellValue('E' . $i, $compra['iva']);
                $sheet->setCellValue('F' . $i, $compra['costo']);
                $sheet->setCellValue('G' . $i, $compra['sucursal_id']);
                $sheet->setCellValue('H' . $i, $compra['proveedor_id']);

               
                $i++;
            }
            //totales
            $sheet->setCellValue('A' . $i, 'Total');
            $sheet->setCellValue('B' . $i, $resumen['costo']);
            $sheet->setCellValue('C' . $i, $resumen['cantidad']);
            $sheet->getStyle('A' . $i . ':G' . $i)->getFont()->setBold(true);
            $sheet->getStyle('A1:H1')->getFont()->setBold(true);
            $sheet->getStyle('A1:H1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');
            $sheet->getStyle('A' . $i . ':H' . $i)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFA0A0A0');

            //asignar bordes
            $sheet->getStyle('A1:H' . $i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A1:H1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

            //autoajustar columnas
            foreach (range('A', 'H') as $columnID) {
                $sheet->getColumnDimension($columnID)->setAutoSize(true);
            }


            $writer = new Xlsx($spreadsheet);
            $filename = 'compras' . $distribuidor_id . '.xlsx';
            $filepath = './../comprobantes/' . $filename;
            $writer->save($filepath);
            $response = array("status" => 200, "status_message" => "Archivo generado", "url" => './comprobantes/' . $filename);
        }
        if($type == 'pdf'){
           
            $filename = 'compras' . $distribuidor_id  . '.pdf';
            $filepath = './../comprobantes/'.$filename;
            $pdf = new FPDF();
           //crear paginas en horizontal
            $pdf->AddPage('L');
            //agragar titulo
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Informe de Compras - Periodo: ' . date('d-m-Y', strtotime($fecha_inicio)) . ' - ' . date('d-m-Y', strtotime($fecha_fin)), 0, 1, 'C');
        
            //agregar tabla
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(30, 10, 'Fecha', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Costo', 1, 0, 'C');
            $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
            
            $pdf->Cell(40, 10, 'Nro Factura', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Sucursal', 1, 0, 'C');
            $pdf->Cell(50, 10, 'Proveedor', 1, 0, 'C');
            $pdf->Cell(40, 10, 'Producto', 1, 1, 'C');
            $pdf->SetFont('Arial', '', 9);
            foreach ($compras_ as $compra) {
                $pdf->Cell(30, 10, $compra['fecha'], 1, 0, 'C');
                $pdf->Cell(30, 10, $compra['costo'], 1, 0, 'C');
                $pdf->Cell(30, 10, $compra['cantidad'], 1, 0, 'C');
                $pdf->Cell(40, 10, $compra['nro_factura'], 1, 0, 'C');
                $pdf->Cell(50, 10, $compra['sucursal_id'], 1, 0, 'C');
                $pdf->Cell(50, 10, $compra['proveedor_id'], 1, 0, 'C');
                $pdf->Cell(40, 10, $compra['producto_codigo'], 1, 1, 'C');
            }
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(30, 10, 'Total', 1, 0, 'C');
            $pdf->Cell(30, 10, '$'.$resumen['costo'], 1, 0, 'C');
            $pdf->Cell(30, 10, '$'.$resumen['cantidad'], 1, 0, 'C');
            $pdf->Cell(40, 10, '', 1, 0, 'C');
            $pdf->Cell(50, 10, '', 1, 0, 'C');
            $pdf->Cell(50, 10, '', 1, 0, 'C');
            $pdf->Cell(40, 10, '', 1, 1, 'C');
            $pdf->Output('F', $filepath);
            $response = array("status" => 200, "status_message" => "Archivo generado", "url" => './comprobantes/' . $filename);

        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}{$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
