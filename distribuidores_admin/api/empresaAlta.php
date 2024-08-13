<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {
    $headers = apache_request_headers();

    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $nombre_empresa = $data['nombre_empresa'];
        $direccion_empresa = $data['direccion_empresa'];
        $telefono_empresa = $data['telefono_empresa'];
        $descripcion_empresa = $data['descripcion_empresa'];
        $responsable_empresa = $data['responsable_empresa'];
        $fecha_inicio_actividades = $data['fecha_inicio_actividades'];
        $cuit = $data['cuit'];
        $email_empresa = $data['email_empresa'];
        $tipo_iva_id = $data['tipo_iva_id'];

        // Additional parameters required by the stored procedure
        $password = $data['password'];
        $nombre_sucursal = $data['nombre_sucursal'];
        $direccion_sucursal = $data['direccion_sucursal'];
        $telefono_sucursal = $data['telefono_sucursal'];
        $email_sucursal = $data['email_sucursal'];
        $distribuidor_id = $data['distribuidor_id'];

        // Prepare the statement for calling the stored procedure
        $stmt = $con->prepare("CALL crear_empresa(:p_nombre_empresa, :p_direccion_empresa, :p_telefono_empresa, :p_descripcion_empresa, :p_responsable_empresa, :p_fecha_inicio_actividades, :p_cuit, :p_email_empresa, :p_password, :p_nombre_sucursal, :p_direccion_sucursal, :p_telefono_sucursal, :p_email_sucursal, :p_distribuidor_id, :p_tipo_iva_id)");

        // Bind parameters
        $stmt->bindParam(':p_nombre_empresa', $nombre_empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p_direccion_empresa', $direccion_empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p_telefono_empresa', $telefono_empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p_descripcion_empresa', $descripcion_empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p_responsable_empresa', $responsable_empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p_fecha_inicio_actividades', $fecha_inicio_actividades, PDO::PARAM_STR);
        $stmt->bindParam(':p_cuit', $cuit, PDO::PARAM_INT);
        $stmt->bindParam(':p_email_empresa', $email_empresa, PDO::PARAM_STR);
        $stmt->bindParam(':p_password', $password, PDO::PARAM_STR);
        $stmt->bindParam(':p_nombre_sucursal', $nombre_sucursal, PDO::PARAM_STR);
        $stmt->bindParam(':p_direccion_sucursal', $direccion_sucursal, PDO::PARAM_STR);
        $stmt->bindParam(':p_telefono_sucursal', $telefono_sucursal, PDO::PARAM_STR);
        $stmt->bindParam(':p_email_sucursal', $email_sucursal, PDO::PARAM_STR);
        $stmt->bindParam(':p_distribuidor_id', $distribuidor_id, PDO::PARAM_INT);
        $stmt->bindParam(':p_tipo_iva_id', $tipo_iva_id, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->closeCursor();

        $response = array("status" => 201, "status_message" => "Empresa creada exitosamente");
    } else {
        $response = array("status" => 400, "status_message" => "Bad request");
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
?>
