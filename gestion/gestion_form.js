// --- GESTIÓN DE ÓRDEN ---

$(document).off('shown.bs.modal', '#modal_registro_orden');
$(document).on('shown.bs.modal', '#modal_registro_orden', function () {
    if (!$("#fecha_programada").data("DateTimePicker")) {
        $("#fecha_programada").datetimepicker({ format: "YYYY-MM-DD" });
    }
    if (!$("#hora_programada").data("DateTimePicker")) {
        $("#hora_programada").datetimepicker({ format: "HH:mm" });
    }
});

$(document).on("input", "#modal_registro_orden #document_number", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
});

$(document).off("submit", "#form_registro_orden");
$(document).on("submit", "#form_registro_orden", function (e) {
    e.preventDefault();
    let formData = $(this).serialize();
    let modal = $("#modal_registro_orden");
    let operacion = modal.data("operacion") || "insertar";
    let id = modal.data("id") || 0;
    formData += "&op=" + operacion + "&id=" + id;

    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function (response) {
            if (response.estado == 1) {
                $("#modal_registro_orden").modal("hide");
                listarGestion();
                Swal.fire('Éxito', response.mensaje, 'success');
            } else {
                Swal.fire('Error', response.mensaje, 'error');
            }
        }
    });
});

function cargarDatosporDNI(dni) {
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: { op: "obtener_cliente_dni", dni: dni },
        dataType: "json",
        success: function (response) {
            if (response) {
                $("#name_or_company_name").val(response.name_or_company_name);
                $("#paternal_surname").val(response.paternal_surname);
                $("#maternal_surname").val(response.maternal_surname);
                $("#date_birth").val(response.date_birth);
                $("#id_cliente").val(response.id);
            }
        }
    });
}

// --- GESTIÓN DE ASIGNACIÓN ---

$(document).off("submit", "#form_asignacion_tecnico");
$(document).on("submit", "#form_asignacion_tecnico", function (e) {
    e.preventDefault();
    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: {
            op: "asignar_tecnico",
            orden_id: $("#asignacion_id").val(),
            technician_id: $("#technician_id").val()
        },
        dataType: "json",
        success: function (response) {
            if (response.estado === 1) {
                $("#modal_asignacion_tecnico").modal("hide");
                listarGestion();
                Swal.fire('Éxito', response.mensaje, 'success');
            } else {
                Swal.fire('Error', response.mensaje, 'error');
            }
        }
    });
});

$(document).off("submit", "#form_rechazar_orden");
$(document).on("submit", "#form_rechazar_orden", function (e) {
    e.preventDefault();
    const motivo = $("#motivo_rechazo").val().trim();
    if (!motivo) {
        Swal.fire('Campo requerido', 'Por favor, ingrese el motivo del rechazo', 'warning');
        return;
    }

    $.ajax({
        url: "/fibra-nort/gestion/gestion_controller.php",
        type: "POST",
        data: {
            op: "rechazar_orden",
            orden_id: $(this).find("#orden_id").val(),
            motivo_rechazo: motivo
        },
        dataType: "json",
        success: function (response) {
            if (response.estado === 1) {
                $("#modal_rechazar_orden").modal("hide");
                listarGestion();
                Swal.fire('Éxito', response.mensaje, 'success');
            } else {
                Swal.fire('Error', response.mensaje, 'error');
            }
        }
    });
});
