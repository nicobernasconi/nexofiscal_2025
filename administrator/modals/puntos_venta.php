<div class="modal fade bs-punto-venta-crear-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-punto-venta">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Crear Punto de venta</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="puntoVentaForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="empresa_id" name="empresa_id" value="<?php echo $_GET['id']; ?>">
                    <input type="hidden" id="id" name="id">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Punto de venta</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="nombre_usuario">Descripcion</label>
                                    <input type="text" class="form-control" id="descripcion" name="descripcion" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="nombre_usuario">Numero</label>
                                    <input type="number" class="form-control" id="numero" name="numero" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-editar-punto-venta">Editar</button>
                        <button type="button" class="btn btn-primary" id="btn-guardar-punto-venta">Guardar</button>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>