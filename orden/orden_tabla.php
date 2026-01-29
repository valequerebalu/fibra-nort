<table id="tabla_ordenes" class="table table-bordered table-hover table-striped">
    <thead class="bg-primary">
        <tr>
            <th style="width: 10px;"><input type="checkbox" id="check_all"></th>
            <th style="width: 20px;">#</th>
            <th>ID</th>
            <th>Cliente</th>
            <th>Técnico</th>
            <th>Fecha Programada</th>
            <th>Hora Programada</th>
            <th>Plan</th>
            <th>Descripción</th>
             <th>Estado de Orden</th>
            <th style="text-align: center;">Estado</th>
            <th style="text-align: center;">Acciones</th>

        </tr>
    </thead>
    <tbody>
        <?php
     
        if (isset($lista_ordenes['data']) && is_array($lista_ordenes['data'])) {
            foreach ($lista_ordenes['data'] as $indice => $orden) { ?>
                <tr>
                    <td>
                        <input type="checkbox" class="check-orden" value="<?php echo $orden['id']; ?>">
                    </td>
                    <td><?php echo $indice + 1; ?></td>
                    <td>
                        <?php echo $orden['code_orders']; ?>
                    </td>
                    <td>
                        <?php echo $orden['client']; ?></td>

                    <td><?php echo $orden['technician'] ?? '---'; ?></td>
                    <td><?php echo $orden['scheduled_date'] ?? '---'; ?></td>
                    <td><?php echo $orden['scheduled_time'] ?? '---'; ?></td>
                    <td><?php echo $orden['plan']; ?></td>
                    <td><?php echo $orden['description']; ?></td>
                    <td><?php echo $orden['state']; ?></td>
                       
                    </td>
                    <td style="text-align: center;">
                        <?php if ($orden['status'] == 1): ?>
                            <span class="badge bg-green">ACTIVO</span>
                        <?php else: ?>
                            <span class="badge bg-red">INACTIVO</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-xs btn-warning" onclick="orden_form('U', <?php echo $orden['id']; ?>)">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-xs btn-danger" onclick="orden_form(<?php echo $orden['id']; ?>)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="12" class="text-center">No se encontraron órdenes de venta registradas.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>

