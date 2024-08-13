<?php

$punto_de_venta = 1;
$tipo_de_nota = 3; // 3 = Nota de Crédito A
$last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_nota);
$punto_factura_asociada = 1;
$tipo_factura_asociada = 1; // 1 = Factura A
$numero_factura_asociada = 1;
$concepto = 1;
$tipo_de_documento = 80;
$numero_de_documento = 33693450239;
$numero_de_nota = $last_voucher+1;
$fecha = date('Y-m-d');
$importe_gravado = 100;
$importe_exento_iva = 0;
$importe_iva = 21;
if ($concepto === 2 || $concepto === 3) {
	$fecha_servicio_desde = intval(date('Ymd'));
	$fecha_servicio_hasta = intval(date('Ymd'));
	$fecha_vencimiento_pago = intval(date('Ymd'));
}
else {
	$fecha_servicio_desde = null;
	$fecha_servicio_hasta = null;
	$fecha_vencimiento_pago = null;
}

$data = array(
	'CantReg' 	=> 1, // Cantidad de Notas de Crédito a registrar
	'PtoVta' 	=> $punto_de_venta,
	'CbteTipo' 	=> $tipo_de_nota, 
	'Concepto' 	=> $concepto,
	'DocTipo' 	=> $tipo_de_documento,
	'DocNro' 	=> $numero_de_documento,
	'CbteDesde' => $numero_de_nota,
	'CbteHasta' => $numero_de_nota,
	'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
	'FchServDesde'  => $fecha_servicio_desde,
	'FchServHasta'  => $fecha_servicio_hasta,
	'FchVtoPago'    => $fecha_vencimiento_pago,
	'ImpTotal' 	=> $importe_gravado + $importe_iva + $importe_exento_iva,
	'ImpTotConc'=> 0, // Importe neto no gravado
	'ImpNeto' 	=> $importe_gravado,
	'ImpOpEx' 	=> $importe_exento_iva,
	'ImpIVA' 	=> $importe_iva,
	'ImpTrib' 	=> 0, //Importe total de tributos
	'MonId' 	=> 'PES', //Tipo de moneda usada en la Nota de Crédito ('PES' = pesos argentinos) 
	'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)
	'CbtesAsoc' => array( //Factura asociada
		array(
			'Tipo' 		=> $tipo_factura_asociada,
			'PtoVta' 	=> $punto_factura_asociada,
			'Nro' 		=> $numero_factura_asociada,
		)
	),
	'Iva' 		=> array( // Alícuotas asociadas a la Nota de Crédito
		array(
			'Id' 		=> 5, // Id del tipo de IVA (5 = 21%)
			'BaseImp' 	=> $importe_gravado,
			'Importe' 	=> $importe_iva 
		)
	), 
);


$res = $afip->ElectronicBilling->CreateVoucher($data);


?>