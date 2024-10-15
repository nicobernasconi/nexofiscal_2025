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
              <img src="<?php echo $_SESSION['logo_gestor']?>" alt="..." class="img-circle profile_img">
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
              <h3>Compras <small>Listar</small></h3>
            </div>

            <div class="title_right">
              <div class="col-md-5 col-sm-5   form-group pull-right top_search">
                <div class="input-group">
                  
                  <span class="input-group-btn">
                    
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
                      <label class="control-label col-md-3 col-sm-3 ">Sucursales</label>
                      <div class="col-md-9 col-sm-9 ">
                        <select id="sucursales" name="sucursales" class="select2_single form-control"></select>
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
              <table id="tablaInformeCompras" class="table table-striped jambo_table bulk_action display" style="width:100%">
                <thead>
                  <tr>
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
  <!-- Select2 -->
  <script src="./vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- validator -->
  <script src="./vendors/validator/validator.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="./js/custom.js"></script>
  <?php include 'scripts.php'; ?>



  <script src="./js/empresas_ventas.js"></script>

  <script>
    $.ajax({
            url: './ajax/tipos_comprobante/list.php',
            type: 'GET',
            beforeSend: function() {
              // Disable the select while loading
              $('#cajeros').prop('disabled', true);
            },
            success: function(tipos) {

                let select = document.getElementById('tipo-comprobante');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todos
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todos";
                select.appendChild(option);
                tipos.forEach(tipo => {
                    let option = document.createElement('option');
                    option.value = tipo.id;
                    option.text = tipo.nombre;
                    select.appendChild(option);
                });
            },
            complete: function() {
              // Enable the select after loading
              $('#cajeros').prop('disabled', false);
            }
        });


       
        //cargar select2 de sucursales
        $.ajax({
            url: './ajax/sucursales/list.php',
            type: 'GET',
            beforeSend: function() {
              // Disable the select while loading
              $('#cajeros').prop('disabled', true);
            },
            success: function(sucursales) {
                let select = document.getElementById('sucursales');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todas
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todas";
                select.appendChild(option);
                sucursales.forEach(sucursal => {
                    let option = document.createElement('option');
                    option.value = sucursal.id;
                    option.text = sucursal.nombre;
                    select.appendChild(option);
                });
            },
            complete: function() {
              // Enable the select after loading
              $('#cajeros').prop('disabled', false);
            }
        });
        //cargar select2 de empresas
        $.ajax({
            url: './ajax/empresas/list.php',
            type: 'GET',
            beforeSend: function() {
              // Disable the select while loading
              $('#cajeros').prop('disabled', true);
            },
            success: function(empresas) {
                let select = document.getElementById('empresa');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todas
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todas";
                select.appendChild(option);
                empresas.forEach(empresa => {
                    let option = document.createElement('option');
                    option.value = empresa.id;
                    option.text = empresa.nombre;
                    select.appendChild(option);
                });
            },
            complete: function() {
              // Enable the select after loading
              $('#cajeros').prop('disabled', false);
            }
        });
          //cargar select2 de usuarios
            $.ajax({
            url: './ajax/usuarios/list.php',
            type: 'GET',
            beforeSend: function() {
              // Disable the select while loading
              $('#cajeros').prop('disabled', true);
            },
            success: function(empresas) {
              let select = document.getElementById('cajeros');
              //vaciar el select
              select.innerHTML = "";
              //agregar la opcion todas
              let option = document.createElement('option');
              option.value = "";
              option.text = "Todos";
              select.appendChild(option);
              empresas.forEach(empresa => {
                let option = document.createElement('option');
                option.value = empresa.id;
                option.text = empresa.nombre_completo+" - "+empresa.nombre_usuario;
                select.appendChild(option);
              });
            },
            complete: function() {
              // Enable the select after loading
              $('#cajeros').prop('disabled', false);
            }
          });
          

    var optionSet1 = {
      startDate: moment().subtract(29, 'days'),
      endDate: moment(),
      minDate: '01/01/2012',
      maxDate: '31/12/2050',
      dateLimit: {
        days:365
      },
      showDropdowns: true,
      showWeekNumbers: true,
      timePicker: false,
      timePickerIncrement: 1,
      timePicker12Hour: true,
      ranges: {
        'Hoy': [moment(), moment()],
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
        'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
        'Este mes': [moment().startOf('month'), moment().endOf('month')],
        'Mes Pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      opens: 'right',
      buttonClasses: ['btn btn-default'],
      applyClass: 'btn-small btn-primary',
      cancelClass: 'btn-small',
      format: 'DD/MM/YYYY',
      separator: ' hasta ',
      locale: {
        format: 'DD/MM/YYYY',
        applyLabel: 'Aceptar',
        cancelLabel: 'Limpiar',
        fromLabel: 'Desde',
        toLabel: 'Hasta',
        customRangeLabel: 'Rango',
        daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        firstDay: 1
      }
    };
    $('#periodo').daterangepicker(optionSet1, function(start, end, label) {});
    $('#periodo').on('apply.daterangepicker', function(ev, picker) {
      $('#fecha_inicio').val(picker.startDate.format('YYYY-MM-DD'));
      $('#fecha_fin').val(picker.endDate.format('YYYY-MM-DD'));
    });
    $('#periodo').on('cancel.daterangepicker', function(ev, picker) {
      $('#fecha_inicio').val('');
      $('#fecha_fin').val('');
    });
    //inicializar fecha_inicio y fecha_fin
    $('#fecha_inicio').val(moment().subtract(29, 'days').format('YYYY-MM-DD'));
    $('#fecha_fin').val(moment().format('YYYY-MM-DD'));
    $("#tablaInformeCompras").hide();



    $("#btn-buscar").click(function(event) {
      event.preventDefault();
      $("#tablaInformeCompras").show();

      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();
      var tipo_comprobante = $("#tipo-comprobante").val() ?? '';
      var sucursal = $("#sucursales").val() ?? '';
      var empresa = $("#empresa").val() ?? '';
      //crear un string para la consulta get
      var data_get = "";

      if (tipo_comprobante != "") {
        data_get += "tipo_comprobante_id=" + tipo_comprobante + "&";

      }

      if (sucursal != "") {
        data_get += "sucursal_id=" + sucursal + "&";
      }
      if (fecha_inicio != "") {
        data_get += "fecha_inicio=" + fecha_inicio + "&";
      }
      if (fecha_fin != "") {
        data_get += "fecha_fin=" + fecha_fin + "&";
      }
      if(empresa != ""){
        data_get += "empresa_id=" + empresa + "&";
      }

      if ($.fn.DataTable.isDataTable("#tablaInformeCompras")) {
        $("#tablaInformeCompras").DataTable().destroy();
      }
      $("#tablaInformeCompras").show();
      $("#tablaInformeCompras").DataTable({
        processing: true,
                serverSide: true,
                paging: true,
                autoWidth: true,
                ajax: {
                    url: "./ajax/informe_compras/list_datatable.php?" + data_get,
                    type: "POST",
                },
                columns: [
                    { data: "nro_factura", title: "N° Factura" },
                    { data: "fecha", title: "Fecha" },
                    { data: "producto_codigo", title: "Producto" },
                    { data: "costo", title: "Costo" },
                    { data: "cantidad", title: "Cantidad" },
                    { data: "proveedor_id", title: "Proveedor" },
                    { data: "sucursal_id", title: "Sucursal" },
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
                fnDrawCallback: function(settings) {
          var api = this.api();
          var resumen = api.ajax.json().resumen;
          console.log(resumen);

          // Actualizar el contenido del tfoot
          $('#tablaInformeCompras tfoot').empty(); // Limpiar el contenido previo
          $('#tablaInformeCompras tfoot').append(
            '<tr>' +
            '<th colspan="3" style="text-align:right">Total:</th>' +
            '<th><span style="font-weight: bold;color:darkgreen;">$' + resumen.costo + '</span></th>' +
            '<th><span style="font-weight: bold;color:darkgreen;">' + resumen.cantidad + '</span></th>' +
            '<th></th>' +
            '<th></th>' +
            '</tr>'

            
          );
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

      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();
      var tipo_comprobante = $("#tipo-comprobante").val() ?? '';
      var vendedor = $("#vendedores").val() ?? '';
      var sucursal = $("#sucursales").val() ?? '';
      var empresa = $("#empresa").val() ?? '';
      //crear un string para la consulta get
      var data_get = "";

      if (tipo_comprobante != "") {
        data_get += "tipo_comprobante_id=" + tipo_comprobante + "&";

      }

      if (vendedor != "") {
        data_get += "vendedor_id=" + vendedor + "&";
      }
      if (sucursal != "") {
        data_get += "sucursal_id=" + sucursal + "&";
      }
      if (fecha_inicio != "") {
        data_get += "fecha_inicio=" + fecha_inicio + "&";
      }
      if (fecha_fin != "") {
        data_get += "fecha_fin=" + fecha_fin + "&";
      }

      if (empresa != "") {
        data_get += "empresa_id=" + empresa + "&";
      }
      data_get += "&type=excel";
      $.ajax({

        url: "./ajax/informe_compras/export.php?" + data_get,
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
      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();
      var tipo_comprobante = $("#tipo-comprobante").val() ?? '';
      var vendedor = $("#vendedores").val() ?? '';
      var sucursal = $("#sucursales").val() ?? '';
      var empresa = $("#empresa").val() ?? '';
      //crear un string para la consulta get
      var data_get = "";

      if (tipo_comprobante != "") {
        data_get += "tipo_comprobante_id=" + tipo_comprobante + "&";

      }

      if (vendedor != "") {
        data_get += "vendedor_id=" + vendedor + "&";
      }
      if (sucursal != "") {
        data_get += "sucursal_id=" + sucursal + "&";
      }
      if (fecha_inicio != "") {
        data_get += "fecha_inicio=" + fecha_inicio + "&";
      }
      if (fecha_fin != "") {
        data_get += "fecha_fin=" + fecha_fin + "&";
      }

      if (empresa != "") {
        data_get += "empresa_id=" + empresa + "&";
      }

      data_get += "&type=pdf";
      $.ajax({

        url: "./ajax/informe_compras/export.php?" + data_get,
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