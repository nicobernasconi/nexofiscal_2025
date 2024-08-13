<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                        vendedores.id,
                        vendedores.nombre,
                        vendedores.direccion,
                        vendedores.telefono,
                        vendedores.porcentaje_comision,
                        vendedores.fecha_ingreso,
                        distribuidores_empresas.distribuidor_id
                    FROM
                        vendedores
                        LEFT JOIN distribuidores_empresas ON vendedores.empresa_id = distribuidores_empresas.empresa_id";

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
            array_push($query_param, "vendedores.id=$id");
        }
        if (isset($_GET['nombre'])) {
            $descripcion = sanitizeInput($_GET['nombre']);
            array_push($query_param, "nombre like '%$descripcion%'");
        }

        if (isset($_GET['descripcion'])) {
            $descripcion = sanitizeInput($_GET['descripcion']);
            array_push($query_param, "descripcion like '%$descripcion%'");
        }

        if (isset($_GET['porcentaje'])) {
            $porcentaje = sanitizeInput($_GET['porcentaje']);
            array_push($query_param, "porcentaje='$porcentaje'");
        }

        if (isset($_GET['fecha_ingreso'])) {
            $fecha_ingreso = sanitizeInput($_GET['fecha_ingreso']);
            array_push($query_param, "fecha_ingreso='$fecha_ingreso'");
        }

        if (isset($_GET['distribuidor_id'])) {
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
            array_push($query_param, "distribuidores_empresas.distribuidor_id='$distribuidor_id'");
        }


        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" AND ", $query_param).")";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM vendedores LEFT JOIN distribuidores_empresas ON vendedores.empresa_id = distribuidores_empresas.empresa_id WHERE distribuidores_empresas.distribuidor_id='$distribuidor_id'";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit =$_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY vendedores. ".$order_by."  ".$sort_order." OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";

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
        $vendedores = array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $vendedores = array(
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'direccion' => $row['direccion'],
                'telefono' => $row['telefono'],
                'porcentaje_comision' => $row['porcentaje_comision'],
                'fecha_ingreso' => $row['fecha_ingreso']
            );



            array_push($response, $vendedores);
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

        $query_insert = "INSERT INTO vendedores (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM vendedores";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Vendedor agregado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Vendedor .", "id" => $id);
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
        $query_update = "UPDATE vendedores SET " . implode(", ", $param_update) . " WHERE vendedores.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Vendedor  actualizado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Vendedor .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM vendedores WHERE vendedores.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Vendedor  eliminado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró el Vendedor  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Vendedor .", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $response = array("status" => 500, "status_message" => "Error en el servidor.", "descripcion" => "Codigo de error {$th->getCode()} ,{$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
