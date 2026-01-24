<?php

require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
require_once __DIR__ . '/../templates/sidebar.php';

$view = $_GET['view'] ?? 'dashboard';

$view = preg_replace('/[^a-zA-Z0-9_-]/', '', $view);

$modulePath = __DIR__ . "/../$view/";

echo '<div class="content-wrapper">';

if (file_exists($modulePath . "{$view}_vista.php")) {
    require_once $modulePath . "{$view}_vista.php";
} elseif (file_exists($modulePath . "index.php")) {
    require_once $modulePath . "index.php";
} else {
    // Si no existe la carpeta o el archivo, cargamos el dashboard o un 404
    require_once __DIR__ . '/../app/views/dashboard/index.php';
}

echo '</div>';
require_once __DIR__ . '/../templates/footer.php';

