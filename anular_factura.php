<?php

use GuzzleHttp\Client;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
// Crear una instancia del cliente Guzzle


require 'vendor/autoload.php';

include("includes/session_parameters.php");

$id = $_POST['id'];
$usuario_id = $_POST['usuario_id'];
$motivo_baja = $_POST['motivo_baja'];
try {
	// URL de la API
	$url = $ruta . 'api/comprobantes/' . $id;
	// Certificado (Puede estar guardado en archivos, DB, etc)



	// Importar la clase GuzzleHTTP\Client

	$client = new Client([
    'verify' => false,
]);



	// Enviar la solicitud POST
	$response = $client->request('GET', $url, [
		'headers' => [
			'Content-Type' => 'application/json',
			// Obtener el token de seguridad de las variables de sesión
			'Authorization' => 'Bearer ' . $_SESSION['token']
		],'query' => [
			'sucursal_id' => $_SESSION['sucursal_id']
		]
	]);

	$bodyContents = $response->getBody()->getContents();
	$comprobante = json_decode($bodyContents, true)[0] ?? [];

	// Datos de la factura

	$razon_social = $_SESSION['empresa_razon_social'];
	$direccion = $_SESSION['direccion'];
	$iibb = $_SESSION['iibb'];
	$inicio_actividades = $_SESSION['fecha_inicio_actividades'];
	$tipo_contribuyente = ($_SESSION['tipo_iva'] == 1) ? 'RESPONSABLE INSCRIPTO' : 'MONOTRIBUTO';
	// Certificado (Puede estar guardado en archivos, DB, etc)
	$certAfip = $_SESSION['cert'];
	// Key (Puede estar guardado en archivos, DB, etc)
	$keyAfip = $_SESSION['key'];
	$cuit = $_SESSION['cuit'];
	// Obtener el array de productos enviado por POST

	$url_renglones = $ruta . 'api/renglones_comprobantes/' . $id . '/';
	$client = new Client([
    'verify' => false,
]);
	// Enviar la solicitud POST
	$response = $client->request('GET', $url_renglones, [
		'headers' => [
			'Content-Type' => 'application/json',
			// Obtener el token de seguridad de las variables de sesión
			'Authorization' => 'Bearer ' . $_SESSION['token']
		],
		
	]);

	$bodyContents = $response->getBody()->getContents();
	$productos = json_decode($bodyContents, true);


	$unique_tasa_iva = array_unique(array_column($productos, 'tasa_iva'));


	$producto_map = array();
	foreach ($productos as $producto) {
		$producto_map[] = array(
			'producto_id' => $producto['producto_id'],
			'cantidad' => $producto['cantidad'],
			'descripcion' => $producto['descripcion'],
			'tasa_iva' => $producto['tasa_iva'],
			'precio' => $producto['precio_unitario'],
			'total_linea' => $producto['total_linea'],


		);
	}

	$productos = $producto_map;


	//calcular iva yt neto por cada tasa 
	foreach ($unique_tasa_iva as $tasa) {
		$importe_gravado = 0;
		$importe_iva = 0;
		$importe_sin_iva = 0;
		$importe_exento_iva = 0;
		$totales_por_tasa["$tasa"] = array();
		foreach ($productos as $producto) {
			if ($producto['tasa_iva'] == $tasa) {
				$importe_gravado += ($producto['total_linea']);
				$importe_iva += ($producto['total_linea'] * ($producto['tasa_iva']));
				$importe_sin_iva += ($producto['total_linea'] / (1 + ($producto['tasa_iva'])));
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

		$tatales[] = array(
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
	foreach ($tatales as $tasa) {
		$importe_iva += ($tasa['importe_iva']);
	}


	//calcular total de gravado
	$importe_gravado = 0;
	foreach ($tatales as $tasa) {
		$importe_gravado += ($tasa['importe_gravado']);
	}

	//calcular total de exento iva
	$importe_exento_iva = 0;

	//crear array de iva para enviar a afip generer un case por cada tasa
	$iva = array();
	foreach ($tatales as $tasa) {
		$iva[] = array(
			'Id' => $tasa['tasa_id'],
			'BaseImp' => round($tasa['importe_gravado'], 2),
			'Importe' => round($tasa['importe_iva'], 2)
		);
	}

	// Tu CUIT
	$tax_id = $cuit;
	$afip = new Afip(array(
		'CUIT' => $tax_id,
		'cert' => $certAfip,
		'key' => $keyAfip,
		'access_token' =>$apiSDKAfip ,
    	'production' => $apiSDKAfipProd
	));
	


	$punto_de_venta = $comprobante['punto_venta'];
	$tipo_de_nota = ($comprobante['tipo_factura'] == 1) ? 3 : (($comprobante['tipo_factura'] == 6) ? 8 : 13);
	$last_voucher = $afip->ElectronicBilling->GetLastVoucher($punto_de_venta, $tipo_de_nota);
	$punto_factura_asociada = $comprobante['punto_venta'];
	$tipo_factura_asociada = $comprobante['tipo_factura'];
	$numero_factura_asociada = $comprobante['numero_factura'];
	$concepto = $comprobante['concepto'];
	$tipo_de_documento = $comprobante['tipo_documento'];
	$numero_de_documento = $comprobante['numero_de_documento'];
	$numero_de_nota = $last_voucher + 1;
	$fecha = date('Y-m-d');
	$hora = date('H:i:s');


	if ($concepto === 2 || $concepto === 3) {
		$fecha_servicio_desde = intval(date('Ymd'));
		$fecha_servicio_hasta = intval(date('Ymd'));
		$fecha_vencimiento_pago = intval(date('Ymd'));
	} else {
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
		'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva, 2),
		'ImpTotConc' => 0, // Importe neto no gravado
		'ImpNeto' 	=> round($importe_gravado, 2),
		'ImpOpEx' 	=> round($importe_exento_iva, 2),
		'ImpIVA' 	=> round($importe_iva, 2),
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
		'Iva' 	=> $iva,
	);
	if( $comprobante['tipo_factura']==11){
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
		'ImpTotal' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva, 2),
		'ImpTotConc' => 0, // Importe neto no gravado
		'ImpNeto' 	=> round($importe_gravado + $importe_iva + $importe_exento_iva, 2),
		'ImpOpEx' 	=> 0,
		'ImpIVA' 	=> 0,
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
		
	);
	}


	$res = $afip->ElectronicBilling->CreateVoucher($data);

	
	$cae = $res['CAE'];
	$fecha_vencimiento = $res['CAEFchVto'];




	//generacion del QR
	$datos_comprobante = array(
		'ver' => 1,
		'fecha' => $fecha,
		'cuit' => $cuit,
		'ptoVta' => $punto_de_venta,
		'tipoCmp' => $tipo_de_nota,
		'nroCmp' => $numero_de_nota,
		'importe' => round(doubleval($importe_gravado + $importe_iva + $importe_exento_iva), 2),
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
	$image = $qrcode->render($texto_qr);



	//generar PDF
	$template_data = array(
		'razon_social' => $razon_social,
		'direccion' => $direccion,
		'cuit' => $cuit,
		'tipo_contribuyente' => $tipo_contribuyente,
		'iibb' => $iibb,
		'inicio_actividad' => $inicio_actividades,
		'tipo_factura_nombre' => $tipo_de_nota,
		'tipo_factura' => $tipo_de_nota,
		'codigo' => $tipo_de_nota,
		'punto_venta' => $comprobante['punto_venta'],
		'numero' => $numero_de_nota,
		'fecha' => $fecha,
		'concepto' => $comprobante['concepto'],
		'productos' => $productos,
		'iva' => $iva,
		'total' => $importe_gravado,
		'cae' => $cae,
		'vencimiento_cae' => $fecha_vencimiento,
		'destinatario' => $comprobante['cliente']['direccion_comercial'],
		'codigo_qr' => $image,
		'numero_factura_asociada' => $numero_factura_asociada,
	);


	// Convierte el array en una cadena de consulta
	$query_string = http_build_query($template_data);

	// Construye la URL completa del template
	$template_url = $ruta . '/templates/nota_credito/tickets.php';


	// Realiza la solicitud POST al template
	$client = new GuzzleHttp\Client([
		'verify' => false,
	]);
	$response = $client->request('POST', $template_url, [
		'form_params' => $template_data
	]);

	// Obtiene el contenido HTML de la respuesta
	$html = $response->getBody()->getContents();


	// Nombre para el archivo (sin .pdf)
	$name = "comprobante_cae_{$comprobante['cae']}";

	// Opciones para el archivo
	$options = array(
		"width" => 3.1, // Ancho de la página en pulgadas (típico para ticket)
		"height" => 5.5, // Alto de la página en pulgadas (típico para ticket)
		"marginLeft" => 0.1, // Margen izquierdo en pulgadas (típico para ticket)
		"marginRight" => 0.1, // Margen derecho en pulgadas (típico para ticket)
		"marginTop" => 0.1, // Margen superior en pulgadas (típico para ticket)
		"marginBottom" => 0.1 // Margen inferior en pulgadas (típico para ticket)
	);

	// Creamos el PDF
	$res = $afip->ElectronicBilling->CreatePDF(array(
		"html" => $html,
		"file_name" => $name,
		"options" => $options
	));


	// Guardar el comprobante en la base de datos

	$comprobantes_data = array(
		'cliente_id' => $comprobante['cliente']['id'],
		'fecha' => $fecha,
		'hora' => $hora,
		'fecha_proceso' => $fecha,
		'letra' =>  $comprobante['letra'],
		'prefijo_factura' => $punto_de_venta,
		'total' => $comprobante['total'],
		'total_pagado' => $comprobante['total_pagado'],
		'tipo_comprobante_id' => 4,
		'vendedor_id' => $comprobante['vendedor_id'],
		'observaciones_1' => $comprobante['observaciones_1'],
		'cae' => $cae,
		'fecha_vencimiento' => $fecha_vencimiento,
		'numero_factura' => $numero_de_nota,
		'url_pdf' => $res['file'],
		'sucursal_id' => $comprobante['sucursal_id'],
		'concepto' => $concepto,
		'tipo_documento' => $tipo_de_documento,
		'numero_de_documento' => $numero_de_documento,
		'tipo_factura' => $tipo_de_nota,
		'punto_venta' => $punto_de_venta,
		'comprobante_id_baja' => $id,
		'qr' => $texto_qr,
		'observaciones_2' => $numero_factura_asociada,
		'importe_iva_105' => $comprobante['importe_iva_105'],
        'importe_iva_21' => $comprobante['importe_iva_21'],
        'importe_iva_0' =>  $comprobante['importe_iva_0'],
        'no_gravado_iva_105' => $comprobante['no_gravado_iva_105'],
        'no_gravado_iva_21' => $comprobante['no_gravado_iva_21'],
        'no_gravado_iva_0' => $comprobante['no_gravado_iva_0'],
        'importe_impuesto_interno' => $comprobante['importe_impuesto_interno']??0,
		


	);

	$client = new Client([
    'verify' => false,
]);
	$client = new Client([
    'verify' => false,
]);

	// Convertir los datos a formato JSON
	$post_json = json_encode($comprobantes_data);


	// URL de la API
	$url_comprobante = $ruta . 'api/comprobantes';
	$response = $client->request('POST', $url, [
		'body' => $post_json,
		'headers' => [
			'Content-Type' => 'application/json',
			// Obtener el token de seguridad de las variables de sesión
			'Authorization' => 'Bearer ' . $_SESSION['token']
		]
	]);


	$comprobantes_data = array(
		'motivo_baja' => $motivo_baja,
		'fecha_baja' => $fecha,
	);

	$post_json = json_encode($comprobantes_data);

	// URL de la API
	$url_comprobante = $ruta . 'api/comprobantes/' . $id . '/';
	$response = $client->request('PUT', $url, [
		'body' => $post_json,
		'headers' => [
			'Content-Type' => 'application/json',
			// Obtener el token de seguridad de las variables de sesión
			'Authorization' => 'Bearer ' . $_SESSION['token']
		]
	]);

	foreach ($productos as $producto) {
		$movimiento_data = array(
			'producto_id' => $producto['producto_id'],
			'sucursal_id' => $comprobante['sucursal_id'],
			'cantidad' => ($producto['cantidad'] ),
		);

		$url_movimientos_stock = $ruta . 'api/stocks/';
		// Convertir el arreglo de datos a JSON

		$post_json = json_encode($movimiento_data);

		$response = $client->request('POST', $url_movimientos_stock, [
			'body' => $post_json,
			'headers' => [
				'Content-Type' => 'application/json',
				// Obtener el token de seguridad de las variables de sesión
				'Authorization' => 'Bearer ' . $_SESSION['token']
			]
		]);
	}







	echo json_encode(['status' => 201, 'pdfUrl' =>  $res['file']]);
} catch (Exception $e) {
	print_r( $tipo_de_nota);
	echo json_encode(['status' => 500, 'mensaje' => 'Error al obtener el template: ' . $e->getMessage().' linea:'.$e->getLine()]);
}
