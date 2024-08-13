$(document).ready(function() {
    var clienteSeleccionado = {
        nombre: "",
        cuit: "",
        domicilio: "",
    };


    function validarFormularioProducto() {
        var isValid = true;
        $("#crear-producto-modal input[required]").each(function() {
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
        $("#crear-cliente-modal input[required]").each(function() {
            if ($.trim($(this).val()) == "") {
                isValid = false;
                $(this).addClass("is-invalid");
            } else {
                $(this).removeClass("is-invalid");
            }
        });
        return isValid;
    }

    function limpiarCampos() {
        // Limpiar el tipo de comprobante seleccionado
        $("input[name='tipoComprobante']").prop("checked", false);
        // Limpiar el monto pagado
        $("#montoPagado").val("");
        // Ocultar el mensaje de alerta
        $("#mensajeAlertaCobrar").css("display", "none");

        $("#vuelto").css("display", "none");

    }

    function limpiarFormularioClientes() {
        // Limpiar todos los campos de texto y select
        $('#crear-cliente-modal input[type="text"]').val('');
        $('#crear-cliente-modal input[type="number"]').val(0);
        $('#crear-cliente-modal select').val(null).trigger('change');
    }


    function mostrarNotificacion(titulo, tipo, mensaje) {
        // Limpiar el contenido del modal
        $("#mensajeModal").empty();

        // Agregar el icono según el tipo de notificación
        var icono = "";
        if (tipo === "error") {
            icono =
                '<i class="fa-solid fa-exclamation-triangle" style="color: red;"></i>';
        } else if (tipo === "advertencia") {
            icono =
                '<i class="fa-solid fa-exclamation-circle" style="color: orange;"></i>';
        } else if (tipo === "exito") {
            icono = '<i class="fa-solid fa-check-circle" style="color: green;"></i>';
        } else {
            icono = '<i class="fa-solid fa-info-circle" style="color: blue;"></i>';
        }

        // Construir el contenido del modal
        var contenido = "<h3>" + icono + " " + titulo + "</h3>";
        contenido += "<p>" + mensaje + "</p>";

        // Agregar el contenido al modal
        $("#mensajeModal").html(contenido);

        // Mostrar el modal
        $("#mensaje-modal").fadeIn();
    }

    function calculateSubtotal() {
        var subtotal = 0;
        var summaryHtml = "";
        products.forEach(function(product) {
            var productTotal = product.price * product.quantity;
            subtotal += productTotal;
            summaryHtml += product.name + ": $" + productTotal.toFixed(2) + "<br>";
        });
        var total = subtotal; // En este ejemplo, el total es igual al subtotal
        $("#total-price").html("$" + subtotal.toFixed(2));
        $("#montoPagar").text("$" + subtotal.toFixed(2));
    }

    // Array para almacenar los productos agregados
    var products = [];
    var selectedClient = null;

    // Evento submit del formulario para agregar productos
    $("#product-form").submit(function(event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario

        // Obtener los datos del formulario
        var productName = $("#product-name").val();
        var productPrice = parseFloat($("#product-price").val());

        // Permitir cambiar manualmente el precio si es 0.00
        if (productPrice === 0) {
            productPrice = parseFloat(prompt("Ingrese el precio del producto:"));
        }

        // Verificar si el producto ya está agregado
        var existingProductIndex = products.findIndex(function(product) {
            return product.name === productName;
        });

        if (existingProductIndex !== -1) {
            // Si el producto ya está agregado, sumar la cantidad
            products[existingProductIndex].quantity++;
        } else {
            // Si el producto no está agregado, agregarlo al array
            products.push({
                name: productName,
                price: productPrice,
                quantity: 1,
            });
        }

        // Limpiar los campos del formulario
        $("#product-name").val("");
        $("#product-price").val("");

        // Actualizar la tabla de productos
        updateProductList();

        // Calcular y mostrar el subtotal
        calculateSubtotal();
    });

    // Función para actualizar la tabla de productos
    // Función para actualizar la tabla de productos
    function updateProductList() {
        var productListHtml = "";
        products.forEach(function(product, index) {
            productListHtml += "<tr>";
            productListHtml += "<td>" + product.name + "</td>";
            // Mostrar un cuadro de texto para ingresar el precio solo si el precio es cero
            if (product.price === 0) {
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
        products.splice(index, 1);
        updateProductList();
        calculateSubtotal();
    };

    // Función para actualizar la cantidad de un producto
    window.updateQuantity = function(index) {
        var quantity = parseInt($("#quantity-" + index).val());
        products[index].quantity = quantity;
        // Actualizar el total del producto
        $("#total-" + index).text(
            "$" + (products[index].price * quantity).toFixed(2)
        );
        calculateSubtotal();
    };

    // Función para actualizar el precio de un producto
    window.updatePrice = function(index) {
        var price = parseFloat($("#price-" + index).val());
        products[index].price = price;
        // Actualizar el total del producto
        $("#total-" + index).text(
            "$" + (price * products[index].quantity).toFixed(2)
        );
        calculateSubtotal();
    };

    // Evento submit del formulario de búsqueda de cliente
    $("#search-client-form").submit(function(event) {
        event.preventDefault(); // Evitar el envío predeterminado del formulario

        // Aquí debes agregar la lógica para buscar el cliente y actualizar la cabecera con la información del cliente seleccionado

        // Por ahora, simplemente ocultamos el modal
        $("#searchClientModal").modal("hide");
    });

    // Función para agregar un producto favorito
    // Función para agregar un producto favorito
    window.addFavoriteProduct = function(id, name, price) {
        // Verificar si el producto ya está agregado
        var existingProductIndex = products.findIndex(function(product) {
            return product.id === id;
        });

        if (existingProductIndex !== -1) {
            // Si el producto ya está agregado, sumar la cantidad
            products[existingProductIndex].quantity++;
        } else {
            // Si el producto no está agregado, agregarlo al array
            products.push({
                id: id,
                name: name,
                price: price,
                quantity: 1,
            });
        }

        // Actualizar la tabla de productos
        updateProductList();

        // Calcular y mostrar el subtotal
        calculateSubtotal();
    };

    function clearProductList() {
        $("#clientedc").text("1 / Eventual");
        $("#cuitdc").text("CUIT: / Eventual");
        $("#domiciliodc").text("Domicilio: / Eventual");
        products = []; // Vaciar el array de productos
        updateProductList(); // Actualizar la tabla de productos
        calculateSubtotal(); // Recalcular el subtotal
        // Mostrar el mensaje de forma de pago
    }

    // Función para limpiar la lista de productos
    $("#btnlimpiar").click(function() {
        clearProductList(); // Llama a la función para limpiar la lista de productos
    });

    $(".table").DataTable({
        sortable: false,
        searching: false, // Desactiva la opción de búsqueda
        paging: false, // Desactiva la paginación
        scrollY: "40vh", // Altura del área de desplazamiento al 30% de la altura de la ventana
        scrollCollapse: true, // Permite que la tabla se contraiga si el contenido es más pequeño que el área de desplazamiento
        info: false, // Oculta la información de la tabla (por ejemplo, "Mostrando 1 a 10 de X entradas")
    });


    $("#tablaProductosPrecios").DataTable({
        ordenable: true,
        buscable: true, // Desactiva la opción de búsqueda
        paginacion: true, // Desactiva la paginación
        scrollY: "40vh", // Altura del área de desplazamiento al 30% de la altura de la ventana
        colapsoScroll: true, // Permite que la tabla se contraiga si el contenido es más pequeño que el área de desplazamiento
        info: true, // Oculta la información de la tabla (por ejemplo, "Mostrando 1 a 10 de X entradas")
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            },
            "buttons": {
                "copy": "Copiar",
                "colvis": "Visibilidad"
            }
        }
    });

    //modal crear cliente

    $("#buscarCliente").click(function() {
        $("#buscar-cliente-modal").fadeIn();
        $("#searchCliente").select2("open").focus();
    });

    // Cerrar modal al hacer clic en el botón de cierre o fuera del modal
    $(".custom-modal-close").click(function() {
        $(".custom-modal").fadeOut();
    });

    $(window).click(function(event) {
        if (event.target == document.getElementById("buscar-cliente-modal")) {
            $(".custom-modal").fadeOut();
        }
    });

    //Modal Crear producto

    $("#crearProducto").click(function() {
        $("#crear-producto-modal").fadeIn();
    });

    // Cerrar modal al hacer clic en el botón de cierre o fuera del modal
    $(".custom-modal-close").click(function() {
        $(".custom-modal").fadeOut();
    });

    $(window).click(function(event) {
        if (event.target == document.getElementById("crear-producto-modal")) {
            $(".custom-modal").fadeOut();
        }
    });

    //modal crear cliente
    $("#crearCliente").click(function() {
        $("#crear-cliente-modal").fadeIn();
    });

    // Cerrar modal al hacer clic en el botón de cierre o fuera del modal
    $(".custom-modal-close").click(function() {
        $(".custom-modal").fadeOut();
    });



    $("#searchProduct").on("keyup", function(event) {
        // Verificar si se presionó la tecla "Enter"
        if (event.keyCode === 13) {
            // Bloquear toda la pantalla
            $.blockUI({ message: "<h1>Buscando Producto...</h1>" });

            // Obtener el valor del input de búsqueda
            var searchTerm = $("#searchProduct").val();
            var limit = 1;

            // Realizar la solicitud AJAX para buscar el producto
            $.ajax({
                url: "ajax/productos/list.php",
                method: "GET",
                data: {
                    limit: limit,
                    descripcion: searchTerm,
                    codigo: searchTerm,
                    codigo_barra: searchTerm,
                },
                dataType: "json",
                success: function(response) {
                    // Desbloquear la pantalla
                    $.unblockUI();
                    // Verificar si se encontraron resultados
                    if (response.length > 0) {
                        // Autocompletar el input con la información del producto
                        var producto = response[0];
                        var textoAutocompletar =
                            producto.codigo_barra +
                            " - " +
                            producto.descripcion +
                            " ($" +
                            producto.precio1 +
                            ")";
                        $("#searchProduct").val(textoAutocompletar);

                        // Llamar a la función addFavoriteProduct para agregar el producto a la tabla
                        addFavoriteProduct(
                            producto.id,
                            producto.descripcion,
                            parseFloat(producto.precio1)
                        );

                        // Limpiar el contenido del input después de 1 segundo
                        setTimeout(function() {
                            $("#searchProduct").val("");
                        }, 600);

                        // Colocar el cursor en el input
                        $("#searchProduct").focus();

                        // Haz lo que necesites con la variable productSelected
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
    });

    $("#btncobrar").click(function() {
        // Verifica si hay productos agregados
        if (products.length > 0) {
            $("#cobrar-modal").fadeIn();
        } else {
            mostrarNotificacion(
                "Cobrar",
                "error",
                "No hay productos agregados para cobrar."
            );
        }
    });


    $("#btnlistaprecio").click(function() {

        $("#lista-precio-modal").fadeIn();

    });

    $(document).keydown(function(event) {
        // Verificar qué tecla se ha presionado
        switch (event.which) {
            case 112: // Tecla F1
                console.log("Se ha presionado la tecla F1");
                break;
            case 115: // Tecla F4
                clearProductList();
                break;
            case 116: // Tecla F5
                console.log("Se ha presionado la tecla F5");
                break;
            case 117: // Tecla F6
                console.log("Se ha presionado la tecla F6");
                break;
            case 118: // Tecla F7
                $("#lista-precio-modal").fadeIn();
                break;
            case 119: // Tecla F8
                console.log("Se ha presionado la tecla F8");
                break;
            case 121: // Tecla F10
                if (products.length > 0) {
                    $("#cobrar-modal").fadeIn();
                } else {
                    mostrarNotificacion(
                        "Cobrar",
                        "error",
                        "No hay productos agregados para cobrar."
                    );
                }

                break;
            case 27: // Tecla de Escape
                $(".custom-modal").fadeOut();
                break;
        }
    });

    $("#searchCliente")
        .select2({
            language: "es",
            placeholder: "Selecciona un Cliente",
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: "ajax/clientes/list_select.php",
                dataType: "json",
                delay: 250,
                processResults: function(data) {
                    return {
                        results: data,
                    };
                },
                cache: false,
            },
        })
        .on("select2:open", function() {
            $(document).on("keydown", ".select2-search__field", function(e) {
                if (e.key === "Enter") {
                    var selectedCliente = $("#searchCliente").select2("data")[0];
                    if (selectedCliente) {
                        $("#clientedc").text(
                            selectedCliente.nro_cliente + " / " + selectedCliente.nombre
                        );
                        $("#cuitdc").text(
                            "CUIT: " +
                            selectedCliente.cuit +
                            " / " +
                            selectedCliente.tipo_iva_nombre
                        );
                        $("#domiciliodc").text(
                            "Domicilio: " +
                            selectedCliente.direccion +
                            " / " +
                            selectedCliente.localidad
                        );
                        $(".custom-modal").fadeOut();
                        $("#searchCliente").val(null).trigger("change");
                        // Dentro del evento de selección de cliente
                        clienteSeleccionado.nombre = selectedCliente.nombre;
                        clienteSeleccionado.cuit = selectedCliente.cuit;
                        clienteSeleccionado.domicilio = selectedCliente.direccion + " - " + selectedCliente.localidad;

                    } else {
                        $("#mensajeAlerta").show();
                    }
                }
            });
        });

    $("#custom-modal-close-btn").on("click", function() {
        $("#searchCliente").val(null).trigger("change"); // Limpiar el select
        $("#mensajeAlerta").hide();
        $("#mensajeAlertaCobro").hide(); // Ocultar el mensaje de alerta si estaba mostrado
    });

    $("#btnSeleccionCliente").on("click", function() {
        var selectedCliente = $("#searchCliente").select2("data")[0];
        if (selectedCliente) {
            $("#clientedc").text(
                selectedCliente.nro_cliente + " / " + selectedCliente.nombre
            );
            $("#cuitdc").text(
                "CUIT: " +
                selectedCliente.cuit +
                " / " +
                selectedCliente.tipo_iva_nombre
            );
            $("#domiciliodc").text(
                "Domicilio: " +
                selectedCliente.direccion +
                " / " +
                selectedCliente.localidad
            );
            $(".custom-modal").fadeOut();
            $("#searchCliente").val(null).trigger("change");
        } else {
            $("#mensajeAlerta").show();
        }
    });

    // Manejar el evento click en el botón "Imprimir comprobante"
    $("#btnImprimirComprobante").click(function() {
        // Verificar si se ha seleccionado un tipo de comprobante
        var tipoComprobante = $("input[name='tipoComprobante']:checked").val();
        if (!tipoComprobante) {
            $("#mensajeAlertaCobrar")
                .html(
                    "<i class='fas fa-exclamation-circle'></i>  Debe seleccionar un tipo de comprobante."
                )
                .css("display", "block");
            return;
        } else {
            $("#mensajeAlertaCobrar").css("display", "none");
        }

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

        // Preparar los datos a enviar
        var formData = {
            nombre_tienda: "Kiosco La Plata",
            direccion_tienda: "La Plata, calle 23 #1434",
            telefono_tienda: "555-1234567", // Cambia el número de teléfono
            articulos: obtenerListadoArticulos(),
            total: $("#montoPagar").text().substring(1),
            tipo_comprobante: tipoComprobante,
            monto_pagado: montoPagado,
            monto_vuelto: montoPagado - montoPagar,
            fecha: new Date().toLocaleDateString(),
            hora: new Date().toLocaleTimeString(),
            cliente_nombre: "Cliente: " + $("#clientedc").text(),
            cliente_cuit: $("#cuitdc").text(),
            cliente_domicilio: $("#domiciliodc").text()

            // Eliminar el símbolo de dólar
        };
        clearProductList();
        limpiarCampos();
        $(".custom-modal").fadeOut();
        $.blockUI({ message: "<h1>Imprimiendo...</h1>" });
        // Realizar la solicitud AJAX mediante POST
        $.ajax({
            url: "./print_ticket.php",
            method: "POST",
            data: formData,
            xhrFields: {
                responseType: "blob", // Indicar que se espera una respuesta de tipo blob (archivo)
            },

            success: function(response) {
                // Crear una URL para el archivo blob
                var blobUrl = window.URL.createObjectURL(response);

                // Abrir una ventana emergente
                var printWindow = window.open("", "_blank");

                // Escribir el contenido del PDF en la ventana emergente
                printWindow.document.write(
                    '<iframe src="' +
                    blobUrl +
                    '" frameborder="0" style="border:0; width:100%; height:100%;"></iframe>'
                );

                // Monitorear el evento onafterprint para cerrar la ventana emergente después de la impresión
                printWindow.onafterprint = function() {
                    // Cerrar la ventana emergente después de la impresión
                    printWindow.close();
                    // Limpiar los campos
                };

                // Imprimir automáticamente el PDF
                printWindow.document
                    .getElementsByTagName("iframe")[0]
                    .contentWindow.print();

                $.unblockUI();
            },
            error: function(xhr, status, error) {
                // Manejar errores, si los hay
                console.error(error);
                $.unblockUI();
            },
        });
    });

    // Función para limpiar todos los campos
    function limpiarCampos() {
        $('form').each(function() {
            this.reset();
        });

    }

    // Función para obtener el listado de artículos en formato de texto
    function obtenerListadoArticulos() {
        var listado = "";
        products.forEach(function(product) {
            listado +=
                product.name +
                " : $" +
                (product.price * product.quantity).toFixed(2) +
                "\n";
        });
        return listado;
    }

    // Manejar el evento click en el botón "Cerrar" del modal
    $(".custom-modal-close-btn").click(function() {
        $(".custom-modal").fadeOut();
    });

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
            $("#vuelto").text("Vuelto: $" + vuelto.toFixed(2)).css("color", "darkgreen"); // Muestra el vuelto con dos decimales y lo resalta en color oscuro
        }
    });


    $("#formaPago").change(function() {
        // Obtener el monto total a pagar
        var montoStr = $("#total-price").text().replace("$", "");

        // Convertir la cadena a un número flotante
        var montoTotal = parseFloat(montoStr);
        var interes = parseFloat($(this).val()) / 100;

        // Calcular el monto a pagar incluyendo el interés
        var montoPagar = montoTotal + montoTotal * interes;

        // Actualizar el texto del elemento con id "montoPagar"
        $("#montoPagar").text("$" + montoPagar.toFixed(2));
    });

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

    // Obtener los elementos de los campos de entrada
    const costoInput = $("#precio_costo");
    const margenInput = $("#margen_ganancia");
    const precio1Input = $("#precio1");

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
    }

    // Agregar un evento de cambio a los campos de entrada
    costoInput.on("input", calcularMargenGanancia);
    precio1Input.on("input", calcularMargenGanancia);


    // Evento clic en el botón guardar

    // Evento clic en el botón guardar
    $("#btn-guardar-cliente").on("click", function(event) {
        // Evitar el envío del formulario
        event.preventDefault();

        // Validar el formulario
        if (!validarFormularioCliente()) {
            return;
        }

        // Obtener los datos del formulario
        var formData = $("#crear-cliente-form").serialize();

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
                    $("#crear-cliente-modal").hide();
                    limpiarFormularioClientes();

                    // Mostrar notificación de éxito
                    mostrarNotificacion(
                        "Agregar Cliente",
                        "exito",
                        responseObject.status_message
                    );
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
                    var errorMessage = (jqXHR.responseJSON && jqXHR.responseJSON.status_message) ? jqXHR.responseJSON.status_message : "Hubo un error al agregar el cliente.";
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

    $("#btn-guardar-producto").on("click", function(event) {
        // Evitar el envío del formulario
        event.preventDefault();

        // Validar el formulario
        if (!validarFormularioProducto()) {
            return;
        }

        // Obtener los datos del formulario
        var formData = $("#crear-producto-form").serialize();

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
                    $("#crear-producto-modal").hide();

                    // Mostrar notificación de éxito
                    mostrarNotificacion(
                        "Agregar Producto",
                        "exito",
                        responseObject.status_message
                    );
                } else {
                    // Mostrar mensaje de error en el modal
                    $("#crear-producto-modal").append(
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
                $("#crear-producto-modal").append(
                    '<div class="alert alert-danger">Hubo un error al agregar el producto.</div>'
                );
            },
        });
    });

});