<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {
    

$headers = apache_request_headers();


//GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {

$query_product = "SELECT
subfamilias.id AS subfamilia_id,
subfamilias.numero AS subfamilia_numero,
subfamilias.familia_id AS subfamilia_familia_id,
subfamilias.descripcion AS subfamilia_descripcion,
familias.numero AS familia_numero,
familias.nombre AS familia_nombre

FROM
subfamilias
LEFT JOIN familias ON subfamilias.familia_id = familias.id";

$query_param = array();

//recibir todos los posibles parametros por GET
if (isset($_GET['param'])) {
    $id = sanitizeInput($_GET['param']);
    array_push($query_param, "subfamilias.id=$id");
}
if (isset($_GET['numero'])) {
    $codigo = sanitizeInput($_GET['numero']);
    array_push($query_param, "subfamilia_numero='$codigo'");
}
if (isset($_GET['nombre'])) {
    $descripcion = sanitizeInput($_GET['nombre']);
    array_push($query_param, "subfamilia_nombre like '%$descripcion%'");
}

if (count($query_param) > 0) {
    $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param).") AND subfamilias.empresa_id = $empresa_id";
}else{
    $query_product = $query_product . " WHERE subfamilias.empresa_id = $empresa_id";
}

//obtener el total de registros
$query_total = "SELECT COUNT(*) AS total FROM subfamilias WHERE subfamilias.empresa_id = $empresa_id";
$result_total = $con->query($query_total);
$row_total = $result_total->fetch(PDO::FETCH_ASSOC);
$total = $row_total['total'] ?? 0;
//limites de la paginacion
$limit =$_GET['limit'] ?? 255;
$cont_pages=ceil($total/$limit);
$offset = $_GET['offset'] ?? 0;

$query_product = $query_product . " ORDER BY subfamilias. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
$producto = array();
$familia = array();
$subfamilia = array();
$agrupacion = array();
$proveedor = array();
$moneda = array();
$tipo = array();

$response = array();

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $subfamilia = array(
        'id' => $row['subfamilia_id'],
        'numero' => $row['subfamilia_numero'],
        'descripcion' => $row['subfamilia_descripcion'],
    );
    $familia = array(
        'id' => $row['subfamilia_familia_id'],
        'numero' => $row['familia_numero'],
        'nombre' => $row['familia_nombre'],
    );
    $subfamilia['familia'] = $familia;
    array_push($response, $subfamilia);
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

    $query_insert = "INSERT INTO subfamilias (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
    $result = $con->query($query_insert);
  
    $query_id = "SELECT MAX(id) AS id FROM subfamilias";
    $result_id = $con->query($query_id);
    $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
    $id = $row_id['id'] ?? null;
    if ($result) {
        $response = array("status" => 201, "status_message" => "SubFamilia agregada correctamente.", "id" => $id);
    } else {
        $response = array("status" => 400, "status_message" => "Error al agregar subfamilias.", "id" => $id);
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
    $query_update = "UPDATE subfamilias SET " . implode(", ", $param_update) . " WHERE subfamilias.id = " . $id;
    $result = $con->query($query_update);
    if ($result) {
        $response = array("status" => 201, "status_message" => "SubFamilia actualizada correctamente.", "id" => $id);
    } else {
        $response = array("status" => 400, "status_message" => "Error al actualizar subfamilias.", "id" => $id);
    }
}
//DELETE PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

    // Utilizar una consulta preparada para evitar inyección SQL
    $query_delete = "DELETE FROM subfamilias WHERE subfamilias.id = :id";
    $stmt = $con->prepare($query_delete);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Verificar si se eliminó algún registro
        if ($stmt->rowCount() > 0) {
            $response = array("status" => 201, "status_message" => "SubFamilia eliminada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 404, "status_message" => "No se encontró la subfamilia para eliminar.", "id" => $id);
        }
    } else {
        $response = array("status" => 400, "status_message" => "Error al eliminar subfamilias.", "id" => $id);
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

?>


