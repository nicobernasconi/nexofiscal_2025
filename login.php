<?php include("includes/config.php");
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <!-- Enlace al archivo CSS de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>
    <header>
        <div class="logoinicio">
            <img src="img/logo.png"></img>
        </div>

    </header>

    <!-- Contenido de la página -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title text-center mb-4">Iniciar sesión</h2>
                        <!-- Formulario de inicio de sesión -->
                        <form id="login-form">
                            <div class="mb-3">
                                <input type="text" class="form-control" name="usuario" placeholder="Usuario" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
                            </div>
                            <div id="login-message" class="text-center text-danger mb-3"></div>
                            <button type="submit" class="btn btn-primary btn-block">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php include("scripts_js.php"); ?>
<!-- jQuery -->
<script src="js/login.js"></script>

</html>