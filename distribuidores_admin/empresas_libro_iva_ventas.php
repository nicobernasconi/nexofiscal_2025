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
              <img src="<?php echo $_SESSION['logo_distribuidor'] ?>" alt="..." class="img-circle profile_img">
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
              <h3>Ventas <small>Listar</small></h3>
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
            <div class="col-md-12 ">
              <div class="x_panel">
                <div class="x_title">
                  <h2>Buscar <small>Elija los parametro de filtrado</small></h2>
                  <ul class="nav navbar-right panel_toolbox">
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                    </li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a>
                    </li>
                  </ul>
                  <div class="clearfix"></div>
                </div>
                <form>
                  <div class="x_content">
                    <br />


                    <div class="form-group row">
                      <label class="control-label col-md-3 col-sm-3 ">Empresa</label>
                      <div class="col-md-9 col-sm-9 ">
                        <select id="empresa" name="empresa" class="select2_single form-control" tabindex="-1"></select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3 col-sm-3 ">Periodo</label>
                      <div class="col-md-9 col-sm-9 ">
                        <div class="input-prepend input-group">
                          <span class="add-on input-group-addon"><i class="fa fa-calendar"></i></span>
                          <input type="text" style="width: 200px" name="periodo" id="periodo" class="form-control">
                        </div>
                      </div>
                    </div>
                    <input type="hidden" name="fecha_inicio" id="fecha_inicio">
                    <input type="hidden" name="fecha_fin" id="fecha_fin">
                  </div>
                  <div class="form-group">
                    <div class="col-md-9 col-sm-9  offset-md-3">
                      <button type="reset" class="btn btn-primary">Resetear</button>
                      <button id="btn-buscar" class="btn btn-dark">Mostrar</button>
                      <button id="btn-exportar-excel" class="btn btn-info"><i class="fa fa-file-excel-o"></i> Exportar Excel</button>
                        <button id="btn-exportar-pdf" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Exportar PDF</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>

          <div class="x_content">
            <div class="table-responsive">
              <table id="tablaInformeLibroIvaVentas" class="table table-striped jambo_table bulk_action display" style="width:100%">
                <thead>
                  <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Los datos se cargarán dinámicamente aquí -->
                </tbody>
                <tfoot></tfoot>
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
  <!-- bootstrap-daterangepicker -->
  <script src="./vendors/moment/min/moment.min.js"></script>
  <script src="./vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-datetimepicker -->
  <script src="./vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <!-- Moment.js -->
  <script src="./vendors/moment/min/moment.min.js"></script>
  <!-- PNotify -->
  <script src="./vendors/pnotify/dist/pnotify.js"></script>
  <script src="./vendors/pnotify/dist/pnotify.buttons.js"></script>
  <script src="./vendors/pnotify/dist/pnotify.nonblock.js"></script>
  <!-- Datatables -->
  <script src="./vendors/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="./vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="./vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
  <script src="./vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
  <script src="./vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
  <script src="./vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
  <script src="./vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
  <script src="./vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
  <script src="./vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
  <script src="./vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
  <script src="./vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
  <script src="./vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>

  <!-- Select2 -->
  <script src="./vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- validator -->
  <script src="./vendors/validator/validator.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="./js/custom.js"></script>


  <?php include 'scripts.php'; ?>

  <script src="./js/empresas_ventas.js"></script>
  <script src="./js/empresas_libro_iva_ventas.js">  </script>
</body>

</html>