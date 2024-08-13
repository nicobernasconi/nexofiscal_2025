<?php
$headers = apache_request_headers();
if (isset($headers['Authorization']) && !empty($headers['Authorization'])) {
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    $verificacion = verificarToken($token,$key,$kid);
    $usuario_id = $verificacion['usuario_id'];
    $empresa_id = $verificacion['empresa_id'];
    



    if ($usuario_id == false) {
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