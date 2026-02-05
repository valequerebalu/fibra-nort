<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/fibra-nort/core/AccessControl.php';
?>
<table id="tabla_planes" class="table table-bordered table-hover table-striped">
    <thead class="bg-primary">
        <tr>
            <th style="width: 10px;"><input type="checkbox" id="check_all"></th>
            <th style="width: 20px;">#</th>
            <th>Código Orden Servicio</th>
            <th>ID Plan-Cliente</th>
            <th>Cliente</th>
            <th>Plan</th>
            <th>Tipo Factura</th>
            <th>Serial Router</th>
            <th>Modelo Router</th>
            <th>Dirección IP</th>
            <th>Dirección MAC</th>
            <th>SSID WiFi</th>
            <th>Contraseña WiFi</th>
            <th>Estado Servicio</th>
            <th>Fecha Instalación</th>
            <th>Fecha Inicio</th>
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
                        <?php echo $plan['code_service_orders'] ?? 'N/A'; ?>
                    </td>
                    <td>
                        <?php echo $plan['code_client_plan']; ?>
                    </td>
                    <td><?php echo $plan['client_name']; ?></td>
                    <td><?php echo $plan['plan_name']; ?></td>
                    <td><?php echo $plan['invoice_type'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['router_serial'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['router_model'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['ip_address'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['mac_address'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['wifi_ssid'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['wifi_password'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['service_status'] ?? 'Activo'; ?></td>
                    <td><?php echo $plan['installed_at'] ?? 'N/A'; ?></td>
                    <td><?php echo $plan['start_date']; ?></td>
                    <td style="text-align: center;">
                        <?php if ($plan['status'] == 1): ?>
                            <span class="badge bg-green">ACTIVO</span>
                        <?php else: ?>
                            <span class="badge bg-red">INACTIVO</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <?php if (AccessControl::hasPermission('planes.editar')): ?>
                            <button class="btn btn-xs btn-warning" onclick="planForm('U', <?php echo $plan['id']; ?>)">
                                <i class="fa fa-edit "></i>
                            </button>
                        <?php endif; ?>
                        
                        <?php if (AccessControl::hasPermission('planes.eliminar')): ?>
                            <button class="btn btn-xs btn-danger" onclick="planForm(<?php echo $plan['id']; ?>)">
                                <i class="fa fa-trash"></i>
                            </button>
                        <?php endif; ?>

                        <?php if (AccessControl::hasPermission('planes.aprobar') && 
                                  ($plan['plan_state_code'] ?? '') != 'APROBADO'): ?>
                            <button class="btn btn-xs btn-success" onclick="aprobarPlan(<?php echo $plan['id']; ?>)">
                                <i class="fa fa-check"></i> Aprobar
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="18" style="text-align: center;">No hay planes disponibles.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>