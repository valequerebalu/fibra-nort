$(function () {
  // Inicializar calendario para Fecha Programada
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

  // Inicializar selector de hora para Hora Programada
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
});
// Submit del formulario
$(document).on("submit", "#form_registro_orden", function (e) {
  e.preventDefault();

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

function cargarDatosporDNI(dni) {
  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: { op: "obtener_cliente_dni", document_number: dni },
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        let cliente = response.data;
        // Poblar los inputs del formulario con los datos del cliente
        $("#name_or_company_name").val(cliente.name_or_company_name);
        $("#paternal_surname").val(cliente.paternal_surname);
        $("#maternal_surname").val(cliente.maternal_surname);
        $("#date_birth").val(cliente.date_birth);
        $("#id_cliente").val(cliente.id);
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
