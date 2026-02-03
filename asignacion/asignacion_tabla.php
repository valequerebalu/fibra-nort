<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/AccessControl.php';
?>

<table id ="tabla_asignacion" class="table table-bordered table-hover table-striped">
    <thead class="bg-primary">
        <tr>
            <th style="width: 10px;"><input type="checkbox" id="check_all_asignacion"></th>
            <th style="width: 20px;">#</th>
            <th>ID Orden</th>
            <th>Cliente</th>
            <th>Técnico Asignado</th>
            <th>Fecha de Asignación</th>
            <th>Hora de Asignación</th>
            <th>Estado de Asignación</th>
            <th style="text-align: center;">Acciones</th>

        </tr>
    </thead>
    <tbody>
        <?php
     
        if (isset($lista_asignaciones['data']) && is_array($lista_asignaciones['data'])) {
            foreach ($lista_asignaciones['data'] as $indice => $asignacion) { ?>
                <tr>
                    <td>
                        <input type="checkbox" class="check-asignacion" value="<?php echo $asignacion['id']; ?>">
                    </td>
                    <td><?php echo $indice + 1; ?></td>
                    <td>
                        <?php echo $asignacion['code_orders']; ?>
                    </td>
                    <td>
                        <?php echo $asignacion['client']; ?></td>

                    <td><?php echo $asignacion['technician'] ?? '---'; ?></td>
                    <td><?php echo $asignacion['scheduled_date'] ?? '---'; ?></td>
                    <td><?php echo $asignacion['scheduled_time'] ?? '---'; ?></td>
                    <td class="text-center">
                        <?php 
                        $estado = strtoupper($asignacion['status']);
                        $badgeClass = 'badge-secondary';
                        $iconClass = 'fa-circle';
                        
                        switch ($estado) {
                            case 'CREADO':
                                $badgeClass = 'badge-secondary';
                                $iconClass = 'fa-plus-circle';
                                break;
                            case 'ASIGNADO':
                                $badgeClass = 'badge-warning'; 
                                $iconClass = 'fa-user-clock';
                                break;
                            case 'ACEPTADA':
                                $badgeClass = 'badge-primary'; // Azul para indicar proceso
                                $iconClass = 'fa-tools';
                                break;
                            case 'RECHAZADA':
                                $badgeClass = 'badge-danger';
                                $iconClass = 'fa-times-circle';
                                break;
                            case 'APROBADO':
                                $badgeClass = 'badge-success';
                                $iconClass = 'fa-check-circle';
                                break;
                        }
                        ?>
                        <span class="badge badge-pill <?php echo $badgeClass; ?>" style="font-size: 0.75rem; padding: 4px 8px;">
                            <i class="fa <?php echo $iconClass; ?> mr-1"></i> <?php echo $asignacion['status']; ?>
                        </span>
                    </td>
                       
                    </td>
                   
                    <td style="text-align: center;">
                       

                        <!-- Botones de Tecnico -->
                        <?php if ($asignacion['status'] == 'ASIGNADO'): ?>
                            <?php if (AccessControl::hasPermission('asignacion.aceptar')): ?>
                                <button class="btn btn-sm btn-success" onclick="aceptarOrden(<?php echo $asignacion['id']; ?>)">
                                    <i class="fa fa-check"></i> Aceptar
                                </button>
                            <?php endif; ?>
                            
                            <?php if (AccessControl::hasPermission('asignacion.rechazar')): ?>
                                <button class="btn btn-sm btn-danger" onclick="rechazarOrden(<?php echo $asignacion['id']; ?>)">
                                    <i class="fa fa-times"></i> Rechazar
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>

                        <!-- Botones de Gestión (Admin/Supervisor) -->
                        <?php if (!empty($asignacion['technician']) && $asignacion['technician'] != '---'): ?>
                            <?php if (AccessControl::hasPermission('asignacion.reasignar') && 
                                  ($asignacion['status'] ?? '') != 'APROBADO'): ?>
                                <button class="btn btn-sm btn-info" onclick="asignacionForm('R', <?php echo $asignacion['id']; ?>)">
                                    <i class="fa fa-sync-alt"></i> Reasignar
                                </button>
                            <?php endif; ?>
                        <?php else: ?>
                            <?php if (AccessControl::hasPermission('asignacion.asignar') && 
                                  ($asignacion['status'] ?? '') != 'APROBADO'): ?>
                                <button class="btn btn-sm btn-success" onclick="asignacionForm('A', <?php echo $asignacion['id']; ?>)">
                                    <i class="fa fa-user-plus"></i> Asignar
                                </button>
                            <?php endif; ?>
                        <?php endif; ?>      
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="9" class="text-center">No se encontraron asignaciones registradas.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>