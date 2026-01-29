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

    let operacion = (action === 'I' ? 'insertar' : 'editar');
}