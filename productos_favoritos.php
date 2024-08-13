
<?php
include("includes/session_parameters.php");

require 'vendor/autoload.php';

use GuzzleHttp\Client;

// URL de la API
$url = $ruta . 'api/productos';

// Parámetros de la solicitud
$params = [
    'headers' => [
        'Content-Type' => 'application/json',
        // Obtener el token de seguridad de las variables de sesión
        'Authorization' => 'Bearer ' . $_SESSION['token']
    ], "query" => [
        'favorito' => '1',
        'empresa_id' => $_SESSION['empresa_id']

    ],


];

// Crear una instancia del cliente Guzzle
$client = new Client();
$data = array();

try {
    // Enviar la solicitud GET
    $response = $client->request('GET', $url, $params);

    // Obtener el cuerpo de la respuesta en formato JSON
    $body = $response->getBody()->getContents();

    // Decodificar el JSON en un array asociativo
    $data = json_decode($body, true);
} catch (Exception $e) {
}




//
$productosFavoritos = array();


//generar $productosFavoritos
// Transformar los datos según el nuevo formato requerido
$formattedData = array_map(function ($item) {
    return [
        'id' => $item['id'],
        'nombre' => $item['descripcion'],
        'icono' => $item['agrupacion']['icono'],
        'precio' => $item['precio1'],
        'tasa_iva' => doubleval($item['tasa_iva']['tasa']),
        'precio_impuesto_interno' => $item['precio1_impuesto_interno'],
        'producto_balanza' => $item['producto_balanza'],
        'color' => $item['agrupacion']['color']
    ];
}, $data);

// Asignar los datos formateados al arreglo de productos favoritos
$productosFavoritos = $formattedData;


// Contador para mantener el control de los botones por fila
$contador = 0;
if (in_array('listar', $permisos_asignados['productos'])) {
    foreach ($productosFavoritos as $producto) {
        echo '<a  href="#" class="pludirectosbtn" style="background:'.trim($producto['color']).'!important;" onclick="addFavoriteProduct(\'' . $producto['id'] . '\',\'' . $producto['nombre'] . '\', ' . $producto['precio'] . ','. $producto['precio_impuesto_interno'] . ','.$producto['tasa_iva'].',1,' . $producto['producto_balanza'] . ');">';
        echo $producto['nombre']; // Nombre del producto
        echo '<br>';
        echo '$' . $producto['precio']; // Precio del producto
        echo '</a>';

        // Incrementar el contador
        $contador++;

        // Verificar si es el último elemento del array y si no es múltiplo de 4
        if ($contador == count($productosFavoritos) && $contador % 4 != 0) {
            // Calcular cuántos botones vacíos se necesitan para completar la fila
            $botonesFaltantes = 4 - ($contador % 4);
            // Iterar para agregar los botones vacíos
            for ($i = 0; $i < $botonesFaltantes; $i++) {
                echo '<a href="#" class="pludirectosbtn empty"></a>';
            }
        }

        // Verificar si el contador es múltiplo de 4 para iniciar una nueva fila
        if ($contador % 4 == 0) {
            echo '<div class="row"></div>'; // Agregar un div para iniciar una nueva fila
        }

    }
    //imprimir la variable de sesion por consola
    echo '<script>console.log('.json_encode($_SESSION).')</script>';
}
