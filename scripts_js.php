<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery.min.js"></script> <!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<!-- Enlace al archivo JavaScript de Bootstrap (opcional) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.js"></script>
<!-- Importar BlockUI -->
<script src="./js/libs/jquery.blockUI.min.js"></script>
<script src="./js/libs/print.min.js"></script>
<script src="./js/libs/jquery.minicolors.js"></script>


<script>
  function updateDateTime() {
    var now = new Date();
    var dateString = now.toLocaleDateString(); // Fecha en formato local
    var timeString = now.toLocaleTimeString(); // Hora en formato local

    // Establece la hora en el elemento con id="time"
    document.getElementById('time').textContent = timeString;

    // Establece la fecha en el elemento con id="date"
    document.getElementById('date').textContent = dateString;
  }

  // Actualiza la fecha y hora cada segundo
  setInterval(updateDateTime, 1000);

  // Llama a la función inmediatamente para mostrar la fecha y hora iniciales
  updateDateTime();
</script>