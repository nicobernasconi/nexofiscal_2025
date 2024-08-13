<div class="modal fade bs-sucursal-crear-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-sucursal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Crear sucursal</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="sucursalForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="empresa_id" name="empresa_id" value="<?php echo $_GET['id'] ?>">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos de la sucursal</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
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
                                    <label for="contacto_nombre">Nombre de contacto</label>
                                    <input type="text" class="form-control" id="contacto_nombre" name="contacto_nombre" >
                                </div>
                                <div class="form-group">
                                    <label for="contacto_telefono">Teléfono de contacto</label>
                                    <input type="text" class="form-control" id="contacto_telefono" name="contacto_telefono" >
                                </div>
                                <div class="form-group">
                                    <label for="contacto_email">Email de contacto</label>
                                    <input type="email" class="form-control" id="contacto_email" name="contacto_email" >
                                </div>
                                <div class="form-group">
                                    <label for="referente_nombre">Nombre de referente</label>
                                    <input type="text" class="form-control" id="referente_nombre" name="referente_nombre" >
                                </div>
                                <div class="form-group">
                                    <label for="referente_email">Email de referente</label>
                                    <input type="email" class="form-control" id="referente_email" name="referente_email" >
                                </div>
                                <div class="form-group">
                                    <label for="referente_telefono">Teléfono de referente</label>
                                    <input type="text" class="form-control" id="referente_telefono" name="referente_telefono" >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-editar-sucursal">Editar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-sucursal">Guardar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>