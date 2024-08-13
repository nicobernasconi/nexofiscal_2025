<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
        comprobantes.id AS id,
        comprobantes.punto_venta AS punto_venta,
        comprobantes.cliente_id AS cliente_id,
        comprobantes.tipo_factura AS tipo_factura,
        comprobantes.tipo_documento AS tipo_documento,
        comprobantes.numero_de_documento AS numero_de_documento,
        comprobantes.fecha AS fecha,
        comprobantes.hora AS hora,
        comprobantes.numero_factura AS numero_factura,
        comprobantes.total AS total,
        comprobantes.importe_iva AS importe_iva,
        comprobantes.vendedor_id AS vendedor_id,
        comprobantes.observaciones_1 AS observaciones_1,
        comprobantes.observaciones_2 AS observaciones_2,
        comprobantes.usuario_id AS usuario_id,
        comprobantes.fecha_baja AS fecha_baja,
        comprobantes.motivo_baja AS motivo_baja,
        comprobantes.cierre_caja_id AS cierre_caja_id,
        comprobantes.sucursal_id AS sucursal_id,
        clientes.nro_cliente AS cliente_numero,
        clientes.nombre AS cliente_nombre,
        clientes.tipo_iva_id,
        clientes.cuit AS cliente_cuit,
        clientes.tipo_documento_id,
        clientes.numero_documento AS cliente_documento,
        clientes.direccion_comercial AS cliente_direccion,
        clientes.telefono AS cliente_telefono,
        clientes.email AS cliente_email,
        tipo_iva.nombre AS tipo_iva_nombre,
        vendedores.nombre AS vendedor_nombre,
        sucursales.nombre AS sucursal_nombre,
        sucursales.direccion AS sucursal_direccion,
        sucursales.telefono AS sucursal_telefono,
        sucursales.email AS sucursal_email,
        sucursales.contacto_nombre AS sucursal_contacto_nombre,
        sucursales.contacto_telefono AS sucursal_contacto_telefono,
        sucursales.contacto_email AS sucursal_contacto_email,
        tipo_comprobante.nombre AS tipo_comprobante_nombre,
        comprobantes.tipo_comprobante_id 
    FROM
        comprobantes
        LEFT JOIN clientes ON comprobantes.cliente_id = clientes.id
        LEFT JOIN tipo_iva ON clientes.tipo_iva_id = tipo_iva.id
        LEFT JOIN vendedores ON comprobantes.vendedor_id = vendedores.id
        INNER JOIN sucursales ON comprobantes.sucursal_id = sucursales.id
        INNER JOIN tipo_comprobante ON comprobantes.tipo_comprobante_id = tipo_comprobante.id 
    WHERE
        comprobantes.fecha_baja IS NULL ";

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
            array_push($query_param, "comprobantes.id=$id");
        }
        if (isset($_GET['punto_venta'])) {
            $punto_venta = sanitizeInput($_GET['punto_venta']);
            array_push($query_param, "comprobantes.punto_venta=$punto_venta");
        }
        if (isset($_GET['cliente_id'])) {
            $cliente_id = sanitizeInput($_GET['cliente_id']);
            array_push($query_param, "comprobantes.cliente_id=$cliente_id");
        }
        if (isset($_GET['tipo_factura'])) {
            $tipo_factura = sanitizeInput($_GET['tipo_factura']);
            array_push($query_param, "comprobantes.tipo_factura=$tipo_factura");
        }
        if (isset($_GET['tipo_documento'])) {
            $tipo_documento = sanitizeInput($_GET['tipo_documento']);
            array_push($query_param, "comprobantes.tipo_documento=$tipo_documento");
        }
        if (isset($_GET['numero_de_documento'])) {
            $numero_de_documento = sanitizeInput($_GET['numero_de_documento']);
            array_push($query_param, "comprobantes.numero_de_documento=$numero_de_documento");
        }
        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
            $fecha_inicio = sanitizeInput($_GET['fecha_inicio']);
            $fecha_fin = sanitizeInput($_GET['fecha_fin']);
            array_push($query_param, "comprobantes.fecha BETWEEN '$fecha_inicio' AND '$fecha_fin'");
        } elseif (isset($_GET['fecha'])) {
            $fecha = sanitizeInput($_GET['fecha']);
            array_push($query_param, "comprobantes.fecha=$fecha");
        }
        if (isset($_GET['hora'])) {
            $hora = sanitizeInput($_GET['hora']);
            array_push($query_param, "comprobantes.hora=$hora");
        }
        if (isset($_GET['numero_factura'])) {
            $numero_factura = sanitizeInput($_GET['numero_factura']);
            array_push($query_param, "comprobantes.numero_factura=$numero_factura");
        }
        if (isset($_GET['total'])) {
            $total = sanitizeInput($_GET['total']);
            array_push($query_param, "comprobantes.total=$total");
        }
        if (isset($_GET['importe_iva'])) {
            $importe_iva = sanitizeInput($_GET['importe_iva']);
            array_push($query_param, "comprobantes.importe_iva=$importe_iva");
        }
        if (isset($_GET['vendedor_id'])) {
            $vendedor_id = sanitizeInput($_GET['vendedor_id']);
            array_push($query_param, "comprobantes.vendedor_id=$vendedor_id");
        }
        if (isset($_GET['observaciones_1'])) {
            $observaciones_1 = sanitizeInput($_GET['observaciones_1']);
            array_push($query_param, "comprobantes.observaciones_1=$observaciones_1");
        }
        if (isset($_GET['observaciones_2'])) {
            $observaciones_2 = sanitizeInput($_GET['observaciones_2']);
            array_push($query_param, "comprobantes.observaciones_2=$observaciones_2");
        }
        if (isset($_GET['usuario_id'])) {
            $usuario_id = sanitizeInput($_GET['usuario_id']);
            array_push($query_param, "comprobantes.usuario_id=$usuario_id");
        }
        if (isset($_GET['fecha_baja'])) {
            $fecha_baja = sanitizeInput($_GET['fecha_baja']);
            array_push($query_param, "comprobantes.fecha_baja=$fecha_baja");
        }
        if (isset($_GET['motivo_baja'])) {
            $motivo_baja = sanitizeInput($_GET['motivo_baja']);
            array_push($query_param, "comprobantes.motivo_baja=$motivo_baja");
        }
        if (isset($_GET['cierre_caja_id'])) {
            $cierre_caja_id = sanitizeInput($_GET['cierre_caja_id']);
            array_push($query_param, "comprobantes.cierre_caja_id=$cierre_caja_id");
        }
        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);
            array_push($query_param, "comprobantes.sucursal_id=$sucursal_id");
        }
        if (isset($_GET['cliente_numero'])) {
            $cliente_numero = sanitizeInput($_GET['cliente_numero']);
            array_push($query_param, "clientes.nro_cliente=$cliente_numero");
        }
        if (isset($_GET['cliente_nombre'])) {
            $cliente_nombre = sanitizeInput($_GET['cliente_nombre']);
            array_push($query_param, "clientes.nombre=$cliente_nombre");
        }
        if (isset($_GET['tipo_iva_id'])) {
            $tipo_iva_id = sanitizeInput($_GET['tipo_iva_id']);
            array_push($query_param, "clientes.tipo_iva_id=$tipo_iva_id");
        }
        if (isset($_GET['cliente_cuit'])) {
            $cliente_cuit = sanitizeInput($_GET['cliente_cuit']);
            array_push($query_param, "clientes.cuit=$cliente_cuit");
        }
        if (isset($_GET['tipo_documento_id'])) {
            $tipo_documento_id = sanitizeInput($_GET['tipo_documento_id']);
            array_push($query_param, "clientes.tipo_documento_id=$tipo_documento_id");
        }
        if (isset($_GET['cliente_documento'])) {
            $cliente_documento = sanitizeInput($_GET['cliente_documento']);
            array_push($query_param, "clientes.numero_documento=$cliente_documento");
        }
        if (isset($_GET['cliente_direccion'])) {
            $cliente_direccion = sanitizeInput($_GET['cliente_direccion']);
            array_push($query_param, "clientes.direccion_comercial=$cliente_direccion");
        }
        if (isset($_GET['cliente_telefono'])) {
            $cliente_telefono = sanitizeInput($_GET['cliente_telefono']);
            array_push($query_param, "clientes.telefono=$cliente_telefono");
        }
        if (isset($_GET['cliente_email'])) {
            $cliente_email = sanitizeInput($_GET['cliente_email']);
            array_push($query_param, "clientes.email=$cliente_email");
        }
        if (isset($_GET['tipo_iva_nombre'])) {
            $tipo_iva_nombre = sanitizeInput($_GET['tipo_iva_nombre']);
            array_push($query_param, "tipo_iva.nombre=$tipo_iva_nombre");
        }
        if (isset($_GET['vendedor_nombre'])) {
            $vendedor_nombre = sanitizeInput($_GET['vendedor_nombre']);
            array_push($query_param, "vendedores.nombre=$vendedor_nombre");
        }
        if (isset($_GET['sucursal_nombre'])) {
            $sucursal_nombre = sanitizeInput($_GET['sucursal_nombre']);
            array_push($query_param, "sucursales.nombre=$sucursal_nombre");
        }
        if (isset($_GET['sucursal_direccion'])) {
            $sucursal_direccion = sanitizeInput($_GET['sucursal_direccion']);
            array_push($query_param, "sucursales.direccion=$sucursal_direccion");
        }
        if (isset($_GET['sucursal_telefono'])) {
            $sucursal_telefono = sanitizeInput($_GET['sucursal_telefono']);
            array_push($query_param, "sucursales.telefono=$sucursal_telefono");
        }
        if (isset($_GET['sucursal_email'])) {
            $sucursal_email = sanitizeInput($_GET['sucursal_email']);
            array_push($query_param, "sucursales.email=$sucursal_email");
        }
        if (isset($_GET['sucursal_contacto_nombre'])) {
            $sucursal_contacto_nombre = sanitizeInput($_GET['sucursal_contacto_nombre']);
            array_push($query_param, "sucursales.contacto_nombre=$sucursal_contacto_nombre");
        }

        if (isset($_GET['sucursal_contacto_telefono'])) {
            $sucursal_contacto_telefono = sanitizeInput($_GET['sucursal_contacto_telefono']);
            array_push($query_param, "sucursales.contacto_telefono=$sucursal_contacto_telefono");
        }

        if (isset($_GET['sucursal_contacto_email'])) {
            $sucursal_contacto_email = sanitizeInput($_GET['sucursal_contacto_email']);
            array_push($query_param, "sucursales.contacto_email=$sucursal_contacto_email");
        }

        if (isset($_GET['tipo_comprobante_nombre'])) {
            $tipo_comprobante_nombre = sanitizeInput($_GET['tipo_comprobante_nombre']);
            array_push($query_param, "tipo_comprobante.nombre like '%$tipo_comprobante_nombre%'");
        }

        if (isset($_GET['tipo_comprobante_id'])) {
            $tipo_comprobante_id = sanitizeInput($_GET['tipo_comprobante_id']);
            array_push($query_param, "comprobantes.tipo_comprobante_id=$tipo_comprobante_id");
        }else{
            $tipo_comprobante_id = 4;
            array_push($query_param, "comprobantes.tipo_comprobante_id<>$tipo_comprobante_id");
        }
              
        
    

        if (count($query_param) > 0) {
            $query_product = $query_product . " and (" . implode(" AND ", $query_param) . ") AND comprobantes.empresa_id = $empresa_id";
            $query_product_resumen = "SELECT SUM(total) AS total_ventas, SUM(importe_iva) AS total_iva, COUNT(*) AS total_comprobantes FROM comprobantes WHERE comprobantes.fecha_baja IS NULL AND comprobantes.empresa_id = $empresa_id and (" . implode(" AND ", $query_param) . ")";
        } else {
            $query_product = $query_product . " and comprobantes.empresa_id = $empresa_id";
            $query_product_resumen = "SELECT SUM(total) AS total_ventas, SUM(importe_iva) AS total_iva, COUNT(*) AS total_comprobantes FROM comprobantes WHERE comprobantes.fecha_baja IS NULL AND comprobantes.empresa_id = $empresa_id";

        }

       

    
        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM comprobantes WHERE fecha_baja IS NULL AND empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //obtener el total de ventas
        $result_total = $con->query($query_product_resumen);
        $row_total_ventas = $result_total->fetch(PDO::FETCH_ASSOC);
        $total_ventas = $row_total_ventas['total_ventas'] ?? 0;
        $total_iva = $row_total_ventas['total_iva'] ?? 0;
        $total_comprobantes = $row_total_ventas['total_comprobantes'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;
        $query_product = $query_product . " ORDER BY   $order_by  $sort_order  LIMIT $limit OFFSET $offset";

    

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
        $tipo_iva = array();
        $vendedores = array();
        $tipo_documento = array();
        $tipo_factura = array();
        $sucursales = array();
        $comprobante = array();
        $comprobantes= array();
        $resumen = array();
        $response = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tipo_iva = array(
                "id" => $row['tipo_iva_id'],
                "nombre" => $row['tipo_iva_nombre']
            );
            $vendedores = array(
                "id" => $row['vendedor_id'],
                "nombre" => $row['vendedor_nombre']
            );

            $tipo_comprobante = array(
                "id" => $row['tipo_comprobante_id'],
                "nombre" => $row['tipo_comprobante_nombre']
            );
            $sucursales = array(
                "id" => $row['sucursal_id'],
                "nombre" => $row['sucursal_nombre'],
                "direccion" => $row['sucursal_direccion'],
                "telefono" => $row['sucursal_telefono'],
                "email" => $row['sucursal_email'],
                "contacto_nombre" => $row['sucursal_contacto_nombre'],
                "contacto_telefono" => $row['sucursal_contacto_telefono'],
                "contacto_email" => $row['sucursal_contacto_email']
            );
            $clientes = array(
                "id" => $row['cliente_id'],
                "nro_cliente" => $row['cliente_numero']??1,
                "nombre" => $row['cliente_nombre']??'Ocacional',
                "tipo_iva" => $tipo_iva,
                "cuit" => $row['cliente_cuit']??1,
                "tipo_documento" => $tipo_documento,
                "numero_documento" => $row['cliente_documento'],
                "direccion" => $row['cliente_direccion'],
                "telefono" => $row['cliente_telefono'],
                "email" => $row['cliente_email']
            );

            $comprobante = array(
                "id" => $row['id'],
                "punto_venta" => $row['punto_venta'],
                "cliente" => $clientes,
                "numero_de_documento" => $row['numero_de_documento'],
                "fecha" => $row['fecha'],
                "hora" => $row['hora'],
                "numero_factura" => $row['numero_factura'],
                "total" => $row['total'],
                "importe_iva" => $row['importe_iva'],
                "vendedor" => $vendedores,
                "observaciones_1" => $row['observaciones_1'],
                "observaciones_2" => $row['observaciones_2'],
                "usuario_id" => $row['usuario_id'],
                "fecha_baja" => $row['fecha_baja'],
                "motivo_baja" => $row['motivo_baja'],
                "cierre_caja_id" => $row['cierre_caja_id'],
                "sucursal" => $sucursales,
                "tipo_comprobante" => $tipo_comprobante
            );
           array_push($comprobantes, $comprobante);
        }
        $resumen = array(
            "total_ventas" => round($total_ventas, 2),
            "total_iva" => round($total_iva, 2),
            "total_comprobantes" => round($total_comprobantes, 2),
            "query"=> $query_product
        );
        $response = array("status" => 200, "status_message" => "Datos cargados correctamente", "data" => $comprobantes, "resumen" => $resumen);
        
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
