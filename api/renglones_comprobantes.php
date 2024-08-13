<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();

    if (isset($_GET['comprobante_id'])) {
$comprobante_id = sanitizeInput($_GET['comprobante_id']);
        //GET PRODUCTOS
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {


            $query_product = "SELECT
                            renglones_comprobantes.id AS id,
                            renglones_comprobantes.comprobante_id AS comprobante_id,
                            renglones_comprobantes.producto_id AS producto_id,
                            renglones_comprobantes.descripcion AS descripcion,
                            renglones_comprobantes.cantidad AS cantidad,
                            renglones_comprobantes.precio_unitario AS precio_unitario,
                            renglones_comprobantes.descuento AS descuento,
                            renglones_comprobantes.total_linea AS total_linea,
                            renglones_comprobantes.tasa_iva AS tasa_iva,
                            productos.codigo AS  producto_codigo,
                            productos.descripcion AS  producto_descripcion,
                            productos.descripcion_ampliada AS  producto_descripcion_ampliada,
                            productos.familia_id AS  producto_familia_id,
                            productos.subfamilia_id AS  producto_subfamilia_id,
                            productos.agrupacion_id AS  producto_agrupacion_id,
                            productos.marca_id AS  producto_marca_id,
                            productos.codigo_barra AS  producto_codigo_barra,
                            productos.proveedor_id AS  producto_proveedor_id,
                            productos.fecha_alta AS  producto_fecha_alta,
                            productos.fecha_actualizacion AS  producto_fecha_actualizacion,
                            productos.articulo_activado AS  producto_articulo_activado,
                            productos.tipo_id AS  producto_tipo_id,
                            productos.producto_balanza AS  producto_producto_balanza,
                            productos.precio1 AS  producto_precio1,
                            productos.moneda_id AS  producto_moneda_id,
                            productos.tasa_iva AS  producto_tasa_iva,
                            productos.incluye_iva AS  producto_incluye_iva,
                            productos.impuesto_interno AS  producto_impuesto_interno

                        FROM
                            renglones_comprobantes
                        LEFT JOIN productos ON renglones_comprobantes.producto_id = productos.id";


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
            if (isset($_GET['renglon_id'])) {
                $renglon_id = sanitizeInput($_GET['renglon_id']);
                if ($renglon_id != '') {
                    array_push($query_param, "renglones_comprobantes.id=$renglon_id");
                }
            }
            if (isset($_GET['comprobante_id'])) {
                $comprobante_id = sanitizeInput($_GET['comprobante_id']);
                array_push($query_param, "renglones_comprobantes.comprobante_id=$comprobante_id");
            }
            if (isset($_GET['producto_id'])) {
                $producto_id = sanitizeInput($_GET['producto_id']);
                array_push($query_param, "renglones_comprobantes.producto_id=$producto_id");
            }
            if (isset($_GET['descripcion'])) {
                $descripcion = sanitizeInput($_GET['descripcion']);
                array_push($query_param, "renglones_comprobantes.descripcion LIKE '%$descripcion%'");
            }
            if (isset($_GET['cantidad'])) {
                $cantidad = sanitizeInput($_GET['cantidad']);
                array_push($query_param, "renglones_comprobantes.cantidad=$cantidad");
            }
            if (isset($_GET['precio_unitario'])) {
                $precio_unitario = sanitizeInput($_GET['precio_unitario']);
                array_push($query_param, "renglones_comprobantes.precio_unitario=$precio_unitario");
            }
            if (isset($_GET['descuento'])) {
                $descuento = sanitizeInput($_GET['descuento']);
                array_push($query_param, "renglones_comprobantes.descuento=$descuento");
            }

            if (isset($_GET['total_linea'])) {
                $total_linea = sanitizeInput($_GET['total_linea']);
                array_push($query_param, "renglones_comprobantes.total_linea=$total_linea");
            }
            if (count($query_param) > 0) {
                $query_product = $query_product . " WHERE " . implode(" OR ", $query_param);
            }


            //obtener el total de registros
            $query_total = "SELECT COUNT(*) AS total FROM renglones_comprobantes";
            $result_total = $con->query($query_total);
            $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
            $total = $row_total['total'] ?? 0;
            //limites de la paginacion
            $limit = $_GET['limit'] ?? 255;
            $cont_pages = ceil($total / $limit);
            $offset = $_GET['offset'] ?? 0;

            $query_product = $query_product . " ORDER BY renglones_comprobantes. " . $order_by . "  " . $sort_order . " OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";
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
            $productos = array();
            $rengloones_comprobantes = array();
            $response = array();


            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                $productos = array(
                    "id" => $row['producto_id'],
                    "codigo" => $row['producto_codigo'],
                    "descripcion" => $row['producto_descripcion'],
                    "descripcion_ampliada" => $row['producto_descripcion_ampliada'],
                    "familia_id" => $row['producto_familia_id'],
                    "subfamilia_id" => $row['producto_subfamilia_id'],
                    "agrupacion_id" => $row['producto_agrupacion_id'],
                    "marca_id" => $row['producto_marca_id'],
                    "codigo_barra" => $row['producto_codigo_barra'],
                    "proveedor_id" => $row['producto_proveedor_id'],
                    "fecha_alta" => $row['producto_fecha_alta'],
                    "fecha_actualizacion" => $row['producto_fecha_actualizacion'],
                    "articulo_activado" => $row['producto_articulo_activado'],
                    "tipo_id" => $row['producto_tipo_id'],
                    "producto_balanza" => $row['producto_producto_balanza'],
                    "precio1" => $row['producto_precio1'],
                    "moneda_id" => $row['producto_moneda_id'],
                    "tasa_iva" => $row['producto_tasa_iva'],
                    "incluye_iva" => $row['producto_incluye_iva'],
                    "impuesto_interno" => $row['producto_impuesto_interno'],

                );

                $renglones_comprobantes = array(
                    "id" => $row['id'],
                    "comprobante_id" => $row['comprobante_id'],
                    "producto_id" => $row['producto_id'],
                    "descripcion" => $row['descripcion'],
                    "cantidad" => $row['cantidad'],
                    "precio_unitario" => $row['precio_unitario'],
                    "tasa_iva" => $row['tasa_iva'],
                    "descuento" => $row['descuento'],
                    "total_linea" => $row['total_linea'],
                    "producto" => $productos,
                    
                );
                array_push($response, $renglones_comprobantes);
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
            $comprobante_id = sanitizeInput($_GET['comprobante_id']);
            array_push($param_insert, "comprobante_id");
            array_push($param_values, $comprobante_id);


            $query_insert = "INSERT INTO renglones_comprobantes (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
            $result = $con->query($query_insert);

            $query_id = "SELECT MAX(id) AS id FROM renglones_comprobantes";
            $result_id = $con->query($query_id);
            $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
            $id = $row_id['id'] ?? null;
            if ($result) {
                $response = array("status" => 201, "status_message" => "Renglon comprobantes agregada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 400, "status_message" => "Error al agregar Renglon comprobantes .", "id" => $id);
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
            $id = sanitizeInput($_GET['renglon_id']);
            $comprobante_id = sanitizeInput($_GET['comprobante_id']);
            //obtener el total_linea del renglon
            $query_total_linea = "SELECT total_linea FROM renglones_comprobantes WHERE renglones_comprobantes.id = " . $id . "";
            $result_total_linea = $con->query($query_total_linea);
            $row_total_linea = $result_total_linea->fetch(PDO::FETCH_ASSOC);
            $query_update = "UPDATE renglones_comprobantes SET " . implode(", ", $param_update) . " WHERE renglones_comprobantes.id = " . $id;
            $result = $con->query($query_update);
            if ($result) {
                //si se total_linea lo sumo al campo total de la tabla comprobantes
                if (isset($data['total_linea'])) {
                    $query_total = "UPDATE comprobantes SET total = total - " . $row_total_linea['total_linea'] . " + " . $data['total_linea'] . " WHERE comprobantes.id = " . $comprobante_id . "";
                    $result_total = $con->query($query_total);
                }
                $response = array("status" => 201, "status_message" => "Renglon comprobantes  actualizada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 400, "status_message" => "Error al actualizar Renglon comprobantes .", "id" => $id);
            }
        }
        //DELETE PRODUCTOS
        if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            $id = sanitizeInput($_GET['renglon_id']);
            $comprobante_id = sanitizeInput($_GET['comprobante_id']);
            //obtener el total_linea del renglon
            $query_total_linea = "SELECT total_linea FROM renglones_comprobantes WHERE renglones_comprobantes.id = " . $id . "";
            $result_total_linea = $con->query($query_total_linea);
            $row_total_linea = $result_total_linea->fetch(PDO::FETCH_ASSOC);

            // Utilizar una consulta preparada para evitar inyección SQL
            $query_delete = "DELETE FROM renglones_comprobantes WHERE renglones_comprobantes.id = :id";
            $stmt = $con->prepare($query_delete);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Verificar si se eliminó algún registro
                if ($stmt->rowCount() > 0) {
                    //si se total_linea lo resto al campo total de la tabla comprobantes
                    $query_total = "UPDATE comprobantes SET total = total - " . $row_total_linea['total_linea'] . " WHERE comprobantes.id = " . $comprobante_id . "";
                    $response = array("status" => 201, "status_message" => "Renglon comprobantes  eliminada correctamente.", "id" => $id);
                } else {
                    $response = array("status" => 404, "status_message" => "No se encontró la Renglon comprobantes  para eliminar.", "id" => $id);
                }
            } else {
                $response = array("status" => 400, "status_message" => "Error al eliminar Renglon comprobantes .", "id" => $id);
            }
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    } else {
        $response = array("status" => 400, "status_message" => "No se puede obtener el Renglon comprobantes .", "id" => $comprobante_id);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
} catch (PDOException $th) {
    $response = array("status" => 500, "status_message" => "Error en el servidor.", "descripcion" => "{$th->getCode()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
