 $(document).ready(function() {
     var z_index = 0;

     function mostrarNotificacion(titulo, tipo, mensaje, boton_id, id, reiniciar) {
         // Limpiar el contenido del modal
         $("#mensajeModal").empty();
         // Agregar el icono según el tipo de notificación
         var icono = "";
         if (tipo === "error") {
             icono = '<i class="fa-solid fa-exclamation-triangle" style="color: red;"></i>';
         } else if (tipo === "instalar") {
             icono = '<i class="fa-solid fa-exclamation-circle" style="color: orange;"></i>';
             icono = '<i class="fa-solid fa-info-circle"  style="color: green;"></i>';
             $("#mensaje-modal .custom-modal-close-btn").hide();
             $("#botonesMensaje").empty();
             $("#botonesMensaje").append('<button id="btn-instalar" class="btn-guardar " >Instalar</button>');
             $("#botonesMensaje").append('<button id="btn-volver" class="btn-cerrar custom-modal-close-btn" >Volver</button>');
         } else if (tipo === "exito") { // Si el tipo es "instalar", agregar un botón de borrado
             icono = '<i class="fa-solid fa-info-circle" style="color: blue;"></i>';
             $("#botonesMensaje").empty();
             $("#botonesMensaje").append('<button class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>');


         } else {
             icono = '<i class="fa-solid fa-warning" style="color: orange;"></i>';
             $("#botonesMensaje").empty();
             $("#botonesMensaje").append('<button class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>');

         }


         //boton volver atras


         // Construir el contenido del modal
         var contenido = "<h3>" + icono + " " + titulo + "</h3>";
         contenido += "<p>" + mensaje + "</p>";

         // Agregar el contenido al modal
         $("#mensajeModal").html(contenido);
         // Mostrar el modal
         $("#mensaje-modal").fadeIn().css("z-index", z_index++);
         //asignar z-index
         $("#mensaje-modal").css("z-index", z_index++);

         //boton instalar
         $("#btn-instalar").click(function() {
             var formData = $('#login-form').serialize();
             if (boton_id === "instalar") {
                 $.blockUI({ message: "<h1>Recuperando informacion...</h1>" });
                 $.ajax({
                     type: 'POST',
                     url: './ajax/instalar/recuperar_informacion.php',
                     data: formData,
                     dataType: 'json',
                     success: function(response) {
                         $.unblockUI();
                         if (response.status == 200) {
                             $url_sql = response.archivo;
                             $.blockUI({ message: "<h1>Instalando...</h1>" });
                             $.ajax({
                                 type: 'POST',
                                 url: './ajax/instalar/instalar.php',
                                 data: { url: $url_sql },
                                 dataType: 'json',
                                 success: function(response) {
                                     $.unblockUI();
                                     if (response.status == 200) {
                                         mostrarNotificacion("Instalacion", "exito", response.message, "instalar", "1");
                                     } else {
                                         mostrarNotificacion("Instalacion", "error", response.message, "instalar", "1");
                                     }
                                 },
                                 error: function(xhr, status, error) {
                                     $.unblockUI();
                                     mostrarNotificacion("Instalacion", "error", "Error de conexión. Inténtalo de nuevo más tarde.", "instalar", "1");
                                 }
                             });




                             console.log(response);
                         } else {
                             mostrarNotificacion("Instalacion", "error", response.message, "instalar", "1");
                         }
                     },
                     error: function(xhr, status, error) {
                         $.unblockUI();
                         mostrarNotificacion("Instalacion", "error", "Error de conexión. Inténtalo de nuevo más tarde.", "instalar", "1");
                     }
                 });
             }
         });
     }

     $(".custom-modal-close-btn").click(function() {
         $(this).closest(".custom-modal").fadeOut();
     });
     $(".custom-modal-close").click(function() {
         $(this).closest(".custom-modal").fadeOut();
     });

     $('#login-form').submit(function(event) {
         event.preventDefault(); // Evitar el envío predeterminado del formulario

         // Mostrar mensaje de "Iniciando sesión..."
         $('#login-message').text("Iniciando sesión...");

         // Obtener los datos del formulario
         var formData = $(this).serialize();
         $.blockUI({ message: "<h1>Ingresando...</h1>" });
         // Enviar la solicitud AJAX
         $.ajax({
             type: 'POST',
             url: './ajax/login/login.php',
             data: formData,
             dataType: 'json',
             success: function(response) {
                 $.unblockUI();
                 console.log(response);
                 if (response.status == 200) {
                     // Redireccionar a la página de inicio si el inicio de sesión fue exitoso
                     window.location.href = 'index.php';
                 } else {
                     // Mostrar mensaje de error si hay un error en la autenticación
                     $('#login-message').text(response.message);
                     if (response.status == 400) {
                         mostrarNotificacion("Instalacion", "instalar", response.message, "instalar", "1");
                     }
                 }
             },
             error: function(xhr, status, error) {
                 console.log(error);
                 $.unblockUI();
                 // Mostrar mensaje de error si hay un error durante la solicitud
                 $('#login-message').text('Error de conexión. Inténtalo de nuevo más tarde.');
             }
         });
     });


 });