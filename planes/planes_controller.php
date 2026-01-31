<?php
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
}
