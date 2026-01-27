
function cliente_form(action, id) {
    console.log("Acción:", action, "ID:", id);
    // 1. Limpiamos el formulario siempre al abrir
    $('#form_registro_cliente')[0].reset();
    
    // 2. Personalizamos el Modal según la acción
    if (action === 'I') {
        $('.modal-title').html('<i class="fa fa-plus"></i> Nuevo Cliente');
        $('#modal_registro_cliente').modal('show');
    } 

}
$(document).ready(function() {

    $('#document_type_id').on('change', function() {
        let tipoDoc = $(this).val(); // Obtenemos el ID seleccionado
        let inputDoc = $('#document_number');
        
        // Limpiamos el valor actual por seguridad al cambiar de tipo
        inputDoc.val('');

        if (tipoDoc == "1") { // DNI
            inputDoc.attr('maxlength', '8');
            inputDoc.attr('placeholder', 'Ingrese 8 dígitos');
        } 
        else if (tipoDoc == "2") { // RUC o equivalente
            inputDoc.attr('maxlength', '11'); // En Perú el RUC son 11, ajusta a 12 si prefieres
            inputDoc.attr('placeholder', 'Ingrese 11 dígitos');
        } 
        else {
            inputDoc.removeAttr('maxlength');
            inputDoc.attr('placeholder', 'Ingrese documento');
        }
    });
    $('#document_number').on('input', function () { 
        this.value = this.value.replace(/[^0-9]/g, '');
    });



    $('#form_registro_cliente').on('submit', function(e) {
        e.preventDefault();
        
        let formData = $(this).serialize();
        formData += "&op=insertar";

        $.ajax({
            url: '../cliente/cliente_controller.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.estado == 1) {
                    $('#modal_registro_cliente').modal('hide');
                    listarClientes(); 
                    alert(response.mensaje);
                } else {
                    alert('Error: ' + response.mensaje);
                }
            },
            error: function(xhr, status, error) {
                alert('Error en la solicitud: ' + error);
            }
        });
    });
});
