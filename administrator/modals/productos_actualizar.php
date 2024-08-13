<div class="modal fade bs-empresa-editar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-actualizar-precio">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Agregar empresa</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="actualizarPrecioForm" class="form-horizontal form-label-left">

                    <input type="hidden" id="empresa_id" name="empresa_id">
                    <input type="hidden" id="familia_id" name="familia_id">
                    <input type="hidden" id="porcentaje1" name="porcentaje1">
                    <input type="hidden" id="porcentaje2" name="porcentaje2">
                    <input type="hidden" id="porcentaje3" name="porcentaje3">
                    <div class="panel-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Actualizacion de precios</h4>
                            </div>
                            <div class="panel-body">
                                <p>
                                    Esta a punto de actualizar los precios de los productos de la familia seleccionada.<br>
                                    Recuerde que no hay vuelta atras.<br><br>
                                    Le recomensamos realizar una vista previa de los precios antes de actualizarlos.<br>
                                    Si ya esta seguro de actualizar los precios, haga click en el boton "Actualizar".
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                <button type="button" class="btn btn-primary" id="btn-actualizar-precios">Actualizar</button>
                            </div>
                </form>


            </div>
        </div>
    </div>
</div>
</div>

