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
            'orden', 
            'asignacion', 
            'planes',
            'usuarios'
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
            'asignacion',
            'planes'
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
            'orden',
            'planes'
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
            'asignacion'
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
