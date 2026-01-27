<?php

$rootPath = $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort';
$envFile = $rootPath . '/.env';

// Verificamos si el archivo existe usando la ruta absoluta
if (!file_exists($envFile)) {
    // Si falla, imprimimos la ruta que PHP está intentando buscar para debuguear
    die("Error: El archivo .env no existe en: " . $envFile);
}

$env = parse_ini_file($envFile);

define('DB_HOST', $env['DB_HOST']);
define('DB_NAME', $env['DB_NAME']);
define('DB_USER', $env['DB_USER']);
define('DB_PASS', $env['DB_PASS']);
