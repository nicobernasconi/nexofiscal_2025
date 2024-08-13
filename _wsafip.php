<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;





require 'vendor/autoload.php';

include("includes/session_parameters.php");




// Datos de la factura

$punto_de_venta = $_POST['punto_de_venta'];
$tipo_factura = $_POST['tipo_factura'];
$concepto = $_POST['concepto'];
$tipo_de_documento = $_POST['tipo_de_documento'];
$numero_de_documento = $_POST['numero_de_documento'];
$razon_social = $_SESSION['empresa_razon_social'];
$direccion = $_SESSION['direccion'];
$iibb = $_SESSION['iibb'];
$inicio_actividades = $_SESSION['fecha_inicio_actividades'];
$tipo_contribuyente = ($_SESSION['tipo_iva'] == 1) ? 'RESPONSABLE INSCRIPTO' : 'MONOTRIBUTO';
$tipo_factura_nombre = ($_POST['tipo_factura'] == 1) ? 'FACTURA A' : (($_POST['tipo_factura'] == 6) ? 'FACTURA B' : 'FACTURA C');

$destinatario = isset($_POST['destinatario']) ? $_POST['destinatario'] : '';
// Certificado (Puede estar guardado en archivos, DB, etc)
$certAfip = $_SESSION['cert'];
// Key (Puede estar guardado en archivos, DB, etc)
$keyAfip = $_SESSION['key'];
$cuit = $_POST['cuit'];
// Obtener el array de productos enviado por POST
$productos = json_decode($_POST['productos'], true);
$promociones = json_decode($_POST['promociones'], true);
// comprobar si los datos para facturar fueron cargados


if (
	empty($punto_de_venta) ||
	empty($tipo_factura) ||
	empty($concepto) ||
	empty($tipo_de_documento) ||
	empty($razon_social) ||
	empty($direccion) ||
	empty($iibb) ||
	empty($inicio_actividades) ||
	empty($tipo_contribuyente) ||
	empty($tipo_factura_nombre) ||
	empty($certAfip) ||
	empty($keyAfip) ||
	empty($cuit) ||
	empty($productos)
) {
	$missing_fields = [];
	if (empty($punto_de_venta)) {
		$missing_fields[] = 'Punto de venta(Obligatorio)';
	}
	if (empty($tipo_factura)) {
		$missing_fields[] = 'Tipo de factura';
	}
	if (empty($concepto)) {
		$missing_fields[] = 'Concepto';
	}
	if (empty($tipo_de_documento)) {
		$missing_fields[] = 'Tipo de documento';
	}
	if (empty($numero_de_documento)) {
		$missing_fields[] = 'Nro de documento (Opcional)';
	}
	if (empty($razon_social)) {
		$missing_fields[] = 'Razon social(Obligatorio)';
	}
	if (empty($direccion)) {
		$missing_fields[] = 'Direccion(Obligatorio)';
	}
	if (empty($iibb)) {
		$missing_fields[] = 'IIBB(Obligatorio)';
	}
	if (empty($inicio_actividades)) {
		$missing_fields[] = 'Inicio de actividades(Obligatorio)';
	}
	if (empty($tipo_contribuyente)) {
		$missing_fields[] = 'Tipo de contribuyente(Obligatorio)';
	}
	if (empty($tipo_factura_nombre)) {
		$missing_fields[] = 'Factura(Obligatorio)';
	}
	if (empty($cert)) {
		$missing_fields[] = 'Certificado(Obligatorio)';
	}
	if (empty($key)) {
		$missing_fields[] = 'Clave privada(Obligatorio)';
	}
	if (empty($cuit)) {
		$missing_fields[] = 'CUIT(Obligatorio)';
	}
	if (empty($productos)) {
		$missing_fields[] = 'Productos(Obligatorio)';
	}

	header('Content-Type: application/json');
	echo json_encode(array(
		'status' => '400', // Bad Request
		'error' => 'Faltan datos para emitir la factura.<br>Comuniquese con su distribuidor. <br>Falta cargar: <br>' . implode('<br>', $missing_fields)

	));
	exit;
}



//sumar todos los discount de  promociones
$total_discount = 0;
foreach ($promociones as $promocion) {
	$total_discount += $promocion['discount'];
}

//asignar desccuento a cada producto
foreach ($productos as $key => $producto) {
	$productos[$key]['discount'] = $total_discount;
}


//mapear los productos "producto_id, descripcion, cantidad, precio, tasa_iva, descuento, total_linea
$productos = array_map(function ($item) {
	return [
		'producto_id' => $item['id'],
		'descripcion' => $item['name'],
		'cantidad' => doubleval($item['quantity']),
		'precio' => doubleval($item['price']),
		'tasa_iva' => doubleval($item['tasa_iva']),
		'discount' => doubleval($item['discount']),
		'total_linea' => $item['price'] * $item['quantity']
	];
}, $productos);

$total = 0;


$unique_tasa_iva = array_unique(array_column($productos, 'tasa_iva'));

//calcular iva yt neto por cada tasa 
foreach ($unique_tasa_iva as $tasa) {
	$importe_gravado = 0;
	$importe_iva = 0;
	$importe_sin_iva = 0;
	$importe_exento_iva = 0;
	$totales_por_tasa["$tasa"] = array();
	foreach ($productos as $producto) {
		if ($producto['tasa_iva'] == $tasa) {
			$importe_gravado +=($producto['total_linea'] / (1+($producto['tasa_iva'])));
			$importe_iva +=  ($producto['total_linea'] - (($producto['total_linea'] / (1+($producto['tasa_iva'])))));
			$importe_sin_iva += ($producto['total_linea'] / (1+($producto['tasa_iva'])));
		}
	}
	//generar la key con la tasa obtener el id de la tasa_id :0.21=>5 ; 0.105=>4; 0=>3 la tasa es el key

	switch ($tasa) {
		case 0.21:
			$tasa_id = 5;
			break;
		case 0.105:
			$tasa_id = 4;
			break;
		case 0:
			$tasa_id = 3;
			break;
	}

	$totales[] = array(
		'tasa' => $tasa,
		'tasa_id' => $tasa_id,
		'importe_gravado' => round($importe_gravado, 2),
		'importe_iva' => round($importe_iva, 2),
		'importe_sin_iva' => round($importe_sin_iva, 2),
		'importe_exento_iva' => round($importe_exento_iva, 2)
	);
}

//calcular total de iva
$importe_iva = 0;
foreach ($totales as $tasa) {
	$importe_iva += ($tasa['importe_iva']);
}


//calcular total de gravado
$importe_gravado = 0;
foreach ($totales as $tasa) {
	$importe_gravado += ($tasa['importe_gravado']);
}


//calcular total de exento iva
$importe_exento_iva = 0;
$importe_iva_21 = 0;
$importe_iva_105 = 0;
$importe_iva_0 = 0;
$no_gravado_iva_21 = 0;
$no_gravado_iva_105 = 0;
$no_gravado_iva_0 = 0;


//crear array de iva para enviar a afip generer un case por cada tasa
$iva = array();
foreach ($totales as $tasa) {
	if ($tasa['tasa'] == 0.21) {
		$importe_iva_21 = $tasa['importe_iva'];
		$no_gravado_iva_21 = $tasa['importe_sin_iva'];
	}
	if ($tasa['tasa'] == 0.105) {
		$importe_iva_105 = $tasa['importe_iva'];
		$no_gravado_iva_105 = $tasa['importe_sin_iva'];
	}
	if ($tasa['tasa'] == 0) {
		$importe_iva_0 = $tasa['importe_iva'];
		$no_gravado_iva_0 = $tasa['importe_sin_iva'];
	}
	$iva[] = array(
		'Id' => $tasa['tasa_id'],
		'BaseImp' => round(($tasa['importe_gravado']), 2),
		'Importe' => round($tasa['importe_iva'], 2)
	);
}



// Tu CUIT
$tax_id = $cuit;

try {
	$afip = new Afip(array(
		'CUIT' => $tax_id,
		'cert' => $certAfip,
		'key' => $keyAfip,
		'access_token' =>$apiSDKAfip ,
    	'production' => $apiSDKAfipProd

	));


	$last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_factura);

	$numero_de_factura = $last_voucher + 1;

	/**
	 * Fecha de la factura en formato aaaa-mm-dd (hasta 10 dias antes y 10 dias despues)
	 **/
	$fecha = date('Y-m-d');


	if ($concepto === 2 || $concepto === 3) {
		/**
		 * Fecha de inicio de servicio en formato aaaammdd
		 **/
		$fecha_servicio_desde = intval(date('Ymd'));

		/**
		 * Fecha de fin de servicio en formato aaaammdd
		 **/
		$fecha_servicio_hasta = intval(date('Ymd'));

		/**
		 * Fecha de vencimiento del pago en formato aaaammdd
		 **/
		$fecha_vencimiento_pago = intval(date('Ymd'));
	} else {
		$fecha_servicio_desde = null;
		$fecha_servicio_hasta = null;
		$fecha_vencimiento_pago = null;
	}

$importe_no_gravado = 0;


	$data = array(
		'CantReg' 	=> 1, // Cantidad de facturas a registrar
		'PtoVta' 	=> $punto_de_venta,
		'CbteTipo' 	=> $tipo_factura,
		'Concepto' 	=> $concepto,
		'DocTipo' 	=> $tipo_de_documento,
		'DocNro' 	=> $numero_de_documento,
		'CbteDesde' => $numero_de_factura,
		'CbteHasta' => $numero_de_factura,
		'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
		'FchServDesde'  => $fecha_servicio_desde,
		'FchServHasta'  => $fecha_servicio_hasta,
		'FchVtoPago'    => $fecha_vencimiento_pago,
		'ImpTotal' 	=> round(doubleval($importe_gravado + $importe_iva + $importe_exento_iva), 2),
		'ImpTotConc' => 0, // Importe neto no gravado
		'ImpNeto' 	=> round($importe_gravado, 2),
		'ImpOpEx' 	=> round($importe_exento_iva, 2), // Importe exento de IVA
		'ImpIVA' 	=> round($importe_iva, 2),
		'ImpTrib' 	=> 0, //Importe total de tributos
		'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
		'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
		'Iva' 		=> $iva, // Alícuotas asociadas a la factura
	);
if($tipo_factura==11){
	$data = array(
		'CantReg' 	=> 1, // Cantidad de facturas a registrar
		'PtoVta' 	=> $punto_de_venta,
		'CbteTipo' 	=> $tipo_factura, 
		'Concepto' 	=> $concepto,
		'DocTipo' 	=> $tipo_de_documento,
		'DocNro' 	=> $numero_de_documento,
		'CbteDesde' => $numero_de_factura,
		'CbteHasta' => $numero_de_factura,
		'CbteFch' 	=> intval(str_replace('-', '', $fecha)),
		'FchServDesde'  => $fecha_servicio_desde,
		'FchServHasta'  => $fecha_servicio_hasta,
		'FchVtoPago'    => $fecha_vencimiento_pago,
		'ImpTotal' 	=> $importe_gravado,
		'ImpTotConc'=> 0, // Importe neto no gravado
		'ImpNeto' 	=> $importe_gravado, // Importe neto
		'ImpOpEx' 	=> 0, // Importe exento al IVA
		'ImpIVA' 	=> 0, // Importe de IVA
		'ImpTrib' 	=> 0, //Importe total de tributos
		'MonId' 	=> 'PES', //Tipo de moneda usada en la factura ('PES' = pesos argentinos) 
		'MonCotiz' 	=> 1, // Cotización de la moneda usada (1 para pesos argentinos)  
	);
}

	$res = $afip->ElectronicBilling->CreateVoucher($data);

	$cae = $res['CAE'];
	$fecha_vencimiento = $res['CAEFchVto'];
} catch (\Throwable $th) {
	header('Content-Type: application/json');
	echo json_encode(array(
		'status' => '500', //Fecha de procesamiento
		'error' => $th->getMessage()
	));
	exit;
}


//generacion del QR
$datos_comprobante = array(
	'ver' => 1,
	'fecha' => $fecha,
	'cuit' => $cuit,
	'ptoVta' => $punto_de_venta,
	'tipoCmp' => $tipo_factura,
	'nroCmp' => $numero_de_factura,
	'importe' => round(doubleval($importe_no_gravado + $importe_iva + $importe_exento_iva), 2),
	'moneda' => 'PES',
	'ctz' => 1,
	'tipoDocRec' => $tipo_de_documento,
	'nroDocRec' => $numero_de_documento,
	'tipoCodAut' => 'E',
	'codAut' => $cae
);

$json_comprobante = json_encode($datos_comprobante);

// Codificar el JSON en Base64
$datos_comprobante_base64 = base64_encode($json_comprobante);

// Construir el texto completo para el código QR
$texto_qr = 'https://www.afip.gob.ar/fe/qr/?p=' . $datos_comprobante_base64;
$options = new QROptions;
$options->quality = 90;
$options->scale = 1;
// Crear una instancia de la clase QRCode
$qrcode = new QRCode($options);


// Generar el código QR y guardarlo en un archivo temporal
$ruta_temporal_imagen = 'comprobantes/qrcode.png';
try {
	$image = $qrcode->render($texto_qr);
} catch (\Throwable $th) {
	$image="";
}





$template_data = array(
	'razon_social' => $razon_social,
	'direccion' => $direccion,
	'cuit' => $cuit,
	'tipo_contribuyente' => $tipo_contribuyente,
	'iibb' => $iibb,
	'inicio_actividad' => $inicio_actividades,
	'tipo_factura_nombre' => $tipo_factura_nombre,
	'tipo_factura' => $tipo_factura,
	'codigo' => $tipo_factura,
	'punto_venta' => $punto_de_venta,
	'numero' => $numero_de_factura,
	'fecha' => $fecha,
	'concepto' => $concepto,
	'productos' => $productos,
	'iva' => $iva,
	'total' => $importe_gravado + $importe_iva + $importe_exento_iva,
	'cae' => $cae,
	'vencimiento_cae' => $fecha_vencimiento,
	'destinatario' => $destinatario,
	'codigo_qr' => $image,
	'importe_iva' => $importe_iva,
	'cliente_nombre' => $destinatario,
	'direccion_cliente' => '',
	'cliente_cuit' => $numero_de_documento,
	'cliente_condicion_iva' => '',
);


// Convierte el array en una cadena de consulta
$query_string = http_build_query($template_data);
$tipo_comprobante_imprimir = $_SESSION['tipo_comprobante_imprimir'];
// Construye la URL completa del template
if($tipo_comprobante_imprimir==1){

$template_url = $ruta . '/templates/tickets/tickets.php';
}else{
	$template_url = $ruta . '/templates/factura/factura.php';
}

try {
	// Realiza la solicitud POST al template
	$client = new GuzzleHttp\Client();
	$response = $client->request('POST', $template_url, [
		'form_params' => $template_data
	]);

	// Obtiene el contenido HTML de la respuesta
	$html = $response->getBody()->getContents();


	// Nombre para el archivo (sin .pdf)
	$name = "comprobante_cae_$cae";
	if($tipo_comprobante_imprimir==1){
	// Opciones para el archivo
	$options = array(
		"width" => 3.1, // Ancho de la página en pulgadas (típico para ticket)
		"height" => 5.5, // Alto de la página en pulgadas (típico para ticket)
		"marginLeft" => 0.1, // Margen izquierdo en pulgadas (típico para ticket)
		"marginRight" => 0.1, // Margen derecho en pulgadas (típico para ticket)
		"marginTop" => 0.1, // Margen superior en pulgadas (típico para ticket)
		"marginBottom" => 0.1 // Margen inferior en pulgadas (típico para ticket)
	);}else{
		//a4
		$options = array(
			"width" => 8.27, // Ancho de la página en pulgadas (típico para ticket)
			"height" => 11.69, // Alto de la página en pulgadas (típico para ticket)
			"marginLeft" => 0.1, // Margen izquierdo en pulgadas (típico para ticket)
			"marginRight" => 0.1, // Margen derecho en pulgadas (típico para ticket)
			"marginTop" => 0.1, // Margen superior en pulgadas (típico para ticket)
			"marginBottom" => 0.1 // Margen inferior en pulgadas (típico para ticket)
		);
	}

	// Creamos el PDF
	$res = $afip->ElectronicBilling->CreatePDF(array(
		"html" => $html,
		"file_name" => $name,
		"options" => $options
	));
} catch (Exception $e) {
	echo 'Error al obtener el template: ' . $e->getMessage();
}


header('Content-Type: application/json');
echo json_encode(array(
	'status' => '201', //Fecha de procesamiento
	'cae' => $cae,
	'vencimiento' => $fecha_vencimiento,
	'factura' => $numero_de_factura,
	'url_pdf' => $res['file'],
	'concepto' => $concepto,
	'punto_venta' => $punto_de_venta,
	'tipo_factura' => $tipo_factura,
	'tipo_documento' => $tipo_de_documento,
	'numero_documento' => $numero_de_documento,
	'numero_factura' => $numero_de_factura,
	'importe_iva' => $importe_iva,
	'importe_gravado' => $importe_gravado,
	'importe_exento_iva' => $importe_exento_iva,
	'importe_iva_21' => $importe_iva_21,
	'importe_iva_105' => $importe_iva_105,
	'importe_iva_0' => $importe_iva_0,
	'no_gravado_iva_21' => $no_gravado_iva_21,
	'no_gravado_iva_105' => $no_gravado_iva_105,
	'no_gravado_iva_0' => $no_gravado_iva_0,
	'qr' => $texto_qr

));





exit;
