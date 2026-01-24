<?php

// Simulamos las clases por ahora ya que no hay BD
// require_once('../permiso/Permiso.class.php');
// require_once('../cliente/Cliente.class.php');

// --- PARTE 1: DATOS DE PRUEBA (MOCK DATA) ---
// Pegamos la lista aquí para que esté disponible para la tabla
$lista_clientes = [
    [
        "id" => 1,
        "nombre" => "NICK ANTONY VELARDE RUIZ",
        "documento" => "70021458",
        "deuda" => "0.00",
        "meses_deuda" => 0,
        "dia_pago" => 1,
        "tipo_facturacion" => "Postpago"
    ],
    [
        "id" => 2,
        "nombre" => "MILDRED WENDY CANAZA QUISPE",
        "documento" => "70099589",
        "deuda" => "10.00",
        "meses_deuda" => 1,
        "dia_pago" => 1,
        "tipo_facturacion" => "Postpago"
    ]
];

// --- PARTE 2: PROCESO DE ACCIONES (POST) ---
// Solo entra aquí si viene una acción por AJAX
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $data = ['estado' => 0, 'mensaje' => ''];

    switch ($action) {
        case 'insertar':
            // Aquí iría la lógica de $oCliente->insertar(...)
            $data['estado'] = 1;
            $data['mensaje'] = 'Cliente registrado correctamente (Simulado).';
            break;

        case 'eliminar':
            $id = intval($_POST['hdd_cliente_id']);
            $data['estado'] = 1;
            $data['mensaje'] = 'Cliente ID ' . $id . ' eliminado (Simulado).';
            break;

        default:
            $data['mensaje'] = 'Acción no reconocida: ' . $action;
            break;
    }

    echo json_encode($data);
    exit; // Importante detener la ejecución aquí si es una respuesta JSON
}