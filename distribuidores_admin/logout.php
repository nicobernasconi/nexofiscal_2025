<?php
session_name('sesion_distribuidor');
session_start();

// Destruye la sesión actual.
session_destroy();

// Redirige al usuario a la página de inicio.
header('Location: index.php');
?>
