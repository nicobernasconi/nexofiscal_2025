<div class="modal fade bs-proveedor-crear-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-proveedor">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Crear Proveedores</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="proveedorForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="id" name="id">
                    <div class="panel-group">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Proveedores</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="razon_social">Razón Social</label>
                                    <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                                </div>

                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" required>
                                </div>

                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>


                                <div class="form-group">
                                    <label for="cuit">CUIT</label>
                                    <input type="number" class="form-control" id="cuit" name="cuit" maxlength="11" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-editar-proveedor">Editar</button>
             
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>