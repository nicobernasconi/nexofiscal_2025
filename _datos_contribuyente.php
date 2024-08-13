<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;





require 'vendor/autoload.php';

include("includes/session_parameters.php");




// Datos de la factura


// Certificado (Puede estar guardado en archivos, DB, etc)
$certAfip = $_SESSION['cert'];
// Key (Puede estar guardado en archivos, DB, etc)
$keyAfip = $_SESSION['key'];
$cuit = '30718329295';

$tax_id = $cuit;

try {
	$afip = new Afip(array(
		'CUIT' => $tax_id,
		'cert' => $certAfip,
		'key' => $keyAfip,
		'access_token' =>$apiSDKAfip ,
    	'production' => $apiSDKAfipProd

	));


	$taxpayer_details = $afip->RegisterInscriptionProof->GetTaxpayerDetails('20111111111');
	print_r($taxpayer_details);

	
} catch (\Throwable $th) {
	header('Content-Type: application/json');
	echo json_encode(array(
		'status' => '500', //Fecha de procesamiento
		'error' => $th->getMessage()
	));
	exit;
}

