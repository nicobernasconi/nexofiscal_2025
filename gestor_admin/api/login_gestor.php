<?php

include("../includes/database.php");
include("../includes/config.php");
include("../includes/security.php");

require '../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;


function login($nombre_usuario, $password, $key, $kid, $tiempo_expiracion, $con)
{
    $password = md5($password);
    $query = "SELECT
    gestores.id,
    gestores.nombre,
    gestores.usuario,
    gestores.`password`,
    gestores.email,
    gestores.telefono
    FROM
    gestores WHERE email = '$nombre_usuario' AND password = '$password'";
    $result = $con->query($query);
    $usuario = $result->fetch(PDO::FETCH_ASSOC) ?? false;
    if (!$usuario) {
        return false;
    }


    $usuario_id = $usuario['id'];
    $empresa_id =999999999;

    $token = crearToken($usuario_id, $empresa_id, $key, $kid, $tiempo_expiracion);
    return $token;
}

// Lógica principal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre_usuario = $data['usuario'] ?? '';
    $password = $data['password'] ?? '';

    if ($nombre_usuario && $password) {
        $token = login($nombre_usuario, $password, $key, $kid, $tiempo_expiracion, $con);
        if ($token != '') {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $usuario_id = $decoded->usuario_id;  
            $gestor = array();
            $query = "SELECT
            gestores.id,
            gestores.nombre,
            gestores.usuario,
            gestores.`password`,
            gestores.email,
            gestores.telefono,
            gestores.logo
            FROM
            gestores WHERE id = '$usuario_id'";
            $result = $con->query($query);
            $gestor = $result->fetch(PDO::FETCH_ASSOC) ?? false;
            $gestor['usuario_id'] = $usuario_id;
            $gestor['empresa_id'] =999999999;
            $gestor['login_gestor'] = true;
            //gaudar tiempo actual en la sesion
            $gestor['time'] = time();
            $gestor['status'] = 200;
            unset($gestor['password']);

            $response = array("status" => 200, "token" => $token, "usuario_id" => $usuario_id,"gestor"=>$gestor);
        
        } else {
            $response = array("status" => 401, "message" => "Nombre de usuario o contraseña incorrectos.");
        }
    } else {
        $response = array("status" => 400, "message" => "Se requieren nombre de usuario y contraseña.");
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
