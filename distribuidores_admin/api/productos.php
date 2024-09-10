<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                            p.id,
                            p. codigo,
                            p.descripcion ,
                            p.descripcion_ampliada,
                            p.stock,
 
                            p.marca_id,
                            p.codigo_barra,
                            p.proveedor_id,
                            p.fecha_alta,
                            p.fecha_actualizacion,
                            p.articulo_activado,
                            p.producto_balanza,
                            p.precio1,
                            p.precio2,
                            p.precio3,
                            p.tasa_iva_id,
                            p.incluye_iva,
                            p.impuesto_interno,
                            p.unidad_id,
                            a.numero AS agrupacion_numero,
                            a.id AS agrupacion_id,
                            a.nombre AS agrupacion_nombre,
                            a.color AS agrupacion_color,
                            f.id AS familia_id,
                            f.numero AS familia_numero,
                            f.nombre AS familia_nombre,
                            sf.id AS subfamilia_id,
                            sf.numero AS subfamilia_numero,
                            sf.descripcion AS subfamilia_descripcion,
                            m.id AS moneda_id,
                            m.simbolo AS moneda_simbolo,
                            m.nombre AS moneda_nombre,
                            m.cotizacion AS moneda_cotizacion,
                            t.id AS tipo_id,
                            t.numero AS tipo_numero,
                            t.nombre AS tipo_nombre,
                            pr.id AS proveedores_id,
                            pr.razon_social AS proveedores_razon_social,
                            pr.direccion AS proveedores_direccion,
                            pr.localidad_id AS proveedores_localidad_id,
                            pr.telefono AS proveedores_telefono,
                            pr.email AS proveedores_email,
                            pr.tipo_iva_id AS proveedores_iva_id,
                            pr.cuit AS proveedor_cuit,
                            pr.categoria_id AS proveedores_categoria_id,
                            pr.subcategoria_id AS proveedores_subcategoria_id,
                            pr.fecha_ultima_compra AS proveedores_fecha_ultima_compra,
                            pr.fecha_ultimo_pago AS proveedores_ultimo_pago,
                            pr.saldo_actual AS proveedores_saldo_actual,
                            ti.nombre AS tipo_iva_nombre,
                            ti.descripcion AS tipo_iva_descripcion,
                            ti.porcentaje AS tipo_iva_porcentaje,
                            l.nombre AS localidad_nombre,
                            l.codigo_postal AS localidad_codigo_postal,
                            p2.nombre AS provincia_nombre,
                            p2.id AS provincia_id,
                            p3.nombre AS pais_nombre,
                            p3.id AS pais_id,
                            l.id AS localidad_id,
                            u.nombre AS unidad_nombre,
                            u.simbolo AS unidad_simbolo,
                            p.precio_costo,
                            p.fraccionado,
                            p.rg5329_23,
                            p.activo,
                            p.texto_panel,
                            p.iibb,
                            p.codigo_barra2,
                            p.oferta,
                            p.empresa_id,
                            p.margen_ganancia,
                            p.favorito,
                            p.tipo_impuesto_interno,
                            ti2.nombre AS tasa_iva_nombre,
                            ti2.tasa AS tasa_iva_tasa,
                             (
                                SELECT JSON_OBJECTAGG(ps_inner.sucursal_id, ps_inner.stock_actual) 
                                FROM productos_stock ps_inner 
                                WHERE ps_inner.producto_id = p.id
                            ) AS stock_actual
                        FROM
                            productos p
                        LEFT JOIN 
                            subfamilias sf ON p.subfamilia_id = sf.id
                        LEFT JOIN 
                            familias f ON p.familia_id = f.id
                        LEFT JOIN 
                            agrupacion a ON p.agrupacion_id = a.id
                        LEFT JOIN 
                            proveedores pr ON p.proveedor_id = pr.id
                        LEFT JOIN 
                            moneda m ON p.moneda_id = m.id
                        LEFT JOIN 
                            tipo t ON p.tipo_id = t.id
                        LEFT JOIN 
                            tipo_iva ti ON pr.tipo_iva_id = ti.id
                        LEFT JOIN 
                            localidad l ON pr.localidad_id = l.id
                        LEFT JOIN 
                            provincias p2 ON l.provincia_id = p2.id
                        LEFT JOIN 
                            pais p3 ON p2.pais_id = p3.id
                        LEFT JOIN 
                            unidad u ON p.unidad_id = u.id
                        LEFT JOIN 
                            codigos_barras cb ON p.empresa_id = cb.empresa_id
                        LEFT JOIN 
                            tasa_iva ti2 ON p.tasa_iva_id = ti2.id  ";

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
            array_push($query_param, "p.id=$id");
        }
        if (isset($_GET['codigo'])) {
            $codigo = sanitizeInput($_GET['codigo']);
            array_push($query_param, "p.codigo = '$codigo'");
        }
        if (isset($_GET['codigo_barra'])) {
            $codigo_barra = sanitizeInput($_GET['codigo_barra']);
            array_push($query_param, "(p.producto_balanza = 1 AND CONCAT(cb.inicio, LPAD(p.codigo_barra, cb.id_long - LENGTH(cb.inicio), '0')) = '$codigo_barra')
                                        OR
                                        (p.producto_balanza != 1 AND p.codigo_barra = '$codigo_barra')");
        }

        if (isset($_GET['descripcion'])) {
            $descripcion = sanitizeInput($_GET['descripcion']);
            array_push($query_param, "p.descripcion like '%$descripcion%'");
        }
        if (isset($_GET['descripcion_ampliada'])) {
            $descripcion_ampliada = sanitizeInput($_GET['descripcion_ampliada']);
            array_push($query_param, "p.descripcion_ampliada like '%$descripcion_ampliada%'");
        }
        if (isset($_GET['familia_id'])) {
            $familia_id = sanitizeInput($_GET['familia_id']);
            array_push($query_param, "p.familia_id=$familia_id");
        }

        if (isset($_GET['subfamilia_id'])) {
            $subfamilia_id = sanitizeInput($_GET['subfamilia_id']);
            array_push($query_param, "p.subfamilia_id=$subfamilia_id");
        }

        if (isset($_GET['agrupacion_id'])) {
            $agrupacion_id = sanitizeInput($_GET['agrupacion_id']);
            array_push($query_param, "p.agrupacion_id=$agrupacion_id");
        }

        if (isset($_GET['marca_id'])) {
            $marca_id = sanitizeInput($_GET['marca_id']);
            array_push($query_param, "p.marca_id=$marca_id");
        }
        if (isset($_GET['favorito'])) {
            $favorito = sanitizeInput($_GET['favorito']);
            array_push($query_param, "p.favorito=$favorito");
        }



        if (isset($_GET['proveedor_id'])) {
            $proveedor_id = sanitizeInput($_GET['proveedor_id']);
            array_push($query_param, "p.proveedor_id=$proveedor_id");
        }

        if (isset($_GET['fecha_alta'])) {
            $fecha_alta = sanitizeInput($_GET['fecha_alta']);
            array_push($query_param, "p.fecha_alta=$fecha_alta");
        }

        if (isset($_GET['fecha_actualizacion'])) {
            $fecha_actualizacion = sanitizeInput($_GET['fecha_actualizacion']);
            array_push($query_param, "p.fecha_actualizacion=$fecha_actualizacion");
        }

        if (isset($_GET['articulo_activado'])) {
            $articulo_activado = sanitizeInput($_GET['articulo_activado']);
            array_push($query_param, "p.articulo_activado=$articulo_activado");
        }

        if (isset($_GET['tipo_id'])) {
            $tipo_id = sanitizeInput($_GET['tipo_id']);
            array_push($query_param, "p.tipo_id=$tipo_id");
        }

        if (isset($_GET['producto_balanza'])) {
            $producto_balanza = sanitizeInput($_GET['producto_balanza']);
            array_push($query_param, "p.producto_balanza=$producto_balanza");
        }

        if (isset($_GET['precio1'])) {
            $precio1 = sanitizeInput($_GET['precio1']);
            array_push($query_param, "p.precio1=$precio1");
        }

        if (isset($_GET['moneda_id'])) {
            $moneda_id = sanitizeInput($_GET['moneda_id']);
            array_push($query_param, "p.moneda_id=$moneda_id");
        }

        if (isset($_GET['tasa_iva'])) {
            $tasa_iva = sanitizeInput($_GET['tasa_iva']);
            array_push($query_param, "p.tasa_iva=$tasa_iva");
        }

        if (isset($_GET['incluye_iva'])) {
            $incluye_iva = sanitizeInput($_GET['incluye_iva']);
            array_push($query_param, "p.incluye_iva=$incluye_iva");
        }

        if (isset($_GET['impuesto_interno'])) {
            $impuesto_interno = sanitizeInput($_GET['impuesto_interno']);
            array_push($query_param, "p.impuesto_interno=$impuesto_interno");
        }

        if (isset($_GET['agrupacion_numero'])) {
            $agrupacion_numero = sanitizeInput($_GET['agrupacion_numero']);
            array_push($query_param, "a.agrupacion_numero=$agrupacion_numero");
        }

        if (isset($_GET['agrupacion_nombre'])) {
            $agrupacion_nombre = sanitizeInput($_GET['agrupacion_nombre']);
            array_push($query_param, "a.agrupacion_nombre like '%$agrupacion_nombre%'");
        }

        if (isset($_GET['familia_numero'])) {
            $familia_numero = sanitizeInput($_GET['familia_numero']);
            array_push($query_param, "f.numero=$familia_numero");
        }

        if (isset($_GET['familia_nombre'])) {
            $familia_nombre = sanitizeInput($_GET['familia_nombre']);
            array_push($query_param, "f.nombre like '%$familia_nombre%'");
        }

        if (isset($_GET['subfamilia_numero'])) {
            $subfamilia_numero = sanitizeInput($_GET['subfamilia_numero']);
            array_push($query_param, "sf.numero=$subfamilia_numero");
        }

        if (isset($_GET['subfamilia_descripcion'])) {
            $subfamilia_descripcion = sanitizeInput($_GET['subfamilia_descripcion']);
            array_push($query_param, "sf.descripcion like '%$subfamilia_descripcion%'");
        }

        if (isset($_GET['moneda_simbolo'])) {
            $moneda_simbolo = sanitizeInput($_GET['moneda_simbolo']);
            array_push($query_param, "m.simbolo like '%$moneda_simbolo%'");
        }

        if (isset($_GET['moneda_nombre'])) {
            $moneda_nombre = sanitizeInput($_GET['moneda_nombre']);
            array_push($query_param, "m.nombre like '%$moneda_nombre%'");
        }

        if (isset($_GET['moneda_cotizacion'])) {
            $moneda_cotizacion = sanitizeInput($_GET['moneda_cotizacion']);
            array_push($query_param, "m.cotizacion=$moneda_cotizacion");
        }

        if (isset($_GET['tipo_numero'])) {
            $tipo_numero = sanitizeInput($_GET['tipo_numero']);
            array_push($query_param, "t.numero=$tipo_numero");
        }

        if (isset($_GET['tipo_nombre'])) {
            $tipo_nombre = sanitizeInput($_GET['tipo_nombre']);
            array_push($query_param, "t.nombre like '%$tipo_nombre%'");
        }

        if (isset($_GET['proveedores_razon_social'])) {
            $proveedores_razon_social = sanitizeInput($_GET['proveedores_razon_social']);
            array_push($query_param, "pr.razon_social like '%$proveedores_razon_social%'");
        }

        if (isset($_GET['proveedores_direccion'])) {
            $proveedores_direccion = sanitizeInput($_GET['proveedores_direccion']);
            array_push($query_param, "pr.direccion like '%$proveedores_direccion%'");
        }

        if (isset($_GET['proveedores_localidad_id'])) {
            $proveedores_localidad_id = sanitizeInput($_GET['proveedores_localidad_id']);
            array_push($query_param, "pr.localidad_id=$proveedores_localidad_id");
        }

        if (isset($_GET['proveedores_telefono'])) {
            $proveedores_telefono = sanitizeInput($_GET['proveedores_telefono']);
            array_push($query_param, "pr.telefono like '%$proveedores_telefono%'");
        }

        if (isset($_GET['proveedores_email'])) {
            $proveedores_email = sanitizeInput($_GET['proveedores_email']);
            array_push($query_param, "pr.email like '%$proveedores_email%'");
        }

        if (isset($_GET['empresa_id'])) {
            $empresa_id = sanitizeInput($_GET['empresa_id']);
        }

        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);
        }


        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param) . ")and codigo <>'' AND p.empresa_id=" . $empresa_id;
        } else {
            $query_product = $query_product . " WHERE p.empresa_id= $empresa_id  and codigo <>'' ";
        }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM (" . $query_product . ") t";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY  " .' p.'.$order_by . "  " . $sort_order . " LIMIT $limit OFFSET $offset";


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
        $unidad = array();
        $tasa_iva = array();
        $stock_actual = array();


        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            if ($row['stock_actual'] != null) {

                $stock_actual = json_decode($row['stock_actual'], true);
                //convertir stock actual ejemplo {"1":10,"2":20} a array de objetos [{"sucursal_id":1,"stock_actual":10},{"sucursal_id":2,"stock_actual":20}]
                $stock_actual = array_map(function ($sucursal_id, $stock_actual) {
                    return array('sucursal_id' => $sucursal_id, 'stock_actual' => $stock_actual);
                }, array_keys($stock_actual), $stock_actual);
            } else {
                $stock_actual = array();
            }
            $familia = array(
                "id" => $row['familia_id'] ?? 0,
                "numero" => $row['familia_numero'],
                "nombre" => $row['familia_nombre']
            );
            $subfamilia = array(
                "id" => $row['subfamilia_id'] ?? 0,
                "numero" => $row['subfamilia_numero'],
                "descripcion" => $row['subfamilia_descripcion']
            );
            $agrupacion = array(
                "id" => $row['agrupacion_id'] ?? 0,
                "numero" => $row['agrupacion_numero'],
                "nombre" => $row['agrupacion_nombre'],
                "color" => $row['agrupacion_color'] ?? '000000',
                "icono" => $row['agrupacion_icono'] ?? 'fas fa-boxes'

            );
            $proveedor = array(
                "id" => $row['proveedores_id'] ?? 0,
                "razon_social" => $row['proveedores_razon_social'],
                "direccion" => $row['proveedores_direccion'],
                "localidad_id" => $row['proveedores_localidad_id'],
                "telefono" => $row['proveedores_telefono'],
                "email" => $row['proveedores_email'],
                "iva_id" => $row['proveedores_iva_id'],
                "cuit" => $row['proveedor_cuit'],
                "categoria_id" => $row['proveedores_categoria_id'],
                "subcategoria_id" => $row['proveedores_subcategoria_id'],
                "fecha_ultima_compra" => $row['proveedores_fecha_ultima_compra'],
                "fecha_ultimo_pago" => $row['proveedores_ultimo_pago'],
                "saldo_actual" => $row['proveedores_saldo_actual']
            );
            $moneda = array(
                "id" => $row['moneda_id'] ?? 0,
                "simbolo" => $row['moneda_simbolo'],
                "nombre" => $row['moneda_nombre'],
                "cotizacion" => $row['moneda_cotizacion']
            );
            $tipo = array(
                "id" => $row['tipo_id'] ?? 0,
                "numero" => $row['tipo_numero'],
                "nombre" => $row['tipo_nombre']
            );

            $unidad = array(
                "id" => $row['unidad_id'] ?? 0,
                "nombre" => $row['unidad_nombre'],
                "simbolo" => $row['unidad_simbolo']
            );
            $tasa_iva = array(
                "id" => $row['tasa_iva_id'] ?? 0,
                "nombre" => $row['tasa_iva_nombre'],
                "tasa" => $row['tasa_iva_tasa']
            );

            $producto = array(
                "id" => $row['id'],
                //limpiar el codigo si es balanza
                "codigo" => $row['codigo'],
                "descripcion" => $row['descripcion'],
                "descripcion_ampliada" => $row['descripcion_ampliada'],
                "stock" => $row['stock'] ?? 0,
                "codigo_barra" => $row['codigo_barra'],
                "articulo_activado" => $row['articulo_activado'],
                "producto_balanza" => $row['producto_balanza'] ?? 0,
                "precio1" => $row['precio1'] ?? 0,
                "precio2" => $row['precio2'] ?? 0,
                "precio3" => $row['precio3'] ?? 0,
                "moneda" => $moneda,
                "tasa_iva" => $row['tasa_iva'] ?? 0,
                "incluye_iva" => $row['incluye_iva'],
                "impuesto_interno" => $row['impuesto_interno'] ?? 0,
                "tipo_impuesto_interno" => $row['tipo_impuesto_interno'] ?? 1,
                //si impuesto interno es 1 se calcula el porcentaje si es 2 se toma el valor del campo impuesto_interno aplicvado al precio
                "precio1_impuesto_interno" => ($row['tipo_impuesto_interno'] == 1) ? ($row['precio1'] * $row['impuesto_interno'] / 100) : ($row['precio1'] - $row['impuesto_interno']),
                "precio2_impuesto_interno" => ($row['tipo_impuesto_interno'] == 1) ? ($row['precio2'] * $row['impuesto_interno'] / 100) : ($row['precio2'] - $row['impuesto_interno']),
                "precio3_impuesto_interno" => ($row['tipo_impuesto_interno'] == 1) ? ($row['precio3'] * $row['impuesto_interno'] / 100) : ($row['precio3'] - $row['impuesto_interno']),
                "precio_costo" => $row['precio_costo'] ?? 0,
                "fraccionado" => $row['fraccionado'],
                "rg5329_23" => $row['rg5329_23'],
                "activo" => $row['activo'],
                "texto_panel" => $row['texto_panel'],
                "iibb" => $row['iibb'] ?? 0,
                "codigo_barra2" => $row['codigo_barra2'],
                "oferta" => $row['oferta'],
                "margen_ganancia" => $row['margen_ganancia'],
                "favorito" => $row['favorito'],
                "stock_actual" => $stock_actual

            );
            $pais = array(
                "id" => $row['pais_id'],
                "nombre" => $row['pais_nombre'] ?? 0
            );
            $provincia = array(
                "id" => $row['provincia_id'] ?? 0,
                "nombre" => $row['provincia_nombre'],
                "pais" => $pais
            );
            $localidad = array(
                "id" => $row['localidad_id'] ?? 0,
                "nombre" => $row['localidad_nombre'],
                "codigo_postal" => $row['localidad_codigo_postal'],
                "provincia" => $provincia
            );

            $producto['familia'] = $familia;
            $producto['subfamilia'] = $subfamilia;
            $producto['agrupacion'] = $agrupacion;
            $producto['proveedor'] = $proveedor;
            $producto['proveedor']['localidad'] = $localidad;
            $producto['proveedor']['localidad']['provincia'] = $provincia;
            $producto['proveedor']['localidad']['provincia']['pais'] = $pais;
            $producto['moneda'] = $moneda;
            $producto['tipo'] = $tipo;
            $producto['unidad'] = $unidad;
            $producto['tasa_iva'] = $tasa_iva;


            array_push($response, $producto);
        }
    }

    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $param_insert = array();
        $param_values = array();

        foreach ($data as $key => $value) {
            // Verificar si el valor es numérico
            if (is_numeric($value)) {
                // Si es numérico, tratarlo como float o int según corresponda
                $escaped_value = strpos($value, '.') !== false || strpos($value, ',') !== false ? (float) str_replace(',', '.', $value) : (int) $value;
            } elseif ($value === '') {
                // Si es vacío, asignar NULL
                $escaped_value = 'NULL';
            } else {
                // Si no es numérico ni vacío, escaparlo para evitar inyección de SQL
                $escaped_value = "'" . str_replace("'", "''", $value) . "'";
            }


            array_push($param_insert, $key);
            array_push($param_values, $escaped_value);
        }
        array_push($param_insert, "empresa_id");
        array_push($param_values, $empresa_id);

        $query_insert = "INSERT INTO productos (" . implode(", ", $param_insert) . ",fecha_alta,fecha_actualizacion) VALUES (" . implode(", ", $param_values) . ",NOW(),NOW())";
        $result = $con->query($query_insert);
        //buscar el id del producto insertado
        $query_id = "SELECT MAX(id) AS id FROM productos";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        if ($result) {
            $response = array("status" => 201, "status_message" => "Producto agregado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar producto.", "id" => $id);
        }
    }


    //PUT PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $id = sanitizeInput($_GET['param']);
        $data = json_decode(file_get_contents('php://input'), true);
        $param_update = array();
        $param_update_values = array();

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';
            if (isset($_GET['venta']) && $key == 'stock') {
                $value_stock = ' stock -' . $escaped_value;
                $escaped_value = $value_stock;
            }
            array_push($param_update, $key . "=" . $escaped_value);
        }

        $query_update = "UPDATE productos SET " . implode(", ", $param_update) . ",fecha_actualizacion=NOW() WHERE id=$id";

        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Producto actualizado correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar producto.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM productos WHERE id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Producto eliminado correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró el producto para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar producto.", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    $error_msg = $errores_mysql[$e->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}{$e->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
