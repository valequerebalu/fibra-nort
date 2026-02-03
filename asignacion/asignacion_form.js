// Usar delegación de eventos ya que el formulario se carga dinámicamente
$(document).off("submit", "#form_asignacion_tecnico");
$(document).on("submit", "#form_asignacion_tecnico", function (e) {
  e.preventDefault();

  const formData = {
    op: "asignar",
    orden_id: $("#asignacion_id").val(),
    technician_id: $("#technician_id").val(),
  };

  $.ajax({
    url: "/fibra-nort/asignacion/asignacion_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.estado === 1) {
        // Cerrar modal
        $("#modal_asignacion_tecnico").modal("hide");
        // Recargar la tabla
        listarAsignaciones();
        Swal.fire({
          icon: 'success',
          title: 'Asignación exitosa',
          text: response.mensaje,
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.mensaje,
          showConfirmButton: false,
          timer: 1500
        });
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la petición:", error);
    },
  });
});
