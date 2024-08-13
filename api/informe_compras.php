<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                            compras.fecha,
                            Sum( compras.cantidad * compras.precio_costo ) AS costo,
                            Sum( compras.cantidad ) AS cantidad,
                            compras.nro_factura,
                            Min( compras.sucursal_id ) AS sucursal_id,
                            Min( proveedores.razon_social ) AS proveedor_razon_social,
                            Min( compras.proveedor_id ) AS proveedor_id,
                            Min( sucursales.nombre ) AS sucursal_nombre,
                            CONCAT( MIN( productos.codigo ), ' - ', MIN( productos.descripcion ) ) AS producto 
                        FROM
                            compras
                            LEFT JOIN proveedores ON compras.proveedor_id = proveedores.id
                            LEFT JOIN sucursales ON compras.sucursal_id = sucursales.id
                            INNER JOIN productos ON productos.id = compras.producto_id 
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

        if (count($query_param) > 0) {
            $where =  " WHERE (" . implode(" AND ", $query_param) . ") AND compras.empresa_id = $empresa_id";
            $query_product = str_replace("##WHERE##", $where, $query_product);
        }else{
            $where = " WHERE compras.empresa_id = $empresa_id";
            $query_product = str_replace("##WHERE##", $where, $query_product);
        }



        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM (SELECT COUNT(*) AS total FROM compras  WHERE empresa_id = $empresa_id GROUP BY producto_id, fecha,nro_factura) AS subconsulta;";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;
        $query_product = $query_product . " ORDER BY   compras.$order_by  $sort_order  LIMIT $limit OFFSET $offset";



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
            $compra['cantidad'] = $row['cantidad'];
            $compra['nro_factura'] = $row['nro_factura'];
            $compra['sucursal'] = $sucursal;
            $compra['proveedor'] = $proveedor;
            array_push($compras, $compra);
        }

        $response = array("status" => 200, "status_message" => "Datos cargados correctamente", "data" => $compras);
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
