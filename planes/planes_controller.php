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
        $id = $_POST['id'] ?? 0;
        $plan_data = null;
        if ($id > 0) {
            // Obtener los datos del plan para mostrar en el modal
            $plan_data = $objPlanes->obtener_plan($id);
        }
        require_once __DIR__ . '/planes_form.php';
        break;
    

}