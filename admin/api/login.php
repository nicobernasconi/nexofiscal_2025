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
                usuarios.id,
                usuarios.nombre_usuario,
                usuarios.`password`,
                usuarios.nombre_completo,
                usuarios.rol_id,
                usuarios.activo,
                usuarios.empresa_id
              FROM
                usuarios
              WHERE nombre_usuario = '$nombre_usuario' AND password = '$password'";
    $result = $con->query($query);
    $usuario = $result->fetch(PDO::FETCH_ASSOC) ?? false;
    if (!$usuario) {
        return false;
    }


    $usuario_id = $usuario['id'];
    $empresa_id = $usuario['empresa_id'];

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
                        usuarios.id AS usuario_id,
                        usuarios.nombre_usuario,
                        usuarios.`password`,
                        usuarios.nombre_completo,
                        usuarios.rol_id,
                        usuarios.activo,
                        usuarios.empresa_id,
                        empresas.nombre AS empresa_nombre,
                        empresas.logo AS empresa_logo,
                        empresas.direccion AS empresa_direccion,
                        empresas.telefono AS empresa_telefono,
                        empresas.cuit AS empresa_cuit,
                        empresas.tipo_iva AS empresa_tipo_iva,
                        empresas.responsable AS empresa_responsable,
                        empresas.email AS empresa_email,
                        empresas.razon_social,
                        empresas.iibb,
                        empresas.fecha_inicio_actividades,
                        empresas.cert,
                        empresas.key,

                        JSON_ARRAYAGG( JSON_OBJECT( 'campo', configuraciones.campo, 'value', configuraciones.VALUE ) ) AS configuraciones 
                    FROM
                        usuarios
                        LEFT JOIN empresas ON usuarios.empresa_id = empresas.id
                        LEFT JOIN configuraciones ON configuraciones.empresa_id = empresas.id 
                    WHERE
                        usuarios.id = $usuario_id
                    GROUP BY
                        usuarios.id,
                        usuarios.nombre_usuario,
                        usuarios.`password`,
                        usuarios.nombre_completo,
                        usuarios.rol_id,
                        usuarios.activo,
                        usuarios.empresa_id,
                        empresas.nombre,
                        empresas.logo,
                        empresas.direccion,
                        empresas.telefono,
                        empresas.cuit,
                        empresas.tipo_iva,
                        empresas.responsable,
                        empresas.email,
                        empresas.razon_social,
                        empresas.iibb,
                        empresas.fecha_inicio_actividades,
                        empresas.cert,
                        empresas.key
                        ;";
            $result = $con->query($query);
            $usuario = $result->fetch(PDO::FETCH_ASSOC) ?? false;

            $rol_id=$usuario['rol_id'];
            $sql_rol="SELECT
                        funciones_roles.rol_id,
                        JSON_ARRAYAGG(
                            JSON_OBJECT(

                                funciones.nombre, JSON_ARRAY(
                                    CASE WHEN permite_ver = 1 THEN 'ver' END,
                                    CASE WHEN permite_crear = 1 THEN 'crear' END,
                                    CASE WHEN permite_modificar = 1 THEN 'modificar' END,
                                    CASE WHEN permite_eliminar = 1 THEN 'eliminar' END,
                                    CASE WHEN permite_imprimir = 1 THEN 'imprimir' END,
                                    CASE WHEN permite_listar = 1 THEN 'listar' END
                                )
                            )
                        ) AS funciones_permisos_json
                    FROM
                        funciones_roles
                    LEFT JOIN funciones ON funciones_roles.funcion_id = funciones.id
                    WHERE funciones_roles.rol_id=$rol_id
                    GROUP BY
                        funciones_roles.rol_id
                       ";
            $result_rol = $con->query($sql_rol);
            $rol = $result_rol->fetch(PDO::FETCH_ASSOC) ?? false;
            $usuario['permisos']=json_decode($rol['funciones_permisos_json'],true);
            
            $empresa = array();
            $configuraciones = json_decode($usuario['configuraciones'], true);
            $empresa['id'] = $usuario['empresa_id'];
            $empresa['nombre'] = $usuario['empresa_nombre'];
            $empresa['logo'] = $usuario['empresa_logo'];
            $empresa['direccion'] = $usuario['empresa_direccion'];
            $empresa['telefono'] = $usuario['empresa_telefono'];
            $empresa['cuit'] = $usuario['empresa_cuit'];
            $empresa['tipo_iva'] = $usuario['empresa_tipo_iva'];
            $empresa['responsable'] = $usuario['empresa_responsable'];
            $empresa['email'] = $usuario['empresa_email'];
            $empresa['razon_social'] = $usuario['razon_social'];
            $empresa['iibb'] = $usuario['iibb'];
            $empresa['fecha_inicio_actividades'] = $usuario['fecha_inicio_actividades'];
            $empresa['cert'] = $usuario['cert'];
            $empresa['key'] = $usuario['key'];

            $usuario['empresa'] = $empresa;
            $usuario['configuraciones'] = $configuraciones;

            // Eliminamos los campos que no queremos enviar
            unset($usuario['empresa_nombre']);
            unset($usuario['empresa_logo']);
            unset($usuario['empresa_direccion']);
            unset($usuario['empresa_telefono']);
            unset($usuario['empresa_cuit']);
            unset($usuario['empresa_tipo_iva']);
            unset($usuario['empresa_responsable']);
            unset($usuario['empresa_email']);
            unset($usuario['empresa_razon_social']);
            unset($usuario['empresa_iibb']);
            unset($usuario['empresa_fecha_inicio_actividades']);
            unset($usuario['empresa_cert']);
            unset($usuario['empresa_key']);
            
            unset($usuario['password']);
            unset($usuario['activo']);
            unset($usuario['empresa_id']);
            

            

            $response = array("status" => 200, "token" => $token, "usuario_id" => $usuario_id, "usuario" => $usuario);
        } else {
            $response = array("status" => 401, "message" => "Nombre de usuario o contraseña incorrectos.");
        }
    } else {
        $response = array("status" => 400, "message" => "Se requieren nombre de usuario y contraseña.");
    }
    header('Content-Type: application/json');
    echo json_encode($response);
}
