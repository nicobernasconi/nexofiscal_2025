<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {
    


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                            id, 
                            empresa_id, 
                            activa, 
                            fecha_creacion, 
                            ciclo_facturacion
                        FROM
                            licencias";

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
        array_push($query_param, "agrupacion.id=$id");
    }

    if (isset($_GET['activa'])) {
        $activa = sanitizeInput($_GET['activa']);
        array_push($query_param, "activa=$activa");
    }

    if (isset($_GET['fecha_creacion'])) {
        $fecha_creacion = sanitizeInput($_GET['fecha_creacion']);
        array_push($query_param, "fecha_creacion='$fecha_creacion'");
    }

    if (isset($_GET['ciclo_facturacion'])) {
        $ciclo_facturacion = sanitizeInput($_GET['ciclo_facturacion']);
        array_push($query_param, "ciclo_facturacion='$ciclo_facturacion'");
    }
    
    if (isset($_GET['empresa_id'])) {
        $empresa_id = sanitizeInput($_GET['empresa_id']);
    }


    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param) . ") AND licencias.empresa_id = $empresa_id and activa=1";
    

    }else{
        $query_product = $query_product . " WHERE licencias.empresa_id = $empresa_id  and activa=1";
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM licencias WHERE licencias.empresa_id = $empresa_id  and activa=1";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY licencias.id LIMIT $limit OFFSET $offset";

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
    $agrupacion = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $agrupacion = array(
            'id' => $row['id'],
            'empresa_id' => $row['empresa_id'],
            'activa' => $row['activa'],
            'fecha_creacion' => $row['fecha_creacion'],
            'ciclo_facturacion' => $row['ciclo_facturacion']  
        );

       
        array_push($response, $agrupacion);
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

        $query_insert = "INSERT INTO licencias (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM licencias";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Licencia agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar licencia.", "id" => $id);
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
        $query_update = "UPDATE licencias SET " . implode(", ", $param_update) . " WHERE licencias.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "licencias actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar licencias.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM licencias WHERE licencias.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "licencias eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la licencias para eliminar.", "id" => $id);
            }
        } else {
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


