<?php

include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
       $data = $_GET;
        $empresa_id = $data['empresa_id'];
        $familia_id = $data['familia_id'];
        $porcentaje1=0;
        $porcentaje2=0;
        $porcentaje3=0;
        if (isset($data['porcentaje1'])) {
            $porcentaje1 = $data['porcentaje1'] / 100;
        }
        if (isset($data['porcentaje2'])) {
            $porcentaje2 = $data['porcentaje2'] / 100;
        }
        if (isset($data['porcentaje3'])) {
            $porcentaje3 = $data['porcentaje3'] / 100;
        }
        $query = "SELECT 
    codigo, 
    descripcion, 
    ROUND(precio1 + (precio1 * $porcentaje1), 2) as precio1, 
    ROUND(precio2 + (precio2 * $porcentaje2), 2) as precio2, 
    ROUND(precio3 + (precio3 * $porcentaje3), 2) as precio3 
FROM 
    productos 
WHERE 
    empresa_id = $empresa_id 
    AND familia_id = $familia_id";
        $result = $con->query($query);
        $response = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $productos = array(
                "codigo" => $row['codigo'],
                "descripcion" => $row['descripcion'],
                "precio1" => $row['precio1'],
                "precio2" => $row['precio2'],
                "precio3" => $row['precio3']
            );

            array_push($response, $productos);
        }
    }

    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $empresa_id = $data['empresa_id'];
        $familia_id = $data['familia_id'];
        
        if ($data['porcentaje1']!='') {
            $porcentaje1 = strval($data['porcentaje1']) / 100;
        }else{
            $porcentaje1=0;
        }
        if ($data['porcentaje2']!='') {
            $porcentaje2 = strval($data['porcentaje2']) / 100;
        }else{
            $porcentaje2=0;
        }
        if ($data['porcentaje3']!='') {
            $porcentaje3 = strval($data['porcentaje3']) / 100;
        }else{
            $porcentaje3=0;
        }

        $query_update = "UPDATE productos 
                 SET precio1 = ROUND(precio1 + (precio1 * $porcentaje1), 2), 
                     precio2 = ROUND(precio2 + (precio2 * $porcentaje2), 2), 
                     precio3 = ROUND(precio3 + (precio3 * $porcentaje3), 2) 
                 WHERE empresa_id = $empresa_id 
                 AND familia_id = $familia_id";
        $result = $con->query($query_update);

        if ($result) {
            $response = array("status" => 200, "status_message" => "Precios actualizados correctamente: ");
        } else {
            $response = array("status" => 500, "status_message" => "Error al actualizar precios");
        }

       
    }



    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    $error_msg = $errores_mysql[$e->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}{$e->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
