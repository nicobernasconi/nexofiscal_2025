<?php
// Iniciar la sesión si no está iniciada

include("../includes/config.php");
//mostrar todos los errores


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
            update_remoto.usuario_id,
            update_remoto.estado,
            update_remoto.token,
            update_remoto.id_remoto,
            update_remoto.entidad,
            update_remoto.operacion,
            update_remoto.id_local,
            update_remoto.metodo
        FROM
            update_remoto";
$result = $con->query($sql);
//imprimir la cantidad de registros
$contador = 0;

try {
    while ($row =  $result->fetch(PDO::FETCH_ASSOC)) {
        $id_update = $row['id'];
        $url = $row['url'];
        $parametros = $row['parametros'];
        $token = $row['token'];
        $entidad = $row['entidad'];
        $operacion = $row['operacion'];
        $id_local = $row['id_local'];
        $metodo = $row['metodo'];
        $client = new Client();
        $con_id_extreno = true;
        //convertir el json a array
        $parametros = json_decode($parametros, true);

        foreach ($parametros as $key => $value) {
            // Verificar si la clave contiene "_id"
            if (strpos($key, '_id') !== false) {
                $sql_key = "SELECT table_name  FROM field_table_mapping WHERE field_name = '$key';";
                $result_key = $con->query($sql_key);
                if ($row_key = $result_key->fetch(PDO::FETCH_ASSOC)) {
                    $table_name = $row_key['table_name'];
                }
                //obtener el id remoto
                $sql_id_remoto = "SELECT id_remoto FROM $table_name WHERE id=$value";
                $result_id_remoto = $con->query($sql_id_remoto);
                $row_id_remoto = $result_id_remoto->fetch(PDO::FETCH_ASSOC);

                if ($row_id_remoto['id_remoto'] != '') {
                    $parametros[$key] = $row_id_remoto['id_remoto'];
                } else {
                    $con_id_extreno = false;
                }
            }
        }

        if ($operacion == 'edit') {
            //cambiar el id de la url por el id remoto}
            $sql_editar = "SELECT id_remoto FROM $entidad WHERE id=$id_local";
            $result_editar = $con->query($sql_editar);
            if ($row_editar = $result_editar->fetch(PDO::FETCH_ASSOC)) {
                $url = str_replace($id_local, $row_editar['id_remoto'], $url);
            }
        }
        if ($entidad == 'renglones_comprobantes') {
            //obtener el id del comprobante, esta el final de la barra
            $url_explode = explode('/', $url);
            $comprobante_id_local = $url_explode[count($url_explode) - 2];
            $sql_id_remoto = "SELECT id_remoto FROM comprobantes WHERE id=$comprobante_id_local";
            $result_id_remoto = $con->query($sql_id_remoto);
            $row = $result_id_remoto->fetch(PDO::FETCH_ASSOC);
            if ($row['id_remoto'] != '') {
                $comprobante_id = $row['id_remoto'];
                $url =  'api/renglones_comprobantes/' . $comprobante_id . '/';
            } else {
                $con_id_extreno = false;
            }
        }
        if ($con_id_extreno) {
            $url = $ruta . $url;
            $response = $client->request($metodo, $url, [
                'body' => json_encode($parametros),
                'headers' => [
                    'Content-Type' => 'application/json',
                    // Obtener el token de seguridad de las variables de sesión
                    'Authorization' => 'Bearer ' . $token
                ]
            ]);

            // Obtener el cuerpo de la respuesta
            $body = $response->getBody()->getContents();
            $data = json_decode($body);
            $status = $data->status;
            if ($status == 201) {
                //si existe el id en la respuesta, actualizar el id remoto
                if (isset($data->id)) {
                    $id = $data->id;
                    $sql_update_id_remoto = "UPDATE $entidad SET id_remoto=$id WHERE id=$id_local";
                    $con->query($sql_update_id_remoto);
                }
                //delete from update_remote
                $sql_delete = "DELETE FROM update_remoto WHERE id=$id_update";
                $con->query($sql_delete);
                $contador++;
            }
        }
    }
    echo "Se actualizaron $contador registros";
} catch (RequestException $e) {
    // Manejar cualquier excepción que ocurra durante la solicitud
    if ($e->hasResponse()) {
        echo "Error: " . $e->getResponse()->getBody()->getContents();
    } else {
        echo "Error: ";
    }
}
