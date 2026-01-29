<?php
require_once __DIR__ . '/Asignacion.class.php';

$operacion = $_POST['op'] ?? '';
$objAsignacion = new Asignacion();
switch ($operacion) {
    case 'listar':
        $lista_asignaciones = $objAsignacion->listar_asignaciones();
        require_once __DIR__ . '/asignacion_tabla.php';
        break;
}