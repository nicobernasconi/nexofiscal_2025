<?php
// Iniciar la sesión si no está iniciada

include("../includes/config.php");
//mostrar todos los errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include("../includes/database.php");
// Importar la clase GuzzleHTTP\Client
require '../vendor/autoload.php';

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

// Crear una instancia del cliente Guzzle

//obtener los datos de la tabla update_remote
$sql = "SELECT
update_remoto.id,
update_remoto.url,
update_remoto.parametros,
update_remoto.token
FROM
update_remoto";
$result = $con->query($sql);
//imprimir la cantidad de registros
echo "Cantidad de registros: " . $result->rowCount() . "<br>";

//recorrer los datos de la tabla update_remote
$comprobante_id=0;
$contador=0;
while ($row =  $result->fetch(PDO::FETCH_ASSOC)) {
    $url = $row['url'];
    $parametros = $row['parametros'];
    $token = $row['token'];

    $client = new Client();

    try {
       if($url=='http://nexofiscal.com.ar/sist/api/comprobantes'){
        // Enviar la solicitud POST
        $response = $client->request('POST', $url, [
            'body' => $parametros,
            'headers' => [
                'Content-Type' => 'application/json',
                // Obtener el token de seguridad de las variables de sesión
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        // Obtener el cuerpo de la respuesta
        $body = $response->getBody()->getContents();

        $json_update = json_decode($body, true);
        if($json_update['status'] == '201'){
            //eliminar el registro de la tabla update_remote
            $sql = "DELETE FROM update_remoto WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $row['id']);
            $stmt->execute();
            $comprobante_id = $json_update['id'];
            $contador++;
        }
        echo 'comprobante<br>';
        
        
    }else if(preg_match('/http:\/\/nexofiscal\.com\.ar\/sist\/api\/renglones_comprobantes/', $url)) {
        $url='http://nexofiscal.com.ar/sist/api/renglones_comprobantes/'.$comprobante_id.'/';

        $response = $client->request('POST', $url, [
            'body' => $parametros,
            'headers' => [
                'Content-Type' => 'application/json',
                // Obtener el token de seguridad de las variables de sesión
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        // Obtener el cuerpo de la respuesta
        $body = $response->getBody()->getContents();

        $json_update = json_decode($body, true);
        if($json_update['status'] == '201'){
            //eliminar el registro de la tabla update_remote
            $sql = "DELETE FROM update_remoto WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $row['id']);
            $stmt->execute();
            $contador++;

        }
        echo 'renglones<br>';

    }else{
        $response = $client->request('POST', $url, [
            'body' => $parametros,
            'headers' => [
                'Content-Type' => 'application/json',
                // Obtener el token de seguridad de las variables de sesión
                'Authorization' => 'Bearer ' . $token
            ]
        ]);
        // Obtener el cuerpo de la respuesta
        $body = $response->getBody()->getContents();

        $json_update = json_decode($body, true);
        if($json_update['status'] == '201'){
            //eliminar el registro de la tabla update_remote
            $sql = "DELETE FROM update_remoto WHERE id = :id";
            $stmt = $con->prepare($sql);
            $stmt->bindParam(':id', $row['id']);
            $stmt->execute();
            $contador++;
        }
        echo 'otros<br>';
    }



    } catch (RequestException $e) {
        // Manejar cualquier excepción que ocurra durante la solicitud
        if ($e->hasResponse()) {
            echo "Error: " . $e->getResponse()->getBody()->getContents();
        } else {
            echo "Error: ";
        }
    }
}
