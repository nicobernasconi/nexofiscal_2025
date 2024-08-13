<?php

//conexion con la base de datos
$tipo_base = 'MYSQL';
$serverName_remota = "190.228.29.53";
$dbname_remota = "nexofiscal";
$username_remota = "nexofiscal";
$password_remota = "gqavOLLtkDB1";


$serverName_local = "localhost";
$dbname_local = "nexofiscal";
$username_local = "nexofiscal";
$password_local = "nexofiscal";



try {
    if ($tipo_base == 'MSSQL') {
        $con_remota = new PDO("sqlsrv:server=$serverName_remota;Database=$dbname_remota;ConnectionPooling=0", $username_remota, $password_remota);
        $con_remota->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    if ($tipo_base === 'MYSQL') {
         $con_remota = new PDO("mysql:host=$serverName_remota;dbname=$dbname_remota", $username_remota, $password_remota);
        $con_remota->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


//comparar la tabla productos y actualizar la tabla productos_local
$stmt = $con_remota->prepare("SELECT
productos.id,
productos.codigo,
productos.descripcion,
productos.descripcion_ampliada,
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
productos.precio_costo,
productos.moneda_id,
productos.tasa_iva,
productos.tasa_iva_id,
productos.incluye_iva,
productos.impuesto_interno,
productos.precio1,
productos.precio2,
productos.precio3,
productos.fraccionado,
productos.rg5329_23,
productos.activo,
productos.texto_panel,
productos.stock,
productos.stock_minimo,
productos.stock_pedido,
productos.iibb,
productos.codigo_barra2,
productos.oferta,
productos.margen_ganancia,
productos.publicado_web,
productos.unidad_id,
productos.empresa_id,
productos.favorito,
productos.tipo_impuesto_interno
FROM
productos where empresa_id = 33");
$stmt->execute();
$productos_remota = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $con_remota->prepare("SELECT
productos.id,
productos.codigo,
productos.descripcion,
productos.descripcion_ampliada,
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
productos.precio_costo,
productos.moneda_id,
productos.tasa_iva,
productos.tasa_iva_id,
productos.incluye_iva,
productos.impuesto_interno,
productos.precio1,
productos.precio2,
productos.precio3,
productos.fraccionado,
productos.rg5329_23,
productos.activo,
productos.texto_panel,
productos.stock,
productos.stock_minimo,
productos.stock_pedido,
productos.iibb,
productos.codigo_barra2,
productos.oferta,
productos.margen_ganancia,
productos.publicado_web,
productos.unidad_id,
productos.empresa_id,
productos.favorito,
productos.tipo_impuesto_interno
FROM
productos where empresa_id = 33");
$stmt->execute();
$productos_local = $stmt->fetchAll(PDO::FETCH_ASSOC);


//comprobar si el id de la tabla productos_remota existe en la tabla productos_local, si es asi actualiza precio1,precio2, precio3
foreach ($productos_remota as $producto_remota) {
    $existe = false;
    foreach ($productos_local as $producto_local) {
        if ($producto_remota['id'] == $producto_local['id']) {
            $existe = true;
            if ($producto_remota['precio1'] != $producto_local['precio1'] || $producto_remota['precio2'] != $producto_local['precio2'] || $producto_remota['precio3'] != $producto_local['precio3']) {
                $stmt = $con_remota->prepare("UPDATE productos SET precio1 = :precio1, precio2 = :precio2, precio3 = :precio3 WHERE id = :id");
                $stmt->bindParam(':precio1', $producto_remota['precio1']);
                $stmt->bindParam(':precio2', $producto_remota['precio2']);
                $stmt->bindParam(':precio3', $producto_remota['precio3']);
                $stmt->bindParam(':id', $producto_remota['id']);
                $stmt->execute();
            }
        }
    }
    if (!$existe) {
        $stmt = $con_remota->prepare("INSERT INTO productos (id,codigo,descripcion,descripcion_ampliada,familia_id,subfamilia_id,agrupacion_id,marca_id,codigo_barra,proveedor_id,fecha_alta,fecha_actualizacion,articulo_activado,tipo_id,producto_balanza,precio_costo,moneda_id,tasa_iva,tasa_iva_id,incluye_iva,impuesto_interno,precio1,precio2,precio3,fraccionado,rg5329_23,activo,texto_panel,stock,stock_minimo,stock_pedido,iibb,codigo_barra2,oferta,margen_ganancia,publicado_web,unidad_id,empresa_id,favorito,tipo_impuesto_interno) VALUES (:id,:codigo,:descripcion,:descripcion_ampliada,:familia_id,:subfamilia_id,:agrupacion_id,:marca_id,:codigo_barra,:proveedor_id,:fecha_alta,:fecha_actualizacion,:articulo_activado,:tipo_id,:producto_balanza,:precio_costo,:moneda_id,:tasa_iva,:tasa_iva_id,:incluye_iva,:impuesto_interno,:precio1,:precio2,:precio3,:fraccionado,:rg5329_23,:activo,:texto_panel,:stock,:stock_minimo,:stock_pedido,:iibb,:codigo_barra2,:oferta,:margen_ganancia,:publicado_web,:unidad_id,:empresa_id,:favorito,:tipo_impuesto_interno)");
        $stmt->bindParam(':id', $producto_remota['id']);
        $stmt->bindParam(':codigo', $producto_remota['codigo']);
        $stmt->bindParam(':descripcion', $producto_remota['descripcion']);
        $stmt->bindParam(':descripcion_ampliada', $producto_remota['descripcion_ampliada']);
        $stmt->bindParam(':familia_id', $producto_remota['familia_id']);
        $stmt->bindParam(':subfamilia_id', $producto_remota['subfamilia_id']);
        $stmt->bindParam(':agrupacion_id', $producto_remota['agrupacion_id']);
        $stmt->bindParam(':marca_id', $producto_remota['marca_id']);
        $stmt->bindParam(':codigo_barra', $producto_remota['codigo_barra']);
        $stmt->bindParam(':proveedor_id', $producto_remota['proveedor_id']);
        $stmt->bindParam(':fecha_alta', $producto_remota['fecha_alta']);
        $stmt->bindParam(':fecha_actualizacion', $producto_remota['fecha_actualizacion']);
        $stmt->bindParam(':articulo_activado', $producto_remota['articulo_activado']);
        $stmt->bindParam(':tipo_id', $producto_remota['tipo_id']);
        $stmt->bindParam(':producto_balanza', $producto_remota['producto_balanza']);
        $stmt->bindParam(':precio_costo', $producto_remota['precio_costo']);
        $stmt->bindParam(':moneda_id', $producto_remota['moneda_id']);
        $stmt->bindParam(':tasa_iva', $producto_remota['tasa_iva']);
        $stmt->bindParam(':tasa_iva_id', $producto_remota['tasa_iva_id']);
        $stmt->bindParam(':incluye_iva', $producto_remota['incluye_iva']);
        $stmt->bindParam(':impuesto_interno', $producto_remota['impuesto_interno']);
        $stmt->bindParam(':precio1', $producto_remota['precio1']);
        $stmt->bindParam(':precio2', $producto_remota['precio2']);
        $stmt->bindParam(':precio3', $producto_remota['precio3']);
        $stmt->bindParam(':fraccionado', $producto_remota['fraccionado']);
        $stmt->bindParam(':rg5329_23', $producto_remota['rg5329_23']);
        $stmt->bindParam(':activo', $producto_remota['activo']);
        $stmt->bindParam(':texto_panel', $producto_remota['texto_panel']);
        $stmt->bindParam(':stock', $producto_remota['stock']);
        $stmt->bindParam(':stock_minimo', $producto_remota['stock_minimo']);
        $stmt->bindParam(':stock_pedido', $producto_remota['stock_pedido']);
        $stmt->bindParam(':iibb', $producto_remota['iibb']);
        $stmt->bindParam(':codigo_barra2', $producto_remota['codigo_barra2']);
        $stmt->bindParam(':oferta', $producto_remota['oferta']);
        $stmt->bindParam(':margen_ganancia', $producto_remota['margen_ganancia']);
        $stmt->bindParam(':publicado_web', $producto_remota['publicado_web']);
        $stmt->bindParam(':unidad_id', $producto_remota['unidad_id']);
        $stmt->bindParam(':empresa_id', $producto_remota['empresa_id']);
        $stmt->bindParam(':favorito', $producto_remota['favorito']);
        $stmt->bindParam(':tipo_impuesto_interno', $producto_remota['tipo_impuesto_interno']);
        $stmt->execute();
    }
}








