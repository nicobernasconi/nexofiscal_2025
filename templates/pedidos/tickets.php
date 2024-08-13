<!DOCTYPE html>
<html>

<head>
    <title>Ticket</title>
    <style type="text/css">
        * {
            box-sizing: border-box;
            -webkit-user-select: none;
            /* Chrome, Opera, Safari */
            -moz-user-select: none;
            /* Firefox 2+ */
            -ms-user-select: none;
            /* IE 10+ */
            user-select: none;
            /* Standard syntax */
        }

        .bill-container {
            border-collapse: collapse;
            max-width: 8cm;
            position: absolute;
            left: 0;
            right: 0;
            margin: auto;
            border-collapse: collapse;
            font-family: monospace;
            font-size: 10px;
        }

        .text-lg {
            font-size: 18px;
        }

        .text-center {
            text-align: center;
        }

        #qrcode {
            width: 75%
        }

        p {
            margin: 2px 0;
        }

        table table {
            width: 100%;
        }

        table table tr td:last-child {
            text-align: right;
        }

        .border-top {
            border-top: 1px dashed;
        }

        .padding-b-3 {
            padding-bottom: 3px;
        }

        .padding-t-3 {
            padding-top: 3px;
        }
    </style>
</head>

<body>
    <table class="bill-container">
        <tr>
            <td class="padding-b-3">
                <p>Razón social: <?php echo $_POST['razon_social']; ?></p>
                <p>Direccion: <?php echo $_POST['direccion']; ?></p>
                <p>C.U.I.T.: <?php echo $_POST['cuit']; ?></p>
                <p>Tipo de contribuyente: <?php echo $_POST['tipo_contribuyente']; ?></p>
                <p>IIBB: <?php echo $_POST['iibb']; ?></p>
                <p>Inicio de actividad: <?php $date = date_create($_POST['inicio_actividad']);
                                        echo date_format($date, 'd-m-Y'); ?></p>
            </td>
        </tr>
        <tr>
            <td class="border-top padding-t-3 padding-b-3">

                <p class="text-center text-lg">PEDIDO </p>
                <p class="text-center">Codigo: X</p>
                <p>P.V: <?php echo str_pad($_POST['punto_venta'], 5, '0', STR_PAD_LEFT); ?></p>
                <p>Nro: <?php echo str_pad($_POST['numero'], 8, '0', STR_PAD_LEFT); ?></p>
                <p>Fecha: <?php $date = date_create($_POST['fecha']);
                            echo date_format($date, 'd-m-Y'); ?></p>
                <p>Concepto: 0</p>
            </td>
        </tr>
        <tr>
            <td class="border-top padding-t-3 padding-b-3">
                <p><?php echo $_POST['destinatario']; ?></p>
            </td>
        </tr>
        <tr>
            <td class="border-top padding-t-3 padding-b-3">
                <div>
                    <table>
                        <?php
                        $productos = $_POST['productos'];
                        foreach ($productos as $producto) : ?>
                            <tr>
                                <td><?php echo $producto['cantidad']; ?></td>
                                <td><?php echo $producto['descripcion']; ?></td>
                                <td><?php echo ($producto['tasa_iva'] * 100); ?>%</td>
                                <td>$<?php echo $producto['precio']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </td>
        </tr>
        <tr>

                <td class="border-top padding-t-3 padding-b-3">
                    <div>
                        <table>
                            <?php
                            $iva = $_POST['iva'];
                            foreach ($iva as $value) : ?>
                                <tr>
                                    <td>TOTAL NETO SIN IVA</td>
                                    <td>$<?php echo $value['BaseImp'] - $value['Importe'];  ?></td>
                                </tr>
                                <tr>
                                    <td>IVA <?php echo ($value['Id'] == 5) ? '21%' : (($value['Id'] == 4) ? '10.5%' : '0%'); ?></td>
                                    <td>$<?php echo $value['Importe']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </td>

        </tr>
        <tr>
            <td class="border-top padding-t-3 padding-b-3">
                <div>
                    <table>
                        <tr>
                            <td>TOTAL</td>
                            <td>$<?php echo $_POST['total']; ?></td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
        <tr>
            
        </tr>
        <tr class="text-center">
            <td>
                <p>Este comprobante no tiene validez fiscal</p>

            </td>
        </tr>
    </table>
</body>

</html>