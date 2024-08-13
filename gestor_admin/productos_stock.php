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
              <img src="<?php echo $_SESSION['logo_gestor'] ?>" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>Bienvenido,</span>
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
              <h3>Productos <small>Listar</small></h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5   form-group pull-right top_search">
                <div class="input-group">
                  <input id="buscar-sucursal" type="text" class="form-control" placeholder="buscar...">
                  <span class="input-group-btn">
                    <button id="btn-buscar-sucursal" class="btn btn-default" type="button">ir!</button>
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

                    <div class="form-group row">
                      <label class="control-label col-md-3 col-sm-3 ">Empresa</label>
                      <div class="col-md-9 col-sm-9 ">
                        <select id="empresa" name="empresa" class="select2_single form-control" tabindex="-1"></select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3 col-sm-3 ">Sucursal</label>
                      <div class="col-md-9 col-sm-9 ">
                        <select id="sucursal" name="sucursal" class="select2_single form-control" tabindex="-1"></select>
                      </div>
                    </div>

                    <div class="form-group row">
                      <label class="control-label col-md-3 col-sm-3 ">Familia</label>
                      <div class="col-md-9 col-sm-9 ">
                        <select id="familias" name="familias" class="select2_single form-control" tabindex="-1"></select>
                      </div>
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
              <table id="tablaProductos" class="table table-striped jambo_table bulk_action display" style="width:100%">
                <thead>
                  <tr>
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
  <!-- Select2 -->
  <script src="./vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- validator -->
  <script src="./vendors/validator/validator.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="./js/custom.js"></script>
  <?php include 'scripts.php'; ?>



  <script src="./js/empresas_ventas.js"></script>

  <script>
    //cargar select2 de empresas
    $.ajax({
      url: './ajax/sucursales/list.php',
      type: 'GET',
      beforeSend: function() {
        // Disable the select while loading
        $('#sucursal').prop('disabled', true);
      },
      success: function(sucursales) {
        let select = document.getElementById('sucursal');
        //vaciar el select
        select.innerHTML = "";
        sucursales.forEach(sucursal => {
          let option = document.createElement('option');
          option.value = sucursal.id;
          option.text = sucursal.nombre;
          select.appendChild(option);
        });
      },
      complete: function() {
        // Enable the select after loading
        $('#sucursal').prop('disabled', false);
      }
    });

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
    $.ajax({
      url: './ajax/familias/list.php',
      type: 'GET',
      beforeSend: function() {
        // Disable the select while loading
        $('#familias').prop('disabled', true);
      },
      success: function(familias) {
        let select = document.getElementById('familias');
        //vaciar el select
        select.innerHTML = "";
        let option = document.createElement('option');
        option.value = '';
        option.text = 'Todas';
        select.appendChild(option);

        familias.forEach(familia => {
          let option = document.createElement('option');
          option.value = familia.id;
          option.text = familia.nombre;
          select.appendChild(option);
        });
      },
      complete: function() {
        // Enable the select after loading
        $('#familias').prop('disabled', false);
      }
    });

    $("#tablaProductos").hide();

    $("#btn-buscar").click(function(event) {
      event.preventDefault();
      $("#tablaProductos").show();
      var sucursal = $("#sucursal").val() ?? '';
      var empresa = $("#empresa").val() ?? '';
      var familia = $("#familias").val() ?? '';
      var data_string = 'sucursal_id=' + sucursal + '&empresa_id=' + empresa;
      if (familia != '') {
        data_string += '&familia_id=' + familia;
      }

      if ($.fn.DataTable.isDataTable("#tablaProductos")) {
        $("#tablaProductos").DataTable().destroy();
      }
      $("#tablaProductos").show();

      $("#tablaProductos").DataTable({
        processing: true,
        serverSide: true,
        sorting: true,
        paging: true,
        autoWidth: true,
        ajax: {
          url: "./ajax/productos/list_datatable.php?" + data_string,
          type: "POST",
        },
        columns: [{
            data: "codigo",
            title: "Código"
          },
          {
            data: "descripcion",
            title: "Descripción"
          },
         
          {
            data: "stock",
            title: "Stock"
          },
          {
            data: "stock_minimo",
            title: "Stock Mínimo"
          }, {
            data: "stock_pedido",
            title: "Stock de Reposición"
          },



        ],
        order: [
          [0, "asc"]
        ],
        language: {
          search: "", // Eliminar el texto de búsqueda predeterminado
          searchPlaceholder: "Buscar..", // Placeholder para el nuevo cuadro de búsqueda
          sProcessing: "Procesando...",
          sLengthMenu: "Mostrar _MENU_ registros",
          sZeroRecords: "No se encontraron resultados",
          sEmptyTable: "Ningún dato disponible en esta tabla",
          sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
          sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
          sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
          sInfoPostFix: "",
          sSearch: "Busar:", // Cambiado a la izquierda
          sUrl: "",
          sInfoThousands: ",",
          sLoadingRecords: "Cargando...",
          oPaginate: {
            sFirst: "Primero",
            sLast: "Último",
            sNext: "Siguiente",
            sPrevious: "Anterior",
          },
          oAria: {
            sSortAscending: ": Activar para ordenar la columna de manera ascendente",
            sSortDescending: ": Activar para ordenar la columna de manera descendente",
          },
          buttons: {
            copy: "Copiar",
            colvis: "Visibilidad",
          }
        },

      });
      //ocultar search
      $("#tablaInformeCompras_filter").hide();

    });


    $("#btn-exportar-excel").click(function(event) {
      event.preventDefault();
      $.blockUI({
        message: '<h1>Exportando Informe a Excel. <br>Espere por favor...</h1>',
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

      var empresa_id = $("#empresa").val();
      var sucursal_id = $("#sucursal").val();
      //crear un string para la consulta get

      var data_get = "";
      if (empresa_id != "") {
        data_get += "empresa_id=" + empresa_id;
      }
      if (sucursal_id != "") {
        data_get += "&sucursal_id=" + sucursal_id;
      }

      data_get += "&type=excel";
      $.ajax({

        url: "./ajax/productos/export.php?" + data_get,
        type: "GET",
        success: function(response) {
          $.unblockUI();
          var data = JSON.parse(response);
          if (data.status == 200) {
            //descargar archivo en una pantalla nueva
            var url = data.url;
            var a = document.createElement('a');
            a.href = url;
            a.download = url.split('/').pop();
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

          }
        }

      });
    });


    $("#btn-exportar-pdf").click(function(event) {
      event.preventDefault();
      $.blockUI({
        message: '<h1>Exportando Informe a PDF. <br>Espere por favor...</h1>',
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

      var empresa_id = $("#empresa").val();
      var sucursal_id = $("#sucursal").val();
      //crear un string para la consulta get

      var data_get = "";
      if (empresa_id != "") {
        data_get += "empresa_id=" + empresa_id;
      }
      if (sucursal_id != "") {
        data_get += "&sucursal_id=" + sucursal_id;
      }

      data_get += "&type=pdf";
      $.ajax({

        url: "./ajax/productos/export.php?" + data_get,
        type: "GET",
        success: function(response) {
          $.unblockUI();
          var data = JSON.parse(response);
          if (data.status == 200) {
            //descargar archivo en una pantalla nueva
            var url = data.url;
            var a = document.createElement('a');
            a.href = url;
            a.download = url.split('/').pop();
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);

          }
        }

      });
    });
  </script>
</body>

</html>