<?php

use Spipu\Html2Pdf\Tag\Html\Em;

include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                            usuarios.id,
                            usuarios.nombre_usuario,
                            usuarios.`password`,
                            usuarios.nombre_completo,
                            usuarios.rol_id,
                            usuarios.activo,
                            usuarios.empresa_id,
                            usuarios.venta_rapida,
                            usuarios.imprimir,
                            usuarios.lista_precios,
                            usuarios.tipo_comprobante_imprimir,
                            roles.nombre AS rol_nombre,
                            roles.descripcion AS rol_descripcion,
                            usuarios.sucursal_id,
                            sucursales.nombre AS sucursal_nombre,
                            sucursales.direccion AS sucursal_direccion,
                            (SELECT vendedores.id FROM vendedores WHERE vendedores.sucursal_id = usuarios.sucursal_id LIMIT 1) AS vendedor_id,
                            (SELECT vendedores.nombre FROM vendedores WHERE vendedores.sucursal_id = usuarios.sucursal_id LIMIT 1) AS vendedor_nombre,
                            distribuidores_empresas.distribuidor_id
                        FROM
                            usuarios
                        LEFT JOIN roles ON usuarios.rol_id = roles.id
                        LEFT JOIN sucursales ON usuarios.sucursal_id = sucursales.id
                        LEFT JOIN distribuidores_empresas ON usuarios.empresa_id = distribuidores_empresas.empresa_id
                      ";

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
            array_push($query_param, "usuarios.id=$id");
        }
        if (isset($_GET['nombre_usuario'])) {
            $nombre_usuario = sanitizeInput($_GET['nombre_usuario']);
            array_push($query_param, "nombre_usuario like '%$nombre_usuario%'");
        }

        if (isset($_GET['nombre_completo'])) {
            $descripcion = sanitizeInput($_GET['nombre_completo']);
            array_push($query_param, "nombre_completo like '%$descripcion%'");
        }

        if (isset($_GET['rol_id'])) {
            $descripcion = sanitizeInput($_GET['rol_id']);
            array_push($query_param, "rol_id = $descripcion");
        }

        if (isset($_GET['activo'])) {
            $descripcion = sanitizeInput($_GET['activo']);
            array_push($query_param, "activo = $descripcion");
        }
        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
        }
        
        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);

        }

        if (isset($_GET['distribuidor_id'])) {
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
  
        }

        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param) . ") and distribuidores_empresas.distribuidor_id = $distribuidor_id  and distribuidores_empresas.empresa_id = $empresa_id and sucursal_id = $sucursal_id ";
        } else {
            $query_product = $query_product . " WHERE  distribuidores_empresas.distribuidor_id = $distribuidor_id  and distribuidores_empresas.empresa_id = $empresa_id and sucursal_id = $sucursal_id ";;
        }


        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM usuarios  LEFT JOIN distribuidores_empresas ON usuarios.empresa_id = distribuidores_empresas.empresa_id WHERE distribuidores_empresas.distribuidor_id = $distribuidor_id  and distribuidores_empresas.empresa_id = $empresa_id and sucursal_id = $sucursal_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY usuarios.id LIMIT $limit OFFSET $offset";
        

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
        $usuarios = array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $rol = array();
            $vendedor = array();
            $usuario = array();
            $sucursal = array();    
            $rol['id'] = $row['rol_id'];
            $rol['nombre'] = $row['rol_nombre'];
            $rol['descripcion'] = $row['rol_descripcion'];
            $vendedor['id'] = $row['vendedor_id'];
            $vendedor['nombre'] = $row['vendedor_nombre'];
            $sucursal['id'] = $row['sucursal_id'];
            $sucursal['nombre'] = $row['sucursal_nombre'];
            $sucursal['direccion'] = $row['sucursal_direccion'];


            $usuario = array(
                'id' => $row['id'],
                'nombre_usuario' => $row['nombre_usuario'],
                'password' => $row['password'],
                'nombre_completo' => $row['nombre_completo'],
                'rol_id' => $row['rol_id'],
                'activo' => $row['activo'],
                'empresa_id' => $row['empresa_id'],
                'rol' => $rol,
                'sucursal' => $sucursal,
                'vendedor' => $vendedor,
                'venta_rapida' => $row['venta_rapida'],
                'imprimir' => $row['imprimir'],
                'lista_precios' => $row['lista_precios'],
                'tipo_comprobante_imprimir' => $row['tipo_comprobante_imprimir']

                

            );
            unset($usuario['rol_id']);
            unset($usuario['rol_nombre']);
            unset($usuario['rol_descripcion']);
            array_push($response, $usuario);
        }
    }
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        //cambiar el password a un md5
        $data['password'] = md5($data['password']);
        unset($data['id']);

        $param_insert = array();
        $param_values = array();

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';

            array_push($param_insert, $key);
            array_push($param_values, $escaped_value);
        }


        $query_insert = "INSERT INTO usuarios (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT MAX(id) AS id FROM usuarios";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "usuarios agregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar usuarios.", "id" => $id);
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
        $query_update = "UPDATE usuarios SET " . implode(", ", $param_update) . " WHERE usuarios.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "usuarios actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar usuarios.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM usuarios WHERE usuarios.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "usuarios eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la usuarios para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar usuarios.", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg} {$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
