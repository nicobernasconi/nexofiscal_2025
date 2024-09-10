<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_param = array();
        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);
            array_push($query_param, "comprobantes.sucursal_id=$sucursal_id");
        }

        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
            array_push($query_param, "comprobantes.empresa_id=$empresa_id");
        }

        if (isset($_GET['familia_id'])) {
            $familia_id = sanitizeInput($_GET['familia_id']);
            array_push($query_param, "productos.familia_id=$familia_id");
        }

        $where = "";
        if (count($query_param) > 0) {
            $where = " WHERE " . implode(" AND ", $query_param);
        }


        $query_product = "SELECT
                                productos.codigo,
                                productos.descripcion,
                                productos.familia_id,
                                SUM( renglones_comprobantes.cantidad ) AS total_cantidad,
                                SUM( renglones_comprobantes.total_linea ) AS total_vendido,
                                (
                                    SUM( renglones_comprobantes.total_linea ) / ( SELECT SUM( renglones_comprobantes.total_linea ) FROM renglones_comprobantes LEFT JOIN productos ON renglones_comprobantes.producto_id = productos.id where productos.empresa_id= $empresa_id)* 100 
                                ) AS proporcion 
                            FROM
                                renglones_comprobantes
                                LEFT JOIN productos ON renglones_comprobantes.producto_id = productos.id
                                LEFT JOIN comprobantes ON renglones_comprobantes.comprobante_id = comprobantes.id
                                LEFT JOIN distribuidores_empresas ON comprobantes.empresa_id = distribuidores_empresas.empresa_id 
                            $where
                            GROUP BY
                                productos.codigo,
                                productos.descripcion,
                                productos.familia_id";

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

        

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM ($query_product) totales";
  
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY  " . $order_by . "  " . $sort_order . " LIMIT $limit OFFSET $offset";

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
        $productos = array();
        $response= array();


        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $producto = array(
                "codigo" => $row['codigo'],
                "descripcion" => $row['descripcion'],
                "familia_id" => $row['familia_id'],
                "total_cantidad" => $row['total_cantidad'],
                "total_vendido" => $row['total_vendido'],
                "proporcion" => round($row['proporcion'], 2).'%'
            );
            array_push($productos, $producto);
        }

        array_push($response, $productos);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $response = array("status" => 500, "status_message" => "Error en el servidor.", "descripcion" => "Codigo de error {$th->getCode()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
