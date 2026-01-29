<div class="modal fade" id="modal_asignacion_tecnico" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form_asignacion_tecnico" method="POST">

                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><i class="fa fa-user-check"></i> Asignar Técnico - <span id="codigo_orden">ORD-000</span></h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-group">
                        <label><strong>Cliente:</strong></label>
                        <p id="cliente_nombre" style="color: #666; margin: 0;">---</p>
                    </div>

                    <div class="form-group">
                        <label><strong>Fecha:</strong></label>
                        <p id="fecha_orden" style="color: #666; margin: 0;">---</p>
                    </div>

                    <div class="form-group">
                        <label for="technician_id"><strong>Seleccionar Técnico</strong></label>
                        <select id="technician_id" name="technician_id" class="form-control" required>
                            <option value="">-- Seleccione un técnico --</option>
                        </select>
                    </div>

                    <input type="hidden" id="asignacion_id" name="asignacion_id">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Asignar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="/fibra-nort/asignacion/asignacion_form.js"></script>
