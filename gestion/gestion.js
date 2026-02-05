$(document).ready(function () {
    listarGestion();
});

function listarGestion() {
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: { op: "listar" },
        success: function (response) {
            $("#contenedor_tabla_gestion").html(response);
            inicializarDataTable("#tabla_gestion", {
                columnDefs: [
                    {
                        targets: [0, 11], // Ajustar según columnas reales
                        orderable: false,
                    },
                ],
            });
        },
        error: function () {
            console.error("Error al cargar la tabla de gestión");
        },
    });
}

// --- FUNCIONES DE ORDEN ---

function gestion_form(action, id) {
    cargarFormularioGestion(action, id);
}

function cargarFormularioGestion(action, id) {
    let operacion = action === "I" ? "insertar" : "editar";
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php", // Debería haber un case 'form' o similar
        type: "POST",
        data: { op: "form", action: action, id: id },
        success: function (response) {
            $("#modal_gestion_container").html(response);
            $("#modal_registro_orden").data("operacion", operacion).data("id", id);
            if (action === "I") $("#modal-titulo-orden").text("Nueva Orden");
            $("#modal_registro_orden").modal("show");
            if (action === "U" && id) {
                setTimeout(function () { cargarDatosOrden(id); }, 300);
            }
        }
    });
}

function cargarDatosOrden(id) {
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: { op: "obtener_por_id", id: id },
        dataType: "json",
        success: function (response) {
            if (response) {
                let orden = response;
                $("#modal-titulo-orden").text("Editando Orden: " + orden.code_orders);
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
            }
        }
    });
}

function eliminar_orden(id) {
    if (confirm("¿Está seguro de eliminar esta orden?")) {
        $.ajax({
            url: "/fibra-nort/gestion/gestion_controller.php",
            type: "POST",
            data: { op: "eliminar", id: id },
            success: function (response) {
                let res = JSON.parse(response);
                alert(res.mensaje);
                listarGestion();
            }
        });
    }
}

// --- FUNCIONES DE ASIGNACIÓN ---

function asignacionForm(action, id) {
    cargarModalAsignacion(action, id);
}

function cargarModalAsignacion(action, id) {
    let operacion = (action === 'A' ? 'asignar' : '');
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: { op: "form_asignacion", action: action, id: id }, // Necesito crear este case
        success: function (response) {
            $("#modal_asignacion_container").html(response);
            $("#modal_asignacion_tecnico").data("operacion", operacion).data("id", id);
            $("#titulo_asignacion").text(action === 'A' ? "Asignar Técnico" : "Reasignar Técnico");
            setTimeout(function () { $("#modal_asignacion_tecnico").modal("show"); }, 100);
        }
    });
}

function aceptarOrden(id) {
    Swal.fire({
        title: '¿Aceptar orden?',
        text: "La orden cambiará a estado 'ACEPTADA'",
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, aceptar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "/fibra-nort/gestion/gestion_controller.php",
                type: "POST",
                data: { op: "aceptar_orden", orden_id: id },
                dataType: "json",
                success: function (response) {
                    if (response.estado == 1) {
                        Swal.fire('Éxito', response.mensaje, 'success');
                        listarGestion();
                    } else {
                        Swal.fire('Error', response.mensaje, 'error');
                    }
                }
            });
        }
    });
}

function rechazarOrden(id) {
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: { op: "form_rechazo", id: id },
        success: function (response) {
            $("#modal_rechazo_container").html(response);
            $("#modal_rechazar_orden").data("id", id);
            setTimeout(function () { $("#modal_rechazar_orden").modal("show"); }, 100);
        }
    });
}
