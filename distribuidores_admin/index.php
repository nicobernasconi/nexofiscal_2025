<!DOCTYPE html>
<html lang="en">

<head>
  <?php include("includes/session_parameters.php"); ?>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="images/favicon.ico" type="image/ico" />

  <title><?php echo $titulo; ?> </title>

  <!-- Bootstrap -->
  <link href="./vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="./vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="./vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="./vendors/iCheck/skins/flat/green.css" rel="stylesheet">

  <!-- bootstrap-progressbar -->
  <link href="./vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
  <!-- JQVMap -->
  <link href="./vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
  <!-- bootstrap-daterangepicker -->
  <link href="./vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

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
              <img src="<?php echo $_SESSION['logo_distribuidor'] ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Binvenido/a,</span>
              <h2><?php echo $_SESSION['nombre_distribuidor']; ?></h2>
            </div>
          </div>
          <!-- /menu profile quick info -->

          <br />

          <?php include 'sidebar.php'; ?>

          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->

          <!-- /menu footer buttons -->
        </div>
      </div>

      <!-- top navigation -->
      <?php include 'top_nav.php'; ?>
      <!-- /top navigation -->

      <!-- page content -->
      <div class="right_col" role="main">
        <!-- top tiles -->
        <div class="row" style="display: inline-block;width: 100%;">
          <div class="tile_count">
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top"><i class="fa fa-building"></i> Empresas</span>
              <div class="count" id="count_empresas_dashboard">-</div>
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top"><i class="fa fa-building"></i> Sucursales</span>
              <div class="count" id="count_sucursales_dashboard">-</div>
            </div>
            <div class="col-md-3 col-sm-4  tile_stats_count">
              <span class="count_top"><i class="fa fa-dollar"></i> Ventas mensuales</span>
              <div class="count" id="count_ventas_dashboard">-</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>-% </i> Ultima Semana</span>
            </div>
            <div class="col-md-2 col-sm-4  tile_stats_count">
              <span class="count_top"><i class="fa fa-file"></i> Pedidos mensuales</span>
              <div class="count" id="count_pedidos_dashboard">-</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>-% </i> Ultima Semana</span>
            </div>
            <div class="col-md-3 col-sm-4  tile_stats_count">
              <span class="count_top">- <i class="fa fa-dollar"></i> Devoluciones mensuales</span>
              <div class="count" id="count_devoluciones_dashboard">-</div>
              <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>-% </i> Ultima Semana</span>
            </div>
          </div>
        </div>
        <!-- /top tiles -->

        <div class="row">
          <div class="col-md-12 col-sm-12 ">
            <div class="dashboard_graph">

              <div class="row x_title">
                <div class="col-md-6">
                  <h3>Ventas <small>por Sucursal</small></h3>
                </div>
                <div class="col-md-6">
                  <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                    <i class="fa fa-calendar"></i>
                    <span><?php //imprimir la fecha en formato texto            
                          $fecha = date("d/m/Y");
                          $dia = date("w");
                          $dias = array("Domingo", "Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado");
                          echo $dias[$dia] . " " . $fecha;
                          ?></span> <b class="caret"></b>
                  </div>
                </div>
              </div>

              <div class="col-md-9 col-sm-9 ">
                <div id="chart_plot_01" class="demo-placeholder"></div>
              </div>
              <div class="col-md-3 col-sm-3  bg-white">
                <div class="x_title">
                  <h2>Productos mas vendidos</h2>
                  <div class="clearfix"></div>
                </div>

                <div class="col-md-12 col-sm-12 ">
                  <div>
                    <p id="ranking_producto_1">-</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div id="ranking_producto_progressbar_1" class="progress-bar bg-green" role="progressbar" data-transitiongoal="80"></div>
                      </div>
                    </div>
                  </div>
                  <div>
                  <p id="ranking_producto_2">-</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div  id="ranking_producto_progressbar_2"  class="progress-bar bg-green" role="progressbar" data-transitiongoal="60"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-12 ">
                  <div>
                  <p id="ranking_producto_3">-</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div id="ranking_producto_progressbar_3"   class="progress-bar bg-green" role="progressbar" data-transitiongoal="40"></div>
                      </div>
                    </div>
                  </div>
                  <div>
                  <p id="ranking_producto_4">-</p>
                    <div class="">
                      <div class="progress progress_sm" style="width: 76%;">
                        <div  id="ranking_producto_progressbar_4" class="progress-bar bg-green" role="progressbar" data-transitiongoal="50"></div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <div class="clearfix"></div>
            </div>
          </div>

        </div>
        <br />


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
    <!-- Chart.js -->
    <script src="./vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="./vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="./vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="./vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="./vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="./vendors/Flot/jquery.flot.js"></script>
    <script src="./vendors/Flot/jquery.flot.pie.js"></script>
    <script src="./vendors/Flot/jquery.flot.time.js"></script>
    <script src="./vendors/Flot/jquery.flot.stack.js"></script>
    <script src="./vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="./vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="./vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="./vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="./vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="./vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="./vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="./vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="./vendors/moment/min/moment.min.js"></script>
    <script src="./vendors/bootstrap-daterangepicker/daterangepicker.js"></script>


    <?php include 'scripts.php'; ?>

    <!-- Custom Theme Scripts -->
    <script src="./js/custom.js"></script>
    <script src="./js/index.js"></script>

</body>

</html>