<?php

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;





require 'vendor/autoload.php';


include("includes/session_parameters.php");

$certAfip = "-----BEGIN CERTIFICATE-----
MIIDRTCCAi2gAwIBAgIIbBZqvHI/hjkwDQYJKoZIhvcNAQENBQAwMzEVMBMGA1UEAwwMQ29tcHV0
YWRvcmVzMQ0wCwYDVQQKDARBRklQMQswCQYDVQQGEwJBUjAeFw0yNDA5MDQxODQxMzNaFw0yNjA5
MDQxODQxMzNaMDAxEzARBgNVBAMMCk5FWE9GSVNDQUwxGTAXBgNVBAUTEENVSVQgMzA3MTg2MzY4
MjEwggEiMA0GCSqGSIb3DQEBAQUAA4IBDwAwggEKAoIBAQC5H7i717VKVxYNZtIH0hHW7YwBjqKX
zbNemqfaa0EZ24atI/AV5sLEf0iQ/wcPTKP2Pobv8ohxN09771Ye+A/RpmCvEPXopFTjfdPh9Y+K
S4V3wf1FbPdYdOzY9mdysO3bXl7HKnKIuDthYxscUK+ROQe+9o4QY3giLNAX5IrYzM9QqAYsT6Ss
8+rwsRPmHDngkKgwcUqDxZLJdujZlXMTqg1SYxZVnQfuaXbs3gqXuMAsedxECd+a8WWIDnD0Ssto
uC5gYpYkgLxNyie5uutJ5SLCjLp2taiK7r6mxC7lBjUaZ3XqVv9hWWmuvguk4uYze0yN9rgPG6+b
LNuSeEdNAgMBAAGjYDBeMAwGA1UdEwEB/wQCMAAwHwYDVR0jBBgwFoAUKw0vyN9h/QjJThHQNZME
bY5b0G4wHQYDVR0OBBYEFO0x14g4A/X4FbxzzghUwbeLx5urMA4GA1UdDwEB/wQEAwIF4DANBgkq
hkiG9w0BAQ0FAAOCAQEAAIxSXedD7wLELoL6ocnXuPdgkp7FhurcYZFr1p848TxrGOeqT+Bka0Ln
hBjQkgb4IKj2YoTDTE09RixD69rgUwHxkZ4zCckXQEmaWTXv9pYYQeiZrrdC+M3rAUkZ87vhlhmK
zs+IWX0LOGW5c8ePtlIShNBXdm6nBgK582PqxH18iJfJ4zcgsnU1qg+BL+Pf8eRu5N9sboHaw1W0
IY88faRmm2PqDxqliI9lsq6ShBfkgpDKfdyy4mJ9OUSPdDzpFHtTSkYP+iwa3jnWb3mYym+RDMiK
+jie9mHx7jgMHinYYQJKlvUV4sdsvfbV9EZ8/3ChFBlGc4wGcFCVD6o+SQ==
-----END CERTIFICATE-----";
// Key (Puede estar guardado en archivos, DB, etc)
$keyAfip = "-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC5H7i717VKVxYN
ZtIH0hHW7YwBjqKXzbNemqfaa0EZ24atI/AV5sLEf0iQ/wcPTKP2Pobv8ohxN097
71Ye+A/RpmCvEPXopFTjfdPh9Y+KS4V3wf1FbPdYdOzY9mdysO3bXl7HKnKIuDth
YxscUK+ROQe+9o4QY3giLNAX5IrYzM9QqAYsT6Ss8+rwsRPmHDngkKgwcUqDxZLJ
dujZlXMTqg1SYxZVnQfuaXbs3gqXuMAsedxECd+a8WWIDnD0SstouC5gYpYkgLxN
yie5uutJ5SLCjLp2taiK7r6mxC7lBjUaZ3XqVv9hWWmuvguk4uYze0yN9rgPG6+b
LNuSeEdNAgMBAAECggEAUk10k2qgylayUq36RYjS7pN4sb6IseW9T5uBcZ1KeaT8
GKDIHeyUfci66d+/80DXCmSdGDNjDraQ62AqESWm3sXR6TMoqM/af7NSznweX1UZ
FveTUtjRlK2TGRhfQIHBlcw8Zd6MwhTqLW9iv7FthKpus6so2MdoQwVaVK0CP8T4
24yyFAvWYzmUCz9MXlsKwGthO78ipPTrjnw+TfEGPLDSKUKSaVSaUl3Au0Lvnp6R
pdubvkxrhvoLv2OdNTaeno+EuH5Y/v0Cj47BQvTiD+1SR6v9HHvYA1wAi/mj6NsT
/FKI9XPe2h0hLEQxHW7v50FlSi8RTm+3Bg762bShTwKBgQDhhp5GKunvHhKg3fR+
n3k+BshxKUE1kT0lChwpy0BcEc0ObfWLNOX9BI4jCXiihWnSeaFiribTwdCzO6AW
Wmq84oaHjaZVm6MexJtnIPgv4sg0fM5NQz3MdhtFJB8EN7kXxn/u0JDnMIvyWcSy
4BqXm2PR+1lNO+lvej5WR5/S3wKBgQDSI4VfBDtKNRkmW9OIHyAyBuXyOxSYVfvT
seeOaM5CW01cFA5WuSJO2DIeAa9nu5YpGBvqyuEuROUDl/5t/mnGCUUmdM0ZivPN
+CiiYicpsrBUxK0Y/nqVXRRaXiQiFYTq1GugUTiHL+20ZssuOGMGcby4ldGZSnpc
BKPY6Mc3UwKBgQDNxt0nvQRSsCfjLGJoHu6rj9jYmAHt9KXL5BuqbtA8seleXyqK
aCvp33wpr/yonf7ekyiUN78pvFDHGYKKotl9m1uQ18iLmEUQT+hUAxsx5kUyzyYW
DWKX6rQbNEgwuv9iGDanGxr8N6mZ6hq19BGPf7Nm52amOCJZGztB0nycFQKBgQCD
TXqqskQxzBfY5gu4xGojmDfaMZxey2s9Da64RziKMe6WmFmVSrzrMx+trDPjx0y+
hLf876Pge1/17OUn6Y5CFczFiLoXOcG5c1RAksARx/tyHCpksa364lwzUxuUyIlz
CiPt2pJNtl77GDfgu470meDoHYUIGYd3M9cFU/4WzQKBgEOHowuf/LrT2SLMMrUR
znuXNxyiQBL+Vx6sp3x8H0rVPCzBaeCFj5G87y6+vQwt4C/+vIIR+8O5k7pshl6+
anTNjqNDdlwpeRp5S0RMGpJ0ryJiHL47ZhVVwd5WxDh5xDBe0OjIRNZ4Cf01ueqz
mEj+964v+3QVxkcF5LrWWJie
-----END PRIVATE KEY-----";
$dni = $_POST['dni'];

// Tu CUIT
$tax_id = "30718636821";

try {
	$afip = new Afip(array(
		'CUIT' => $tax_id,
		'cert' => $certAfip,
		'key' => $keyAfip,
		'access_token' =>'YS6NKTsRHrPkhdRwy9dm7YgnvcepUyc3n96osfHbpf2uTVTadwmj4Y4H4nly3pjy' ,
    	'production' => TRUE
	));

	$tax_id = $afip->RegisterScopeThirteen->GetTaxIDByDocument($dni);
	$taxpayer_details = $afip->RegisterInscriptionProof->GetTaxpayerDetails($tax_id);	
	echo json_encode($taxpayer_details);
	
} catch (\Throwable $th) {
	header('Content-Type: application/json');
	echo json_encode(array(
		'status' => '500', //Fecha de procesamiento
		'error' => $th->getMessage(). " - " . $th->getLine()
	));
	exit;
}

