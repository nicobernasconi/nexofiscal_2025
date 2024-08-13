<?php

use chillerlan\QRCode\QRCode;



require 'vendor/autoload.php';

include("includes/session_parameters.php");


$cuit = $_SESSION['cuit'];

// Datos de la factura



// Certificado (Puede estar guardado en archivos, DB, etc)
$cert = $_SESSION['cert'];

// Key (Puede estar guardado en archivos, DB, etc)
$key = $_SESSION['key'];

// Tu CUIT
$tax_id = $cuit;


$afip = new Afip(array(
	'CUIT' => $tax_id,
	'cert' => $cert,
	'key' => $key,
	'access_token' =>$apiSDKAfip ,
    	'production' => $apiSDKAfipProd
));



$aloquot_types = $afip->ElectronicBilling->GetAliquotTypes();
print_r($aloquot_types);


exit;
