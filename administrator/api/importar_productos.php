<?php
include("../includes/database.php");
include("../includes/security.php");
include("../includes/sesion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

try {


    $headers = apache_request_headers();

    //POST PRODUCTOS
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        //archivo de excel en base64


        $empresa_id = $data['empresa_id'];
        $query_insert = "INSERT INTO productos (
    codigo,
    descripcion,
    descripcion_ampliada,
    familia_id,
    codigo_barra,
    proveedor_id,
    agrupacion_id,
    fecha_alta,
    fecha_actualizacion,
    tipo_id,
    moneda_id,
    tasa_iva_id,
    precio1,
    precio2,
    precio3,
    unidad_id,
    empresa_id,
    tipo_impuesto_interno
)
SELECT
    ip.codigo,
    ip.descripcion,
    ip.descripcion,
    (SELECT id FROM familias WHERE empresa_id = ip.empresa_id ORDER BY id LIMIT 1) AS familia_id,
    ip.codigo,
    (SELECT id FROM proveedores WHERE empresa_id = ip.empresa_id ORDER BY id LIMIT 1) AS proveedor_id,
    (SELECT id FROM agrupacion WHERE empresa_id = ip.empresa_id ORDER BY id LIMIT 1) AS agrupacion_id,
    NOW() AS fecha_alta,
    NOW() AS fecha_actualizacion,
    (SELECT id FROM tipo WHERE empresa_id = ip.empresa_id ORDER BY id LIMIT 1) AS tipo_id,
    (SELECT id FROM moneda ORDER BY id LIMIT 1) AS moneda_id,
    ip.tasa_iva AS tasa_iva_id,
    ip.precio1,
    ip.precio2,
    ip.precio3,
    (SELECT id FROM unidad WHERE empresa_id = ip.empresa_id ORDER BY id LIMIT 1) AS unidad_id,
    ip.empresa_id,
    1 
FROM
    importar_productos ip
WHERE
    ip.tipo = 'C'
    AND ip.empresa_id = $empresa_id";

        $query_update = "UPDATE productos p
        JOIN importar_productos ip ON p.codigo = ip.codigo AND p.empresa_id = ip.empresa_id
        SET
            p.precio1 = ip.precio1,
            p.precio2 = ip.precio2,
            p.precio3 = ip.precio3,
            p.tasa_iva = ip.tasa_iva,
            p.tasa_iva_id = ip.tasa_iva
        WHERE
            ip.tipo = 'U'
            AND ip.empresa_id = $empresa_id";

    $con->beginTransaction();
    $con->exec($query_insert);
    $con->exec($query_update);
    $con->commit();

        $response = array("status" => 200, "status_message" => "Archivo cargado correctamente");
    } else {
        throw new Exception("Método no permitido", 405);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    $error_msg = $errores_mysql[$e->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "{$error_msg}");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
