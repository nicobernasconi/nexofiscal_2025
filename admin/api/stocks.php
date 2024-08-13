<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
productos.id,
productos.codigo,
productos.descripcion,
productos.descripcion_ampliada,
productos.stock,
productos.stock_minimo,
productos.stock_pedido,
productos.familia_id,
productos.subfamilia_id,
productos.agrupacion_id,
productos.marca_id,
productos.codigo_barra,
productos.proveedor_id,
productos.fecha_alta,
productos.fecha_actualizacion,
productos.articulo_activado,
productos.tipo_id,
productos.producto_balanza,
productos.precio1,
productos.precio1,
productos.precio2,
productos.moneda_id,
productos.tasa_iva,
productos.incluye_iva,
productos.impuesto_interno,
productos.unidad_id,
agrupacion.numero AS agrupacion_numero,
agrupacion.id AS agrupacion_id,
agrupacion.nombre AS agrupacion_nombre,
agrupacion.color AS agrupacion_color,
agrupacion.icono AS agrupacion_icono,
familias.id AS familia_id,
familias.numero AS familia_numero,
familias.nombre AS familia_nombre,
subfamilias.id AS subfamilia_id,
subfamilias.numero AS subfamilia_numero,
subfamilias.descripcion AS subfamilia_descripcion,
moneda.id AS moneda_id,
moneda.simbolo AS moneda_simbolo,
moneda.nombre AS moneda_nombre,
moneda.cotizacion AS moneda_cotizacion,
tipo.id AS tipo_id,
tipo.numero AS tipo_numero,
tipo.nombre AS tipo_nombre,
proveedores.id AS proveedores_id,
proveedores.razon_social AS proveedores_razon_social,
proveedores.direccion AS proveedores_direccion,
proveedores.localidad_id AS proveedores_localidad_id,
proveedores.telefono AS proveedores_telefono,
proveedores.email AS proveedores_email,
proveedores.tipo_iva_id AS proveedores_iva_id,
proveedores.cuit AS proveedor_cuit,
proveedores.categoria_id AS proveedores_categoria_id,
proveedores.subcategoria_id AS proveedores_subcategoria_id,
proveedores.fecha_ultima_compra AS proveedores_fecha_ultima_compra,
proveedores.fecha_ultimo_pago AS proveedores_ultimo_pago,
proveedores.saldo_actual AS proveedores_saldo_actual,
tipo_iva.nombre AS tipo_iva_nombre,
tipo_iva.descripcion AS tipo_iva_descripcion,
tipo_iva.porcentaje AS tipo_iva_porcentaje,
localidad.nombre AS localidad_nombre,
localidad.codigo_postal AS localidad_codigo_postal,
provincias.nombre AS provincia_nombre,
pais.nombre AS pais_nombre,
pais.id AS pais_id,
provincias.id AS provincia_id,
localidad.id AS localidad_id,
unidad.nombre AS unidad_nombre,
unidad.simbolo AS unidad_simbolo,
productos.precio_costo,
productos.fraccionado,
productos.rg5329_23,
productos.activo,
productos.texto_panel,
productos.iibb,
productos.codigo_barra2,
productos.oferta,
productos.margen_ganancia
FROM
productos
LEFT JOIN subfamilias ON productos.subfamilia_id = subfamilias.id
LEFT JOIN familias ON productos.familia_id = familias.id
LEFT JOIN agrupacion ON productos.agrupacion_id = agrupacion.id
LEFT JOIN proveedores ON productos.proveedor_id = proveedores.id
LEFT JOIN moneda ON productos.moneda_id = moneda.id
LEFT JOIN tipo ON productos.tipo_id = tipo.id
LEFT JOIN tipo_iva ON proveedores.tipo_iva_id = tipo_iva.id
LEFT JOIN localidad ON proveedores.localidad_id = localidad.id
LEFT JOIN provincias ON localidad.provincia_id = provincias.id
LEFT JOIN pais ON provincias.pais_id = pais.id
LEFT JOIN unidad ON productos.unidad_id = unidad.id
";

        $query_param = array();
        if (isset($_GET['order_by'])) {
            $order_by = sanitizeInput($_GET['order_by']);
        } else {
            $order_by = 'stock';
        }

        if (isset($_GET['sort_order'])) {
            $sort_order = sanitizeInput($_GET['sort_order']);
        } else {
            $sort_order = ' ASC ';
        }



        //recibir todos los posibles parametros por GET
        if (isset($_GET['param'])) {
            $id = sanitizeInput($_GET['param']);
            array_push($query_param, "productos.id=$id");
        }
        if (isset($_GET['codigo'])) {
            $codigo = sanitizeInput($_GET['codigo']);
            array_push($query_param, "codigo='$codigo'");
        }
        if (isset($_GET['descripcion'])) {
            $descripcion = sanitizeInput($_GET['descripcion']);
            array_push($query_param, "productos.descripcion like '%$descripcion%'");
        }
        if (isset($_GET['descripcion_ampliada'])) {
            $descripcion_ampliada = sanitizeInput($_GET['descripcion_ampliada']);
            array_push($query_param, "descripcion_ampliada like '%$descripcion_ampliada%'");
        }
        if (isset($_GET['familia_id'])) {
            $familia_id = sanitizeInput($_GET['familia_id']);
            array_push($query_param, "familia_id=$familia_id");
        }

        if (isset($_GET['subfamilia_id'])) {
            $subfamilia_id = sanitizeInput($_GET['subfamilia_id']);
            array_push($query_param, "subfamilia_id=$subfamilia_id");
        }

        if (isset($_GET['agrupacion_id'])) {
            $agrupacion_id = sanitizeInput($_GET['agrupacion_id']);
            array_push($query_param, "agrupacion_id=$agrupacion_id");
        }

        if (isset($_GET['marca_id'])) {
            $marca_id = sanitizeInput($_GET['marca_id']);
            array_push($query_param, "marca_id=$marca_id");
        }

        if (isset($_GET['codigo_barra'])) {
            $codigo_barra = sanitizeInput($_GET['codigo_barra']);
            array_push($query_param, "codigo_barra='$codigo_barra'");
        }

        if (isset($_GET['proveedor_id'])) {
            $proveedor_id = sanitizeInput($_GET['proveedor_id']);
            array_push($query_param, "proveedor_id=$proveedor_id");
        }

        if (isset($_GET['fecha_alta'])) {
            $fecha_alta = sanitizeInput($_GET['fecha_alta']);
            array_push($query_param, "fecha_alta=$fecha_alta");
        }

        if (isset($_GET['fecha_actualizacion'])) {
            $fecha_actualizacion = sanitizeInput($_GET['fecha_actualizacion']);
            array_push($query_param, "fecha_actualizacion=$fecha_actualizacion");
        }

        if (isset($_GET['articulo_activado'])) {
            $articulo_activado = sanitizeInput($_GET['articulo_activado']);
            array_push($query_param, "articulo_activado=$articulo_activado");
        }

        if (isset($_GET['tipo_id'])) {
            $tipo_id = sanitizeInput($_GET['tipo_id']);
            array_push($query_param, "tipo_id=$tipo_id");
        }

        if (isset($_GET['producto_balanza'])) {
            $producto_balanza = sanitizeInput($_GET['producto_balanza']);
            array_push($query_param, "producto_balanza=$producto_balanza");
        }

        if (isset($_GET['precio1'])) {
            $precio1 = sanitizeInput($_GET['precio1']);
            array_push($query_param, "precio1=$precio1");
        }

        if (isset($_GET['moneda_id'])) {
            $moneda_id = sanitizeInput($_GET['moneda_id']);
            array_push($query_param, "moneda_id=$moneda_id");
        }

        if (isset($_GET['tasa_iva'])) {
            $tasa_iva = sanitizeInput($_GET['tasa_iva']);
            array_push($query_param, "tasa_iva=$tasa_iva");
        }

        if (isset($_GET['incluye_iva'])) {
            $incluye_iva = sanitizeInput($_GET['incluye_iva']);
            array_push($query_param, "incluye_iva=$incluye_iva");
        }



        if (isset($_GET['impuesto_interno'])) {
            $impuesto_interno = sanitizeInput($_GET['impuesto_interno']);
            array_push($query_param, "impuesto_interno=$impuesto_interno");
        }

        if (isset($_GET['agrupacion_numero'])) {
            $agrupacion_numero = sanitizeInput($_GET['agrupacion_numero']);
            array_push($query_param, "agrupacion_numero=$agrupacion_numero");
        }

        if (isset($_GET['agrupacion_nombre'])) {
            $agrupacion_nombre = sanitizeInput($_GET['agrupacion_nombre']);
            array_push($query_param, "agrupacion_nombre like '%$agrupacion_nombre%'");
        }

        if (isset($_GET['familia_numero'])) {
            $familia_numero = sanitizeInput($_GET['familia_numero']);
            array_push($query_param, "familia_numero=$familia_numero");
        }

        if (isset($_GET['familia_nombre'])) {
            $familia_nombre = sanitizeInput($_GET['familia_nombre']);
            array_push($query_param, "familia_nombre like '%$familia_nombre%'");
        }

        if (isset($_GET['subfamilia_numero'])) {
            $subfamilia_numero = sanitizeInput($_GET['subfamilia_numero']);
            array_push($query_param, "subfamilia_numero=$subfamilia_numero");
        }

        if (isset($_GET['subfamilia_descripcion'])) {
            $subfamilia_descripcion = sanitizeInput($_GET['subfamilia_descripcion']);
            array_push($query_param, "subfamilia_descripcion like '%$subfamilia_descripcion%'");
        }

        if (isset($_GET['moneda_simbolo'])) {
            $moneda_simbolo = sanitizeInput($_GET['moneda_simbolo']);
            array_push($query_param, "moneda_simbolo like '%$moneda_simbolo%'");
        }

        if (isset($_GET['moneda_nombre'])) {
            $moneda_nombre = sanitizeInput($_GET['moneda_nombre']);
            array_push($query_param, "moneda_nombre like '%$moneda_nombre%'");
        }

        if (isset($_GET['moneda_cotizacion'])) {
            $moneda_cotizacion = sanitizeInput($_GET['moneda_cotizacion']);
            array_push($query_param, "moneda_cotizacion=$moneda_cotizacion");
        }

        if (isset($_GET['tipo_numero'])) {
            $tipo_numero = sanitizeInput($_GET['tipo_numero']);
            array_push($query_param, "tipo_numero=$tipo_numero");
        }
        if (isset($_GET['stock'])) {
            $stock = sanitizeInput($_GET['stock']);
            array_push($query_param, "stock=$stock");
        }

        if (isset($_GET['stock_minimo'])) {
            $stock_minimo = sanitizeInput($_GET['stock_minimo']);
            array_push($query_param, "stock_minimo=$stock_minimo");
        }

        if (isset($_GET['stock_pedido'])) {
            $stock_pedido = sanitizeInput($_GET['stock_pedido']);
            array_push($query_param, "stock_pedido=$stock_pedido");
        }


        if (isset($_GET['tipo_nombre'])) {
            $tipo_nombre = sanitizeInput($_GET['tipo_nombre']);
            array_push($query_param, "tipo_nombre like '%$tipo_nombre%'");
        }

        if (isset($_GET['proveedores_razon_social'])) {
            $proveedores_razon_social = sanitizeInput($_GET['proveedores_razon_social']);
            array_push($query_param, "proveedores_razon_social like '%$proveedores_razon_social%'");
        }

        if (isset($_GET['proveedores_direccion'])) {
            $proveedores_direccion = sanitizeInput($_GET['proveedores_direccion']);
            array_push($query_param, "proveedores_direccion like '%$proveedores_direccion%'");
        }

        if (isset($_GET['proveedores_localidad_id'])) {
            $proveedores_localidad_id = sanitizeInput($_GET['proveedores_localidad_id']);
            array_push($query_param, "proveedores_localidad_id=$proveedores_localidad_id");
        }

        if (isset($_GET['proveedores_telefono'])) {
            $proveedores_telefono = sanitizeInput($_GET['proveedores_telefono']);
            array_push($query_param, "proveedores_telefono like '%$proveedores_telefono%'");
        }

        if (isset($_GET['proveedores_email'])) {
            $proveedores_email = sanitizeInput($_GET['proveedores_email']);
            array_push($query_param, "proveedores_email like '%$proveedores_email%'");
        }
        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" OR ", $query_param).") AND productos.empresa_id = $empresa_id";        
            }else{
                $query_product = $query_product . " WHERE productos.empresa_id = $empresa_id";
            }

        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM productos WHERE productos.empresa_id = $empresa_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit =$_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY  " . $order_by . "  " . $sort_order . " OFFSET $offset ROWS FETCH NEXT $limit ROWS ONLY";


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

        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

            $familia = array(
                "id" => $row['familia_id'],
                "numero" => $row['familia_numero'],
                "nombre" => $row['familia_nombre']
            );
            $subfamilia = array(
                "id" => $row['subfamilia_id'],
                "numero" => $row['subfamilia_numero'],
                "descripcion" => $row['subfamilia_descripcion']
            );
            $agrupacion = array(
                "id" => $row['agrupacion_id'],
                "numero" => $row['agrupacion_numero'],
                "nombre" => $row['agrupacion_nombre'],
                "color" => $row['agrupacion_color'] ?? '000000',
                "icono" => $row['agrupacion_icono'] ?? 'fas fa-boxes'

            );
            $proveedor = array(
                "id" => $row['proveedores_id'],
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
                "id" => $row['moneda_id'],
                "simbolo" => $row['moneda_simbolo'],
                "nombre" => $row['moneda_nombre'],
                "cotizacion" => $row['moneda_cotizacion']
            );
            $tipo = array(
                "id" => $row['tipo_id'],
                "numero" => $row['tipo_numero'],
                "nombre" => $row['tipo_nombre']
            );

            $unidad = array(
                "id" => $row['unidad_id'],
                "nombre" => $row['unidad_nombre'],
                "simbolo" => $row['unidad_simbolo']
            );

            $producto = array(
                "id" => $row['id'],
                "codigo" => $row['codigo'],
                "descripcion" => $row['descripcion'],
                "descripcion_ampliada" => $row['descripcion_ampliada'],
                "stock" => $row['stock'],
                "stock_minimo" => $row['stock_minimo'],
                "stock_pedido" => $row['stock_pedido'],
                "codigo_barra" => $row['codigo_barra'],
                "articulo_activado" => $row['articulo_activado'],
                "producto_balanza" => $row['producto_balanza'],
                "precio1" => $row['precio1'],
                "precio2" => $row['precio2'],
                "moneda" => $moneda,
                "tasa_iva" => $row['tasa_iva'],
                "incluye_iva" => $row['incluye_iva'],
                "impuesto_interno" => $row['impuesto_interno'],
                "precio_costo" => $row['precio_costo'],
                "fraccionado" => $row['fraccionado'],
                "rg5329_23" => $row['rg5329_23'],
                "activo" => $row['activo'],
                "texto_panel" => $row['texto_panel'],
                "iibb" => $row['iibb'] ?? 0,
                "codigo_barra2" => $row['codigo_barra2'],
                "oferta" => $row['oferta'],
                "margen_ganancia" => $row['margen_ganancia']
            );
            $pais = array(
                "id" => $row['pais_id'],
                "nombre" => $row['pais_nombre']
            );
            $provincia = array(
                "id" => $row['provincia_id'],
                "nombre" => $row['provincia_nombre'],
                "pais" => $pais
            );
            $localidad = array(
                "id" => $row['localidad_id'],
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


            array_push($response, $producto);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    $response = array("status" => 500, "status_message" => "Error en el servidor.{$e->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
