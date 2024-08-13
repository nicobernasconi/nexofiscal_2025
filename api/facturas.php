<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                        view_facturas.nro_factura,
                        view_facturas.empresa_id,
                        view_facturas.total,
                        view_facturas.proveedor_id,
                        view_facturas.pagado
                    FROM
                        view_facturas";

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
        if (isset($_GET['nro_factura'])) {
            $nro_factura = sanitizeInput($_GET['nro_factura']);
            array_push($query_param, "nro_factura='$nro_factura'");
        }
        if (isset($_GET['sin_pagar'])) {
            array_push($query_param, "total > pagado");
        }
        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
            array_push($query_param, "empresa_id='$empresa_id'");
        }
        if (isset($_GET['total'])) {
            $total = sanitizeInput($_GET['total']);
            array_push($query_param, "total='$total'");
        }
        if (isset($_GET['proveedor_id'])) {
            $proveedor_id = sanitizeInput($_GET['proveedor_id']);
            array_push($query_param, "proveedor_id='$proveedor_id'");
        }
        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" and ", $query_param) . ") AND view_facturas.empresa_id = $empresa_id";
        } else {
            $query_product = $query_product . " WHERE view_facturas.empresa_id = $empresa_id";
        }
        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM view_facturas WHERE view_facturas.empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;


        $query_product = $query_product . " ORDER BY nro_factura LIMIT $limit OFFSET $offset";

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
        $facturas = array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $facturas = array(
                "nro_factura" => $row['nro_factura'],
                "empresa_id" => $row['empresa_id'],
                "total" => $row['total'],
                "proveedor_id" => $row['proveedor_id'],
                "pagado" => $row['pagado']
            );



            array_push($response, $facturas);
        }
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg} {$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
