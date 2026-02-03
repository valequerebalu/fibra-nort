<?php
require_once __DIR__ . '/Asignacion.class.php';
session_start();

$operacion = $_POST['op'] ?? '';
$objAsignacion = new Asignacion();
switch ($operacion) {
    case 'listar':
        
        $technician_id = null;
        if (isset($_SESSION['role_id']) && $_SESSION['role_id'] == 3) {
            $technician_id = $_SESSION['user_id'];
        }
        $lista_asignaciones = $objAsignacion->listar_asignaciones($technician_id);
        require_once __DIR__ . '/asignacion_tabla.php';
        break;
    
    case 'form':
     
        $id = $_POST['id'] ;
   
            // Obtener los datos de la orden para mostrar en el modal
            $orden_data = $objAsignacion->obtener_orden($id);
            // Obtener lista de técnicos disponibles
            $tecnicos = $objAsignacion->listar_tecnicos();
            require_once __DIR__ . '/asignacion_form.php';
        
        break;
    
    case 'asignar':
        $orden_id = $_POST['orden_id'] ?? 0;
        $technician_id = $_POST['technician_id'] ?? 0;
        
        $resultado = $objAsignacion->asignar_tecnico($orden_id, $technician_id);
        header('Content-Type: application/json');
        echo json_encode($resultado);
        break;

    case 'aceptar_orden':
        $orden_id = $_POST['orden_id'] ?? 0;
        $resultado = $objAsignacion->aceptar_orden($orden_id);
        header('Content-Type: application/json');
        echo json_encode($resultado);
        break;

    case 'rechazar_orden':
        $orden_id = $_POST['orden_id'] ?? 0;
        $resultado = $objAsignacion->rechazar_orden($orden_id);
        header('Content-Type: application/json');
        echo json_encode($resultado);
        break;
}