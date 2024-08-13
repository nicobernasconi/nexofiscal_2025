<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("includes/config.php"); ?>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo $titulo; ?> </title>

  <!-- Bootstrap -->
  <link href="./vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="./vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="./vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="./vendors/animate.css/animate.min.css" rel="stylesheet">

  <!-- Custom Theme Style -->
  <link href="./css/custom.css" rel="stylesheet">
</head>

<body class="login">
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <form>
            <h1>Ingresar</h1>
            <div>
              <input type="text" id="usuario" name="usuario" class="form-control" placeholder="Usuario" required="" />
            </div>
            <div>
              <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required="" />
            </div>
            <div id="mensaje-error" class="alert alert-danger" style="display:none;">
              <strong>Error!</strong> <span id="mensaje-error-text"></span>
            </div>
            <div style="margin-bottom: 10px;"></div>
            <button type="button" id="btn-ingresar" class="btn btn-primary">Ingresar</button>
      </div>

      <div class="clearfix"></div>

      <h1><img src="images/icono.png" width="50"> NexoFiscal</h1>

    </div>
  </div>
  </form>
  </section>
  </div>
  </div>
  </div>
  </div>
</body>

<script src="./vendors/jquery/dist/jquery.min.js"></script>
<script src="./vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<?php include("scripts.php"); ?>

<script>
  $(document).ready(function() {
    $('#btn-ingresar').click(function() {
      //prevenir el evento por defecto
      event.preventDefault();
      var usuario = $('#usuario').val();
      var password = $('#password').val();
      $.blockUI({
        message: '<h1> Espere por favor...</h1>',
      });
      $('#mensaje-error').hide();
      $.ajax({
        type: 'POST',
        url: 'ajax/login/login.php',
        data: {
          usuario: usuario,
          password: password
        },
        success: function(response) {
          if (response.status == '200') {
            window.location.href = 'index.php';
          } else {
            $('#mensaje-error-text').html(response.message);
            $('#mensaje-error').show();

            $.unblockUI();
          }
        }
      });
    });
  });
</script>

</html>