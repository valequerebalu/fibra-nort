<div class="modal fade" id="modal_registro_cliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_registro_cliente" method="POST">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Datos del Cliente</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            
                                <label>Tipo de Documento</label>

                                <select class="form-control" name="document_type_id" id="document_type_id" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php foreach ($tipos_doc as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>">
                                            <?php echo $tipo['code']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                       
                            <label>Teléfono</label>
                            <input type="text" name="phone" class="form-control">
                            <label>Nombres</label>
                            <input type="text" name="full_name" class="form-control" required>
                             <label>Email</label>
                            <input type="text" name="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Documento</label>
                            <input type="text" name="document_number" class="form-control" required>

                            <label>Dirección</label>
                            <input type="text" name="address" class="form-control" required>
                            <label>Referencia</label>
                            <input type="text" name="reference" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="../cliente/cliente_form.js"></script>