<div class="modal fade bs-licencia-crear-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-licencia">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Crear licencia</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="licenciaForm" class="form-horizontal form-label-left">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Datos de la licencia</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="ciclo_facturacion">Ciclo de facturación(Dia del mes)</label>
                                    <input type="hidden" id="empresa_id" name="empresa_id" value="<?php echo $_GET['id']; ?>">
                                    <input type="number" class="form-control" id="ciclo_facturacion" name="ciclo_facturacion" min="1" max="31" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-licencia">Guardar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-licencia-eliminar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-licencia-eliminar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                
                <h4 class="modal-title" id="myModalLabel">Eliminar licencia</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="licenciaForm" class="form-horizontal form-label-left">
                <input type="hidden" id="licencia_id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Eliminar licencia</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <p>¿Está seguro de eliminar la licencia?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger" id="btn-eliminar-licencia">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bs-sesiones-activas-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-sesiones-activas">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Sesiones activas</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-striped jambo_table bulk_action" id="tablaSesionesActivas">
                    <thead>
                        <tr class="headings">
                            <th class="column-title"></th>
                            <th class="column-title"></th>
                            <th class="column-title"></th>
                            <th class="column-title"></th>
                            <th class="column-title"></th>
                            <th class="column-title"></th>
                            <th class="column-title"></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade bs-session-eliminar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-sesion-eliminar">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                
                <h4 class="modal-title" id="myModalLabel">Cerrar sesion</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="licenciaForm" class="form-horizontal form-label-left">
                <input type="hidden" id="sesion_id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Cerrar sesion</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <p>¿Está seguro de cerrar la sesion?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-danger" id="btn-eliminar-sesion">Cerrar Sesion</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

                