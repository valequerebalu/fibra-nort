<?php
session_start();
require_once __DIR__ . '/Orden.class.php';

$operacion = $_POST['op'] ?? '';

$objOrden = new Orden();

switch ($operacion) {
    case 'listar':
        $lista_ordenes = $objOrden->listar_ordenes();
        require_once __DIR__ . '/orden_tabla.php';
        break;

    case 'form':

        $planes = $objOrden->obtener_planes();
        require_once __DIR__ . '/orden_form.php';
        break;

    case 'obtener_cliente_dni':

        $document_number = $_POST['document_number'] ?? null;

        if (!$document_number) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Número de documento no especificado'
            ]);
            break;
        }

        $cliente = $objOrden->obtener_cliente_dni($document_number);

        header('Content-Type: application/json');
        if ($cliente) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Cliente obtenido correctamente',
                'data' => $cliente
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Cliente no encontrado'
            ]);
        }
        break;

    case 'insertar':
        // Capturamos los datos del POST
        $cliente_id = $_POST['id_cliente'];
        $scheduled_date  = $_POST['scheduled_date'];
        $scheduled_time        = $_POST['scheduled_time'];
        $plan_id            = $_POST['plan_id'] ?? null;
        $description            = $_POST['description'] ?? null;

        // Valores por defecto para la lógica de negocio
        $created_by = $_SESSION['user_id'] ; // ID del usuario de la sesión;
        $seller_id = $_SESSION['user_id'] ; 
        $technician_id = null;
     

        // Llamamos al método insertar con los datos reales
        $res = $objOrden->insertar(
            $seller_id,
            $technician_id,
            $cliente_id,
            $scheduled_date,
            $scheduled_time,
            $plan_id,
            $description,
            $created_by
        );

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Orden insertada correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al insertar la orden'
            ]);
        }
        break;

    case 'obtener':
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de orden no especificado'
            ]);
            break;
        }

        $orden = $objOrden->obtener_por_id($id);

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

    case 'editar':
        // Obtener el ID de la orden a editar
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de orden no especificado'
            ]);
            break;
        }

        // Capturamos los datos del POST
       
        $technician_id = $_POST['technician_id'] ?? null;
        $client_id = $_POST['id_cliente'] ;
        $scheduled_date = $_POST['scheduled_date'];
        $scheduled_time = $_POST['scheduled_time'];
        $plan_id = $_POST['plan_id'] ?? null;
        $description = $_POST['description'] ?? null;
      
        $updated_by = $_SESSION['user_id'] ;

        // Llamamos al método editar con los datos reales
        $res = $objOrden->editar(
            $id,
            $technician_id,
            $client_id,
            $scheduled_date,
            $scheduled_time,
            $plan_id,
            $description,
            $updated_by
        );

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Orden actualizada correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al actualizar la orden'
            ]);
        }
        break;
    case 'eliminar':
        // Obtener el ID de la orden a eliminar
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de orden no especificado'
            ]);
            break;
        }

        // Llamamos al método eliminar con el ID real
        $res = $objOrden->eliminar($id);

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Orden eliminada correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al eliminar la orden'
            ]);
        }
        break;

    default:
        header('HTTP/1.1 400 Bad Request');
        echo 'Operación no válida';
        break;

    
}
