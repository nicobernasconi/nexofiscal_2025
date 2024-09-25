try {
    $("#tablaProveedores").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/proveedores/list_datatable.php",
            type: "POST",
        },
        columns: [
            { data: "razon_social", "title": "Razón Social" },
            { data: "direccion", "title": "Dirección" },
            { data: "telefono", "title": "Teléfono" },
            { data: "email", "title": "Email" },
            { data: "cuit", "title": "CUIT" },
            { data: "saldo_actual", "title": "Saldo Actual" },
            { data: "empresa_id", "title": "Empresa" },
            { data: "acciones", "title": "Acciones" },
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
    });
    // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
    //buscar en la tabla cuando hago clic en el boton btn-buscar-proveedor
    $('#btn-buscar-proveedor').on('click', function() {
        $('#tablaProveedores').DataTable().search($('#buscar-proveedor').val()).draw();
        console.log($('#buscar-proveedor').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaEmpresas_filter').hide();

} catch (error) {}




$("#tablaProveedores").on("click", ".btn-seleccionar-proveedor", function() {

    var id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: "ajax/proveedores/list.php?param=" + id,
        success: function(response) {
            var proveedor = response[0];
            console.log(proveedor);
            $("#proveedorForm #id").val(proveedor.id);
            $("#proveedorForm #razon_social").val(proveedor.razon_social);
            $("#proveedorForm #email").val(proveedor.email);
            $("#proveedorForm #telefono").val(proveedor.telefono);
            $("#proveedorForm #direccion").val(proveedor.direccion);
            $("#proveedorForm #cuit").val(proveedor.cuit);
            $("#modal-proveedor").modal("show");

        },
    });



});


$("#btn-editar-proveedor").on("click", function() {
    //validar campos

    let camposVacios = $('#proveedorForm input[required], #proveedorForm textarea[required], #proveedorForm select[required]').filter(function() {
        return this.value === '';
    });

    if (camposVacios.length) {
        new PNotify({
            title: 'Error',
            text: 'Por favor, rellene todos los campos obligatorios.',
            type: 'error',
            styling: 'bootstrap3'
        });
        return;
    }
    $.blockUI({
        message: '<h1>Guardando proveedor...</h1>',
    });
    try {


        $.ajax({
            type: "POST",
            url: "ajax/proveedores/edit.php",
            data: $("#proveedorForm").serialize(),
            success: function(response) {
                var data = JSON.parse(response);
                if (data.status == "201") {
                    new PNotify({
                        title: 'Exito',
                        text: 'proveedor guardada correctamente',
                        type: 'success',
                        styling: 'bootstrap3'
                    });
                    $("#tablaProveedores").DataTable().ajax.reload();
                    $("#proveedorForm")[0].reset();
                    //cerras modal
                    $('#modal-proveedor').modal('hide');
                } else {
                    new PNotify({
                        title: 'Error',
                        text: 'Error al guardar la proveedor',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                }
                $.unblockUI();
            },
        });
    } catch (error) {
        $.unblockUI();
        new PNotify({
            title: 'Error',
            text: 'Error al guardar la proveedor',
            type: 'error',
            styling: 'bootstrap3'
        });


    }
});

$("#tablaProveedores").on("click", ".btn-eliminar-proveedor", function() {
    var id = $(this).data('id');
    var razon_social = $(this).data('razon_social');
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¿Desea eliminar el proveedor " + razon_social + "?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.blockUI({
                message: '<h1>Eliminando proveedor...</h1>',
            });
            $.ajax({
                type: "POST",
                url: "ajax/proveedores/delete.php",
                data: {
                    id: id
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == "201") {
                        new PNotify({
                            title: 'Exito',
                            text: 'proveedor eliminado correctamente',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        $("#tablaProveedores").DataTable().ajax.reload();
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'Error al eliminar la proveedor',
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