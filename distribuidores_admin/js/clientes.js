try {
    $("#tablaProvvedores").DataTable({
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