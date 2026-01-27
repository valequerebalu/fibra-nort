<?php
// 1. Definimos la ruta raíz
$root = $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort';

// 2. Cargamos la clase y creamos el objeto aquí mismo
require_once $root . '/cliente/Cliente.class.php';
$objCliente = new Cliente();

// 3. Obtenemos los tipos de documento ANTES de cualquier HTML
$tipos_doc = $objCliente->obtener_tipos_documento();
?>

<section class="content-header">
    <h1>Clientes <small>Listado de clientes</small></h1>
</section>

<section class="content">
    <div class="box">
        <div class="box-header">
            <?php require_once 'cliente_form.php'; ?>
            <button class="btn btn-primary" onclick="cliente_form('I', 0)">
                <i class="fa fa-plus"></i> Nuevo Cliente
            </button>
        </div>
        <div class="box-body">
            <?php
            require_once 'cliente_controller.php'; // Aquí se crea $lista_clientes
            ?>
            <div id="contenedor_tabla_clientes">
                <?php require_once('cliente_tabla.php'); ?>
            </div>
        </div>
    </div>
</section>

<div id="modal_cliente_container"></div> 

<script src="/fibra-nort/cliente/cliente.js"></script>


