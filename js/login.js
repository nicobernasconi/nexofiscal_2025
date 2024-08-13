 $(document).ready(function() {
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
                 }
             },
             error: function(xhr, status, error) {
                 $.unblockUI();
                 // Mostrar mensaje de error si hay un error durante la solicitud
                 $('#login-message').text('Error de conexión. Inténtalo de nuevo más tarde.');
             }
         });
     });
 });