$.ajax({
    type: "GET",
    url: "ajax/informes_resumen_general/list.php",

    success: function(response) {
        var data = response;
        $("#count_empresas_dashboard").html(data.count_empresas);
        $("#count_sucursales_dashboard").html(data.count_sucursales);
        $("#count_ventas_dashboard").html('$' + data.total_ventas);
        $("#count_devoluciones_dashboard").html('$' + data.total_devoluciones);
        $("#count_pedidos_dashboard").html('$' + data.total_pedidos);
    },
});


$.ajax({
    type: "GET",
    url: "ajax/informes_ranking_productos_ventas/list.php",

    success: function(response) {
        var data = response['data'];
        if (data.length < 4) {
            for (var i = data.length; i < 4; i++) {
                data.push({ productos: 'Sin datos', porcentaje: 0 });
            }
        }
        $("#ranking_producto_1").html(data[0].productos);
        $("#ranking_producto_2").html(data[1].productos);
        $("#ranking_producto_3").html(data[2].productos);
        $("#ranking_producto_4").html(data[3].productos);
        $("#ranking_producto_progressbar_1").css("width", data[0].porcentaje + '%');
        $("#ranking_producto_progressbar_2").css("width", data[1].porcentaje + '%');
        $("#ranking_producto_progressbar_3").css("width", data[2].porcentaje + '%');
        $("#ranking_producto_progressbar_4").css("width", data[3].porcentaje + '%');
    },
});