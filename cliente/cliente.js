$(document).ready(function() {
    listarClientes();
});

function listarClientes() {
    $.ajax({
      url: '/fibra-nort/cliente/cliente_controller.php', // Ajusta la ruta según tu index
        type: 'POST',
        data: { op: 'listar' },
        success: function(response) {
            // Inyectamos el HTML generado por client_tabla.php dentro del contenedor
            $('#contenedor_tabla_clientes').html(response);
            
            // Si usas DataTable, lo reinicializamos aquí
            // $('#tabla_clientes').DataTable(); 
        },
        error: function() {
            console.error("Error al cargar la tabla de clientes");
        }
    });
}