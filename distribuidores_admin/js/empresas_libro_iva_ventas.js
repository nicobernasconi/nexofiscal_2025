  //cargar select2 de empresas
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
          empresas.forEach(empresa => {
              let option = document.createElement('option');
              option.value = empresa.id;
              option.text = empresa.nombre;
              select.appendChild(option);
          });
      },
      complete: function() {
          // Enable the select after loading
          $('#empresa').prop('disabled', false);
      }
  });


  var optionSet1 = {
      startDate: moment().startOf('month'),
      endDate: moment().endOf('month'),
      minDate: '01/01/2012',
      maxDate: '31/12/2050',
      showDropdowns: true,
      timePicker: false,
      singleDatePicker: true, // Solo permite una selección de fecha
      format: 'MM/YYYY',
      locale: {
          format: 'MM/YYYY',
          applyLabel: 'Aceptar',
          cancelLabel: 'Limpiar',
          fromLabel: 'Desde',
          toLabel: 'Hasta',
          customRangeLabel: 'Rango',
          daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          firstDay: 1
      },
      isInvalidDate: function(date) {
          // Desactivar todos los días excepto el primero del mes
          return date.date() !== 1;
      }
  };

  $('#periodo').daterangepicker(optionSet1, function(start, end, label) {
      // Obtener el primer día del mes seleccionado
      var startOfMonth = start.startOf('month').format('YYYY-MM-DD');
      // Obtener el último día del mes seleccionado
      var endOfMonth = start.endOf('month').format('YYYY-MM-DD');

      // Establecer las fechas en los campos correspondientes
      $('#fecha_inicio').val(startOfMonth);
      $('#fecha_fin').val(endOfMonth);
  });

  $('#periodo').on('apply.daterangepicker', function(ev, picker) {
      var startOfMonth = picker.startDate.startOf('month').format('YYYY-MM-DD');
      var endOfMonth = picker.startDate.endOf('month').format('YYYY-MM-DD');

      $('#fecha_inicio').val(startOfMonth);
      $('#fecha_fin').val(endOfMonth);
  });

  $('#periodo').on('cancel.daterangepicker', function(ev, picker) {
      $('#fecha_inicio').val('');
      $('#fecha_fin').val('');
  });
  //inicializar fecha_inicio y fecha_fin
  $('#fecha_inicio').val(moment().startOf('month').format('YYYY-MM-DD'));
  $('#fecha_fin').val(moment().endOf('month').format('YYYY-MM-DD'));
  $("#tablaInformeLibroIvaVentas").hide();



  $("#btn-buscar").click(function(event) {
      event.preventDefault();
      $("#tablaInformeLibroIvaVentas").show();

      var fecha_inicio = $("#fecha_inicio").val();
      var fecha_fin = $("#fecha_fin").val();
      var empresa_id = $("#empresa").val();
      //crear un string para la consulta get
      var data_get = "";

      if (empresa_id != "") {
          data_get += "empresa_id=" + empresa_id + "&";

      }
      if (fecha_inicio != "") {
          data_get += "fecha_inicio=" + fecha_inicio + "&";
      }
      if (fecha_fin != "") {
          data_get += "fecha_fin=" + fecha_fin + "&";
      }

      if ($.fn.DataTable.isDataTable("#tablaInformeLibroIvaVentas")) {
          $("#tablaInformeLibroIvaVentas").DataTable().destroy();
      }
      $("#tablaInformeLibroIvaVentas").show();
      $("#tablaInformeLibroIvaVentas").DataTable({
          processing: true,
          serverSide: true,
          paging: true,
          autoWidth: true,
          ajax: {
              url: "./ajax/informe_libro_iva_ventas/list_datatable.php?" + data_get,
              type: "POST",
          },
          columns: [{
                  data: "dia",
                  title: "Día"
              },
              {
                  data: "numero_factura",
                  title: "Número de Factura"
              },
              {
                  data: "cuit",
                  title: "CUIT"
              },
              {
                  data: "cliente",
                  title: "Cliente"
              },
              {
                  data: "ng21",
                  title: "NG.21"
              },
              {
                  data: "ng105",
                  title: "NG.10.5"
              },
              {
                  data: "ng0",
                  title: "NG.0"
              },
              {
                  data: "int",
                  title: "Int."
              },
              {
                  data: "iibb",
                  title: "IIBB"
              },
              {
                  data: "iva21",
                  title: "IVA 21"
              },
              {
                  data: "iva105",
                  title: "IVA 10.5"
              },
              {
                  data: "iva0",
                  title: "IVA 0"
              },
              {
                  data: "total",
                  title: "Total"
              },
          ],
          //establecer alineacion para las columnas numericas
          columnDefs: [{
              targets: [4, 5, 6, 7, 8, 9, 10, 11, 12],
              className: 'dt-body-right'
          }],
          //establecer 2,3,4 como moneda
          createdRow: function(row, data, dataIndex) {
              $(row).find('td:eq(4)').html('$' + data.ng21);
              $(row).find('td:eq(5)').html('$' + data.ng105);
              $(row).find('td:eq(6)').html('$' + data.ng0);
              $(row).find('td:eq(7)').html('$' + data.int);
              $(row).find('td:eq(8)').html('$' + data.iibb);
              $(row).find('td:eq(9)').html('$' + data.iva21);
              $(row).find('td:eq(10)').html('$' + data.iva105);
              $(row).find('td:eq(11)').html('$' + data.iva0);
              $(row).find('td:eq(12)').html('$' + data.total);
          },
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

          },
          fnDrawCallback: function(settings) {
              var api = this.api();
              var resumen = api.ajax.json().resumen;
              console.log(resumen);

              // Actualizar el contenido del tfoot
              $('#tablaInformeLibroIvaVentas tfoot').empty(); // Limpiar el contenido previo
              $('#tablaInformeLibroIvaVentas tfoot').append(
                  '<tr>' +
                  '<td colspan="4"><b>Total:</b></td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_ng21 + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_ng105 + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_ng0 + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_int + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iibb + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iva21 + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iva105 + '</td>' +
                  '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iva0 + '</td>' +
                  '<td  style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total + '</td>' +
                  '</tr>'

              );
          },
      });
      //ocultar search
      $("#tablaInformeVentas_filter").hide();

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
      var tipo_comprobante = $("#tipo-comprobante").val() || '';
      var vendedor = $("#vendedores").val() || '';
      var sucursal = $("#sucursales").val() || '';
      var empresa = $("#empresa").val() || '';
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

          url: "./ajax/informe_libro_iva_ventas/export.php?" + data_get,
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
      var tipo_comprobante = $("#tipo-comprobante").val() || '';
      var vendedor = $("#vendedores").val() || '';
      var sucursal = $("#sucursales").val() || '';
      var empresa = $("#empresa").val() || '';
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

          url: "./ajax/informe_libro_iva_ventas/export.php?" + data_get,
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