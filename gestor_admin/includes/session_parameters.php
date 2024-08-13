<?php
include("config.php");
// Iniciar la sesión si no está iniciada

if (session_status() == PHP_SESSION_NONE) {
    session_name('sesion_gestor');
session_start(); 

}
if($_SESSION['time']+$tiempo_expiracion<time()){
    session_destroy();
    header('location:login.php');
}
if(!$_SESSION['login_gestor']){
header('location:login.php');
}



?>