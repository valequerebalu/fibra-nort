<?php
$root = $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort';

require_once $root . '/asignacion/Asignacion.class.php';
require_once $root . '/core/AccessControl.php';
$objAsignacion = new Asignacion();

?>

<section class="content-header">
    <div class="row align-items-center" style="margin: 15px;" class="container">
        <div class="col-sm-6">
            <h1><i class="fa fa-layer-group text-primary"></i> Planes</h1>
            <h3 id="titulo_planes" style="color: #666; margin-top: 10px;">Listado de planes de clientes</h3>
        </div>
        
        <?php if (AccessControl::hasPermission('planes.crear')): ?>
        <div class="col-sm-6 text-right">
             <button class="btn btn-primary" onclick="planForm('I', 0)">
                <i class="fa fa-plus"></i> Nuevo Plan
            </button>
        </div>
        <?php endif; ?>

    </div>
</section>
<section class="content">
    <div class="box box-primary">
        <div class="box-body">
            <div id="contenedor_tabla_planes">
            </div>
        </div>
    </div>
</section>
<div id="modal_planes_container"></div>
<script src="/fibra-nort/planes/planes.js"></script>
