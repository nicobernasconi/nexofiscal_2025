<?php

use FPDF\PDF;
use GuzzleHttp\Client;

// Incluye el autoload generado por Composer
require_once 'vendor/autoload.php';


include("includes/session_parameters.php");
include("includes/database.php");


// Crear una instancia del cliente Guzzle
$client = new Client();

// Si se reciben datos por POST, se asignan a variables
$nombre_tienda =  $_SESSION['empresa_razon_social'];
$direccion_tienda = $_SESSION['direccion'];
$telefono_tienda = isset($_POST['telefono_tienda']) ? $_POST['telefono_tienda'] : '';
$articulos = isset($_POST['articulos']) ? $_POST['articulos'] : '';
$total = isset($_POST['total']) ? $_POST['total'] : '';
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : '';
$monto_pagado = isset($_POST['monto_pagado']) ? $_POST['monto_pagado'] : '';
$monto_vuelto = isset($_POST['monto_vuelto']) ? $_POST['monto_vuelto'] : '';
//fecha hoy
$fecha = date('Y-m-d');
//hora actual
$hora = date('H:i:s');
$destinatario = isset($_POST['cliente_nombre']) ? $_POST['cliente_nombre'] : 'Ocacional';
$cliente_cuit = isset($_POST['cliente_cuit']) ? $_POST['cliente_cuit'] : '0';
$cliente_domicilio = isset($_POST['cliente_domicilio']) ? $_POST['cliente_domicilio'] : '-';
$tipo_comprobante_id = isset($_POST['tipo_comprobante_id']) ? $_POST['tipo_comprobante_id'] : '0';
$tipo_comprobante = isset($_POST['tipo_comprobante']) ? $_POST['tipo_comprobante'] : '';
$vendedor_id = ($_POST['vendedor_id'] != '') ? $_POST['vendedor_id'] : null;
$numero_factura = isset($_POST['numero_factura']) ? $_POST['numero_factura'] : '';
$cae = isset($_POST['cae']) ? $_POST['cae'] : '';
$fecha_vencimiento = isset($_POST['fecha_vencimiento']) ? $_POST['fecha_vencimiento'] : '';
$letra_factura = isset($_POST['letra_factura']) ? $_POST['letra_factura'] : '';
$punto_venta = $_SESSION['punto_venta'];
$razon_social = $nombre_tienda;
$tipo_contribuyente = ($_SESSION['tipo_iva'] == 1) ? 'RESPONSABLE INSCRIPTO' : 'MONOTRIBUTO';
$punto_de_venta = $_SESSION['punto_venta'];
$iibb = $_SESSION['iibb'];
$inicio_actividades = $_SESSION['fecha_inicio_actividades'];
$productos = json_decode($_POST['productos'], true);
$promociones = json_decode($_POST['promociones'], true);
$sucursal_id = $_SESSION['sucursal_id'];

$cliente_id = (isset($_POST['cliente_id']) && $_POST['cliente_id'] != '') ? $_POST['cliente_id'] : 1;
$productos = isset($_POST['productos']) ? $_POST['productos'] : array();
$promociones = isset($_POST['promociones']) ? json_decode($_POST['promociones'], true) : array();

// Certificado (Puede estar guardado en archivos, DB, etc)
$certAfip = $_SESSION['cert'];
// Key (Puede estar guardado en archivos, DB, etc)
$keyAfip = $_SESSION['key'];
$cuit = $_SESSION['cuit'];

$url_clientes = $ruta . 'api/clientes/' . $cliente_id . '/';
// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ]
];

// Enviar la solicitud GET
$response = $client->request('GET', $url_clientes, $params);
// Obtener el cuerpo de la respuesta en formato JSON
$body = $response->getBody()->getContents();
// Decodificar el JSON en un array asociativo
$data = json_decode($body, true);

//generenar un string con las promociones ejemplo: [{"id":"1","name":"PROMO 1( 10%)","discount":"10"}] debe tener el formato name y un salto de linea
$promociones_string = '';
foreach ($promociones as $promocion) {
    $promociones_string .= $promocion['name'] . ' (' . $promocion['discount'] . '%)' . "\n";
}


$fecha = date('Ymd');

if ($tipo_comprobante == 'FACTURA') {
    // Crear un array con los datos de la comprobantes
    $comprobantes_data = array(
        'cliente_id' => $cliente_id,
        'fecha' => $fecha,
        'hora' => $hora,
        'fecha_proceso' => $fecha,
        'letra' =>  $letra_factura,
        'prefijo_factura' => $punto_venta,
        'total' => $total,
        'total_pagado' => $monto_pagado,
        'tipo_comprobante_id' => $tipo_comprobante_id,
        'vendedor_id' => $vendedor_id,
        'observaciones_1' => $promociones_string,
        'cae' => $cae,
        'fecha_vencimiento' => $fecha_vencimiento,
        'numero_factura' => $numero_factura,
        'sucursal_id' => $sucursal_id,
        'punto_venta' => $punto_venta,
    );
} else {
    $comprobantes_data = array(
        'cliente_id' => $cliente_id,
        'fecha' => $fecha,
        'hora' => $hora,
        'fecha_proceso' => $fecha,
        'letra' =>  $letra_factura,
        'prefijo_factura' => $punto_venta,
        'total' => $total,
        'total_pagado' => $monto_pagado,
        'tipo_comprobante_id' => $tipo_comprobante_id,
        'vendedor_id' => $vendedor_id,
        'observaciones_1' => $promociones_string,
        'sucursal_id' => $sucursal_id,
        'punto_venta' => $punto_venta,


    );
}

$client = new Client();

// Convertir los datos a formato JSON
$post_json = json_encode($comprobantes_data);


// URL de la API
$url = $ruta . 'api/comprobantes';
$url_remota = $ruta_remota . 'api/comprobantes';

try {

    // Enviar la solicitud POST
    $response = $client->request('POST', $url, [
        'body' => $post_json,
        'headers' => [
            'Content-Type' => 'application/json',
            // Obtener el token de seguridad de las variables de sesión
            'Authorization' => 'Bearer ' . $_SESSION['token']
        ]
    ]);

    $bodyContents = $response->getBody()->getContents();
    //inserter en la base de datos remota
    //insertUpdateRemoto($con, $post_json, $url_remota, $_SESSION['token'], $local);

    $data = json_decode($bodyContents, true);
    $id = $data['id'];
    $id_comprobante = $data['id'];
    //quito la ruta para generar la url de la api
    $url_remoto = str_replace($ruta,'',$url); 
    insertUpdateRemoto($con, $post_json,'comprobantes','add','POST',$id, $url_remoto, $_SESSION['token'], $local);

    $id = $data['id'] ?? 0;

    if (($tipo_comprobante == 'PEDIDO')) {
        //obtener año
        $year = date('Y');
        $nro_comprobante = "OC-" . $year . "-PV" . str_pad($punto_venta, 4, '0', STR_PAD_LEFT) . "-" . str_pad($id, 8, '0', STR_PAD_LEFT);
    } else {
        $nro_comprobante = "FC-" . $letra_factura . '-' . str_pad($punto_venta, 4, '0', STR_PAD_LEFT) . "-" . str_pad($numero_factura, 8, '0', STR_PAD_LEFT);
    }
    $url = $ruta . 'api/renglones_comprobantes/' . $id . '/';
    $url_remota = $ruta_remota . 'api/renglones_comprobantes/' . $id . '/';

    // Crear una instancia del cliente Guzzle
    $client = new Client();

    // Obtener el array de productos enviado por POST
    $productos = json_decode($_POST['productos'], true);

    // Iterar sobre cada producto
    foreach ($productos as $producto) {
        // Crear el arreglo de datos para el producto
        $comprobantes_data = array(
            "producto_id" => $producto['id'],
            "descripcion" => $producto['name'],
            "cantidad" => $producto['quantity'],
            "precio_unitario" => $producto['price'],
            "tasa_iva" => $producto['tasa_iva'], // Tasa de IVA
            "descuento" => 0,
            "total_linea" => $producto['price'] * $producto['quantity'] // Calcular el total de la línea
        );

        // Convertir el arreglo de datos a JSON
        $post_json = json_encode($comprobantes_data);

        // Enviar la solicitud POST
        $response = $client->request('POST', $url, [
            'body' => $post_json,
            'headers' => [
                'Content-Type' => 'application/json',
                // Obtener el token de seguridad de las variables de sesión
                'Authorization' => 'Bearer ' . $_SESSION['token']
            ]
        ]);
        $bodyContents = $response->getBody()->getContents();
        //inserter en la base de datos remota
        //insertUpdateRemoto($con, $post_json, $url_remota, $_SESSION['token'], $local);
  
        $id = json_decode($bodyContents)->id;
        //quito la ruta para generar la url de la api
        $url_remoto = str_replace($ruta,'',$url); 
        
        insertUpdateRemoto($con, $post_json,'renglones_comprobantes','add','POST',$id, $url_remoto, $_SESSION['token'], $local);

        $data = json_decode($bodyContents, true);
        $status = $data['status'];


        if ($status == 201) {
            $client_stock = new Client();
            $movimiento_data = array(
                'producto_id' => $producto['id'],
                'sucursal_id' => $sucursal_id,
                'cantidad' => ((-1) * $producto['quantity']),
            );

            $url_movimientos_stock = $ruta . 'api/stocks/';
            $url_movimientos_stock_remoto = $ruta_remota . 'api/stocks/';

            $post_json = json_encode($movimiento_data);

            $response_stock = $client_stock->request('POST', $url_movimientos_stock, [
                'body' => $post_json,
                'headers' => [
                    'Content-Type' => 'application/json',
                    // Obtener el token de seguridad de las variables de sesión
                    'Authorization' => 'Bearer ' . $_SESSION['token']
                ]
            ]);

            $id = json_decode($bodyContents)->id;
                        //quito la ruta para generar la url de la api
            $url_remoto = str_replace($ruta,'',$url_movimientos_stock); 
            insertUpdateRemoto($con, $post_json,'stocks','add','POST',$id, $url_remoto, $_SESSION['token'], $local);
        }
    }
} catch (\Throwable $th) {
}


/*$lineas_articulos = explode("\n", $articulos);
$alto_pagina = count($lineas_articulos) * 10 + 130;
$pdf = new FPDF('P', 'mm', array(75, $alto_pagina));
// Establecer los márgenes
$pdf->SetMargins(5, 5, 5);*/

$unique_tasa_iva = array_unique(array_column($productos, 'tasa_iva'));

	$producto_map = array();
	foreach ($productos as $producto) {
		$producto_map[] = array(
			'cantidad' => $producto['quantity'], 
			'descripcion' => $producto['name'],
			'tasa_iva' => $producto['tasa_iva'],
			'precio' => $producto['price'],
			'total_linea' => $producto['price'] * $producto['quantity'],

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

// Enviar la solicitud POST
$url = $ruta . 'api/comprobantes/' . $id_comprobante;

// Crear una instancia del cliente Guzzle
$client = new Client();

$response_comp = $client->request('GET', $url, [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ],
    'query' => [
        'sucursal_id' => $_SESSION['sucursal_id']
    ]
]);

$bodyContentsComp = $response_comp->getBody()->getContents();
$comprobante = json_decode($bodyContentsComp, true)[0] ?? [];
$numero= $comprobante['numero'] ?? 0;

$template_data = array(
    'razon_social' => $nombre_tienda,
    'direccion' => $direccion_tienda,
    'cuit' => $_SESSION['cuit'],
    'tipo_contribuyente' => $tipo_contribuyente,
    'iibb' => $iibb,
    'inicio_actividad' => $inicio_actividades,
    'tipo_factura_nombre' => 'PEDIDO',
    'tipo_factura' => '1',
    'punto_venta' => $punto_de_venta,
    'destinaatario' => $destinatario,
    'numero' => $numero,
    'fecha' => $fecha,
    'concepto' => '9999',
    'productos' => $productos,
    'total' => $total,
    'total_iva' => $importe_iva,
    'iva' => $iva,
    'destinatario' => $destinatario,
    'cliente_nombre' => $destinatario,
    'direccion_cliente' => $cliente_domicilio,
    'cliente_cuit' => $cliente_cuit,
    'cliente_condicion_iva' => '',

);






/*
$pdf->AddPage();

// Establecer estilos
$pdf->SetFont('Courier', '', 8); // Fuente y tamaño de fuente
// Contenido
$pdf->MultiCell(0, 5, 'Razon social: ' . $template_data['razon_social'], 0, 1);
$pdf->MultiCell(0, 5, 'Direccion: ' . $template_data['direccion'], 0, 1);
$pdf->MultiCell(0, 5, 'C.U.I.T.: ' . $template_data['cuit'], 0, 1);
$pdf->MultiCell(0, 5, 'Tipo de contribuyente: ' . $template_data['tipo_contribuyente'], 0, 1);
$pdf->MultiCell(0, 5, 'IIBB: ' . $template_data['iibb'], 0, 1);
$pdf->MultiCell(0, 5, 'Inicio de actividad: ' . $template_data['inicio_actividad'], 0, 1);

$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(0, 5, $template_data['tipo_factura'], 0, 1, 'C');

$pdf->SetFont('Courier', '', 8);
$pdf->Cell(0, 5, 'Codigo XXXX', 0, 1, 'C');
$pdf->Cell(0, 5, 'P.V: ' . str_pad($template_data['punto_venta'], 5, '0', STR_PAD_LEFT), 0, 1);
$pdf->Cell(0, 5, 'Nro: ' . str_pad($id, 5, '0', STR_PAD_LEFT), 0, 1);
$pdf->Cell(0, 5, 'Fecha: ' . $template_data['fecha'], 0, 1);
$pdf->Cell(0, 5, 'Concepto: ' . $template_data['concepto'], 0, 1);
$pdf->Cell(0, 5, '--------------------------------', 0, 1);
$pdf->Cell(0, 5, $template_data['destinatario'], 0, 1);
$pdf->Cell(0, 5, '--------------------------------', 0, 1);

$pdf->SetFont('Courier', '', 8); // Restablecer fuente normal para productos
foreach ($template_data['productos'] as $producto) {
    // Contenido de la tabla// Reducir el ancho de la primera celda
    $pdf->Cell(45, 5, $producto['quantity'] . ' ' . $producto['name'], 0); // Utilizar MultiCell para ajustar el texto al ancho de la celda
    $pdf->Cell(10, 5, ($producto['tasa_iva'] * 100) . '%', 0, 0); // Reducir el ancho de la tercera celda
    $pdf->Cell(20, 5, '$' . $producto['price'], 0, 1); // Reducir el ancho de la cuarta celda y agregar salto de línea
}

$pdf->Ln(); // Salto de línea
$pdf->Cell(0, 5, '--------------------------------', 0, 1);
$pdf->SetFont('Courier', 'B', 8);
$pdf->Cell(55, 5, 'TOTAL: $' . $template_data['total'], 0, 0); // Reducir el ancho de la celda

$pdf->Ln();
$pdf->Cell(0, 5, '--------------------------------', 0, 1);
$pdf->Cell(0, 5, 'Comprobante sin validez fiscal', 0, 1, 'C');


// Salida del PDF
$pdfPath = "comprobantes/comprobante_{$id}.pdf";
$pdf->Output($pdfPath, 'F');

// Devolver la URL del PDF en la respuesta AJAX
echo json_encode(['pdfUrl' => $ruta_https . $pdfPath]);
*/

// Tu CUIT
$tax_id = $cuit;

try {
    $afip = new Afip(array(
        'CUIT' => $tax_id,
        'cert' => $certAfip,
        'key' => $keyAfip,
        'access_token' => $apiSDKAfip,
        'production' => $apiSDKAfipProd

    ));




    // Convierte el array en una cadena de consulta
    $query_string = http_build_query($template_data);
    $tipo_comprobante_imprimir = $_SESSION['tipo_comprobante_imprimir'];
    // Construye la URL completa del template
    if ($tipo_comprobante_imprimir == 1) {

        $template_url = $ruta . '/templates/pedidos/tickets.php';
    } else {
        $template_url = $ruta . '/templates/pedidos/factura.php';
    }

    // Realiza la solicitud POST al template
    $client = new GuzzleHttp\Client();
    $response = $client->request('POST', $template_url, [
        'form_params' => $template_data
    ]);

    // Obtiene el contenido HTML de la respuesta
    $html = $response->getBody()->getContents();


    // Nombre para el archivo (sin .pdf)
    $name = "comprobante_";
    if ($tipo_comprobante_imprimir == 1) {
        // Opciones para el archivo
        $options = array(
            "width" => 1.7, // Ancho de la página en pulgadas (típico para ticket)
            "height" => 5.5, // Alto de la página en pulgadas (típico para ticket)
            "marginLeft" => 0.1, // Margen izquierdo en pulgadas (típico para ticket)
            "marginRight" => 0.1, // Margen derecho en pulgadas (típico para ticket)
            "marginTop" => 0.1, // Margen superior en pulgadas (típico para ticket)
            "marginBottom" => 0.1 // Margen inferior en pulgadas (típico para ticket)
        );
    } else {
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
    $url_pdf = $res['file'];
    echo json_encode(['pdfUrl' => $url_pdf]);
} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}
