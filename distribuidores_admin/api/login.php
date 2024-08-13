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
                        usuarios.venta_rapida,
                        usuarios.tipo_comprobante_imprimir,
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
                        codigos_barras.id_long,
                        codigos_barras.inicio,
                        codigos_barras.long,
                        codigos_barras.payload_int,
                        codigos_barras.payload_type,
                        sucursales.id AS sucursal_id,
                        sucursales.nombre AS sucursal_nombre,
                        sucursales.direccion AS sucursal_direccion,
                        puntos_venta.id AS punto_venta_id,
                        puntos_venta.numero AS punto_venta_numero,
                        puntos_venta.descripcion AS punto_venta_descripcion,
                        ( SELECT vendedores.id FROM vendedores WHERE vendedores.sucursal_id = usuarios.sucursal_id LIMIT 1 ) AS vendedor_id,
                        ( SELECT vendedores.nombre FROM vendedores WHERE vendedores.sucursal_id = usuarios.sucursal_id LIMIT 1 ) AS vendedor_nombre 
                    FROM
                        usuarios
                        LEFT JOIN empresas ON usuarios.empresa_id = empresas.id
                        LEFT JOIN codigos_barras ON codigos_barras.empresa_id = empresas.id
                        LEFT JOIN sucursales ON sucursales.id = usuarios.sucursal_id 
                        LEFT JOIN puntos_venta ON puntos_venta.id = usuarios.punto_venta_id
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
                        usuarios.tipo_comprobante_imprimir,
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
                        empresas.key,
                        codigos_barras.id_long,
                        codigos_barras.inicio,
                        codigos_barras.long,
                        codigos_barras.payload_int,
                        codigos_barras.payload_type,
                        sucursales.id,
                        sucursales.nombre,
                        sucursales.direccion;";
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
            $sucursal = array();
            $vendedor = array();
            $punto_venta = array();
            $punto_venta['id'] = $usuario['punto_venta_id'];
            $punto_venta['numero'] = $usuario['punto_venta_numero'];
            $punto_venta['descripcion'] = $usuario['punto_venta_descripcion'];
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
            $empresa['cert'] = ($usuario['cert']);
            $empresa['key'] = ($usuario['key']);
            $empresa['codigos_barras_id_long'] = $usuario['id_long'];
            $empresa['codigos_barras_long'] = $usuario['long'];
            $empresa['codigos_barras_inicio'] = $usuario['inicio'];
            $empresa['codigos_barras_payload_int'] = $usuario['payload_int'];
            $empresa['codigos_barras_payload_type'] = $usuario['payload_type'];
            $usuario['empresa'] = $empresa;
            $sucursal['id'] = $usuario['sucursal_id'];
            $sucursal['nombre'] = $usuario['sucursal_nombre'];
            $sucursal['direccion'] = $usuario['sucursal_direccion'];
            $usuario['sucursal'] = $sucursal;
            $vendedor['id'] = $usuario['vendedor_id'];
            $vendedor['nombre'] = $usuario['vendedor_nombre'];
            $usuario['vendedor'] = $vendedor;
            $usuario['punto_venta'] = $punto_venta;

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
            unset($usuario['id_long']);
            unset($usuario['inicio']);
            unset($usuario['payload_int']);
            unset($usuario['payload_type']);
            unset($usuario['sucursal_id']);
            unset($usuario['sucursal_nombre']);
            unset($usuario['sucursal_direccion']);
            unset($usuario['vendedor_id']);
            unset($usuario['vendedor_nombre']);
            unset($usuario['punto_venta_id']);
            unset($usuario['punto_venta_numero']);
            unset($usuario['punto_venta_descripcion']);

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
