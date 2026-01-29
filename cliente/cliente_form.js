$(document).ready(function () {
  // Los event listeners se manejan con la función cliente_form() desde onclick
});


// Event delegation para elementos dinámicos del formulario
$(document).on("change", "#document_type_id", function () {
  let tipoDoc = $(this).val();
  let inputDoc = $("#document_number");

  inputDoc.val("");

  if (tipoDoc == "1") {
    inputDoc.attr("maxlength", "8");
    inputDoc.attr("placeholder", "Ingrese 8 dígitos");
  } else if (tipoDoc == "2") {
    inputDoc.attr("maxlength", "11");
    inputDoc.attr("placeholder", "Ingrese 11 dígitos");
  } else {
    inputDoc.removeAttr("maxlength");
    inputDoc.attr("placeholder", "Ingrese documento");
  }
});

$(document).on("input", "#document_number", function () {
  this.value = this.value.replace(/[^0-9]/g, "");
});

// Submit del formulario
$(document).on("submit", "#form_registro_cliente", function (e) {
  e.preventDefault();

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
