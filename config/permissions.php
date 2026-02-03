<?php
// config/permissions.php

/**
 * Definición de permisos por Rol
 * Clave: Nombre del Rol (tal cual viene de la BD)
 * Valor: Array de módulos permitidos (match con el nombre de carpeta/view)
 */

return [
    'admin' => [
        'dashboard', 
        'cliente', 
        'orden', 
        'asignacion', 
        'planes',
        'usuarios' // Futuro modulo
    ],
    
    'supervisor' => [
        'dashboard', 
        'cliente', 
        'asignacion',
        'planes'
    ],
    
    'vendedor' => [
        'dashboard', 
        'cliente', 
        'orden',
        'planes'
    ],
    
    'tecnico' => [
        'dashboard', 
        'asignacion' 
    ],
    
    // Rol por defecto si algo falla
    'guest' => ['dashboard']
];
