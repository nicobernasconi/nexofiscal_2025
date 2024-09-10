<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                        localidad.id AS localidad_id,
                        localidad.nombre AS localidad_nombre,
                        localidad.provincia_id,
                        localidad.codigo_postal AS localidad_codigo_postal,
                        provincias.nombre AS provincia_nombre,
                        provincias.pais_id AS pais_id,
                        pais.nombre AS pais_nombre
                     FROM
                        localidad
                     LEFT JOIN provincias ON localidad.provincia_id = provincias.id
                     LEFT JOIN pais ON provincias.pais_id = pais.id";

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
        array_push($query_param, "localidad.id=$id");
    }
    if (isset($_GET['nombre'])) {
        $descripcion = sanitizeInput($_GET['nombre']);
        array_push($query_param, "localidad.nombre like '%$descripcion%'");
    }

    if (isset($_GET['provincia_id'])) {
        $provincia_id = sanitizeInput($_GET['provincia_id']);
        array_push($query_param, "provincia_id='$provincia_id'");
    }

    if (isset($_GET['codigo_postal'])) {
        $codigo_postal = sanitizeInput($_GET['codigo_postal']);
        array_push($query_param, "codigo_postal='$codigo_postal'");
    }


    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE " . implode(" OR ", $query_param);
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM localidad";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY localidad. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
    $localidad = array();
    $provincia = array();
    $pais = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $pais = array(
            'id' => $row['pais_id'],
            'nombre' => $row['pais_nombre']
        );
        $provincia = array(
            'id' => $row['provincia_id'],
            'nombre' => $row['provincia_nombre'],
            'pais' => $pais
        );
        $localidad = array(
            'id' => $row['localidad_id'],
            'nombre' => $row['localidad_nombre'],
            'codigo_postal' => $row['localidad_codigo_postal'],
            'provincia' => $provincia
        );       
       
        array_push($response, $localidad);
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

        $query_insert = "INSERT INTO localidad (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM localidad";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Localidad agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Localidad .", "id" => $id);
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
        $query_update = "UPDATE localidad SET " . implode(", ", $param_update) . " WHERE localidad.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Localidad  actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Localidad .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM localidad WHERE localidad.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Localidad  eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Localidad  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Localidad .", "id" => $id);
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





