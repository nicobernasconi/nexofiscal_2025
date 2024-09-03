<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
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
        if(isset($_GET['empresa_id'])){
            $empresa_id = sanitizeInput($_GET['empresa_id']);   
        }



        if (count($query_param) > 0) {
            $query_product = $query_product . " and (" . implode(" AND ", $query_param) . ") AND comprobantes.empresa_id = $empresa_id";
        } else {
            $query_product = $query_product . " and comprobantes.empresa_id = $empresa_id";
        }

         //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM  ($query_product) t";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;

        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;
        $query_product = $query_product . " ORDER BY   $order_by  $sort_order  LIMIT $limit OFFSET $offset";



        //header con la informacion de la paginacion
        header("X-Total-Count: $total");
        header("Access-Control-Expose-Headers: X-Total-Count");
        header("X-Total-Pages: $cont_pages");
        header("Access-Control-Expose-Headers: X-Total-Pages");
        header("X-Current-Page: $offset");
        header("Access-Control-Expose-Headers: X-Current-Page");
        header("X-Per-Page: $limit");
        header("Access-Control-Expose-Headers: X-Per-Page");

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
            }else if ($row['tipo_factura'] == 3) {
                $letra = 'A';
            }else if ($row['tipo_factura'] == 8) {
                $letra = 'B';
            }else if ($row['tipo_factura'] == 13) {
                $letra = 'C';
            }
            


            $comprobante = array(   
                "dia" => $row['dia'],
                "numero_factura" => $row['tipo_comprobante_nombre'].' '.$letra.' '.sprintf("%04d %08d", $row['punto_venta'], $row['numero_factura']),
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

        $response = array("status" => 200, "status_message" => "Datos cargados correctamente", "data" => $comprobantes, "resumen" => $resumen);
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
