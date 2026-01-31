<div class="modal fade" id="modal_registro_plan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="form_registro_plan" method="POST">

                <div class="modal-header bg-primary">
                    <h4 class="modal_titulo_plan" id="modal_titulo_plan"><i class="fa fa-user-plus"></i> Nuevo Plan</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">

                            <label>ID Orden de Venta</label>
                            <div class="input-group">
                                <input type="text" name="code_service_orders" id="code_service_orders" class="form-control">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-primary" id="btn_buscar_cliente" onclick="cargarDatosporId($('#code_service_orders').val());">
                                        <i class="fa fa-search"></i> Buscar
                                    </button>
                                </div>
                            </div>
                            <label>Nombres del Cliente / RUC </label>
                            <input type="text" name="client_name" id="client_name" class="form-control" required>

                            <label>Número de Documento </label>
                            <input type="text" name="document_number" id="document_number" class="form-control" required>

                            <label>Plan </label>
                            <input type="text" name="plan_id" id="plan_id" class="form-control" required>

                            <label>Nodo</label>
                            <select class="form-control" name="nodo_id" id="nodo_id" required>
                                <option value="">-- Seleccione --</option>
                                <?php if (!empty($nodo_data)): ?>
                                    <?php foreach ($nodo_data as $nodo): ?>
                                        <option value="<?php echo $nodo['id']; ?>">
                                            <?php echo $nodo['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option value="" disabled>No hay nodos disponibles</option>
                                <?php endif; ?>
                            </select>


                            <label>Día de Facturación</label>
                            <input type="text" name="billing_day" id="billing_day" class="form-control" required>

                            <label>Fecha de Inicio</label>
                            <div class="input-group date" id="fecha_inicio_plan" data-target-input="nearest">
                                <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#fecha_inicio_plan" required>
                                <div class="input-group-append" data-target="#fecha_inicio_plan" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>

                            <label>Fecha de instalación</label>
                            <div class="input-group date" id="fecha_instalacion_plan" data-target-input="nearest">
                                <input type="text" name="installed_at" class="form-control datetimepicker-input" data-target="#fecha_instalacion_plan" required>
                                <div class="input-group-append" data-target="#fecha_instalacion_plan" data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Tipo de Comprobante</label>
                            <select name="invoice_type" id="invoice_type" class="form-control" required>
                                <option value="">-- Seleccione --</option>
                                <option value="boleta">Boleta</option>
                                <option value="factura">Factura</option>
                            </select>

                            <label>Serie del Router</label>
                            <input type="text" name="router_serial" id="router_serial" class="form-control" required>

                            <label>Modelo del Router</label>
                            <input type="text" name="router_model" id="router_model" class="form-control" required>

                            <label>Direccion IP </label>
                            <input type="text" name="ip_address" id="ip_address" class="form-control" required>

                            <label>Direccion MAC del equipo</label>
                            <input type="text" name="mac_address" id="mac_address" class="form-control" required>

                            <label>Nombre de red</label>
                            <input type="text" name="wifi_ssid" id="wifi_ssid" class="form-control" required>

                            <label>Contraseña WiFi </label>
                            <input type="text" name="wifi_password" id="wifi_password" class="form-control" required>


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
<script src="/fibra-nort/planes/planes_form.js"></script>