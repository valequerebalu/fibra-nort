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
          cargarDatosPlan(id);
        }, 300);
      }
    },
    error: function () {
      console.error("Error al cargar el formulario de planes");
    },
  });
}

function cargarDatosPlan(id) {
  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: { op: "obtener", id: id },
    success: function (response) {
      if (response.estado == 1) {
        let plan = response.data;
        
        $("#code_service_orders").val(plan.code_orders);
        $("#order_id").val(plan.order_id);
        $("#client_name").val(plan.client_name);
        $('#client_id').val(plan.client_id);
        $("#document_number").val(plan.document_number);
        $('#plan_id').val(plan.plan_id);
        $("#plan").val(plan.plan_name);
        $("#nodo_id").val(plan.node_name);
        $("#billing_day").val(plan.billing_day);
        $("#start_date").val(plan.start_date);
        $("#installed_at").val(plan.installed_at);
        $("#invoice_type").val(plan.invoice_type);
        $("#router_serial").val(plan.router_serial);
        $("#router_model").val(plan.router_model);
        $("#ip_address").val(plan.ip_address);
        $("#mac_address").val(plan.mac_address);
        $("#wifi_ssid").val(plan.wifi_ssid);
        $("#wifi_password").val(plan.wifi_password);
      } else {
        console.error(
          "Error al obtener datos del plan: " + response.mensaje,
        );
      }
    },
    error: function () {
      console.error("Error al cargar los datos de la orden");
    },
  });
}

function guardarPlan() {
  let operacion = $("#modal_registro_plan").data("operacion");
  let id = $("#modal_registro_plan").data("id");
  
  let datos = {
    op: operacion,
    id: id,
    client_id: $("#client_id").val(),
    plan_id: $("#plan_id").val(),
    nodo_id: $("#nodo_id").val(),
    billing_day: $("#billing_day").val(),
    start_date: $("#start_date").val(),
    invoice_type: $("#invoice_type").val(),
    router_serial: $("#router_serial").val(),
    router_model: $("#router_model").val(),
    ip_address: $("#ip_address").val(),
    mac_address: $("#mac_address").val(),
    wifi_ssid: $("#wifi_ssid").val(),
    wifi_password: $("#wifi_password").val(),
    installed_at: $("#installed_at").val()
  };

  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: datos,
    success: function (response) {
      if (response.estado == 1) {
        alert(response.mensaje);
        $("#modal_registro_plan").modal("hide");
        listarPlanes();
      } else {
        alert("Error: " + response.mensaje);
      }
    },
    error: function () {
      console.error("Error al guardar el plan");
      alert("Error al guardar el plan");
    },
  });
}

function eliminarPlan(id) {
  if (confirm("¿Está seguro de que desea eliminar este plan?")) {
    $.ajax({
      url: "/fibra-nort/planes/planes_controller.php",
      type: "POST",
      data: { op: "eliminar", id: id },
      success: function (response) {
        if (response.estado == 1) {
          alert(response.mensaje);
          listarPlanes();
        } else {
          alert("Error: " + response.mensaje);
        }
      },
      error: function () {
        console.error("Error al eliminar el plan");
        alert("Error al eliminar el plan");
      },
    });
  }
}


function planForm(action, id) {
  console.log("Acción:", action, "ID:", id);

  if (action === "U" && id) {
    cargarFormularioPlan("U", id);
  }
  // Si es eliminar (cuando se pasa solo el ID como número)
  else if (typeof action === "number") {
    if (confirm("¿Está seguro de eliminar este plan?")) {
      eliminarPlan(action);
    }
  }
  // Si es inserción (I) o nuevo
  else {
    cargarFormularioPlan("I", 0);
  }
}

function eliminarPlan(id) {
  $.ajax({
    url: "/fibra-nort/planes/planes_controller.php",
    type: "POST",
    data: {
      op: "eliminar",
      id: id,
    },
    success: function (response) {
      alert(response.mensaje);
      listarPlanes();
    },
    error: function () {
      console.error("Error al eliminar plan");
    },
  });
}
