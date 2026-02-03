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
    switch (action) {
        case 'A':
            cargarModalAsignacion('A', id);
            break;
        case 'R':
            cargarModalAsignacion('R', id);
            break;
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
            else {
                $("#titulo_asignacion").text("Reasignar Técnico");
                setTimeout(function () {
                $("#modal_rechazar_orden").modal("show");

            }, 100);
            }
            setTimeout(function () {
                $("#modal_asignacion_tecnico").modal("show");

            }, 100);
        },
        error: function (xhr, status, error) {
            console.error("Error al cargar el formulario de asignación:", error);
            console.error("Status:", status);
            console.error("Response:", xhr.responseText);
        },
    });
}

function aceptarOrden(id) {
    Swal.fire({
        title: '¿Aceptar orden?',
        text: "La orden cambiará a estado 'ACEPTADA'",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, aceptar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/fibra-nort/asignacion/asignacion_controller.php",
                type: "POST",
                data: { op: "aceptar_orden", orden_id: id },
                dataType: "json",
                success: function (response) {
                    if (response.estado == 1) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: response.mensaje,
                            showConfirmButton: false,
                            timer: 1500
                        });
                        listarAsignaciones();
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
                        text: "Error en la solicitud: " + error
                    });
                }
            });
        }
    });
}

function rechazarOrden(id) {
    // Abrir el modal de rechazo
    $('#rechazo_orden_id').val(id);
    $('#motivo_rechazo').val('');
    $('#modal_rechazar_orden').modal('show');
}