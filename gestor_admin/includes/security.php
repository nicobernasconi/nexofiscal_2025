<?php
include("config.php");
require '../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

function crearToken($usuario_id,$empresa_id, $key, $kid,$tiempo_expiracion) {

    $token = array(
        "iss" => "http://nexofiscal.com.ar", // Emisor del token
        "aud" => "http://nexofiscal.com.ar", // Audiencia a la que va dirigido
        "iat" => time(), // Tiempo de emisión del token
        "exp" => time() + $tiempo_expiracion, // Tiempo de expiración del token
        "empresa_id" => $empresa_id,
        "usuario_id" => $usuario_id,
        "kid" => $kid // Agrega un identificador de clave (puedes usar cualquier valor único)
    );

    $jwt = JWT::encode($token, $key, 'HS256');
    return $jwt;
}

function verificarToken($token, $key, $kid) {
    try {
        $decoded = JWT::decode($token, new Key($key, 'HS256'));
        $kid_claim = $decoded->kid ?? null;

        // Verificar si el campo "kid" en el token coincide con el proporcionado
        if ($kid_claim !== $kid) {
            throw new Exception("Token inválido: El campo 'kid' en el token no coincide con el esperado.");
        }

        // Ahora, puedes acceder a otras partes del token si es necesario
        $usuario_id = $decoded->usuario_id;
        $empresa_id = $decoded->empresa_id;

        return array('usuario_id' => $usuario_id, 'empresa_id' => $empresa_id);
    } catch (Exception $e) {
        return array('usuario_id' => false, 'empresa_id' => false);
        
    }
}
