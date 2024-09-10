$(document).ready(function() {

    function seleccionarPorDescripcion(control, descripcion) {
        $.ajax({
            url: "ajax/tipos_iva/list_select.php",
            dataType: 'json',
            data: {
                q: descripcion
            },
            success: function(data) {
                //GET FIRST ITEM
                var item = data[0];
                if (item) {
                    var newOption = new Option(item.text, item.id, true, true);
                    $("#modificar-cliente-modal " + control).append(newOption).trigger('change');
                }
            }
        });
    }

    window.closeModal = function() {
        $(".custom-modal").fadeOut();
    };

    var clienteSeleccionado = {
        nombre: "",
        cuit: "",
        domicilio: "",
    };
    var selectedVendedor = $("#vendedor_id").val();
    // Array para almacenar los productos agregados
    var products = [];
    products[selectedVendedor] = [];
    var selectedClient = null;

    var promotions = [];
    promotions[selectedVendedor] = [];
    var z_index = 1;
    var codigo_barra_inicio = $("#codigo_barra_inicio").val();
    var codigo_barra_id_long = $("#codigo_barra_id_long").val();
    var codigo_barra_payload_type = $("#codigo_barra_payload_type").val();
    var codigo_barra_payload_int = $("#codigo_barra_payload_int").val();
    var codigo_barra_long = $("#codigo_barra_long").val();
    var tipo_iva = $("#tipo_iva").val();
    var venta_rapida = $("#venta_rapida").val();
    var imprimir = $("#imprimir").val();
    if (tipo_iva == 1) {
        var tipo_de_factura = 6;
    } else {
        var tipo_de_factura = 11;
    }
    var tipo_de_documento = 99;
    var numero_de_documento = 0;

    var destinatario = "CONSUMIDOR FINAL";
    //anulo el boton cobrar si tipo_iva=4 -> no fiscal



    // Obtener los elementos de los campos de entrada
    const costoInput = $("#precio_costo");
    const margenInput = $("#margen_ganancia");
    const precio1Input = $("#precio1");

    const costoEditInput = $("#editar-producto-form  #precio_costo");
    const margenEditInput = $("#editar-producto-form #margen_ganancia");
    const precio1EditInput = $("#editar-producto-form #precio1");

    // Agregar un evento de cambio a los campos de entrada
    costoEditInput.on("input", calcularPrecio1);
    margenEditInput.on("input", calcularPrecio1);

    // Agregar un evento de cambio a los campos de entrada
    costoInput.on("input", calcularPrecio1);
    margenInput.on("input", calcularPrecio1);

    // Función para calcular el precio 1
    function calcularPrecio1() {
        // Obtener los valores de los campos de entrada
        const costo = parseFloat(costoInput.val());
        const margen = parseFloat(margenInput.val());

        // Calcular el precio 1
        const precio1 = costo * (1 + margen / 100);

        // Mostrar el precio 1 en el campo de entrada correspondiente
        precio1Input.val(precio1.toFixed(2));

        // Obtener los valores de los campos de entrada
        const costoEdit = parseFloat(costoEditInput.val());
        const margenEdit = parseFloat(margenEditInput.val());

        // Calcular el precio 1
        const precio1Edit = costoEdit * (1 + margenEdit / 100);

        // Mostrar el precio 1 en el campo de entrada correspondiente
        precio1EditInput.val(precio1Edit.toFixed(2));
    }

    // Función para calcular el margen de ganancia
    function calcularMargenGanancia() {
        // Obtener los valores de los campos de entrada
        const costo = parseFloat(costoInput.val());
        const precio1 = parseFloat(precio1Input.val());

        // Calcular el margen de ganancia
        const margen = ((precio1 - costo) / costo) * 100;

        // Mostrar el margen de ganancia en el campo de entrada correspondiente
        margenInput.val(margen.toFixed(2));

        // Obtener los valores de los campos de entrada
        const costoEdit = parseFloat(costoEditInput.val());
        const precio1Edit = parseFloat(precio1EditInput.val());

        // Calcular el margen de ganancia
        const margenEdit = ((precio1Edit - costoEdit) / costoEdit) * 100;

        // Mostrar el margen de ganancia en el campo de entrada correspondiente
        margenEditInput.val(margenEdit.toFixed(2));
    }

    // Función para obtener el listado de artículos en formato de texto
    function obtenerListadoArticulos() {
        var listado = "";
        products[selectedVendedor].forEach(function(product) {
            listado +=
                product.name +
                " : $" +
                (product.price * product.quantity).toFixed(2) +
                "\n";
        });
        return listado;
    }

    function validarFormularioProducto() {
        var isValid = true;
        $("#editar-producto-modal input[required]").each(function() {
            if ($.trim($(this).val()) == "") {
                isValid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });
        return isValid;
    }

    function validarFormularioEditarProducto() {
        var isValid = true;
        $("#editar-producto-modal input[required]").each(function() {
            if ($.trim($(this).val()) == "") {
                isValid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });
        return isValid;
    }

    function validarFormularioCliente() {
        var isValid = true;
        $("#modificar-cliente-modal input[required]").each(function() {
            if ($.trim($(this).val()) == "") {
                isValid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });
        return isValid;
    }
    // Agregar un evento de cambio a los campos de entrada
    costoInput.on("input", calcularMargenGanancia);
    precio1Input.on("input", calcularMargenGanancia);

    function limpiarFormularioClientes() {
        // Limpiar todos los campos de texto y select
        $('#modificar-cliente-modal input[type="text"]').val("");
        $('#modificar-cliente-modal input[type="number"]').val(0);
        $("#modificar-cliente-modal select").val(null).trigger("change");
    }

    function mostrarNotificacion(titulo, tipo, mensaje, boton_id, id, reiniciar) {
        // Limpiar el contenido del modal
        $("#mensajeModal").empty();
        // Agregar el icono según el tipo de notificación
        var icono = "";
        if (tipo === "error") {
            icono = '<i class="fa-solid fa-exclamation-triangle" style="color: red;"></i>';
        } else if (tipo === "advertencia") {
            icono = '<i class="fa-solid fa-exclamation-circle" style="color: orange;"></i>';
        } else if (tipo === "exito") {
            icono = '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
        } else if (tipo === "eliminar" || tipo === "anular") { // Si el tipo es "eliminar", agregar un botón de borrado
            icono = '<i class="fa-solid fa-trash" style="color: red;"></i>';
        } else {
            icono = '<i class="fa-solid fa-info-circle" style="color: blue;"></i>';
        }

        // Construir el contenido del modal
        var contenido = "<h3>" + icono + " " + titulo + "</h3>";
        contenido += "<p>" + mensaje + "</p>";

        // Agregar el contenido al modal
        $("#mensajeModal").html(contenido);

        // Si se proporciona un id de botón, agregar un botón adicional al contenido del modal
        if (tipo === "eliminar") {
            //agergar el boton al final de custom-modal-content-s
            $("#mensaje-modal .custom-modal-close-btn").hide();
            $("#botonesMensaje").empty();
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-eliminar" class="btn-cancelar " data-id="' + id + '">Eliminar</button>');
            //boton volver atras
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-volver" class="btn-cerrar">Volver</button>');
            //agregar evento click al boton cerrar
            $("#btn-" + boton_id + "-volver").click(function() {
                $("#mensaje-modal").fadeOut();
            });
            $("#btn-" + boton_id + '-eliminar').click(function() {
                var id = $(this).data("id");
                $.unblockUI("Eliminando...");
                $.ajax({
                    url: "./ajax/" + boton_id + "/delete.php",
                    method: "POST",
                    data: { id: id },
                    success: function(response) {
                        $("#mensaje-modal").fadeOut();
                        $.unblockUI();
                        var data = JSON.parse(response);
                        if (data.status == "201") {
                            mostrarNotificacion("Eliminado", "exito", "El registro ha sido eliminado correctamente", "", "");
                        } else {
                            mostrarNotificacion("Error", "error", "El registro no pudo ser eliminado: <br>" + data.status_message, "", "");
                        }
                    },
                    error: function(xhr, status, error) {

                        $.unblockUI();
                    },
                });



            });

        } else if (tipo === "anular") {
            //agergar el boton al final de custom-modal-content-s
            $("#mensaje-modal .custom-modal-close-btn").hide();
            $("#botonesMensaje").empty();
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-anular" class="btn-cancelar " data-id="' + id + '">Anular</button>');
            //boton volver atras
            $("#botonesMensaje").append('<button id="btn-' + boton_id + '-volver" class="btn-cerrar">Volver</button>');
            //agregar evento click al boton cerrar
            $("#btn-" + boton_id + "-volver").click(function() {
                $("#mensaje-modal").fadeOut();
            });

            $("#btn-" + boton_id + '-anular').click(function() {
                var id = $(this).data("id");
                $.unblockUI("Anulando...");
                $.ajax({
                    url: "./ajax/" + boton_id + "/anular.php",
                    method: "POST",
                    data: { id: id },
                    success: function(response) {
                        $("#mensaje-modal").fadeOut();
                        $.unblockUI();
                        var data = JSON.parse(response);
                        if (data.status == "201") {
                            mostrarNotificacion("Anulando", "exito", "El registro ha sido anulado correctamente", "", "");
                        } else {
                            mostrarNotificacion("Error", "error", "El registro no pudo ser anulado: <br>" + data.status_message, "", "");
                        }
                    },
                    error: function(xhr, status, error) {

                        $.unblockUI();
                    },
                });



            });

        } else {
            $("#botonesMensaje").empty();
            $("#mensaje-modal .custom-modal-close-btn").show();
        }
        // Mostrar el modal
        $("#mensaje-modal").fadeIn().css("z-index", z_index++);
        //asignar z-index
        $("#mensaje-modal").css("z-index", z_index++);
    }

    function calculateSubtotal() {
        var subtotal = 0;
        var summaryHtml = "";
        products[selectedVendedor].forEach(function(product) {
            var productTotal = product.price * product.quantity;
            subtotal += productTotal;
            summaryHtml += product.name + ": $" + productTotal.toFixed(2) + "<br>";
        });

        // Aplicar promociones
        promotions[selectedVendedor].forEach(function(promotion) {
            var discountAmount = subtotal * (promotion.discount / 100);
            subtotal -= discountAmount;
            if (subtotal < 0) {
                subtotal = 0;
            }
        });

        var total = subtotal; // En este ejemplo, el total es igual al subtotal
        $("#total-price").html("$" + subtotal.toFixed(2));
        $("#montoPagar").text("$" + subtotal.toFixed(2));
    }

    //funcion que carga el producto buscado en el Modal para editarlo
    function mostrarDatosProducto(data) {
        // Parsear la respuesta JSON
        var producto = data[0]; // Suponiendo que solo se devuelve un producto
        // Llenar el formulario con los datos del producto
        $("#editar-producto-form #id").val(producto.id);
        $("#editar-producto-form #codigo").val(producto.codigo);
        $("#editar-producto-form #activo").prop(
            "checked",
            producto.articulo_activado == "1"
        );
        $("#editar-producto-form #favorito").prop(
            "checked",
            producto.favorito == "1"
        );

        $("#editar-producto-form #fraccionado").prop(
            "checked",
            producto.fraccionado == "1"
        );
        $("#editar-producto-form #publicado_web").prop(
            "checked",
            producto.publicado_web == "1"
        );
        $("#editar-producto-form #oferta").prop("checked", producto.oferta == "1");
        $("#editar-producto-form #rg5329_23").prop(
            "checked",
            producto.rg5329_23 == "1"
        );
        $("#editar-producto-form #producto_balanza").prop(
            "checked",
            producto.producto_balanza == "1"
        );
        $("#editar-producto-form #stock").val(producto.stock);
        $("#editar-producto-form #descripcion").val(producto.descripcion);
        $("#editar-producto-form #unidad_id").val(producto.unidad_id); // Si es un select, asegúrate de que esta opción exista en el select
        $("#editar-producto-form #codigo_barra").val(producto.codigo_barra);
        $("#editar-producto-form #codigo_barra2").val(producto.codigo_barra2);
        $("#editar-producto-form #texto_panel").val(producto.texto_panel);
        $("#editar-producto-form #iibb").val(producto.iibb);
        //calculo precio costo en base al margen de ganancia, si no esta establecido alguno delos parametros los calcula en base a lo cargado
        var margen_ganancia = 0;
        var precio_costo = producto.precio1;
        if (producto.margen_ganancia != 0) {
            margen_ganancia = producto.margen_ganancia;
        }
        if (producto.precio_costo != 0) {
            precio_costo = producto.precio_costo;
        }
        $("#editar-producto-form #precio_costo").val(precio_costo);
        $("#editar-producto-form #margen_ganancia").val(margen_ganancia);
        $("#editar-producto-form #precio1").val(producto.precio1);
        $("#editar-producto-form #precio2").val(producto.precio2);
        $("#editar-producto-form #precio3").val(producto.precio3);
        $("#editar-producto-form #impuesto_interno").val(producto.impuesto_interno);


        if (producto.tipo_impuesto_interno != null) {

            $("#editar-producto-form #tipo_impuesto_interno").push(
                '<option value="' +
                producto.tipo_impuesto_interno +
                '">' +
                (producto.tipo_impuesto_interno == 1 ? "PORCENTAJE" : "FIJO") +
                +
                "</option>"
            );
        }
        // Llenar los campos de select
        // Por ejemplo, si "tipo" es un objeto con propiedades "id" y "nombre":
        if (producto.tipo.id != null) {
            $("#editar-producto-form #tipo_id").html(
                '<option value="' +
                producto.tipo.id +
                '">' +
                producto.tipo.nombre +
                "</option>"
            );
        }
        if (producto.tasa_iva.id != null) {
            $("#editar-producto-form #tasa_iva_id").html(
                '<option value="' +
                producto.tasa_iva.id +
                '">' +
                producto.tasa_iva.nombre +
                "</option>"
            );
        }

        // Si "familia" es un objeto con propiedades "id" y "nombre":
        if (producto.familia.id != null) {
            $("#editar-producto-form #familia_id").html(
                '<option value="' +
                producto.familia.id +
                '">' +
                producto.familia.nombre +
                "</option>"
            );
        }

        // Si "proveedor" es un objeto con propiedades "id" y "razon_social":
        if (producto.proveedor.id != null) {
            $("#editar-producto-form #proveedor_id").html(
                '<option value="' +
                producto.proveedor.id +
                '">' +
                producto.proveedor.razon_social +
                "</option>"
            );
        }

        // Si "agrupacion" es un objeto con propiedades "id" y "nombre":
        if (producto.agrupacion.id != null) {
            $("#editar-producto-form #agrupacion_id").html(
                '<option value="' +
                producto.agrupacion.id +
                '">' +
                producto.agrupacion.nombre +
                "</option>"
            );
        }
        // Si "moneda" es un objeto con propiedades "id" y "simbolo":
        // Asegúrate de que exista la opción correspondiente en el select
        if (producto.moneda.id != null) {
            $("#editar-producto-form #moneda_id").html(
                '<option value="' +
                producto.moneda.id +
                '">' +
                producto.moneda.simbolo +
                "</option>"
            );
        }

        // Si "moneda" es un objeto con propiedades "id" y "simbolo":
        // Asegúrate de que exista la opción correspondiente en el select
        if (producto.moneda.id != null) {
            $("#editar-producto-form #unidad_id").html(
                '<option value="' +
                producto.unidad.id +
                '">' +
                producto.unidad.nombre +
                "</option>"
            );
        }

        $("#editar-producto-form #tipo_id").select2({
            language: "es",
            placeholder: "Seleccione un tipo",
            allowClear: true,
            //minimumInputLength: 1,
            ajax: {
                url: "ajax/tipos/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
        $("#editar-producto-form #tasa_iva_id").select2({
            language: "es",
            placeholder: "Seleccione un tipo",
            allowClear: true,
            //minimumInputLength: 1,
            ajax: {
                url: "ajax/tasa_iva/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
        //select Unidad de producto
        $("#editar-producto-form #unidad_id").select2({
            language: "es",
            placeholder: "Seleccione un tipo",
            allowClear: true,
            //minimumInputLength: 1,
            ajax: {
                url: "ajax/unidades/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
        //select Agrupacion de producto
        $("#editar-producto-form #agrupacion_id").select2({
            language: "es",
            placeholder: "Seleccione una agrupacion",
            allowClear: true,
            //minimumInputLength: 1,
            ajax: {
                url: "ajax/agrupaciones/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
        //select Familia de producto
        $("#editar-producto-form #familia_id").select2({
            language: "es",
            placeholder: "Seleccione una familia",
            allowClear: true,
            //minimumInputLength: 1,
            ajax: {
                url: "ajax/familias/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
        //select Proveedor de producto
        $("#editar-producto-form #proveedor_id").select2({
            language: "es",
            placeholder: "Seleccione un proveedor",
            allowClear: true,
            minimumInputLength: 3,
            ajax: {
                url: "ajax/proveedores/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });

    }

    window.consultarProducto = function(id) {
        // Realizar la consulta AJAX
        $.blockUI({ message: "<h1>Recuperando datos del Producto...</h1>" });
        $.ajax({
            url: "./ajax/productos/list.php",
            method: "GET",
            data: { param: id },
            success: function(response) {
                // Manejar la respuesta aquí
                mostrarDatosProducto(response);
                $("#custom-search-productos-editar-input").val("");
                $("#btn-guardar-producto").hide();
                $("#btn-editar-producto").show();
                $("#editar-producto-modal").fadeIn().css("z-index", z_index++);;

                $.unblockUI();
            },
            error: function(xhr, status, error) {
                // Manejar errores aquí
                $.unblockUI();
                console.error(xhr, status, error);
            },
        });
    };

    // Función para actualizar la tabla de productos
    function updateProductList() {
        var productListHtml = "";
        //compruebo products[selectedVendedor] existe
        if (products[selectedVendedor] == undefined) {
            products[selectedVendedor] = [];
        }
        products[selectedVendedor].forEach(function(product, index) {
            productListHtml += "<tr>";
            productListHtml +=
                '<td><input type="text" class="form-control" id="name-' +
                index +
                '" value="' +
                product.name + '" onchange="updateName(' +
                index +
                ')"></td>';
            // Mostrar un cuadro de texto para ingresar el precio solo si el precio es cero
            if (true) {
                productListHtml +=
                    '<td><input type="number" class="form-control" id="price-' +
                    index +
                    '" value="' +
                    product.price.toFixed(2) +
                    '" min="0.00" step="0.01" onchange="updatePrice(' +
                    index +
                    ')"></td>';
            } else {
                productListHtml += "<td>$" + product.price.toFixed(2) + "</td>";
            }
            // Añadir un campo de entrada para la cantidad con un ID único para cada producto
            productListHtml +=
                '<td><input type="number" class="form-control" id="quantity-' +
                index +
                '" value="' +
                product.quantity +
                '" min="1" onchange="updateQuantity(' +
                index +
                ')"></td>';
            productListHtml +=
                '<td id="total-' +
                index +
                '">$' +
                (product.price * product.quantity).toFixed(2) +
                "</td>";
            productListHtml +=
                '<td><button type="button" class="btn btn-danger" onclick="removeProduct(' +
                index +
                ')"><i class="fas fa-trash"></i></button></td>';
            productListHtml += "</tr>";
        });

        // Añadir una fila para el resumen de la venta
        productListHtml += "<tr>";
        productListHtml +=
            '<td colspan="3" class="text-end"><strong></strong></td>';
        productListHtml += "<td></td>";
        productListHtml += "<td></td>";
        productListHtml += "</tr>";

        $("#product-list").html(productListHtml);
    }

    // Función para eliminar un producto de la lista
    window.removeProduct = function(index) {
        products[selectedVendedor].splice(index, 1);
        updateProductList();
        calculateSubtotal();
    };

    // Función para actualizar la cantidad de un producto
    window.updateQuantity = function(index) {
        var quantity = parseFloat($("#quantity-" + index).val());
        products[selectedVendedor][index].quantity = quantity;
        // Actualizar el total del producto
        $("#total-" + index).text(
            "$" + (products[selectedVendedor][index].price * quantity).toFixed(2)
        );
        calculateSubtotal();
    };

    // Función para actualizar el precio de un producto
    window.updatePrice = function(index) {
        var price = parseFloat($("#price-" + index).val());
        products[selectedVendedor][index].price = price;
        // Actualizar el total del producto
        $("#total-" + index).text(
            "$" + (price * products[selectedVendedor][index].quantity).toFixed(2)
        );
        calculateSubtotal();
    };
    // Función para actualizar el precio de un producto
    window.updateName = function(index) {
        var name = $("#name-" + index).val();
        products[selectedVendedor][index].name = name;
        // Actualizar el total del producto
    };
    // Función para agregar un producto favorito
    window.addFavoriteProduct = function(
        id,
        name,
        price,
        impuesto_interno,
        tasa_iva,
        cantidad,
        producto_balanza
    ) {
        // Verificar si el producto ya está agregado
        var existingProductIndex = products[selectedVendedor].findIndex(function(product) {
            return product.id === id;
        });

        if (existingProductIndex !== -1 && !parseInt(producto_balanza)) {
            products[selectedVendedor][existingProductIndex].quantity++;
        } else {
            // Si el producto no está agregado, agregarlo al array

            products[selectedVendedor].push({
                id: id,
                name: name,
                price: price,
                impuesto_interno: impuesto_interno,
                tasa_iva: tasa_iva,
                quantity: cantidad,
            });
        }

        // Actualizar la tabla de productos
        updateProductList();

        // Calcular y mostrar el subtotal
        calculateSubtotal();
    };

    window.addPromotion = function(id, name, discount) {
        // Verificar si la promoción ya está agregada
        var existingPromotionIndex = promotions[selectedVendedor].findIndex(function(promotion) {
            return promotion.id === id;
        });

        if (existingPromotionIndex !== -1) {
            // Si la promoción ya está agregada, actualizar el descuento
            promotions[selectedVendedor][existingPromotionIndex].discount = discount;
        } else {
            // Si la promoción no está agregada, agregarla al array
            promotions[selectedVendedor].push({
                id: id,
                name: name,
                discount: discount,
            });
        }

        // Actualizar la lista de promociones
        updatePromotionList();

        // Calcular y mostrar el nuevo subtotal con las promociones aplicadas
        calculateSubtotalWithpromotions();
        calculateSubtotal();
    };

    window.updatePromotionList = function() {
        // Obtener el elemento del DOM donde se mostrarán las promociones
        var promotionListElement = document.getElementById("promociones-list");

        // Crear el HTML para las promociones y un boton para eliminarlos
        //compruebo products[selectedVendedor] existe
        if (promotions[selectedVendedor] == undefined) {
            promotions[selectedVendedor] = [];
        }

        var promotionListHTML = promotions[selectedVendedor]
            .map(function(promotion) {
                return (
                    "<tr>" +
                    "<td>" +
                    promotion.name +
                    "</td>" +
                    "<td> <button type='button' class='btn btn-danger' onclick='removePromotion(" +
                    promotions[selectedVendedor].id +
                    ")'><i class='fas fa-trash'></i></button></td>" +
                    "</tr>"
                );
            })
            .join("");

        // Actualizar el contenido HTML del elemento de la lista de promociones
        promotionListElement.innerHTML = promotionListHTML;
        calculateSubtotal();
    };

    window.calculateSubtotalWithpromotions = function() {
        var subtotal = 0;

        // Calcular el subtotal para cada producto en el carrito
        for (var i = 0; i < products[selectedVendedor].length; i++) {
            var product = products[selectedVendedor][i];
            var discount = 0;

            // Aplicar el descuento de cada promoción al producto
            for (var j = 0; j < promotions[selectedVendedor].length; j++) {
                var promotion = promotions[selectedVendedor][j];
                if (product.id === promotion.id) {
                    discount += product.price * (promotion.porcentaje / 100);
                }
            }

            // Agregar el precio del producto (menos el descuento) al subtotal
            subtotal += product.price - discount;
        }

        return subtotal;
    };

    window.removePromotion = function(index) {
        promotions[selectedVendedor].splice(index, 1);
        updatePromotionList();
        calculateSubtotal();
    };



    function clearProductList() {
        $("#clientedc").text("1 / Ocacional");
        $("#cuitdc").text("CUIT: / Ocacional");
        $("#domiciliodc").text("Domicilio: / Ocacional");

        // Limpiar los inputs
        $("input[type='text']").val("");
        $("input[type='number']").val("");
        $("input[type='checkbox']").prop("checked", false);
        $("#vuelto").html("");

        products[selectedVendedor] = []; // Vaciar el array de productos
        promotions[selectedVendedor] = []; // Vaciar el array de promociones
        updateProductList(); // Actualizar la tabla de productos
        updatePromotionList(); // Actualizar la lista de promociones
        calculateSubtotal(); // Recalcular el subtotal

        // Mostrar el mensaje de forma de pago
    }

    // Función para limpiar la lista de productos
    $("#btnlimpiar").click(function() {
        clearProductList(); // Llama a la función para limpiar la lista de productos
    });

    $(".table").DataTable({
        //la tabla debe ocupar el 70% de la pantalla
        ordering: false, // Desactiva la ordenación de las columnas
        searching: false, // Desactiva la opción de búsqueda
        paging: false, // Desactiva la paginación
        scrollY: "40vh", // Altura del área de desplazamiento al 30% de la altura de la ventana
        scrollCollapse: true, // Permite que la tabla se contraiga si el contenido es más pequeño que el área de desplazamiento
        info: false,
        // Oculta la información de la tabla (por ejemplo, "Mostrando 1 a 10 de X entradas")
        language: {
            search: "", // Elimina el texto de búsqueda predeterminado
            searchPlaceholder: "Buscar...", // Placeholder para el nuevo cuadro de búsqueda
            sProcessing: "Procesando...",
            sLengthMenu: "Mostrar _MENU_ registros",
            sZeroRecords: "",
            sEmptyTable: "",
            sInfo: "",
        },
    });


    //modal buscar cliente

    $("#buscarCliente,#btn-menu-clientes-listar, #btn-menu-clientes-eliminar,#btn-menu-clientes-editar").click(function() {
        //obtener el id del boton que se hizo clic
        var btn = $(this);

        if ($.fn.DataTable.isDataTable("#tablaClientes")) {
            $("#tablaClientes").DataTable().destroy();
        }
        $("#lista-clientes-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaClientes").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/clientes/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {
                            $('#tablaClientes').closest('.dataTables_wrapper').find('.alert').remove();
                            //ocultar el mendaje de cargando de la tabla
                            $("#tablaClientes_processing").hide();
                            $('#tablaClientes').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [
                    { data: "nro_cliente" },
                    { data: "nombre" },
                    { data: "direccion_comercial" },
                    { data: "localidad_nombre" },
                    { data: "cuit" },
                    { data: "tipo_iva_nombre" },
                    {
                        // Agregar una columna para el botón
                        data: null,
                        render: function(data, type, row, meta) {

                            if (btn.attr('id') == 'btn-menu-clientes-listar' || btn.attr('id') == 'buscarCliente') {
                                return '<button class="btn-guardar btn-selecciona-cliente"><i class="fas fa-plus"></i></button>';
                            } else if (btn.attr('id') == 'btn-menu-clientes-eliminar') {
                                return '<button class="btn-cancelar btn-eliminar-cliente" data-id="' + row.id + '"><i class="fas fa-trash"></i></button>';
                            } else {
                                return '<button class="btn-guardar btn-editar-cliente" data-id="' + row.id + '"><i class="fas fa-pencil"></i></button>';
                            }
                        },
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar cliente...", // Placeholder para el nuevo cuadro de búsqueda
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
                //no ordenar por la columna 3 y 5
                columnDefs: [{
                    orderable: false,
                    targets: [3, 5],
                }, ],
                lengthChange: false,
                //searching: false,
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-clientes-input").on("change", function() {
                $("#tablaClientes").DataTable().search($(this).val()).draw();
            });
            $("#tablaClientes_filter").hide();

            //editar cliente
            $(document).on("click", ".btn-editar-cliente", function() {
                $("#btn-editar-cliente").show();
                $("#btn-guardar-cliente").hide();
                var id = $(this).attr("data-id");
                $.blockUI({ message: "<h1>Recuperando datos del Cliente...</h1>" });
                // Realizar la solicitud AJAX
                $.ajax({
                    url: "./ajax/clientes/list.php",
                    method: "GET",
                    data: { param: id },
                    success: function(response) {
                        var cliente = response[0];

                        $("#modificar-cliente-modal #tipo_documento_id").select2({
                            language: "es",
                            placeholder: "Seleccione un tipo de documento",
                            allowClear: true,
                            //minimumInputLength: 3,
                            ajax: {
                                url: "ajax/tipos_documento/list_select.php",
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: data,
                                    };
                                },
                                cache: false,
                            },
                        });
                        //select Localidad
                        $("#modificar-cliente-modal #localidad_id").select2({
                            language: "es",
                            placeholder: "Seleccione una localidad",
                            allowClear: true,
                            minimumInputLength: 3,
                            ajax: {
                                url: "ajax/localidades/list_select.php",
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: data,
                                    };
                                },
                                cache: false,
                            },
                        });
                        //select Categoria de producto
                        $("#modificar-cliente-modal #categoria_id").select2({
                            language: "es",
                            placeholder: "Seleccione una categoria de cliente",
                            allowClear: true,
                            //minimumInputLength: 3,
                            ajax: {
                                url: "ajax/categorias/list_select.php",
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: data,
                                    };
                                },
                                cache: false,
                            },
                        });

                        $("#modificar-cliente-modal #tipo_iva_id").select2({
                            language: "es",
                            placeholder: "Seleccione un tipo de IVA",
                            allowClear: true,
                            //minimumInputLength: 3,
                            ajax: {
                                url: "ajax/tipos_iva/list_select.php",
                                dataType: "json",
                                delay: 250,
                                processResults: function(data) {
                                    return {
                                        results: data,
                                    };
                                },
                                cache: false,
                            },
                        });
                        // Obtener el primer elemento del arreglo
                        $("#modificar-cliente-modal #id").val(cliente.id);
                        $("#modificar-cliente-modal #nombre").val(cliente.nombre);
                        $("#modificar-cliente-modal #tipo_documento_id").val(cliente.tipo_documento_id).trigger("change");
                        $("#modificar-cliente-modal #numero_documento").val(cliente.numero_documento);
                        $("#modificar-cliente-modal #cuit").val(cliente.cuit);
                        $("#modificar-cliente-modal #tipo_iva_id").val(cliente.tipo_iva_id).trigger("change");
                        $("#modificar-cliente-modal #direccion_comercial").val(cliente.direccion_comercial);
                        $("#modificar-cliente-modal #direccion_entrega").val(cliente.direccion_entrega);
                        $("#modificar-cliente-modal #localidad_id").val(cliente.localidad_id).trigger("change");
                        $("#modificar-cliente-modal #telefono").val(cliente.telefono);
                        $("#modificar-cliente-modal #celular").val(cliente.celular);
                        $("#modificar-cliente-modal #email").val(cliente.email);
                        $("#modificar-cliente-modal #contacto").val(cliente.contacto);
                        $("#modificar-cliente-modal #telefono_contacto").val(cliente.telefono_contacto);
                        $("#modificar-cliente-modal #categoria_id").val(cliente.categoria_id).trigger("change");
                        $("#modificar-cliente-modal #porcentaje_descuento").val(cliente.porcentaje_descuento);
                        $("#modificar-cliente-modal #limite_credito").val(cliente.limite_credito);
                        $("#modificar-cliente-modal #saldo_inicial").val(cliente.saldo_inicial);
                        $("#modificar-cliente-modal #percepcion_iibb").val(cliente.percepcion_iibb);

                        //$("#lista-clientes-modal").fadeOut();
                        $("#modificar-cliente-modal").fadeIn().css("z-index", z_index++);
                        $.unblockUI();

                    },
                    error: function(xhr, status, error) {
                        // Manejar errores aquí
                        $.unblockUI();
                        console.error(xhr, status, error);
                    },
                });
            });

        } catch (error) {}
    });
    $("#btn-editar-cliente").on("click", function(event) {

        //prevenir el evento por defecto
        event.preventDefault();
        // Obtener los datos del formulario
        var id = $("#modificar-cliente-modal #id").val();
        var nombre = $("#modificar-cliente-modal #nombre").val();
        var tipo_documento_id = $("#modificar-cliente-modal #tipo_documento_id").val();
        var numero_documento = $("#modificar-cliente-modal #numero_documento").val();
        var cuit = $("#modificar-cliente-modal #cuit").val();
        var tipo_iva_id = $("#modificar-cliente-modal #tipo_iva_id").val();
        var direccion_comercial = $("#modificar-cliente-modal #direccion_comercial").val();
        var direccion_entrega = $("#modificar-cliente-modal #direccion_entrega").val();
        var localidad_id = $("#modificar-cliente-modal #localidad_id").val();
        var telefono = $("#modificar-cliente-modal #telefono").val();
        var celular = $("#modificar-cliente-modal #celular").val();
        var email = $("#modificar-cliente-modal #email").val();
        var contacto = $("#modificar-cliente-modal #contacto").val();
        var telefono_contacto = $("#modificar-cliente-modal #telefono_contacto").val();
        var categoria_id = $("#modificar-cliente-modal #categoria_id").val();
        var porcentaje_descuento = $("#modificar-cliente-modal #porcentaje_descuento").val();
        var limite_credito = $("#modificar-cliente-modal #limite_credito").val();
        var saldo_inicial = $("#modificar-cliente-modal #saldo_inicial").val();
        var percepcion_iibb = $("#modificar-cliente-modal #percepcion_iibb").val();
        $.blockUI({ message: "<h1>Actualizando datos del Cliente...</h1>" });
        // Realizar la solicitud AJAX
        $.ajax({
            url: "./ajax/clientes/edit.php",
            method: "POST",
            data: {
                id: id,
                nombre: nombre,
                tipo_documento_id: tipo_documento_id,
                numero_documento: numero_documento,
                cuit: cuit,
                tipo_iva_id: tipo_iva_id,
                direccion_comercial: direccion_comercial,
                direccion_entrega: direccion_entrega,
                localidad_id: localidad_id,
                telefono: telefono,
                celular: celular,
                email: email,
                contacto: contacto,
                telefono_contacto: telefono_contacto,
                categoria_id: categoria_id,
                porcentaje_descuento: porcentaje_descuento,
                limite_credito: limite_credito,
                saldo_inicial: saldo_inicial,
                percepcion_iibb: percepcion_iibb,
            },
            success: function(response) {
                responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#modificar-cliente-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar Cliente",
                        "exito",
                        responseObject.status_message
                    );
                    $("#lista-clientes-modal").fadeIn().css("z-index", z_index++);
                    $("#tablaClientes").DataTable().ajax.reload();
                } else {
                    $("#modificar-cliente-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }

                $.unblockUI();
            },
            error: function(xhr, status, error) {
                // Manejar errores aquí
                console.error(xhr, status, error);
                $.unblockUI();
            },
        });
    });





    $(document).on("click", ".btn-selecciona-cliente", function() {
        // Obtener los datos de la fila de la tabla
        var data = $("#tablaClientes").DataTable().row($(this).closest("tr")).data();

        // Verificar si se obtuvieron datos válidos
        if (data) {
            // Asignar los datos a las variables correspondientes
            var id = data.id;
            var nro_cliente = data.nro_cliente;
            var nombre = data.nombre;
            var cuit = data.cuit;
            var tipo_iva_nombre = data.tipo_iva_nombre;
            var direccion = data.direccion_comercial;
            var localidad = data.localidad_nombre;
            destinatario = nombre;
            var tipo_iva = $("#tipo_iva").val();

            if (tipo_iva == '1') {
                if (data.tipo_iva_nombre == 'INSCRIPTO') {
                    tipo_de_factura = 1; //FACTURA A
                } else {
                    tipo_de_factura = 6; //FACTURA B
                }
            } else {
                // factura C
                tipo_de_factura = 11;
            }


            if (data.nro_cliente == 1) {
                tipo_de_documento = 96;
                numero_de_documento = 1111111;
            } else {
                if (data.tipo_iva_nombre == 'CONSUMIDOR FINAL') {
                    tipo_de_documento = 96;
                } else {
                    tipo_de_documento = 80;
                }
                if (data.cuit != null) {
                    numero_de_documento = data.cuit;
                } else {
                    numero_de_documento = data.numero_documento;
                    cuit = numero_de_documento;
                }
            }



            $("#clientedc").text(nro_cliente + " / " + nombre);
            $("#cuitdc").text("CUIT: " + cuit + " / " + tipo_iva_nombre);
            $("#domiciliodc").text("Domicilio: " + direccion + " / " + localidad);
            $("#cliente_id").val(id);
            $("#tipo_de_factura").val(tipo_de_factura);
            $("#tipo_de_documento").val(tipo_de_documento);
            $("#numero_de_documento").val(numero_de_documento);

            $("#lista-clientes-modal").fadeOut();

        } else {
            console.error("No se pudieron obtener los datos del cliente.");
        }
    });

    $(document).on("click", ".btn-eliminar-cliente", function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "clientes", id_eliminar);

    });

    $(document).on("click", "#btn-realizar-cierre-caja", function() {
        // Obtener los datos del formulario
        var efectivo_inicial = $("#efectivo_inicial").val();
        var efectivo_final = $("#efectivo_final").val();
        var comentarios = $("#comentarios").val();
        var usuario_id = $("#usuario_id").val();

        // enviar los datos del formulario
        $.blockUI({ message: "<h1>Realizando cierre de caja...</h1>" });
        $.ajax({
            url: "./ajax/cierres_cajas/add.php",
            method: "POST",
            data: {
                efectivo_inicial: efectivo_inicial,
                efectivo_final: efectivo_final,
                comentarios: comentarios,
                usuario_id: usuario_id,
            },
            success: function(response) {
                responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#cierre-caja-modal").fadeOut();
                    mostrarNotificacion(
                        "Cierre de Caja",
                        "exito",
                        responseObject.status_message
                    );
                    clearProductList();
                } else {
                    $("#cierre-caja-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }

                $.unblockUI();
            },
            error: function(xhr, status, error) {
                // Manejar errores aquí
                console.error(xhr, status, error);
                $.unblockUI();
            },
        });

    });


    $(".custom-modal-close-btn").click(function() {
        $(this).closest(".custom-modal").fadeOut();
    });
    $(".custom-modal-close").click(function() {
        $(this).closest(".custom-modal").fadeOut();
    });

    $(window).click(function(event) {
        if (event.target == document.getElementById("buscar-cliente-modal")) {
            $(".custom-modal").fadeOut();
        }
    });

    //Modal Crear producto
    $("#crearProducto, #btn-menu-productos-crear").click(function() {

        //limpia todo el formulario, incluyendo los campos ocultos
        $("#editar-producto-form")[0].reset();
        //limpiar los select
        $("#editar-producto-form select").val(null).trigger("change");






        //cambiar el id del boton btn-editar-producto por btn-guardar-producto
        $("#btn-guardar-producto").show();
        $("#btn-editar-producto").hide();

        $("#editar-producto-modal").fadeIn().css("z-index", z_index++);

        $("#editar-producto-form #tasa_iva_id").select2({
            language: "es",
            placeholder: "Seleccione un tipo",
            allowClear: true,
            //minimumInputLength: 1,
            ajax: {
                url: "ajax/tasa_iva/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        });
    });

    $(window).click(function(event) {
        if (event.target == document.getElementById("editar-producto-modal")) {
            $(".custom-modal").fadeOut();
        }
    });

    //modal crear cliente
    $("#crearCliente, #btn-menu-clientes-crear").click(function() {

        //deahabilitar el evento submit de modificar-cliente-form
        $("#modificar-cliente-form").off("submit");
        //ocultar el boton btn-editar-cliente y mostrar el boton btn-guardar-cliente
        $("#btn-editar-cliente").hide();
        $("#btn-guardar-cliente").show();


        var formCliente = $("#modificar-cliente-form");
        // Limpiar los campos del formulario
        formCliente[0].reset();
        // Mostrar el modal
        $("#modificar-cliente-modal").fadeIn().css("z-index", z_index++);
        $("#mensajeAlertaCliente").hide();
        $("#modificar-cliente-modal #cuit").on("keyup", function(event) {
            if (event.keyCode == 13) {

                var cuit = $(this).val();
                if (cuit.length == 11) {
                    $.blockUI({ message: "<h1>Buscando Contribuyente...</h1>" });
                    try {

                        $.ajax({
                            url: "./_wsafip_contribuyente.php",
                            type: "POST",
                            data: {
                                "cuit": cuit,
                            },
                            success: function(response) {
                                if (response != "null") {
                                    try {

                                        var responseObject = JSON.parse(response);
                                        console.log(responseObject);
                                        //si es perona fisica
                                        if (responseObject.datosGenerales.tipoPersona == "FISICA") {
                                            var apellido_nombre = responseObject.datosGenerales.apellido + " " + responseObject.datosGenerales.nombre;
                                            var cuit = responseObject.datosGenerales.idPersona;
                                            //obtener el dni en base al cuit
                                            var cuitStr = cuit.toString(); // Convierte el número a cadena
                                            var dni = cuitStr.substring(2, 10);

                                        } else {
                                            var apellido_nombre = responseObject.datosGenerales.razonSocial;
                                            var dni = 0;
                                        }
                                        var direccion = responseObject.datosGenerales.domicilioFiscal.direccion + " - " + responseObject.datosGenerales.domicilioFiscal.localidad + " - " + responseObject.datosGenerales.domicilioFiscal.descripcionProvincia;
                                        //obtengo el tipo de inpiesto del contribuyente
                                        var impuestos_contribuyentes = responseObject.datosRegimenGeneral.impuesto;
                                        //recorro los impuestos del contribuyente y guardo idImpuesto en un arreglo
                                        var impuestos = [];
                                        for (var i = 0; i < impuestos_contribuyentes.length; i++) {
                                            impuestos.push(impuestos_contribuyentes[i].idImpuesto);
                                        }
                                        //si impuestos contiene 30 es responsable inscripto, si contiene 5095, 20, 21,22,23 o 24 es monotributista,
                                        if (impuestos.includes(30)) {
                                            seleccionarPorDescripcion("#tipo_iva_id", "INSCRIPTO");

                                        }

                                        if (impuestos.includes(32)) {
                                            seleccionarPorDescripcion("#tipo_iva_id", "EXENTO");

                                        }

                                        if (impuestos.includes(5095) || impuestos.includes(20) || impuestos.includes(21) || impuestos.includes(22) || impuestos.includes(23) || impuestos.includes(24)) {
                                            seleccionarPorDescripcion("#tipo_iva_id", "MONOTRIBUTO");

                                        }

                                        $("#modificar-cliente-modal #nombre").val(apellido_nombre);
                                        $("#modificar-cliente-modal #direccion_comercial").val(direccion);
                                        $("#modificar-cliente-modal #numero_documento").val(dni);

                                    } catch (error) {
                                        mostrarNotificacion("Buscar Cliente", "error", "No se encontraron datos.<br>Compruebe que el CUIT sea correcto.<br>Y que el servicio de AFIP este habilitado.");
                                        $.unblockUI();
                                    }
                                }
                                $.unblockUI();
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                $.unblockUI();
                            },
                        });
                    } catch (error) {
                        mostrarNotificacion("Buscar Cliente", "error", "No se encontraron datos.<br>Compruebe que el CUIT sea correcto.<br>Y que el servicio de AFIP este habilitado.");
                        $.unblockUI();

                    }
                }
            }


        });


        $("#modificar-cliente-modal #numero_documento").on("keyup", function(event) {
            if (event.keyCode == 13) {
                var dni = $(this).val();
                if (dni.length > 6) {
                    $.blockUI({ message: "<h1>Buscando Contribuyente...</h1>" });
                    try {

                        $.ajax({
                            url: "./_wsafip_contribuyente_dni.php",
                            type: "POST",
                            data: {
                                "dni": dni,
                            },
                            success: function(response) {
                                try {


                                    if (response != "null") {
                                        var responseObject = JSON.parse(response);
                                        if (responseObject.errorConstancia != undefined) {
                                            $.unblockUI();
                                            return;
                                        }

                                        console.log(responseObject);
                                        //si es perona fisica
                                        if (responseObject.datosGenerales.tipoPersona == "FISICA") {
                                            var apellido_nombre = responseObject.datosGenerales.apellido + " " + responseObject.datosGenerales.nombre;
                                            var cuit = responseObject.datosGenerales.idPersona;
                                        }
                                        var direccion = responseObject.datosGenerales.domicilioFiscal.direccion + " - " + responseObject.datosGenerales.domicilioFiscal.localidad + " - " + responseObject.datosGenerales.domicilioFiscal.descripcionProvincia;
                                        //obtengo el tipo de inpiesto del contribuyente
                                        //sino existe responseObject.datosRegimenGeneral.impuesto es porque no tiene impuestos activos
                                        if (responseObject.datosRegimenGeneral != undefined) {
                                            var impuestos_contribuyentes = responseObject.datosRegimenGeneral.impuesto;
                                        } else {
                                            var impuestos_contribuyentes = [];
                                        }
                                        //recorro los impuestos del contribuyente y guardo idImpuesto en un arreglo
                                        var impuestos = [];
                                        for (var i = 0; i < impuestos_contribuyentes.length; i++) {
                                            impuestos.push(impuestos_contribuyentes[i].idImpuesto);
                                        }
                                        //si impuestos contiene 30 es responsable inscripto, si contiene 5095, 20, 21,22,23 o 24 es monotributista,
                                        if (impuestos.includes(30)) {
                                            seleccionarPorDescripcion("#tipo_iva_id", "INSCRIPTO");
                                        } else
                                        if (impuestos.includes(32)) {
                                            seleccionarPorDescripcion("#tipo_iva_id", "EXENTO");
                                        } else

                                        if (impuestos.includes(5095) || impuestos.includes(20) || impuestos.includes(21) || impuestos.includes(22) || impuestos.includes(23) || impuestos.includes(24)) {
                                            seleccionarPorDescripcion("#tipo_iva_id", "MONOTRIBUTO");
                                        } else {
                                            seleccionarPorDescripcion("#tipo_iva_id", "CONSUMIDOR FINAL");
                                        }
                                        $("#modificar-cliente-modal #nombre").val(apellido_nombre);
                                        $("#modificar-cliente-modal #direccion_comercial").val(direccion);
                                        $("#modificar-cliente-modal #cuit").val(cuit);
                                    }
                                    $.unblockUI();
                                } catch (error) {
                                    mostrarNotificacion("Buscar Cliente", "error", "No se encontraron datos.<br>Compruebe que el DNI sea correcto.<br>Y que el servicio de AFIP este habilitado.");
                                    $.unblockUI();
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error(error);
                                $.unblockUI();
                            },
                        });
                    } catch (error) {
                        console.error(error);
                        $.unblockUI();

                    }
                }
            }


        });

    });


    // Buscar el producto luego de precionar ENTER (Lector codigo de barras)
    $("#searchProduct").on("keyup", function(event) {
        // Verificar si se presionó la tecla "Enter"
        if ($("#searchProduct").val().length > 0) {
            if (event.keyCode === 13) {
                // Bloquear toda la pantalla
                $.blockUI({ message: "<h1>Buscando Producto...</h1>" });

                // Obtener el valor del input de búsqueda
                var searchTerm = $("#searchProduct").val();
                var limit = 1;
                var producto_balanza = false;

                // comprobar si el codigo de barras inicia con el valor de cadigo_barra_inicio
                if (
                    searchTerm.startsWith(codigo_barra_inicio) &&
                    codigo_barra_inicio.length > 0 &&
                    searchTerm.length == codigo_barra_long
                ) {
                    var final_codigo_barra = parseInt(codigo_barra_id_long);
                    var inicio_payload = final_codigo_barra;
                    var inicio_decimal = parseInt(codigo_barra_payload_int);

                    //obtener el codigo de barras que son los primeros caracteres cuya cantidad es codigo_barra_id_long
                    var codigo_barra = searchTerm.substring(0, final_codigo_barra);
                    //OBTENER EL RESTO DEL CODIGO DE BARRAS
                    var payload = searchTerm.substring(inicio_payload);
                    //crear un decimal cuya parte entera esta dada por la cantidad de caracters codigo_barra_payload_int
                    var payload_int = payload.substring(0, inicio_decimal);
                    //crear un decimal cuya parte decimal esta dada por la cantidad de caracters codigo_barra_payload_dec
                    var payload_dec = payload.substring(inicio_decimal);
                    var payload_decimal = payload_int + "." + payload_dec;
                    //convertir el decimal a un numero
                    var payload_number = parseFloat(payload_decimal);
                    //tomar lo que falta del codigo de barras
                    producto_balanza = true;
                    searchTerm = codigo_barra;
                }

                //si el producto es una balanza o es numerico solo busco el codigo de barra y el codigo sino busco descripcion y codigo
                if (producto_balanza || $.isNumeric(searchTerm)) {
                    var data = {
                        limit: limit,
                        codigo_barra: searchTerm,
                        codigo: searchTerm,
                    };
                } else {
                    var data = {
                        limit: limit,
                        descripcion: searchTerm,
                        codigo: searchTerm,
                        codigo_barra: searchTerm,
                    };
                }
                // Realizar la solicitud AJAX para buscar el producto                
                $.ajax({
                    url: "ajax/productos/list.php",
                    method: "GET",
                    data: data,
                    dataType: "json",
                    success: function(response) {
                        // Desbloquear la pantalla
                        $.unblockUI();
                        // Verificar si se encontraron resultados
                        if (response.length > 0) {
                            // Autocompletar el input con la información del producto
                            var producto = response[0];
                            var precio = parseFloat(producto.precio1);
                            var precio_impuesto_interno = parseFloat(producto.precio1_impuesto_interno);

                            var cantidad = 1;
                            if (producto_balanza) {
                                if (codigo_barra_payload_type == "P") {
                                    precio = parseFloat(payload_number);
                                } else {
                                    cantidad = parseFloat(payload_number);
                                }
                            }
                            var textoAutocompletar =
                                producto.codigo_barra +
                                " - " +
                                producto.descripcion +
                                " ($" +
                                precio.toFixed(2) +
                                ")";
                            $("#searchProduct").val(textoAutocompletar);

                            producto_balanza = producto_balanza || producto.producto_balanza;
                            // Llamar a la función addFavoriteProduct para agregar el producto a la tabla
                            addFavoriteProduct(
                                producto.id,
                                producto.descripcion,
                                parseFloat(precio),
                                parseFloat(precio_impuesto_interno),
                                parseFloat(producto.tasa_iva.tasa),
                                cantidad,
                                producto_balanza
                            );

                            // Limpiar el contenido del input después de 1 segundo
                            setTimeout(function() {
                                $("#searchProduct").val("");
                            }, 600);

                            // Colocar el cursor en el input
                            $("#searchProduct").focus();

                            // Haz lo que necesites con la variable products[selectedVendedor]elected
                        } else {
                            $("#searchProduct").val("");
                            // Colocar el cursor en el input
                            $("#searchProduct").focus();
                            mostrarNotificacion(
                                "Buscar Producto",
                                "error",
                                "No se encontraron productos"
                            );
                        }
                    },
                    error: function(xhr, status, error) {
                        // Desbloquear la pantalla
                        $.unblockUI();
                        // Manejo de errores
                        console.error(error);
                    },
                });
            }
        }

    });

    //Abrir el modal cobrar
    $("#btnfacturar,#btn-menu-ventas-cobrar").click(function() {
        // Verifica si hay productos agregados
        if (tipo_iva != 4) {
            if (products[selectedVendedor].length > 0) {
                $("#cobrar-modal").fadeIn().css("z-index", z_index++);
                $("#tipo_comprobante_id").val(1);
            } else {
                // Mostrar notificación si no hay productos agregados
                mostrarNotificacion(
                    "Cobrar",
                    "error",
                    "No hay productos agregados para cobrar."
                );

            }
        }
    });
    //Abrir el modal cobrar
    $("#btnpedido").click(function() {
        // Verifica si hay productos agregados

        if (products[selectedVendedor].length > 0) {
            if (venta_rapida == "0") {
                $("#cobrar-modal").fadeIn().css("z-index", z_index++);
                $("#tipo_comprobante_id").val(3);
            } else {
                ventaRapidaPedido();
            }
        } else {
            // Mostrar notificación si no hay productos agregados
            mostrarNotificacion(
                "Cobrar",
                "error",
                "No hay productos agregados para cobrar."
            );
        }

    });
    //Limpiar y abrir el modal lista de precios
    $("#btnlistaprecio").click(function() {
        //Tabla  Mostrar precios de productos
        try {
            if ($.fn.DataTable.isDataTable("#tablaProductosPrecios")) {
                $("#tablaProductosPrecios").DataTable().destroy();
            }
            $("#tablaProductosPrecios").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/productos/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {
                            // Aquí puedes manejar el error de timeout como prefieras
                            // Por ejemplo, mostrando un mensaje en la consola:
                            $("#tablaProductosPrecios_processing").hide();
                            $('#tablaProductosPrecios').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaProductosPrecios').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                            // Elimina mensajes previos
                        }
                    },

                    type: "POST",
                },
                columns: [
                    { data: "codigo" },
                    { data: "codigo_barra" },
                    { data: "descripcion" },
                    { data: "stock" },
                    { data: "precio1" },

                    // Agrega más columnas según la estructura de tus datos
                ],
                columnDefs: [
                    { targets: [0, 1, 3], className: "text-right" },
                    {
                        targets: -1,

                        className: "text-right", // Última columna (precio)
                        render: function(data, type, row, meta) {
                            // Aplicar estilos personalizados
                            return (
                                '<span style="font-size: 16px; font-weight: bold; color: green;">$' +
                                data +
                                "</span>"
                            );
                        },
                    },
                ],
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar producto...", // Placeholder para el nuevo cuadro de búsqueda
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
            $("#custom-search-input").on("change", function() {
                $("#tablaProductosPrecios").DataTable().search($(this).val()).draw();
            });
            $("#tablaProductosPrecios_filter").hide();
        } catch (error) {}
        $("#lista-precio-modal").fadeIn().css("z-index", z_index++);
        $("#custom-search-input").val("");
        $("#custom-search-input").focus();
        $("#tablaProductosPrecios").DataTable().search("").draw();
    });

    //Limpiar y abrir el modal buscar producto
    $("#btnBuscarProducto ,#btn-menu-productos-buscar").click(function() {

        // Verificar si la tabla ya está inicializada
        if ($.fn.DataTable.isDataTable("#tablaProductos")) {
            $("#tablaProductos").DataTable().destroy();
        }
        try {
            $("#tablaProductos").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/productos/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            $("#tablaProductos_processing").hide();
                            $('#tablaProductos').closest('.dataTables_wrapper').find('.alert').remove(); // Elimina mensajes previos
                            $('#tablaProductos').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [
                    { data: "codigo" },
                    { data: "codigo_barra" },
                    { data: "descripcion" },
                    { data: "stock" },
                    { data: "precio1" },
                    {
                        // Agregar una columna para el botón
                        data: null,
                        render: function(data, type, row, meta) {
                            return (
                                '<button class="btn-guardar" onclick="window.addFavoriteProduct(' +
                                row.id +
                                ", '" +
                                row.descripcion +
                                "', " +
                                row.precio1 +
                                ", " +
                                row.precio1_impuesto_interno +
                                ", " +
                                row.tasa_iva.tasa +
                                ",1, " +
                                row.producto_balanza +
                                ');window.closeModal();"><i class="fas fa-shopping-cart"></i></button>'
                            );
                        },
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],
                columnDefs: [
                    { targets: [0, 1, 3, 4, 5], className: "text-right" },
                    {
                        targets: 4,

                        className: "text-right", // Última columna (precio)
                        render: function(data, type, row, meta) {
                            // Aplicar estilos personalizados
                            return (
                                '<span style="font-size: 16px; font-weight: bold; color: green;">$' +
                                data +
                                "</span>"
                            );
                        },
                    },
                ],
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar producto...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-productos-input").on("change", function() {
                $("#tablaProductos").DataTable().search($(this).val()).draw();
            });
            $("#tablaProductos_filter").hide();
            $("#lista-productos-modal").fadeIn().css("z-index", z_index++);
        } catch (error) {}

        $("#custom-search-productos-input").val("");
        $("#custom-search-productos-input").focus();
        $("#tablaProductos").DataTable().search("").draw();
    });

    //Limpiar y abrir el modal buscar producto
    $("#btncierredecaja , #btn-menu-cierre-caja-cerrar").click(function() {
        $("#cierre-caja-modal").fadeIn().css("z-index", z_index++);

    });

    $("#btn-menu-cierre-caja-listar").click(function() {
        // Verificar si la tabla ya está inicializada
        if ($.fn.DataTable.isDataTable("#tablaCierresCajas")) {
            $("#tablaCierresCajas").DataTable().destroy();
        }
        // Mostrar el modal de lista de cierres de caja
        $("#lista-cierre-caja-modal").fadeIn().css("z-index", z_index++);
        // Inicializar la tabla de cierres de caja
        try {
            //destroy la tabla si ya esta inicializada
            if ($.fn.DataTable.isDataTable("#tablaCerrarCaja")) {
                $("#tablaCerrarCaja").DataTable().destroy();
            }


            $("#tablaCerrarCaja").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/cierres_cajas/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaCerrarCaja_processing").hide();
                            $('#tablaCerrarCaja').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaCerrarCaja').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [
                    { data: "id" },
                    { data: "usuario_nombre_completo" },
                    { data: "fecha" },
                    { data: "efectivo_inicial" },
                    { data: "total_ventas" },
                    { data: "total_gastos" },
                    { data: "efectivo_final" },
                ],
                columnDefs: [
                    { targets: [3, 4, 5, 6], className: "text-right" },
                    {
                        targets: [3, 4, 6],
                        className: "text-right",
                        render: function(data, type, row, meta) {
                            return (
                                '<span style="font-size: 16px; font-weight: bold; color: green;">$' +
                                data +
                                "</span>"
                            );
                        },
                    },
                    {
                        targets: [5],
                        className: "text-right",
                        render: function(data, type, row, meta) {
                            return (
                                '<span style="font-size: 16px; font-weight: bold; color: red;">$' +
                                data +
                                "</span>"
                            );
                        },
                    },
                ],
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar cierre de caja...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-cierre_caja-input").on("change", function() {
                $("#tablaCerrarCaja").DataTable().search($(this).val()).draw();
            });
            $("#tablaCerrarCaja_filter").hide();


        } catch (error) {}



    });





    //Limpiar y abrir el modal buscar producto
    $("#btnEditarProducto ,#btn-menu-productos-editar,#btnEditarProducto,#btn-menu-productos-eliminar").click(function() {
        var btn = $(this);
        // Verificar si la tabla ya está inicializada
        if ($.fn.DataTable.isDataTable("#tablaEditarProductos")) {
            $("#tablaEditarProductos").DataTable().destroy();
        }

        $("#lista-productos-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarProductos").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/productos/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarProductos_processing").hide();
                            $('#tablaEditarProductos').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarProductos').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [
                    { data: "codigo" },
                    { data: "codigo_barra" },
                    { data: "descripcion" },
                    { data: "stock" },
                    { data: "precio1" },
                    {
                        // Agregar una columna para el botón
                        data: null,

                        render: function(data, type, row, meta) {
                            if (btn.attr('id') == 'btn-menu-productos-editar' || btn.attr('id') == 'btnEditarProducto') {
                                return (
                                    '<button class="btn-guardar" onclick=" window.consultarProducto(' +
                                    row.id +
                                    ')"><i class="fas fa-pencil"></i></button>'
                                );
                            } else if (btn.attr('id') == 'btn-menu-productos-eliminar') {
                                return (
                                    '<button class="btn-cancelar btn-eliminar-producto" data-id="' + row.id + '"><i class="fas fa-trash"></i></button>'
                                );
                            }
                        },
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],
                columnDefs: [
                    { targets: [0, 1, 3, 4, 5], className: "text-right" },
                    {
                        targets: 4,

                        className: "text-right", // Última columna (precio)
                        render: function(data, type, row, meta) {
                            // Aplicar estilos personalizados
                            return (
                                '<span style="font-size: 16px; font-weight: bold; color: green;">$' +
                                data +
                                "</span>"
                            );
                        },
                    },
                ],
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar producto...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada

            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-productos-input").on("change", function() {
                $("#tablaEditarProductos").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarProductos_filter").hide();
            //asignar evento para eliminar producto
            $(document).on("click", ".btn-eliminar-producto", function() {
                var id_eliminar = $(this).attr("data-id");
                // Llamar a la función mostrarNotificacion con los parámetros deseados
                mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "productos", id_eliminar);

            });



        } catch (error) {}

        $("#custom-search-editar-productos-input").val("");
        $("#custom-search-editar-productos-input").focus();
        $("#tablaEditarProductos").DataTable().search("").draw();
    });




    // Manejar el evento click en el botón "Imprimir comprobante"
    $("#btnImprimirComprobante").click(function() {
        // Obtener los datos del cliente
        var cliente_id = $("#cliente_id").val();

        // Obtener el tipo de comprobante seleccionado
        var tipoComprobanteId = $("#tipo_comprobante_id").val();

        // Obtener la forma de pago
        var formaPago = $("#formaPago").val();

        // Obtener el monto a pagar
        var montoPagar = $("#montoPagar").text();

        // Obtener el monto pagado
        var montoPagado = $("#montoPagado").val();


        // Obtener la lista de productos
        var listaProductos = JSON.stringify(products[selectedVendedor]);
        var listaPromociones = JSON.stringify(promotions[selectedVendedor]);

        // Verificar si se ha ingresado el monto pagado
        var montoPagado = parseFloat($("#montoPagado").val());
        if (isNaN(montoPagado) || montoPagado <= 0) {
            $("#mensajeAlertaCobrar")
                .html(
                    "<i class='fas fa-exclamation-circle'></i>  Ingrese un monto válido."
                )
                .css("display", "block");
            return;
        } else {
            $("#mensajeAlertaCobrar").css("display", "none");
        }

        // Obtener el monto a pagar
        var montoPagar = parseFloat($("#montoPagar").text().substring(1));
        if (montoPagado < montoPagar) {
            $("#mensajeAlertaCobrar")
                .html(
                    "<i class='fas fa-exclamation-circle'></i>  El monto pagado es menor al monto a pagar."
                )
                .css("display", "block");
            return;
        } else {
            $("#mensajeAlertaCobrar").css("display", "none");
        }



        $(".custom-modal").fadeOut();
        if (tipoComprobanteId == 1) {
            $.blockUI({
                message: "<h1 style='color: white; font-size: 24px;'>Conectando con AFIP...</h1>",
                css: {
                    background: "lightblue",
                    "border-radius": "10px",
                    padding: "20px",
                    "box-shadow": "0 0 10px rgba(0, 0, 0, 0.5)",
                    color: "black",
                    "text-align": "center"
                }
            });

            $.ajax({
                url: "./_wsafip.php",
                method: "POST",
                data: {
                    cuit: $("#cuit").val(),
                    punto_de_venta: $("#punto_de_venta").val(),
                    tipo_factura: tipo_de_factura,
                    concepto: 1,
                    destinatario: destinatario,
                    tipo_de_documento: $("#tipo_de_documento").val(),
                    numero_de_documento: $("#numero_de_documento").val(),
                    promociones: listaPromociones,
                    productos: listaProductos,
                },
                success: function(response) {
                    if (response.status == '201') {
                        var formData = {
                            nombre_tienda: $("#nombre_tienda").text(),
                            direccion_tienda: $("#direccion_tienda").text(),
                            telefono_tienda: "", // Cambia el número de teléfono
                            articulos: obtenerListadoArticulos(),
                            total: $("#montoPagar").text().substring(1),
                            tipo_comprobante_id: tipoComprobanteId,
                            tipo_comprobante: "FACTURA", // Enviar el label del tipo de comprobante
                            monto_pagado: montoPagado,
                            monto_vuelto: (montoPagado - montoPagar).toFixed(2),
                            fecha: new Date().toLocaleDateString(),
                            hora: new Date().toLocaleTimeString(),
                            cliente_nombre: "Cliente: " + $("#clientedc").text(),
                            cliente_cuit: $("#cuitdc").text(),
                            cliente_domicilio: $("#domiciliodc").text(),
                            cliente_id: cliente_id,
                            productos: listaProductos,
                            vendedor_id: selectedVendedor,
                            promociones: listaPromociones,
                            numero_factura: response.factura,
                            cae: response.cae,
                            fecha_vencimiento: response.vencimiento,
                            letra_factura: ($("#tipo_de_factura").val() == 1) ? "A" : (($("#tipo_de_factura").val() == 6) ? "B" : "C"),
                            punto_venta: $("#punto_de_venta").val(),
                            url_pdf: response.url_pdf,
                            concepto: response.concepto,
                            tipo_de_documento: $("#tipo_de_documento").val(),
                            numero_de_documento: $("#numero_de_documento").val(),
                            tipo_factura: tipo_de_factura,
                            qr: response.qr,
                            importe_iva: response.importe_iva,
                            importe_iva_105: response.importe_iva_105,
                            importe_iva_21: response.importe_iva_21,
                            importe_iva_0: response.importe_iva_0,
                            no_gravado_iva_0: response.no_gravado_iva_0,
                            no_gravado_iva_105: response.no_gravado_iva_105,
                            no_gravado_iva_21: response.no_gravado_iva_21,
                        };

                        $.unblockUI();
                        $.blockUI({
                            message: "<h1 style='color: white; font-size: 24px;'>Imprimiendo ticket...</h1>",
                            css: {
                                background: "lightgreen",
                                "border-radius": "10px",
                                padding: "20px",
                                "box-shadow": "0 0 10px rgba(0, 0, 0, 0.5)",
                                color: "white",
                                "text-align": "center"
                            }
                        });
                        $.ajax({
                            url: "./print_factura.php",
                            method: "POST",
                            data: formData,
                            success: function(response) {
                                // Utilizar la función print de print-js para imprimir

                                try {
                                    var responseData = JSON.parse(response);

                                    // Obtener la URL del PDF del objeto JSON
                                    var pdfUrl = responseData.pdfUrl;

                                    printJS({ printable: pdfUrl, type: "pdf", silent: true });
                                    $.unblockUI();

                                    clearProductList();
                                } catch (error) {
                                    mostrarNotificacion("Error", "error", "Error al imprimir la factura<br>Reimprima la factura desde el listado de comprobantes");
                                    $.unblockUI();
                                    clearProductList()
                                }

                            },
                            error: function(xhr, status, error) {
                                // Manejar errores, si los hay
                                console.error(error);
                                $.unblockUI();
                            },
                        });
                    } else {
                        $.unblockUI();
                        mostrarNotificacion("Error", "error", response.error);
                    }
                },
                error: function(xhr, status, error) {
                    $.unblockUI();
                }
            });




        } else {
            $.blockUI({
                message: "<h1 style='color: white; font-size: 24px;'>Imprimiendo ticket...</h1>",
                css: {
                    background: "lightgreen",
                    "border-radius": "10px",
                    padding: "20px",
                    "box-shadow": "0 0 10px rgba(0, 0, 0, 0.5)",
                    color: "white",
                    "text-align": "center"
                }
            });
            var formData = {
                nombre_tienda: $("#nombre_tienda").text(),
                direccion_tienda: $("#direccion_tienda").text(),
                telefono_tienda: "", // Cambia el número de teléfono
                articulos: obtenerListadoArticulos(),
                total: $("#montoPagar").text().substring(1),
                tipo_comprobante_id: tipoComprobanteId,
                tipo_comprobante: "PEDIDO", // Enviar el label del tipo de comprobante
                monto_pagado: montoPagado,
                monto_vuelto: (montoPagado - montoPagar).toFixed(2),
                fecha: new Date().toLocaleDateString(),
                hora: new Date().toLocaleTimeString(),
                cliente_nombre: "Cliente: " + $("#clientedc").text(),
                cliente_cuit: $("#cuitdc").text(),
                cliente_domicilio: $("#domiciliodc").text(),
                cliente_id: cliente_id,
                productos: listaProductos,
                vendedor_id: selectedVendedor,
                promociones: listaPromociones,


            };
            $.ajax({

                url: "./print_pedido.php",
                method: "POST",
                data: formData,
                success: function(response) {
                    // Utilizar la función print de print-js para imprimir
                    try {
                        var responseData = JSON.parse(response);

                        // Obtener la URL del PDF del objeto JSON
                        var pdfUrl = responseData.pdfUrl;

                        printJS({ printable: pdfUrl, type: "pdf", silent: true });
                        $.unblockUI();

                        clearProductList();
                    } catch (error) {
                        mostrarNotificacion("Error", "error", "Error al imprimir el pedido<br>Reimprima el pedido desde el listado de comprobantes");
                        $.unblockUI();
                        clearProductList()
                    }
                },
                error: function(xhr, status, error) {
                    // Manejar errores, si los hay
                    console.error(error);
                    $.unblockUI();
                },
            });
        }


    });

    // funcion venta rapida
    function ventaRapidaPedido() {
        var cliente_id = $("#cliente_id").val();
        var tipoComprobanteId = 3; // Tipo de comprobante igual a 3
        var formaPago = $("#formaPago").val();
        var montoPagar = $("#montoPagar").text(); // No se hace control del monto
        var montoPagado = montoPagar; // Obtener el monto pagado

        var listaProductos = JSON.stringify(products[selectedVendedor]);
        var listaPromociones = JSON.stringify(promotions[selectedVendedor]);

        var montoPagado = parseFloat($("#montoPagado").val());


        // Datos del formulario
        var formData = {
            nombre_tienda: $("#nombre_tienda").text(),
            direccion_tienda: $("#direccion_tienda").text(),
            telefono_tienda: "",
            articulos: obtenerListadoArticulos(),
            total: $("#montoPagar").text().substring(1),
            tipo_comprobante_id: tipoComprobanteId,
            tipo_comprobante: "PEDIDO", // Cambiar a "PEDIDO"
            monto_pagado: $("#montoPagar").text().substring(1),
            monto_vuelto: 0, // Vuelto igual a cero
            fecha: new Date().toLocaleDateString(),
            hora: new Date().toLocaleTimeString(),
            cliente_nombre: "Cliente: " + $("#clientedc").text(),
            cliente_cuit: $("#cuitdc").text(),
            cliente_domicilio: $("#domiciliodc").text(),
            cliente_id: cliente_id,
            productos: listaProductos,
            vendedor_id: selectedVendedor,
            promociones: listaPromociones
        };
        if (imprimir == 1) {
            $.blockUI({
                message: "<h1 style='color: white; font-size: 24px;'>Imprimiendo ticket...</h1>",
                css: {
                    background: "lightgreen",
                    "border-radius": "10px",
                    padding: "20px",
                    "box-shadow": "0 0 10px rgba(0, 0, 0, 0.5)",
                    color: "white",
                    "text-align": "center"
                }
            });


            $.ajax({
                url: "./print_pedido.php", // Cambiar la URL al archivo print_pedido.php
                method: "POST",
                data: formData,
                success: function(response) {
                    try {
                        var responseData = JSON.parse(response);
                        var pdfUrl = responseData.pdfUrl;
                        printJS({ printable: pdfUrl, type: "pdf", silent: true });
                        $.unblockUI();
                        clearProductList();
                    } catch (error) {
                        mostrarNotificacion("Error", "error", "Error al imprimir el pedido<br>Reimprima el pedido desde el listado de comprobantes");
                        $.unblockUI();
                        clearProductList()
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $.unblockUI();
                },

            });
        } else {
            $.blockUI({
                message: "<h1 style='color: white; font-size: 24px;'>Cobrando...</h1>",
                css: {
                    background: "lightgreen",
                    "border-radius": "10px",
                    padding: "20px",
                    "box-shadow": "0 0 10px rgba(0, 0, 0, 0.5)",
                    color: "white",
                    "text-align": "center"
                }
            });

            $.ajax({
                url: "./print_pedido.php", // Cambiar la URL al archivo print_pedido.php
                method: "POST",
                data: formData,
                success: function(response) {
                    try {
                        var responseData = JSON.parse(response);
                        var pdfUrl = responseData.pdfUrl;
                        //printJS({ printable: pdfUrl, type: "pdf", silent: true });
                        $.unblockUI();
                        clearProductList();
                    } catch (error) {
                        mostrarNotificacion("Error", "error", "Error al imprimir el pedido<br>Reimprima el pedido desde el listado de comprobantes");
                        $.unblockUI();
                        clearProductList()
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $.unblockUI();
                },

            });
        }
    }





    // Manejar el evento keyup en el campo de monto pagado
    $("#montoPagado").keyup(function() {
        var montoPagado = parseFloat($(this).val());
        var montoPagar = parseFloat($("#montoPagar").text().substring(1)); // Obtiene el monto a pagar eliminando el símbolo de $
        var vuelto = montoPagado - montoPagar;

        if (montoPagado < montoPagar) {
            $("#mensajeAlertaCobro").show();
            $("#vuelto").hide(); // Oculta el campo de vuelto si el monto pagado es menor al monto a pagar
        } else {
            $("#mensajeAlertaCobro").hide();
            $("#vuelto").show(); // Muestra el campo de vuelto si el monto pagado supera al monto a pagar
            $("#vuelto")
                .text("Vuelto: $" + vuelto.toFixed(2))
                .css("color", "darkgreen"); // Muestra el vuelto con dos decimales y lo resalta en color oscuro
        }
    });

    //select Tipo de producto
    $("#tipo_id").select2({
        language: "es",
        placeholder: "Seleccione un tipo",
        allowClear: true,
        //minimumInputLength: 1,
        ajax: {
            url: "ajax/tipos/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    $("#formas_pago").select2({
        language: "es",
        placeholder: "Seleccione la forma de pago",
        allowClear: true,
        //minimumInputLength: 1,
        ajax: {
            url: "ajax/formas_pagos/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {

                return {
                    results: data,
                };
            },
            cache: false,
        },
    }).on('select2:open', function(e) {
        var $select2 = $(this);
        // Esperar un breve momento antes de seleccionar el primer elemento
        setTimeout(function() {
            $select2.select2('select', $select2.find('option').first().val());
        }, 0);
    });

    // Cambiar el monto según la forma de pago seleccionada
    $("#formas_pago").on("select2:select", function(e) {
        // Obtener el monto total a pagar
        var montoStr = $("#total-price").text().replace("$", "");

        // Convertir la cadena a un número flotante
        var montoTotal = parseFloat(montoStr);

        // Obtener el porcentaje de interés desde los datos del elemento seleccionado
        var interes = parseFloat(e.params.data.porcentaje) / 100;

        // Calcular el monto a pagar incluyendo el interés
        var montoPagar = montoTotal + montoTotal * interes;

        // Actualizar el texto del elemento con id "montoPagar"
        $("#montoPagar").text("$" + montoPagar.toFixed(2));
        $("#formaPago").val(e.params.data.id);
    });

    // Manejar el caso cuando se borra la selección
    $("#formas_pago").on("select2:unselect", function(e) {
        // Restablecer el monto a pagar al total original
        var montoStr = $("#total-price").text().replace("$", "");
        $("#montoPagar").text("$" + montoStr);
    });

    // Seleccionar automáticamente el primer elemento al cargar la página
    $(document).ready(function() {
        // Establecer el valor del primer elemento
        $("#formas_pago").val($("#formas_pago option:first").val());

        // Desencadenar manualmente el evento change para ejecutar el código de actualización del monto
        $("#formas_pago").trigger("change");
    });
    //select Unidad de producto
    $("#unidad_id").select2({
        language: "es",
        placeholder: "Seleccione un tipo",
        allowClear: true,
        //minimumInputLength: 1,
        ajax: {
            url: "ajax/unidades/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Agrupacion de producto
    $("#agrupacion_id").select2({
        language: "es",
        placeholder: "Seleccione una agrupacion",
        allowClear: true,
        //minimumInputLength: 1,
        ajax: {
            url: "ajax/agrupaciones/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Familia de producto
    $("#familia_id").select2({
        language: "es",
        placeholder: "Seleccione una familia",
        allowClear: true,
        //minimumInputLength: 1,
        ajax: {
            url: "ajax/familias/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Proveedor de producto
    $("#proveedor_id").select2({
        language: "es",
        placeholder: "Seleccione un proveedor",
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: "ajax/proveedores/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Tipo  de IVA del producto
    $("#tipo_iva_id").select2({
        language: "es",
        placeholder: "Seleccione un tipo de IVA",
        allowClear: true,
        //minimumInputLength: 3,
        ajax: {
            url: "ajax/tipos_iva/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Tipo de documento
    $("#tipo_documento_id").select2({
        language: "es",
        placeholder: "Seleccione un tipo de documento",
        allowClear: true,
        //minimumInputLength: 3,
        ajax: {
            url: "ajax/tipos_documento/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Localidad
    $("#localidad_id").select2({
        language: "es",
        placeholder: "Seleccione una localidad",
        allowClear: true,
        minimumInputLength: 3,
        ajax: {
            url: "ajax/localidades/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    //select Categoria de producto
    $("#categoria_id").select2({
        language: "es",
        placeholder: "Seleccione una categoria de cliente",
        allowClear: true,
        //minimumInputLength: 3,
        ajax: {
            url: "ajax/categorias/list_select.php",
            dataType: "json",
            delay: 250,
            processResults: function(data) {
                return {
                    results: data,
                };
            },
            cache: false,
        },
    });
    // Evento clic en el botón guardar cliente
    $("#btn-guardar-cliente").on("click", function(event) {
        // Evitar el envío del formulario
        event.preventDefault();

        // Validar el formulario
        if (!validarFormularioCliente()) {
            return;
        }

        // Obtener los datos del formulario
        var formData = $("#modificar-cliente-form").serialize();

        // Bloquear la pantalla con blockUI
        $.blockUI({ message: "<h1>Guardando...</h1>" });

        // Enviar los datos por AJAX
        $.ajax({
            type: "POST",
            url: "ajax/clientes/add.php",
            data: formData,
            success: function(response) {
                // Desbloquear la pantalla
                $.unblockUI();

                // Convertir la respuesta a objeto JSON
                var responseObject = JSON.parse(response);

                // Verificar el estado de la respuesta
                if (responseObject.status === 201) {
                    // Cerrar el modal
                    $("#modificar-cliente-modal").hide();
                    limpiarFormularioClientes();

                    // Mostrar notificación de éxito
                    mostrarNotificacion(
                        "Agregar Cliente",
                        "exito",
                        responseObject.status_message
                    );
                    //$("#tablaClientes").DataTable().ajax.reload();
                    $("#mensajeAlertaCliente").hide();
                } else {
                    // Mostrar mensaje de error en el modal
                    $("#mensajeAlertaCliente").text(responseObject.status_message);
                    $("#mensajeAlertaCliente").show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Desbloquear la pantalla
                $.unblockUI();

                // Verificar si hay un error 4xx
                if (jqXHR.status >= 400 && jqXHR.status < 500) {
                    // Mostrar mensaje de error específico en el modal
                    var errorMessage =
                        jqXHR.responseJSON && jqXHR.responseJSON.status_message ?
                        jqXHR.responseJSON.status_message :
                        "Hubo un error al agregar el cliente.";
                    $("#mensajeAlertaCliente").text(errorMessage);
                    $("#mensajeAlertaCliente").show();
                } else {
                    // Mostrar mensaje de error genérico en el modal
                    var genericErrorMessage = "Hubo un error en el servidor.";
                    if (jqXHR.responseJSON && jqXHR.responseJSON.status_message) {
                        genericErrorMessage += " " + jqXHR.responseJSON.status_message;
                    }
                    $("#mensajeAlertaCliente").text(genericErrorMessage);
                    $("#mensajeAlertaCliente").show();
                }
            },
        });
    });
    // Evento clic en el botón guardar producto
    $("#btn-guardar-producto").on("click", function(event) {
        // Evitar el envío del formulario
        event.preventDefault();

        // Validar el formulario
        if (!validarFormularioProducto()) {
            return;
        }

        // Obtener los datos del formulario
        var formData = $("#editar-producto-form").serialize();

        // Bloquear la pantalla con blockUI
        $.blockUI({ message: "<h1>Guardando...</h1>" });

        // Enviar los datos por AJAX
        $.ajax({
            type: "POST",
            url: "ajax/productos/add.php",
            data: formData,
            success: function(response) {
                // Desbloquear la pantalla
                $.unblockUI();

                // Convertir la respuesta a objeto JSON
                var responseObject = JSON.parse(response);

                // Verificar el estado de la respuesta
                if (responseObject.status === 201) {
                    // Cerrar el modal
                    $("#editar-producto-modal").fadeOut();

                    // Mostrar notificación de éxito
                    mostrarNotificacion(
                        "Agregar Producto",
                        "exito",
                        responseObject.status_message
                    );
                    //$("#tablaEditarProductos").DataTable().ajax.reload();
                } else {
                    // Mostrar mensaje de error en el modal
                    $("#editar-producto-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Desbloquear la pantalla
                $.unblockUI();

                // Mostrar mensaje de error en el modal
                $("#editar-producto-modal").append(
                    '<div class="alert alert-danger">Hubo un error al agregar el producto.</div>'
                );
            },
        });
    });

    $("#btn-editar-producto").click(function(event) {
        // Evitar el envío del formulario por defecto
        event.preventDefault();
        if (!validarFormularioEditarProducto()) {
            return;
        }



        //agrego todos los checkbox que esten tildados y destildados en editar-producto-form

        // Obtener los datos del formulario
        var formData = $("#editar-producto-form").serialize();

        var checks = $("#editar-producto-form input[type='checkbox']");
        var checkeds = [];
        var uncheckeds = [];
        for (var i = 0; i < checks.length; i++) {
            if (checks[i].checked) {
                checkeds.push(checks[i].name);
            } else {
                uncheckeds.push(checks[i].name);
            }
        }
        for (var i = 0; i < checkeds.length; i++) {
            formData += "&" + checkeds[i] + "=1";
        }
        for (var i = 0; i < uncheckeds.length; i++) {
            formData += "&" + uncheckeds[i] + "=0";
        }


        $.blockUI({ message: "<h1>Guardando...</h1>" });
        // Enviar la solicitud AJAX para actualizar el producto
        $.ajax({
            url: "./ajax/productos/edit.php",
            type: "POST",
            dataType: "json",
            data: formData,
            success: function(response) {
                $.unblockUI();
                // Manejar la respuesta del servidor aquí
                if (response.status === 201) {
                    // Producto actualizado exitosamente
                    // Cerrar el modal
                    $("#editar-producto-modal").fadeOut();
                    // Mostrar notificación de éxito
                    mostrarNotificacion(
                        "Actualizar producto",
                        "exito",
                        "Producto actualizado correctamente."
                    );
                    $("#tablaEditarProductos").DataTable().ajax.reload();
                } else {
                    // Error al actualizar el producto
                    console.error("Error al actualizar el producto:", response.message);
                    // Mostrar notificación de error
                    mostrarNotificacion(
                        "Actualizar producto",
                        "error",
                        "Error al actualizar el producto: " + response.message
                    );
                }
            },
            error: function(xhr, status, error) {
                // Manejar errores de la solicitud AJAX aquí
                console.error("Error en la solicitud AJAX:", error);
                $.unblockUI();
                // Mostrar notificación de error
                mostrarNotificacion(
                    "Actualizar producto",
                    "error",
                    "Error en la solicitud AJAX: " + error
                );
            },
        });
    });

    $("#btnTabFavoritos").click(function() {
        $("#indicadorCarga").fadeIn().css("z-index", z_index++);
        $("#tabs").load("productos_favoritos.php", function() {
            $("#indicadorCarga").fadeOut();
        });
        $(this).addClass("activo");
        $("#btnTabVentas, #btnTabStock").removeClass("activo");
    });

    $("#btnTabVentas").click(function() {
        $("#indicadorCarga").fadeIn().css("z-index", z_index++);
        // Cargar página para ventas y realizar las operaciones necesarias
        $("#tabs").load("tabla_comprobantes.php", function() {
            $("#indicadorCarga").fadeOut();
        });
        $(this).addClass("activo");
        $("#btnTabFavoritos, #btnTabStock").removeClass("activo");
    });

    $("#btnTabStock").click(function() {
        $("#indicadorCarga").fadeIn().css("z-index", z_index++);

        $(this).addClass("activo");
        $("#btnTabFavoritos, #btnTabVentas").removeClass("activo");
        $("#tabs").load("tabla_stocks.php", function() {
            $("#indicadorCarga").fadeOut();
        });
    });

    $("#btnlistavendedores").click(function() {


        // Cargar los valores en la tabla tablaVendedores
        $.ajax({
            url: "ajax/vendedores/list_select.php",
            dataType: "json",
            success: function(data) {
                var tablaVendedores = $("#tablaVendedores tbody");
                tablaVendedores.empty();

                // Recorrer los datos y agregar filas a la tabla
                data.forEach(function(vendedor) {
                    var fila = $("<tr>");
                    var boton = $("<button>")
                        .text(vendedor.text)
                        .attr("data-id", vendedor.id)
                        .addClass("btn-vendedor");

                    var columnaBoton = $("<td>").append(boton);
                    fila.append(columnaBoton);

                    tablaVendedores.append(fila);
                });

                //destroy datatable if exist
                if ($.fn.DataTable.isDataTable("#tablaVendedores")) {
                    $("#tablaVendedores").DataTable().destroy();
                }
                //crear datatable de vendedores , no se puede buscar es una pagina, tiene scroll verticat  y no tiene paginacion
                $("#tablaVendedores").DataTable({
                    paging: false,
                    searching: false,
                    info: false,
                    scrollY: "600px",
                    scrollCollapse: true,
                });

                // Agregar evento click a los botones de vendedor
                $(".btn-vendedor").click(function() {
                    var id = $(this).attr("data-id");
                    var text = $(this).text();
                    $("#vendedor_nombre").html(text);
                    selectedVendedor = id;
                    updateProductList(); // Actualizar la tabla de productos
                    updatePromotionList(); // Actualizar la lista de promociones
                    calculateSubtotal();

                    // Cerrar el modal de lista de vendedores
                    $("#lista-vendedores-modal").fadeOut();
                });
                $("#lista-vendedores-modal").fadeIn().css("z-index", z_index++);
            },
            error: function() {
                console.log("Error al cargar los vendedores");
            },
        });
    });
    $("#btnpromociones").click(function() {
        if (products[selectedVendedor].length > 0) {
            $.blockUI({ message: "<h1>Cargando promociones...</h1>" });
            $("#lista-promociones-modal").fadeIn().css("z-index", z_index++);
            // Cargar los valores en la tabla tablapromociones
            $.ajax({
                url: "ajax/promociones/list.php",
                dataType: "json",
                success: function(data) {
                    var tablapromociones = $("#tablapromociones tbody");
                    tablapromociones.html("");

                    // Recorrer los datos y agregar filas a la tabla
                    data.forEach(function(promo) {
                        var fila = $("<tr>");
                        var boton = $("<button>")
                            .text(promo.nombre)
                            .attr("data-id", promo.id)
                            .attr("data-porcentaje", promo.porcentaje)
                            .addClass("btn-promocion");

                        var columnaBoton = $("<td style='text-align: center;'>").append(
                            boton
                        );
                        fila.append(columnaBoton);

                        tablapromociones.append(fila);
                    });

                    //destroy datatable if exist
                    if ($.fn.DataTable.isDataTable("#tablapromociones")) {
                        $("#tablapromociones").DataTable().destroy();
                    }
                    //crear datatable de promociones , no se puede buscar es una pagina, tiene scroll verticat  y no tiene paginacion
                    $("#tablapromociones").DataTable({
                        paging: false,
                        searching: false,
                        info: false,
                        scrollY: "600px",
                        scrollCollapse: true,
                    });

                    //desbloquear la pantalla
                    $.unblockUI();
                },
                error: function() {
                    $.unblockUI();
                    console.log("Error al cargar los promociones");
                },
            });
        } else {
            mostrarNotificacion(
                "Cobrar",
                "error",
                "No hay productos agregados para cobrar."
            );
        }
    });
    $(document).on("click", ".btn-promocion", function() {
        var id = $(this).attr("data-id");
        var text = $(this).text();
        var porcentaje = $(this).attr("data-porcentaje");
        addPromotion(id, text, porcentaje);
        // Cerrar el modal de lista de promociones
        $("#lista-promociones-modal").fadeOut();
    });

    $(document).on("click", "#btn-menu", function() {
        $("#menu-modal").fadeIn().css("z-index", z_index++);
    });
    document.addEventListener("keydown", function(event) {
        // Verificar si se presiona la tecla Control (Ctrl)
        if (event.ctrlKey) {
            switch (event.which) {

                case 121: // Tecla F10

                    if (products[selectedVendedor].length > 0) {
                        if (venta_rapida == "0") {

                            $("#cobrar-modal").fadeIn().css("z-index", z_index++);
                            $("#tipo_comprobante_id").val(3);
                        } else {
                            ventaRapidaPedido();
                        }

                    } else {
                        mostrarNotificacion(
                            "Cobrar",
                            "error",
                            "No hay productos agregados para cobrar."
                        );
                    }
                    break;

            }
        } else {
            // Otras teclas sin Ctrl
            switch (event.which) {
                case 27: // Tecla de Escape
                    $(".custom-modal").fadeOut();
                    break;
                case 113: // Tecla F2
                    $("#crear-gasto-modal").fadeIn().css("z-index", z_index++);
                    break;
                case 115: // Tecla F4
                    clearProductList();
                    break;
                case 117:
                    // Tecla F6
                    if (products[selectedVendedor].length > 0) {
                        $.blockUI({ message: "<h1>Cargando promociones...</h1>" });
                        $("#lista-promociones-modal").fadeIn().css("z-index", z_index++);
                        // Cargar los valores en la tabla tablapromociones
                        $.ajax({
                            url: "ajax/promociones/list_select.php",
                            dataType: "json",
                            success: function(data) {
                                var tablapromociones = $("#tablapromociones tbody");
                                tablapromociones.html("");

                                // Recorrer los datos y agregar filas a la tabla
                                data.forEach(function(promo) {
                                    var fila = $("<tr>");
                                    var boton = $("<button>")
                                        .text(promo.text)
                                        .attr("data-id", promo.id)
                                        .attr("data-id", promo.id)
                                        .attr("data-porcentaje", promo.porcentaje)
                                        .addClass("btn-promocion");

                                    var columnaBoton = $(
                                        "<td style='text-align: center;'>"
                                    ).append(boton);
                                    fila.append(columnaBoton);

                                    tablapromociones.append(fila);
                                });

                                //destroy datatable if exist
                                if ($.fn.DataTable.isDataTable("#tablapromociones")) {
                                    $("#tablapromociones").DataTable().destroy();
                                }
                                //crear datatable de promociones , no se puede buscar es una pagina, tiene scroll verticat  y no tiene paginacion
                                $("#tablapromociones").DataTable({
                                    paging: false,
                                    searching: false,
                                    info: false,
                                    scrollY: "600px",
                                    scrollCollapse: true,
                                });

                                //desbloquear la pantalla
                                $.unblockUI();
                            },
                            error: function() {
                                $.unblockUI();
                                console.log("Error al cargar los promociones");
                            },
                        });
                    } else {
                        mostrarNotificacion(
                            "Cobrar",
                            "error",
                            "No hay productos agregados para cobrar."
                        );
                    }
                    break;
                case 118: // Tecla F7
                    $("#lista-precio-modal").fadeIn().css("z-index", z_index++);
                    $("#custom-search-input").val("");
                    $("#custom-search-input").focus();
                    $("#tablaProductosPrecios").DataTable().search("").draw();
                    break;
                case 119: // Tecla F8


                    // Cargar los valores en la tabla tablaVendedores
                    $.ajax({
                        url: "ajax/vendedores/list_select.php",
                        dataType: "json",
                        success: function(data) {
                            var tablaVendedores = $("#tablaVendedores tbody");
                            tablaVendedores.empty();

                            // Recorrer los datos y agregar filas a la tabla
                            data.forEach(function(vendedor) {
                                var fila = $("<tr>");
                                var boton = $("<button>")
                                    .text(vendedor.text)
                                    .attr("data-id", vendedor.id)
                                    .addClass("btn-vendedor");

                                var columnaBoton = $("<td>").append(boton);
                                fila.append(columnaBoton);

                                tablaVendedores.append(fila);
                            });

                            //destroy datatable if exist
                            if ($.fn.DataTable.isDataTable("#tablaVendedores")) {
                                $("#tablaVendedores").DataTable().destroy();
                            }
                            //crear datatable de vendedores , no se puede buscar es una pagina, tiene scroll verticat  y no tiene paginacion
                            $("#tablaVendedores").DataTable({
                                paging: false,
                                searching: false,
                                info: false,
                                scrollY: "600px",
                                scrollCollapse: true,
                            });

                            // Agregar evento click a los botones de vendedor
                            $(".btn-vendedor").click(function() {
                                var id = $(this).attr("data-id");
                                var text = $(this).text();
                                $("#vendedor_nombre").html(text);
                                selectedVendedor = id;
                                updateProductList(); // Actualizar la tabla de productos
                                updatePromotionList(); // Actualizar la lista de promociones
                                calculateSubtotal();

                                // Cerrar el modal de lista de vendedores
                                $("#lista-vendedores-modal").fadeOut();
                            });
                            $("#lista-vendedores-modal").fadeIn().css("z-index", z_index++);
                        },
                        error: function() {
                            console.log("Error al cargar los vendedores");
                        },
                    });
                    break;

                case 120: // Tecla F9
                    $("#cierre-caja-modal").fadeIn().css("z-index", z_index++);
                    break;

                case 121: // Tecla F10
                    if (tipo_iva_id != 4) {
                        if (products[selectedVendedor].length > 0) {
                            $("#cobrar-modal").fadeIn().css("z-index", z_index++);
                            $("#tipo_comprobante_id").val(1);
                        } else {
                            mostrarNotificacion(
                                "Cobrar",
                                "error",
                                "No hay productos agregados para cobrar."
                            );
                        }
                        break;
                    }
            }
        }
    });

    $('[id^="btn-menu-"]').on("click", function(e) {
        e.preventDefault(); // Prevenir comportamiento por defecto del enlace

        // Obtener el ID del menú correspondiente al botón clicado
        var menuId = $(this).attr("id").replace("btn-", "");

        // Cerrar cualquier menú abierto
        $('[id^="menu-"]').fadeOut();

        // Abrir el menú correspondiente
        $("#" + menuId + "-modal").fadeIn().css("z-index", z_index++);
    });

    //configuracion Tipos de documentos
    $("#btn-menu-configuracion-tipos-documentos").click(function() {


        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarTipoDocumento")) {
            $("#tablaEditarTipoDocumento").DataTable().destroy();
        }
        $("#lista-tipo-documentos-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarTipoDocumento").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/tipos_documento/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarTipoDocumento_processing").hide();
                            $('#tablaEditarTipoDocumento').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarTipoDocumento').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "nombre" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-tipo-documento-input").on("change", function() {
                $("#tablaEditarTipoDocumento").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarTipoDocumento_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarTipoDocumento').on('click', '.btn-seleccionar-tipo-documento', function() {
        //$("#lista-tipo-documentos-editar-modal").fadeOut();
        var id = $(this).attr("data-id");
        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/tipos_documento/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var tipoDocumento = response[0];
                $("#editar-tipo-documentos-modal #id").val(tipoDocumento.id);
                $("#editar-tipo-documentos-modal #nombre").val(tipoDocumento.nombre);
                $.unblockUI();
                //ocultar btn-crear-tipo-documento y mostrar btn-editar-tipo-documento
                $("#btn-crear-tipo-documento").hide();
                $("#btn-editar-tipo-documento").show();

                $("#editar-tipo-documentos-modal").fadeIn().css("z-index", z_index++);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-tipo-documento').on('click', function() {

        var id = $("#editar-tipo-documentos-modal #id").val();
        var nombre = $("#editar-tipo-documentos-modal #nombre").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/tipos_documento/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-tipo-documentos-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarTipoDocumento").DataTable().ajax.reload();

                } else {
                    $("#editar-tipo-documentos-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#btn-crear-tipo-documento").hide();
        $("#btn-editar-tipo-documento").show();
        $("#editar-tipo-documentos-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarTipoDocumento').on('click', '.btn-eliminar-tipo-documento', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "tipos_documento", id_eliminar);
    });

    $('#lista-tipo-documentos-editar-modal').on('click', '#btn-nuevo-tipo-documento', function() {
        $("#lista-tipo-documentos-editar-modal").fadeOut();
        //ocultar btn-editar-tipo-documento y mostrar btn-crear-tipo-documento
        $("#btn-crear-tipo-documento").show();
        $("#btn-editar-tipo-documento").hide();

        $("#editar-tipo-documentos-modal").fadeIn().css("z-index", z_index++);
    });


    $('#editar-tipo-documentos-modal').on('click', '#btn-crear-tipo-documento', function() {
        var nombre = $("#editar-tipo-documentos-modal #nombre").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/tipos_documento/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-tipo-documentos-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#editar-tipo-documentos-modal #nombre").val('');
                } else {
                    $("#editar-tipo-documentos-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });


    //usuarios

    $("#btn-menu-usuarios-listar , #btn-menu-usuarios-editar , #btn-menu-usuarios-eliminar").click(function() {
        var btn = $(this);
        // Verificar si la tabla ya está inicializada
        if ($.fn.DataTable.isDataTable("#tablaUsuarios")) {
            $("#tablaUsuarios").DataTable().destroy();
        }

        $("#listar-usuarios-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaUsuarios").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "./ajax/usuarios/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaUsuarios_processing").hide();
                            $('#tablaUsuarios').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaUsuarios').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [
                    { data: "nombre_usuario" },
                    { data: "nombre_completo" },
                    { data: "rol" },
                    { data: "activo" },
                    { data: "acciones" },


                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar usuarios...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada

            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-usuarios-input").on("change", function() {
                $("#tablaUsuarios").DataTable().search($(this).val()).draw();
            });
            $("#tablaUsuarios_filter").hide();
            //asignar evento para eliminar producto
            $(document).on("click", ".btn-eliminar-usuario", function() {
                var id_eliminar = $(this).attr("data-id");
                // Llamar a la función mostrarNotificacion con los parámetros deseados
                mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "productos", id_eliminar);
            });
            $(document).on("click", "#btn-agregar-usuario", function() {
                //$("#listar-usuarios-modal").fadeOut();
                //cargar el select de roles
                $.ajax({
                    url: "ajax/roles/list_select.php",
                    dataType: "json",
                    success: function(data) {
                        var selectRoles = $("#editar-usuario-modal #rol_id");
                        selectRoles.empty();
                        var option = $("<option>")
                            .val("")
                            .text("Seleccione un rol");
                        selectRoles.append(option);
                        data.forEach(function(rol) {
                            var option = $("<option>")
                                .val(rol.id)
                                .text(rol.text);
                            selectRoles.append(option);
                        });
                        $("#mensajeAlertaCrearUsuario").hide();
                        //ocultar btn-editar-usuario y mostrar btn-crear-usuario
                        $("#btn-crear-usuario").show();
                        $("#btn-editar-usuario").hide();
                        $("#editar-usuario-modal").fadeIn().css("z-index", z_index++);
                    },
                    error: function() {

                        console.log("Error al cargar los roles");
                    },
                });

                $("#btn-crear-usuario").click(function() {
                    var nombre_usuario = $("#editar-usuario-modal #nombre_usuario").val();
                    var nombre_completo = $("#editar-usuario-modal #nombre_completo").val();
                    var password = $("#editar-usuario-modal #password").val();
                    var rol_id = $("#editar-usuario-modal #rol_id").val();
                    var activo = 1;
                    //comprobar si los campos estan vacios y si las contraseñas no coinciden
                    if (nombre_usuario == "" || nombre_completo == "" || password == "" || rol_id == "") {
                        $("#mensajeAlertaCrearUsuario").show();
                        $("#mensajeAlertaCrearUsuario").text("Todos los campos son obligatorios");
                        return;

                    }
                    if (password.length < 6) {
                        $("#mensajeAlertaCrearUsuario").show();
                        $("#mensajeAlertaCrearUsuario").text("La contraseña debe tener al menos 6 caracteres");
                        return;
                    }
                    if (password != $("#editar-usuario-modal #password2").val()) {
                        $("#mensajeAlertaCrearUsuario").show();
                        $("#mensajeAlertaCrearUsuario").text("Las contraseñas no coinciden");
                        return;
                    }

                    $.blockUI({ message: "<h1>Guardando...</h1>" });
                    $.ajax({
                        url: "ajax/usuarios/add.php",
                        type: "POST",
                        data: {
                            "nombre_usuario": nombre_usuario,
                            "nombre_completo": nombre_completo,
                            "password": password,
                            "rol_id": rol_id,
                            "activo": activo,
                        },
                        success: function(response) {
                            var responseObject = JSON.parse(response);
                            if (responseObject.status === 201) {
                                $("#editar-usuario-modal").fadeOut();
                                mostrarNotificacion(
                                    "Crear Usuario",
                                    "exito",
                                    responseObject.status_message
                                );
                            } else {
                                $("#editar-usuario-modal").append(
                                    '<div class="alert alert-danger">' +
                                    responseObject.status_message +
                                    "</div>"
                                );
                            }
                            $.unblockUI();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $.unblockUI();
                        },
                    });

                });
            });

            //editar usuario
            $(document).on("click", ".btn-editar-usuario", function() {
                var id = $(this).attr("data-id");
                var selectRoles = $("#editar-usuario-modal #rol_id");
                selectRoles.empty();
                $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
                $.ajax({
                    url: "ajax/usuarios/list.php",
                    type: "GET",
                    data: {
                        "param": id,
                    },
                    success: function(response) {
                        var usuario = response[0];
                        var option = $("<option>")
                            .val(usuario.rol_id)
                            .text(usuario.rol_nombre);
                        selectRoles.append(option);
                        $.ajax({
                            url: "ajax/roles/list_select.php",
                            dataType: "json",
                            success: function(data) {
                                data.forEach(function(rol) {
                                    var option = $("<option>")
                                        .val(rol.id)
                                        .text(rol.text);
                                    selectRoles.append(option);
                                });
                            },
                            error: function() {
                                console.log("Error al cargar los roles");
                            },
                        });
                        $("#editar-usuario-modal #id").val(usuario.id);
                        $("#editar-usuario-modal #nombre_usuario").val(usuario.nombre_usuario);
                        $("#editar-usuario-modal #nombre_completo").val(usuario.nombre_completo);
                        $("#editar-usuario-modal #rol_id").val(usuario.rol_id);
                        $.unblockUI();
                        $("#mensajeAlertaEditarUsuario").hide();
                        //ocultar btn-crear-usuario y mostrar btn-editar-usuario
                        $("#btn-crear-usuario").hide();
                        $("#btn-editar-usuario").show();
                        $("#editar-usuario-modal").fadeIn().css("z-index", z_index++);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $.unblockUI();
                    },
                });

                //editar usuario
                $("#btn-editar-usuario").click(function() {
                    var id = $("#editar-usuario-modal #id").val();
                    var nombre_usuario = $("#editar-usuario-modal #nombre_usuario").val();
                    var nombre_completo = $("#editar-usuario-modal #nombre_completo").val();
                    var rol_id = $("#editar-usuario-modal #rol_id").val();

                    //comprobar si los campos estan vacios y si las contraseñas no coinciden
                    if (nombre_usuario == "" || nombre_completo == "" || rol_id == "") {
                        $("#mensajeAlertaEditarUsuario").show();
                        $("#mensajeAlertaEditarUsuario").text("Todos los campos son obligatorios");
                        return;
                    }
                    if (password.length < 6) {
                        $("#mensajeAlertaEditarUsuario").show();
                        $("#mensajeAlertaEditarUsuario").text("La contraseña debe tener al menos 6 caracteres");
                        return;
                    }

                    if (password != $("#editar-usuario-modal #password2").val()) {
                        $("#mensajeAlertaEditarUsuario").show();
                        $("#mensajeAlertaEditarUsuario").text("Las contraseñas no coinciden");
                        return;
                    }
                    $.blockUI({ message: "<h1>Guardando...</h1>" });
                    $.ajax({
                        url: "ajax/usuarios/edit.php",
                        type: "POST",
                        data: {
                            "id": id,
                            "nombre_usuario": nombre_usuario,
                            "nombre_completo": nombre_completo,
                            "rol_id": rol_id,
                        },
                        success: function(response) {
                            var responseObject = JSON.parse(response);
                            if (responseObject.status === 201) {
                                $("#editar-usuario-modal").fadeOut();
                                mostrarNotificacion(
                                    "Editar Usuario",
                                    "exito",
                                    responseObject.status_message
                                );
                                $("#tablaUsuarios").DataTable().ajax.reload();
                            } else {
                                $("#editar-usuario-modal").append(
                                    '<div class="alert alert-danger">' +
                                    responseObject.status_message +
                                    "</div>"
                                );
                            }
                            $.unblockUI();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $.unblockUI();
                        },
                    });

                });
            });



        } catch (error) {}

        $("#custom-search-editar-usuarios-input").val("");
        $("#custom-search-editar-usuarios-input").focus();
        $("#tablaUsuarios").DataTable().search("").draw();
    });




    //tipo IVA
    //configuracion Tipos de IVA
    $("#btn-menu-configuracion-tipos-iva").click(function() {
        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarTipoIva")) {
            $("#tablaEditarTipoIva").DataTable().destroy();
        }
        $("#lista-tipos-iva-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarTipoIva").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/tipos_iva/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarTipoIva_processing").hide();
                            $('#tablaEditarTipoIva').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarTipoIva').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "nombre" },
                    { data: "letra_factura" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo IVA...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-tipos-iva-input").on("change", function() {
                $("#tablaEditarTipoIva").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarTipoIva_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarTipoIva').on('click', '.btn-seleccionar-tipos-iva', function() {
        $("#lista-tipos-iva-editar-modal").fadeOut();
        var id = $(this).attr("data-id");
        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/tipos_iva/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var tipoIva = response[0];
                $("#editar-tipos-iva-modal #id").val(tipoIva.id);
                $("#editar-tipos-iva-modal #nombre").val(tipoIva.nombre);
                $("#editar-tipos-iva-modal #letra_factura").val(tipoIva.letra_factura);
                //ocultar btn-crear-tipos-iva y mostrar btn-editar-tipos-iva
                $("#btn-crear-tipos-iva").hide();
                $("#btn-editar-tipos-iva").show();
                $("#editar-tipos-iva-modal").fadeIn().css("z-index", z_index++);
                $.unblockUI();
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-tipos-iva').on('click', function() {


        var id = $("#editar-tipos-iva-modal #id").val();
        var nombre = $("#editar-tipos-iva-modal #nombre").val();
        var letra_factura = $("#editar-tipos-iva-modal #letra_factura").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/tipos_iva/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
                "letra_factura": letra_factura,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-tipos-iva-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar Tipo de IVA",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarTipoIva").DataTable().ajax.reload();

                } else {
                    $("#editar-tipos-iva-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#editar-tipos-iva-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarTipoIva').on('click', '.btn-eliminar-tipos-iva', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "tipos_iva", id_eliminar);
    });

    $('#lista-tipos-iva-editar-modal').on('click', '#btn-nuevo-tipos-iva', function() {
        //ocultar btn-editar-tipos-iva y mostrar btn-crear-tipos-iva
        $("#btn-crear-tipos-iva").show();
        $("#btn-editar-tipos-iva").hide();
        $("#editar-tipos-iva-modal").fadeIn().css("z-index", z_index++);
    });

    $('#editar-tipos-iva-modal').on('click', '#btn-crear-tipos-iva', function() {
        var nombre = $("#editar-tipos-iva-modal #nombre").val();
        var letra_factura = $("#editar-tipos-iva-modal #letra_factura").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/tipos_iva/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
                "letra_factura": letra_factura,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-tipos-iva-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#editar-tipos-iva-modal #nombre").val('');
                    $("#editar-tipos-iva-modal #letra_factura").val('');

                } else {
                    $("#editar-tipos-iva-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });

    //configuracion Unidades
    $("#btn-menu-configuracion-unidades").click(function() {


        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarUnidad")) {
            $("#tablaEditarUnidad").DataTable().destroy();
        }
        $("#lista-unidad-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarUnidad").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/unidades/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarUnidad_processing").hide();
                            $('#tablaEditarUnidad').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarUnidad').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "nombre" },
                    { data: "simbolo" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-unidad-input").on("change", function() {
                $("#tablaEditarUnidad").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarUnidad_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarUnidad').on('click', '.btn-seleccionar-unidad', function() {
        //ocultar btn-crear-unidad y mostrar btn-editar-unidad
        $("#btn-crear-unidad").hide();
        $("#btn-editar-unidad").show();
        var id = $(this).attr("data-id");
        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/unidades/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var unidad = response[0];
                $("#editar-unidad-modal #id").val(unidad.id);
                $("#editar-unidad-modal #nombre").val(unidad.nombre);
                $("#editar-unidad-modal #simbolo").val(unidad.simbolo);
                $.unblockUI();
                $("#editar-unidad-modal").fadeIn().css("z-index", z_index++);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-unidad').on('click', function() {

        var id = $("#editar-unidad-modal #id").val();
        var nombre = $("#editar-unidad-modal #nombre").val();
        var simbolo = $("#editar-unidad-modal #simbolo").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/unidades/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
                "simbolo": simbolo,

            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-unidad-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar unidad",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarUnidad").DataTable().ajax.reload();

                } else {
                    $("#editar-unidad-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#editar-unidad-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarUnidad').on('click', '.btn-eliminar-unidad', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "unidades", id_eliminar);
    });

    $('#lista-unidad-editar-modal').on('click', '#btn-nuevo-unidad', function() {
        //$("#lista-unidad-editar-modal").fadeOut();
        //ocultar btn-editar-unidad y mostrar btn-crear-unidad
        $("#btn-crear-unidad").show();
        $("#btn-editar-unidad").hide();
        $("#editar-unidad-modal").fadeIn().css("z-index", z_index++);
    });


    $('#editar-unidad-modal').on('click', '#btn-crear-unidad', function() {
        var nombre = $("#editar-unidad-modal #nombre").val();
        var simbolo = $("#editar-unidad-modal #simbolo").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/unidades/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
                "simbolo": simbolo,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-unidad-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#editar-unidad-modal #nombre").val('');
                    $("#editar-unidad-modal #simbolo").val('');
                } else {
                    $("#editar-unidad-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });

    //configuracion Familias
    $("#btn-menu-configuracion-familias").click(function() {


        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarFamilia")) {
            $("#tablaEditarFamilia").DataTable().destroy();
        }
        $("#lista-familia-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarFamilia").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/familias/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            $("#tablaEditarFamilia_processing").hide();
                            $('#tablaEditarFamilia').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarFamilia').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "numero" },
                    { data: "nombre" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-familia-input").on("change", function() {
                $("#tablaEditarFamilia").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarFamilia_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarFamilia').on('click', '.btn-seleccionar-familia', function() {
        //$("#lista-familia-editar-modal").fadeOut();
        var id = $(this).attr("data-id");
        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/familias/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var familia = response[0];
                $("#editar-familia-modal #id").val(familia.id);
                $("#editar-familia-modal #nombre").val(familia.nombre);
                $("#editar-familia-modal #numero").val(familia.numero);
                $.unblockUI();
                //ocultar btn-crear-familia y mostrar btn-editar-familia
                $("#btn-crear-familia").hide();
                $("#btn-editar-familia").show();
                $("#editar-familia-modal").fadeIn().css("z-index", z_index++);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-familia').on('click', function() {

        var id = $("#editar-familia-modal #id").val();
        var nombre = $("#editar-familia-modal #nombre").val();
        var numero = $("#editar-familia-modal #numero").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/familias/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
                "numero": numero,

            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-familia-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar familia",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarFamilia").DataTable().ajax.reload();

                } else {
                    $("#editar-familia-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#editar-familia-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarFamilia').on('click', '.btn-eliminar-familia', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "familias", id_eliminar);
    });

    $('#lista-familia-editar-modal').on('click', '#btn-nuevo-familia', function() {
        //ocultar btn-editar-familia y mostrar btn-crear-familia
        $("#btn-crear-familia").show();
        $("#btn-editar-familia").hide();
        $("#editar-familia-modal").fadeIn().css("z-index", z_index++);
    });

    $('#editar-familia-modal').on('click', '#btn-crear-familia', function() {
        var nombre = $("#editar-familia-modal #nombre").val();
        var numero = $("#editar-familia-modal #numero").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/familias/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
                "numero": numero,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-familia-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#editar-familia-modal #nombre").val('');
                    $("#editar-familia-modal #numero").val('');
                } else {
                    $("#editar-familia-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });


    //configuracion agrupaciones
    $("#btn-menu-configuracion-agrupaciones").click(function() {
        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarAgrupacion")) {
            $("#tablaEditarAgrupacion").DataTable().destroy();
        }
        $("#lista-agrupacion-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarAgrupacion").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/agrupaciones/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarAgrupacion_processing").hide();
                            $('#tablaEditarAgrupacion').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarAgrupacion').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "nombre" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-agrupacion-input").on("change", function() {
                $("#tablaEditarAgrupacion").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarAgrupacion_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarAgrupacion').on('click', '.btn-seleccionar-agrupacion', function() {
        //$("#lista-agrupacion-editar-modal").fadeOut();
        var id = $(this).attr("data-id");

        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/agrupaciones/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var agrupacion = response[0];
                $("#editar-agrupacion-modal #id").val(agrupacion.id);
                $("#editar-agrupacion-modal #nombre").val(agrupacion.nombre);
                $("#editar-agrupacion-modal #color").val(agrupacion.color);
                $.unblockUI();
                //ocultar btn-crear-agrupacion y mostrar btn-editar-agrupacion
                $("#btn-crear-agrupacion").hide();
                $("#btn-editar-agrupacion").show();
                $("#editar-agrupacion-modal").fadeIn().css("z-index", z_index++);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-agrupacion').on('click', function() {

        var id = $("#editar-agrupacion-modal #id").val();
        var nombre = $("#editar-agrupacion-modal #nombre").val();
        var color = $("#editar-agrupacion-modal #color").val();


        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/agrupaciones/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
                "color": color,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-agrupacion-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar agrupacion",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarAgrupacion").DataTable().ajax.reload();

                } else {
                    $("#editar-agrupacion-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#editar-agrupacion-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarAgrupacion').on('click', '.btn-eliminar-agrupacion', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "agrupaciones", id_eliminar);
    });

    $('#lista-agrupacion-editar-modal').on('click', '#btn-nuevo-agrupacion', function() {
        //ocultar btn-editar-agrupacion y mostrar btn-crear-agrupacion
        $("#btn-crear-agrupacion").show();
        $("#btn-editar-agrupacion").hide();
        $("#editar-agrupacion-modal #nombre").val("");
        $("#editar-agrupacion-modal #color").val("");
        $("#editar-agrupacion-modal").fadeIn().css("z-index", z_index++);
    });

    $('#editar-agrupacion-modal').on('click', '#btn-crear-agrupacion', function() {
        var nombre = $("#editar-agrupacion-modal #nombre").val();
        var color = $("#editar-agrupacion-modal #color").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/agrupaciones/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
                "color": color,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-agrupacion-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#editar-agrupacion-modal #nombre").val('');
                } else {
                    $("#editar-agrupacion-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });

    //configuracion tipos
    $("#btn-menu-configuracion-tipos-productos").click(function() {
        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarTipo")) {
            $("#tablaEditarTipo").DataTable().destroy();
        }
        $("#lista-tipo-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarTipo").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/tipos/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarTipo_processing").hide();
                            $('#tablaEditarTipo').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarTipo').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "numero" },
                    { data: "nombre" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-tipo-input").on("change", function() {
                $("#tablaEditarTipo").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarTipo_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarTipo').on('click', '.btn-seleccionar-tipo', function() {
        //$("#lista-tipo-editar-modal").fadeOut();
        var id = $(this).attr("data-id");
        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/tipos/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var tipo = response[0];
                $("#editar-tipo-modal #id").val(tipo.id);
                $("#editar-tipo-modal #nombre").val(tipo.nombre);
                $("#editar-tipo-modal #numero").val(tipo.numero);
                $.unblockUI();
                //ocultar btn-crear-tipo y mostrar btn-editar-tipo
                $("#btn-crear-tipo").hide();
                $("#btn-editar-tipo").show();
                $("#editar-tipo-modal").fadeIn().css("z-index", z_index++);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-tipo').on('click', function() {

        var id = $("#editar-tipo-modal #id").val();
        var nombre = $("#editar-tipo-modal #nombre").val();
        var numero = $("#editar-tipo-modal #numero").val();

        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/tipos/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
                "numero": numero,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-tipo-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar tipo",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarTipo").DataTable().ajax.reload();

                } else {
                    $("#editar-tipo-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#editar-tipo-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarTipo').on('click', '.btn-eliminar-tipo', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "tipos", id_eliminar);
    });

    $('#lista-tipo-editar-modal').on('click', '#btn-nuevo-tipo', function() {
        //ocultar btn-editar-tipo y mostrar btn-crear-tipo
        $("#btn-crear-tipo").show();
        $("#btn-editar-tipo").hide();
        $("#editar-tipo-modal").fadeIn().css("z-index", z_index++);
    });

    $('#editar-tipo-modal').on('click', '#btn-crear-tipo', function() {
        var nombre = $("#editar-tipo-modal #nombre").val();
        var numero = $("#editar-tipo-modal #numero").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/tipos/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
                "numero": numero,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-tipo-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#editar-tipo-modal #nombre").val('');
                    $("#editar-tipo-modal #numero").val('');
                } else {
                    $("#editar-tipo-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });





    //configuracion formas de pago
    $("#btn-menu-configuracion-forma-pagos").click(function() {
        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaEditarFormaPago")) {
            $("#tablaEditarFormaPago").DataTable().destroy();
        }
        $("#lista-forma-pago-editar-modal").fadeIn().css("z-index", z_index++);
        try {
            $("#tablaEditarFormaPago").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/formas_pagos/list_datatable.php",
                    timeout: 15000,
                    error: function(jqXHR, textStatus, errorThrown) {
                        if (textStatus === "timeout") {

                            console.error("La solicitud ha excedido el tiempo de espera.");
                            $("#tablaEditarFormaPago_processing").hide();
                            $('#tablaEditarFormaPago').closest('.dataTables_wrapper').find('.alert').remove();
                            $('#tablaEditarFormaPago').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                        }
                    },
                    type: "POST",
                },
                columns: [

                    { data: "nombre" },
                    { data: "porcentaje" },
                    {
                        data: "acciones"
                    },

                    // Agrega más columnas según la estructura de tus datos
                ],

                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar forma de pago...", // Placeholder para el nuevo cuadro de búsqueda
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
                pageLength: 10, // Establecer la cantidad de productos por página predeterminada
            });

            // Configurar el evento de búsqueda para el nuevo cuadro de búsqueda
            $("#custom-search-editar-forma-pago-input").on("change", function() {
                $("#tablaEditarFormaPago").DataTable().search($(this).val()).draw();
            });
            $("#tablaEditarFormaPago_filter").hide();
        } catch (error) {}
    });
    //Listar
    $('#tablaEditarFormaPago').on('click', '.btn-seleccionar-forma-pago', function() {
        //$("#lista-forma-pago-editar-modal").fadeOut();
        var id = $(this).attr("data-id");
        $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
        $.ajax({
            url: "ajax/formas_pagos/list.php",
            type: "GET",
            data: {
                "param": id,
            },
            success: function(response) {
                var forma_pago = response[0];
                $("#editar-forma-pago-modal #id").val(forma_pago.id);
                $("#editar-forma-pago-modal #nombre").val(forma_pago.nombre);
                $("#editar-forma-pago-modal #porcentaje").val(forma_pago.porcentaje);
                $.unblockUI();
                $("#editar-forma-pago-modal").fadeIn().css("z-index", z_index++);
            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //Editar
    $('#btn-editar-forma-pago').on('click', function() {

        var id = $("#editar-forma-pago-modal #id").val();
        var nombre = $("#editar-forma-pago-modal #nombre").val();
        var porcentaje = $("#editar-forma-pago-modal #porcentaje").val();

        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/formas_pagos/edit.php",
            type: "POST",
            data: {
                "id": id,
                "nombre": nombre,
                "porcentaje": porcentaje,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#editar-forma-pago-modal").fadeOut();
                    mostrarNotificacion(
                        "Editar tipo",
                        "exito",
                        responseObject.status_message
                    );
                    $("#tablaEditarFormaPago").DataTable().ajax.reload();
                } else {
                    $("#editar-forma-pago-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
        $("#editar-forma-pago-modal").fadeIn().css("z-index", z_index++);

    });
    //borrar
    $('#tablaEditarFormaPago').on('click', '.btn-eliminar-forma-pago', function() {
        var id_eliminar = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "formas_pagos", id_eliminar);
    });

    $('#lista-forma-pago-editar-modal').on('click', '#btn-nuevo-forma-pago', function() {
        //$("#lista-forma-pago-editar-modal").fadeOut();
        $("#crear-forma-pago-modal").fadeIn().css("z-index", z_index++);
    });

    $('#crear-forma-pago-modal').on('click', '#btn-crear-forma-pago', function() {
        var nombre = $("#crear-forma-pago-modal #nombre").val();
        var porcentaje = $("#crear-forma-pago-modal #porcentaje").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/formas_pagos/add.php",
            type: "POST",
            data: {
                "nombre": nombre,
                "porcentaje": porcentaje,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#crear-forma-pago-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#crear-forma-pago-modal #nombre").val('');
                    $("#crear-forma-pago-modal #porcentaje").val('');
                } else {
                    $("#crear-forma-pago-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });


    });
    //gastos
    $("#btngastos").click(function() {
        $("#crear-gasto-modal").fadeIn().css("z-index", z_index++);
    });

    $('#crear-gasto-modal').on('click', '#btn-crear-gasto', function() {
        var descripcion = $("#crear-gasto-modal #descripcion").val();
        var monto = $("#crear-gasto-modal #monto").val();
        var usuario_id = $("#crear-gasto-modal #usuario_id").val();
        $.blockUI({ message: "<h1>Guardando...</h1>" });
        $.ajax({
            url: "ajax/gastos/add.php",
            type: "POST",
            data: {
                "descripcion": descripcion,
                "monto": monto,
                "usuario_id": usuario_id,
            },
            success: function(response) {
                var responseObject = JSON.parse(response);
                if (responseObject.status === 201) {
                    $("#crear-gasto-modal").fadeOut();
                    mostrarNotificacion(
                        "Crear Tipo de Documento",
                        "exito",
                        responseObject.status_message
                    );
                    $("#crear-gasto-modal #descripcion").val('');
                    $("#crear-gasto-modal #monto").val('');

                } else {
                    $("#crear-gasto-modal").append(
                        '<div class="alert alert-danger">' +
                        responseObject.status_message +
                        "</div>"
                    );
                }
                $.unblockUI();

            },
            error: function(xhr, status, error) {
                console.error(error);
                $.unblockUI();
            },
        });
    });

    $("#btn-menu-ventas-listar,#btn-menu-ventas-reimprimir,#btn-menu-ventas-cancelar").click(function() {
        $("#lista-ventas-modal").fadeIn().css("z-index", z_index++);
        var btn = $(this);

        if (btn.attr('id') == 'btn-menu-ventas-reimprimir') {
            $("#btnTabVentas").click();
        }

        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaVentas")) {
            $("#tablaVentas").DataTable().destroy();
        }
        $("#tablaVentas").DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: "./ajax/comprobantes/list_datatable.php",
                timeout: 15000,
                error: function(jqXHR, textStatus, errorThrown) {
                    if (textStatus === "timeout") {
                        console.error("La solicitud ha excedido el tiempo de espera.");
                        $("#tablaVentas_processing").hide();
                        $('#tablaVentas').closest('.dataTables_wrapper').find('.alert').remove();
                        $('#tablaVentas').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                    }
                },
                type: "POST",
            },
            order: [
                [0, "desc"]
            ],
            columnDefs: [{ orderable: false, targets: [1] }],
            columns: [
                { data: "id" },
                { data: "tipo_comprobante" },
                { data: "numero_factura" },
                { data: "cliente_nombre" },
                { data: "fecha" },
                { data: "total" },
                {
                    // Agregar una columna para el botón
                    data: null,

                    render: function(data, type, row, meta) {
                        if (btn.attr('id') == 'btn-menu-ventas-reimprimir') {
                            return (
                                '<a  class="btn-guardar btn-re-imprimir-comprobante" data-id="' + row.id + '" data-tipo="' + row.tipo_comprobante + '"><i class="fas fa-print"></i></a>'
                            );
                        } else if (btn.attr('id') == 'btn-menu-ventas-cancelar' && row.fecha_baja == null && row.tipo_comprobante == 'PDO') {
                            return ('<button class="btn-cancelar btn-anular-venta" data-id="' + row.id + '"><i class="fas fa-ban"></i></button>');
                        } else {
                            return ('');
                        }
                    },
                },

                // Agrega más columnas según la estructura de tus datos
            ],

            language: {
                search: "", // Eliminar el texto de búsqueda predeterminado
                searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
            pageLength: 10, // Establecer la cantidad de productos por página predeterminada
        });
        $("#custom-search-venta-input").on("change", function() {
            $("#tablaVentas").DataTable().search($(this).val()).draw();
        });
        $("#tablaVentas_filter").hide();
    });
    $("#tablaVentas").on("click", ".btn-re-imprimir-comprobante", function() {
        //forzar el clic en btnTabFavoritos

        $("#mensajeReImprimirComoprobante").hide();
        let id = $(this).data("id");
        $("#comprobante-id").val(id);
        $("#tipo_comprobante").val($(this).data("tipo"));

        $('#menu-reimpresion-modal').fadeIn();
        $('#lista-ventas-modal').fadeOut();
    });

    //Ventas
    $('#tablaVentas').on('click', '.btn-anular-venta', function() {
        var id = $(this).attr("data-id");
        // Llamar a la función mostrarNotificacion con los parámetros deseados
        mostrarNotificacion("Confirmar Anulacion", "anular", "¿Estás seguro de que deseas anular esta venta?", "comprobantes", id);

    });

    $("#btn-menu-compras-crear, #btn-menu-modificar-stocks").click(function() {
        //detectar si es el boton de modificar stock
        if ($(this).attr('id') == 'btn-menu-modificar-stocks') {
            //ocutar fecha factura y provvedor
            $("#crear-compra-modal #fecha_div").hide();
            var fecha_hoy = new Date().toISOString().slice(0, 10);
            $("#crear-compra-modal #fecha").val(fecha_hoy);
            $("#crear-compra-modal #proveedor_id_div").hide();
            $("#crear-compra-modal #nro_factura_div").hide();
            $("#crear-compra-modal #nro_factura").val("0");
            $("#titulo_modal_compra").text("Modificar Stock");
            $("#leyenda_ajuste_stock").show();
        } else {
            //mostrar fecha factura y provvedor
            $("#crear-compra-modal #fecha_div").show();
            $("#crear-compra-modal #proveedor_id_div").show();
            $("#crear-compra-modal #nro_factura_div").show();
            $("#titulo_modal_compra").text("Registrar Compra");
            $("#leyenda_ajuste_stock").hide();

        }


        $("#mensajeAlertaCrearCompra").text("");
        var body_table = $("#crear-compra-modal #tablaCompra tbody");
        body_table.empty();
        $("#mensajeAlertaCrearCompra").hide();
        $("#crear-compra-modal").fadeIn().css("z-index", z_index++);
        //crear select proveedores
        $("#crear-compra-modal #proveedor_id").select2({
            ajax: {
                url: "ajax/proveedores/list_select.php",
                dataType: "json",
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data,
                    };
                },
                cache: true,
            },
            placeholder: "Buscar proveedor",
            minimumInputLength: 0,
        });

        $("#custom-search-crear-compra-input").on("keydown", function(event) {
            if (event.keyCode == 13) {
                event.preventDefault();
                //buscar productos
                var codigo = $(this).val();
                $.blockUI({ message: "<h1>Buscando...</h1>" });
                $.ajax({
                    url: "ajax/stocks/list.php",
                    type: "GET",
                    data: {
                        "codigo": codigo,
                        "codigo_barra": codigo,
                        "descripcion": codigo,
                        "sucursal_id": $("#crear-compra-modal #sucursal_id").val(),
                    },
                    success: function(response) {
                        var producto = response[0];
                        if (producto) {
                            if (producto.stock == null) {
                                producto.stock = 0;
                            }
                            //el precio y la cantidad pueden modificarse
                            var row = '<tr data-producto-id="' + producto.id + '" style="background-color: #f9f9f9;border-bottom: 1px solid #ddd;">' +
                                '<td>' + producto.codigo + '</td>' +
                                '<td>' + producto.descripcion + '   <strong>Stock Act:' + producto.stock + '</strong></td>' +
                                '<td><input type="number" class="cantidad" value="1" min="1"></td>' +
                                '<td><input type="number" class="precio" value="' + producto.precio1 + '" min="0"></td>' +
                                '<td><button class="btn-eliminar-producto-compra btn-cancelar" data-id="' + producto.id + '"><i class="fas fa-trash"></i></button></td>' +
                                '</tr>';
                            body_table.append(row);
                            $.unblockUI();
                            $("#mensajeAlertaCrearCompra").text("");
                            $("#mensajeAlertaCrearCompra").hide();
                            return;
                        } else {
                            $("#mensajeAlertaCrearCompra").text("Producto no encontrado");
                            $("#mensajeAlertaCrearCompra").show();
                            $.unblockUI();
                        }



                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $.unblockUI();
                    },
                });
                $("#custom-search-crear-compra-input").val('');
            }
        });

        $('#productos-select').select2({
            ajax: {
                url: 'ajax/productos/list_select.php',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // término de búsqueda
                        page: params.page
                    };
                },
                processResults: function(data, params) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            minimumInputLength: 1

        });

        $('#productos-select').on('select2:select', function(e) {
            var producto = e.params.data;

            // Aquí puedes manejar la lógica para agregar el producto seleccionado a la tabla
            if (producto) {
                var row = '<tr data-producto-id="' + producto.id + '" style="background-color: #f9f9f9;border-bottom: 1px solid #ddd;">' +
                    '<td>' + producto.codigo + '</td>' +
                    '<td>' + producto.descripcion + '   <strong>Stock Act:' + producto.stock + '</strong></td>' +
                    '<td><input type="number" class="cantidad" value="1" min="1"></td>' +
                    '<td><input type="number" class="precio" value="' + producto.precio1 + '" min="0"></td>' +
                    '<td><button class="btn-eliminar-producto-compra btn-cancelar" data-id="' + producto.id + '"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';
                body_table.append(row);
                $("#mensajeAlertaCrearCompra").text("");
                $("#mensajeAlertaCrearCompra").hide();
            }
            //limpiar select
            $('#productos-select').val(null).trigger('change');

        });


    });

    $("#tablaCompra").on('click', '.btn-eliminar-producto-compra', function() {
        $(this).closest('tr').remove();
    });

    $("#crear-compra-modal #btn-crear-compra").click(function() {
        var fecha = $("#crear-compra-modal #fecha").val();
        var sucursal_id = $("#crear-compra-modal #sucursal_id").val();
        var productos = [];
        var nro_factura = $("#crear-compra-modal #nro_factura").val();
        var proveedor_id = $("#crear-compra-modal #proveedor_id").val();
        var procuctos = {};
        if (fecha != "" && nro_factura != "" && sucursal_id != "") {
            if ($("#crear-compra-modal #tablaCompra tbody tr").length > 0) {
                $.blockUI({ message: "<h1>Guardando...</h1>" });
                var rows = $("#crear-compra-modal #tablaCompra tbody tr");
                rows.each(function() {
                    var row = $(this);
                    var producto = {
                        producto_id: row.attr("data-producto-id"),
                        cantidad: row.find(".cantidad").val(),
                        precio_costo: row.find(".precio").val(),
                        fecha: fecha,
                        sucursal_id: sucursal_id,
                        nro_factura: nro_factura,
                    };
                    if (proveedor_id != null) {
                        producto.proveedor_id = proveedor_id;
                    }
                    productos.push(producto);
                });

                //recorro productos
                productos.forEach(producto => {
                    var data = {};
                    data.producto_id = producto.producto_id;
                    data.cantidad = producto.cantidad;
                    data.precio_costo = producto.precio_costo;
                    data.fecha = producto.fecha;
                    data.sucursal_id = producto.sucursal_id;
                    data.nro_factura = producto.nro_factura;
                    if (proveedor_id != null) {
                        data.proveedor_id = proveedor_id;
                    }
                    $.ajax({
                        url: "ajax/compras/add.php",
                        type: "POST",
                        data: data,
                    });
                });


                $.unblockUI();
                mostrarNotificacion("Crear compra", "exito", "Compra creada con exito");
                //recargar pagina despues que se cierra el mensajeModal
                setTimeout(function() {
                    window.location.reload();
                }, 3000);
            }
        } else {
            mostrarNotificacion("Crear compra", "error", "Complete los campos obligatorios");
        }
    });


    $("#btn-menu-proveedores-listar").click(function() {
        $("#listar-proveedores-modal").fadeIn().css("z-index", z_index++);
        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaProveedores")) {
            $("#tablaProveedores").DataTable().destroy();
        }

        $("#tablaProveedores").DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: "./ajax/proveedores/list_datatable.php",
                timeout: 15000,
                error: function(jqXHR, textStatus, errorThrown) {
                    if (textStatus === "timeout") {
                        $("#tablaProveedores_processing").hide();
                        $('#tablaProveedores').closest('.dataTables_wrapper').find('.alert').remove();
                        $('#tablaProveedores').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                    }
                },
                type: "POST",
            },
            columns: [

                { data: "razon_social" },
                { data: "cuit" },
                { data: "email" },
                { data: "telefono" },
                { data: "direccion" },
                {
                    data: "acciones"
                },

                // Agrega más columnas según la estructura de tus datos
            ],

            language: {
                search: "", // Eliminar el texto de búsqueda predeterminado
                searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
            pageLength: 10, // Establecer la cantidad de productos por página predeterminada
        });
        $("#tablaProveedores_filter").hide();
        $("#custom-search-proveedores-input").on("change", function() {
            $("#tablaProveedores").DataTable().search($(this).val()).draw();
        });

        $('#listar-proveedores-modal').on('click', '#btn-agregar-proveedor', function() {
            $("#crear-proveedor-modal").fadeIn().css("z-index", z_index++);
            $("#mensajeAlertaCrearProveedor").text("");
            $("#mensajeAlertaCrearProveedor").hide();
        });

        $('#crear-proveedor-modal').on('click', "#btn-crear-proveedor", function() {
            //comprobar que todos los campos esten completos
            var razon_social = $("#crear-proveedor-modal #razon_social").val();
            var cuit = $("#crear-proveedor-modal #cuit").val();
            var email = $("#crear-proveedor-modal #email").val();
            var telefono = $("#crear-proveedor-modal #telefono").val();
            var direccion = $("#crear-proveedor-modal #direccion").val();

            if (razon_social != "" && cuit != "" && email != "" && telefono != "" && direccion != "") {
                $.blockUI({ message: "<h1>Guardando...</h1>" });
                $.ajax({
                    url: "ajax/proveedores/add.php",
                    type: "POST",
                    data: {
                        "razon_social": razon_social,
                        "cuit": cuit,
                        "email": email,
                        "telefono": telefono,
                        "direccion": direccion,
                    },
                    success: function(response) {
                        var responseObject = JSON.parse(response);
                        if (responseObject.status === 201) {
                            $("#crear-proveedor-modal").fadeOut();
                            mostrarNotificacion(
                                "Crear Proveedor",
                                "exito",
                                responseObject.status_message
                            );
                            $("#tablaProveedores").DataTable().ajax.reload();
                        } else {
                            $("#mensajeAlertaCrearProveedor").text(responseObject.status_message);
                            $("#mensajeAlertaCrearProveedor").show();
                        }
                        $.unblockUI();

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $.unblockUI();
                    },
                });
            } else {
                $("#mensajeAlertaCrearProveedor").text("Complete los campos obligatorios");
                $("#mensajeAlertaCrearProveedor").show();
            }


        });

        //editar proveedor
        $('#tablaProveedores').on('click', '.btn-seleccionar-proveedor', function() {
            var id = $(this).attr("data-id");
            $("#mensajeAlertaEditarProveedor").text("");
            $("#mensajeAlertaEditarProveedor").hide();
            $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
            $.ajax({
                url: "ajax/proveedores/list.php",
                type: "GET",
                data: {
                    "param": id,
                },
                success: function(response) {
                    var proveedor = response[0];
                    $("#editar-proveedor-modal #id").val(proveedor.id);
                    $("#editar-proveedor-modal #razon_social").val(proveedor.razon_social);
                    $("#editar-proveedor-modal #cuit").val(proveedor.cuit);
                    $("#editar-proveedor-modal #email").val(proveedor.email);
                    $("#editar-proveedor-modal #telefono").val(proveedor.telefono);
                    $("#editar-proveedor-modal #direccion").val(proveedor.direccion);
                    $.unblockUI();
                    $("#editar-proveedor-modal").fadeIn().css("z-index", z_index++);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $.unblockUI();
                },
            });

            //editar
            $('#btn-editar-proveedor').on('click', function() {
                var id = $("#editar-proveedor-modal #id").val();
                var razon_social = $("#editar-proveedor-modal #razon_social").val();
                var cuit = $("#editar-proveedor-modal #cuit").val();
                var email = $("#editar-proveedor-modal #email").val();
                var telefono = $("#editar-proveedor-modal #telefono").val();
                var direccion = $("#editar-proveedor-modal #direccion").val();

                if (razon_social != "" && cuit != "" && email != "" && telefono != "" && direccion != "") {
                    $.blockUI({ message: "<h1>Guardando...</h1>" });
                    $.ajax({
                        url: "ajax/proveedores/edit.php",
                        type: "POST",
                        data: {
                            "id": id,
                            "razon_social": razon_social,
                            "cuit": cuit,
                            "email": email,
                            "telefono": telefono,
                            "direccion": direccion,
                        },
                        success: function(response) {
                            var responseObject = JSON.parse(response);
                            if (responseObject.status === 201) {
                                $("#editar-proveedor-modal").fadeOut();
                                mostrarNotificacion(
                                    "Editar Proveedor",
                                    "exito",
                                    responseObject.status_message
                                );
                                $("#tablaProveedores").DataTable().ajax.reload();
                            } else {
                                $("#mensajeAlertaEditarProveedor").text(responseObject.status_message);
                                $("#mensajeAlertaEditarProveedor").show();
                            }
                            $.unblockUI();

                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $.unblockUI();
                        },
                    });
                } else {
                    $("#mensajeAlertaEditarProveedor").text("Complete los campos obligatorios");
                    $("#mensajeAlertaEditarProveedor").show();
                }

            });

        });

        //borrar
        $('#tablaProveedores').on('click', '.btn-eliminar-proveedor', function() {
            var id_eliminar = $(this).attr("data-id");
            // Llamar a la función mostrarNotificacion con los parámetros deseados
            mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "proveedores", id_eliminar);
        });

    });


    $("#btn-menu-promociones-listar").click(function() {
        $("#listar-promociones-modal").fadeIn().css("z-index", z_index++);
        //destroy datatable if exist
        if ($.fn.DataTable.isDataTable("#tablaPromociones")) {
            $("#tablaPromociones").DataTable().destroy();
        }

        $("#tablaPromociones").DataTable({
            processing: true,
            serverSide: true,
            paging: true,
            ajax: {
                url: "./ajax/promociones/list_datatable.php",
                timeout: 10000,
                error: function(jqXHR, textStatus, errorThrown) {
                    if (textStatus === "timeout") {
                        $("#tablaPromociones_processing").hide();
                        $('#tablaPromociones').closest('.dataTables_wrapper').find('.alert').remove();
                        $('#tablaPromociones').closest('.dataTables_wrapper').append('<div class="alert alert-warning" role="alert">....</div>');
                    }
                },

                type: "POST",
            },
            columns: [

                { data: "nombre" },
                { data: "porcentaje" },

                {
                    data: "acciones"
                },

                // Agrega más columnas según la estructura de tus datos
            ],

            language: {
                search: "", // Eliminar el texto de búsqueda predeterminado
                searchPlaceholder: "Buscar promocion...", // Placeholder para el nuevo cuadro de búsqueda
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
            pageLength: 10, // Establecer la cantidad de productos por página predeterminada
        });
        $("#tablaPromociones_filter").hide();
        $("#custom-search-promociones-input").on("change", function() {
            $("#tablaPromociones").DataTable().search($(this).val()).draw();
        });

        $('#listar-promociones-modal').on('click', '#btn-agregar-promocion', function() {
            //ocultar btn-editar-promocion y mostrar btn-crear-promocion
            $("#btn-crear-promocion").show();
            $("#btn-editar-promocion").hide();
            $("#editar-promocion-modal").fadeIn().css("z-index", z_index++);
            $("#mensajeAlertaCrearPromocion").text("");
            $("#mensajeAlertaCrearPromocion").hide();
        });

        $('#editar-promocion-modal').on('click', "#btn-crear-promocion", function() {
            //comprobar que todos los campos esten completos
            var nombre = $("#editar-promocion-modal #nombre").val();
            var porcentaje = $("#editar-promocion-modal #porcentaje").val();
            if (nombre != "" && porcentaje != "") {
                $.blockUI({ message: "<h1>Guardando...</h1>" });
                $.ajax({
                    url: "ajax/promociones/add.php",
                    type: "POST",
                    data: {
                        "nombre": nombre,
                        "porcentaje": porcentaje,

                    },
                    success: function(response) {
                        var responseObject = JSON.parse(response);
                        if (responseObject.status === 201) {
                            $("#editar-promocion-modal").fadeOut();
                            mostrarNotificacion(
                                "Crear Promocion",
                                "exito",
                                responseObject.status_message
                            );
                            $("#tablaPromociones").DataTable().ajax.reload();
                        } else {
                            $("#mensajeAlertaCrearPromocion").text(responseObject.status_message);
                            $("#mensajeAlertaCrearPromocion").show();
                        }
                        $.unblockUI();

                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $.unblockUI();
                    },
                });
            } else {
                $("#mensajeAlertaCrearPromocion").text("Complete los campos obligatorios");
                $("#mensajeAlertaCrearPromocion").show();
            }


        });

        //editar promocion
        $('#tablaPromociones').on('click', '.btn-seleccionar-promocion', function() {
            var id = $(this).attr("data-id");
            $("#mensajeAlertaEditarPromocion").text("");
            $("#mensajeAlertaEditarPromocion").hide();
            $.blockUI({ message: "<h1>Cargando informacion...</h1>" });
            $.ajax({
                url: "ajax/promociones/list.php",
                type: "GET",
                data: {
                    "param": id,
                },
                success: function(response) {
                    var promocion = response[0];
                    $("#editar-promocion-modal #id").val(promocion.id);
                    $("#editar-promocion-modal #nombre").val(promocion.nombre);
                    $("#editar-promocion-modal #porcentaje").val(promocion.porcentaje);

                    $.unblockUI();
                    //ocultar btn-crear-promocion y mostrar btn-editar-promocion
                    $("#btn-crear-promocion").hide();
                    $("#btn-editar-promocion").show();
                    $("#editar-promocion-modal").fadeIn().css("z-index", z_index++);
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $.unblockUI();
                },
            });

            //editar
            $('#btn-editar-promocion').on('click', function() {
                var id = $("#editar-promocion-modal #id").val();
                var nombre = $("#editar-promocion-modal #nombre").val();
                var porcentaje = $("#editar-promocion-modal #porcentaje").val();



                if (razon_social != "" && cuit != "" && email != "" && telefono != "" && direccion != "") {
                    $.blockUI({ message: "<h1>Guardando...</h1>" });
                    $.ajax({
                        url: "ajax/promociones/edit.php",
                        type: "POST",
                        data: {
                            "id": id,
                            "nombre": nombre,
                            "porcentaje": porcentaje,
                        },

                        success: function(response) {
                            var responseObject = JSON.parse(response);
                            if (responseObject.status === 201) {
                                $("#editar-promocion-modal").fadeOut();
                                mostrarNotificacion(
                                    "Editar Promocion",
                                    "exito",
                                    responseObject.status_message
                                );
                                $("#tablaPromociones").DataTable().ajax.reload();
                            } else {
                                $("#mensajeAlertaEditarPromocion").text(responseObject.status_message);
                                $("#mensajeAlertaEditarPromocion").show();
                            }
                            $.unblockUI();

                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                            $.unblockUI();
                        },
                    });
                } else {
                    $("#mensajeAlertaEditarPromocion").text("Complete los campos obligatorios");
                    $("#mensajeAlertaEditarPromocion").show();
                }

            });

        });

        //borrar
        $('#tablaPromociones').on('click', '.btn-eliminar-promocion', function() {
            var id_eliminar = $(this).attr("data-id");
            // Llamar a la función mostrarNotificacion con los parámetros deseados
            mostrarNotificacion("Confirmar Eliminación", "eliminar", "¿Estás seguro de que deseas eliminar este elemento?", "promociones", id_eliminar);
        });



    });
    $("#btn-menu-configuracion-asignar-punto-venta").click(function() {
        $("#asignar-punto-venta-modal").fadeIn().css("z-index", z_index++);
        $("#mensajeAlertaAsignarPuntoVenta").hide();
        $("#mensajeAlertaAsignarPuntoVenta").text("");
        $("#asignar-punto-venta-modal #punto_venta_id").select2({
            ajax: {
                url: "./ajax/puntos_venta/list_select.php",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: true,
            },
            placeholder: 'Seleccione un punto de venta',
        });

    });
    $("#btn-asignar-punto-venta").click(function() {
        var usuario_id = $("#asignar-punto-venta-modal #usuario_id").val();
        var punto_venta_id = $("#asignar-punto-venta-modal #punto_venta_id").val();
        if (usuario_id != "" && punto_venta_id != "") {
            $.blockUI({ message: "<h1>Guardando...</h1>" });
            $.ajax({
                url: "ajax/usuarios/edit.php",
                type: "POST",
                data: {
                    "id": usuario_id,
                    "punto_venta_id": punto_venta_id,
                },
                success: function(response) {
                    var responseObject = JSON.parse(response);
                    if (responseObject.status === 201) {
                        $("#asignar-punto-venta-modal").fadeOut();
                        mostrarNotificacion(
                            "Asignar Punto de Venta",
                            "exito",
                            "Punto de venta asignado con exito<br>Debe cerrar sesion para que los cambios surtan efecto"
                        );
                    } else {
                        $("#mensajeAlertaAsignarPuntoVenta").text(responseObject.status_message);
                        $("#mensajeAlertaAsignarPuntoVenta").show();
                    }
                    $.unblockUI();

                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $.unblockUI();
                },
            });
        } else {
            $("#mensajeAlertaAsignarPuntoVenta").text("Complete los campos obligatorios");
            $("#mensajeAlertaAsignarPuntoVenta").show();
        }
    });
    clearProductList();
    $('.seleccionarColor').minicolors({

        // animation speed
        animationSpeed: 50,

        // easing function
        animationEasing: 'swing',

        // defers the change event from firing while the user makes a selection
        changeDelay: 0,

        // hue, brightness, saturation, or wheel
        control: 'hue',

        // default color
        defaultValue: '',

        // hex or rgb
        format: 'hex',

        // show/hide speed
        showSpeed: 100,
        hideSpeed: 100,

        // is inline mode?
        inline: false,

        // a comma-separated list of keywords that the control should accept (e.g. inherit, transparent, initial). 
        keywords: '',

        // uppercase or lowercase
        letterCase: 'lowercase',

        // enables opacity slider
        opacity: false,

        // custom position
        position: 'bottom left',

        // additional theme class
        theme: 'default',

        // an array of colors that will show up under the main color <a href="https://www.jqueryscript.net/tags.php?/grid/">grid</a>
        swatches: []

    });


    $("#btn-menu-informes-ventas").click(function() {
        $("#informe-ventas-modal").fadeIn().css("z-index", z_index++);
        //ocultar tabla
        $("#tablaInformeVentas").hide();
        //hide search
        $("#tablaInformeVentas_filter").hide();
        $("#custom-search-informe-ventas-input").on("change", function() {
            $("#tablaInformeVentas").DataTable().search($(this).val()).draw();
        });
        $.ajax({
            url: './ajax/tipos_comprobante/list.php',
            type: 'GET',
            success: function(tipos) {

                let select = document.getElementById('tipo-comprobante');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todos
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todos";
                select.appendChild(option);
                tipos.forEach(tipo => {
                    let option = document.createElement('option');
                    option.value = tipo.id;
                    option.text = tipo.nombre;
                    select.appendChild(option);
                });
            }
        });

        //cargar select2 de clientes
        $.ajax({
            url: './ajax/clientes/list.php',
            type: 'GET',
            success: function(clientes) {

                let select = document.getElementById('clientes');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todos
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todos";
                select.appendChild(option);
                clientes.forEach(cliente => {
                    let option = document.createElement('option');
                    option.value = cliente.id;
                    option.text = cliente.nombre;
                    select.appendChild(option);
                });
            }
        });

        //cargar select2 de vendedores
        $.ajax({
            url: './ajax/vendedores/list.php',
            type: 'GET',
            success: function(vendedores) {

                let select = document.getElementById('vendedores');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todos
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todos";
                select.appendChild(option);
                vendedores.forEach(vendedor => {
                    let option = document.createElement('option');
                    option.value = vendedor.id;
                    option.text = vendedor.nombre;
                    select.appendChild(option);
                });
            }
        });

        //cargar select2 de sucursales
        $.ajax({
            url: './ajax/sucursales/list.php',
            type: 'GET',
            success: function(sucursales) {
                let select = document.getElementById('sucursales');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todas
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todas";
                select.appendChild(option);
                sucursales.forEach(sucursal => {
                    let option = document.createElement('option');
                    option.value = sucursal.id;
                    option.text = sucursal.nombre;
                    select.appendChild(option);
                });
            }
        });

        $("#btn-buscar-informe-ventas").click(function() {
            var fecha_inicio = $("#fecha-inicio").val();
            var fecha_fin = $("#fecha-fin").val();
            var tipo_comprobante = $("#tipo-comprobante").val();
            var cliente = $("#clientes").val();
            var vendedor = $("#vendedores").val();
            var sucursal = $("#sucursales").val();
            //crear un string para la consulta get
            var data_get = "";

            //armar el data solo con los valores que no esten vacios y  si tiene fehca de inicio y fin que la fecha de inicio sea menor a la fecha de fin
            if (fecha_inicio != "" && fecha_fin != "") {
                if (fecha_inicio > fecha_fin) {
                    alert("La fecha de inicio no puede ser mayor a la fecha de fin");
                    return;
                }
            }
            if (tipo_comprobante != "") {
                data_get += "tipo_comprobante_id=" + tipo_comprobante + "&";

            }
            if (cliente != "") {
                data_get += "cliente_id=" + cliente + "&";
            }
            if (vendedor != "") {
                data_get += "vendedor_id=" + vendedor + "&";
            }
            if (sucursal != "") {
                data_get += "sucursal_id=" + sucursal + "&";
            }

            //si la fecha hasta esta vacia le asigno la fecha actual
            if (fecha_inicio != "") {
                if (fecha_fin == "") {
                    var date = new Date();
                    //dar formato yyyy-mm-dd
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    if (day < 10) {
                        day = '0' + day;
                    }
                    if (month < 10) {
                        month = '0' + month;
                    }
                    fecha_fin = year + "-" + month + "-" + day;
                }
                data_get += "fecha_inicio=" + fecha_inicio + "&";
                data_get += "fecha_fin=" + fecha_fin + "&";
            }



            if ($.fn.DataTable.isDataTable("#tablaInformeVentas")) {
                $("#tablaInformeVentas").DataTable().destroy();
            }
            $("#tablaInformeVentas").show();
            $("#tablaInformeVentas").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: "./ajax/informe_ventas/list_datatable.php?" + data_get,
                    type: "POST",
                },
                columns: [
                    { data: "numero_factura", title: "N° Comp." },
                    { data: "tipo_comprobante_id", title: "Tipo Comprobante" },
                    { data: "cliente_id", title: "Cliente" },
                    { data: "tipo_iva_id", title: "Tipo IVA" },
                    { data: "cuit", title: "Cuit" },
                    { data: "fecha", title: "Fecha" },
                    { data: "hora", title: "Hora" },
                    { data: "total", title: "Total" },
                    { data: "importe_iva", title: "Importe IVA" },
                    { data: "vendedor_id", title: "Vendedor" },
                    { data: "sucursal_id", title: "Sucursal" },
                ],
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar tipo documento...", // Placeholder para el nuevo cuadro de búsqueda
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
                    }
                },
                fnDrawCallback: function(settings) {
                    var api = this.api();
                    var resumen = api.ajax.json().resumen;
                    console.log(resumen);

                    // Actualizar el contenido del tfoot
                    $('#tablaInformeVentas tfoot').empty(); // Limpiar el contenido previo
                    $('#tablaInformeVentas tfoot').append(
                        '<tr>' +
                        '<td colspan="7"><b>Total:</b></td>' +
                        '<td><span style="font-weight: bold;color:darkgreen;">$' + resumen.total_ventas + '</span></td>' +
                        '<td><span style="font-weight: bold;color:darkgreen;">$' + resumen.total_iva + '</span></td>' +
                        '<td colspan="2"></td>' +
                        '</tr>'
                    );
                }
            });
            //ocultar search
            $("#tablaInformeVentas_filter").hide();




        });

    });


    $("#btn-menu-informes-compras").click(function() {
        $("#informe-compras-modal").fadeIn().css("z-index", z_index++);
        //ocultar tabla
        $("#tablaInformeCompras").hide();
        //hide search
        $("#tablaInformeCompras_filter").hide();
        $("#custom-search-informe-compras-input").on("change", function() {
            $("#tablaInformeCompras").DataTable().search($(this).val()).draw();
        });


        //cargar select2 de vendedores
        $.ajax({
            url: './ajax/proveedores/list.php',
            type: 'GET',
            success: function(proveedores) {

                let select = document.getElementById('proveedores');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todas
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todos";
                select.appendChild(option);
                proveedores.forEach(proveedor => {
                    let option = document.createElement('option');
                    option.value = proveedor.id;
                    option.text = proveedor.razon_social;
                    select.appendChild(option);
                });
            }
        });

        //cargar select2 de sucursales
        $.ajax({
            url: './ajax/sucursales/list.php',
            type: 'GET',
            success: function(sucursales) {
                let select = document.getElementById('sucursales2');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todas
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todas";
                select.appendChild(option);
                sucursales.forEach(sucursal => {
                    let option = document.createElement('option');
                    option.value = sucursal.id;
                    option.text = sucursal.nombre;
                    select.appendChild(option);
                });
            }
        });

        $("#btn-buscar-informe-compras").click(function() {
            var fecha_inicio = $("#fecha-inicio2").val();
            var fecha_fin = $("#fecha-fin2").val();
            var sucursal = $("#sucursales2").val();
            var proveedor = $("#proveedores").val();
            //crear un string para la consulta get
            var data_get = "";

            //armar el data solo con los valores que no esten vacios y  si tiene fehca de inicio y fin que la fecha de inicio sea menor a la fecha de fin
            if (fecha_inicio != "" && fecha_fin != "") {
                if (fecha_inicio > fecha_fin) {
                    alert("La fecha de inicio no puede ser mayor a la fecha de fin");
                    return;
                }
            }
            if (sucursal != "") {
                data_get += "sucursal_id=" + sucursal + "&";
            }
            if (proveedor != "") {
                data_get += "proveedor_id=" + proveedor + "&";
            }

            //si la fecha hasta esta vacia le asigno la fecha actual
            if (fecha_inicio != "") {
                if (fecha_fin == "") {
                    var date = new Date();
                    //dar formato yyyy-mm-dd
                    var day = date.getDate();
                    var month = date.getMonth() + 1;
                    var year = date.getFullYear();
                    if (day < 10) {
                        day = '0' + day;
                    }
                    if (month < 10) {
                        month = '0' + month;
                    }
                    fecha_fin = year + "-" + month + "-" + day;
                }
                data_get += "fecha_inicio=" + fecha_inicio + "&";
                data_get += "fecha_fin=" + fecha_fin + "&";
            }



            if ($.fn.DataTable.isDataTable("#tablaInformeCompras")) {
                $("#tablaInformeCompras").DataTable().destroy();
            }
            $("#tablaInformeCompras").show();

            $("#tablaInformeCompras").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                autoWidth: true,
                ajax: {
                    url: "./ajax/informe_compras/list_datatable.php?" + data_get,
                    type: "POST",
                },
                columns: [
                    { data: "nro_factura", title: "N° Factura" },
                    { data: "fecha", title: "Fecha" },
                    { data: "codigo", title: "Producto" },
                    { data: "costo", title: "Costo" },
                    { data: "cantidad", title: "Cantidad" },
                    { data: "proveedor_id", title: "Proveedor" },
                    { data: "sucursal_id", title: "Sucursal" },
                ],
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar..", // Placeholder para el nuevo cuadro de búsqueda
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
                    }
                },
            });
            $("#tablaInformeCompras_filter").hide();


        });

    });



    $("#btn-menu-informes-stock").click(function() {
        $("#informe-stock-modal").fadeIn().css("z-index", z_index++);
        //ocultar tabla
        $("#tablaInformeStock").hide();
        //hide search
        $("#tablaInformeStock_filter").hide();
        $("#custom-search-informe-stock-input").on("change", function() {
            $("#tablaInformeStock").DataTable().search($(this).val()).draw();
        });
        $("#codigo2").val("");
        //cargar select2 de sucursales
        $.ajax({
            url: './ajax/sucursales/list.php',
            type: 'GET',
            success: function(sucursales) {
                let select = document.getElementById('sucursales3');
                //vaciar el select
                select.innerHTML = "";
                //agregar la opcion todas
                let option = document.createElement('option');
                option.value = "";
                option.text = "Todas";
                select.appendChild(option);
                sucursales.forEach(sucursal => {
                    let option = document.createElement('option');
                    option.value = sucursal.id;
                    option.text = sucursal.nombre;
                    select.appendChild(option);
                });
            }
        });

        $("#btn-buscar-informe-stock").click(function() {
            var sucursal = $("#sucursales3").val();
            var codigo = $("#codigo2").val();

            //crear un string para la consulta get
            var data_get = "";

            if (sucursal != "") {
                data_get += "sucursal_id=" + sucursal + "&";
            }
            if (codigo != "") {
                data_get += "codigo=" + codigo + "&";
            }

            if ($.fn.DataTable.isDataTable("#tablaInformeStock")) {
                $("#tablaInformeStock").DataTable().destroy();
            }
            $("#tablaInformeStock").show();

            $("#tablaInformeStock").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                autoWidth: true,
                ajax: {
                    url: "./ajax/informe_stock/list_datatable.php?" + data_get,
                    type: "POST",
                },
                columns: [
                    { data: "codigo", title: "Código" },
                    { data: "descripcion", title: "Descripción" },
                    { data: "precio1", title: "Precio 1" },
                    { data: "precio2", title: "Precio 2" },
                    { data: "precio3", title: "Precio 3" },
                    { data: "sucursal_id", title: "Sucursal" },
                    { data: "stock_actual", title: "Stock Actual" },
                    { data: "stock_minimo", title: "Stock Mínimo" },
                    { data: "stock_pedido", title: "Stock Pedido" },

                ],
                //establecer alineacion para las columnas numericas
                columnDefs: [{
                    targets: [0, 2, 3, 4, 6, 7, 8],
                    className: 'dt-body-right'
                }],
                //establecer 2,3,4 como moneda
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(2)').html('$' + data.precio1);
                    $(row).find('td:eq(3)').html('$' + data.precio2);
                    $(row).find('td:eq(4)').html('$' + data.precio3);
                },
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar..", // Placeholder para el nuevo cuadro de búsqueda
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
                    }
                },
            });
            $("#tablaInformeStock_filter").hide();


        });

    });

    $("#btn-menu-informes-libro-iva-ventas").click(function() {
        $("#informe-libro-iva-ventas-modal").fadeIn().css("z-index", z_index++);
        //ocultar tabla
        $("#tablaInformeLibroIvaVentas").hide();
        //hide search
        $("#tablaInformeLibroIvaVentas_filter").hide();
        $("#anio").val("");
        $("#mes").val("");
        $("#btn-buscar-informe-libro-iva-ventas").click(function() {
            var anio = $("#anio").val();
            var mes = $("#mes").val();
            var fecha_inicio = anio + "-" + mes + "-01";
            var fecha_fin = anio + "-" + mes + "-31";

            //crear un string para la consulta get
            var data_get = "";

            data_get += "fecha_inicio=" + fecha_inicio + "&";
            data_get += "fecha_fin=" + fecha_fin + "&";

            if ($.fn.DataTable.isDataTable("#tablaInformeLibroIvaVentas")) {
                $("#tablaInformeLibroIvaVentas").DataTable().destroy();
            }
            $("#tablaInformeLibroIvaVentas").show();

            $("#tablaInformeLibroIvaVentas").DataTable({
                processing: true,
                serverSide: true,
                paging: true,
                autoWidth: true,

                ajax: {
                    url: "./ajax/informe_libro_iva_ventas/list_datatable.php?" + data_get,
                    type: "POST",
                },
                columns: [
                    { data: "dia", title: "Día" },
                    { data: "numero_factura", title: "Número de Factura" },
                    { data: "cuit", title: "CUIT" },
                    { data: "cliente", title: "Cliente" },
                    { data: "ng21", title: "NG.21" },
                    { data: "ng105", title: "NG.10.5" },
                    { data: "ng0", title: "NG.0" },
                    { data: "int", title: "Int." },
                    { data: "iibb", title: "IIBB" },
                    { data: "iva21", title: "IVA 21" },
                    { data: "iva105", title: "IVA 10.5" },
                    { data: "iva0", title: "IVA 0" },
                    { data: "total", title: "Total" },
                ],
                //establecer alineacion para las columnas numericas
                columnDefs: [{
                        orderable: false,
                        targets: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                    },

                    {
                        targets: [4, 5, 6, 7, 8, 9, 10, 11, 12],
                        className: 'dt-body-right'
                    }
                    //quitar todas las columnas dle orden


                ],
                //establecer 2,3,4 como moneda
                createdRow: function(row, data, dataIndex) {
                    $(row).find('td:eq(4)').html('$' + data.ng21);
                    $(row).find('td:eq(5)').html('$' + data.ng105);
                    $(row).find('td:eq(6)').html('$' + data.ng0);
                    $(row).find('td:eq(7)').html('$' + data.int);
                    $(row).find('td:eq(8)').html('$' + data.iibb);
                    $(row).find('td:eq(9)').html('$' + data.iva21);
                    $(row).find('td:eq(10)').html('$' + data.iva105);
                    $(row).find('td:eq(11)').html('$' + data.iva0);
                    $(row).find('td:eq(12)').html('$' + data.total);
                },
                language: {
                    search: "", // Eliminar el texto de búsqueda predeterminado
                    searchPlaceholder: "Buscar..", // Placeholder para el nuevo cuadro de búsqueda
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
                    }
                },
                fnDrawCallback: function(settings) {
                    var api = this.api();
                    var resumen = api.ajax.json().resumen;
                    console.log(resumen);

                    // Actualizar el contenido del tfoot
                    $('#tablaInformeLibroIvaVentas tfoot').empty(); // Limpiar el contenido previo
                    $('#tablaInformeLibroIvaVentas tfoot').append(
                        '<tr>' +
                        '<td colspan="4"><b>Total:</b></td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_ng21 + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_ng105 + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_ng0 + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_int + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iibb + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iva21 + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iva105 + '</td>' +
                        '<td style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total_iva0 + '</td>' +
                        '<td  style="font-weight: bold;color:darkgreen;text-align:right;width:auto;">$' + resumen.total + '</td>' +
                        '</tr>'

                    );
                }
            });
            $("#tablaInformeLibroIvaVentas_filter").hide();


        });

        $("#btn-exportar-informe-libro-iva-ventas").click(function() {
            var anio = $("#anio").val();
            var mes = $("#mes").val();
            var fecha_inicio = anio + "-" + mes + "-01";
            var fecha_fin = anio + "-" + mes + "-31";

            //crear un string para la consulta get
            var data_get = "";

            data_get += "fecha_inicio=" + fecha_inicio + "&";
            data_get += "fecha_fin=" + fecha_fin + "&";

            //envio por ajax los datos a la url ./ajax/informe_libro_iva_ventas/print.php
            $.ajax({
                url: "./ajax/informe_libro_iva_ventas/export.php?" + data_get,
                type: "GET",
                success: function(response) {
                    var responseObject = JSON.parse(response);
                    var pdfUrl = responseObject.pdfUrl;
                    window.open(pdfUrl, '_blank');


                },
                error: function(xhr, status, error) {
                    console.error(error);
                },
            });



        });

        $("#btn-imprimir-informe-libro-iva-ventas").click(function() {
            var anio = $("#anio").val();
            var mes = $("#mes").val();
            var fecha_inicio = anio + "-" + mes + "-01";
            var fecha_fin = anio + "-" + mes + "-31";

            //crear un string para la consulta get
            var data_get = "";

            data_get += "fecha_inicio=" + fecha_inicio + "&";
            data_get += "fecha_fin=" + fecha_fin + "&";

            //envio por ajax los datos a la url ./ajax/informe_libro_iva_ventas/print.php
            $.ajax({
                url: "./ajax/informe_libro_iva_ventas/print.php?" + data_get,
                type: "GET",
                success: function(response) {
                    var responseObject = JSON.parse(response);
                    var pdfUrl = responseObject.pdfUrl;

                    printJS({ printable: pdfUrl, type: "pdf", silent: true });

                },
                error: function(xhr, status, error) {
                    console.error(error);
                },
            });



        });

    });

    if (tipo_iva == 4) {
        $("#btnfacturar").css("background-color", "gray");
    }

    //pagos
    $("#btn-menu-pagos-crear").click(function() {
        $("#crear-pago-modal").fadeIn().css("z-index", z_index++);
        $("#mensajeAlertaCrearPago").hide();
        $("#mensajeAlertaCrearPago").text("");
        //limpiar campos y los select2
        $("#proveedor_pago_id").val(null).trigger('change');
        $("#facturas-select").val(null).trigger('change');
        $("#monto_pago").val("");

        $("#proveedor_pago_id").select2({
            ajax: {
                url: "./ajax/proveedores/list_select.php",
                type: "GET",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
                    };
                },
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: true,
            },
            placeholder: 'Seleccione un proveedor',
        });

        //cargar select2 de facturas-select cuando se seleccione un proveedor
        $("#proveedor_pago_id").on('select2:select', function() {
            //limpiar select2
            $("#facturas-select").val(null).trigger('change');
            $("#facturas-select").select2({
                ajax: {
                    url: "./ajax/facturas/list.php",
                    type: "GET",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            proveedor_id: $("#proveedor_pago_id").val(),
                        };
                    },
                    processResults: function(data) {
                        return {
                            //id:nro_factura, text:nro_factura + " - " + total
                            results: data.map(function(item) {
                                return {
                                    id: item.nro_factura,
                                    text: "N°" + item.nro_factura + " Monto: $" + item.total + " -------> Falta pagar: $" + (item.total - item.pagado),
                                };
                            }),

                        };
                    },
                    cache: true,
                },
                placeholder: 'Seleccione una factura',
            });
        });

        $("#btn-crear-pago").click(function() {
            var proveedor_id = $("#proveedor_pago_id").val();
            var factura = $("#facturas-select").val();
            var monto = $("#monto_pago").val();
            var fecha = $("#fecha_pago").val();
            var nro_comprobante = $("#nro_comprobante").val();
            var tipo_pago_id = $("#tipo_pago_id").val();
            $.blockUI({ message: "<h1>Guardando...</h1>" });

            $.ajax({
                url: "ajax/pagos/add.php",
                type: "POST",
                data: {
                    "proveedor_id": proveedor_id,
                    "nro_factura": factura,
                    "monto": monto,
                    "fecha": fecha,
                    "nro_comprobante": nro_comprobante,
                    "tipo_pago_id": tipo_pago_id,
                },
                success: function(response) {
                    var responseObject = JSON.parse(response);
                    if (responseObject.status === 201) {
                        //limiar campos
                        $("#proveedor_pago_id").val(null).trigger('change');
                        $("#facturas-select").val(null).trigger('change');
                        $("#monto_pago").val("");
                        $("#nro_comprobante").val("");
                        $("#tipo_pago_id").val(null).trigger('change');
                        mostrarNotificacion(
                            "Crear Pago",
                            "exito",
                            responseObject.status_message
                        );


                    } else {
                        $("#mensajeAlertaCrearPago").text(responseObject.status_message);
                        $("#mensajeAlertaCrearPago").show();
                    }
                    $.unblockUI();

                },
                error: function(xhr, status, error) {
                    console.error(error);
                    $.unblockUI();
                },
            });


        });
    });

});