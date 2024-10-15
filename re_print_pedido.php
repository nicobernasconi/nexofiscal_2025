<?php


require 'vendor/autoload.php';

include("includes/session_parameters.php");

$id = $_POST['id'];
$tipo_template_archivo = $_POST['tipo_template'] ?? 'tickets';



// URL de la API
$url = $ruta . 'api/comprobantes/' . $id;

// Importar la clase GuzzleHTTP\Client

use GuzzleHttp\Client;
// Crear una instancia del cliente Guzzle
$client = new Client([
    'verify' => false,
]);

try {

	// Enviar la solicitud POST
	$response = $client->request('GET', $url, [
		'headers' => [
			'Content-Type' => 'application/json',
			// Obtener el token de seguridad de las variables de sesión
			'Authorization' => 'Bearer ' . $_SESSION['token']
		],
		'query' => [
			'sucursal_id' => $_SESSION['sucursal_id']
		]
	]);

	$bodyContents = $response->getBody()->getContents();
	$comprobante = json_decode($bodyContents, true)[0] ?? [];

		$total_con_descuento = $comprobante['total'];


		

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
	$cliente_nombre = $comprobante['cliente']['nombre'];
	$direccion_cliente = $comprobante['cliente']['direccion_comercial'];


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
		]
	]);

	$bodyContents = $response->getBody()->getContents();



	$productos = json_decode($bodyContents, true);


	$unique_tasa_iva = array_unique(array_column($productos, 'tasa_iva'));

	$producto_map = array();
	foreach ($productos as $producto) {
		$producto_map[] = array(
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
				$importe_iva += ($producto['total_linea']-($producto['total_linea'] / (1 + ($producto['tasa_iva']))));
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



	//crear array de iva para enviar a afip generer un case por cada tasa
	$iva = array();
	foreach ($totales as $tasa) {
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




	$template_data = array(
		'razon_social' => $razon_social,
		'direccion' => $direccion,
		'cuit' => $cuit,
		'tipo_contribuyente' => $tipo_contribuyente,
		'iibb' => $iibb,
		'inicio_actividad' => $inicio_actividades,
		'tipo_factura_nombre' => $comprobante['tipo_factura'],
		'tipo_factura' => $comprobante['tipo_factura'],
		'codigo' => $comprobante['tipo_factura'],
		'punto_venta' => $comprobante['punto_venta'],
		'numero' => $comprobante['numero']??0,
		'fecha' => $comprobante['fecha'],
		'concepto' => $comprobante['concepto'],
		'productos' => $productos,
		'total_iva' => $importe_iva,
		'iva' => $iva,
		//'total' => $importe_gravado,
		'total' => $total_con_descuento,
		'vencimiento_cae' => $comprobante['fecha_vencimiento'],
		'destinatario' => $comprobante['cliente']['direccion_comercial'],
		'cliente_nombre' => $cliente_nombre,
		'direccion_cliente' => $direccion_cliente,
		'cliente_cuit' => $comprobante['cliente']['cuit'],
		'cliente_condicion_iva' => $comprobante['cliente']['tipo_iva']['nombre'],
		'numero_factura_asociada' => $comprobante['observaciones_2'],
	);



	// Convierte el array en una cadena de consulta
	$query_string = http_build_query($template_data);

	// Construye la URL completa del template
	$template_url = $ruta . "/templates/pedidos/{$tipo_template_archivo}.php";


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
	$name = "comprobante_cae_{$comprobante['numero']}";

	// Opciones para el archivo
	if ($tipo_template_archivo == 'tickets') {
		$options = array(
			"width" => 1.7, // Ancho de la página en pulgadas (típico para ticket)
			"height" => 5.5, // Alto de la página en pulgadas (típico para ticket)
			"marginLeft" => 0.1, // Margen izquierdo en pulgadas (típico para ticket)
			"marginRight" => 0.1, // Margen derecho en pulgadas (típico para ticket)
			"marginTop" => 0.1, // Margen superior en pulgadas (típico para ticket)
			"marginBottom" => 0.1 // Margen inferior en pulgadas (típico para ticket)
		);
	} else {
		$options = array(
			"width" => 8.27, // Ancho de la página en pulgadas (típico para A4)
			"height" => 11.69, // Alto de la página en pulgadas (típico para A4)
			"marginLeft" => 0.1, // Margen izquierdo en pulgadas (típico para A4)
			"marginRight" => 0.1, // Margen derecho en pulgadas (típico para A4)
			"marginTop" => 0.1, // Margen superior en pulgadas (típico para A4)
			"marginBottom" => 0.1 // Margen inferior en pulgadas (típico para A4)
		);
	}



	// Creamos el PDF
	$res = $afip->ElectronicBilling->CreatePDF(array(
		"html" => $html,
		"file_name" => $name,
		"options" => $options
	));
} catch (Exception $e) {
	echo 'Error al obtener el template: ' . $e->getMessage().$e->getLine();;
}

echo json_encode(['status' => 201, 'pdfUrl' =>  $res['file']]);
