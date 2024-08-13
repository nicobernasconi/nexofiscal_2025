<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                        clientes.id,
                        clientes.nro_cliente,
                        clientes.nombre,
                        clientes.tipo_iva_id,
                        clientes.cuit,
                        clientes.tipo_documento_id,
                        clientes.numero_documento,
                        clientes.direccion_comercial,
                        clientes.direccion_entrega,
                        clientes.localidad_id,
                        clientes.telefono,
                        clientes.celular,
                        clientes.email,
                        clientes.contacto,
                        clientes.telefono_contacto,
                        clientes.categoria_id,
                        clientes.vendedor_id,
                        clientes.porcentaje_descuento,
                        clientes.limite_credito,
                        clientes.saldo_inicial,
                        clientes.saldo_actual,
                        clientes.fecha_ultima_compra,
                        clientes.fecha_ultimo_pago,
                        clientes.percepcion_iibb,
                        clientes.desactivado,
                        tipo_iva.nombre AS tipo_iva_nombre,
                        tipo_iva.descripcion AS tipo_iva_descripcion,
                        tipo_iva.porcentaje AS tipo_iva_porcentaje,
                        tipo_iva.letra_factura AS tipo_iva_letra_factura,

                        tipo_documento.nombre AS tipo_documento_nombre,
                        localidad.nombre AS localidad_nombre,
                        localidad.provincia_id AS localidad_provincia_id,
                        localidad.codigo_postal AS localidad_codigo_postal,
                        categoria.nombre AS categoria_nombre,
                        categoria.se_imprime AS categoria_se_imprime,
                        vendedores.nombre AS vendedor_nombre,
                        vendedores.direccion AS vendedor_direccion,
                        vendedores.telefono AS vendedor_telefono,
                        vendedores.porcentaje_comision AS vendedor_porcentaje_comision,
                        vendedores.fecha_ingreso AS vendedor_fecha_ingreso

                    FROM
                        clientes
                    LEFT JOIN tipo_iva ON clientes.tipo_iva_id = tipo_iva.id
                    LEFT JOIN tipo_documento ON clientes.tipo_documento_id = tipo_documento.id
                    LEFT JOIN localidad ON clientes.localidad_id = localidad.id
                    LEFT JOIN categoria ON clientes.categoria_id = categoria.id
                    LEFT JOIN vendedores ON clientes.vendedor_id = vendedores.id
                    ";

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
            array_push($query_param, "clientes.id=$id");
        }
        if (isset($_GET['nro_cliente'])) {
            $nro_cliente = sanitizeInput($_GET['nro_cliente']);
            array_push($query_param, "clientes.nro_cliente = '$nro_cliente'");
        }

        if (isset($_GET['nombre'])) {
            $nombre = sanitizeInput($_GET['nombre']);
            array_push($query_param, "clientes.nombre like '%$nombre%'");
        }

        if (isset($_GET['tipo_iva_id'])) {
            $tipo_iva_id = sanitizeInput($_GET['tipo_iva_id']);
            array_push($query_param, "tipo_iva_id='$tipo_iva_id'");
        }

        if (isset($_GET['cuit'])) {
            $cuit = sanitizeInput($_GET['cuit']);
            array_push($query_param, "cuit='$cuit'");
        }

        if (isset($_GET['tipo_documento_id'])) {
            $tipo_documento_id = sanitizeInput($_GET['tipo_documento_id']);
            array_push($query_param, "tipo_documento_id='$tipo_documento_id'");
        }

        if (isset($_GET['numero_documento'])) {
            $numero_documento = sanitizeInput($_GET['numero_documento']);
            array_push($query_param, "numero_documento='$numero_documento'");
        }

        if (isset($_GET['direccion_comercial'])) {
            $direccion_comercial = sanitizeInput($_GET['direccion_comercial']);
            array_push($query_param, "direccion_comercial='$direccion_comercial'");
        }

        if (isset($_GET['direccion_entrega'])) {
            $direccion_entrega = sanitizeInput($_GET['direccion_entrega']);
            array_push($query_param, "direccion_entrega='$direccion_entrega'");
        }

        if (isset($_GET['localidad_id'])) {
            $localidad_id = sanitizeInput($_GET['localidad_id']);
            array_push($query_param, "localidad_id='$localidad_id'");
        }

        if (isset($_GET['telefono'])) {
            $telefono = sanitizeInput($_GET['telefono']);
            array_push($query_param, "telefono='$telefono'");
        }

        if (isset($_GET['celular'])) {
            $celular = sanitizeInput($_GET['celular']);
            array_push($query_param, "celular='$celular'");
        }

        if (isset($_GET['email'])) {
            $email = sanitizeInput($_GET['email']);
            array_push($query_param, "email='$email'");
        }

        if (isset($_GET['contacto'])) {
            $contacto = sanitizeInput($_GET['contacto']);
            array_push($query_param, "contacto='$contacto'");
        }

        if (isset($_GET['telefono_contacto'])) {
            $telefono_contacto = sanitizeInput($_GET['telefono_contacto']);
            array_push($query_param, "telefono_contacto='$telefono_contacto'");
        }

        if (isset($_GET['categoria_id'])) {
            $categoria_id = sanitizeInput($_GET['categoria_id']);
            array_push($query_param, "categoria_id='$categoria_id'");
        }

        if (isset($_GET['vendedor_id'])) {
            $vendedor_id = sanitizeInput($_GET['vendedor_id']);
            array_push($query_param, "vendedor_id='$vendedor_id'");
        }

        if (isset($_GET['porcentaje_descuento'])) {
            $porcentaje_descuento = sanitizeInput($_GET['porcentaje_descuento']);
            array_push($query_param, "porcentaje_descuento='$porcentaje_descuento'");
        }

        if (isset($_GET['limite_credito'])) {
            $limite_credito = sanitizeInput($_GET['limite_credito']);
            array_push($query_param, "limite_credito='$limite_credito'");
        }

        if (isset($_GET['saldo_inicial'])) {
            $saldo_inicial = sanitizeInput($_GET['saldo_inicial']);
            array_push($query_param, "saldo_inicial='$saldo_inicial'");
        }

        if (isset($_GET['saldo_actual'])) {
            $saldo_actual = sanitizeInput($_GET['saldo_actual']);
            array_push($query_param, "saldo_actual='$saldo_actual'");
        }

        if (isset($_GET['fecha_ultima_compra'])) {
            $fecha_ultima_compra = sanitizeInput($_GET['fecha_ultima_compra']);
            array_push($query_param, "fecha_ultima_compra='$fecha_ultima_compra'");
        }

        if (isset($_GET['fecha_ultimo_pago'])) {
            $fecha_ultimo_pago = sanitizeInput($_GET['fecha_ultimo_pago']);
            array_push($query_param, "fecha_ultimo_pago='$fecha_ultimo_pago'");
        }

        if (isset($_GET['percepcion_iibb'])) {
            $percepcion_iibb = sanitizeInput($_GET['percepcion_iibb']);
            array_push($query_param, "percepcion_iibb='$percepcion_iibb'");
        }

        if (isset($_GET['desactivado'])) {
            $desactivado = sanitizeInput($_GET['desactivado']);
            array_push($query_param, "desactivado='$desactivado'");
        }

        if (isset($_GET['clientes_nombre'])) {
            $clientes_nombre = sanitizeInput($_GET['clientes_nombre']);
            array_push($query_param, "clientes_nombre='$clientes_nombre'");
        }

        if (isset($_GET['clientes_descripcion'])) {
            $clientes_descripcion = sanitizeInput($_GET['clientes_descripcion']);
            array_push($query_param, "clientes_descripcion='$clientes_descripcion'");
        }

        if (isset($_GET['clientes_porcentaje'])) {
            $clientes_porcentaje = sanitizeInput($_GET['clientes_porcentaje']);
            array_push($query_param, "clientes_porcentaje='$clientes_porcentaje'");
        }

        if (isset($_GET['tipo_documento_nombre'])) {
            $tipo_documento_nombre = sanitizeInput($_GET['tipo_documento_nombre']);
            array_push($query_param, "tipo_documento_nombre='$tipo_documento_nombre'");
        }

        if (isset($_GET['localidad_nombre'])) {
            $localidad_nombre = sanitizeInput($_GET['localidad_nombre']);
            array_push($query_param, "localidad_nombre='$localidad_nombre'");
        }

        if (isset($_GET['localidad_provincia_id'])) {
            $localidad_provincia_id = sanitizeInput($_GET['localidad_provincia_id']);
            array_push($query_param, "localidad_provincia_id='$localidad_provincia_id'");
        }

        if (isset($_GET['localidad_codigo_postal'])) {
            $localidad_codigo_postal = sanitizeInput($_GET['localidad_codigo_postal']);
            array_push($query_param, "localidad_codigo_postal='$localidad_codigo_postal'");
        }

        if (isset($_GET['categoria_nombre'])) {
            $categoria_nombre = sanitizeInput($_GET['categoria_nombre']);
            array_push($query_param, "categoria_nombre='$categoria_nombre'");
        }

        if (isset($_GET['categoria_se_imprime'])) {
            $categoria_se_imprime = sanitizeInput($_GET['categoria_se_imprime']);
            array_push($query_param, "categoria_se_imprime='$categoria_se_imprime'");
        }

        if (isset($_GET['vendedor_nombre'])) {
            $vendedor_nombre = sanitizeInput($_GET['vendedor_nombre']);
            array_push($query_param, "vendedor_nombre='$vendedor_nombre'");
        }

        if (isset($_GET['vendedor_direccion'])) {
            $vendedor_direccion = sanitizeInput($_GET['vendedor_direccion']);
            array_push($query_param, "vendedor_direccion='$vendedor_direccion'");
        }

        if (isset($_GET['vendedor_telefono'])) {
            $vendedor_telefono = sanitizeInput($_GET['vendedor_telefono']);
            array_push($query_param, "vendedor_telefono='$vendedor_telefono'");
        }

        if (isset($_GET['vendedor_porcentaje_comision'])) {
            $vendedor_porcentaje_comision = sanitizeInput($_GET['vendedor_porcentaje_comision']);
            array_push($query_param, "vendedor_porcentaje_comision='$vendedor_porcentaje_comision'");
        }

        if (isset($_GET['vendedor_fecha_ingreso'])) {
            $vendedor_fecha_ingreso = sanitizeInput($_GET['vendedor_fecha_ingreso']);
            array_push($query_param, "vendedor_fecha_ingreso='$vendedor_fecha_ingreso'");
        }


        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param).") AND clientes.empresa_id = $empresa_id";
        }else{
            $query_product = $query_product . " WHERE clientes.empresa_id = $empresa_id";
        }



        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM clientes WHERE clientes.empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit =$_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY clientes. ".$order_by."  ".$sort_order." OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";



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
        $clientes = array();
        $tipo_documento = array();
        $localidad = array();
        $categoria = array();
        $vendedores = array();
        $tipo_iva = array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tipo_documento = array(
                'id' => $row['tipo_documento_id'],
                'nombre' => $row['tipo_documento_nombre']
            );
            $localidad = array(
                'id' => $row['localidad_id'],
                'nombre' => $row['localidad_nombre'],
                'provincia_id' => $row['localidad_provincia_id'],
                'codigo_postal' => $row['localidad_codigo_postal']
            );
            $categoria = array(
                'id' => $row['categoria_id'],
                'nombre' => $row['categoria_nombre'],
                'se_imprime' => $row['categoria_se_imprime']
            );
            $vendedores = array(
                'id' => $row['vendedor_id'],
                'nombre' => $row['vendedor_nombre'],
                'direccion' => $row['vendedor_direccion'],
                'telefono' => $row['vendedor_telefono'],
                'porcentaje_comision' => $row['vendedor_porcentaje_comision'],
                'fecha_ingreso' => $row['vendedor_fecha_ingreso']
            );

            $tipo_iva = array(
                'id' => $row['tipo_iva_id'],
                'nombre' => $row['tipo_iva_nombre'],
                'porcentaje' => $row['tipo_iva_porcentaje'],
                'letra_factura' => $row['tipo_iva_letra_factura'],
            );

            $clientes = array(
                'id' => $row['id'],
                'nro_cliente' => $row['nro_cliente'],
                'nombre' => $row['nombre'],
                'cuit' => $row['cuit'],
                'tipo_documento' => $tipo_documento,
                'numero_documento' => $row['numero_documento'],
                'direccion_comercial' => $row['direccion_comercial'],
                'direccion_entrega' => $row['direccion_entrega'],
                'localidad' => $localidad,
                'telefono' => $row['telefono'],
                'celular' => $row['celular'],
                'email' => $row['email'],
                'contacto' => $row['contacto'],
                'telefono_contacto' => $row['telefono_contacto'],
                'categoria' => $categoria,
                'vendedores' => $vendedores,
                'porcentaje_descuento' => $row['porcentaje_descuento'],
                'limite_credito' => $row['limite_credito'],
                'saldo_inicial' => $row['saldo_inicial'],
                'saldo_actual' => $row['saldo_actual'],
                'fecha_ultima_compra' => $row['fecha_ultima_compra'],
                'fecha_ultimo_pago' => $row['fecha_ultimo_pago'],
                'percepcion_iibb' => $row['percepcion_iibb'],
                'desactivado' => $row['desactivado'],
                'tipo_iva' => $tipo_iva

            );


            array_push($response, $clientes);
        }
    }
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        // Verificar si ya existe un cliente con el mismo CUIT o número de cliente
        $query_check_duplicate = "SELECT COUNT(*) AS count FROM clientes WHERE cuit = :cuit";
        $stmt_check_duplicate = $con->prepare($query_check_duplicate);
        $stmt_check_duplicate->bindParam(':cuit', $data['cuit']);
        $stmt_check_duplicate->execute();
        $row_duplicate = $stmt_check_duplicate->fetch(PDO::FETCH_ASSOC);
        if ($row_duplicate['count'] > 0) {
            // Si hay duplicados, devuelve un error
            $response = array("status" => 400, "status_message" => "Error: CUIT del cliente duplicado.");
        } else {
            // Si no hay duplicados, procede con la inserción
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

            $query_nro_cliente = "SELECT MAX(nro_cliente) AS nro_cliente FROM clientes";
            $result_nro_cliente = $con->query($query_nro_cliente);
            $row_nro_cliente = $result_nro_cliente->fetch(PDO::FETCH_ASSOC);
            $nro_cliente = $row_nro_cliente['nro_cliente'] + 1 ?? 1;
            array_push($param_insert, 'nro_cliente');
            array_push($param_values, $nro_cliente);


            $query_insert = "INSERT INTO clientes (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
            $result = $con->query($query_insert);

            $query_id = "SELECT MAX(id) AS id FROM clientes";
            $result_id = $con->query($query_id);
            $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
            $id = $row_id['id'] ?? null;
            if ($result) {
                $response = array("status" => 201, "status_message" => "Cliente agregado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 400, "status_message" => "Error al agregar Cliente.", "id" => $id);
            }
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
        $query_update = "UPDATE clientes SET " . implode(", ", $param_update) . " WHERE clientes.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Cliente  actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Cliente .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM clientes WHERE clientes.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Cliente  eliminado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró el Cliente  para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Cliente .", "id" => $id);
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




