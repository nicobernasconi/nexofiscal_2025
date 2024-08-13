<?php
include("config.php");
// Iniciar la sesión si no está iniciada

if (session_status() == PHP_SESSION_NONE) {
    session_start(); 

}
if($_SESSION['time']+$tiempo_expiracion<time()){
    session_destroy();
    header('location:login.php');
}
if(!$_SESSION['login']){
header('location:login.php');
}


$permisos=$_SESSION['permisos']; 
// Decodificar el JSON en un arreglo asociativo
$arreglo_json = $permisos;

// Variable para almacenar los resultados
$permisos_asignados = [];

// Iterar sobre cada elemento del arreglo JSON
foreach ($arreglo_json as $elemento) {
    // Obtener el nombre de la función
    $nombre_funcion = key($elemento);

    // Obtener los permisos asociados a la función
    $permisos = current($elemento);

    // Filtrar los permisos para eliminar los valores nulos
    $permisos_filtrados = array_filter($permisos);

    // Agregar el nombre de la función y los permisos filtrados al arreglo de resultados
    $permisos_asignados[$nombre_funcion] = $permisos_filtrados;
}


?>