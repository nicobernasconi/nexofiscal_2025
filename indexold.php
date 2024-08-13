<?php include("includes/session_parameters.php");
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexoFiscal</title>
    <!-- Enlace al archivo CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <!-- Enlace a jQuery -->

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header con Menú</title>
    <link rel="stylesheet" href="css/header.css"> <!-- Estilo personalizado -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Iconos Font Awesome -->
</head>

<body>
    <?php
    include("header.php");
    ?>

    <div class="container mt-5">

        <div class="row">
            <!-- Panel para buscar clientes -->
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body d-flex align-items-center">
                        <button id="search-client-btn" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#searchClientModal">
                            <i class="fas fa-search"></i>
                        </button>
                        <h5 class="card-title mb-0">Buscar Clientes</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body d-flex align-items-center">
                        <span class="btn btn-primary me-2">
                            <i class="fas fa-search"></i>
                        </span>
                        <select class="form-select select2" id="searchProduct" name="searchProduct">
                        </select>
                    </div>
                </div>
            </div>

            <!-- Columna principal para el formulario y la lista de productos -->
            <div class="col-md-6">

                <!-- Tabla para mostrar los productos agregados -->
                <div class="mt-4">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="card-title">Productos</h2>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Precio</th>
                                            <th>Cantidad</th>
                                            <th>Subtotal</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-list">
                                        <!-- Aquí se agregarán dinámicamente los productos -->
                                    </tbody>
                                    <tfoot>
                                        <!-- Puedes agregar contenido adicional al pie de la tabla si es necesario -->
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-4" id="payment-panel" style="display:none;">
                    <div class="card-body">
                        <h2 class="card-title">Forma de Pago</h2>
                        <div class="mb-3">
                            <label for="paymentMethod" class="form-label">Método de Pago:</label>
                            <!-- Agrega la clase "select2" al select para inicializar Select2 -->
                            <select class="form-select select2" id="paymentMethod" name="paymentMethod">
                            </select>
                        </div>
                        <!-- Mostrar el precio final -->
                        <div class="mb-3">
                            <label for="final-price" class="form-label">Precio Final:</label>
                            <p class="form-control" id="final-price">$0.00</p>
                        </div>
                        <!-- Campo para agregar el monto pagado -->
                        <div class="mb-3 mt-3">
                            <div id="payment-validation-message" class="text-danger d-none">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Los montos deben coincidir.
                            </div>
                            <label for="paidAmount" class="form-label">Monto a pagar:</label>
                            <input type="number" class="form-control" id="paidAmount" name="paidAmount" min="0" step="0.01">
                        </div>
                    </div>

                </div>
                <button id="payment-btn" class="btn btn-success mt-3">
                    Realizar Venta
                </button>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Favoritos</h2>
                        <div class="row" id="favorite-products">
                            <?php include("productos_favoritos.php"); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="summaryModal" tabindex="-1" aria-labelledby="summaryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="summaryModalLabel">Resumen de la Venta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div id="paymentSummary"></div> <!-- Aquí se mostrará el resumen -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fas fa-times"></i> Cerrar</button>
                        <a href="index.php" class="btn btn-primary" onclick="printSummary()"><i class="fas fa-print"></i> Imprimir Comprobante</a>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal para buscar clientes -->
        <div class="modal fade" id="searchClientModal" tabindex="-1" aria-labelledby="searchClientModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="searchClientModalLabel">Buscar Cliente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario de búsqueda de cliente -->
                        <form id="search-client-form">
                            <div class="mb-3">
                                <label for="search-client" class="form-label">Nombre del Cliente:</label>
                                <input type="text" class="form-control" id="search-client" name="search-client">
                            </div>
                            <button type="submit" class="btn btn-primary">Buscar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="menuModalLabel">Menú</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <button class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-user"></i> Usuarios y Roles
                                </button>
                                <button class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-cog"></i> Configuración y Administración
                                </button>
                                <button class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-box"></i> Productos y Ventas
                                </button>
                                <button class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fas fa-sitemap"></i> Categorización y Organización
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="js/header.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- jQuery -->
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <!-- Enlace al archivo JavaScript de Bootstrap (opcional) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/i18n/es.js"></script>

        <!-- Tu código JavaScript personalizado -->
        <script src="js/index.js"></script>

</body>

</html>