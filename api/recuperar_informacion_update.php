<?php

include("../includes/database.php");
include("../includes/config.php");
include("../includes/security.php");
require '../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

function escapeValue($value)
{
    return $value === '' ? 'NULL' : "'" . addslashes($value) . "'";
}

// Lógica principal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        $empresa_id = $data['empresa_id'];

        try {
            $archivoEstructura = "./update_scripts/estructura_empresa$empresa_id.sql";
            $archivoDatosFiltrados = "./update_scripts/datos_filtrados$empresa_id.sql";

            $tablas = [
                'moneda',
                'agrupacion',
                'categoria',
                'puntos_venta',
                'sucursales',
                'roles',
                'usuarios',
                'tipo_documento',
                'tipo_iva',
                'vendedores',
                'clientes',      // Insert con ON DUPLICATE KEY UPDATE
                'codigos_barras',
                'tasa_iva',
                'familias',
                'marcas',
                'subcategoria',
                'proveedores',
                'subfamilias',
                'tipo',
                'unidad',
                'productos',     // Insert con ON DUPLICATE KEY UPDATE
                'condicion_venta',
                'forma_pago',
                'configuraciones',
                'funciones',
                'funciones_roles',
                'movimientos_stock',
                'promociones',
                'pagos'
            ];

            // Exportar datos filtrados
            $archivoDatos = fopen($archivoDatosFiltrados, 'w');

            // Deshabilitar las claves foráneas
            fwrite($archivoDatos, "SET foreign_key_checks = 0;\n");

            // Fecha límite para los últimos 2 días
            $fechaLimite = date('Y-m-d H:i:s', strtotime('-2 days'));

            foreach ($tablas as $tabla) {
                try {
                    // Si es la tabla 'clientes' o 'productos', solo considerar los registros modificados en los últimos 2 días
                    if ($tabla == 'productos' || $tabla == 'clientes') {
                        $query = "SELECT * FROM $tabla WHERE empresa_id = $empresa_id AND fecha_actualizacion >= '$fechaLimite'";
                    } else {
                        // Para las demás tablas, obtener todos los registros
                        $query = "SELECT * FROM $tabla WHERE empresa_id = $empresa_id";
                    }

                    $resultado = $con->query($query);

                    if ($resultado != false) {
                        // Procesar todas las filas de la tabla
                        while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
                            $campos = array_keys($fila);

                            // Remover todos los campos que contienen '_id', 'id' o fecha
                            $campos = array_filter($campos, function($campo) {
                                return !str_contains($campo, '_id') && $campo !== 'id' && !str_contains($campo, 'fecha');
                            });

                            // Añadir el campo 'id_remoto' si existe 'id' en los valores
                            if (isset($fila['id'])) {
                                $fila['id_remoto'] = $fila['id'];
                                unset($fila['id']); // Eliminar 'id'
                                $campos[] = 'id_remoto';  // Añadir 'id_remoto' al listado de campos
                            }

                            // Para las tablas 'productos' y 'clientes', generar sentencias INSERT con ON DUPLICATE KEY UPDATE
                            if ($tabla == 'productos' || $tabla == 'clientes') {
                                $camposInsert = implode('`, `', $campos);
                                $valoresInsert = implode(', ', array_map('escapeValue', array_intersect_key($fila, array_flip($campos))));

                                // Generar la parte de SET para el ON DUPLICATE KEY UPDATE
                                $camposUpdate = array_map(function($campo) use ($fila) {
                                    return "`$campo` = " . escapeValue($fila[$campo]);
                                }, $campos);
                                //agreha el campo empresa_id
                                $camposUpdate[] = "`empresa_id` = " . escapeValue($empresa_id);

                                $updateList = implode(", ", $camposUpdate);

                                // Generar la sentencia INSERT ON DUPLICATE KEY UPDATE
                                $insertQuery = "INSERT INTO $tabla (`$camposInsert`) VALUES ($valoresInsert) ON DUPLICATE KEY UPDATE $updateList;\n";
                                // Reemplazar '' por NULL
                                $insertQuery = str_replace("''", "NULL", $insertQuery);

                                // Escribir la sentencia en el archivo
                                fwrite($archivoDatos, $insertQuery);
                            } else {
                                // Para las demás tablas, generar sentencias UPDATE
                                $camposUpdate = array_map(function($campo) use ($fila) {
                                    return "`$campo` = " . escapeValue($fila[$campo]);
                                }, $campos);

                                $updateList = implode(", ", $camposUpdate);

                                // Generar la sentencia UPDATE utilizando 'id_remoto' en el WHERE
                                if (isset($fila['id_remoto'])) {
                                    $updateQuery = "UPDATE $tabla SET $updateList WHERE `id_remoto` = " . escapeValue($fila['id_remoto']) . ";\n";
                                    // Reemplazar '' por NULL
                                    $updateQuery = str_replace("''", "NULL", $updateQuery);
                                    
                                    // Escribir la sentencia en el archivo
                                    fwrite($archivoDatos, $updateQuery);
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    // Manejar errores por tabla
                    echo "Error en la tabla $tabla: ";
                }
            }

            // Habilitar las claves foráneas
            fwrite($archivoDatos, "SET foreign_key_checks = 1;\n");

            fclose($archivoDatos);

            $archivoFinal = "./update_scripts/volcado_completo_update_$empresa_id.sql";
            file_put_contents($archivoFinal, file_get_contents($archivoEstructura));
            file_put_contents($archivoFinal, file_get_contents($archivoDatosFiltrados), FILE_APPEND);
            unlink($archivoDatosFiltrados);

            $archivoFinal = substr($archivoFinal, 1);
            $url = $ruta . 'api' . $archivoFinal;

            $response = array("status" => 200, "message" => "Datos exportados correctamente.", "archivo" => $url);
        } catch (Exception $e) {
            echo "Error: ";
        }

    } catch (Exception $e) {
        $response = array("status" => 500, "status_message" => "{$error_msg}");
    }

    header('Content-Type: application/json');
    echo json_encode($response);
}
