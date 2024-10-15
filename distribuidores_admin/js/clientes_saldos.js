try {
    $("#tablaClientes").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/clientes/list_datatable.php",
            type: "POST",
        },
        columns: [
            { data: "nro_cliente", "title": "Nro Cliente" },
            { data: "nombre", "title": "Nombre" },
            { data: "cuit", "title": "CUIT" },
            { data: "direccion_comercial", "title": "Dirección Comercial" },
            { data: "localidad_nombre", "title": "Localidad" },
            { data: "tipo_iva_nombre", "title": "Tipo IVA" },
            { data: "saldo_actual", "title": "Saldo Actual" },
            { data: "empresas_razon_social", "title": "Empresa" },

        ],

        language: {
            search: "", // Eliminar el texto de búsqueda predeterminado
            searchPlaceholder: "Buscar...", // Placeholder para el nuevo cuadro de búsqueda
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
            },
        },
        lengthChange: false,
        //searching: false,
        pageLength: 15, // Establecer la cantidad de productos por página predeterminada
        //agregar un total
        footerCallback: function(row, data, start, end, display) {
            var api = this.api(),
                data;

            // Remove the formatting to get integer data for summation
            var intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ?
                    i : 0;
            };

            // Total over all pages
            total = api
                .column(6)
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Total over this page
            pageTotal = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            // Update footer
            $(api.column(6).footer()).html(
                '$' + pageTotal
            );
        }


    });
    // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
    //buscar en la tabla cuando hago clic en el boton btn-buscar-cliente
    $('#btn-buscar-cliente').on('click', function() {
        $('#tablaClientes').DataTable().search($('#buscar-cliente').val()).draw();
        console.log($('#buscar-cliente').val());
    });











    //ocultar tablaEmpresas_filter
    $('#tablaEmpresas_filter').hide();

} catch (error) {}


$("#tablaClientes").on("click", ".btn-seleccionar-cliente", function() {
    var id = $(this).data("id");
    $.blockUI({
        message: '<h1> Cargando datos del cliente...</h1>',
    });
    $.ajax({
        url: "ajax/clientes/list.php",
        method: "GET",
        data: { param: id },
        dataType: "json",
        success: function(data) {
            $('#modal-cliente #id').val(data[0].id);
            $('#modal-cliente #nombre').val(data[0].nombre);
            $('#modal-cliente #numero_documento').val(data[0].numero_documento);
            $('#modal-cliente #cuit').val(data[0].cuit);
            $('#modal-cliente #direccion_comercial').val(data[0].direccion_comercial);
            $('#modal-cliente #direccion_entrega').val(data[0].direccion_entrega);
            $('#modal-cliente #telefono').val(data[0].telefono);
            $('#odal-cliente#celular').val(data[0].celular);
            $('#modal-cliente #email').val(data[0].email);
            $('#modal-cliente #contacto').val(data[0].contacto);
            $('#modal-cliente #telefono_contacto').val(data[0].telefono_contacto);
            $.unblockUI();
        }
    }).done(function() {
        $.unblockUI();
    });

});

$("#btn-editar-cliente").on("click", function() {
    $.ajax({
        type: "POST",
        url: "ajax/clientes/edit.php",
        data: $("#clienteForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status == "201") {
                new PNotify({
                    title: 'Éxito',
                    text: 'Cliente guardado correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaClientes").DataTable().ajax.reload();
                $("#clienteForm")[0].reset();
                // cerrar modal
                $('#modal-cliente').modal('hide');
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al guardar el cliente',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },
    });
});

$("#tablaClientes").on("click", ".btn-eliminar-cliente", function() {
    var id = $(this).data("id");
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.blockUI({ message: '<h1>Eliminando cliente...</h1>' });
            $.ajax({
                type: "POST",
                url: "ajax/clientes/delete.php",
                data: { id: id },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == "201") {
                        new PNotify({
                            title: 'Éxito',
                            text: 'Cliente eliminado correctamente',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        $("#tablaClientes").DataTable().ajax.reload();
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'Error al eliminar el cliente',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                    }
                    $.unblockUI();
                },
            });
        }
    });
});