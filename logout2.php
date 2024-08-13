<?php
// Inicia una sesión o retoma una existente
session_start();

// Destruye todas las variables de sesión
$_SESSION = [];

// Si se desea destruir la sesión completamente, también se deben borrar las cookies de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], 'nexofiscal.com.ar',
        $params["secure"], $params["httponly"]
    );
}

// Finalmente, destruye la sesión
session_destroy();

// Limpia el directorio de sesiones
session_write_close();
$sessionFiles = glob(session_save_path() . '/sess_*');
foreach ($sessionFiles as $file) {
    unlink($file);
}

echo "Todas las sesiones para el dominio nexofiscal.com.ar han sido destruidas.";
?>
