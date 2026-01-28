# Guía de DataTables - Estilo Global y Reutilización

## Resumen

Se ha creado un sistema de estilos y funciones globales para DataTables que permite:
- Estilo consistente en todas las tablas de la aplicación
- Inicialización simple y reutilizable
- Configuración centralizada que se puede modificar en un solo lugar

## Archivos Creados/Modificados

### 1. **datatables-custom.css**
Ubicación: `/cliente/datatables-custom.css`

Contiene estilos personalizados para:
- Buscador (search): Input con borde azul en focus, 250px de ancho
- Paginación: Botones azules, alineados a la derecha
- Encabezados de tabla: Fondo azul (#007bff), texto blanco, bold
- Filas: Hover effect con fondo gris claro
- Información de registros
- Responsive design para móvil

**Ventaja:** Un solo archivo CSS que aplica a TODAS las tablas DataTables de la aplicación.

### 2. **datatable-init.js**
Ubicación: `/public/assets/js/datatable-init.js`

Función principal:
```javascript
inicializarDataTable(selector, opciones = {})
```

**Características:**
- Configuración base centralizada
- Soporte para opciones personalizadas
- Destruye DataTable anterior si existe
- Guarda el estado de la tabla (stateSave)
- Idioma en español por defecto
- Responsive y autoWidth desactivado

## Cómo Usar

### En tu módulo de PHP/AJAX (ejemplo: tabla de productos)

#### Paso 1: Crear el HTML de la tabla
```html
<div id="contenedor_tabla_productos">
  <!-- La tabla se inyectará aquí -->
</div>
```

#### Paso 2: Cargar la tabla con AJAX
```javascript
function listarProductos() {
  $.ajax({
    url: "/fibra-nort/producto/producto_controller.php",
    type: "POST",
    data: { op: "listar" },
    success: function (response) {
      $("#contenedor_tabla_productos").html(response);
      
      // Inicializar con función global
      inicializarDataTable('#tabla_productos', {
        columnDefs: [
          { targets: [0, lastColumn], orderable: false }
        ]
      });
    }
  });
}
```

#### Paso 3: Crear tu tabla HTML (producto_tabla.php)
```html
<table id="tabla_productos" class="table table-bordered table-hover table-striped">
  <thead class="bg-primary">
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Precio</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($productos as $prod): ?>
    <tr>
      <td><?= $prod['id'] ?></td>
      <td><?= $prod['nombre'] ?></td>
      <td><?= $prod['precio'] ?></td>
      <td>
        <button onclick="editarProducto(<?= $prod['id'] ?>)">Editar</button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
```

## Opciones de Configuración

Cuando llames a `inicializarDataTable()`, puedes pasar opciones personalizadas:

```javascript
// Sin opciones (usa valores por defecto)
inicializarDataTable('#tabla_clientes');

// Con opciones personalizadas
inicializarDataTable('#tabla_productos', {
  pageLength: 25,  // Cambiar elementos por página
  columnDefs: [    // Desabilitar ordenamiento en algunas columnas
    { targets: [0, 5], orderable: false }
  ],
  order: [[1, 'asc']]  // Ordenar por columna 1 ascendente por defecto
});

// Combinación de múltiples opciones
inicializarDataTable('#tabla_servicios', {
  pageLength: 50,
  columnDefs: [
    { targets: 0, orderable: false },
    { targets: 5, searchable: false }
  ],
  order: [[2, 'desc']]
});
```

## Configuración Base (en datatable-init.js)

```javascript
{
  language: {
    url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
  },
  responsive: true,        // Adaptable a dispositivos móviles
  autoWidth: false,        // No ajusta ancho automático
  stateSave: true,         // Guarda filtros, orden, página actual
  pageLength: 10           // 10 filas por página por defecto
}
```

## Dónde están cargados los estilos

En `templates/header.php`:

```html
<!-- DataTables CSS personalizado (ANTES de jQuery) -->
<link rel="stylesheet" href="/fibra-nort/cliente/datatables-custom.css">

<!-- jQuery -->
<script src="/fibra-nort/public/assets/adminlte/plugins/jquery/jquery.min.js"></script>

<!-- Inicialización global de DataTables -->
<script src="/fibra-nort/public/assets/js/datatable-init.js"></script>
```

**Importante:** El CSS se carga en el `<head>` para todos los módulos sin necesidad de hacer nada adicional.

## Personalización Futura

### Si necesitas cambiar estilos globales:
1. Edita `/cliente/datatables-custom.css`
2. El cambio afecta a TODAS las tablas automáticamente

### Si necesitas estilos por módulo:
1. Crea `producto/datatables-custom.css` con tus estilos específicos
2. Carga en el header solo para esa módulo

### Si necesitas cambiar la configuración base:
1. Edita `/public/assets/js/datatable-init.js`
2. Modifica el objeto `configBase`

## Ejemplo Completo

### Archivo: `producto/producto_vista.php`
```html
<div class="col-md-12">
  <h1><i class="fa fa-box text-primary"></i> Productos</h1>
  <h3 id="titulo_producto">Listado de productos</h3>
  <div id="contenedor_tabla_productos">
    <!-- Tabla se inyecta aquí -->
  </div>
</div>

<script src="../producto/producto.js"></script>
```

### Archivo: `producto/producto.js`
```javascript
$(document).ready(function () {
  listarProductos();
});

function listarProductos() {
  $.ajax({
    url: "/fibra-nort/producto/producto_controller.php",
    type: "POST",
    data: { op: "listar" },
    success: function (response) {
      $("#contenedor_tabla_productos").html(response);
      
      // Una sola línea para inicializar con estilos globales
      inicializarDataTable('#tabla_productos', {
        columnDefs: [{ targets: [0, 4], orderable: false }]
      });
    }
  });
}
```

## Resultados Esperados

✅ **Buscador:** Input azul con borde en focus, placeholder "Buscar..."
✅ **Paginación:** Botones azules, alineada a la derecha
✅ **Encabezados:** Fondo azul, texto blanco, legible
✅ **Filas:** Hover effect gris claro
✅ **Responsive:** Se adapta a pantallas pequeñas
✅ **Consistencia:** Todas las tablas se ven igual

## Ventajas del Sistema

1. **DRY (Don't Repeat Yourself):** No repites código CSS en cada módulo
2. **Mantenimiento:** Cambios centralizados
3. **Consistencia:** Todas las tablas se ven igual
4. **Simplicidad:** Una línea de código para inicializar cualquier tabla
5. **Escalabilidad:** Fácil agregar nuevos módulos (clientes, productos, servicios, etc.)

## Próximos Pasos

1. ✅ Crear módulo de Productos con tabla DataTables
2. ✅ Crear módulo de Servicios con tabla DataTables
3. ✅ Crear módulo de Proveedores con tabla DataTables
4. ✅ Todos heredarán automáticamente los estilos globales
