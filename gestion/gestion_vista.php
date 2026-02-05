<?php
require_once 'Gestion.class.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/AccessControl.php';

$gestion = new Gestion();

// Cargar datos según permisos
if (AccessControl::hasPermission('asignacion.ver_mis_ordenes')) {
    $lista_gestiones = $gestion->listar_asignaciones($_SESSION['user_id']);
} elseif (AccessControl::hasPermission('asignacion.ver')) {
    $lista_gestiones = $gestion->listar_asignaciones();
} else {
    $lista_gestiones = $gestion->listar_ordenes();
}
?>

<div class="container-fluid">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo AccessControl::getModuleLabel('gestion'); ?></h1>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Listado de <?php echo AccessControl::getModuleLabel('gestion'); ?></h3>
                    <div class="card-tools">
                        <?php if (AccessControl::hasPermission('orden.crear')): ?>
                            <button class="btn btn-primary btn-sm" onclick="gestion_form('I', 0)">
                                <i class="fas fa-plus"></i> Nueva Orden
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div id="contenedor_tabla_gestion">
                        <?php require_once 'gestion_tabla.php'; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Container para formularios dinámicos -->
<div id="modal_gestion_container"></div>
<div id="modal_asignacion_container"></div>
<div id="modal_rechazo_container"></div>

<script src="/fibra-nort/gestion/gestion.js"></script>
