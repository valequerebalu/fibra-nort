$(document).ready(function () {
  listarClientes();
});

function listarClientes() {
  //LLAMAR AL FORMULARIO DE CLIENTES (MODAL) , LO IMPRIMO EN UNA VARIABLE QUE ESTA EN CLIENTE_VISTA.PHP Y LUEGO SE LEVANTA EL MODAL

  $.ajax({
    url: "/fibra-nort/cliente/cliente_controller.php", // Ajusta la ruta según tu index
    type: "POST",
    data: { op: "listar" },
    success: function (response) {
      // Inyectamos el HTML generado por client_tabla.php dentro del contenedor
      $("#contenedor_tabla_clientes").html(response);

      // Inicializar DataTables con la función global
      inicializarDataTable('#tabla_clientes', {
        columnDefs: [
          {
            targets: [0, 10], // Columnas de checkbox y acciones
            orderable: false
          }
        ]
      });
    },
    error: function () {
      console.error("Error al cargar la tabla de clientes");
    },
  });
}
function cargarFormularioCliente(action, id) {

  let operacion = (action === 'I' ? 'insertar' : 'editar');

  $.ajax({
    url: "/fibra-nort/cliente/cliente_controller.php",
    type: "POST",
    data: { op: "form", action: action, id: id },
    success: function (response) {
      // Inyectamos el HTML del formulario dentro del contenedor
      $("#modal_cliente_container").html(response);
      
      // Inicializar componentes del formulario (Datepicker)
      if (typeof inicializarFormularioCliente === 'function') {
        inicializarFormularioCliente();
      }

      // Guardar operación e id directamente
      $("#modal_registro_cliente")
        .data("operacion", operacion)
        .data("id", id);
      
      // Resetear o actualizar el título
      if (action === 'I') {
        $("#modal_titulo_cliente").text("Nuevo Cliente");
      }
      
      $("#modal_registro_cliente").modal("show");
      
      // Si es edición, cargar los datos del cliente
      if (action === 'U' && id) {
        setTimeout(function() {
          cargarDatosCliente(id);
        }, 300);
      }
    },
    error: function () {
      console.error("Error al cargar el formulario de clientes");
    },
  });
}

function cargarDatosCliente(id) {
  $.ajax({
    url: "/fibra-nort/cliente/cliente_controller.php",
    type: "POST",
    data: { op: "obtener", id: id },
    dataType: "json",
    success: function (response) {
      if (response.estado == 1) {
        let cliente = response.data;
        
        // Actualizar el título con el nombre del cliente
        $("#modal_titulo_cliente").text("Editando Cliente: " + cliente.code_clients + " / " + cliente.name_or_company_name);
        
        // Poblar los inputs del formulario con los datos del cliente
        $("#document_type_id").val(cliente.document_type_id);
        $("#document_number").val(cliente.document_number);
        $("#name_or_company_name").val(cliente.name_or_company_name);
        $("#maternal_surname").val(cliente.maternal_surname);
        $("#paternal_surname").val(cliente.paternal_surname);
        $("#sex").val(cliente.sexo);
        $("#date_birth").val(cliente.date_birth);
        $("#phone").val(cliente.phone);
        $("#email").val(cliente.email);
        $("#address").val(cliente.address);
        $("#reference").val(cliente.reference);
      } else {
        console.error("Error al obtener datos del cliente: " + response.mensaje);
      }
    },
    error: function () {
      console.error("Error al cargar datos del cliente");
    },
  });
}

function cliente_form(action, id) {
  console.log("Acción:", action, "ID:", id);
  
  // Si es edición (U)
  if (action === 'U' && id) {
    cargarFormularioCliente('U', id);
  } 
  // Si es eliminar (cuando se pasa solo el ID como número)
  else if (typeof action === 'number') {
    if (confirm('¿Está seguro de eliminar este cliente?')) {
      eliminarCliente(action);
    }
  }
  // Si es inserción (I) o nuevo
  else {
    cargarFormularioCliente('I', 0);
  }
}

function eliminarCliente(id) {
  $.ajax({
    url: "/fibra-nort/cliente/cliente_controller.php",
    type: "POST",
    data: {
      op: "eliminar",
      id: id,
    },
    success: function (response) {
      alert(response.mensaje);
      listarClientes();
    },
    error: function () {
      console.error("Error al eliminar cliente");
    },
  });
}

