<?php
include("../includes/database.php");

try {
    $headers = apache_request_headers();
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $url = $data['url'];
        //descarga el archivo sql de la url y lo ejecuta
        $sql = file_get_contents($url);

        $statements = explode(';', $sql);
        $cant_errors = 0;
        $i=0;
        $erros_srt = "";
            foreach ($statements as $statement) {
                try {
                    $con->exec($statement);
                } catch (PDOException $e) {
                    $cant_errors++;
                    $erros_srt .= "Error en la consulta $i: " . $e->getMessage() . "\n";
                }
            }
        if ($con) {
            $response = array("status" => 200, "message" => "Base de datos instalada correctamente.");
        } else {
            $response = array("status" => 400, "message" => "Error al instalar la base de datos.");
        }
    } else {
        $response = array("status" => 400, "message" => "Error en los parametros de la peticion.");
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
