<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();
    //GET PRODUCTOS
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    $query_product = "SELECT
                        empresas.id,
                        empresas.nombre,
                        empresas.logo,
                        empresas.direccion,
                        empresas.telefono,
                        empresas.tipo_iva,
                        empresas.cuit,
                        empresas.responsable,
                        empresas.email,
                        empresas.rubro_id,
                        empresas.fecha_inicio_actividades,
                        empresas.descripcion,
                        empresas.razon_social,
                        empresas.inicio_actividad,
                        empresas.iibb,
                        tipo_iva_empresa.nombre as tipo_iva_nombre
                        FROM
                        empresas
                        LEFT JOIN distribuidores_empresas ON empresas.id = distribuidores_empresas.empresa_id
                        LEFT JOIN tipo_iva_empresa ON empresas.tipo_iva = tipo_iva_empresa.id";

    $query_param = array();

if (isset($_GET['order_by'])|| $_GET['sort_order']!=''){
        $order_by = sanitizeInput($_GET['order_by']);
    }else{
        $order_by ='empresas.id';
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
        array_push($query_param, "empresas.id=$id");
    }
    if (isset($_GET['nombre'])) {
        $descripcion = sanitizeInput($_GET['nombre']);
        array_push($query_param, "empresas.nombre like '%$descripcion%'");
    }
    if (isset($_GET['logo'])) {
        $descripcion = sanitizeInput($_GET['logo']);
        array_push($query_param, "empresas.logo like '%$descripcion%'");
    }

    if (isset($_GET['direccion'])) {
        $descripcion = sanitizeInput($_GET['direccion']);
        array_push($query_param, "empresas.direccion like '%$descripcion%'");
    }
    if (isset($_GET['telefono'])) {
        $descripcion = sanitizeInput($_GET['telefono']);
        array_push($query_param, "empresas.telefono like '%$descripcion%'");
    }
    if (isset($_GET['tipo_iva'])) {
        $descripcion = sanitizeInput($_GET['tipo_iva']);
        array_push($query_param, "empresas.tipo_iva like '%$descripcion%'");
    }
    if (isset($_GET['cuit'])) {
        $descripcion = sanitizeInput($_GET['cuit']);
        array_push($query_param, "empresas.cuit like '%$descripcion%'");
    }
    if (isset($_GET['responsable'])) {
        $descripcion = sanitizeInput($_GET['responsable']);
        array_push($query_param, "empresas.responsable like '%$descripcion%'");
    }
    if (isset($_GET['email'])) {
        $descripcion = sanitizeInput($_GET['email']);
        array_push($query_param, "empresas.email like '%$descripcion%'");
    }
    if (isset($_GET['rubro_id'])) {
        $descripcion = sanitizeInput($_GET['rubro_id']);
        array_push($query_param, "empresas.rubro_id like '%$descripcion%'");
    }
    if (isset($_GET['fecha_inicio_actividades'])) {
        $descripcion = sanitizeInput($_GET['fecha_inicio_actividades']);
        array_push($query_param, "empresas.fecha_inicio_actividades like '%$descripcion%'");
    }
    if (isset($_GET['descripcion'])) {
        $descripcion = sanitizeInput($_GET['descripcion']);
        array_push($query_param, "empresas.descripcion like '%$descripcion%'");
    }

    if(isset($_GET['distribuidor_id'])){
        $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
        array_push($query_param, "distribuidores_empresas.distribuidor_id=$distribuidor_id");
    }



    if (count($query_param) > 0) {
        $query_product = $query_product . " WHERE  (" . implode(" AND ", $query_param) . ")";
    }

    //obtener el total de registros
    $query_total = "SELECT COUNT(*) AS total FROM ($query_product) t";
    $result_total = $con->query($query_total);
    $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
    $total = $row_total['total'] ?? 0;
    //limites de la paginacion
    $limit =$_GET['limit'] ?? 255;
    $cont_pages = ceil($total / $limit);
    $offset = $_GET['offset'] ?? 0;

    $query_product = $query_product . " ORDER BY  ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";


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
    $empresas = array();
    $response = array();

    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $empresas = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'logo' => $row['logo'],
            'direccion' => $row['direccion'],
            'telefono' => $row['telefono'],
            'tipo_iva' => $row['tipo_iva'],
            'cuit' => $row['cuit'],
            'responsable' => $row['responsable'],
            'email' => $row['email'],
            'rubro_id' => $row['rubro_id'],
            'fecha_inicio_actividades' => $row['fecha_inicio_actividades'],
            'descripcion' => $row['descripcion'],
            'razon_social' => $row['razon_social'],
            'inicio_actividad' => $row['inicio_actividad'],
            'iibb' => $row['iibb'],
            'tipo_iva_nombre' => $row['tipo_iva_nombre']

        );


            
       
        array_push($response, $empresas);
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

        $query_insert = "INSERT INTO empresas (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM empresas";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "empresas agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar empresas .", "id" => $id);
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
            array_push($param_update, 'empresas.'.$key . "=" . $escaped_value);
        }
        $id = sanitizeInput($_GET['param']);
        $query_update = "UPDATE empresas SET " . implode(", ", $param_update) . " WHERE empresas.id = " . $id;

        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "empresas  actualizado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar empresas .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM empresas WHERE empresas.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "empresas  eliminado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró el empresas  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar empresas .", "id" => $id);
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



