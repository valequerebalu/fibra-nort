<div class="modal fade" id="modal_registro_cliente" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_registro_cliente" method="POST">
              
                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><i class="fa fa-user-plus"></i> Datos del Cliente</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            
                                <label>Tipo de Documento</label>

                                <select class="form-control" name="document_type_id" id="document_type_id" required>
                                    <option value="">-- Seleccione --</option>
                                    <?php if (!empty($tipos_doc)): ?>
                                        <?php foreach ($tipos_doc as $tipo): ?>
                                            <option value="<?php echo $tipo['id']; ?>">
                                                <?php echo $tipo['code']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="" disabled>No hay tipos de documento disponibles</option>
                                    <?php endif; ?>
                                </select>
                       
                            <label>Teléfono</label>
                            <input type="text" name="phone" id="phone" class="form-control">
                            <label>Nombres</label>
                            <input type="text" name="full_name" id="full_name" class="form-control" required>
                             <label>Email</label>
                            <input type="text" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Documento</label>
                            <input type="text" name="document_number" id="document_number" class="form-control" required>

                            <label>Dirección</label>
                            <input type="text" name="address" id="address" class="form-control" required>
                            <label>Referencia</label>
                            <input type="text" name="reference" id="reference" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="/fibra-nort/cliente/cliente_form.js"></script>