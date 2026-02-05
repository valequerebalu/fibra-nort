<?php
$action = $action ?? $_POST['action'] ?? 'I'; 
?>

<?php if ($action == 'I' || $action == 'U'): ?>
    <!-- Formulario de Orden (del archivo orden_form.php original) -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/orden/orden_form.php'; ?>
<?php elseif ($action == 'A' || $action == 'R'): ?>
    <!-- Formulario de Asignación (del archivo asignacion_form.php original) -->
    <?php require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/asignacion/asignacion_form.php'; ?>
<?php elseif ($action == 'RECHAZO'): ?>
    <!-- Formulario de Rechazo (del archivo asignacion_form.php original, asumiendo que maneja rechazo) -->
    <?php 
    // Necesito revisar cómo maneja el rechazo asignacion_form.php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/gestion/asignacion_form.php'; 
    ?>
<?php endif; ?>

<script src="/fibra-nort/gestion/gestion_form.js"></script>
