<div class="modal fade" id="modal_asignacion_tecnico" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_asignacion_tecnico" method="POST">
                <div class="modal-header bg-primary">
                    <h4 class="modal-title" id="titulo_asignacion"><i class="fa fa-user-check"></i> Asignar Técnico - <span id="codigo_orden"><?php echo $orden_data['code_orders'] ?? '---'; ?></span></h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Cliente:</strong></label>
                        <p id="cliente_nombre"><?php echo ($orden_data['name_or_company_name'] ?? '') . ' ' . ($orden_data['paternal_surname'] ?? ''); ?></p>
                    </div>
                    <div class="form-group">
                        <label><strong>Fecha:</strong></label>
                        <p id="fecha_orden"><?php echo $orden_data['scheduled_date'] ?? '---'; ?></p>
                    </div>
                    <div class="form-group">
                        <label for="technician_id"><strong>Seleccionar Técnico</strong></label>
                        <select id="technician_id" name="technician_id" class="form-control" required>
                            <option value="">-- Seleccione un técnico --</option>
                            <?php if (isset($tecnicos)): ?>
                                <?php foreach ($tecnicos as $tecnico): ?>
                                    <option value="<?php echo $tecnico['id']; ?>"><?php echo $tecnico['name']; ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <input type="hidden" id="asignacion_id" value="<?php echo $orden_data['id'] ?? ''; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php if ($action == 'RECHAZO'): ?>
<div class="modal fade" id="modal_rechazar_orden" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_rechazar_orden" method="POST">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title"><i class="fa fa-times-circle"> Rechazar Orden / <?php echo $orden_data['code_orders'] ?? '---'; ?></i> </h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="motivo_rechazo"><strong>Motivo del Rechazo <span class="text-danger">*</span></strong></label>
                        <textarea id="motivo_rechazo" name="motivo_rechazo" class="form-control" rows="4" required maxlength="500"></textarea>
                    </div>
                    <input type="hidden" id="orden_id" value="<?php echo $orden_data['id'] ?? ''; ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Rechazar Orden</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
