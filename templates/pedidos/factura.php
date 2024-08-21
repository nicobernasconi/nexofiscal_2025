<?php
$template_data = array(
    'razon_social' => $_POST['razon_social'],
    'direccion' => $_POST['direccion'],
    'cuit' => $_POST['cuit'],
    'tipo_contribuyente' => $_POST['tipo_contribuyente'],
    'iibb' => $_POST['iibb'],
    'inicio_actividad' => $_POST['inicio_actividad'],
    'tipo_factura_nombre' => $_POST['tipo_factura']??'X',
    'tipo_factura' => $_POST['tipo_factura']??'1',
    'codigo' => $_POST['tipo_factura']??'XXXX',
    'punto_venta' => $_POST['punto_venta'],
    'numero' => $_POST['numero'],
    'fecha' => $_POST['fecha'],
    'concepto' => $_POST['concepto']??'',
    'productos' => $_POST['productos'],
    'iva' => $_POST['total_iva'],
    'total' => $_POST['total'],

    'cliente_nombre' => $_POST['cliente_nombre'],
    'cliente_direccion' => $_POST['direccion_cliente'],
    'cliente_cuit' => $_POST['cliente_cuit']??'1',
    'cliente_condicion_iva' => $_POST['cliente_condicion_iva']??'CONSUMIDOR FINAL',
);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Factura</title>
    <style type="text/css">
        * {
            box-sizing: border-box;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .bill-container {
            width: 750px;
            position: relative;
            left: 0;
            right: 0;
            margin: auto;
            border-collapse: collapse;
            font-family: sans-serif;
            font-size: 13px;
        }

        .bill-emitter-row td {
            width: 50%;
            border-bottom: 1px solid;
            padding-top: 10px;
            padding-left: 10px;
            vertical-align: top;
        }

        .bill-emitter-row {
            position: relative;
        }

        .bill-emitter-row td:nth-child(2) {
            padding-left: 60px;
        }

        .bill-emitter-row td:nth-child(1) {
            padding-right: 60px;
        }

        .bill-type {
            border: 1px solid;
            border-top: 1px solid;
            border-bottom: 1px solid;
            margin-right: -30px;
            background: white;
            width: 60px;
            height: 50px;
            position: absolute;
            left: 0;
            right: 0;
            top: -1px;
            margin: auto;
            text-align: center;
            font-size: 40px;
            font-weight: 600;
        }

        .text-lg {
            font-size: 30px;
        }

        .text-center {
            text-align: center;
        }

        .col-2 {
            width: 16.66666667%;
            float: left;
        }

        .col-3 {
            width: 25%;
            float: left;
        }

        .col-4 {
            width: 33.3333333%;
            float: left;
        }

        .col-5 {
            width: 41.66666667%;
            float: left;
        }

        .col-6 {
            width: 50%;
            float: left;
        }

        .col-8 {
            width: 66.66666667%;
            float: left;
        }

        .col-10 {
            width: 83.33333333%;
            float: left;
        }

        .row {
            overflow: hidden;
        }

        .margin-b-0 {
            margin-bottom: 0px;
        }

        .bill-row td {
            padding-top: 5px
        }

        .bill-row td>div {
            border-top: 1px solid;
            border-bottom: 1px solid;
            margin: 0 -1px 0 -2px;
            padding: 0 10px 13px 10px;
        }

        .row-details table {
            border-collapse: collapse;
            width: 100%;
        }

        .row-details td>div,
        .row-qrcode td>div {
            border: 0;
            margin: 0 -1px 0 -2px;
            padding: 0;
        }

        .row-details table td {
            padding: 5px;
        }

        .row-details table tr:nth-child(1) {
            border-top: 1px solid;
            border-bottom: 1px solid;
            background: #c0c0c0;
            font-weight: bold;
            text-align: center;
        }

        .row-details table tr+tr {
            border-top: 1px solid #c0c0c0;
        }

        .text-right {
            text-align: right;
        }

        .margin-b-10 {
            margin-bottom: 10px;
        }

        .total-row td>div {
            border-width: 2px;
        }

        .row-qrcode td {
            padding: 10px;
        }

        #qrcode {
            width: 50%
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .footer .total {
            width: 100%;
            border-top: 2px solid;
            margin-top: 20px;
            padding-top: 10px;
        }

        .footer .qrcode,
        .footer .signature {
            width: 48%;
            float: left;
            text-align: center;
        }

        .footer .qrcode {
            float: left;
        }

        .footer .signature {
            float: right;
        }

        .clear {
            clear: both;
        }
    </style>
</head>

<body>
    <table class="bill-container">
        <tr class="bill-emitter-row">
            <td>
                <div class="bill-type">
                    <?php echo 'X';?>
                </div>
                <div class="text-lg text-center">
                    <?php echo $template_data['razon_social']; ?>
                </div>
                <p><strong>Razón social:</strong> <?php echo $template_data['razon_social']; ?></p>
                <p><strong>Domicilio Comercial:</strong> <?php echo $template_data['direccion']; ?></p>
                <p><strong>Condición Frente al IVA:</strong> <?php echo $template_data['tipo_contribuyente']; ?></p>
            </td>
            <td>
                <div>
                    <div class="text-lg">
                        <strong>PEDIDO</strong>
                    </div>
                    <div class="row">
                        <p class="col-6 margin-b-0">
                            <strong>Punto de Venta: <?php echo str_pad($template_data['punto_venta'], 4, '0', STR_PAD_LEFT); ?></strong>
                        </p>
                        <p class="col-6 margin-b-0">
                            <strong>Comp. Nro: <?php echo str_pad($template_data['numero'], 8, '0', STR_PAD_LEFT); ?></strong>
                        </p>
                    </div>
                    <p><strong>Fecha de Emisión:</strong> <?php echo date('d-m-Y', strtotime($template_data['fecha'])); ?></p>
                    <p><strong>CUIT:</strong> <?php echo $template_data['cuit']; ?></p>
                    <p><strong>Ingresos Brutos:</strong> <?php echo $template_data['iibb']; ?></p>
                    <p><strong>Fecha de Inicio de Actividades:</strong> <?php echo $template_data['inicio_actividad']; ?></p>
                </div>
            </td>
        </tr>

        <tr class="bill-row">
            <td colspan="2">
                <div>
                    <div class="row">
                        <p class="col-4 margin-b-0">
                            <strong>CUIL/CUIT: </strong><?php echo $template_data['cliente_cuit']; ?>
                        </p>
                        <p class="col-8 margin-b-0">
                            <strong>Apellido y Nombre / Razón social: </strong><?php echo $template_data['cliente_nombre']; ?>
                        </p>
                    </div>
                    <div class="row">
                        <p class="col-6 margin-b-0">
                            <strong>Condición Frente al IVA: </strong><?php echo $template_data['cliente_condicion_iva']; ?>
                        </p>
                        <p class="col-6 margin-b-0">
                            <strong>Domicilio: </strong><?php echo $template_data['cliente_direccion']; ?>
                        </p>
                    </div>
                </div>
            </td>
        </tr>
        <tr class="bill-row row-details">
            <td colspan="2">
                <div>
                    <table>
                        <tr>
                            <th>Producto / Servicio</th>
                            <th>Cantidad</th>
                            <th>Precio Unit.</th>
                            <?php if ($template_data['tipo_factura'] == 1) { ?>
                            <th>% IVA.</th>
                            <th>Imp. IVA.</th>
                            <?php } ?>
                            <th>Subtotal</th>
                        </tr>
                        <?php
                        $total_iva = 0;
                        foreach ($template_data['productos'] as $producto) {
                            $total_iva += round(($producto['total_linea'])-(($producto['total_linea']) /(1+($producto['tasa_iva']))), 2);
                        ?>
                            <tr>
                                <td><?php echo $producto['descripcion']; ?></td>
                                <td><?php echo $producto['cantidad']; ?></td>
                                <td>$<?php echo number_format($producto['precio'], 2, ',', '.'); ?></td>
                                <?php if ($template_data['tipo_factura'] == 1) { ?>
                                <td><?php echo $producto['tasa_iva'] * 100; ?>%</td>
                                <td>$<?php 
                                $total_iva_linea=round(($producto['total_linea'])-(($producto['total_linea']) /(1+($producto['tasa_iva']))), 2);
                                echo number_format($total_iva_linea, 2, ',', '.'); ?>
                                </td>
                                <?php } ?>
                                <td>$<?php echo number_format($producto['total_linea'], 2, ',', '.'); ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <div class="total row text-right">
            <?php if ($template_data['tipo_factura'] == 1) { ?>
            <div class="col-10 margin-b-0">
                <strong>Subtotal: $</strong>
                <?php echo number_format($template_data['total'] - $total_iva, 2, ',', '.'); ?>
            </div>
            <div class="col-10 margin-b-0">
                <strong>Total IVA: $</strong>
                <?php echo number_format($total_iva, 2, ',', '.'); ?>
            </div> 
            <?php } ?>
            <div class="col-10 margin-b-0">
                <strong>Total: $</strong>
                <?php echo  number_format($template_data['total'], 2, ',', '.'); ?>
        </div>
        <div class="clear"></div>
        <div class="col-10 margin-b-0 text-center">
            <p>Este comprobante no tiene validez fiscal.</p>
        </div>
        <div class="clear"></div>
    </div>
</body>

</html>
