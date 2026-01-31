<?php
session_start();
require_once __DIR__ . '/Planes.class.php';
$operacion = $_POST['op'] ?? '';
$objPlanes = new Planes();

switch ($operacion) {
    case 'listar':
        $lista_planes = $objPlanes->listar_planes();
        require_once __DIR__ . '/planes_tabla.php';
        break;

    case 'form':
        // Obtener los datos del nodo para mostrar en el modal
        $nodo_data = $objPlanes->obtener_nodo();
        require_once __DIR__ . '/planes_form.php';
        break;

    case 'obtener_orden_code':

        $code_order = $_POST['code_order'] ?? null;

        if (!$code_order) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Código de orden no especificado'
            ]);
            break;
        }

        $orden = $objPlanes->obtener_orden_code($code_order);

        header('Content-Type: application/json');
        if ($orden) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Orden obtenida correctamente',
                'data' => $orden
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Orden no encontrada'
            ]);
        }
        break;
    
    case 'insertar':

        // Capturamos los datos del POST
        
        $client_id = $_POST['client_id'] ?? '';
        $plan_id = $_POST['plan_id'] ?? '';
        $nodo_id = $_POST['nodo_id'] ?? '';
        $billing_day = $_POST['billing_day'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $created_by = $_SESSION['user_id'] ; // ID del usuario de la sesión;
        $order_id = $_POST['order_id'] ?? '';
        $invoice_type = $_POST['invoice_type'];
        $router_serial = $_POST['router_serial'];
        $router_model = $_POST['router_model'];
        $ip_address = $_POST['ip_address'];
        $mac_address = $_POST['mac_address'];
        $wifi_ssid = $_POST['wifi_ssid'];
        $wifi_password = $_POST['wifi_password'];
        $installed_at = $_POST['installed_at'];

        $res = $objPlanes->insertar_plan(
            
            $client_id, 
            $plan_id, 
            $nodo_id, 
            $billing_day, 
            $start_date, 
            $created_by,
            $order_id, 
            $invoice_type, 
            $router_serial,
            $router_model,
            $ip_address,
            $mac_address,
            $wifi_ssid,
            $wifi_password,
            $installed_at);

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Plan insertado correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al insertar el plan'
            ]);
        }
        break;
    
    case 'obtener':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de plan no especificado'
            ]);
            break;
        }

        $plan = $objPlanes->obtener_por_id($id);

        header('Content-Type: application/json');
        if ($plan) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Plan obtenido correctamente',
                'data' => $plan
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Plan no encontrado'
            ]);
        }
        break;

    case 'editar':
        // Capturamos los datos del POST
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de plan no especificado'
            ]);
            break;
        }

        $client_id = $_POST['client_id'] ?? '';
        $plan_id = $_POST['plan_id'] ?? '';
        $nodo_id = $_POST['nodo_id'] ?? '';
        $billing_day = $_POST['billing_day'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $updated_by = $_SESSION['user_id'] ?? null;
        $invoice_type = $_POST['invoice_type'] ?? '';
        $router_serial = $_POST['router_serial'] ?? '';
        $router_model = $_POST['router_model'] ?? '';
        $ip_address = $_POST['ip_address'] ?? '';
        $mac_address = $_POST['mac_address'] ?? '';
        $wifi_ssid = $_POST['wifi_ssid'] ?? '';
        $wifi_password = $_POST['wifi_password'] ?? '';
        $installed_at = $_POST['installed_at'] ?? '';

        $res = $objPlanes->actualizar_plan(
            $id,
            $client_id,
            $plan_id,
            $nodo_id,
            $billing_day,
            $start_date,
            $updated_by,
            $invoice_type,
            $router_serial,
            $router_model,
            $ip_address,
            $mac_address,
            $wifi_ssid,
            $wifi_password,
            $installed_at
        );

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Plan actualizado correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al actualizar el plan'
            ]);
        }
        break;

    case 'eliminar':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de plan no especificado'
            ]);
            break;
        }

        $res = $objPlanes->eliminar_plan($id);

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Plan eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al eliminar el plan'
            ]);
        }
        break;
}       
