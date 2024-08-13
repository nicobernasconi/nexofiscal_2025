  
  
  <?php 
  include("includes/session_parameters.php");
  
  if (in_array('listar', $permisos_asignados['stock'])) {?>

<section class="tablaStocks">
    <div class="row">
        <div class="col-md-8">
            <div class="custom-search-container">
                <input type="text" id="custom-search-comprobantes-input" class="custom-search-input" placeholder="Buscar comprobante...">
                <i class="fas fa-search custom-search-icon"></i>
            </div>
        </div>
    </div>
    <!-- Aquí se mostrará el DataTable -->
    <div class="row">
        <div class="col-md-12"> <!-- Cambiado el tamaño de la columna a 12 -->
            <div class="table-responsive"> <!-- Agregado el contenedor de tabla responsive -->
                <table class="table" id="tablaStocks">
                    <thead>
                        <tr style="font-size: 12px!important;">
                            <th>Descripcion</th>
                            <th>Stock</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="comprobante-list" style="font-size: 12px!important;">
                        <!-- Aquí se agregarán dinámicamente los productos -->
                    </tbody>
                    <tfoot>
                        <!-- Footer de la tabla -->
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</section>
<script>
    try {
        $("#tablaStocks").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "./ajax/stocks/list_datatable.php",
                type: "POST",
            },
            columns: [{
                    data: "descripcion"
                },
                {
                    data: "stock"
                },
                {
                    data: 'stock',
                    render: function(data, type, row) {
                        var estado = '';
                        if (parseFloat(row.stock_minimo) >= parseFloat(row.stock)) {
                            estado = '<span class="estado-badge estado-danger"><i class="fas fa-exclamation-circle"></i> Stock mínimo alcanzado</span>';
                        } else if (parseFloat(row.stock_pedido) >= parseFloat(row.stock)) {
                            estado = '<span class="estado-badge estado-warning"><i class="fas fa-truck"></i> Stock suficiente para pedido</span>';

                        } else {
                            estado = '<span class="estado-badge estado-success"><i class="fas fa-check-circle"></i> Stock normal</span>';
                        }
                        return estado;
                    }
                }
            ],
            order: [
                [1, "desc"]
            ],

            language: {
                search: "", // Eliminar el texto de búsqueda predeterminado
                searchPlaceholder: "Buscar producto...", // Placeholder para el nuevo cuadro de búsqueda
                sProcessing: "Procesando...",
                sLengthMenu: "Mostrar _MENU_ registros",
                sZeroRecords: "No se encontraron resultados",
                sEmptyTable: "Ningún dato disponible en esta tabla",
                sInfo: "",
                sInfoEmpty: "",
                sInfoFiltered: "",
                sInfoPostFix: "",
                sSearch: "Buscar:", // Cambiado a la izquierda
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
                },
                

            },
            lengthChange: false,
            pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            //no puede ordenarse por la ultima columna
            
             

        
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Definir la estructura del DOM
        });
        // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
        $("#custom-search-comprobantes-input").on("change", function() {
            $("#tablaStocks").DataTable().search($(this).val()).draw();
        });
        $("#tablaStocks_filter").hide();
    } catch (error) {}
</script>
 <?php } ?>