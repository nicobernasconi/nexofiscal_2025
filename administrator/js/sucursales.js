try {
    //obtener el id de la empresa
    var empresa_id = $("#empresa_id").val();

    $("#tablaSucursal").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/sucursales/list_datatable.php?id=" + empresa_id,
            type: "POST",
        },
        columns: [{
                //checkbox id="check-all" class="flat"

                data: null,
                render: function(data, type, row) {
                    return `<input type="checkbox" name="chkEmpresa" id="check-all" class="flat" value="${row.id}">`;
                }
            },

            { data: "nombre" },
            { data: "direccion" },
            { data: "telefono" },
            { data: "email" },
            { data: "contacto_nombre" },
            { data: "acciones" }
        ],
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

        lengthChange: false,
        //searching: false,
        pageLength: 15, // Establecer la cantidad de productos por página predeterminada
    });
    $('#btn-buscar-sucursal').on('click', function() {
        $('#tablaSucursal').DataTable().search($('#buscar-sucursal').val()).draw();
        console.log($('#buscar-sucursal').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaSucursal_filter').hide();
} catch (error) {
    console.error(error);
}

$("#btn-agregar-usuario").on("click", function() {
    $("#sucursalForm #btn-editar-sucursal").hide();
    $("#sucursalForm #btn-guardar-sucursal").show();
});

$("#btn-guardar-sucursal").on("click", function() {
    let camposVacios = $('#sucursalForm input[required], #sucursalForm textarea[required], #sucursalForm select[required]').filter(function() {
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
        message: '<h1>Guardando Sucursal...</h1>',
    });

    $.ajax({
        type: "POST",
        url: "ajax/sucursales/add.php",
        data: $("#sucursalForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'Sucursal guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaSucursal").DataTable().ajax.reload();
                $("#sucursalForm")[0].reset();
                $("#modal-sucursal").modal("hide");
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al guardar la Sucursal',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },
    });
});

$("#tablaSucursal").on("click", ".btn-seleccionar-sucursal", function() {
    var id = $(this).data("id");
    $.ajax({
        type: "POST",
        url: "ajax/sucursales/list.php?param=" + id,
        data: { id: id },
        success: function(response) {
            var data = response[0];
            $("#sucursalForm #btn-guardar-sucursal").hide();
            $("#sucursalForm #btn-editar-sucursal").show();
            $("#sucursalForm #id").val(data.id);
            $("#sucursalForm #nombre").val(data.nombre);
            $("#sucursalForm #direccion").val(data.direccion);
            $("#sucursalForm #telefono").val(data.telefono);
            $("#sucursalForm #email").val(data.email);
            $("#sucursalForm #contacto_nombre").val(data.contacto_nombre);
            $("#modal-sucursal").modal("show");
        },
    });
});

$("#btn-editar-sucursal").on("click", function() {
    let camposVacios = $('#sucursalForm input[required], #sucursalForm textarea[required], #sucursalForm select[required]').filter(function() {
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
        message: '<h1>Actualizando Sucursal...</h1>',
    });
    $.ajax({
        type: "POST",
        url: "ajax/sucursales/edit.php",
        data: $("#sucursalForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'Sucursal actualizada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaSucursal").DataTable().ajax.reload();
                $("#sucursalForm")[0].reset();
                $("#modal-sucursal").modal("hide");
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al actualizar la Sucursal',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },
    });
});