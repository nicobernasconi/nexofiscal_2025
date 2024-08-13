<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("includes/session_parameters.php"); ?>
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
  <!-- iCheck -->
  <link href="./vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- Datatables -->
  <link href="./vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
  <!-- PNotify -->
  <link href="./vendors/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="./vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <link href="./vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="./vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="./css/custom.css" rel="stylesheet">
</head>

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
              <img src="<?php echo $_SESSION['logo_distribuidor']?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Bienvenido,</span>
              <h2><?php echo $_SESSION['nombre_distribuidor']; ?></h2>
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

          <a href="empresas.php" class="btn btn-success"><i class="fa fa-arrow-left"></i> Volver a empresas</a>
          <div class="page-title">

            <div class="title_left">
              <h3>Roles <small>Gestionar todas los roles de la Empresa</small></h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5   form-group pull-right top_search">
                <div class="input-group">
                  <input id="buscar-rol" type="text" class="form-control" placeholder="buscar...">
                  <span class="input-group-btn">
                    <button id="btn-buscar-rol" class="btn btn-default" type="button">ir!</button>
                  </span>
                </div>
              </div>
            </div>
          </div>



          <div class="row" style="display: block;">

            <div class="col-md-12 col-sm-12  ">
              <div class="x_panel">

                <div class="x_title">
                  <!-- boton volver -->

                  <h2>roles <small>Listados</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li class="dropdown">
                      <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="#">Settings 1</a>
                        <a class="dropdown-item" href="#">Settings 2</a>
                      </div>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <input type="hidden" id="empresa_id" value="<?php echo $_GET['id']; ?>">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-rol-editar-modal-lg" id="btn-agregar-rol">Agregar rol</button>
                  <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action" id="tablaRoles">
                      <thead>
                        <tr class="headings">
                          <th>
                            <input type="checkbox" id="check-all" class="flat">
                          </th>
                          <th class="column-title">Nombre </th>
                          <th class="column-title">Descripcion </th>
                          <th class="column-title no-link last"><span class="nobr">Acciones</span>
                          </th>
                          <th class="bulk-actions" colspan="7">
                            <a class="antoo" style="color:#fff; font-weight:500;">Bulk Actions ( <span class="action-cnt"> </span> ) <i class="fa fa-chevron-down"></i></a>
                          </th>
                        </tr>
                      </thead>

                      <tbody>

                      </tbody>
                    </table>
                  </div>


                </div>
              </div>
            </div>
          </div>
        </div>
        <?php include 'modals/empresas.php'; ?>
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
  <!-- iCheck -->
  <script src="./vendors/iCheck/icheck.min.js"></script>
  <!-- PNotify -->
  <script src="./vendors/pnotify/dist/pnotify.js"></script>
  <script src="./vendors/pnotify/dist/pnotify.buttons.js"></script>
  <script src="./vendors/pnotify/dist/pnotify.nonblock.js"></script>
  <!-- Datatables -->
  <script src="./vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <!-- Select2 -->
  <script src="./vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- validator -->
  <script src="./vendors/validator/validator.js"></script>

  <?php include 'scripts.php'; ?>
  <!-- Custom Theme Scripts -->
  <script src="./js/roles.js"></script>
  <script src="./js/custom.js"></script>
</body>

</html>