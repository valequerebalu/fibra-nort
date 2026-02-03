<?php
// 1. Definimos la ruta raíz
$root = $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort';

// 2. Cargamos la clase y creamos el objeto aquí mismo
require_once $root . '/cliente/Cliente.class.php';
$objCliente = new Cliente();

?>

<section class="content-header">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1><i class="fa fa-users text-primary"></i> Clientes</h1>
            <h3 id="titulo_cliente" style="color: #666; margin-top: 10px;">Listado de clientes</h3>
        </div>
        <div class="col-sm-6 text-right">
            <button class="btn btn-primary btn-lg" onclick="cargarFormularioCliente('I', 0);">
                <i class="fa fa-plus"></i> Nuevo Cliente
            </button>
        </div>
    </div>
</section>

<section class="content">
    <div class="box box-primary">
      
        <div class="box-body">
            <div id="contenedor_tabla_clientes">
            </div>
        </div>
    </div>
</section>

<div id="modal_cliente_container"></div>

<div id="modal_orden_container"></div>

<script src="/fibra-nort/cliente/cliente.js"></script>
<script src="/fibra-nort/orden/orden.js"></script> 
<script src="/fibra-nort/orden/orden_form.js"></script>




