try {
    //obtener el id de la empresa
    var empresa_id = $("#empresa_id").val();

    $("#tablaLicencia").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/licencias/list_datatable.php?empresa_id=" + empresa_id,
            type: "POST",
        },
        columns: [
            { data: "id", title: "Número" },
            { data: "fecha_creacion", title: "Fecha de Alta" },
            { data: "ciclo_facturacion", title: "Ciclo de facturación" },
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
    $('#btn-buscar-punto-venta').on('click', function () {
        $('#tablaLicencia').DataTable().search($('#buscar-punto-venta').val()).draw();
        console.log($('#buscar-punto-venta').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaUsuarios_filter').hide();
} catch (error) {
    console.error(error);
}


try {
    $("#tablaSesionesActivas").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/sessions/list_datatable.php?empresa_id=" + empresa_id,
            type: "POST",
        },
        columns: [
            { data: "id", title: "ID" },
            { data: "nombre_usuario", title: "Nombre de Usuario" },
            { data: "licencia_id", title: "Licencia ID" },
            { data: "tiempo", title: "Ultima Actualizacion" },
            { data: "host", title: "IP" },
            { data: "agent", title: "Agent" },
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




} catch (error) {
    
}



$("#btn-guardar-licencia").on("click", function () {
    $("#empresa_id").val(empresa_id);
    $("#ciclo_facturacion").val($("#ciclo_facturacion").val());
    var data = $("#licenciaForm").serialize();

    $.ajax({
        type: "POST",
        url: "ajax/licencias/add.php",
        data: data,
        success: function (response) {
            var data = JSON.parse(response);
            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'La licencia se guardó correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#modal-licencia").modal("hide");
                $("#tablaLicencia").DataTable().ajax.reload();
                   
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Ocurrió un error al guardar la licencia',
                    type: 'error',
                    styling: 'bootstrap3'
                   
                });
                
            }
        },


    });
});

$("#btn-eliminar-licencia").on("click", function () {
    var licencia_id = $("#licencia_id").val();
    $.ajax({
        type: "POST",
        url: "ajax/licencias/edit.php",
        data: { id: licencia_id ,activa:0},
        success: function (response) {
            var data = JSON.parse(response);
            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'La licencia se eliminó correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                   
                });
                    
                $("#modal-licencia-eliminar").modal("hide");
                $("#tablaLicencia").DataTable().ajax.reload();
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Ocurrió un error al eliminar la licencia',
                    type: 'error',
                    styling: 'bootstrap3'
                   
                });
                   
            }
        },
    });
});

$(document).on("click", ".btn-seleccionar-licencia", function () {
    var licencia_id = $(this).data("id");
    $("#licencia_id").val(licencia_id);
});


$(document).on("click", ".btn-seleccionar-sesion", function () {
    var sesion_id = $(this).data("id");
    $("#sesion_id").val(sesion_id);
    
});

$("#btn-sesiones-activas").on("click", function () {
    $("#tablaSesionesActivas").DataTable().ajax.reload();
});


$("#btn-eliminar-sesion").on("click", function () {
    var sesion_id = $("#sesion_id").val();
    $.ajax({
        type: "POST",
        url: "ajax/sessions/delete.php",
        data: { id: sesion_id },
        success: function (response) {
            var data = JSON.parse(response);
            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'La licencia se eliminó correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                   
                });
                $("#modal-sesion-eliminar").modal("hide");
                $("#tablaSesionesActivas").DataTable().ajax.reload();

                    
               
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Ocurrió un error al eliminar la licencia',
                    type: 'error',
                    styling: 'bootstrap3'
                   
                });
                   
            }
        },
    });
});
   

