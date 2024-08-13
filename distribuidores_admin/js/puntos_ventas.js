try {
    //obtener el id de la empresa
    var empresa_id = $("#empresa_id").val();

    $("#tablaPuntosVenta").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/puntos_venta/list_datatable.php?empresa_id=" + empresa_id,
            type: "POST",
        },
        columns: [{
                data: "descripcion",
                title: "Descripción"
            },
            { data: "numero", title: "Número" },
            { data: "acciones", title: "Acciones" },
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
    $('#btn-buscar-punto-venta').on('click', function() {
        $('#tablaPuntosVenta').DataTable().search($('#buscar-punto-venta').val()).draw();
        console.log($('#buscar-punto-venta').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaUsuarios_filter').hide();
} catch (error) {
    console.error(error);
}



$("#btn-agregar-punto-venta").on("click", function() {
    var empresa_id = $("#empresa_id").val();
    $("#puntoVentaForm")[0].reset();
    $("#puntoVentaForm #btn-guardar-punto-venta").show();
    $("#puntoVentaForm #btn-editar-punto-venta").hide();



});

$("#btn-guardar-punto-venta").on("click", function() {

    let camposVacios = $('#puntoVentaForm input[required], #puntoVentaForm textarea[required], #puntoVentaForm select[required]').filter(function() {
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
        message: '<h1>Guardando punto-venta...</h1>',
    });
    $.ajax({
        type: "POST",
        url: "ajax/puntos_venta/add.php",
        data: $("#puntoVentaForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'punto-venta guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaPuntosVenta").DataTable().ajax.reload();
                $("#puntoVentaForm")[0].reset();
                $("#modal-punto-venta").modal("hide");
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


$("#tablaPuntosVenta").on("click", ".btn-seleccionar-punto-venta", function() {
    var id = $(this).data("id");
    $("#puntoVentaForm #id").val(id);
    $.blockUI({ message: '<h1>Cargando punto-venta...</h1>' });
    $.ajax({
        type: "POST",
        url: "ajax/puntos_venta/list.php?param=" + id,
        data: { id: id },
        success: function(response) {
            var data = response[0];
            $("#puntoVentaForm #btn-guardar-punto-venta").hide();
            $("#puntoVentaForm #btn-editar-punto-venta").show();
            $("#puntoVentaForm #descripcion").val(data.descripcion);
            $("#puntoVentaForm #numero").val(data.numero);
            $("#modal-punto-venta").modal("show");
            $.unblockUI();
        },
    });
});



$("#btn-editar-punto-venta").on("click", function() {
    let camposVacios = $('#puntoVentaForm input[required], #puntoVentaForm textarea[required], #puntoVentaForm select[required]').filter(function() {
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
        message: '<h1>Actualizando punto-venta...</h1>',
    });
    //quito las variables que no necesito
    var formData = $("#puntoVentaForm");
    formData = formData.serialize();

    $.ajax({
        type: "POST",
        url: "ajax/puntos_venta/edit.php",
        data: formData,
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'punto-venta actualizado correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaPuntosVenta").DataTable().ajax.reload();
                $("#puntoVentaForm")[0].reset();
                $("#modal-punto-venta").modal("hide");
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al actualizar el punto-venta',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },

    });
});