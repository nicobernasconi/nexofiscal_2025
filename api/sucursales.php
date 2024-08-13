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
                            nombre, 
                            direccion, 
                            telefono, 
                            email, 
                            contacto_nombre, 
                            contacto_telefono, 
                            contacto_email, 
                            referente_nombre, 
                            referente_telefono, 
                            referente_email
                          FROM
                            sucursales";

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
            array_push($query_param, "sucursales.id=$id");
        }
        if (isset($_GET['nombre'])) {
            $descripcion = sanitizeInput($_GET['nombre']);
            array_push($query_param, "nombre like '%$descripcion%'");
        }
        $empresa_id_query='';
        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
            $empresa_id_query="empresa_id=$empresa_id";
        }

        if (isset($_GET['direccion'])) {
            $direccion = sanitizeInput($_GET['direccion']);
            array_push($query_param, "direccion like '%$direccion%'");
        }

        if (isset($_GET['telefono'])) {
            $telefono = sanitizeInput($_GET['telefono']);
            array_push($query_param, "telefono like '%$telefono%'");
        }

        if (isset($_GET['email'])) {
            $email = sanitizeInput($_GET['email']);
            array_push($query_param, "email like '%$email%'");
        }

        if (isset($_GET['contacto_nombre'])) {
            $contacto_nombre = sanitizeInput($_GET['contacto_nombre']);
            array_push($query_param, "contacto_nombre like '%$contacto_nombre%'");
        }

        if (isset($_GET['contacto_telefono'])) {
            $contacto_telefono = sanitizeInput($_GET['contacto_telefono']);
            array_push($query_param, "contacto_telefono like '%$contacto_telefono%'");
        }

        if (isset($_GET['contacto_email'])) {
            $contacto_email = sanitizeInput($_GET['contacto_email']);
            array_push($query_param, "contacto_email like '%$contacto_email%'");
        }

        if (isset($_GET['referente_nombre'])) {
            $referente_nombre = sanitizeInput($_GET['referente_nombre']);
            array_push($query_param, "referente_nombre like '%$referente_nombre%'");
        }

        if (isset($_GET['referente_telefono'])) {
            $referente_telefono = sanitizeInput($_GET['referente_telefono']);
            array_push($query_param, "referente_telefono like '%$referente_telefono%'");
        }

        if (isset($_GET['referente_email'])) {
            $referente_email = sanitizeInput($_GET['referente_email']);
            array_push($query_param, "referente_email like '%$referente_email%'");
        }



        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param) . ") and empresa_id=$empresa_id";
        }else{
            $query_product = $query_product . " WHERE empresa_id=$empresa_id";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM sucursales WHERE empresa_id=$empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY sucursales. " . $order_by . "  " . $sort_order . " OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";

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
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $sucursal = array(
                "id" => $row['id'],
                "empresa_id" => $row['empresa_id'],
                "nombre" => $row['nombre'],
                "direccion" => $row['direccion'],
                "telefono" => $row['telefono'],
                "email" => $row['email'],
                "contacto_nombre" => $row['contacto_nombre'],
                "contacto_telefono" => $row['contacto_telefono'],
                "contacto_email" => $row['contacto_email'],
                "referente_nombre" => $row['referente_nombre'],
                "referente_telefono" => $row['referente_telefono'],
                "referente_email" => $row['referente_email']
            );


            array_push($response, $sucursal);
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

        $query_insert = "INSERT INTO sucursales (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM sucursales";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "sucursales agregado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar sucursales.", "id" => $id);
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
        $query_update = "UPDATE sucursales SET " . implode(", ", $param_update) . " WHERE sucursales.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "sucursales actualizado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar sucursales.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM sucursales WHERE sucursales.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "sucursales eliminado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró el sucursales para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar sucursales.", "id" => $id);
        }
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
