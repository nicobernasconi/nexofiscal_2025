<!DOCTYPE html>
<html lang="en">

<head>
  <?php include 'head.php'; ?>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">
          <div class="navbar nav_title" style="border: 0;">
            <a href="index.php" class="site_title"><img src="./images/icono.png" width="30" style="margin:5px;"><span><?php echo $titulo; ?> </span></a>
          </div>

          <div class="clearfix"></div>

          <!-- menu profile quick info -->
          <div class="profile clearfix">
            <div class="profile_pic">
              <img src="<?php echo $_SESSION['logo_gestor'] ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Binvenido/a,</span>
              <h2><?php echo $_SESSION['nombre_gestor']; ?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <br />

          <?php include 'sidebar.php'; ?>

          <!-- /menu footer buttons -->
          <?php include("footer_sidebar.php"); ?>
          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <?php include 'top_nav.php'; ?>
      <!-- /top navigation -->


      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Form Wizards</h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5  form-group row pull-right top_search">

              </div>
            </div>
          </div>
          <div class="clearfix"></div>

          <div class="row">

            <div class="col-md-12 col-sm-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Importador masivo de productos <small>Importar productos en formato XLS</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <ul class="dropdown-menu" role="menu">
                        <li><a href="#">Settings 1</a>
                        </li>
                        <li><a href="#">Settings 2</a>
                        </li>
                      </ul>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">


                  <!-- Smart Wizard -->
                  <p>Este asistente te guiará a través del proceso de actualización de productos en tu sistema. Sigue los pasos para completar la actualización de productos.</p>
                  <div id="wizard" class="form_wizard wizard_horizontal">
                    <ul class="wizard_steps">
                      <li>
                        <a href="#step-1">
                          <span class="step_no">1</span>
                          <span class="step_descr">
                            Paso 1<br />
                            <small>Seleccion y carga del archivo de importacion</small>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="#step-2">
                          <span class="step_no">2</span>
                          <span class="step_descr">
                            Paso 2<br />
                            <small>Descripcion de las altas/modificaciones de los productos</small>
                          </span>
                        </a>
                      </li>
                      <li>
                        <a href="#step-3">
                          <span class="step_no">3</span>
                          <span class="step_descr">
                            Paso 3<br />
                            <small>Resultado</small>
                          </span>
                        </a>
                      </li>

                    </ul>
                    <div id="step-1">
                      <form class="form-horizontal form-label-left">

                        <div class="container">
                          <div class="row">
                            <div class="col-md-6" style="background-color: #f0f8ff; padding: 20px;">
                              <h2 style="font-size: 22px;">Actualizar Productos</h2>
                              <p>Utiliza esta sección para subir un archivo XLS que contenga la información de los productos que deseas actualizar.</p>
                              <form action="procesar_actualizacion.php" method="post" enctype="multipart/form-data" class="form-label-left">
                                <div class="form-group row">
                                  <label class="control-label col-md-4 col-sm-3">Empresa</label>
                                  <div class="col-md-8 col-sm-9">
                                    <select id="empresa" name="empresa" class="select2_single form-control" tabindex="-1"></select>
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <label class="col-form-label col-md-4 col-sm-3" for="file">Archivo XLS<span class="required">*</span></label>
                                  <div class="col-md-8 col-sm-9">
                                    <input type="file" id="file" name="file" required="required" class="form-control-file" accept=".xls, .xlsx">
                                  </div>
                                </div>
                                <div class="form-group row">
                                  <div class="col-md-8 col-md-offset-4">
                                    <button id="btn_enviar_planilla" class="btn btn-primary">Subir Archivo</button>
                                  </div>
                                </div>
                              </form>
                            </div>

                            <div class="col-md-6" style="border: 2px solid #007bff; padding: 10px; background-color: #f0f0f0;">
                              <h2>Descargas</h2>
                              <p>Utiliza esta sección para descargar recursos útiles relacionados con la actualización de productos.</p>
                              <p>Aquí puedes descargar una plantilla Excel de muestra para ayudarte a preparar tu archivo XLS de actualización:</p>
                              <ul class="list-group">
                                <li class="list-group-item">
                                  <a href="./downloads/productos.xls" class="btn btn-info">Descargar Plantilla Excel de Muestra</a>
                                </li>
                              </ul>
                              <p>También puedes descargar la lista actual de productos para revisarla o hacer referencia a ella:</p>
                              <ul class="list-group">
                                <li class="list-group-item">
                                  <button id="btn-descargar-productos" class="btn btn-success">Descargar Lista de Productos Actual</button>
                                </li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </form>

                    </div>
                    <div id="step-2">
                      <h2 class="StepTitle">Actualizar / Crear Productos</h2>
                      <p>
                        A continuación se muestra un resumen de la importación de productos:
                      </p>
                      <ul>
                        <li><strong>Productos a actualizar:</strong> <span id="count_actualizar">0</span></li>
                        <li><strong>Productos a dar de alta:</strong> <span id="count_insertar">0</span></li>
                      </ul>
                      <p>
                        Por favor, revisa el resumen y haz clic en el botón "siguiente" para continuar con la actualización de productos.<br>
                        Si deseas volver a cargar el archivo, haz clic en el botón "anterior".<br>
                        Recuerda que una vez que continúes, no podrás volver atrás.
                      </p>
                      <div class="form-group">
                        <div class="col-md-6 col-md-offset-3">
                          <div class="checkbox">
                            <label>
                              <input type="checkbox" value="1" id="aceptar_resumen"> He revisado el resumen y deseo continuar con la actualización de productos.
                            </label>
                          </div>
                        </div>


                      </div>

                      <div id="step-3">
                        <h2 class="StepTitle">Proceso finalizado</h2>
                        <p>
                          sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore
                          eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                        <p>
                          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                          in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                        </p>
                      </div>

                    </div>
                    <!-- End SmartWizard Content -->
                    <!-- End SmartWizard Content -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            NexoFiscal - Todos los derechas reservados 2024
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="./vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="./vendors/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <!-- FastClick -->
    <script src="./vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="./vendors/nprogress/nprogress.js"></script>
    <!-- jQuery Smart Wizard -->
    <script src="./vendors/jQuery-Smart-Wizard/js/jquery.smartWizard.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="./js/custom.js"></script>
    <?php include 'scripts.php'; ?>

    <script>
      let archivo_cargado = false;
      let resumen_aprobado = false;
      let finalizar = false;
      $(document).ready(function() {


        if (typeof($.fn.smartWizard) === 'undefined') {
          return;
        }

        //cambiar orden de los botones a anterior siguiente y finalizar
        $('#wizard').smartWizard({
          theme: 'circles',
          lang: {
            next: 'Siguiente',
            previous: 'Anterior'
          },
          transitionEffect: 'fade',
          showStepURLhash: true,
          toolbarSettings: {
            toolbarPosition: 'bottom', // none, top, bottom, both
            toolbarButtonPosition: 'right', // left, right
            showNextButton: true, // show/hide a Next button
            showPreviousButton: true, // show/hide a Previous button
          },
          onLeaveStep: function(obj, context) {
            if (context.fromStep == 1 && context.toStep == 2) {
              if (!archivo_cargado) {
                alert('Debes cargar un archivo antes de continuar');
                return false;
              }
            }
            if (context.fromStep == 2 && context.toStep == 3) {
              if (!resumen_aprobado) {
                alert('Debes aprobar el resumen antes de continuar');
                return false;
              } else {
                $.blockUI({
                  message: '<h1>Procesando...</h1>',
                  css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                  }
                });
                $.ajax({
                  url: './ajax/productos/importar.php',
                  type: 'POST',
                  data: {
                    empresa_id: $('#empresa').val()
                  },
                  beforeSend: function() {
                    // Disable the button while loading
                    $('#btn_enviar_planilla').prop('disabled', true);
                  },
                  success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status == 200) {
                      alert(response.status_message);
                     window.location.href = 'productos.php';
                    } else {
                      alert(response.status_message);
                    }

                  },
                  complete: function() {
                    $('#btn_enviar_planilla').prop('disabled', false);
                    $.unblockUI();
                  }
                });
              }
            }

            return true;
          },
        });
        //formato de los botones
        $('.buttonNext').addClass('btn btn-primary');
        $('.buttonPrevious').addClass('btn btn-secondary');
        $('.buttonFinish').hide();


        $.ajax({
          url: './ajax/empresas/list.php',
          type: 'GET',
          beforeSend: function() {
            // Disable the select while loading
            $('#empresa').prop('disabled', true);
          },
          success: function(empresas) {
            let select = document.getElementById('empresa');
            //vaciar el select
            select.innerHTML = "";
            empresas.forEach(sucursal => {
              let option = document.createElement('option');
              option.value = sucursal.id;
              option.text = sucursal.nombre;
              select.appendChild(option);
            });
          },
          complete: function() {
            // Enable the select after loading
            $('#empresa').prop('disabled', false);
          }
        });
      });
      $('#btn_enviar_planilla').click(function(event) { // Agrega el parámetro 'event' aquí
        // Prevent the form from submitting
        event.preventDefault();
        //agragar el archivo a un formData
        var file = document.getElementById('file').files[0];
        if (file) {
          var reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = function() {

            var data = reader.result;
           
            $.ajax({
              url: './ajax/productos/preparar_importar.php',
              type: 'POST',
              data: {
                file: data,
                empresa_id: $('#empresa').val()
              },
              beforeSend: function() {
                // Disable the button while loading
                $('#btn_enviar_planilla').prop('disabled', true);
                $.blockUI({
                  message: '<h1>Procesando...</h1>',
                  css: {
                    border: 'none',
                    padding: '15px',
                    backgroundColor: '#000',
                    '-webkit-border-radius': '10px',
                    '-moz-border-radius': '10px',
                    opacity: .5,
                    color: '#fff'
                  }
                });
              },
              success: function(response) {
                console.log(response);
                response = JSON.parse(response);
                if (response.status == 200) {
                  alert(response.status_message);
                  archivo_cargado = true;
                  $('#count_actualizar').text(response.count_actualizar);
                  $('#count_insertar').text(response.count_insertar);
                } else {
                  alert(response.status_message);
                }

              },
              complete: function() {
                $('#btn_enviar_planilla').prop('disabled', false);
                $.unblockUI();
              }
            });
          }
        }
      });
      $('#aceptar_resumen').change(function() {
        resumen_aprobado = $(this).is(':checked');
      });
      $('#btn-descargar-productos').click(function() {
        event.preventDefault();
        $.ajax({
          url: './ajax/productos/descargar.php',
          type: 'POST',
          data: {
            empresa_id: $('#empresa').val()
          },
          success: function(response) {
            console.log(response);
            response = JSON.parse(response);
            if (response.status == 200) {
              window.open(response.url, '_blank');
            } else {
              alert(response.status_message);
            }
          }
        });
      });
    </script>


</body>

</html>