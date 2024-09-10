<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                        ganancias_proveedores.id,
                        ganancias_proveedores.proveedor_id,
                        ganancias_proveedores.ingresos_gravados,
                        ganancias_proveedores.deducciones,
                        ganancias_proveedores.ganancia_neta,
                        ganancias_proveedores.impuesto_calculado,
                        ganancias_proveedores.periodo_fiscal

                    FROM
                        ganancias_proveedores";

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



    //recibir todos los posibles parametros por GET
    if (isset($_GET['param'])) {
        $id = sanitizeInput($_GET['param']);
        array_push($query_param, "ganancias_proveedores.id=$id");
    }

    if (isset($_GET['proveedor_id'])) {
        $proveedor_id = sanitizeInput($_GET['proveedor_id']);
        array_push($query_param, "ganancias_proveedores.proveedor_id=$proveedor_id");
    }

    if (isset($_GET['ingresos_gravados'])) {
        $ingresos_gravados = sanitizeInput($_GET['ingresos_gravados']);
        array_push($query_param, "ganancias_proveedores.ingresos_gravados=$ingresos_gravados");
    }

    if (isset($_GET['deducciones'])) {
        $deducciones = sanitizeInput($_GET['deducciones']);
        array_push($query_param, "ganancias_proveedores.deducciones=$deducciones");
    }

    if (isset($_GET['ganancia_neta'])) {
        $ganancia_neta = sanitizeInput($_GET['ganancia_neta']);
        array_push($query_param, "ganancias_proveedores.ganancia_neta=$ganancia_neta");
    }

    if (isset($_GET['impuesto_calculado'])) {
        $impuesto_calculado = sanitizeInput($_GET['impuesto_calculado']);
        array_push($query_param, "ganancias_proveedores.impuesto_calculado=$impuesto_calculado");
    }

    if (isset($_GET['periodo_fiscal'])) {
        $periodo_fiscal = sanitizeInput($_GET['periodo_fiscal']);
        array_push($query_param, "ganancias_proveedores.periodo_fiscal=$periodo_fiscal");
    }

    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param).") AND ganancias_proveedores.empresa_id = $empresa_id";
    }else{
        $query_product = $query_product . " WHERE ganancias_proveedores.empresa_id = $empresa_id";
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM ganancias_proveedores WHERE ganancias_proveedores.empresa_id = $empresa_id";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY ganancias_proveedores. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
    $ganancias_proveedores = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $ganancias_proveedores = array(
            'id' => $row['id'],
            'proveedor_id' => $row['proveedor_id'],
            'ingresos_gravados' => $row['ingresos_gravados'],
            'deducciones' => $row['deducciones'],
            'ganancia_neta' => $row['ganancia_neta'],
            'impuesto_calculado' => $row['impuesto_calculado'],
            'periodo_fiscal' => $row['periodo_fiscal']
        );
           

        array_push($response, $ganancias_proveedores);
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

        $query_insert = "INSERT INTO ganancias_proveedores (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM ganancias_proveedores";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Ganancias Proveedor agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Ganancias Proveedor .", "id" => $id);
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
        $query_update = "UPDATE ganancias_proveedores SET " . implode(", ", $param_update) . " WHERE ganancias_proveedores.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Ganancias Proveedor  actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Ganancias Proveedor .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM ganancias_proveedores WHERE ganancias_proveedores.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Ganancias Proveedor  eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Ganancias Proveedor  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Ganancias Proveedor .", "id" => $id);
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


