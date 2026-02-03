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

// Manejar el formulario de rechazo
$(document).off("submit", "#form_rechazar_orden");
$(document).on("submit", "#form_rechazar_orden", function (e) {
  e.preventDefault();

  const formData = {
    op: "rechazar_orden",
    orden_id: $(this).find("#orden_id").val(),
    motivo_rechazo: $("#motivo_rechazo").val().trim(),
  };
  console.log("id de la orden:" + formData.orden_id);
  console.log("motivo de rechazo:" + formData.motivo_rechazo);
  // Validar que el motivo no esté vacío
  if (!formData.motivo_rechazo) {
    Swal.fire({
      icon: 'warning',
      title: 'Campo requerido',
      text: 'Por favor, ingrese el motivo del rechazo',
      showConfirmButton: true
    });
    return;
  }

  $.ajax({
    url: "/fibra-nort/asignacion/asignacion_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.estado === 1) {
        // Cerrar modal
        $("#modal_rechazar_orden").modal("hide");
        // Recargar la tabla
        listarAsignaciones();
        Swal.fire({
          icon: 'success',
          title: 'Orden rechazada',
          text: response.mensaje,
          showConfirmButton: false,
          timer: 1500
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.mensaje,
          showConfirmButton: true
        });
      }
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "Error en la solicitud: " + error,
        showConfirmButton: true
      });
      console.error("Error en la petición:", error);
    },
  });
});
