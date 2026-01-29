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
                    <td><?php echo $asignacion['state']; ?></td>
                       
                    </td>
                   
                    <td style="text-align: center;">
                        <?php if (!empty($asignacion['technician']) && $asignacion['technician'] != '---'): ?>
                            <button class="btn btn-xs btn-info" onclick="asignacionForm('R', <?php echo $asignacion['id']; ?>)">
                                <i class="fa fa-sync-alt"></i> Reasignar
                            </button>
                        <?php else: ?>
                            <button class="btn btn-xs btn-success" onclick="asignacionForm('A', <?php echo $asignacion['id']; ?>)">
                                <i class="fa fa-user-plus"></i> Asignar
                            </button>
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