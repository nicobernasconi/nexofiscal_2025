<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $query_product = "SELECT
                            proveedores.id,
                            proveedores.razon_social,
                            proveedores.direccion,
                            proveedores.localidad_id,
                            proveedores.telefono,
                            proveedores.email,
                            proveedores.tipo_iva_id,
                            proveedores.cuit,
                            proveedores.categoria_id,
                            proveedores.subcategoria_id,
                            proveedores.fecha_ultima_compra,
                            proveedores.fecha_ultimo_pago,
                            proveedores.saldo_actual,
                            localidad.nombre,
                            tipo_iva.nombre,
                            tipo_iva.descripcion,
                            tipo_iva.porcentaje,
                            categoria.nombre,
                            subcategoria.nombre,
                            subcategoria.se_imprime,
                            localidad.codigo_postal,
                            categoria.se_imprime

                        FROM
                            proveedores
                        LEFT JOIN localidad ON proveedores.localidad_id = localidad.id
                        LEFT JOIN tipo_iva ON proveedores.tipo_iva_id = tipo_iva.id
                        LEFT JOIN categoria ON proveedores.categoria_id = categoria.id
                        LEFT JOIN subcategoria ON proveedores.subcategoria_id = subcategoria.id";

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
            array_push($query_param, "proveedores.id=$id");
        }

        if (isset($_GET['razon_social'])) {
            $razon_social = sanitizeInput($_GET['razon_social']);
            array_push($query_param, "proveedores.razon_social LIKE '%$razon_social%'");
        }

        if (isset($_GET['direccion'])) {
            $direccion = sanitizeInput($_GET['direccion']);
            array_push($query_param, "proveedores.direccion LIKE '%$direccion%'");
        }

        if (isset($_GET['localidad_id'])) {
            $localidad_id = sanitizeInput($_GET['localidad_id']);
            array_push($query_param, "proveedores.localidad_id=$localidad_id");
        }

        if (isset($_GET['telefono'])) {
            $telefono = sanitizeInput($_GET['telefono']);
            array_push($query_param, "proveedores.telefono LIKE '%$telefono%'");
        }

        if (isset($_GET['email'])) {
            $email = sanitizeInput($_GET['email']);
            array_push($query_param, "proveedores.email LIKE '%$email%'");
        }

        if (isset($_GET['tipo_iva_id'])) {
            $tipo_iva_id = sanitizeInput($_GET['tipo_iva_id']);
            array_push($query_param, "proveedores.tipo_iva_id=$tipo_iva_id");
        }

        if (isset($_GET['cuit'])) {
            $cuit = sanitizeInput($_GET['cuit']);
            array_push($query_param, "proveedores.cuit LIKE '%$cuit%'");
        }

        if (isset($_GET['categoria_id'])) {
            $categoria_id = sanitizeInput($_GET['categoria_id']);
            array_push($query_param, "proveedores.categoria_id=$categoria_id");
        }

        if (isset($_GET['subcategoria_id'])) {
            $subcategoria_id = sanitizeInput($_GET['subcategoria_id']);
            array_push($query_param, "proveedores.subcategoria_id=$subcategoria_id");
        }

        if (isset($_GET['fecha_ultima_compra'])) {
            $fecha_ultima_compra = sanitizeInput($_GET['fecha_ultima_compra']);
            array_push($query_param, "proveedores.fecha_ultima_compra LIKE '%$fecha_ultima_compra%'");
        }

        if (isset($_GET['fecha_ultimo_pago'])) {
            $fecha_ultimo_pago = sanitizeInput($_GET['fecha_ultimo_pago']);
            array_push($query_param, "proveedores.fecha_ultimo_pago LIKE '%$fecha_ultimo_pago%'");
        }

        if (isset($_GET['saldo_actual'])) {
            $saldo_actual = sanitizeInput($_GET['saldo_actual']);
            array_push($query_param, "proveedores.saldo_actual LIKE '%$saldo_actual%'");
        }

        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param).") AND proveedores.empresa_id = $empresa_id";
        }else{
            $query_product = $query_product . " WHERE proveedores.empresa_id = $empresa_id";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM proveedores WHERE proveedores.empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit =$_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY proveedores. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

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
        $proveedores = array();
        $tipo_iva = array();
        $localidad = array();
        $categoria = array();
        $subcategoria = array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tipo_iva = array(
                "id" => $row['tipo_iva_id'],
                "nombre" => $row['nombre'],
                "descripcion" => $row['descripcion'],
                "porcentaje" => $row['porcentaje']
            );

            $localidad = array(
                "id" => $row['localidad_id'],
                "nombre" => $row['nombre'],
                "codigo_postal" => $row['codigo_postal']
            );

            $categoria = array(
                "id" => $row['categoria_id'],
                "nombre" => $row['nombre'],
                "se_imprime" => $row['se_imprime']
            );

            $subcategoria = array(
                "id" => $row['subcategoria_id'],
                "nombre" => $row['nombre'],
                "se_imprime" => $row['se_imprime']
            );

            $proveedores = array(
                "id" => $row['id'],
                "razon_social" => $row['razon_social'],
                "direccion" => $row['direccion'],
                "localidad" => $localidad,
                "telefono" => $row['telefono'],
                "email" => $row['email'],
                "tipo_iva" => $tipo_iva,
                "cuit" => $row['cuit'],
                "categoria" => $categoria,
                "subcategoria" => $subcategoria,
                "fecha_ultima_compra" => $row['fecha_ultima_compra'],
                "fecha_ultimo_pago" => $row['fecha_ultimo_pago'],
                "saldo_actual" => $row['saldo_actual']
            );

            array_push($response, $proveedores);
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

        $query_insert = "INSERT INTO proveedores (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM proveedores";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Proveedor agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Proveedor .", "id" => $id);
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
        $query_update = "UPDATE proveedores SET " . implode(", ", $param_update) . " WHERE proveedores.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Proveedor  actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Proveedor .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM proveedores WHERE proveedores.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Proveedor  eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Proveedor  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Proveedor .", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}



?>