try {
    $("#tablaProvvedores").DataTable({
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
        $('#tablaProvvedores').DataTable().search($('#buscar-proveedor').val()).draw();
        console.log($('#buscar-proveedor').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaEmpresas_filter').hide();

} catch (error) {}

$('#btn-agregar-proveedor').on('click', function() {
    $('#empresaForm')[0].reset();
    $('#empresaForm #id').val('');
    $('#empresaForm #btn-guardar-proveedor').show();
    $("#empresaForm #panel-agragar-sucursal").show();
    $("#empresaForm #panel-password").show();
    $('#empresaForm #btn-editar-proveedor').hide();
    $("#panel-agragar-sucursal").show();
    //cargar select de tipo_iva_id
    $.ajax({
        type: "POST",
        url: "ajax/tipos_iva_empresa/list.php",

        success: function(response) {
            var tipos_iva = JSON.parse(response);
            let select = $('#empresaForm #tipo_iva_id');
            //vaciar el select
            select.innerHTML = "";
            //agregar la opcion todos
            let option = document.createElement('option');
            option.value = "";
            option.text = "Todos";
            select.appendChild(option);
            tipos_iva.forEach(vendedor => {
                let option = document.createElement('option');
                option.value = vendedor.id;
                option.text = vendedor.nombre;
                select.appendChild(option);
            });

        },
    });


});

$("#btn-guardar-proveedor").on("click", function() {
    //validar campos

    let camposVacios = $('#empresaForm input[required], #empresaForm textarea[required], #empresaForm select[required]').filter(function() {
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
    $.ajax({
        type: "POST",
        url: "ajax/empresas/add.php",
        data: $("#empresaForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);

            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'proveedor guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaProvvedores").DataTable().ajax.reload();
                $("#empresaForm")[0].reset();
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
});


$("#tablaProvvedores").on("click", ".btn-seleccionar-proveedor", function() {
    //bloquear todos los elementos del formulario
    $('#empresaForm input').prop('disabled', true);
    $('#empresaForm input').prop('disabled', false);
    $("#empresaForm #panel-agragar-sucursal").hide();
    $("#empresaForm #panel-password").hide();
    $("#empresaForm #btn-guardar-proveedor").hide();
    $("#empresaForm #btn-editar-proveedor").show();
    var id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: "ajax/empresas/list.php?param=" + id,
        success: function(response) {
            var proveedor = response[0];
            console.log(proveedor);
            $.ajax({
                type: "POST",
                url: "ajax/tipos_iva_empresa/list.php",
                data: { proveedor: proveedor },
                success: function(response) {
                    var tipos_iva = response;
                    let select = document.getElementById('tipo_iva_id');
                    //vaciar el select
                    select.innerHTML = "";
                    //agregar la opcion proveedor.tipo_iva y proveedor.tipo_iva_nombre
                    let option = document.createElement('option');
                    option.value = proveedor.tipo_iva;
                    option.text = proveedor.tipo_iva_nombre;
                    select.appendChild(option);
                    tipos_iva.forEach(tipo_iva => {
                        let option = document.createElement('option');
                        option.value = tipo_iva.id;
                        option.text = tipo_iva.nombre;

                        select.appendChild(option);
                    });


                },
                //desbloquer el formulario cuando termina de cargar
                complete: function() {
                    //bloquear todos los elementos del formulario
                    $('#empresaForm input').prop('disabled', false);
                }




            });

            $("#empresaForm #id").val(proveedor.id);
            $("#empresaForm #nombre_empresa").val(proveedor.nombre);
            $("#empresaForm #email_empresa").val(proveedor.email);
            $("#empresaForm #cuit").val(proveedor.cuit);
            $("#empresaForm #telefono_empresa").val(proveedor.telefono);
            $("#empresaForm #fecha_inicio_actividades").val(proveedor.fecha_inicio_actividades);
            $("#empresaForm #iibb").val(proveedor.iibb);
            $("#empresaForm #razon_social").val(proveedor.razon_social);
            $("#empresaForm #direccion_empresa").val(proveedor.direccion);
            $("#empresaForm #descripcion_empresa").val(proveedor.descripcion);
            $("#empresaForm #responsable_empresa").val(proveedor.responsable);


        },
    });



});


$("#btn-editar-proveedor").on("click", function() {
    //validar campos

    let camposVacios = $('#empresaForm #nombre_empresa, #empresaForm #distribuidor_id, #empresaForm #razon_social, #empresaForm #email_empresa, #empresaForm #direccion_empresa, #empresaForm #telefono_empresa, #empresaForm #descripcion_empresa, #empresaForm #responsable_empresa, #empresaForm #fecha_inicio_actividades, #empresaForm #cuit, #empresaForm #iibb, #empresaForm #tipo_iva_id').filter(function() {
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
    $.ajax({
        type: "POST",
        url: "ajax/empresas/edit.php",
        data: $("#empresaForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'proveedor guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaProvvedores").DataTable().ajax.reload();
                $("#empresaForm")[0].reset();
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
});

$("#tablaProvvedores").on("click", ".btn-editar-proveedor-certificado", function() {
    var empresa_id = $(this).data('id');
    $("#empresaCertificadoForm #empresa_id").val(empresa_id);
});
$("#btn-guardar-certificado-proveedor").on("click", function() {

    let camposVacios = $('#empresaCertificadoForm #certificado_afip, #empresaCertificadoForm #clave_privada_afip').filter(function() {
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
    // validar si certificado_afip tiene una extecion crt y clave_privada_afip tiene una extencion key
    var certificado_afip = document.getElementById('certificado_afip').files[0];
    var clave_privada_afip = document.getElementById('clave_privada_afip').files[0];

    if (certificado_afip.name.split('.').pop() != 'crt' || clave_privada_afip.name.split('.').pop() != 'key') {
        new PNotify({
            title: 'Error',
            text: 'Por favor, seleccione un certificado y una clave privada con extensiones correctas.',
            type: 'error',
            styling: 'bootstrap3'
        });
        return;
    }

    let clave_afip = '';
    let cert_afip = '';
    //obtener contenido de los archivos
    var reader1 = new FileReader();
    reader1.readAsText(certificado_afip);
    reader1.onload = function() {
        var cert_b64 = btoa(reader1.result);
        cert_afip = cert_b64;

        // Envía los datos al servidor después de leer el certificado
        enviarDatosAlServidor();
    };

    var reader = new FileReader();
    reader.readAsText(clave_privada_afip);
    reader.onload = function() {
        var clave_b64 = btoa(reader.result);
        clave_afip = clave_b64;

        // Envía los datos al servidor después de leer la clave privada
        enviarDatosAlServidor();
    };

    function enviarDatosAlServidor() {

        // Verifica si ambos archivos han sido leídos antes de enviar los datos al servidor
        if (cert_afip && clave_afip) {
            $.blockUI({
                message: '<h1>Guardando certificado...</h1>',
            });
            $.ajax({
                type: "POST",
                url: "ajax/empresas/editCert.php",
                data: {
                    empresa_id: $("#empresaCertificadoForm #empresa_id").val(),
                    cert: cert_afip,
                    key: clave_afip
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status == "201") {
                        new PNotify({
                            title: 'Exito',
                            text: 'Certificado guardado correctamente',
                            type: 'success',
                            styling: 'bootstrap3'
                        });
                        $("#tablaProvvedores").DataTable().ajax.reload();
                        $("#empresaCertificadoForm")[0].reset();
                        //cerrar modal
                        $('#modal-certificado-proveedor').modal('hide');
                    } else {
                        new PNotify({
                            title: 'Error',
                            text: 'Error al guardar el certificado',
                            type: 'error',
                            styling: 'bootstrap3'
                        });
                    }
                    $.unblockUI();
                },
            });
        }
    }
});




$(".btn-editar-proveedor-certificado").on("click", function() {
    var empresa_id = $(this).data('id');
    $("#empresaCertificadoForm #empresa_id").val(empresa_id);
});