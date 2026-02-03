<?php
// 1. Definimos la ruta raíz
$root = $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort';

require_once $root . '/asignacion/Asignacion.class.php';
$objAsignacion = new Asignacion();

?>

<section class="content-header" >
    <div class="row align-items-center" style="margin: 15px;" class="container">
        <div class="col-sm-6">
            <h1><i class="fa fa-user-cog text-primary"></i> Asignación de Técnicos</h1>
            <h3 id="titulo_orden" style="color: #666; margin-top: 10px;">Listado de ordenes de Venta</h3>
        </div>

    </div>
</section>

<section class="content">
    <div class="box box-primary">

        <div class="box-body">
            <div id="contenedor_tabla_asignacion">
            </div>
        </div>
    </div>
</section>

<div id="modal_asignacion_container"></div>

<script src="/fibra-nort/asignacion/asignacion.js"></script>