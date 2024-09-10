<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                            pagos.id,
                            pagos.proveedor_id,
                            pagos.nro_factura,
                            pagos.tipo_pago_id,
                            pagos.monto,
                            pagos.empresa_id,
                            pagos.fecha,
                            pagos.nro_comprobante 
                        FROM
                            pagos";

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



    if (isset($_GET['nro_factura'])) {
        $nro_factura = sanitizeInput($_GET['nro_factura']);
        array_push($query_param, "nro_factura='$nro_factura'");
    }
    if (isset($_GET['proveedor_id'])) {
        $proveedor_id = sanitizeInput($_GET['proveedor_id']);
        array_push($query_param, "proveedor_id='$proveedor_id'");
    }
    if (isset($_GET['tipo_pago_id'])) {
        $tipo_pago_id = sanitizeInput($_GET['tipo_pago_id']);
        array_push($query_param, "tipo_pago_id='$tipo_pago_id'");
    }
    if (isset($_GET['nro_comprobante'])) {
        $nro_comprobante = sanitizeInput($_GET['nro_comprobante']);
        array_push($query_param, "nro_comprobante='$nro_comprobante'");
    }
    if (isset($_GET['fecha'])) {
        $fecha = sanitizeInput($_GET['fecha']);
        array_push($query_param, "fecha='$fecha'");
    }
    if (isset($_GET['monto'])) {
        $monto = sanitizeInput($_GET['monto']);
        array_push($query_param, "monto='$monto'");
    }
    if (isset($_GET['empresa_id'])) {
        $empresa_id = sanitizeInput($_GET['empresa_id']);
        array_push($query_param, "empresa_id='$empresa_id'");
    }

    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE (" . implode(" and ", $query_param) . ") AND pagos.empresa_id = $empresa_id";
    } else {
        $query_product = $query_product . " WHERE pagos.empresa_id = $empresa_id";
    }
    



    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM pagos WHERE pagos.empresa_id = $empresa_id";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY pagos. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
    $pagoa = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $pagos = array(
            "id" => $row['id'],
            "proveedor_id" => $row['proveedor_id'],
            "nro_factura" => $row['nro_factura'],
            "tipo_pago_id" => $row['tipo_pago_id'],
            "monto" => $row['monto'],
            "empresa_id" => $row['empresa_id'],
            "fecha" => $row['fecha'],
            "nro_comprobante" => $row['nro_comprobante']
        );       
        array_push($response, $pagos);
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

        $query_insert = "INSERT INTO pagos (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);
        $id = $con->lastInsertId();
        if ($result) {

            $response = array("status" => 201, "status_message" => "Pago agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Pago.", "id" => $id);
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
            $response = array("status" => 201, "status_message" => "Pago actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Pago.", "id" => $id);
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
                $response = array("status" => 201, "status_message" => "Pago eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Pago para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Pago.", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
   $error_msg=$errores_mysql[$th->getCode()]??"Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg} {$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



