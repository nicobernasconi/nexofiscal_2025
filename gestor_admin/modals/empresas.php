<div class="modal fade bs-empresa-pago-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-empresa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Agregar pago</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="empresaPagoForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="id" name="id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos de la empresa</h4>
                            </div>
                            <div class="panel-body">
                                <input type="hidden" id="empresa_id" name="empresa_id">
                                <input type="hidden" id="gestor_id" name="gestor_id">
                                <input type="hidden" id="empresa_id" name="empresa_id">
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pagado">Pagado</label>
                                    <input type="text" class="form-control" id="pagado" name="pagado" required>
                                </div>
                                <div class="form-group">
                                    <label for="numero_comprobante">Número de Comprobante</label>
                                    <input type="text" class="form-control" id="numero_comprobante" name="numero_comprobante" required>
                                </div>
                                <div class="form-group">
                                    <label for="forma_pago">Forma de Pago</label>
                                    <select class="form-control" id="forma_pago" name="forma_pago" required>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Mercado Pago">Mercado Pago</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                        

                                </div>
                                <div class="form-group">
                                    <label for="monto">Monto</label>
                                    <input type="number" class="form-control" id="monto" name="monto" required>
                                </div>

                                    
                            </div>
                        </div>

                       
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-empresa">Guardar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

