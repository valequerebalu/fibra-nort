<?php
require_once 'Gestion.class.php';
session_start();

$gestion = new Gestion();
$op = $_REQUEST['op'] ?? '';

switch ($op) {
    case 'listar':
        // Determinar qué listar basado en el rol/permisos
        require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/AccessControl.php';
        $lista_gestiones = [];
        if (AccessControl::hasPermission('asignacion.ver_mis_ordenes')) {
            $lista_gestiones = $gestion->listar_asignaciones($_SESSION['user_id']);
        } elseif (AccessControl::hasPermission('asignacion.ver')) {
            $lista_gestiones = $gestion->listar_asignaciones();
        } else {
            $lista_gestiones = $gestion->listar_ordenes();
        }
        require_once 'gestion_tabla.php';
        break;

    case 'form':
        $planes = $gestion->obtener_planes();
        require_once 'gestion_form.php';
        break;

    case 'form_asignacion':
        $id = $_POST['id'];
        $orden_data = $gestion->obtener_por_id($id);
        $tecnicos = $gestion->listar_tecnicos();
        $action = $_POST['action'] ?? 'A';
        require_once 'gestion_form.php';
        break;

    case 'form_rechazo':
        $id = $_POST['id'];
        $orden_data = $gestion->obtener_por_id($id);
        $action = 'RECHAZO';
        require_once 'gestion_form.php';
        break;

    case 'insertar':
        $seller_id = $_SESSION['user_id'];
        $technician_id = $_POST['technician_id'] ?? null;
        $cliente_id = $_POST['cliente_id'];
        $scheduled_date = $_POST['scheduled_date'];
        $scheduled_time = $_POST['scheduled_time'];
        $plan_id = $_POST['plan_id'];
        $description = $_POST['description'];
        $created_by = $_SESSION['user_id'];

        echo json_encode($gestion->insertar($seller_id, $technician_id, $cliente_id, $scheduled_date, $scheduled_time, $plan_id, $description, $created_by));
        break;

    case 'obtener_cliente_dni':
        $dni = $_POST['dni'];
        echo json_encode($gestion->obtener_cliente_dni($dni));
        break;

    case 'obtener_por_id':
        $id = $_POST['id'];
        echo json_encode($gestion->obtener_por_id($id));
        break;

    case 'editar':
        $id = $_POST['id'];
        $technician_id = $_POST['technician_id'] ?? null;
        $client_id = $_POST['client_id'];
        $scheduled_date = $_POST['scheduled_date'];
        $scheduled_time = $_POST['scheduled_time'];
        $plan_id = $_POST['plan_id'];
        $description = $_POST['description'];
        $updated_by = $_SESSION['user_id'];

        echo json_encode($gestion->editar($id, $technician_id, $client_id, $scheduled_date, $scheduled_time, $plan_id, $description, $updated_by));
        break;

    case 'eliminar':
        $id = $_POST['id'];
        if ($gestion->eliminar($id)) {
            echo json_encode(['estado' => 1, 'mensaje' => 'Orden eliminada correctamente']);
        } else {
            echo json_encode(['estado' => 0, 'mensaje' => 'Error al eliminar la orden']);
        }
        break;

    case 'asignar_tecnico':
        $orden_id = $_POST['orden_id'];
        $technician_id = $_POST['technician_id'];
        echo json_encode($gestion->asignar_tecnico($orden_id, $technician_id));
        break;

    case 'aceptar_orden':
        $orden_id = $_POST['orden_id'];
        echo json_encode($gestion->aceptar_orden($orden_id));
        break;

    case 'rechazar_orden':
        $orden_id = $_POST['orden_id'];
        $motivo = $_POST['motivo_rechazo'];
        echo json_encode($gestion->rechazar_orden($orden_id, $motivo));
        break;

    case 'listar_tecnicos':
        echo json_encode($gestion->listar_tecnicos());
        break;
        
    case 'obtener_planes':
        echo json_encode($gestion->obtener_planes());
        break;
}
