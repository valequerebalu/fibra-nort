$(document).ready(function () {
  listarAsignaciones();
});
function listarAsignaciones() {
    $.ajax({
        url: "/fibra-nort/asignacion/asignacion_controller.php", // Ajusta la ruta según tu index
        type: "POST",
        data: { op: "listar" },
        success: function (response) {
            // Inyectamos el HTML generado por asignacion_tabla.php dentro del contenedor
            $("#contenedor_tabla_asignacion").html(response);
            // Inicializar DataTables con la función global 
            inicializarDataTable('#tabla_asignacion', {
                columnDefs: [
                    {       
                        targets: [0, 8], // Columnas de checkbox y acciones
                        orderable: false
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
            // Inyectamos el HTML del formulario dentro del contenedor
            $("#modal_asignacion_container").html(response);

            // Guardar operación e id directamente
            $("#modal_registro_asignacion")
                .data("operacion", operacion)
                .data("id", id);

            // Resetear o actualizar el título
            if (action === 'A') {
                $("#titulo_asignacion").text("Asignar Técnico");
            }   
            $("#modal_registro_asignacion").modal("show");
        },
        error: function () {
            console.error("Error al cargar el formulario de asignación");
        },
    });
}