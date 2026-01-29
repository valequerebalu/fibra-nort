$(document).ready(function () {
  listarOrdenes();
});

function listarOrdenes() {
  //LLAMAR AL FORMULARIO DE ORDENES, INYECTAR EN EL CONTENEDOR Y LUEGO INICIALIZAR DATATABLE

  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: { op: "listar" },
    success: function (response) {
      $("#contenedor_tabla_ordenes").html(response);
      inicializarDataTable("#tabla_ordenes", {
        columnDefs: [
          {
            targets: [0, 11], // Columnas de checkbox y acciones
            orderable: false,
          },
        ],
      });
    },
    error: function (xhr, status, error) {
      console.error("Error al cargar la tabla de ordenes", error);
    },
  });
}

function cargarFormularioOrden(action, id) {
  let operacion = action === "I" ? "insertar" : "editar";
  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: { op: "form", action: action, id: id },
    success: function (response) {
      // Inyectamos el HTML del formulario dentro del contenedor
      $("#modal_orden_container").html(response);

      $("#modal_registro_orden").data("operacion", operacion).data("id", id);

      // Resetear o actualizar el título
      if (action === "I") {
        $("#titulo_orden").text("Nueva Orden");
      }

      $("#modal_registro_orden").modal("show");

      // Si es edición, cargar los datos de la orden DESPUÉS de que el modal esté visible
      if (action === "U" && id) {
        // Usar setTimeout para asegurar que el DOM esté completamente renderizado
        setTimeout(function () {
          cargarDatosOrden(id);
        }, 500);
      }
    },
    error: function () {
      console.error("Error al cargar el formulario de ordenes");
    },
  });
}

function cargarDatosOrden(id) {
  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: { op: "obtener", id: id },
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        let orden = response.data;

        // Actualizar el título con el nombre del cliente
        $("#titulo_orden").text("Editando: " + orden.name_or_company_name);

        // Poblar los inputs del formulario con los datos del cliente
        $("#name_or_company_name").val(orden.name_or_company_name);
        $("#id_cliente").val(orden.client_id);
        $("#document_number").val(orden.document_number);
        $("#paternal_surname").val(orden.paternal_surname);
        $("#scheduled_date").val(orden.scheduled_date);
        $("#plan_id").val(orden.plan_id);
        $("#description").val(orden.description);
        $("#maternal_surname").val(orden.maternal_surname);
        $("#scheduled_time").val(orden.scheduled_time);
        $("#date_birth").val(orden.date_birth);
      } else {
        console.error(
          "Error al obtener datos de la orden: " + response.mensaje,
        );
      }
    },
    error: function () {
      console.error("Error al cargar los datos de la orden");
    },
  });
}
function orden_form(action, id) {
  console.log("Acción:", action, "ID:", id);

  if (action === "U" && id) {
    cargarFormularioOrden("U", id);
  }
  // Si es eliminar (cuando se pasa solo el ID como número)
  else if (typeof action === "number") {
    if (confirm("¿Está seguro de eliminar esta orden?")) {
      eliminarOrden(action);
    }
  }
  // Si es inserción (I) o nuevo
  else {
    cargarFormularioOrden("I", 0);
  }
}

function eliminarOrden(id) {
  $.ajax({
    url: "/fibra-nort/orden/orden_controller.php",
    type: "POST",
    data: {
      op: "eliminar",
      id: id,
    },
    success: function (response) {
      alert(response.mensaje);
      listarOrdenes();
    },
    error: function () {
      console.error("Error al eliminar orden");
    },
  });
}
