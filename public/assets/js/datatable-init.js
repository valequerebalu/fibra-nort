/**
 * Inicialización global de DataTables
 * Esta función centraliza la configuración para todas las tablas de la aplicación
 * 
 * Uso:
 * inicializarDataTable('#tabla_clientes');
 * inicializarDataTable('#tabla_productos', {columnDefs: [{targets: [0], orderable: false}]});
 */

function inicializarDataTable(selector, opciones = {}) {
  // Configuración base para todas las tablas
  const configBase = {
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
    },
    responsive: true,
    autoWidth: false,
    stateSave: true,
    pageLength: 10
  };

  // Combinar configuración base con opciones personalizadas
  const config = Object.assign({}, configBase, opciones);

  // Destruir DataTable existente si ya estaba inicializada
  if ($.fn.dataTable.isDataTable(selector)) {
    $(selector).DataTable().destroy();
  }

  // Inicializar DataTable con la configuración
  return $(selector).DataTable(config);
}

/**
 * Reinicializar DataTable después de cambios en el DOM
 * Útil cuando se inyecta HTML dinámico
 */
function reinicializarDataTable(selector, opciones = {}) {
  inicializarDataTable(selector, opciones);
}
