<?php
session_start();

// Validar sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: /fibra-nort/auth/login.php');
    exit;
}

require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../templates/navbar.php';
require_once __DIR__ . '/../templates/sidebar.php';

// Cargar Control de Acceso
require_once __DIR__ . '/../core/AccessControl.php';

$view = $_GET['view'] ?? 'dashboard';

$view = preg_replace('/[^a-zA-Z0-9_-]/', '', $view);

// 2. Seguridad: Verificar Permisos RBAC
if (!AccessControl::canAccess($view)) {
    // Si intenta entrar al dashboard y no tiene permiso (raro), o cualquier otro módulo prohibido
    if ($view === 'dashboard') {
        // Fallback extremo o error fatal
        die("Error de configuración: El rol asignado no tiene acceso al Dashboard.");
    }
    
    // Redirigir al dashboard con error (o página 403 personalizada)
    // Usamos JS para alerta o parámetro GET
    // header('Location: /fibra-nort/public/?view=dashboard&error=403');
    // Alternativa: Cargar una vista de error 403 aqui mismo
    require_once __DIR__ . '/../templates/header.php';
    require_once __DIR__ . '/../templates/navbar.php';
    require_once __DIR__ . '/../templates/sidebar.php';
    echo '<div class="content-wrapper">
  
            <section class="content">
                
                <div class="error-page">
                    <h2 class="headline text-warning"> 403</h2>
                    <div class="error-content">
                        <h3><i class="fas fa-exclamation-triangle text-warning"></i> Acceso Denegado.</h3>
                        <p>No tienes permisos para ver este módulo.<br>
                           <a href="/fibra-nort/public/">Volver al Dashboard</a>
                        </p>
                    </div>
                </div>
            </section>
          </div>';
    require_once __DIR__ . '/../templates/footer.php';
    exit;
}

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

