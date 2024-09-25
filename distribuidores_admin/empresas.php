<!DOCTYPE html>
<html lang="en">

<?php include("head.php"); ?>

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
          <div class="page-title">
            <div class="title_left">
              <h3>Empresas <small>Gestionar todas las empresas del distibuidor</small></h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5   form-group pull-right top_search">
                <div class="input-group">
                  <input id="buscar-empresa" type="text" class="form-control" placeholder="buscar...">
                  <span class="input-group-btn">
                    <button id="btn-buscar-empresa" class="btn btn-default" type="button">ir!</button>
                  </span>
                </div>
              </div>
            </div>
          </div>



          <div class="row" style="display: block;">

            <div class="col-md-12 col-sm-12  ">
              <div class="x_panel">
                
                <div class="x_content">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-empresa-editar-modal-lg" id="btn-agregar-empresa">Agregar Empresa</button>
                  <div class="table-responsive">
                    <table class="table table-striped jambo_table bulk_action" id="tablaEmpresas">
                      <thead>
                        <tr class="headings">
                          
                          <th class="column-title">Nombre </th>
                          <th class="column-title">Email </th>
                          <th class="column-title">Telefono </th>
                          <th class="column-title">CUIT </th>
                          <th class="column-title">Tipo IVA </th>
                          <th class="column-title">Inicio actividad </th>
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
  <script src="./js/empresas.js"></script>
  <script src="./js/custom.js"></script>
</body>

</html>