$(document).ready(function() {
    // Mostrar/ocultar el menú desplegable al hacer clic en el botón de perfil
    $('.user-menu button').click(function() {
        $(this).siblings('.dropdown-content').toggle();
    });

    // Ocultar el menú desplegable al hacer clic fuera de él
    $(document).click(function(event) {
        if (!$(event.target).closest('.user-menu').length) {
            $('.dropdown-content').hide();
        }
    });

    // Evento click del botón de menú
    $('#menu-prinipal').click(function() {
        // Mostrar el modal
        $('#menuModal').modal('show');
    });
});