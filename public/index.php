<?php

require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
require_once __DIR__ . '/../templates/sidebar.php';
// Contenido principal (temporal)
echo '<div class="content-wrapper">';
$view = $_GET['view'] ?? 'dashboard';

switch ($view) {
    case 'cliente':
        require_once __DIR__ . '/../cliente/index.php';
        break;

    default:
        require_once __DIR__ . '/../app/views/dashboard/index.php';
        break;
}

echo '</div>';
require_once __DIR__ . '/../templates/footer.php';