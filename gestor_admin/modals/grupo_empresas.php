<div class="modal fade bs-crear-distribuidor-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-empresa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Agregar grupo de empresa</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form id="grupoEmpresaForm" class="form-horizontal form-label-left" enctype="multipart/form-data">
                   
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Grupo de Empresas</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                                <div class="form-group">
                                    <label for="telefono">Teléfono</label>
                                    <input type="text" class="form-control" id="telefono" name="telefono" required>
                                </div>
                                <div class="form-group">
                                    <label for="responsable">Responsable</label>
                                    <input type="text" class="form-control" id="responsable" name="responsable" required>
                                </div>
                                <div class="form-group">
                                    <label for="logo">Logo</label>
                                    <input type="file" class="form-control" id="logo" name="logo">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-guardar-grupo-empresa">Guardar</button>
                </div>


            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-empresa-certificado-editar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-certificado-empresa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Modificar certificados</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="empresaCertificadoForm" class="form-horizontal form-label-left" enctype="multipart/form-data">
                    <input type="hidden" id="empresa_id" name="empresa_id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Certificado / Clave privada AFIP</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="certificado_afip">Certificado AFIP</label>
                                    <input type="file" class="form-control" id="certificado_afip" name="certificado_afip" required>
                                </div>
                                <div class="form-group">
                                    <label for="clave_privada_afip">Clave privada AFIP</label>
                                    <input type="file" class="form-control" id="clave_privada_afip" name="clave_privada_afip" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btn-guardar-certificado-empresa">Guardar</button>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-cambiar-contrasena-modal" tabindex="-1" role="dialog" aria-hidden="true" id="modal-cambiar-contrasena">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Cambiar contraseña</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="cambiarContrasenaForm" class="form-horizontal form-label-left">

                    <input type="hidden" id="distribuidor_id" name="distribuidor_id" >
                    <div class="form-group">
                        <label for="nueva_contrasena">Nueva contraseña</label>
                        <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmar_contrasena">Confirmar contraseña</label>
                        <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="btn-cambiar-contrasena">Cambiar</button>
            </div>

        </div>
    </div>
</div>