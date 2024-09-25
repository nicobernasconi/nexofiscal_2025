<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
        productos.id,
        productos.codigo,
        productos.descripcion,
        productos.precio1,
        productos.precio2,
        productos.precio3,
        productos.stock_minimo,
        productos.stock_pedido,
        productos_stock.stock_actual,
        sucursales.id as sucursal_id,
        sucursales.nombre as sucursal_nombre
        FROM
        productos 
        LEFT JOIN productos_stock ON productos_stock.producto_id = productos.id 
        LEFT JOIN sucursales ON productos_stock.sucursal_id = sucursales.id";

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
            array_push($query_param, "comprobantes.id=$id");
        }
        
        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);
            array_push($query_param, "sucursales.id=$sucursal_id");
        }
        if (isset($_GET['producto_id'])) {
            $producto_id = sanitizeInput($_GET['producto_id']);
            array_push($query_param, "productos.id=$producto_id");
        }

        if (isset($_GET['proveedor_id'])) {
            $proveedor_id = sanitizeInput($_GET['proveedor_id']);
            array_push($query_param, "productos.proveedor_id=$proveedor_id");
        }
        if (isset($_GET['codigo'])) {
            $codigo = sanitizeInput($_GET['codigo']);
            array_push($query_param, "productos.codigo=$codigo");
        }

        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" AND ", $query_param) . ") AND productos.empresa_id = $empresa_id AND productos.codigo <> ''";
            
        } else {
            $query_product = $query_product . " WHERE productos.empresa_id = $empresa_id AND productos.codigo <> ''";

        }

       

    
        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM productos WHERE empresa_id = $empresa_id AND productos.codigo <> ''";
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
        $productos = array();
        $sucursales = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sucursal = array(
                "id" => $row['sucursal_id'],
                "nombre" => $row['sucursal_nombre'],
                "stock_actual" => $row['stock_actual']
            );
            $producto = array(
                "id" => $row['id'],
                "codigo" => $row['codigo'],
                "descripcion" => $row['descripcion'],
                "precio1" => $row['precio1'],
                "precio2" => $row['precio2'],
                "precio3" => $row['precio3'],
                "stock_minimo" => $row['stock_minimo'],
                "stock_pedido" => $row['stock_pedido'],
                "sucursal" => $sucursal
            );
            array_push($productos, $producto);
                    }
        $response = array("status" => 200, "status_message" => "Datos cargados correctamente", "data" => $productos);
    
        
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
