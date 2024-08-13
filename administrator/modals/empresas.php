<div class="modal fade bs-empresa-editar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-empresa">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Agregar empresa</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="empresaForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="id" name="id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos de la empresa</h4>
                            </div>
                            <div class="panel-body">
                                
                                <div class="form-group">
                                    <label for="nombre_empresa">Nombre Empresa</label>
                                    <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" required>
                                    <input type="hidden" id="distribuidor_id" name="distribuidor_id" value="<?php echo $_SESSION['distribuidor_id']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="nombre_empresa">Razon social</label>
                                    <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                                </div>
                                <div class="form-group">
                                    <label for="email_empresa">Email Empresa</label>
                                    <input type="email" class="form-control" id="email_empresa" name="email_empresa" required>
                                </div>
                                <div class="form-group" id="panel-password">
                                    <label for="password">Contraseña</label>
                                    <input type="password" class="form-control" id="password" name="password" required>
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
                                    <textarea class="form-control" id="descripcion_empresa" name="descripcion_empresa" ></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="responsable_empresa">Responsable Empresa</label>
                                    <input type="text" class="form-control" id="responsable_empresa" name="responsable_empresa" >
                                </div>
                                <div class="form-group">
                                    <label for="fecha_inicio_actividades">Fecha Inicio Actividades</label>
                                    <input type="date" class="form-control" id="fecha_inicio_actividades" name="fecha_inicio_actividades" >
                                </div>
                                <div class="form-group">
                                    <label for="cuit">CUIT</label>
                                    <input type="number" class="form-control" id="cuit" name="cuit" >
                                </div>
                                <div class="form-group">
                                    <label for="cuit">IIBB</label>
                                    <input type="number" class="form-control" id="iibb" name="iibb" >
                                </div>
                                <div class="form-group">
                                    <label for="tipo_iva">Tipo IVA</label>
                                    <select class="form-control" style="width: 100%!important;" id="tipo_iva_id" name="tipo_iva_id" >
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default" id="panel-agragar-sucursal">
                            <div class="panel-heading">
                                <h4 class="panel-title">Sucursal Principal</h4>
                            </div>
                            <div class="panel-body">
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-editar-empresa">Editar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-empresa">Guardar</button>
                    </div>
                </form>


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