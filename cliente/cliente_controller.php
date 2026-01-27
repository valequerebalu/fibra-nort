<?php
require_once __DIR__ . '/Cliente.class.php';

// Capturamos la operación (op) que envía el JS
$operacion = $_POST['op'] ?? '';

$objCliente = new Cliente();

switch ($operacion) {
    case 'listar':
        // Llamamos al método de la clase que ya tiene PDO y Singleton
        $lista_clientes = $objCliente->listar_clientes();
        require_once __DIR__ . '/cliente_tabla.php';
        break;

  case 'insertar':
    $action = $_POST['action']; 

    // Capturamos los datos del POST
    $document_type_id = $_POST['document_type_id'];
    $document_number  = $_POST['document_number'];
    $full_name        = $_POST['full_name'];
    $phone            = $_POST['phone'] ?? null;
    $email            = $_POST['email'] ?? null;
    $address          = $_POST['address'];
    $reference        = $_POST['reference'] ?? '';
    
    // Valores por defecto para la lógica de negocio
    $state_id   = 1; // Activo
    $created_by = 1; // ID del usuario (luego lo sacarás de $_SESSION)

    if ($action == 'I') {
        // Llamamos al método insertar con los datos reales
        $res = $objCliente->insertar(
            $document_type_id,
            $document_number,
            $full_name,
            $phone,
            $email,
            $address,
            $reference,
            $state_id,
            $created_by
        );
        echo $res; // Esto devuelve "1" si el PDO ejecutó correctamente
    } else {
        // Aquí iría la lógica de editar cuando la necesites
        // echo $objCliente->editar($id, ...);
    }
    break;
}
