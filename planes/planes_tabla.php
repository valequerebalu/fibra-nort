<table id="tabla_planes" class="table table-bordered table-hover table-striped">
    <thead class="bg-primary">
        <tr>
            <th style="width: 10px;"><input type="checkbox" id="check_all"></th>
            <th style="width: 20px;">#</th>
            <th>ID Plan-Cliente</th>
            <th>Nro.Documento</th>
            <th>Nombre del Cliente</th>
            <th>Plan</th>
            <th>Precio Mensual</th>
            <th>Velocidad mbps</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th style="text-align: center;">Estado</th>
            <th style="text-align: center;">Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (isset($lista_planes['data']) && is_array($lista_planes['data'])) {
            foreach ($lista_planes['data'] as $indice => $plan) { ?>
                <tr>
                    <td>
                        <input type="checkbox" class="check-orden" value="<?php echo $plan['id']; ?>">
                    </td>
                    <td><?php echo $indice + 1; ?></td>
                    <td>
                        <?php echo $plan['code_client_plan']; ?>
                    </td>
                    <td>
                        <?php echo $plan['document_number']; ?>
                    </td>
                    <td><?php echo $plan['client_name']; ?></td>
                    <td><?php echo $plan['plan_name']; ?></td>
                    <td><?php echo 'S/.' . number_format($plan['monthly_price'], 2); ?></td>
                    <td><?php echo $plan['speed_mbps']; ?></td>
                    <td><?php echo $plan['start_date']; ?></td>
                    <td><?php echo $plan['end_date']; ?></td>
                    <td style="text-align: center;">
                        <?php if ($plan['status'] == 1): ?>
                            <span class="badge bg-green">ACTIVO</span>
                        <?php else: ?>
                            <span class="badge bg-red">INACTIVO</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-xs btn-warning" onclick="cargarFormularioPlan('U', <?php echo $plan['id']; ?>)">
                            <i class="fa fa-edit "></i>
                        </button>
                        <button class="btn btn-xs btn-danger" onclick="cargarFormularioPlan('D', <?php echo $plan['id']; ?>)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="11" style="text-align: center;">No hay planes disponibles.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>