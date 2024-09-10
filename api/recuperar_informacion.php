<?php

include("../includes/database.php");
include("../includes/config.php");
include("../includes/security.php");

require '../vendor/autoload.php';

use Firebase\JWT\Key;
use \Firebase\JWT\JWT;

function escapeValue($value) {
    return $value === '' ? 'NULL' : "'" . addslashes($value) . "'";
}

function login($nombre_usuario, $password, $key, $kid, $tiempo_expiracion, $con)
{
    $password = md5($password);
    $query = "SELECT
                usuarios.id,
                usuarios.nombre_usuario,
                usuarios.`password`,
                usuarios.nombre_completo,
                usuarios.rol_id,
                usuarios.activo,
                usuarios.empresa_id
              FROM
                usuarios
              WHERE nombre_usuario = '$nombre_usuario' AND password = '$password'";
    $result = $con->query($query);
    $usuario = $result->fetch(PDO::FETCH_ASSOC) ?? false;
    if (!$usuario) {
        return false;
    }

    $usuario_id = $usuario['id'];
    $empresa_id = $usuario['empresa_id'];

    $token = crearToken($usuario_id, $empresa_id, $key, $kid, $tiempo_expiracion);
    return $token;
}

// Lógica principal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   try{
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre_usuario = $data['usuario'] ?? '';
    $password = $data['password'] ?? '';
    //comprobar si la tabla usuario esta vacia
    $query = "SELECT * FROM usuarios";
    $result = $con->query($query);
    $usuario = $result->fetch(PDO::FETCH_ASSOC) ?? false;

    if ($nombre_usuario && $password) {
        $token = login($nombre_usuario, $password, $key, $kid, $tiempo_expiracion, $con);

        if ($token != '') {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $usuario_id = $decoded->usuario_id;
            $empresa_id = $decoded->empresa_id;

            try {

                $archivoEstructura = "./install_scripts/estructura_empresa$empresa_id.sql";
                $archivoDatosFiltrados = "./install_scripts/datos_filtrados$empresa_id.sql";

                $tablas = [
                    'tipo_iva_empresa',
                    'empresas',
                    'moneda',
                    'agrupacion',
                    'categoria',
                    'categoria_iibb',
                    'tipos_cajas',
                    'puntos_venta',
                    'sucursales',
                    'roles',
                    'usuarios',
                    'cierres_cajas',
                    'pais',
                    'provincias',
                    'localidad',
                    'tipo_documento',
                    'tipo_iva',
                    'vendedores',
                    'clientes',
                    'codigos_barras',
                    'tasa_iva',
                    'familias',
                    'marcas',
                    'subcategoria',
                    'proveedores',
                    'subfamilias',
                    'tipo',
                    'unidad',
                    'productos',
                    'compras',
                    'condicion_venta',
                    'forma_pago',
                    'ganancias_proveedores',
                    'operacion_negocio',
                    'provincia_categoria_iibb',
                    'provincia_iva_proveedor',
                    'comprobantes',
                    'configuraciones',
                    'funciones',
                    'funciones_roles',
                    'ganancias_clientes',
                    'gastos',
                    'lista_precios',
                    'movimientos_stock',
                    'presupuestos',
                    'productos_stock',
                    'promociones',
                    'remitos',
                    'renglones_comprobantes',
                    'renglones_presupuestos',
                    'renglones_remitos',
                    'tipo_cierre_forma_pago',
                    'usuarios_roles',
                    'pagos',
                    'tipo_comprobante',
                ];
                // Exportar datos filtrados
                $archivoDatos = fopen($archivoDatosFiltrados, 'w');
                $ids_comprobantes = [];

                fwrite($archivoDatos, $cliente_eventual);
                foreach ($tablas as $tabla) {
                    try {
                        $resultado = $con->query("SELECT * FROM $tabla WHERE empresa_id = $empresa_id");

                        if ($resultado != false) {
                            //guardar ids de comprobantes
                            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

                            if ($fila) {
                                $campos = array_keys($fila);

                                // Agregar el campo id_remoto si existe el campo id
                                if (in_array('id', $campos)) {
                                    $campos[] = 'id_remoto';
                                }

                                // Agregar el caracter ` a los campos
                                $campos = array_map(function ($campo) {
                                    return "`$campo`";
                                }, $campos);
                                $camposList = implode(", ", $campos);

                                $inserts = "INSERT INTO $tabla ($camposList) VALUES\n";
                                $values = [];

                                do {
                                    if ($tabla == 'comprobantes') {
                                        $ids_comprobantes[] = $fila['id'];
                                    }

                                    // Agregar el valor de id_remoto si existe el campo id
                                    if (isset($fila['id'])) {
                                        $fila['id_remoto'] = $fila['id'];
                                    }

                                    // Escapar y preparar los valores
                                    $fila = array_map('escapeValue', $fila);

                                    // Generar la línea del INSERT
                                    $values[] = "(" . implode(", ", $fila) . ")";
                                } while ($fila = $resultado->fetch(PDO::FETCH_ASSOC));

                                $inserts .= implode(",\n", $values) . ";\n";
                                $inserts = str_replace("''", "NULL", $inserts);
                                fwrite($archivoDatos, $inserts);
                            }
                        }
                    } catch (Exception $e) {
                        $tabla_enteras = [
                            "localidad",
                            "pais",
                            "provincias",
                            "tipo_cierre_forma_pago",
                            "tipo_comprobante",
                            "tipo_iva_empresa",
                            "tipos_cajas",
                            "usuarios_roles",
                            "renglones_comprobantes",
                            "empresas",
                        ];
                        if (in_array($tabla, $tabla_enteras)) {
                            if ($tabla == 'empresas') {
                                $resultado = $con->query("SELECT * FROM $tabla WHERE id = $empresa_id");
                            } else if ($tabla == 'renglones_comprobantes') {
                                $resultado = $con->query("SELECT * FROM $tabla WHERE comprobante_id IN (" . implode(",", $ids_comprobantes) . ")");
                            } else {
                                $resultado = $con->query("SELECT * FROM $tabla");
                            }

                            if ($resultado != false) {
                                $fila = $resultado->fetch(PDO::FETCH_ASSOC);
                                if ($fila) {
                                    $campos = array_keys($fila);

                                    // Agregar el campo id_remoto si existe el campo id
                                    if (in_array('id', $campos)) {
                                        $campos[] = 'id_remoto';
                                    }

                                    $campos = array_map(function ($campo) {
                                        return "`$campo`";
                                    }, $campos);
                                    $camposList = implode(", ", $campos);
                                    $inserts = "INSERT INTO $tabla ($camposList) VALUES\n";
                                    $values = [];
                                    do {
                                        // Agregar el valor de id_remoto si existe el campo id
                                        if (isset($fila['id'])) {
                                            $fila['id_remoto'] = $fila['id'];
                                        }

                                        // Escapar y preparar los valores
                                        $fila = array_map('escapeValue', $fila);

                                        // Generar la línea del INSERT
                                        $values[] = "(" . implode(", ", $fila) . ")";
                                    } while ($fila = $resultado->fetch(PDO::FETCH_ASSOC));

                                    $inserts .= implode(",\n", $values) . ";\n";
                                    //reemplazar los campos '' por NULL
                                    $inserts = str_replace("''", "NULL", $inserts);
                                    fwrite($archivoDatos, $inserts);
                                }
                            }
                        }
                    }
                }

                fclose($archivoDatos);
                $archivoFinal = "./install_scripts/volcado_completo$empresa_id.sql";

                file_put_contents($archivoFinal, file_get_contents($archivoEstructura));
                file_put_contents($archivoFinal, file_get_contents($archivoDatosFiltrados), FILE_APPEND);
                //borrar archivo de datos filtrados
                unlink($archivoDatosFiltrados);
                //quitar el primer caracter de la direccion del archivo
                $archivoFinal = substr($archivoFinal, 1);
                $url = $ruta . 'api' . $archivoFinal;

                $response = array("status" => 200, "message" => "Datos exportados correctamente.", "archivo" => $url);
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            $response = array("status" => 401, "message" => "Nombre de usuario o contraseña incorrectos.");
        }
    } else {
        $response = array("status" => 400, "message" => "Se requieren nombre de usuario y contraseña.");
    }
   
    }catch(Exception $e){
        $response = array("status" => 500, "message" => "Error en el servidor. {$e->getMessage()}");
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    
}
?>
