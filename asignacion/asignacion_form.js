// Usar delegación de eventos ya que el formulario se carga dinámicamente
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
        alert(response.mensaje);
      } else {
        alert("Error: " + response.mensaje);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la petición:", error);
    },
  });
});
