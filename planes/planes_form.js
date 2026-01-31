// Inicializar cuando el modal se muestra
$(document).on('shown.bs.modal', '#modal_registro_plan', function () {
  // Inicializar calendario para Fecha de Inicio solo si no está inicializado
  if (!$("#fecha_inicio_plan").data("DateTimePicker")) {
    $("#fecha_inicio_plan").datetimepicker({
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

  // Inicializar calendario para Fecha de Instalación solo si no está inicializado
  if (!$("#fecha_instalacion_plan").data("DateTimePicker")) {
    $("#fecha_instalacion_plan").datetimepicker({
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
});
// Submit del formulario
$(document).on("submit", "#form_registro_plan", function (e) {
  e.preventDefault();

  let formData = $(this).serialize();
  let modal = $("#modal_registro_plan");
  let operacion = modal.data("operacion") || "insertar";
  let id = modal.data("id") || 0;

  formData += "&op=" + operacion + "&id=" + id;

  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        $("#modal_registro_plan").modal("hide");
        listarPlanes();
        alert(response.mensaje);
      } else {
        alert("Error: " + response.mensaje);
      }
    },
    error: function (xhr, status, error) {
      alert("Error en la solicitud: " + error);
    },
  });
});


function cargarDatosporId(code_order) {
  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: { op: "obtener_orden_code", code_order: code_order },
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        let orden = response.data;
        // Poblar los inputs del formulario con los datos del cliente
        $("#order_id").val(orden.order_id);
        $("#client_name").val(orden.client_name);
        $("#document_number").val(orden.document_number);
        $("#plan").val(orden.name);
        $("#client_id").val(orden.client_id);
        $("#plan_id").val(orden.plan_id);
      } else {
        console.error(
          "Error al obtener datos del cliente por DNI: " + response.mensaje,
        );
      }
    },
    error: function () {
      console.error("Error al cargar datos del cliente por DNI");
    },
  });
}
