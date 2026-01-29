$(document).ready(function () {
  listarAsignaciones();
});
function listarAsignaciones() {
    $.ajax({
        url: "/fibra-nort/asignacion/asignacion_controller.php", // Ajusta la ruta según tu index
        type: "POST",
        data: { op: "listar" },
        success: function (response) {
            $("#contenedor_tabla_asignacion").html(response);
            inicializarDataTable('#tabla_asignacion', {
                columnDefs: [
                    {       
                        targets: [0, 8], // Columnas de checkbox y acciones
                        orderable: false,
                    }
                ]
            });
        },
        error: function () {
            console.error("Error al cargar la tabla de asignaciones");
        }
    });
}

function asignacionForm(action, id) {
 
    if(action === 'A') {
       
        cargarModalAsignacion('A', id);
    }
}

function cargarModalAsignacion(action, id) {
    let operacion = (action === 'A' ? 'asignar' : '');
    $.ajax({
        url: "/fibra-nort/asignacion/asignacion_controller.php",
        type: "POST",
        data: { op: "form", action: action, id: id },
        success: function (response) {
            console.log("Respuesta recibida del servidor");
    
            $("#modal_asignacion_container").html(response);
            $("#modal_asignacion_tecnico")
                .data("operacion", operacion)
                .data("id", id);

            if (action === 'A') {
                $("#titulo_asignacion").text("Asignar Técnico");
            }
            
            // Abrir el modal después de un pequeño delay
            setTimeout(function() {
                $("#modal_asignacion_tecnico").modal("show");
                console.log("Modal abierto");
            }, 100);
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el formulario de asignación:", error);
            console.error("Status:", status);
            console.error("Response:", xhr.responseText);
        },
    });
}