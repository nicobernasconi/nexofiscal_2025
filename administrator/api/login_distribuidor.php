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
                distribuidor.id,
                distribuidor.nombre,
                distribuidor.telefono,
                distribuidor.responsable,
                distribuidor.logo,
                distribuidor.email,
                distribuidor.`password`
              FROM
                distribuidor WHERE email = '$nombre_usuario' AND password = '$password'";
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
            $query = "SELECT
                        distribuidor.id,
                        distribuidor.nombre,
                        distribuidor.telefono,
                        distribuidor.responsable,
                        distribuidor.logo,
                        distribuidor.email,
                        distribuidor.`password`,
                        empresas.nombre AS empresa_nombre,
                        empresas.direccion AS empresa_direccion,
                        empresas.telefono AS empresa_telefono,
                        empresas.tipo_iva AS empresa_tipo_iva,
                        empresas.cuit AS empresa_cuit,
                        empresas.responsable AS empresa_responsable,
                        empresas.email AS empresa_email,
                        empresas.razon_social AS empresa_razon_social,
                        empresas.descripcion AS empresa_descripcion,
                        empresas.inicio_actividad AS empresa_inicio_actividad,
                        empresas.iibb AS empresa_iibb,
                        empresas.cert AS empresa_cert,
                        empresas.`key` AS empresa_key,
                        empresas.logo AS empresa_logo,
                        empresas.rubro_id AS empresa_rubro_id,
                        empresas.fecha_inicio_actividades AS empresa_fecha_inicio_actividades,
                        distribuidores_empresas.distribuidor_id,
                        distribuidores_empresas.empresa_id 
                    FROM
                        distribuidor
                        LEFT JOIN distribuidores_empresas ON distribuidor.id = distribuidores_empresas.distribuidor_id
                        LEFT JOIN empresas ON empresas.id = distribuidores_empresas.empresa_id
                        where distribuidor.id = $usuario_id";

            $result = $con->query($query);
           $empresas=array();
            $distribuidor = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                $empresa['id']=$row['empresa_id'];
                $empresa['nombre']=$row['empresa_nombre'];
                $empresa['direccion']=$row['empresa_direccion'];
                $empresa['telefono']=$row['empresa_telefono'];
                $empresa['tipo_iva']=$row['empresa_tipo_iva'];
                $empresa['cuit']=$row['empresa_cuit'];
                $empresa['responsable']=$row['empresa_responsable'];
                $empresa['email']=$row['empresa_email'];
                $empresa['razon_social']=$row['empresa_razon_social'];
                $empresa['descripcion']=$row['empresa_descripcion'];
                $empresa['inicio_actividad']=$row['empresa_inicio_actividad'];
                $empresa['iibb']=$row['empresa_iibb'];
                $empresa['cert']=($row['empresa_cert']);
                $empresa['key']=($row['empresa_key']);
                $empresa['logo']=$row['empresa_logo'];
                $empresa['rubro_id']=$row['empresa_rubro_id'];
                $empresa['fecha_inicio_actividades']=$row['empresa_fecha_inicio_actividades'];
                $empresa['distribuidor_id']=$row['distribuidor_id'];
                $empresa['empresa_id']=$row['empresa_id'];
                $distribuidor['id']=$row['id'];
                $distribuidor['nombre']=$row['nombre'];
                $distribuidor['telefono']=$row['telefono'];
                $distribuidor['responsable']=$row['responsable'];
                $logo=$row['logo']??'default/logo.png';
                $distribuidor['logo']='./images/logos/distribuidores/'.$logo;
                $distribuidor['email']=$row['email'];
                array_push($empresas,$empresa);
            }
            $distribuidor['empresas']=$empresas;
            $response = array("status" => 200, "token" => $token, "usuario_id" => $usuario_id,"distribuidor"=>$distribuidor);
        
        } else {
            $response = array("status" => 401, "message" => "Nombre de usuario o contraseña incorrectos.");
        }
    } else {
        $response = array("status" => 400, "message" => "Se requieren nombre de usuario y contraseña.");
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
