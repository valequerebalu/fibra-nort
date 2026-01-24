$(function () {
    // El ID debe ser el mismo que le pusiste a tu <table>
    $('#tabla_clientes').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true, // <--- ESTO activa el buscador
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        }
    });
});
$(function () {
    $("#tabla_clientes_pro").DataTable({
        "responsive": true, 
        "lengthChange": true, // Permite elegir cuántos clientes ver (10, 25, 50)
        "autoWidth": false,
        "searching": true,    // ¡ESTO ACTIVA EL BUSCADOR!
        "ordering": true,     // Permite ordenar por nombre, deuda, etc.
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sSearch":         "Buscar:", // Aquí defines el texto del buscador
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });
});