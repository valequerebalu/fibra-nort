$(document).ready(function () {
  listarPlanes();
});

function listarPlanes() {
  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: { op: "listar" },
    success: function (response) {
      $("#contenedor_tabla_planes").html(response);
      inicializarDataTable("#tabla_planes", {
        columnDefs: [
          {
            targets: [0, 7], // Columnas de checkbox y acciones
            orderable: false,
          },
        ],
      });
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar la tabla de planes", error);
    },
  });
}

function cargarFormularioPlan(action, id) {
  let operacion = action === "I" ? "insertar" : "editar";
  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: { op: "form", action: action, id: id },
    success: function (response) {
      // Inyectamos el HTML del formulario dentro del contenedor
      $("#modal_planes_container").html(response);

      $("#modal_registro_plan").data("operacion", operacion).data("id", id);

      // Resetear o actualizar el título
      if (action === "I") {
        $("#modal-titulo-plan").text("Nuevo Plan");
      }

      $("#modal_registro_plan").modal("show");

      // Si es edición, cargar los datos del plan DESPUÉS de inicializar el form
      if (action === "U" && id) {
        // Usar setTimeout para asegurar que el DOM esté completamente renderizado
        setTimeout(function () {
          cargarDatosOrden(id);
        }, 300);
      }
    },
    error: function () {
      console.error("Error al cargar el formulario de planes");
    },
  });
}
