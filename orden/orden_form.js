// Inicializar cuando el modal se muestra
$(document).off('shown.bs.modal', '#modal_registro_orden');
$(document).on('shown.bs.modal', '#modal_registro_orden', function () {
  // Inicializar calendario para Fecha Programada solo si no está inicializado
  if (!$("#fecha_programada").data("DateTimePicker")) {
    $("#fecha_programada").datetimepicker({
      format: "YYYY-MM-DD",
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-calendar-check",
        clear: "fa fa-trash",
        close: "fa fa-times",
      },
    });
  }

  // Inicializar selector de hora para Hora Programada solo si no está inicializado
  if (!$("#hora_programada").data("DateTimePicker")) {
    $("#hora_programada").datetimepicker({
      format: "HH:mm",
      icons: {
        time: "fa fa-clock",
        date: "fa fa-calendar",
        up: "fa fa-arrow-up",
        down: "fa fa-arrow-down",
        previous: "fa fa-chevron-left",
        next: "fa fa-chevron-right",
        today: "fa fa-calendar-check",
        clear: "fa fa-trash",
        close: "fa fa-times",
      },
    });
  }
});

// Restringir input de documento a solo números
$(document).on("input", "#modal_registro_orden #document_number", function () {
  this.value = this.value.replace(/[^0-9]/g, "");
});

// Submit del formulario
$(document).off("submit", "#form_registro_orden");
$(document).on("submit", "#form_registro_orden", function (e) {
  e.preventDefault();

  if (!validateForm(this)) {
    return;
  }

  let formData = $(this).serialize();
  let modal = $("#modal_registro_orden");
  let operacion = modal.data("operacion") || "insertar";
  let id = modal.data("id") || 0;

  formData += "&op=" + operacion + "&id=" + id;

  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        $("#modal_registro_orden").modal("hide");
        listarOrdenes();
        Swal.fire({
          icon: 'success',
          title: 'Éxito',
          text: response.mensaje
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: response.mensaje
        });
      }
    },
    error: function (xhr, status, error) {
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: "Error en la solicitud: " + error
      });
    },
  });
});

function cargarDatosporDNI(dni) {
  const $modal = $("#modal_registro_orden");
  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: { op: "obtener_cliente_dni", document_number: dni },
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        let cliente = response.data;
        // Poblar los inputs del formulario con los datos del cliente
        $modal.find("#name_or_company_name").val(cliente.name_or_company_name);
        $modal.find("#paternal_surname").val(cliente.paternal_surname);
        $modal.find("#maternal_surname").val(cliente.maternal_surname);
        $modal.find("#date_birth").val(cliente.date_birth);
        $modal.find("#id_cliente").val(cliente.id);
      } else {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: response.mensaje,
        });
      }
    },
    error: function () {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Error al cargar datos del cliente por DNI",
      });
    },
  });
}
