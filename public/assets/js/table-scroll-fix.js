/**
 * Ajusta el ancho del thead para que coincida con el tbody
 * cuando hay scrollbar visible
 */
function ajustarAnchoTabla() {
    const tables = document.querySelectorAll('.table-scroll');
    
    tables.forEach(tableContainer => {
        const tbody = tableContainer.querySelector('tbody');
        const thead = tableContainer.querySelector('thead');
        
        if (tbody && thead) {
            // Obtener el ancho del scrollbar
            const scrollbarWidth = tbody.offsetWidth - tbody.clientWidth;
            
            // Ajustar el padding-right del thead para compensar el scrollbar
            if (scrollbarWidth > 0) {
                thead.style.paddingRight = scrollbarWidth + 'px';
            }
        }
    });
}

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', ajustarAnchoTabla);

// Ejecutar después de que DataTables se inicialice
$(document).on('init.dt', function() {
    setTimeout(ajustarAnchoTabla, 100);
});

// Ejecutar cuando cambie el tamaño de la ventana
window.addEventListener('resize', ajustarAnchoTabla);
