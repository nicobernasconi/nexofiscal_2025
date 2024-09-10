<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        //GET PRODUCTOS

        $query_product = "SELECT
                        categoria_iibb.id,
                        categoria_iibb.nombre,
                        categoria_iibb.descripcion,
                        categoria_iibb.alicuota,
                        categoria_iibb.limite_facturacion,
                        categoria_iibb.obligaciones_formales,
                        categoria_iibb.fecha_vigencia,
                        categoria_iibb.observaciones

                    FROM
                        categoria_iibb";

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
            array_push($query_param, "categoria_iibb.id=$id");
        }
        if (isset($_GET['nombre'])) {
            $descripcion = sanitizeInput($_GET['nombre']);
            array_push($query_param, "nombre like '%$descripcion%'");
        }

        if (isset($_GET['descripcion'])) {
            $descripcion = sanitizeInput($_GET['descripcion']);
            array_push($query_param, "descripcion like '%$descripcion%'");
        }

        if (isset($_GET['alicuota'])) {
            $alicuota = sanitizeInput($_GET['alicuota']);
            array_push($query_param, "alicuota='$alicuota'");
        }

        if (isset($_GET['limite_facturacion'])) {
            $limite_facturacion = sanitizeInput($_GET['limite_facturacion']);
            array_push($query_param, "limite_facturacion='$limite_facturacion'");
        }

        if (isset($_GET['obligaciones_formales'])) {
            $obligaciones_formales = sanitizeInput($_GET['obligaciones_formales']);
            array_push($query_param, "obligaciones_formales='$obligaciones_formales'");
        }

        if (isset($_GET['fecha_vigencia'])) {
            $fecha_vigencia = sanitizeInput($_GET['fecha_vigencia']);
            array_push($query_param, "fecha_vigencia='$fecha_vigencia'");
        }

        if (isset($_GET['observaciones'])) {
            $observaciones = sanitizeInput($_GET['observaciones']);
            array_push($query_param, "observaciones='$observaciones'");
        }

        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param) . ") AND categoria_iibb.empresa_id = $empresa_id";
        } else {
            $query_product = $query_product . " WHERE categoria_iibb.empresa_id = $empresa_id";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM categoria_iibb WHERE categoria_iibb.empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit =$_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY categoria_iibb. " . $order_by . "  " . $sort_order . " LIMIT $limit OFFSET $offset";

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
        $categoria_iibb = array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $categoria_iibb = array(
                'id' => $row['id'],
                'nombre' => $row['nombre'],
                'descripcion' => $row['descripcion'],
                'alicuota' => $row['alicuota'],
                'limite_facturacion' => $row['limite_facturacion'],
                'obligaciones_formales' => $row['obligaciones_formales'],
                'fecha_vigencia' => $row['fecha_vigencia'],
                'observaciones' => $row['observaciones']
            );


            array_push($response, $categoria_iibb);
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

        $query_insert = "INSERT INTO categoria_iibb (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM categoria_iibb";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Categoria IIBB agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar categoria IIBB.", "id" => $id);
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
        $query_update = "UPDATE categoria_iibb SET " . implode(", ", $param_update) . " WHERE categoria_iibb.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Categoria IIBB actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar categoria IIBB.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM categoria_iibb WHERE categoria_iibb.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Categoria IIBB eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la categoria IIBB para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar categoria IIBB.", "id" => $id);
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
