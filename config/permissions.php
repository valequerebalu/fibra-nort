<?php
// config/permissions.php

/**
 * Definición de permisos por Rol
 * Clave: Nombre del Rol (tal cual viene de la BD)
 * Valor: Array de módulos permitidos (match con el nombre de carpeta/view)
 */

return [
    'admin' => [
        'modules' => [
            'dashboard', 
            'cliente', 
            'gestion', 
            'planes',
            'usuarios'
        ],
        'menu_labels' => [
            'gestion' => 'Órdenes'
        ],
        'actions' => [
            'planes.ver',
            'planes.crear',
            'planes.editar',
            'planes.eliminar',
            'orden.ver',
            'orden.crear',
            'orden.editar',
            'orden.eliminar',
            'cliente.ver',
            'cliente.crear',
            'cliente.editar',
            'cliente.ordenes',
            'cliente.eliminar',
            'asignacion.ver',
            'asignacion.crear',
            'asignacion.editar',
            'asignacion.eliminar',
            'usuarios.ver',
            'usuarios.crear',
            'usuarios.editar',
            'usuarios.eliminar'
        ]
    ],
    
    'supervisor' => [
        'modules' => [
            'dashboard', 
            'cliente', 
            'gestion',
            'planes'
        ],
        'menu_labels' => [
            'gestion' => 'Gestión de Asignaciones'
        ],
        'actions' => [
            'cliente.ver',
            'asignacion.ver',
            'asignacion.asignar',
            'asignacion.reasignar',
            'planes.ver',
            'planes.aprobar'
        
        ]
    ],
    
    'vendedor' => [
        'modules' => [
            'dashboard', 
            'cliente', 
            'gestion',
            'planes'
        ],
        'menu_labels' => [
            'gestion' => 'Órdenes'
        ],
        'actions' => [
            'cliente.ver',
            'cliente.crear',
            'cliente.editar',
            'cliente.eliminar',
            'cliente.ordenes',
            'orden.ver',
            'orden.crear',
            'orden.editar',
            'orden.eliminar',
            'planes.ver',
            'planes.crear',
            'planes.editar',
            'planes.eliminar'
        ]
    ],
    
    'tecnico' => [
        'modules' => [
            'dashboard', 
            'gestion'
        ],
        'menu_labels' => [
            'gestion' => 'Gestión de Asignaciones'
        ],
        'actions' => [
            'asignacion.ver_mis_ordenes',
            'asignacion.aceptar',
            'asignacion.rechazar',

        ]
    ],
    
    // Rol por defecto si algo falla
    'guest' => [
        'modules' => ['dashboard'],
        'actions' => []
    ]
];
