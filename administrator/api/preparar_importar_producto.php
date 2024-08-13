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

        $file = $data['file'];
        $empresa_id = $data['empresa_id'];
        // Remove the 'data:application/vnd.ms-excel;base64,' part from the data
        $base64String = str_replace('data:application/vnd.ms-excel;base64,', '', $file);

        // Convert base64 string to binary data
        $fileDecoded = base64_decode($base64String);

        // Save the binary data to a temporary file
        $tempName = tempnam(sys_get_temp_dir(), 'excel');
        file_put_contents($tempName, $fileDecoded);

        // Load the spreadsheet file
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader('Xls');
        $spreadsheet = $reader->load($tempName);

        // Now you can access the data in the spreadsheet
        $worksheet = $spreadsheet->getActiveSheet();
        $data = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
            $cells = [];
            foreach ($cellIterator as $cell) {
                $cells[] = $cell->getValue();
            }
            $data[] = $cells;
            // Do something with the cells
        }
        //controlar si la primer fila es el encabezado ["CODIGO", "DESCRIPCION", "PRECIO1", "PRECIO2", "PRECIO3", "TASA_IVA"]
        $encabezado = array_shift($data);
        if ($encabezado != ["CODIGO", "DESCRIPCION", "PRECIO1", "PRECIO2", "PRECIO3", "TASA_IVA"]) {
            throw new Exception("El archivo no tiene el formato correcto", 1);
        }
        //controlar si la cantidad de columnas es correcta
        foreach ($data as $key => $value) {
            if (count($value) != 6) {
                throw new Exception("El archivo no tiene el formato correcto", 1);
            }
        }
        //controlar si el codigo de producto es unico
        $codigos = array_column($data, 0);
        $codigos_unicos = array_unique($codigos);
        if (count($codigos) != count($codigos_unicos)) {
            throw new Exception("El archivo no tiene el formato correcto", 1);
        }
        //limpiar la tabla importar_productos para la empresa_id
        $query = "DELETE FROM importar_productos WHERE empresa_id=$empresa_id";
        $con->exec($query);
        //insertar los datos en la tabla importar_productos
        $query = "INSERT INTO importar_productos (empresa_id, codigo, descripcion, precio1, precio2, precio3, tasa_iva) VALUES ";
        $values = [];
        foreach ($data as $key => $value) {
            $query_iva = "SELECT id FROM tasa_iva WHERE empresa_id=$empresa_id AND tasa * 100=" . $value[5];
            $result = $con->query($query_iva);
            $row = $result->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $query_iva = "SELECT id FROM tasa_iva WHERE empresa_id=$empresa_id LIMIT 1";
                $result = $con->query($query_iva);
                $row = $result->fetch(PDO::FETCH_ASSOC);
                $tipo_iva_id = $row['id'];
            }else{
                $tipo_iva_id = $row['id'];
            }

            //si no retorna un id, tomar el primer tipo_iva_id de la empresa_id
           
            $values[] = "($empresa_id, '{$value[0]}', '{$value[1]}', {$value[2]}, {$value[3]}, {$value[4]}, $tipo_iva_id)";           
        }
        $query .= implode(",", $values);
        $con->exec($query);
        //asignar el tipo_iva a los productos
        //busco en la tabla tipo_iva el id del tipo_iva que tenga el valor de tasa_iva*100 que pertece a la empresa_id

        //actualizo la tabla importar_productos con el tipo_iva_id


        //borrar el archivo temporal
        unlink($tempName);
        //establecer el campo tipo en, C para los productos que no existen en la tabla productos y U para los que existen
        $query = "UPDATE importar_productos SET tipo = 'C' WHERE empresa_id=$empresa_id AND codigo NOT IN (SELECT codigo FROM productos WHERE empresa_id=$empresa_id)";
        $con->exec($query);
        $query = "UPDATE importar_productos SET tipo = 'U' WHERE empresa_id=$empresa_id AND codigo IN (SELECT codigo FROM productos WHERE empresa_id=$empresa_id)";
        $con->exec($query);
        //devolver cualtos productos se van a insertar y cuantos se van a actualizar
        $query = "SELECT count(*) as count_insertar FROM importar_productos WHERE empresa_id=$empresa_id AND tipo='C'";
        $result = $con->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $count_insertar = $row['count_insertar'];
        $query = "SELECT count(*) as count_actualizar FROM importar_productos WHERE empresa_id=$empresa_id AND tipo='U'";
        $result = $con->query($query);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $count_actualizar = $row['count_actualizar'];
        $response = array("status" => 200, "status_message" => "Archivo cargado correctamente", "count_insertar" => $count_insertar, "count_actualizar" => $count_actualizar);
    } else {
        throw new Exception("Método no permitido", 405);
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
} catch (Exception $e) {
    $error_msg = $errores_mysql[$e->getCode()] ?? "Error desconocido";
    $response = array("status" => 500, "status_message" => "Error en la carga del archivo");
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}
