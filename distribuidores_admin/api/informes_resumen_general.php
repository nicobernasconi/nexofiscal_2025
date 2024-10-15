<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {

 

    $headers = apache_request_headers();
    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['distribuidor_id'])) {
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
        }
        $query_ventas = "SELECT
                                sum(comprobantes.total) as ventas
                            FROM
                                comprobantes
                            INNER JOIN distribuidores_empresas ON comprobantes.empresa_id = distribuidores_empresas.empresa_id
                            WHERE comprobantes.tipo_comprobante_id=1 and distribuidores_empresas.distribuidor_id=$distribuidor_id and fecha BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW()";
        $query_devoluciones = "SELECT
                            sum(comprobantes.total) as devoluciones
                            FROM
                            comprobantes
                            INNER JOIN distribuidores_empresas ON comprobantes.empresa_id = distribuidores_empresas.empresa_id
                            where comprobantes.tipo_comprobante_id=4 and distribuidores_empresas.distribuidor_id=$distribuidor_id and fecha BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW()";

        $query_pedidos = "SELECT
                            sum(comprobantes.total) as pedidos
                            FROM
                            comprobantes
                            INNER JOIN distribuidores_empresas ON comprobantes.empresa_id = distribuidores_empresas.empresa_id
                            where comprobantes.tipo_comprobante_id=3 and distribuidores_empresas.distribuidor_id=$distribuidor_id and fecha BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW()";

        $query_count_empresas = "SELECT count(*) as count_empresas
                            FROM empresas
                            INNER JOIN distribuidores_empresas ON empresas.id = distribuidores_empresas.empresa_id
                            where distribuidores_empresas.distribuidor_id=$distribuidor_id";
        $query_count_sucursales = "SELECT count(*) as count_sucursales
                            FROM sucursales
                            INNER JOIN distribuidores_empresas ON sucursales.empresa_id = distribuidores_empresas.empresa_id
                            where distribuidores_empresas.distribuidor_id=$distribuidor_id";



        $result_ventas_total = $con->query($query_ventas);
        $row_ventas_total = $result_ventas_total->fetch(PDO::FETCH_ASSOC);
        $total_ventas = $row_ventas_total['ventas'];

        $result_devoluciones_total = $con->query($query_devoluciones);
        $row_devoluciones_total = $result_devoluciones_total->fetch(PDO::FETCH_ASSOC);
        $total_devoluciones = $row_devoluciones_total['devoluciones'];

        $result_pedidos_total = $con->query($query_pedidos);
        $row_pedidos_total = $result_pedidos_total->fetch(PDO::FETCH_ASSOC);
        $total_pedidos = $row_pedidos_total['pedidos'];

        $result_count_empresas = $con->query($query_count_empresas);
        $row_count_empresas = $result_count_empresas->fetch(PDO::FETCH_ASSOC);
        $count_empresas = $row_count_empresas['count_empresas'];

        $result_count_sucursales = $con->query($query_count_sucursales);
        $row_count_sucursales = $result_count_sucursales->fetch(PDO::FETCH_ASSOC);
        $count_sucursales = $row_count_sucursales['count_sucursales'];

        $response = array(
            "status" => 200,
            "status_message" => "OK",
            "total_ventas" => round($total_ventas??0,2),
            "total_devoluciones" => round($total_devoluciones??0,2),
            "total_pedidos" => round($total_pedidos??0,2),
            "count_empresas" => $count_empresas??0,
            "count_sucursales" => $count_sucursales??0
        );
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
