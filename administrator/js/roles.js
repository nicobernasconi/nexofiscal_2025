
try {
    //obtener el id de la empresa
    var empresa_id = $("#empresa_id").val();

    $("#tablaRoles").DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        ajax: {
            url: "ajax/roles/list_datatable.php?id=" + empresa_id,
            type: "POST",
        },
        columns: [{
            //checkbox id="check-all" class="flat"

            data: null,
            render: function (data, type, row) {
                return `<input type="checkbox" name="chkEmpresa" id="check-all" class="flat" value="${row.id}">`;
            }
        },

        { data: "nombre" },
        { data: "descripcion" },
        { data: "acciones" }],
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
    $('#btn-buscar-rol').on('click', function () {
        $('#tablaRoles').DataTable().search($('#buscar-rol').val()).draw();
        console.log($('#buscar-rol').val());
    });

    //ocultar tablaEmpresas_filter
    $('#tablaRoles_filter').hide();
}catch (error) {
    console.error(error);
}