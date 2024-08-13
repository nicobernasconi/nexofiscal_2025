<div class="modal fade bs-empresa-editar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-empresa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Agregar empresa</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="empresaForm">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email_empresa">Email Empresa</label>
                                <input type="email" class="form-control" id="email_empresa" name="email_empresa" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="nombre_empresa">Nombre Empresa</label>
                                <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" required>
                                <input type="hidden" id="distribuidor_id" name="distribuidor_id" value="<?php echo $_SESSION['distribuidor_id']; ?>">
                            </div>
                            <div class="form-group">
                                <label for="direccion_empresa">Dirección Empresa</label>
                                <input type="text" class="form-control" id="direccion_empresa" name="direccion_empresa" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono_empresa">Teléfono Empresa</label>
                                <input type="text" class="form-control" id="telefono_empresa" name="telefono_empresa" required>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_empresa">Descripción Empresa</label>
                                <textarea class="form-control" id="descripcion_empresa" name="descripcion_empresa" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="responsable_empresa">Responsable Empresa</label>
                                <input type="text" class="form-control" id="responsable_empresa" name="responsable_empresa" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_inicio_actividades">Fecha Inicio Actividades</label>
                                <input type="date" class="form-control" id="fecha_inicio_actividades" name="fecha_inicio_actividades" required>
                            </div>
                            <div class="form-group">
                                <label for="cuit">CUIT</label>
                                <input type="number" class="form-control" id="cuit" name="cuit" required>
                            </div>
                            <!-- Continuar con más campos en la primera columna -->
                        </div>
                        <div class="col-md-6">
                            
                             <div class="form-group">
                                <label for="nombre_sucursal">Nombre Sucursal</label>
                                <input type="text" class="form-control" id="nombre_sucursal" name="nombre_sucursal" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion_sucursal">Direccion Sucursal</label>
                                <input type="text" class="form-control" id="direccion_sucursal" name="direccion_sucursal" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono_sucursal">Teléfono Sucursal</label>
                                <input type="text" class="form-control" id="telefono_sucursal" name="telefono_sucursal" required>
                            </div>
                            <div class="form-group">
                                <label for="email_sucursal">Email Sucursal</label>
                                <input type="email" class="form-control" id="email_sucursal" name="email_sucursal" required>
                            </div>


                            
                            
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-guardar-empresa">Guardar</button>
            </div>

        </div>
    </div>
</div>
