<?php
//configuracion de los parametros de la base de datos MS SQL SERVER
/*$serverName = "NICO\BD01";
$dbname = "nexofiscal";
$username = "seic";
$password = "Seic123";
$tipo_base = 'MSSQL';
*/
//conexion con la base de datos
$tipo_base = 'MYSQL';
$serverName = "190.228.29.53";
$dbname = "nexofiscal";
$username = "nexofiscal";
$password = "gqavOLLtkDB1";
try {
	if ($tipo_base == 'MSSQL') {
		$con = new PDO("sqlsrv:server=$serverName;Database=$dbname;ConnectionPooling=0", $username, $password);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	if ($tipo_base === 'MYSQL') {
		 $con = new PDO("mysql:host=$serverName;dbname=$dbname", $username, $password);
		$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
} catch (PDOException $e) {
	echo "Error: " . $e->getMessage();
}


$errores_mysql = array(
    1062 => "Ya existe un registro con la misma clave única.",
    1216 => "La restricción de clave foránea no se puede agregar o actualizar porque existe una fila secundaria que viola la restricción.",
    1217 => "La restricción de clave foránea falló.",
    1364 => "Se intentó insertar un valor NULL en una columna que no admite valores nulos.",
    1451 => "No se puede eliminar o actualizar una fila porque hay una restricción de clave foránea que la referencia.",
    1452 => "No se puede agregar o actualizar una fila porque violaría una restricción de clave foránea.",
    1557 => "No se puede eliminar una fila porque está siendo referenciada por una tabla secundaria.",
    1568 => "No se puede agregar un valor de clave externa porque no existe una clave principal correspondiente.",
    2002 => "No se pudo establecer conexión con el servidor.",
    2003 => "No se pudo conectar al servidor.",
    2013 => "Se perdió la conexión al servidor durante la consulta.",
    1045 => "Acceso denegado para el usuario y la contraseña proporcionados.",
    1049 => "La base de datos especificada no existe.",
    1054 => "La columna especificada en la consulta no existe.",
    1064 => "Error de sintaxis en la consulta SQL.",
	22007 => "Error de conversión de cadena. Un dato no se puede convertir a una fecha o valor numérico.",
    23000 => "Esta entidad no se puede eliminar porque tiene registros relacionados.",




);
    function sanitizeInput($input) {
    // Array de caracteres problemáticos y sus reemplazos
    $caracteresProblematicos = array("'", "\"", ";", "--", "#", /* Otros caracteres que desees */);
    $reemplazos = array("\\'", "\\\"", "\\;", "\\--", "\\#", /* Otros reemplazos que desees */);

    // Reemplazar caracteres problemáticos
    $inputLimpio = str_replace($caracteresProblematicos, $reemplazos, $input);

    return $inputLimpio;
}