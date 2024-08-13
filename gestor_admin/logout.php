<?php
session_name('sesion_gestor');
session_start();

// Destruye la sesión actual.
session_destroy();

// Redirige al usuario a la página de inicio.
header('Location: index.php');
?>
