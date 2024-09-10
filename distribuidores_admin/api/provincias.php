<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    $query_product = "SELECT
                        provincias.id AS provincia_id,
                        provincias.nombre AS provincia_nombre,
                        provincias.pais_id AS pais_id,
                        pais.nombre AS pias_nombre

                     FROM
                        provincias
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
        array_push($query_param, "provincias.id=$id");
    }
    if (isset($_GET['nombre'])) {
        $descripcion = sanitizeInput($_GET['nombre']);
        array_push($query_param, "nombre like '%$descripcion%'");
    }

    if (isset($_GET['pais_id'])) {
        $pais_id = sanitizeInput($_GET['pais_id']);
        array_push($query_param, "pais_id='$pais_id'");
    }

    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE " . implode(" OR ", $query_param);
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM provincias";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY provincias. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
    $provincia = array();
    $pais=array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $pais = array(
            'id' => $row['pais_id'],
            'nombre' => $row['pias_nombre']
        );
        $provincia = array(
            'id' => $row['provincia_id'],
            'nombre' => $row['provincia_nombre'],
            'pais' => $pais
        );

        array_push($response, $provincia);
    }}
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

        $query_insert = "INSERT INTO provincias (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM provincias";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Provinciaagregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Provincia.", "id" => $id);
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
        $query_update = "UPDATE provincias SET " . implode(", ", $param_update) . " WHERE provincias.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Provincia actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Provincia.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM provincias WHERE provincias.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Provincia eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Provincia para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Provincia.", "id" => $id);
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

// ekemplo insert
// {
//     "nombre": "Cordoba",
//     "pais_id": 1
// }


