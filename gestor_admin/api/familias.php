<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                            familias.id,
                            familias.numero,
                            familias.nombre,
                            familias.empresa_id
                          FROM
                            familias
                          LEFT JOIN distribuidores_empresas ON familias.empresa_id = distribuidores_empresas.empresa_id";

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
            array_push($query_param, "familias.id=$id");
        }
        if (isset($_GET['numero'])) {
            $codigo = sanitizeInput($_GET['numero']);
            array_push($query_param, "numero='$codigo'");
        }
        if (isset($_GET['nombre'])) {
            $descripcion = sanitizeInput($_GET['nombre']);
            array_push($query_param, "nombre like '%$descripcion%'");
        }
        if (isset($_GET['distribuidor_id'])) {
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
            array_push($query_param, "distribuidor_id=$distribuidor_id");
        }
        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
            array_push($query_param, "familias.empresa_id=$empresa_id");
        }

        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" AND ", $query_param) . ") AND distribuidores_empresas.distribuidor_id = $distribuidor_id";
        } else {
            $query_product = $query_product . " WHERE distribuidores_empresas.distribuidor_id = $distribuidor_id";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM familias  LEFT JOIN distribuidores_empresas ON familias.empresa_id = distribuidores_empresas.empresa_id WHERE distribuidores_empresas.distribuidor_id = $distribuidor_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY  familias." . $order_by . "  " . $sort_order . " OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
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

            $familia['id'] = $row['id'];
            $familia['numero'] = $row['numero'];
            $familia['nombre'] = $row['nombre'];

            array_push($response, $familia);
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


        $query_insert = "INSERT INTO familias (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM familias";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Familia agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar familias.", "id" => $id);
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
        $query_update = "UPDATE familias SET " . implode(", ", $param_update) . " WHERE id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Familia actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar familias.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM familias WHERE id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Familia eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Familia para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar familias.", "id" => $id);
        }
    }

    header('Content-Type: application/json');

    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $response = array("status" => 500, "status_message" => "Error en el servidor.", "descripcion" => "Codigo de error {$th->getCode()} ");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
