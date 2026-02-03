<?php
// core/AccessControl.php

class AccessControl {
    
    private static $permissions = [];

    public static function load() {
        self::$permissions = require __DIR__ . '/../config/permissions.php';
    }

    /**
     * Verifica si el usuario actual tiene acceso al módulo solicitado
     */
    public static function canAccess($view) {
        // Cargar permisos si no están en memoria
        if (empty(self::$permissions)) {
            self::load();
        }

        // Si no hay sesión, denegar (aunque ya se valida antes)
        if (!isset($_SESSION['role'])) {
            return false;
        }

        $userRole = $_SESSION['role']; 
        
        // Normalizar vista 
        $module = strtolower($view);

        // Verificar si el rol existe en la configuración
        if (!isset(self::$permissions[$userRole])) {
            return false;
        }

        // Verificar si el módulo está en la lista permitida para ese rol (ahora dentro de 'modules')
        $allowedModules = self::$permissions[$userRole]['modules'] ?? [];
        return in_array($module, $allowedModules);
    }
    
    /**
     * Verifica si el usuario actual tiene permiso para una acción específica
     * @param string $permission Nombre del permiso (ej: 'planes.crear')
     * @return boolean
     */
    public static function hasPermission($permission) {
        // Cargar permisos si no están en memoria
        if (empty(self::$permissions)) {
            self::load();
        }

        if (!isset($_SESSION['role'])) {
            return false;
        }

        $userRole = $_SESSION['role'];
        
        if (!isset(self::$permissions[$userRole])) {
            return false;
        }
        
        // Verificar si la acción está en la lista permitida
        $allowedActions = self::$permissions[$userRole]['actions'] ?? [];
        return in_array($permission, $allowedActions);
    }
}
