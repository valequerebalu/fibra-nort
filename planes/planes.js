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
