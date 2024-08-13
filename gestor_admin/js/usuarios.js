try {
    //obtener el id de la empresa
    var sucursal_id = $("#sucursal_id").val();
    var empresa_id = $("#empresa_id").val();

    $("#tablaUsuarios").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/usuarios/list_datatable.php?empresa_id=" + empresa_id + "&sucursal_id=" + sucursal_id,
            type: "POST",
        },
        columns: [{
                //checkbox id="check-all" class="flat"

                data: null,
                render: function(data, type, row) {
                    return `<input type="checkbox" name="chkEmpresa" id="check-all" class="flat" value="${row.id}">`;
                }
            },

            { data: "nombre_usuario" },
            { data: "nombre_completo" },
            { data: "rol" },
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
    $('#btn-buscar-usuario').on('click', function() {
        $('#tablaUsuarios').DataTable().search($('#buscar-usuario').val()).draw();
        console.log($('#buscar-usuario').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaUsuarios_filter').hide();
} catch (error) {
    console.error(error);
}



$("#btn-agregar-usuario").on("click", function() {
    var empresa_id = $("#empresa_id").val();
    var sucursal_id = $("#sucursal_id").val();
    $("#usuarioForm")[0].reset();
    $("#usuarioForm #btn-guardar-usuario").show();
    $("#usuarioForm #btn-editar-usuario").hide();
    $("#usuarioForm #password").prop("required", true);
    $("#usuarioForm #password").show();
    $("#usuarioForm #id").val(empresa_id);
    $("#usuarioForm #empresa_id").val(empresa_id);
    $("#usuarioForm #sucursal_id").val(sucursal_id);
    let select = document.getElementById('rol_id');
    //vaciar el select
    select.innerHTML = "";

    $.ajax({
        type: "GET",
        url: "ajax/roles/list.php?empresa_id=" + empresa_id,
        data: { empresa_id: empresa_id },
        success: function(response) {
            var roles = response;
            roles.forEach(function(rol) {
                var option = document.createElement('option');
                option.value = rol.id;
                option.text = rol.descripcion;
                select.appendChild(option);
            });

        },
    });





});

$("#btn-guardar-usuario").on("click", function() {
    let camposVacios = $('#usuarioForm input[required], #usuarioForm textarea[required], #usuarioForm select[required]').filter(function() {
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
        message: '<h1>Guardando Usuario...</h1>',
    });
    $.ajax({
        type: "POST",
        url: "ajax/usuarios/add.php",
        data: $("#usuarioForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'Usuario guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaUsuarios").DataTable().ajax.reload();
                $("#usuarioForm")[0].reset();
                $("#modal-usuario").modal("hide");
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


$("#tablaUsuarios").on("click", ".btn-seleccionar-usuario", function() {
    var id = $(this).data("id");
    $("#usuarioForm #btn-guardar-usuario").hide();
    $("#usuarioForm #btn-editar-usuario").show();
    $("#usuarioForm #password").prop("required", false);
    $("#usuarioForm #password").hide();
    $("#usuarioForm #id").val(id);
    $.blockUI({ message: '<h1>Cargando Usuario...</h1>' });
    $.ajax({
        type: "POST",
        url: "ajax/usuarios/list.php?param=" + id,
        data: { id: id },
        success: function(response) {
            var data = response[0];
            $("#usuarioForm #btn-guardar-usuario").hide();
            $("#usuarioForm #btn-editar-usuario").show();
            $("#usuarioForm #id").val(data.id);
            $("#usuarioForm #nombre_usuario").val(data.nombre_usuario);
            $("#usuarioForm #nombre_completo").val(data.nombre_completo);
            $("#usuarioForm #rol_id").val(data.rol_id);
            $("#usuarioForm #activo").val(data.activo);
            $("#usuarioForm #empresa_id").val(data.empresa_id);
            $("#usuarioForm #sucursal_id").val(data.sucursal_id);
            $("#usuarioForm #lista_precios").html("");
            $("#usuarioForm #lista_precios").append(`<option value="${data.lista_precios}">Lista ${data.lista_precios}</option><option value="1">Lista 1</option><option value="2">Lista 2</option><option value="3">Lista 3</option>`);
            $("#usuarioForm #venta_rapida").html("");
            $("#usuarioForm #venta_rapida").append(`<option value="${data.venta_rapida}">${data.venta_rapida == 1 ? 'SI' : 'NO'}</option><option value="1">SI</option><option value="0">NO</option>`);
            $("#usuarioForm #imprimir").html("");
            $("#usuarioForm #imprimir").append(`<option value="${data.imprimir}">${data.imprimir == 1 ? 'SI' : 'NO'}</option><option value="1">SI</option><option value="0">NO</option>`);
            $("#usuarioForm #tipo_comprobante_imprimir").html("");
            $("#usuarioForm #tipo_comprobante_imprimir").append(`<option value="${data.tipo_comprobante_imprimir}">${data.tipo_comprobante_imprimir == 1 ? 'Ticket' : 'A4'}</option><option value="1">Ticket</option><option value="2">A4</option>`);

            $("#modal-usuario").modal("show");
            $.unblockUI();
        },
    });
});
let select = document.getElementById('rol_id');
//vaciar el select
select.innerHTML = "";
$.ajax({
    type: "GET",
    url: "ajax/roles/list.php?empresa_id=" + empresa_id,
    data: { empresa_id: empresa_id },
    success: function(response) {
        var roles = response;
        roles.forEach(function(rol) {
            var option = document.createElement('option');
            option.value = rol.id;
            option.text = rol.descripcion;
            select.appendChild(option);
        });

    },
});


$("#btn-editar-usuario").on("click", function() {
    let camposVacios = $('#usuarioForm input[required], #usuarioForm textarea[required], #usuarioForm select[required]').filter(function() {
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
        message: '<h1>Actualizando Usuario...</h1>',
    });
    //quito las variables que no necesito
    var formData = $("#usuarioForm");
    formData.find("input[name='password']").remove();
    formData.find("input[name='password_confirmation']").remove();
    formData = formData.serialize();

    $.ajax({
        type: "POST",
        url: "ajax/usuarios/edit.php",
        data: formData,
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'Usuario actualizado correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaUsuarios").DataTable().ajax.reload();
                $("#usuarioForm")[0].reset();
                $("#modal-usuario").modal("hide");
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al actualizar el usuario',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },

    });
});