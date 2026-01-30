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

    case 'form':
        // Obtenemos los tipos de documento para el formulario
        $tipos_doc = $objCliente->obtener_tipos_documento();
        // Debug: verificar si hay datos
        error_log('tipos_doc: ' . json_encode($tipos_doc));
        require_once __DIR__ . '/cliente_form.php';
        break;

    case 'obtener':
        // Obtener datos de un cliente específico
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de cliente no especificado'
            ]);
            break;
        }

        $cliente = $objCliente->obtener_por_id($id);

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
        $document_type_id = $_POST['document_type_id'];
        $document_number  = $_POST['document_number'];
        $name_or_company_name = $_POST['name_or_company_name'];
        $maternal_surname = $_POST['maternal_surname'];
        $paternal_surname = $_POST['paternal_surname'];
        $sex              = $_POST['sex'];
        $phone            = $_POST['phone'] ?? null;
        $email            = $_POST['email'] ?? null;
        $address          = $_POST['address'];
        $reference        = $_POST['reference'] ?? '';

        // Valores por defecto para la lógica de negocio
        $state_id   = 1; // Activo
        $created_by = 1; // ID del usuario (luego lo sacarás de $_SESSION)

        // Llamamos al método insertar con los datos reales
        $res = $objCliente->insertar(
            $document_type_id,
            $document_number,
            $name_or_company_name,
            $maternal_surname,
            $paternal_surname,
            $sex,
            $phone,
            $email,
            $address,
            $reference,
            $state_id,
            $created_by
        );

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Cliente insertado correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al insertar el cliente'
            ]);
        }
        break;

    case 'editar':
        // Obtener el ID del cliente a editar
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de cliente no especificado'
            ]);
            break;
        }

        // Capturamos los datos del POST
        $document_type_id = $_POST['document_type_id'];
        $document_number  = $_POST['document_number'];
        $name_or_company_name = $_POST['name_or_company_name'];
        $maternal_surname = $_POST['maternal_surname'];
        $paternal_surname = $_POST['paternal_surname'];
        $sex              = $_POST['sex'];
        $phone            = $_POST['phone'] ?? null;
        $email            = $_POST['email'] ?? null;
        $address          = $_POST['address'];
        $reference        = $_POST['reference'] ?? '';

        // Valores por defecto para la lógica de negocio
        $state_id = 1; // Activo
        $updated_by = 1;

        // Llamamos al método editar con los datos reales
        $res = $objCliente->editar(
            $id,
            $document_type_id,
            $document_number,
            $full_name,
            $phone,
            $email,
            $address,
            $reference,
            $state_id,
            $updated_by
        );

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Cliente actualizado correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al actualizar el cliente'
            ]);
        }
        break;
    case 'eliminar':
        // Obtener el ID del cliente a eliminar
        $id = $_POST['id'] ?? null;

        if (!$id) {
            header('Content-Type: application/json');
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'ID de cliente no especificado'
            ]);
            break;
        }

        // Llamamos al método eliminar
        $res = $objCliente->eliminar($id);

        header('Content-Type: application/json');
        if ($res) {
            echo json_encode([
                'estado' => 1,
                'mensaje' => 'Cliente eliminado correctamente'
            ]);
        } else {
            echo json_encode([
                'estado' => 0,
                'mensaje' => 'Error al eliminar el cliente'
            ]);
        }
        break;
}
