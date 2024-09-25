try {
    $("#tablaEmpresas").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/empresas/list_datatable.php",
            type: "POST",
        },
        columns: [

            { data: "nombre", "title": "Nombre" },
            { data: "email", "title": "Email" },
            { data: "cuit", "title": "CUIT" },
            { data: "telefono", "title": "Telefono" },
            { data: "tipo_iva", "title": "Tipo IVA" },
            { data: "inicio_actividad", "title": "Inicio Actividad" },
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
    //buscar en la tabla cuando hago clic en el boton btn-buscar-empresa
    $('#btn-buscar-empresa').on('click', function() {
        $('#tablaEmpresas').DataTable().search($('#buscar-empresa').val()).draw();
        console.log($('#buscar-empresa').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaEmpresas_filter').hide();

} catch (error) {}


$('#btn-agregar-empresa').on('click', function() {
    $('#empresaForm')[0].reset();
    $('#empresaForm #id').val('');
    $('#empresaForm #btn-guardar-empresa').show();
    $("#empresaForm #panel-agragar-sucursal").show();
    $("#empresaForm #panel-password").show();
    $('#empresaForm #btn-editar-empresa').hide();
    $("#panel-agragar-sucursal").show();
    //cargar select de tipo_iva_id
    $.ajax({
        type: "POST",
        url: "ajax/tipos_iva_empresa/list.php",
        success: function(response) {
            var tipos_iva = response;
            let select = document.getElementById('tipo_iva_id');
            //vaciar el select
            select.innerHTML = "";

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




});

$("#btn-guardar-empresa").on("click", function() {
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
        message: '<h1>Guardando empresa...</h1>',
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
                    text: 'Empresa guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaEmpresas").DataTable().ajax.reload();
                $("#empresaForm")[0].reset();
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al guardar la empresa',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },
    });
});


$("#tablaEmpresas").on("click", ".btn-seleccionar-empresa", function() {
    //bloquear todos los elementos del formulario
    $('#empresaForm input').prop('disabled', true);
    $('#empresaForm input').prop('disabled', false);
    $("#empresaForm #panel-agragar-sucursal").hide();
    $("#empresaForm #panel-password").hide();
    $("#empresaForm #btn-guardar-empresa").hide();
    $("#empresaForm #btn-editar-empresa").show();
    var id = $(this).data('id');
    $.ajax({
        type: "POST",
        url: "ajax/empresas/list.php?param=" + id,
        success: function(response) {
            var empresa = response[0];
            console.log(empresa);
            $.ajax({
                type: "POST",
                url: "ajax/tipos_iva_empresa/list.php",
                data: { empresa: empresa },
                success: function(response) {
                    var tipos_iva = response;
                    let select = document.getElementById('tipo_iva_id');
                    //vaciar el select
                    select.innerHTML = "";
                    //agregar la opcion empresa.tipo_iva y empresa.tipo_iva_nombre
                    let option = document.createElement('option');
                    option.value = empresa.tipo_iva;
                    option.text = empresa.tipo_iva_nombre;
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

            $("#empresaForm #id").val(empresa.id);
            $("#empresaForm #nombre_empresa").val(empresa.nombre);
            $("#empresaForm #email_empresa").val(empresa.email);
            $("#empresaForm #cuit").val(empresa.cuit);
            $("#empresaForm #telefono_empresa").val(empresa.telefono);
            $("#empresaForm #fecha_inicio_actividades").val(empresa.fecha_inicio_actividades);
            $("#empresaForm #iibb").val(empresa.iibb);
            $("#empresaForm #razon_social").val(empresa.razon_social);
            $("#empresaForm #direccion_empresa").val(empresa.direccion);
            $("#empresaForm #descripcion_empresa").val(empresa.descripcion);
            $("#empresaForm #responsable_empresa").val(empresa.responsable);


        },
    });



});


$("#btn-editar-empresa").on("click", function() {
    //validar campos

    let camposVacios = $('#empresaForm #nombre_empresa, #empresaForm #distribuidor_id, #empresaForm #razon_social, #empresaForm #email_empresa, #empresaForm #direccion_empresa, #empresaForm #tipo_iva_id').filter(function() {
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
        message: '<h1>Guardando empresa...</h1>',
    });
    //si no esta cargado el cuit lo pongo en 0
    if ($("#empresaForm #cuit").val() == '') {
        $("#empresaForm #cuit").val('0');
    }
    if ($("#empresaForm #iibb").val() == '') {
        $("#empresaForm #iibb").val('0');
    }

    $.ajax({
        type: "POST",
        url: "ajax/empresas/edit.php",
        data: $("#empresaForm").serialize(),
        success: function(response) {
            var data = JSON.parse(response);
            if (data.status == "201") {
                new PNotify({
                    title: 'Exito',
                    text: 'Empresa guardada correctamente',
                    type: 'success',
                    styling: 'bootstrap3'
                });
                $("#tablaEmpresas").DataTable().ajax.reload();
                $("#empresaForm")[0].reset();
                //cerras modal
                $('#modal-empresa').modal('hide');
            } else {
                new PNotify({
                    title: 'Error',
                    text: 'Error al guardar la empresa',
                    type: 'error',
                    styling: 'bootstrap3'
                });
            }
            $.unblockUI();
        },
    });
});

$("#tablaEmpresas").on("click", ".btn-editar-empresa-certificado", function() {
    var empresa_id = $(this).data('id');
    $("#empresaCertificadoForm #empresa_id").val(empresa_id);
});
$("#btn-guardar-certificado-empresa").on("click", function() {

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
                        $("#tablaEmpresas").DataTable().ajax.reload();
                        $("#empresaCertificadoForm")[0].reset();
                        //cerrar modal
                        $('#modal-certificado-empresa').modal('hide');
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




$(".btn-editar-empresa-certificado").on("click", function() {
    var empresa_id = $(this).data('id');
    $("#empresaCertificadoForm #empresa_id").val(empresa_id);
});