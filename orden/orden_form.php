<div class="modal fade" id="modal_registro_orden" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_registro_orden" method="POST">

                <div class="modal-header bg-primary">
                    <h4 class="modal-title"><i class="fa fa-file-invoice"></i> Datos de la Orden de Venta</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">

                            <label>DNI Cliente</label>
                            <div class="input-group">
                                <input type="text" name="document_number" id="document_number" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="btn_buscar_cliente" onclick="cargarDatosporDNI($('#document_number').val());">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <input type="text" name="id_cliente" id="id_cliente" class="form-control" hidden readonly>

                            <label>Apellido Paterno</label>
                            <input type="text" name="paternal_surname" id="paternal_surname" class="form-control" readonly>
                            <label>Fecha Programada</label>
                            <div class="input-group date" id="fecha_programada" data-target-input="nearest">
                                <input type="text" name="scheduled_date" id="scheduled_date" class="form-control datetimepicker-input" data-target="#fecha_programada" required>
                                <div class="input-group-append" data-target="#fecha_programada" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <label>Plan</label>
                            <select class="form-control" name="plan_id" id="plan_id" required>
                                <option value="">-- Seleccione --</option>
                                <?php if (!empty($planes)): ?>
                                    <?php foreach ($planes as $plan): ?>
                                        <option value="<?php echo $plan['id']; ?>">
                                            <?php echo $plan['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay planes disponibles</option>
                                <?php endif; ?>
                            </select>

                            <label>Descripción</label>
                            <input type="text" name="description" id="description" class="form-control" required>


                        </div>
                        <div class="col-md-6">

                            <label>Nombre / Razón Social</label>
                            <input type="text" name="name_or_company_name" id="name_or_company_name" class="form-control" readonly>
                            <label>Apellido Materno</label>
                            <input type="text" name="maternal_surname" id="maternal_surname" class="form-control" required readonly>
                            <label>Hora Programada</label>
                            <div class="input-group date" id="hora_programada" data-target-input="nearest">
                                <input type="text" name="scheduled_time" id="scheduled_time" class="form-control datetimepicker-input" data-target="#hora_programada" required>
                                <div class="input-group-append" data-target="#hora_programada" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-clock"></i>
                                    </div>
                                </div>
                            </div>
                            <label>Fecha de Nacimiento</label>
                            <input type="text" name="date_birth" id="date_birth" class="form-control" required>
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
<script src="/fibra-nort/orden/orden_form.js"></script>