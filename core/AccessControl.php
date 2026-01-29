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

        $userRole = $_SESSION['role']; // Ej: 'Administrador'
        
        // Normalizar vista (por si viene con query params o algo)
        $module = strtolower($view);

        // Si el rol es Super Admin (ej: acceso total hardcodeado opcional)
        // if ($userRole === 'SuperAdmin') return true;

        // Verificar si el rol existe en la configuración
        if (!isset(self::$permissions[$userRole])) {
            // Si el rol no está definido, denegar por seguridad (o dar acceso básico)
            return false;
        }

        // Verificar si el módulo está en la lista permitida para ese rol
        return in_array($module, self::$permissions[$userRole]);
    }
}
