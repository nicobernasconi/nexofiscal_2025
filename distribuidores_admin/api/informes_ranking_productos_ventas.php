<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");
try {



    $headers = apache_request_headers();


    //GET PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['distribuidor_id'])) {
            $distribuidor_id = sanitizeInput($_GET['distribuidor_id']);
        }
        $query_ranking_ventas = "SELECT
        SUM(renglones_comprobantes.cantidad) / total_cantidades.total * 100 AS porcentaje,
        productos.descripcion AS productos,
        SUM(renglones_comprobantes.cantidad) AS total
    FROM
        renglones_comprobantes
    LEFT JOIN productos ON renglones_comprobantes.producto_id = productos.id
    LEFT JOIN comprobantes ON renglones_comprobantes.comprobante_id = comprobantes.id
    LEFT JOIN distribuidores_empresas ON comprobantes.empresa_id = distribuidores_empresas.empresa_id
    LEFT JOIN (
        SELECT
            SUM(renglones_comprobantes.cantidad) AS total
        FROM
            renglones_comprobantes
        LEFT JOIN comprobantes ON renglones_comprobantes.comprobante_id = comprobantes.id
        LEFT JOIN distribuidores_empresas ON comprobantes.empresa_id = distribuidores_empresas.empresa_id
        WHERE
            comprobantes.fecha_baja IS NULL
    ) AS total_cantidades ON 1=1
    WHERE
        comprobantes.fecha_baja IS NULL AND distribuidores_empresas.distribuidor_id = $distribuidor_id
    GROUP BY
        productos.descripcion
    ORDER BY
        total DESC
    LIMIT 4";
       
           $result_ranking_ventas = $con->query($query_ranking_ventas);  
              $ranking_ventas = $result_ranking_ventas->fetchAll(PDO::FETCH_ASSOC);
              $ranking_productos = array();
              foreach ($ranking_ventas as $row) {
                  $ranking_productos[] = array(
                      "productos" => $row['productos'],
                      "total" => $row['total'],
                      "porcentaje" => $row['porcentaje']
                  );
              }   

       
        $response = array(
            "status" => 200,
            "status_message" => "OK",
            "data" => $ranking_productos
        );
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (PDOException $th) {
    $error_msg = $errores_mysql[$th->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}: {$th->getMessage()}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
