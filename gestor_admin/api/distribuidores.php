<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();
    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
	distribuidor.id,
	distribuidor.nombre,
	distribuidor.telefono,
	distribuidor.responsable,
	distribuidor.logo,
	distribuidor.email,
	distribuidor.`password`,
	gestores_distribuidores.gestor_id,
	(SELECT count( * ) FROM distribuidores_empresas WHERE distribuidores_empresas.distribuidor_id = distribuidor.id ) AS empresas 
    FROM
    distribuidor
    LEFT JOIN gestores_distribuidores ON distribuidor.id = gestores_distribuidores.distribuidor_id where gestores_distribuidores.gestor_id=$usuario_id";

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
        array_push($query_param, "distribuidor.id=$id");
    }
    if (isset($_GET['nombre'])) {
        $descripcion = sanitizeInput($_GET['nombre']);
        array_push($query_param, "distribuidor.nombre like '%$descripcion%'");
    }
    if (isset($_GET['logo'])) {
        $descripcion = sanitizeInput($_GET['logo']);
        array_push($query_param, "distribuidor.logo like '%$descripcion%'");
    }

    if (isset($_GET['direccion'])) {
        $descripcion = sanitizeInput($_GET['direccion']);
        array_push($query_param, "distribuidor.direccion like '%$descripcion%'");
    }
    if (isset($_GET['telefono'])) {
        $descripcion = sanitizeInput($_GET['telefono']);
        array_push($query_param, "distribuidor.telefono like '%$descripcion%'");
    }
    if (isset($_GET['tipo_iva'])) {
        $descripcion = sanitizeInput($_GET['tipo_iva']);
        array_push($query_param, "distribuidor.tipo_iva like '%$descripcion%'");
    }
    if (isset($_GET['cuit'])) {
        $descripcion = sanitizeInput($_GET['cuit']);
        array_push($query_param, "distribuidor.cuit like '%$descripcion%'");
    }
    if (isset($_GET['responsable'])) {
        $descripcion = sanitizeInput($_GET['responsable']);
        array_push($query_param, "distribuidor.responsable like '%$descripcion%'");
    }
    if (isset($_GET['email'])) {
        $descripcion = sanitizeInput($_GET['email']);
        array_push($query_param, "distribuidor.email like '%$descripcion%'");
    }
    if (isset($_GET['rubro_id'])) {
        $descripcion = sanitizeInput($_GET['rubro_id']);
        array_push($query_param, "distribuidor.rubro_id like '%$descripcion%'");
    }
    if (isset($_GET['fecha_inicio_actividades'])) {
        $descripcion = sanitizeInput($_GET['fecha_inicio_actividades']);
        array_push($query_param, "distribuidor.fecha_inicio_actividades like '%$descripcion%'");
    }
    if (isset($_GET['descripcion'])) {
        $descripcion = sanitizeInput($_GET['descripcion']);
        array_push($query_param, "distribuidor.descripcion like '%$descripcion%'");
    }


    if (count($query_param) > 0) {
        $query_product = $query_product . " and  (" . implode(" AND ", $query_param) . ")";
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM distribuidor";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY distribuidor. ".$order_by."  ".$sort_order." OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";

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
    $distribuidor = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $distribuidor = array(
            "id" => $row['id'],
            "nombre" => $row['nombre'],
            "telefono" => $row['telefono'],
            "responsable" => $row['responsable'],
            "logo" => $row['logo'],
            "email" => $row['email'],
            "password" => $row['password'],
            "gestor_id" => $row['gestor_id'],
            "empresas" => $row['empresas']

            
        );


            
       
        array_push($response, $distribuidor);
    }

}
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        //encriptar password con md5
        $data['password'] = md5($data['password']);
        
        $param_insert = array();
        $param_values = array();

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';
            array_push($param_insert, $key);
            array_push($param_values, $escaped_value);
        }

        $query_insert = "INSERT INTO distribuidor (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM distribuidor";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if($id!=null){
            $query_insert_gestor_distribuidor = "INSERT INTO gestores_distribuidores (gestor_id,distribuidor_id) VALUES ($usuario_id,$id)";
            $result_gestor_distribuidor = $con->query($query_insert_gestor_distribuidor);
        }

        if ($result) {
            $response = array("status" => 201, "status_message" => "distribuidor agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar distribuidor .", "id" => $id);
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
            array_push($param_update, 'distribuidor.'.$key . "=" . $escaped_value);
        }
        $id = sanitizeInput($_GET['param']);
        $query_update = "UPDATE distribuidor SET " . implode(", ", $param_update) . " WHERE distribuidor.id = " . $id;

        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "distribuidor  actualizado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar distribuidor .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM distribuidor WHERE distribuidor.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "distribuidor  eliminado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró el distribuidor  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar distribuidor .", "id" => $id);
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



