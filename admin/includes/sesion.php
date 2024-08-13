<?php
$headers = apache_request_headers();
if (isset($headers['Authorization']) && !empty($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $verificacion = verificarToken($token,$key,$kid);
    $distribuidor_id = $verificacion['distribuidor_id'];




    if ($distribuidor_id == false) {
        $respuesta = array('status' => true, 'mensaje' => 'Error al verificar el credencial del usuario');
        echo json_encode($respuesta);
        header('Content-Type: application/json');
        exit;
    } 
} else {
    $respuesta = array('error' => true, 'mensaje' => 'No se enviaron las credenciales');
    echo json_encode($respuesta);
    header('Content-Type: application/json');
    exit;
}
?>