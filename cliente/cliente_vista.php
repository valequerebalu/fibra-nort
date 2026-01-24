<section class="content-header">
    <h1>Clientes <small>Listado de clientes</small></h1>
</section>

<section class="content">
    <div class="box">
        <div class="box-header">
            <button class="btn btn-primary" onclick="cliente_form('I',0)">Nuevo Cliente</button>
        </div>
        <div class="box-body">
            <?php
            require_once 'cliente_controller.php'; // Aquí se crea $lista_clientes
            ?>
            <div id="div_cliente_tabla">
                <?php require_once('cliente_tabla.php'); ?>
            </div>
        </div>
    </div>
</section>