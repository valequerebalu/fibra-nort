
function inicializarFormularioCliente() {
  // Inicializar calendario para Fecha Nacimiento
  $("#fecha_nacimiento").datetimepicker({
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
// Event delegation para elementos dinámicos del formulario
$(document).on("change", "#document_type_id", function () {
  let tipoDoc = $(this).val();
  let inputDoc = $("#document_number");

  inputDoc.val("");

  if (tipoDoc == "2") {
    inputDoc.attr("maxlength", "8");
    inputDoc.data("exact-length", 8); // Para el validador
    inputDoc.attr("placeholder", "Ingrese 8 dígitos");
  } else if (tipoDoc == "1") {
    inputDoc.attr("maxlength", "11");
    inputDoc.data("exact-length", 11); // Para el validador
    inputDoc.attr("placeholder", "Ingrese 11 dígitos");
  } else {
    inputDoc.removeAttr("maxlength");
    inputDoc.removeData("exact-length"); // Remover restricción
    inputDoc.attr("placeholder", "Ingrese documento");
  }
});

$(document).on("input", "#document_number, #phone", function () {
  this.value = this.value.replace(/[^0-9]/g, "");
});

// Submit del formulario
$(document).on("submit", "#form_registro_cliente", function (e) {
  e.preventDefault();

  if (!validateForm(this)) {
    return;
  }

  // Detectar qué botón fue presionado
  let botonPresionado = $(document.activeElement).val();

  let formData = $(this).serialize();
  let modal = $("#modal_registro_cliente");

  // Usar la operación ya determinada (sin conversiones redundantes)
  let operacion = modal.data("operacion") || "insertar";
  let id = modal.data("id") || 0;

  formData += "&op=" + operacion + "&id=" + id;

  $.ajax({
    url: "/fibra-nort/cliente/cliente_controller.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        $("#modal_registro_cliente").modal("hide");
        listarClientes();
        Swal.fire({
          icon: 'success',
          title: 'Mensaje',
          text: response.mensaje
        });

        if (botonPresionado === "generar_orden") {
          let documentNumber = $("#modal_registro_cliente #document_number").val();

          $("#modal_registro_cliente").on("hidden.bs.modal", function () {

            if (typeof cargarFormularioOrden === 'function') {
              cargarFormularioOrden('I', 0);
            }
            $(document).one("shown.bs.modal", "#modal_registro_orden", function () {
              if (response.id && documentNumber) {
                $("#modal_registro_orden #document_number").val(documentNumber);
                if (typeof cargarDatosporDNI === 'function') {
                  cargarDatosporDNI(documentNumber);
                }
              }
            });

            // Remover el event listener para evitar múltiples ejecuciones
            $(this).off("hidden.bs.modal");
          });
        }
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
        text: error
      });
    },
  });
});
