<table id="tabla_clientes" class="table table-bordered table-hover table-striped">
    <thead class="bg-primary">
        <tr>
            <th style="width: 10px;"><input type="checkbox" id="check_all"></th>
            <th style="width: 20px;">#</th>
            <th>ID</th>
            <th>Nombres Completos/Raz. Social</th>
            <th>Nro. Documento</th>
            <th>Telefono</th>
            <th>Email</th>
            <th>Dirección</th>
            <th>Referencia</th>
            <th style="text-align: center;">Estado</th>
            <th style="text-align: center;">Acciones</th>

        </tr>
    </thead>
    <tbody>
        <?php
        // Suponiendo que el controlador devuelve el array en $lista_clientes['data']
        if (isset($lista_clientes['data']) && is_array($lista_clientes['data'])) {
            foreach ($lista_clientes['data'] as $indice => $cliente) { ?>
                <tr>
                    <td>
                        <input type="checkbox" class="check-cliente" value="<?php echo $cliente['id']; ?>">
                    </td>
                    <td><?php echo $indice + 1; ?></td>
                    <td>
                        <?php echo $cliente['code_clients']; ?>
                    </td>
                    <td>
                        <?php echo $cliente['full_name']; ?></td>
            
                    <td><?php echo $cliente['document_number'] ?? '---'; ?></td>
                    <td><?php echo $cliente['phone'] ?? '---'; ?></td>
                    <td><?php echo $cliente['email'] ?? '---'; ?></td>
                    <td><?php echo $cliente['address']; ?></td>
                    <td><?php echo $cliente['reference']; ?></td>
                    <td style="text-align: center;">
                        <?php if ($cliente['status'] == 1): ?>
                            <span class="badge bg-green">ACTIVO</span>
                        <?php else: ?>
                            <span class="badge bg-red">INACTIVO</span>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;">
                        <button class="btn btn-xs btn-warning" onclick="cliente_form('U', <?php echo $cliente['id']; ?>)">
                            <i class="fa fa-edit"></i>
                        </button>
                        <button class="btn btn-xs btn-danger" onclick="cliente_form(<?php echo $cliente['id']; ?>)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="11" class="text-center">No se encontraron clientes registrados.</td>
            </tr>
        <?php } ?>
    </tbody>
</table>