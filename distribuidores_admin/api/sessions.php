<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {
    


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                        sessions.id,
                        sessions.usuario_id,
                        sessions.token,
                        sessions.licencia_id,
                        sessions.tiempo,
                        sessions.`host`,
                        sessions.agent,
                        usuarios.nombre_usuario,
                        usuarios.empresa_id as empresa_id
                      FROM
                        sessions
                        INNER JOIN usuarios ON sessions.usuario_id = usuarios.id";

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
        array_push($query_param, "sessions.id=$id");
    }

    if (isset($_GET['usuario_id'])) {
        $usuario_id = sanitizeInput($_GET['usuario_id']);
        array_push($query_param, "usuario_id=$usuario_id");
    }

    if (isset($_GET['token'])) {
        $token = sanitizeInput($_GET['token']);
        array_push($query_param, "token='$token'");

    }

    if (isset($_GET['licencia_id'])) {
        $licencia_id = sanitizeInput($_GET['licencia_id']);
        array_push($query_param, "licencia_id=$licencia_id");
    }

    if (isset($_GET['tiempo'])) {
        $tiempo = sanitizeInput($_GET['tiempo']);
        array_push($query_param, "tiempo='$tiempo'");
    }

    if (isset($_GET['host'])) {
        $host = sanitizeInput($_GET['host']);
        array_push($query_param, "host='$host'");
    }

    if (isset($_GET['agent'])) {
        $agent = sanitizeInput($_GET['agent']);
        array_push($query_param, "agent='$agent'");
    }

    if (isset($_GET['nombre_usuario'])) {
        $nombre_usuario = sanitizeInput($_GET['nombre_usuario']);
        array_push($query_param, "usuarios.nombre_usuario='$nombre_usuario'");
    }

    if (isset($_GET['empresa_id'])) {
        $empresa_id = sanitizeInput($_GET['empresa_id']);
    }
    

    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param) . ") AND empresa_id = $empresa_id";
    

    }else{
        $query_product = $query_product . " WHERE usuarios.empresa_id = $empresa_id";
    }



    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM sessions INNER JOIN usuarios ON sessions.usuario_id = usuarios.id WHERE empresa_id = $empresa_id";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY sessions.id LIMIT $limit OFFSET $offset";

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
            "id" => $row['id'],
            "usuario_id" => $row['usuario_id'],
            "token" => $row['token'],
            "licencia_id" => $row['licencia_id'],
            "tiempo" => $row['tiempo'],
            "host" => $row['host'],
            "agent" => $row['agent'],
            "nombre_usuario" => $row['nombre_usuario']
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

        $query_insert = "INSERT INTO sessions (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM sessions";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Sesion agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Sesion.", "id" => $id);
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
        $query_update = "UPDATE sessions SET " . implode(", ", $param_update) . " WHERE sessions.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Sesion actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Sesion.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM sessions WHERE sessions.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Sesion eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Sesion para eliminar.", "id" => $id);
            }
        } else {
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
   $error_msg=$errores_mysql[$th->getCode()]??"Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}{$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}


