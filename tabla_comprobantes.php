
<?php

include("includes/session_parameters.php");
if (in_array('listar', $permisos_asignados['comprobantes'])) { ?>
    <section class="tablaComprobante">
        <div class="row">
            <div class="col-md-8">
                <div class="custom-search-container">
                    <input type="number" min="0" step="1" id="custom-search-comprobantes-input" class="custom-search-input" placeholder="Buscar por Nro. de comprobante...">
                    <i class="fas fa-search custom-search-icon"></i>
                </div>
            </div>
        </div>
        <!-- Aquí se mostrará el DataTable -->
        <div class="row">
            <div class="col-md-12"> <!-- Cambiado el tamaño de la columna a 12 -->
                <div class="table-responsive"> <!-- Agregado el contenedor de tabla responsive -->
                    <table class="table" id="tablaComprobante">
                        <thead>
                            <tr style="font-size: 12px!important;">
                                <th>#</th>
                                <th>Tipo</th>
                                <th>N°</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th></th>
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
    <div id="menu-reimpresion-modal" class="custom-modal">
        <div id="mensajeReImprimirComoprobante" style="color: red;background-color: #f8d7da;padding: 10px;border-radius: 5px;display:none;">Error al reimprimir el comprobante</div>
        <input type="hidden" id="comprobante-id">
        <input type="hidden" id="tipo_comprobante">
        <div class="custom-modal-content-s">
            <span class="custom-modal-close">&times;</span>
            <h2>Elija el formato de impresión</h2>
            <div class="row">
                <ul>
                    <li><a href="#" id="btn-reimprimir-a4"><i class="fas fa-file-pdf"> Formato A4</i></a></li>
                    <li><a href="#" id="btn-reimprimir-tickets"><i class="fas fa-ticket-alt"> Formato Ticket58</i></a></li>
                </ul>
            </div>
            <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
        </div>
    </div>



    <div id="anular-comprobante-modal" class="custom-modal">
        <div class="custom-modal-content-s">
            <span class="custom-modal-close">&times;</span>
            <h2>Anular comprobante</h2>
            <div class="row">
                <div id="mensajeAnularComoprobante" style="color: red;background-color: #f8d7da;padding: 10px;border-radius: 5px;display:none;">Error al anular el comprobante</div>

                <div class="col-md-12">
                    <!-- Campo para mostrar el monto de efectivo final en caja -->
                    <input type="hidden" id="usuario_id" name="usuario_id" value="<?php echo $_SESSION['usuario_id']; ?>">
                    <input type="hidden" id="comprobante_id">
                    <label for="motivo_baja">Motivos de la anulacion:</label>
                    <textarea class="form-control" id="motivo_baja" name="motivo_baja" type="text" required></textarea>
                </div>
            </div>

            <button id="btn-anular-comprobante" class="btn-cancelar">Anula Comprobante</button>

            <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>

        </div>
    </div>
    <script>
        var z_indexc=0;
    function mostrarNotificacionC(titulo, tipo, mensaje, boton_id, id, reiniciar) {
        // Limpiar el contenido del modal
        $("#mensajeModal").empty();
        // Agregar el icono según el tipo de notificación
        var icono = "";
        if (tipo === "error") {
            icono = '<i class="fa-solid fa-exclamation-triangle" style="color: red;"></i>';
        } else if (tipo === "advertencia") {
            icono = '<i class="fa-solid fa-exclamation-circle" style="color: orange;"></i>';
        } else if (tipo === "exito") {
            icono = '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
        } else if (tipo === "eliminar" || tipo === "anular") { // Si el tipo es "eliminar", agregar un botón de borrado
            icono = '<i class="fa-solid fa-trash" style="color: red;"></i>';
        } else {
            icono = '<i class="fa-solid fa-info-circle" style="color: blue;"></i>';
        }

        // Construir el contenido del modal
        var contenido = "<h3>" + icono + " " + titulo + "</h3>";
        contenido += "<p>" + mensaje + "</p>";

        // Agregar el contenido al modal
        $("#mensajeModal").html(contenido);

        // Si se proporciona un id de botón, agregar un botón adicional al contenido del modal
        if (tipo === "eliminar") {
            //agergar el boton al final de custom-modal-content-s
            $("#mensaje-modal .custom-modal-close-btn").hide();
            $("#botonesMensaje").empty();
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-eliminar" class="btn-cancelar " data-id="' + id + '">Eliminar</button>');
            //boton volver atras
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-volver" class="btn-cerrar">Volver</button>');
            //agregar evento click al boton cerrar
            $("#btn-" + boton_id + "-volver").click(function() {
                $("#mensaje-modal").fadeOut();
            });
            $("#btn-" + boton_id + '-eliminar').click(function() {
                var id = $(this).data("id");
                $.unblockUI("Eliminando...");
                $.ajax({
                    url: "./ajax/" + boton_id + "/delete.php",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $("#mensaje-modal").fadeOut();
                        $.unblockUI();
                        var data = JSON.parse(response);
                        if (data.status == "201") {
                            mostrarNotificacionC("Eliminado", "exito", "El registro ha sido eliminado correctamente", "", "");
                        } else {
                            mostrarNotificacionC("Error", "error", "El registro no pudo ser eliminado: <br>" + data.status_message, "", "");
                        }
                    },
                    error: function(xhr, status, error) {

                        $.unblockUI();
                    },
                });



            });

        } else if (tipo === "anular") {
            //agergar el boton al final de custom-modal-content-s
            $("#mensaje-modal .custom-modal-close-btn").hide();
            $("#botonesMensaje").empty();
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-anular" class="btn-cancelar " data-id="' + id + '">Anular</button>');
            //boton volver atras
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-volver" class="btn-cerrar">Volver</button>');
            //agregar evento click al boton cerrar
            $("#btn-" + boton_id + "-volver").click(function() {
                $("#mensaje-modal").fadeOut();
            });

            $("#btn-" + boton_id + '-anular').click(function() {
                var id = $(this).data("id");
                $.unblockUI("Anulando...");
                $.ajax({
                    url: "./ajax/" + boton_id + "/anular.php",
                    method: "POST",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $("#mensaje-modal").fadeOut();
                        $.unblockUI();
                        var data = JSON.parse(response);
                        if (data.status == "201") {
                            mostrarNotificacionC("Anulando", "exito", "El registro ha sido anulado correctamente", "", "");
                        } else {
                            mostrarNotificacionC("Error", "error", "El registro no pudo ser anulado: <br>" + data.status_message, "", "");
                        }
                    },
                    error: function(xhr, status, error) {

                        $.unblockUI();
                    },
                });



            });

        } else {
            $("#botonesMensaje").empty();
            $("#mensaje-modal .custom-modal-close-btn").show();
        }
        // Mostrar el modal
        $("#mensaje-modal").fadeIn().css("z-index", z_indexc++);
        //asignar z-index
        $("#mensaje-modal").css("z-index", z_indexc++);
    }
</script>

    <script>
        try {
            $("#tablaComprobante").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/comprobantes/list_datatable.php",
                    type: "POST",
                },
                columns: [{
                        data: "id"
                    },
                    {
                        data: "tipo_comprobante"
                    },
                    {
                        data: 'numero',
                        render: function(data, type, row) {
                            if (row.tipo_comprobante != 'PDO') {
                                return row.numero_factura;
                            } else {
                                return row.numero;
                            }

                        }
                    },
                    {
                        data: "cliente_nombre"
                    },
                    {
                        data: "fecha"
                    },
                    {
                        data: "total"
                    },
                    {
                        data: null,
                        render: function(data, type, row) {
                            var boton_anular = '';
                            if (!row.anulado && row.tipo_comprobante != 'NC' && row.tipo_comprobante != 'PDO') {
                                boton_anular = '<button class="btn btn-danger btn-re-anular" style="margin-right: 5px;" data-id="' + row.id + '"  data-tipo="' + row.tipo_comprobante + '"><i class="fas fa-ban" style="color:#e53434;font-size: 15px;"></i></button>';
                            }

                            if (row.tipo_comprobante == 'FC' || row.tipo_comprobante == 'PDO' || row.tipo_comprobante == 'NC') {
                                boton_reimprimir = '<botton class="btn-re-imprimir" data-id="' + row.id + '" + data-tipo="' + row.tipo_comprobante + '"><i class="fas fa-print"style="color:#599f3c;font-size: 15px;"></i></botton>';
                            }
                            return boton_reimprimir + boton_anular;

                        },
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],
                order: [
                    [0, "desc"]
                ],
                //no ordenar la columna de botones y la primera
                columnDefs: [{
                        orderable: false,
                        targets: [6]
                    },
                    {
                        orderable: false,
                        targets: [1]
                    }
                ],
                rowCallback: function(row, data) {
                    if (data.anulado) {
                        $(row).css('background-color', '#ffe6e6'); // Cambia el fondo a rojo suave
                    }
                },
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
                    },
                },
                lengthChange: false,
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
                dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>" // Definir la estructura del DOM
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-comprobantes-input").on("change", function() {
                $("#tablaComprobante").DataTable().search($(this).val()).draw();
            });
            $("#tablaComprobante_filter").hide();
        } catch (error) {}


        $("#tablaComprobante").on("click", ".btn-re-imprimir", function() {
            $("#mensajeReImprimirComoprobante").hide();
            let id = $(this).data("id");
            $("#comprobante-id").val(id);
            $("#tipo_comprobante").val($(this).data("tipo"));
            $('#menu-reimpresion-modal').fadeIn();
        });


        $("#menu-reimpresion-modal").on("click", "#btn-reimprimir-a4", function() {
            let id = $("#comprobante-id").val();
            let tipo_comprobante = $("#tipo_comprobante").val();
            $.blockUI({
                message: '<h1>Imprimiendo comprobante...</h1>',
            });
            if (tipo_comprobante == 'FC' || tipo_comprobante == 'NC') {
                $.ajax({
                    url: "./re_print_factura.php",
                    type: "POST",
                    data: {
                        id: id,
                        tipo_template: 'factura',
                        tipo_comprobante: tipo_comprobante,
                    },
                    success: function(response) {
                        try {


                            var responseData = JSON.parse(response);
                            if (responseData.status == 201) {

                                console.log(responseData);
                                var pdfUrl = responseData.pdfUrl;
                                printJS({
                                    printable: pdfUrl,
                                    type: "pdf",
                                    silent: true
                                });
                                $(".custom-modal").fadeOut();
                                $.unblockUI();
                            } else {

                                $.unblockUI();
                                mostrarNotificacionC(
                                    "error",
                                    "Error al imprimir el comprobante",
                                    'Intemte nuevamente'
                                )
                            }
                        } catch (error) {
                            $.unblockUI();
                            mostrarNotificacionC(
                                "error",
                                "Error al imprimir el comprobante",
                                'Intemte nuevamente'
                            )
                        }
                    },
                });
            } else {
                $.ajax({
                    url: "./re_print_pedido.php",
                    type: "POST",
                    data: {
                        id: id,
                        tipo_template: 'factura',
                        tipo_comprobante: tipo_comprobante,
                    },
                    success: function(response) {
                        try {


                            var responseData = JSON.parse(response);
                            if (responseData.status == 201) {

                                console.log(responseData);
                                var pdfUrl = responseData.pdfUrl;
                                printJS({
                                    printable: pdfUrl,
                                    type: "pdf",
                                    silent: true
                                });
                                $(".custom-modal").fadeOut();
                                $.unblockUI();
                            } else {

                                $.unblockUI();
                                mostrarNotificacionC(
                                    "error",
                                    "Error al imprimir el comprobante",
                                    responseData
                                )
                            }
                        } catch (error) {
                            $.unblockUI();
                            mostrarNotificacionC(
                                "error",
                                "Error al imprimir el comprobante",
                                'Intemte nuevamente'
                            )

                        }
                    },
                });
            }
        });

        $("#menu-reimpresion-modal").on("click", "#btn-reimprimir-tickets", function() {
            let id = $("#comprobante-id").val();
            let tipo_comprobante = $("#tipo_comprobante").val();
            $.blockUI({
                message: '<h1>Imprimiendo comprobante...</h1>',
            });
            if (tipo_comprobante == 'FC' || tipo_comprobante == 'NC') {
                $.ajax({
                    url: "./re_print_factura.php",
                    type: "POST",
                    data: {
                        id: id,
                        tipo_template: 'tickets',
                        tipo_comprobante: tipo_comprobante,

                    },
                    success: function(response) {
                        var responseData = JSON.parse(response);
                        if (responseData.status == 201) {
                            console.log(responseData);
                            var pdfUrl = responseData.pdfUrl;
                            printJS({
                                printable: pdfUrl,
                                type: "pdf",
                                silent: true
                            });
                            $.unblockUI();

                            $(".custom-modal").fadeOut();
                            $("#mensajeReImprimirComoprobante").hide();
                        } else {
                            $("#mensajeReImprimirComoprobante").show();
                            $.unblockUI();
                            mostrarNotificacionC(
                                "error",
                                "Error al imprimir el comprobante",
                                'Intemte nuevamente'
                            )
                        }
                    },
                });
            } else {
                $.ajax({
                    url: "./re_print_pedido.php",
                    type: "POST",
                    data: {
                        id: id,
                        tipo_template: 'tickets',
                        tipo_comprobante: tipo_comprobante,

                    },
                    success: function(response) {
                        try {
                            var responseData = JSON.parse(response);
                            if (responseData.status == 201) {
                                console.log(responseData);
                                var pdfUrl = responseData.pdfUrl;
                                printJS({
                                    printable: pdfUrl,
                                    type: "pdf",
                                    silent: true
                                });
                                $.unblockUI();

                                $(".custom-modal").fadeOut();
                                $("#mensajeReImprimirComoprobante").hide();
                            } else {
                                $("#mensajeReImprimirComoprobante").show();
                                $.unblockUI();
                            mostrarNotificacionC(
                                "error",
                                "Error al imprimir el comprobante",
                                'Intemte nuevamente'
                            )
                            }
                        } catch (error) {
                            $("#mensajeReImprimirComoprobante").show();
                            $.unblockUI();
                            mostrarNotificacionC(
                                "error",
                                "Error al imprimir el comprobante",
                                'Intemte nuevamente'
                            )

                        }
                    },
                });
            }
        });

        $(".custom-modal-close-btn , .custom-modal-close").click(function() {
            $(".custom-modal").fadeOut();
        });

        $("#tablaComprobante").on("click", ".btn-re-anular", function() {
            $("#mensajeAnularComoprobante").hide();
            let id = $(this).data("id");
            $("#comprobante_id").val(id);
            $('#anular-comprobante-modal').fadeIn();
        });

        $("#btn-anular-comprobante").click(function() {
            let id = $("#comprobante_id").val();
            let motivo_baja = $("#motivo_baja").val();
            let usuario_id = $("#usuario_id").val();
            $.blockUI({
                message: '<h1>Anulando comprobante...</h1>',
            });
            $.ajax({
                url: "./anular_factura.php",
                type: "POST",
                data: {
                    id: id,
                    motivo_baja: motivo_baja,
                    usuario_id: usuario_id,
                },
                success: function(response) {
                    try {
                        var responseData = JSON.parse(response);
                        console.log(responseData);
                        if (responseData.status == 201) {
                            $.unblockUI();
                            $(".custom-modal").fadeOut();
                            printJS({
                                printable: responseData.pdfUrl,
                                type: 'pdf',
                                silent: true
                            });

                            $("#tablaComprobante").DataTable().ajax.reload();
                            $("#mensajeAnularComoprobante").hide();
                        } else {
                            $("#mensajeAnularComoprobante").show();

                            $.unblockUI();
                        }
                    } catch (error) {
                        $("#mensajeAnularComoprobante").show();
                        $.unblockUI();
                    }
                },
            });
        });
        
        $('#custom-search-comprobantes-input').on('keyup', function() {
            $('#tablaComprobante').DataTable().search($(this).val()).draw();
        });
    </script>
<?php } ?>