<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {


    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $query_product = "SELECT
                            cierres_cajas.id,
                            cierres_cajas.fecha,
                            cierres_cajas.total_ventas,
                            cierres_cajas.total_gastos,
                            cierres_cajas.efectivo_inicial,
                            cierres_cajas.efectivo_final,
                            cierres_cajas.usuario_id,
                            cierres_cajas.tipo_caja_id,
                            usuarios.nombre_completo as usuario_nombre_completo
                        FROM
                            cierres_cajas LEFT JOIN usuarios ON cierres_cajas.usuario_id = usuarios.id
                            LEFT JOIN distribuidores_empresas ON distribuidores_empresas.empresa_id=cierres_cajas.empresa_id
                        ";

        $query_param = array();
if (isset($_GET['order_by'])) {
        $order_by = sanitizeInput($_GET['order_by']);
    }else{
        $order_by ='id';
    }
if (isset($_GET['sort_order'])) {
        $sort_order= sanitizeInput($_GET['sort_order']);
    }else{
        $sort_order=' ASC ';
    }



        //recibir todos los posibles parametros por GET
        if (isset($_GET['param'])) {
            $id = sanitizeInput($_GET['param']);
            array_push($query_param, "id=$id");
        }
        if (isset($_GET['fecha'])) {
            $descripcion = sanitizeInput($_GET['fecha']);
            array_push($query_param, "fecha like '%$descripcion%'");
        }

        if (isset($_GET['total_ventas'])) {
            $descripcion = sanitizeInput($_GET['total_ventas']);
            array_push($query_param, "total_ventas like '%$descripcion%'");
        }

        if (isset($_GET['efectivo_inicial'])) {
            $descripcion = sanitizeInput($_GET['efectivo_inicial']);
            array_push($query_param, "efectivo_inicial like '%$descripcion%'");
        }

        if (isset($_GET['efectivo_final'])) {
            $descripcion = sanitizeInput($_GET['efectivo_final']);
            array_push($query_param, "efectivo_final like '%$descripcion%'");
        }

        if (isset($_GET['usuario_id'])) {
            $descripcion = sanitizeInput($_GET['usuario_id']);
            array_push($query_param, "usuario_id like '%$descripcion%'");
        }

        if (isset($_GET['tipo_caja_id'])) {
            $descripcion = sanitizeInput($_GET['tipo_caja_id']);
            array_push($query_param, "tipo_caja_id like '%$descripcion%'");
        }

        if(isset($_GET['usuario_nombre_completo'])){
            $descripcion = sanitizeInput($_GET['usuario_nombre_completo']);
            array_push($query_param, "usuarios.nombre_completo like '%$descripcion%'");
        }

        if(isset($_GET['distribuidor_id'])){
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
            array_push($query_param, "distribuidores_empresas.distribuidor_id = $distribuidor_id");
        }


       

        if (count($query_param) > 0) {
            $query_product = $query_product . " WHERE (" . implode(" and ", $query_param).")";
        }else{
            $query_product = $query_product . " ";
        }


        //obtener el total de registros
        $query_total = "SELECT COUNT(*) AS total FROM cierres_cajas LEFT JOIN distribuidores_empresas ON distribuidores_empresas.empresa_id=cierres_cajas.empresa_id WHERE distribuidores_empresas.distribuidor_id = $distribuidor_id";
        $result_total = $con->query($query_total);
        $row_total = $result_total->fetch(PDO::FETCH_ASSOC);
        $total = $row_total['total'] ?? 0;
        //limites de la paginacion
        $limit =$_GET['limit'] ?? 255;
        $cont_pages = ceil($total / $limit);
        $offset = $_GET['offset'] ?? 0;

        $query_product = $query_product . " ORDER BY cierres_cajas. ".$order_by."  ".$sort_order." LIMIT $limit OFFSET $offset";

        //header con la informacion de la paginacion
        header("X-Total-Count: $total");
        header("Access-Control-Expose-Headers: X-Total-Count");
        header("X-Total-Pages: $cont_pages");
        header("Access-Control-Expose-Headers: X-Total-Pages");
        header("X-Current-Page: $offset");
        header("Access-Control-Expose-Headers: X-Current-Page");
        header("X-Per-Page: $limit");
        header("Access-Control-Expose-Headers: X-Per-Page");



        $result = $con->query($query_product);
        $cierres_cajas = array();
        $usaurio= array();
        $response = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $usaurio = array(
                'id' => $row['usuario_id'],
                'nombre' => $row['usuario_nombre_completo']
            );
            $cierres_cajas = array(
                'id' => $row['id'],
                'fecha' => $row['fecha'],
                'total_ventas' => $row['total_ventas'],
                'total_gastos' => $row['total_gastos'],
                'efectivo_inicial' => $row['efectivo_inicial'],
                'efectivo_final' => $row['efectivo_final'],
                'tipo_caja_id' => $row['tipo_caja_id'],
                'usuario' => $usaurio

            );


            array_push($response, $cierres_cajas);
        }
    }
    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        $param_insert = array();
        $param_values = array();

        $usuario_id = $data['usuario_id'];
        

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';

            array_push($param_insert, $key);
            array_push($param_values, $escaped_value);
        }


        //obtener el total de ventas
        $query_total_ventas = "SELECT SUM(total) AS total_ventas FROM comprobantes WHERE cierre_caja_id IS NULL AND usuario_id = $usuario_id";
        $result_total_ventas = $con->query($query_total_ventas);
        $row_total_ventas = $result_total_ventas->fetch(PDO::FETCH_ASSOC);
        $total_ventas = $row_total_ventas['total_ventas'] ?? 0;
        //obtener el total de gastos
        $query_total_gastos = "SELECT SUM(monto) AS total_gastos FROM gastos WHERE cierre_caja_id IS NULL AND usuario_id = $usuario_id";
        $result_total_gastos = $con->query($query_total_gastos);
        $row_total_gastos = $result_total_gastos->fetch(PDO::FETCH_ASSOC);
        $total_gastos = $row_total_gastos['total_gastos'] ?? 0;

        array_push($param_insert, 'total_ventas');
        array_push($param_values, $total_ventas);
        array_push($param_insert, 'total_gastos');
        array_push($param_values, $total_gastos);

        
        $query_insert = "INSERT INTO cierres_cajas (" . implode(", ", $param_insert) . ") VALUES (" . implode(", ", $param_values) . ")";
        $result = $con->query($query_insert);


        $query_id = "SELECT MAX(id) AS id FROM cierres_cajas";

        $result_id = $con->query($query_id);
        $row_id = $result_id->fetch(PDO::FETCH_ASSOC);
        $id = $row_id['id'] ?? null;
      
        if ($result) {
            $query_update = "UPDATE comprobantes SET cierre_caja_id = $id WHERE cierre_caja_id IS NULL AND usuario_id = $usuario_id";
            $result = $con->query($query_update);
            $query_update = "UPDATE gastos SET cierre_caja_id = $id WHERE cierre_caja_id IS NULL AND usuario_id = $usuario_id";
            $result = $con->query($query_update);

            $response = array("status" => 201, "status_message" => "Cierre de Cajaagregada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al agregar Cierre de Caja.", "id" => $id);
        }
    }

    //PUT PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $data = json_decode(file_get_contents('php://input'), true);
        $param_update = array();
        $param_update_values = array();

        foreach ($data as $key => $value) {
            // Evitar inyección de SQL y manejar correctamente los valores nulos
            $escaped_value = ($value !== null) ? "'" . str_replace("'", "''", $value) . "'" : 'NULL';

            array_push($param_update, $key . "=" . $escaped_value);
        }
        $id = sanitizeInput($_GET['param']);
        $query_update = "UPDATE cierres_cajas SET " . implode(", ", $param_update) . " WHERE cierres_cajas.id = " . $id;
        $result = $con->query($query_update);
        if ($result) {
            $response = array("status" => 201, "status_message" => "Cierre de Caja actualizada correctamente.", "id" => $id);
        } else {
            $response = array("status" => 400, "status_message" => "Error al actualizar Cierre de Caja.", "id" => $id);
        }
    }
    //DELETE PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
$id = sanitizeInput($_GET['param']);

        // Utilizar una consulta preparada para evitar inyección SQL
        $query_delete = "DELETE FROM cierres_cajas WHERE cierres_cajas.id = :id";
        $stmt = $con->prepare($query_delete);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Verificar si se eliminó algún registro
            if ($stmt->rowCount() > 0) {
                $response = array("status" => 201, "status_message" => "Cierre de Caja eliminada correctamente.", "id" => $id);
            } else {
                $response = array("status" => 404, "status_message" => "No se encontró la Cierre de Caja para eliminar.", "id" => $id);
            }
        } else {
            $response = array("status" => 400, "status_message" => "Error al eliminar Cierre de Caja.", "id" => $id);
        }
    }
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
   $error_msg=$errores_mysql[$th->getCode()]??"Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg} $query_product");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
