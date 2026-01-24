<table id="tabla_clientes" class="table table-bordered table-hover">
    <thead>
        <tr>
            <th><input type="checkbox" id="check_all"></th> <th>#</th>
            <th>Nombres y apellidos/Raz. Social</th>
            <th>Num. doc.</th>
            <th>Deuda</th>
            <th>Día/pago</th>
            <th>Facturación</th>
           
        </tr>
    </thead>
    <tbody>
        <?php 
        // Aquí simulamos que $lista_clientes viene de tu controlador
        foreach ($lista_clientes as $indice => $cliente) { ?>
            <tr>
                <td><input type="checkbox" class="check-cliente" value="<?php echo $cliente['id']; ?>"></td>
                <td><?php echo $indice + 1; ?></td>
                <td>
                    <?php echo $cliente['nombre']; ?> 
                    <span class="badge bg-green"><?php echo $cliente['meses_deuda']; ?> meses</span>
                </td>
                <td><?php echo $cliente['documento']; ?></td>
                <td><span class="text-danger">S/ <?php echo $cliente['deuda']; ?></span></td>
                <td><?php echo $cliente['dia_pago']; ?></td>
                <td><?php echo $cliente['tipo_facturacion']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>