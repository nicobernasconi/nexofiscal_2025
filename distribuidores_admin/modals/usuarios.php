<div class="modal fade bs-usuario-crear-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-usuario">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Crear usuario</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="usuarioForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="id" name="id">
                    <input type="hidden" id="empresa_id" name="empresa_id">
                    <input type="hidden" id="sucursal_id" name="sucursal_id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos de la usuario</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="nombre_usuario">Nombre de usuario</label>
                                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                                <div class="form-group">
                                    <label for="nombre_completo">Nombre completo</label>
                                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
                                </div>
                                <div class="form-group">
                                    <label for="rol_id">Rol ID</label>
                                    <select class="form-control" id="rol_id" name="rol_id" required>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="lista_precios">Lista de precio</label>
                                    <select class="form-control" id="lista_precios" name="lista_precios" required>
                                        <option value="1">Lista 1</option>
                                        <option value="2">Lista 2</option>
                                        <option value="3">Lista 3</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="venta_rapida">Venta rapida</label>
                                    <select class="form-control" id="venta_rapida" name="venta_rapida" required>
                                        <option value="0">NO</option>
                                        <option value="1">SI</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="imprimir">Imprimir</label>
                                    <select class="form-control" id="imprimir" name="imprimir" required>
                                    <option value="1">SI</option>    
                                    <option value="0">NO</option>
                                       
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tipo_comprobante_imprimir">Tipo de comprobante</label>
                                    <select class="form-control" id="tipo_comprobante_imprimir" name="tipo_comprobante_imprimir" required>
                                    <option value="1">Ticket</option>    
                                    <option value="2">A4</option>
                                       
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-editar-usuario">Editar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-usuario">Guardar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>