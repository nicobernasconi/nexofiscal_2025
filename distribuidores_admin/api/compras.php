<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                        compras.id,
                        compras.fecha,
                        compras.producto_id,
                        compras.precio_costo,
                        compras.cantidad,
                        compras.nro_factura,
                        compras.proveedor_id,
                        compras.sucursal_id,
                        productos.id,
                        productos.codigo AS producto_codigo,
                        productos.descripcion AS producto_descripcion,
                        productos.codigo_barra AS producto_codigo_barra 
                      FROM
                        compras
                        INNER JOIN productos ON compras.producto_id = productos.id";

    $query_param = array();
if (isset($_GET['order_by'])) {
        $order_by = sanitizeInput($_GET['order_by']);
    }else{
        $order_by ='id';
    }
if (isset($_GET['sort_order'])) {
        $sort_order= sanitizeInput($_GET['sort_order']);
    }else{
        $sort_order=' ASC ';
    }

    //quitar todos parametros GET que tienen valor vacio
    $_GET = array_filter($_GET);



    //recibir todos los posibles parametros por GET
    if (isset($_GET['param'])) {
        $id = sanitizeInput($_GET['param']);
        array_push($query_param, "compras.id=$id");
    }
    if (isset($_GET['fecha'])) {
        $fecha = sanitizeInput($_GET['fecha']);
        array_push($query_param, "fecha like '%$fecha%'");
    }
    if (isset($_GET['producto_id'])) {
        $producto_id = sanitizeInput($_GET['producto_id']);
        array_push($query_param, "producto_id=$producto_id");
    }
    if (isset($_GET['precio_costo'])) {
        $precio_costo = sanitizeInput($_GET['precio_costo']);
        array_push($query_param, "precio_costo=$precio_costo");
    }
    if (isset($_GET['cantidad'])) {
        $cantidad = sanitizeInput($_GET['cantidad']);
        array_push($query_param, "cantidad=$cantidad");
    }
    if (isset($_GET['nro_factura'])) {
        $nro_factura = sanitizeInput($_GET['nro_factura']);
        array_push($query_param, "nro_factura like '%$nro_factura%'");
    }
    if (isset($_GET['proveedor_id'])) {
        $proveedor_id = sanitizeInput($_GET['proveedor_id']);
        array_push($query_param, "proveedor_id=$proveedor_id");
    }
    if (isset($_GET['sucursal_id'])) {
        $sucursal_id = sanitizeInput($_GET['sucursal_id']);
        array_push($query_param, "sucursal_id=$sucursal_id");
    }



    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param).") AND compras.empresa_id = $empresa_id";
    }else{
        $query_product = $query_product . " WHERE compras.empresa_id = $empresa_id";
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM compras WHERE compras.empresa_id = $empresa_id";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY compras. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
    $compras = array();
    $productos = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $productos = array(
            'id' => $row['id'],
            'producto_id' => $row['producto_id'],
            'producto_codigo' => $row['producto_codigo'],
            'producto_descripcion' => $row['producto_descripcion'],
            'producto_codigo_barra' => $row['producto_codigo_barra']
        );
        
        $compras = array(
            'id' => $row['id'],
            'fecha' => $row['fecha'],
            'precio_costo' => $row['precio_costo'],
            'cantidad' => $row['cantidad'],
            'nro_factura' => $row['nro_factura'],
            'proveedor_id' => $row['proveedor_id'],
            'sucursal_id' => $row['sucursal_id'],
            'productos' => $productos,

        );

       
        array_push($response, $compras);
    }
}
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $param_insert = array();
        $param_values = array();

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';

            array_push($param_insert, $key);
            array_push($param_values, $escaped_value);
        }
        array_push($param_insert, 'empresa_id');
        array_push($param_values, $empresa_id);

        $query_insert = "INSERT INTO compras (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM compras";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $sucursal_id = $data['sucursal_id'];
            $producto_id = $data['producto_id'];
            $cantidad = $data['cantidad'];
            $query_stock = "INSERT INTO productos_stock (producto_id, sucursal_id, stock_actual,empresa_id) VALUES ($producto_id, $sucursal_id, $cantidad,$empresa_id) ON DUPLICATE KEY UPDATE stock_actual = stock_actual + $cantidad;";
            $result_stock = $con->query($query_stock);
            $response = array("status" => 201, "status_message" => "compras agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar compras.", "id" => $id);
        }
    }

    //PUT PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $param_update = array();
        $param_update_values = array();

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';

            array_push($param_update, $key . "=" . $escaped_value);
        }
        $id = sanitizeInput($_GET['param']);
        $query_update = "UPDATE compras SET " . implode(", ", $param_update) . " WHERE compras.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "compras actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar compras.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM compras WHERE compras.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "compras eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la compras para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar compras.", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
   $error_msg=$errores_mysql[$th->getCode()]??"Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



