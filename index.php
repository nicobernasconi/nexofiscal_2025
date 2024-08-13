<?php include("includes/session_parameters.php");


$empresa_nombre = $_SESSION['empresa_nombre'];
$empresa_direccion = $_SESSION['direccion'];
$sucursal_nombre = $_SESSION['sucursal_nombre'];
$sucursal_direccion = $_SESSION['direccion'];
$sucursal_id = $_SESSION['sucursal_id'];
$vendedor_nombre = $_SESSION['vendedor_nombre'];
$vendedor_id = $_SESSION['vendedor_id'];
$tipo_comprobante_imprimir = $_SESSION['tipo_comprobante_imprimir'] ?? 1;
//obtener "codigo_barra_inicio,codigo_barra_id_long,codigo_barra_payload_type
$codigo_barra_inicio = $_SESSION['codigos_barras_inicio'] ?? null;
$codigo_barra_id_long = $_SESSION['codigos_barras_id_long'] ?? null;
$codigo_barra_payload_type = $_SESSION['codigos_barras_payload_type'] ?? null;
$codigo_barra_payload_int = $_SESSION['codigos_barras_payload_int'] ?? null;
$codigo_barra_long = $_SESSION['codigos_barras_long'] ?? null;
$tipo_iva = $_SESSION['tipo_iva'] ?? null;
if ($tipo_iva == 'INSCRIPTO') {
    $tipo_factura = 6;
} else {
    $tipo_factura = 11;
}
$venta_rapida= $_SESSION['venta_rapida'] ?? 0;
$imprimir= $_SESSION['imprimir'] ?? 1;





?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexoFiscal</title>
    <!-- Vinculación del archivo CSS -->
    <link rel="stylesheet" href="css/libs/jquery.minicolors.css">
    <link rel="stylesheet" href="css/estilos.css">
    <!-- Iconos -->
    <?php include("estilos.php");?>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="img/icono.png">

</head>

<body>

    <?php include("header.php");


    ?>
    <section class="datoscontribuyente">
        <h1><?php echo "$empresa_nombre ($sucursal_nombre)"; ?></h1>
        <input type="hidden" id="nombre_tienda" name="nombre_tienda" value="<?php echo $_SESSION['empresa_nombre']; ?>">
        <p><?php echo $sucursal_direccion; ?></p>
        <input type="hidden" id="direccion_tienda" name="direccion_tienda" value="<?php echo $sucursal_direccion; ?>">
        <p>
            <strong id="usuario_nombre"><?php echo $_SESSION['usuario_nombre']; ?></strong> ->
            Vendedor: <strong id="vendedor_nombre"><?php echo $vendedor_nombre ?></strong>
        </p>
        <input type="hidden" id="vendedor_id" name="vendedor_id" value="<?php echo $vendedor_id; ?>">
        <input type="hidden" id="tipo_iva" name="tipo_iva" value="<?php echo $tipo_iva; ?>">
        <input type="hidden" id="cuit" name="cuit" value="<?php echo $_SESSION['cuit']; ?>">
        <input type="hidden" id="punto_de_venta" name="punto_de_venta" value="<?php echo $_SESSION['punto_venta']; ?>">
        <input type="hidden" id="venta_rapida" name="venta_rapida" value="<?php echo $venta_rapida; ?>">
        <input type="hidden" id="imprimir" name="imprimir" value="<?php echo $imprimir; ?>">
        <input type="hidden" id="tipo_comprobante_imprimir" name="tipo_comprobante_imprimir" value="<?php echo $tipo_comprobante_imprimir; ?>">

    </section>
    <section class="datoscliente">
        <p id="titdc">Emitir comprobantes a:</p>
        <?php if (in_array('listar', $permisos_asignados['clientes'])) { ?>
            <a class="btncliente" id="buscarCliente" Title="Seleccionar el cliente" href="#"><i class="fas fa-file-alt"></i> Seleccionar Cliente</a>
        <?php } else { ?>
            <a class="btncliente" style="pointer-events: none; cursor: default;background-color: #c2c2c2;" Title="Seleccionar el cliente" href="#"><i class="fas fa-file-alt"></i> Seleccionar Cliente</a>
        <?php } ?>
        <?php if (in_array('crear', $permisos_asignados['clientes'])) { ?>
            <a class="btncliente" id="crearCliente" Title="Agregar cliente nuevo" href="#"><i class="fas fa-plus"></i> Cliente Nuevo</a>
        <?php } else { ?>
            <a class="btncliente" style="pointer-events: none; cursor: default;background-color: #c2c2c2;" Title="Agregar cliente nuevo" href="#"><i class="fas fa-plus"></i> Cliente Nuevo</a>
        <?php } ?>
        <input type="hidden" id="cliente_id" name="cliente_id">
        <input type="hidden" id="tipo_de_factura" name="tipo_de_factura" value="<?php echo $tipo_factura; ?>">
        <input type="hidden" id="concepto" name="concepto" value="1">
        <input type="hidden" id="tipo_de_documento" name="tipo_de_documento" value=96>
        <input type="hidden" id="numero_de_documento" name="numero_de_documento" value=111111>



        <p id="clientedc">1 / Ocacional</p>
        <p id="cuitdc">CUIT: / Ocacional</p>
        <p id="domiciliodc">Domicilio: / Ocacional</p>

    </section>
    <section class="busquedaproducto">

        <input type="search" class="form-select " id="searchProduct">
        <input type="hidden" id="codigo_barra_inicio" value="<?php echo is_null($codigo_barra_inicio) ? '' : $codigo_barra_inicio; ?>">
        <input type="hidden" id="codigo_barra_id_long" value="<?php echo is_null($codigo_barra_id_long) ? '' : $codigo_barra_id_long; ?>">
        <input type="hidden" id="codigo_barra_payload_type" value="<?php echo is_null($codigo_barra_payload_type) ? '' : $codigo_barra_payload_type; ?>">
        <input type="hidden" id="codigo_barra_payload_int" value="<?php echo is_null($codigo_barra_payload_int) ? '' : $codigo_barra_payload_int; ?>">
        <input type="hidden" id="codigo_barra_long" value="<?php echo is_null($codigo_barra_long) ? '' : $codigo_barra_long; ?>">


        <div class="btnproduct">
            <ul>
                <?php if (in_array('modificar', $permisos_asignados['productos'])) { ?>
                    <li>
                        <a href="#" id="btnEditarProducto" title="Editar Producto"><i class="fas fa-pencil-alt"></i></a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;" title="Editar Producto"><i class="fas fa-pencil-alt"></i></a>
                    </li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['productos'])) { ?>
                    <li>
                        <a href="#" id="btnBuscarProducto" title="Buscar Producto"><i class="fas fa-question"></i></a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;" title="Buscar Producto"><i class="fas fa-question"></i></a>
                    </li>
                <?php } ?>
                <?php if (in_array('crear', $permisos_asignados['productos'])) { ?>
                    <li>
                        <a href="#" id="crearProducto" title="Agregar Producto"><i class="fas fa-plus"></i></a>
                    </li>
                <?php } else { ?>
                    <li>
                        <a href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;" title="Agregar Producto"><i class="fas fa-plus"></i></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </section>
    <?php include("tabla_productos.php"); ?>

    <aside class="sidebar">
        <div class="controlessidebar">
            <a class="activo" id="btnTabFavoritos" href="#">PLU Directos</a>
            <a href="#" id="btnTabVentas">Comprobantes</a>
            <a href="#" id="btnTabStock">Stock</a>
        </div>
        <div class="clima">
            <!-- www.tutiempo.net - Ancho:220px - Alto:95px -->
            <div id="TT_FCJAbhtBd2j7d8sAKAqjzDjzj5aALEAFrdEtEsyIqEDI3IGIm">El tiempo - Tutiempo.net</div>
            <script type="text/javascript" src="https://www.tutiempo.net/s-widget/l_FCJAbhtBd2j7d8sAKAqjzDjzj5aALEAFrdEtEsyIqEDI3IGIm"></script>
        </div>
        <div class="clima">
            <div id="time" style="text-align: right;"></div>
            <div id="date" style="text-align: right;"></div>
        </div>
        <div class="pludirectos" id="tabs">
            <?php include("productos_favoritos.php"); ?>
        </div>
        <div class="indicadorCarga" id="indicadorCarga">Cargando...</div>
    </aside>
    <?php include("botonera_inferior.php"); ?>
    <!-- modals-->

<?php include("modals/menu.php"); ?>
    <?php include("modals.php"); ?>
</body>
<?php include("scripts_js.php"); ?>

<!-- Tu código JavaScript personalizado -->
<script src="js/index.js"></script>

</html>