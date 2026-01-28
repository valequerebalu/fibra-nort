<?php
// 1. Definimos la ruta raíz
$root = $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort';

require_once $root . '/orden/Orden.class.php';
$objOrden = new Orden();

?>

<section class="content-header">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h1><i class="fa fa-tasks text-primary"></i> Ordenes</h1>
            <h3 id="titulo_orden" style="color: #666; margin-top: 10px;">Listado de ordenes de Venta</h3>
        </div>
        <div class="col-sm-6 text-right">
            <button class="btn btn-primary btn-lg" onclick="">
                <i class="fa fa-plus"></i> Nueva Orden
            </button>
        </div>
    </div>
</section>

<section class="content">
    <div class="box box-primary">
      
        <div class="box-body">
            <div id="contenedor_tabla_ordenes">
            </div>
        </div>
    </div>
</section>

<div id="modal_orden_container"></div>

<script src="/fibra-nort/orden/orden.js"></script> 