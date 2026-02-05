<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/AccessControl.php';

// Determinar el contexto de vista
$es_asignacion = AccessControl::hasPermission('asignacion.ver') || AccessControl::hasPermission('asignacion.ver_mis_ordenes');
?>

<table id="tabla_gestion" class="table table-bordered table-hover table-striped">
    <thead class="bg-primary">
        <tr>
            <th style="width: 10px;"><input type="checkbox" id="check_all"></th>
            <th style="width: 20px;">#</th>
            <th>ID</th>
            <th>Cliente</th>
            <th>Técnico Asignado</th>
            <th>Fecha Programada</th>
            <th>Hora Programada</th>
            <?php if (!$es_asignacion): ?>
                <th>Plan</th>
                <th>Descripción</th>
                <th>Estado Orden</th>
            <?php else: ?>
                <th>Estado Asignación</th>
            <?php endif; ?>
            <th style="text-align: center;">Estado</th>
            <th style="text-align: center;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($lista_gestiones['data']) && is_array($lista_gestiones['data'])) {
            foreach ($lista_gestiones['data'] as $indice => $item) { ?>
                <tr>
                    <td><input type="checkbox" class="check-item" value="<?php echo $item['id']; ?>"></td>
                    <td><?php echo $indice + 1; ?></td>
                    <td><?php echo $item['code_orders']; ?></td>
                    <td><?php echo $item['client']; ?></td>
                    <td><?php echo $item['technician'] ?? '---'; ?></td>
                    <td><?php echo $item['scheduled_date'] ?? '---'; ?></td>
                    <td><?php echo $item['scheduled_time'] ?? '---'; ?></td>
                    
                    <?php if (!$es_asignacion): ?>
                        <td><?php echo $item['plan']; ?></td>
                        <td><?php echo $item['description']; ?></td>
                    <?php endif; ?>

                    <td class="text-center">
                        <?php 
                        $estado = strtoupper($es_asignacion ? $item['status'] : $item['state']);
                        $badgeClass = 'badge-secondary';
                        $iconClass = 'fa-circle';
                        
                        switch ($estado) {
                            case 'CREADO': $badgeClass = 'badge-secondary'; $iconClass = 'fa-plus-circle'; break;
                            case 'ASIGNADO': $badgeClass = 'badge-warning'; $iconClass = 'fa-user-clock'; break;
                            case 'ACEPTADA': case 'EN PROCESO': $badgeClass = 'badge-primary'; $iconClass = 'fa-tools'; break;
                            case 'RECHAZADA': $badgeClass = 'badge-danger'; $iconClass = 'fa-times-circle'; break;
                            case 'ATENDIDO': case 'APROBADO': $badgeClass = 'badge-success'; $iconClass = 'fa-check-circle'; break;
                        }
                        ?>
                        <span class="badge badge-pill <?php echo $badgeClass; ?>" style="font-size: 0.75rem; padding: 4px 8px;">
                            <i class="fa <?php echo $iconClass; ?> mr-1"></i> <?php echo $es_asignacion ? $item['status'] : $item['state']; ?>
                        </span>
                    </td>

                    <td style="text-align: center;">
                        <?php if (($item['status'] ?? 1) == 1): ?>
                            <span class="badge bg-green">ACTIVO</span>
                        <?php else: ?>
                            <span class="badge bg-red">INACTIVO</span>
                        <?php endif; ?>
                    </td>

                    <td style="text-align: center;">
                        <?php if (!$es_asignacion): ?>
                            <?php if (AccessControl::hasPermission('orden.editar')): ?>
                                <button class="btn btn-xs btn-warning" onclick="gestion_form('U', <?php echo $item['id']; ?>)">
                                    <i class="fa fa-edit"></i>
                                </button>
                            <?php endif; ?>
                            <?php if (AccessControl::hasPermission('orden.eliminar')): ?>
                                <button class="btn btn-xs btn-danger" onclick="eliminar_orden(<?php echo $item['id']; ?>)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Botones de Tecnico -->
                            <?php if ($item['status'] == 'ASIGNADO'): ?>
                                <?php if (AccessControl::hasPermission('asignacion.aceptar')): ?>
                                    <button class="btn btn-xs btn-success" onclick="aceptarOrden(<?php echo $item['id']; ?>)">
                                        <i class="fa fa-check"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if (AccessControl::hasPermission('asignacion.rechazar')): ?>
                                    <button class="btn btn-xs btn-danger" onclick="rechazarOrden(<?php echo $item['id']; ?>)">
                                        <i class="fa fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Botones de Gestión (Admin/Supervisor) -->
                            <?php if (!empty($item['technician']) && $item['technician'] != '---'): ?>
                                <?php if (AccessControl::hasPermission('asignacion.reasignar') && ($item['status'] ?? '') != 'APROBADO'): ?>
                                    <button class="btn btn-xs btn-info" onclick="asignacionForm('R', <?php echo $item['id']; ?>)">
                                        <i class="fa fa-sync-alt"></i>
                                    </button>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (AccessControl::hasPermission('asignacion.asignar') && ($item['status'] ?? '') != 'APROBADO'): ?>
                                    <button class="btn btn-xs btn-success" onclick="asignacionForm('A', <?php echo $item['id']; ?>)">
                                        <i class="fa fa-user-plus"></i>
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>   
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="12" class="text-center">No se encontraron registros.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>
