<div class="modal fade bs-cliente-editar-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="modal-cliente">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Editar Cliente</h4>
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="clienteForm" class="form-horizontal form-label-left">
                    <input type="hidden" id="id" name="id">
                    <div class="panel-group">

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">Clientes</h4>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <input class="form-control" type="text" id="nombre" name="nombre" required="">
                                </div>


                                <div class="form-group">
                                    <label for="numero_documento">Número de Documento:</label>
                                    <input class="form-control" type="text" id="numero_documento" name="numero_documento">
                                </div>

                                <div class="form-group">
                                    <label for="cuit">CUIT:</label>
                                    <input class="form-control" type="text" id="cuit" name="cuit">
                                </div>

   

                                <div class="form-group">
                                    <label for="direccion_comercial">Dirección Comercial:</label>
                                    <input class="form-control" type="text" id="direccion_comercial" name="direccion_comercial">
                                </div>

                                <div class="form-group">
                                    <label for="direccion_entrega">Dirección de Entrega:</label>
                                    <input class="form-control" type="text" id="direccion_entrega" name="direccion_entrega">
                                </div>



                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <input class="form-control" type="text" id="telefono" name="telefono">
                                </div>

                                <div class="form-group">
                                    <label for="celular">Celular:</label>
                                    <input class="form-control" type="text" id="celular" name="celular">
                                </div>

                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input class="form-control" type="text" id="email" name="email">
                                </div>

                                <div class="form-group">
                                    <label for="contacto">Contacto:</label>
                                    <input class="form-control" type="text" id="contacto" name="contacto">
                                </div>

                                <div class="form-group">
                                    <label for="telefono_contacto">Teléfono de Contacto:</label>
                                    <input class="form-control" type="text" id="telefono_contacto" name="telefono_contacto">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="btn-editar-cliente">Editar</button>
             
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>