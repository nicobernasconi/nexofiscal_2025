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
                            comprobantes.numero AS numero,
                            comprobantes.cuotas AS cuotas,
                            comprobantes.cliente_id AS cliente_id,
                            comprobantes.remito AS remito,
                            comprobantes.persona AS persona,
                            comprobantes.provincia_id AS provincia_id,
                            comprobantes.fecha AS fecha,
                            comprobantes.hora AS hora,
                            comprobantes.fecha_proceso AS fecha_proceso,
                            comprobantes.letra AS letra,
                            comprobantes.numero_factura AS numero_factura,
                            comprobantes.prefijo_factura AS prefijo_factura,
                            comprobantes.operacion_negocio_id AS operacion_negocio_id,
                            comprobantes.retencion_iva AS retencion_iva,
                            comprobantes.retencion_iibb AS retencion_iibb,
                            comprobantes.retencion_ganancias AS retencion_ganancias,
                            comprobantes.porcentaje_ganancias AS porcentaje_ganancias,
                            comprobantes.porcentaje_iibb AS porcentaje_iibb,
                            comprobantes.porcentaje_iva AS porcentaje_iva,
                            comprobantes.no_gravado AS no_gravado,
                            comprobantes.importe_iva AS importe_iva,
                            comprobantes.total AS total,
                            comprobantes.total_pagado AS total_pagado,
                            comprobantes.condicion_venta_id AS condicion_venta_id,
                            comprobantes.descripcion_flete AS descripcion_flete,
                            comprobantes.vendedor_id AS vendedor_id,
                            comprobantes.recibo AS recibo,
                            comprobantes.observaciones_1 AS observaciones_1,
                            comprobantes.observaciones_2 AS observaciones_2,
                            comprobantes.observaciones_3 AS observaciones_3,
                            comprobantes.observaciones_4 AS observaciones_4,
                            comprobantes.descuento AS descuento,
                            comprobantes.descuento_1 AS descuento_1,
                            comprobantes.descuento_2 AS descuento_2,
                            comprobantes.descuento_3 AS descuento_3,
                            comprobantes.descuento_4 AS descuento_4,
                            comprobantes.iva_2 AS iva_2,
                            comprobantes.impresa AS impresa,
                            comprobantes.cancelado AS cancelado,
                            comprobantes.nombre_cliente AS nombre_cliente,
                            comprobantes.direccion_cliente AS direccion_cliente,
                            comprobantes.localidad_cliente AS localidad_cliente,
                            comprobantes.garantia AS garantia,
                            comprobantes.concepto AS concepto,
                            comprobantes.notas AS notas,
                            comprobantes.linea_pago_ultima AS linea_pago_ultima,
                            comprobantes.relacion_tk AS relacion_tk,
                            comprobantes.total_iibb AS total_iibb,
                            comprobantes.importe_iibb AS importe_iibb,
                            comprobantes.provincia_categoria_iibb_id AS provincia_categoria_iibb_id,
                            comprobantes.importe_retenciones AS importe_retenciones,
                            comprobantes.provincia_iva_proveedor_id AS provincia_iva_proveedor_id,
                            comprobantes.ganancias_proveedor_id AS ganancias_proveedor_id,
                            comprobantes.importe_ganancias AS importe_ganancias,
                            comprobantes.numero_iibb AS numero_iibb,
                            comprobantes.numero_ganancias AS numero_ganancias,
                            comprobantes.ganancias_proveedor AS ganancias_proveedor,
                            comprobantes.cae AS cae,
                            comprobantes.fecha_vencimiento AS fecha_vencimiento,
                            comprobantes.forma_pago_id AS forma_pago_id,
                            comprobantes.remito_cliente AS remito_cliente,
                            comprobantes.texto_dolares AS texto_dolares,
                            comprobantes.comprobante_final AS comprobante_final,
                            comprobantes.numero_guia_1 AS numero_guia_1,
                            comprobantes.numero_guia_2 AS numero_guia_2,
                            comprobantes.numero_guia_3 AS numero_guia_3,
                            comprobantes.tipo_alicuota_1 AS tipo_alicuota_1,
                            comprobantes.tipo_alicuota_2 AS tipo_alicuota_2,
                            comprobantes.tipo_alicuota_3 AS tipo_alicuota_3,
                            comprobantes.importe_iva_105 AS importe_iva_105,
                            comprobantes.importe_iva_21 AS importe_iva_21,
                            comprobantes.importe_iva_0 AS importe_iva_0,
                            comprobantes.no_gravado_iva_105 AS no_gravado_iva_105,
                            comprobantes.no_gravado_iva_21 AS no_gravado_iva_21,
                            comprobantes.no_gravado_iva_0 AS no_gravado_iva_0,
                            comprobantes.direccion_entrega AS direccion_entrega,
                            comprobantes.fecha_entrega AS fecha_entrega,
                            comprobantes.hora_entrega AS hora_entrega,
                            comprobantes.tipo_comprobante_id AS tipo_comprobante_id,
                            comprobantes.fecha_baja AS fecha_baja,
                            comprobantes.motivo_baja AS motivo_baja,
                            comprobantes.empresa_id AS empresa_id,
                            comprobantes.sucursal_id AS sucursal_id,
                            comprobantes.comprobante_id_baja AS comprobante_id_baja,
                            comprobantes.qr AS qr,
                            clientes.nro_cliente AS cliente_nro_cliente,
                            clientes.nombre AS cliente_nombre,
                            clientes.tipo_iva_id AS cliente_tipo_iva_id,
                            clientes.cuit AS cliente_cuit,
                            clientes.tipo_documento_id AS cliente_tipo_documento_id,
                            clientes.numero_documento AS cliente_numero_documento,
                            clientes.direccion_comercial AS clientes_direccion_comercial,
                            provincias.nombre AS provincia_nombre,
                            operacion_negocio.nombre AS operacion_negocio_nombre,
                            condicion_venta.nombre AS condicion_venta_nombre,
                            vendedores.nombre AS vendedor_nombre,
                            vendedores.direccion AS vendedor_direccion,
                            vendedores.telefono AS vendedor_telefono,
                            vendedores.porcentaje_comision AS vendedor_porcentaje_comision,
                            vendedores.fecha_ingreso AS vendedor_fecha_ingreso,
                            provincia_categoria_iibb.categoria_iibb_id AS provincia_categoria_iibb_categoria_iibb_id,
                            provincia_iva_proveedor.provincia_id AS provincia_iva_proveedor_provincia_id,
                            provincia_iva_proveedor.tipo_iva_id AS provincia_iva_proveedor_tipo_iva_id,
                            provincia_iva_proveedor.proveedor_id AS provincia_iva_proveedor_proveedor_id,
                            ganancias_proveedores.proveedor_id AS ganancias_proveedor_proveedor_id,
                            ganancias_proveedores.ingresos_gravados AS ganancias_proveedor_ingresos_gravados,
                            ganancias_proveedores.deducciones AS ganancias_proveedor_deducciones,
                            ganancias_proveedores.ganancia_neta AS ganancias_proveedor_ganancia_neta,
                            ganancias_proveedores.impuesto_calculado AS ganancias_proveedor_impuesto_calculado,
                            ganancias_proveedores.periodo_fiscal AS ganancias_proveedor_periodo_fiscal,
                            provincia_categoria_iibb.provincia_id AS provincia_categoria_iibb_provincia_id,
                            forma_pago.nombre AS forma_pago_nombre,
                            forma_pago.porcentaje AS forma_pago_porcentaje,
                            tipo_comprobante.nombre AS tipo_comprobante_nombre,
                            comprobantes.punto_venta,
                            comprobantes.tipo_factura,
                            comprobantes.tipo_documento,
                            comprobantes.numero_de_documento,
                            tipo_iva.nombre AS cliente_tipo_iva_nombre,
                            tipo_iva.id AS cliente_tipo_iva_id
                            FROM
                            comprobantes
                            LEFT JOIN clientes ON comprobantes.cliente_id = clientes.id
                            LEFT JOIN provincias ON comprobantes.provincia_id = provincias.id
                            LEFT JOIN operacion_negocio ON comprobantes.operacion_negocio_id = operacion_negocio.id
                            LEFT JOIN condicion_venta ON comprobantes.condicion_venta_id = condicion_venta.id
                            LEFT JOIN vendedores ON comprobantes.vendedor_id = vendedores.id
                            LEFT JOIN provincia_categoria_iibb ON comprobantes.provincia_categoria_iibb_id = provincia_categoria_iibb.id
                            LEFT JOIN provincia_iva_proveedor ON provincia_iva_proveedor.id = comprobantes.provincia_iva_proveedor_id
                            LEFT JOIN ganancias_proveedores ON comprobantes.ganancias_proveedor_id = ganancias_proveedores.id
                            LEFT JOIN forma_pago ON comprobantes.forma_pago_id = forma_pago.id
                            LEFT JOIN tipo_comprobante ON comprobantes.tipo_comprobante_id = tipo_comprobante.id
                            LEFT JOIN localidad ON clientes.localidad_id = localidad.id
                            LEFT JOIN tipo_iva ON clientes.tipo_iva_id = tipo_iva.id";

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

        if (isset($_GET['numero_factura'])) {
            $numero_factura = sanitizeInput($_GET['numero_factura']);
            array_push($query_param, "comprobantes.numero_factura=$numero_factura");
        }

        if (isset($_GET['sucursal_id'])) {
            $sucursal_id = sanitizeInput($_GET['sucursal_id']);

        }


        if (isset($_GET['numero'])) {
            $numero = sanitizeInput($_GET['numero']);
            array_push($query_param, "comprobantes.numero=$numero");
        }

        if (isset($_GET['tipo_comprobante_nombre'])) {
            $tipo_comprobante_nombre = sanitizeInput($_GET['tipo_comprobante_nombre']);
            array_push($query_param, "tipo_comprobante.nombre='$tipo_comprobante_nombre'");
        }




        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE  (" . implode(" OR ", $query_param) . ') and comprobantes.empresa_id=' . $empresa_id.' and comprobantes.sucursal_id='.$sucursal_id;
        } else {
            $query_product = $query_product . " WHERE comprobantes.empresa_id=" . $empresa_id;
        }
        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM comprobantes WHERE comprobantes.empresa_id=" . $empresa_id . " and comprobantes.sucursal_id=".$sucursal_id;
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit = $_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY  " . $order_by . "  " . $sort_order . " LIMIT $limit OFFSET $offset";

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
        $comprobantes = array();
        $clientes = array();
        $operacion_negocio = array();
        $condicion_venta = array();
        $vendedores = array();
        $provincia_categoria_iibb = array();
        $provincia_iva_proveedor = array();
        $ganancias_proveedores = array();
        $forma_pago = array();
        $provincias = array();
        $response = array();
        $tipo_comprobante = array();
        $tipo_iva = array();


        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $tipo_iva = array(
                'id' => $row['cliente_tipo_iva_id'],
                'nombre' => $row['cliente_tipo_iva_nombre']
            );

            $clientes = array(
                'id' => $row['cliente_id'],
                'nro_cliente' => $row['cliente_nro_cliente'],
                'nombre' => $row['cliente_nombre'],
                'tipo_iva_id' => $row['cliente_tipo_iva_id'],
                'cuit' => $row['cliente_cuit'],
                'tipo_documento_id' => $row['cliente_tipo_documento_id'],
                'numero_documento' => $row['cliente_numero_documento'],
                'direccion_comercial' => $row['clientes_direccion_comercial'],
                'tipo_iva' => $tipo_iva

            );

            $operacion_negocio = array(
                'id' => $row['operacion_negocio_id'],
                'nombre' => $row['operacion_negocio_nombre']
            );

            $condicion_venta = array(
                'id' => $row['condicion_venta_id'],
                'nombre' => $row['condicion_venta_nombre']
            );

            $vendedores = array(
                'id' => $row['vendedor_id'],
                'nombre' => $row['vendedor_nombre'],
                'direccion' => $row['vendedor_direccion'],
                'telefono' => $row['vendedor_telefono'],
                'porcentaje_comision' => $row['vendedor_porcentaje_comision'],
                'fecha_ingreso' => $row['vendedor_fecha_ingreso']
            );

            $provincia_categoria_iibb = array(
                'id' => $row['provincia_categoria_iibb_id'],
                'provincia_id' => $row['provincia_categoria_iibb_provincia_id'],
                'categoria_iibb_id' => $row['provincia_categoria_iibb_categoria_iibb_id']
            );

            $provincia_iva_proveedor = array(
                'id' => $row['provincia_iva_proveedor_id'],
                'provincia_id' => $row['provincia_iva_proveedor_provincia_id'],
                'tipo_iva_id' => $row['provincia_iva_proveedor_tipo_iva_id'],
                'proveedor_id' => $row['provincia_iva_proveedor_proveedor_id']
            );

            $ganancias_proveedores = array(
                'id' => $row['ganancias_proveedor_id'],
                'proveedor_id' => $row['ganancias_proveedor_proveedor_id'],
                'ingresos_gravados' => $row['ganancias_proveedor_ingresos_gravados'],
                'deducciones' => $row['ganancias_proveedor_deducciones'],
                'ganancia_neta' => $row['ganancias_proveedor_ganancia_neta'],
                'impuesto_calculado' => $row['ganancias_proveedor_impuesto_calculado'],
                'periodo_fiscal' => $row['ganancias_proveedor_periodo_fiscal']
            );

            $forma_pago = array(
                'id' => $row['forma_pago_id'],
                'nombre' => $row['forma_pago_nombre'],
                'porcentaje' => $row['forma_pago_porcentaje']
            );

            $provincias = array(
                'id' => $row['provincia_id'],
                'nombre' => $row['provincia_nombre']
            );

            $tipo_comprobante = array(
                'id' => $row['tipo_comprobante_id'],
                'nombre' => $row['tipo_comprobante_nombre']
            );

            $comprobantes = array(
                'id' => $row['id'],
                'numero' => $row['numero'],
                'cuotas' => $row['cuotas'],
                'cliente_id' => $row['cliente_id'],
                'remito' => $row['remito'],
                'persona' => $row['persona'],
                'provincia_id' => $row['provincia_id'],
                'fecha' => $row['fecha'],
                'fecha_baja' => $row['fecha_baja'],
                'motivo_baja' => $row['motivo_baja'],
                'hora' => $row['hora'],
                'fecha_proceso' => $row['fecha_proceso'],
                'letra' => $row['letra'],
                'numero_factura' => $row['numero_factura'],
                'prefijo_factura' => $row['prefijo_factura'],
                'operacion_negocio_id' => $row['operacion_negocio_id'],
                'retencion_iva' => $row['retencion_iva'],
                'retencion_iibb' => $row['retencion_iibb'],
                'retencion_ganancias' => $row['retencion_ganancias'],
                'porcentaje_ganancias' => $row['porcentaje_ganancias'],
                'porcentaje_iibb' => $row['porcentaje_iibb'],
                'porcentaje_iva' => $row['porcentaje_iva'],
                'no_gravado' => $row['no_gravado'],
                'importe_iva' => $row['importe_iva'],
                'total' => $row['total'],
                'total_pagado' => $row['total_pagado'],
                'condicion_venta_id' => $row['condicion_venta_id'],
                'descripcion_flete' => $row['descripcion_flete'],
                'vendedor_id' => $row['vendedor_id'],
                'recibo' => $row['recibo'],
                'observaciones_1' => $row['observaciones_1'],
                'observaciones_2' => $row['observaciones_2'],
                'observaciones_3' => $row['observaciones_3'],
                'observaciones_4' => $row['observaciones_4'],
                'descuento' => $row['descuento'],
                'descuento_1' => $row['descuento_1'],
                'descuento_2' => $row['descuento_2'],
                'descuento_3' => $row['descuento_3'],
                'descuento_4' => $row['descuento_4'],
                'iva_2' => $row['iva_2'],
                'impresa' => $row['impresa'],
                'cancelado' => $row['cancelado'],
                'nombre_cliente' => $row['nombre_cliente'],
                'direccion_cliente' => $row['direccion_cliente'],
                'localidad_cliente' => $row['localidad_cliente'],
                'garantia' => $row['garantia'],
                'concepto' => $row['concepto'],
                'notas' => $row['notas'],
                'linea_pago_ultima' => $row['linea_pago_ultima'],
                'relacion_tk' => $row['relacion_tk'],
                'total_iibb' => $row['total_iibb'],
                'importe_iibb' => $row['importe_iibb'],
                'provincia_categoria_iibb_id' => $row['provincia_categoria_iibb_id'],
                'importe_retenciones' => $row['importe_retenciones'],
                'provincia_iva_proveedor_id' => $row['provincia_iva_proveedor_id'],
                'ganancias_proveedor_id' => $row['ganancias_proveedor_id'],
                'importe_ganancias' => $row['importe_ganancias'],
                'numero_iibb' => $row['numero_iibb'],
                'numero_ganancias' => $row['numero_ganancias'],
                'ganancias_proveedor' => $row['ganancias_proveedor'],
                'cae' => $row['cae'],
                'fecha_vencimiento' => $row['fecha_vencimiento'],
                'forma_pago_id' => $row['forma_pago_id'],
                'remito_cliente' => $row['remito_cliente'],
                'texto_dolares' => $row['texto_dolares'],
                'comprobante_final' => $row['comprobante_final'],
                'numero_guia_1' => $row['numero_guia_1'],
                'numero_guia_2' => $row['numero_guia_2'],
                'numero_guia_3' => $row['numero_guia_3'],
                'tipo_alicuota_1' => $row['tipo_alicuota_1'],
                'tipo_alicuota_2' => $row['tipo_alicuota_2'],
                'tipo_alicuota_3' => $row['tipo_alicuota_3'],
                'importe_iva_105' => $row['importe_iva_105'],
                'importe_iva_21' => $row['importe_iva_21'],
                'importe_iva_0' => $row['importe_iva_0'],
                'no_gravado_iva_105' => $row['no_gravado_iva_105'],
                'no_gravado_iva_21' => $row['no_gravado_iva_21'],
                'no_gravado_iva_0' => $row['no_gravado_iva_0'],
                'direccion_entrega' => $row['direccion_entrega'],
                'fecha_entrega' => $row['fecha_entrega'],
                'hora_entrega' => $row['hora_entrega'],
                'empresa_id' => $row['empresa_id'],
                'punto_venta' => $row['punto_venta'],
                'tipo_factura' => $row['tipo_factura'],
                'tipo_documento' => $row['tipo_documento'],
                'numero_de_documento' => $row['numero_de_documento'],
                'qr' => $row['qr'],
                'comprobante_id_baja' => $row['comprobante_id_baja'],
                'sucursal_id' => $row['sucursal_id'],
                'cliente' => $clientes,
                'operacion_negocio' => $operacion_negocio,
                'condicion_venta' => $condicion_venta,
                'vendedor' => $vendedores,
                'provincia_categoria_iibb' => $provincia_categoria_iibb,
                'provincia_iva_proveedor' => $provincia_iva_proveedor,
                'ganancias_proveedores' => $ganancias_proveedores,
                'forma_pago' => $forma_pago,
                'provincia' => $provincias,
                'tipo_comprobante' => $tipo_comprobante,
                



            );


            array_push($response, $comprobantes);
        }
    }
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $param_insert = array();
        $param_values = array();

        if ($data['tipo_comprobante_id']==3){
            $sucursal_id=$data['sucursal_id'];
            $query_pedido="SELECT nro_pedido(".$empresa_id.",".$sucursal_id." ) as numero";
            $result_pedido = $con->query($query_pedido);
            $row_pedido = $result_pedido->fetch(PDO::FETCH_ASSOC);
            $value_pedido=$row_pedido['numero'];
            $data['numero']=$value_pedido;
        }
            

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';

            array_push($param_insert, $key);
            array_push($param_values, $escaped_value);
        }
        array_push($param_insert, 'empresa_id');
        array_push($param_values, $empresa_id);
        array_push($param_insert, 'usuario_id');
        array_push($param_values, $usuario_id);

        $query_insert = "INSERT INTO comprobantes (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);

        $query_id = "SELECT id ,numero_factura  FROM comprobantes order by id DESC LIMIT 1";
        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
        $numero_factura = $row_id['numero_factura'] ?? null;

        if ($result) {
            $response = array("status" => 201, "status_message" => "comprobantes agregada correctamente.", "id" => $id, "numero_factura" => $numero_factura,"numero"=> $data['numero']??0);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar comprobantes .", "id" => $id, "numero_factura" => $numero_factura);
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
        $query_update = "UPDATE comprobantes SET " . implode(", ", $param_update) . " WHERE comprobantes.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "comprobantes  actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar comprobantes .", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "UPDATE comprobantes SET fecha_baja=NOW() WHERE comprobantes.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "comprobantes  CANCELADO correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la comprobantes  para CANCELAR.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al CANCELAR comprobantes .", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $response = array("status" => 500, "status_message" => "Error en el servidor.", "descripcion" => "Codigo de error {$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
