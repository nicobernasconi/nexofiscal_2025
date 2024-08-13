<div id="menu-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú</h2>
        <div class="row">
            <ul>
                <li><a href="#" id="btn-menu-productos"><i class="fas fa-box-open"> Productos</i></a></li>
                <li><a href="#" id="btn-menu-clientes"><i class="fas fa-users"> Clientes</i></a></li>
                <li><a href="#" id="btn-menu-ventas"><i class="fas fa-cash-register"> Ventas</i></a></li>
                <li><a href="#" id="btn-menu-compras"><i class="fas fa-shopping-cart"> Compras</i></a></li>
                <li><a href="#" id="btn-menu-cierre_caja"><i class="fas fa-cash-register"> Caja</i></a></li>
                <li><a href="#" id="btn-menu-proveedores"><i class="fas fa-truck"> Proveedores</i></a></li>
                <li><a href="#" id="btn-menu-promociones"><i class="fas fa-gift"> Promociones</i></a></li>
                <li><a href="#" id="btn-menu-informes"><i class="fas fa-file-invoice-dollar"> Informes</i></a></li>
                <?php if (false) { ?>
                    <li> <a href="#" id="btn-menu-usuarios"><i class="fas fa-user"> Usuarios</i></a></i>
                    <?php } ?>
                    <li><a href="#" id="btn-menu-configuracion"><i class="fas fa-cogs"> Configuración</i></a></li>
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"> Salir</i></a></li>
            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>


<div id="menu-productos-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Productos</h2>
        <div class="row">
            <ul>
                <?php if (in_array('crear', $permisos_asignados['productos'])) { ?>
                    <li><a href="#" id="btn-menu-productos-crear"><i class="fas fa-box-open"> Crear Producto</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['productos'])) { ?>
                    <li><a href="#" id="btn-menu-productos-listar"><i class="fas fa-list"> Listar de precios</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['productos'])) { ?>
                    <li><a href="#" id="btn-menu-productos-buscar"><i class="fas fa-list"> Seleccionar Productos</i></a></li>
                <?php } ?>

                <?php if (in_array('modificar', $permisos_asignados['productos'])) { ?>
                    <li><a href="#" id="btn-menu-productos-editar"><i class="fas fa-list"> Editar Productos</i></a></li>
                <?php } ?>


                <?php if (in_array('eliminar', $permisos_asignados['productos'])) { ?>
                    <li><a href="#" id="btn-menu-productos-eliminar"><i class="fas fa-list"> Eliminar Productos</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['productos'])) { ?>
                    <li><a href="#" id="btn-menu-modificar-stocks"><i class="fas fa-list"> Ajuste Stock</i></a></li>
                <?php } ?>
            </ul>
            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-clientes-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Clientes</h2>
        <div class="row">
            <ul>
                <?php if (in_array('crear', $permisos_asignados['clientes'])) { ?>
                    <li><a href="#" id="btn-menu-clientes-crear"><i class="fas fa-user-plus"> Crear Cliente</i></a></li>
                <?php } ?>
                <?php if (in_array('modificar', $permisos_asignados['clientes'])) { ?>
                    <li><a href="#" id="btn-menu-clientes-editar"><i class="fas fa-user-edit"> Editar Cliente</i></a></li>
                <?php } ?>
                <?php if (in_array('eliminar', $permisos_asignados['clientes'])) { ?>
                    <li><a href="#" id="btn-menu-clientes-eliminar"><i class="fas fa-user-times"> Eliminar Cliente</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['clientes'])) { ?>
                    <li><a href="#" id="btn-menu-clientes-listar"><i class="fas fa-list"> Listar Clientes</i></a></li>
                <?php } ?>

            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-ventas-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Ventas</h2>
        <div class="row">
            <ul>
                <?php if (in_array('listar', $permisos_asignados['comprobantes'])) { ?>
                    <li><a href="#" id="btn-menu-informes-ventas"><i class="fas fa-chart-line"> Ventas</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['comprobantes'])) { ?>
                    <li><a href="#" id="btn-menu-ventas-reimprimir"><i class="fas fa-print"> Reimprimir Comprobante</i></a></li>
                <?php } ?>
                <?php if (in_array('eliminar', $permisos_asignados['comprobantes'])) { ?>
                    <li><a href="#" id="btn-menu-ventas-cancelar"><i class="fas fa-ban"> Anular Pedidos</i></a></li>
                <?php } ?>
                <?php if (in_array('crear', $permisos_asignados['comprobantes'])) { ?>
                    <li><a href="#" id="btn-menu-ventas-cobrar"><i class="fas fa-money-bill-wave"> Cobrar</i></a></li>
                <?php } ?>

                <?php if (in_array('crear', $permisos_asignados['comprobantes'])) { ?>
                    <li><a href="#" id="btn-menu-informes-libro-iva-ventas"><i class="fas fa-chart-line"> Libro IVA Ventas</i></a></li>
                <?php } ?>

                

            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-compras-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Compras</h2>
        <div class="row">
            <ul>
                <?php if (in_array('crear', $permisos_asignados['compras'])) { ?>
                    <li><a href="#" id="btn-menu-compras-crear"><i class="fas fa-cart-plus"> Registrar Compra</i></a></li>
                <?php } ?>
                
            </ul>
            <ul>
                <?php if (in_array('crear', $permisos_asignados['compras'])) { ?>
                    <li><a href="#" id="btn-menu-pagos-crear"><i class="fas fa-dollar-sign"> Registrar Pago</i></a></li>
                <?php } ?>
                
            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-cierre_caja-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Caja</h2>
        <div class="row">
            <ul>
                <?php if (in_array('listar', $permisos_asignados['cierre_cajas'])) { ?>
                    <li><a href="#" id="btn-menu-cierre-caja-listar"><i class="fas fa-list"> Listar Cierre de Caja</i></a></li>
                <?php } ?>
                <?php if (in_array('crear', $permisos_asignados['cierre_cajas'])) { ?>
                    <li><a href="#" id="btn-menu-cierre-caja-cerrar"><i class="fas fa-cash-register"> Cerrar Caja</i></a></li>
                <?php } ?>
            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-proveedores-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Proveedores</h2>
        <div class="row">
            <ul>
                <?php if (in_array('listar', $permisos_asignados['proveedores'])) { ?>
                    <li><a href="#" id="btn-menu-proveedores-listar"><i class="fas fa-list">Proveedores</i></a></li>
                <?php } ?>
            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-promociones-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Promociones</h2>
        <div class="row">
            <ul>

                
            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-informes-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Informes</h2>
        <div class="row">
            <ul>
                
                <li><a href="#" id="btn-menu-informes-compras"><i class="fas fa-chart-line"> Compras</i></a></li>
                
                <li><a href="#" id="btn-menu-informes-clientes"><i class="fas fa-chart-line"> Clientes</i></a></li>
                <li><a href="#" id="btn-menu-informes-proveedores"><i class="fas fa-chart-line"> Proveedores</i></a></li>

            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>

<div id="menu-configuracion-modal" class="custom-modal">
    <div class="custom-modal-content-s">
        <span class="custom-modal-close">&times;</span>
        <h2>Menú Configuración</h2>
        <div class="row">
            <ul>
                <?php if (in_array('listar', $permisos_asignados['tipo_documento'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-tipos-documentos"><i class="fas fa-cogs"> Tipos de Documentos</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['tipo_iva'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-tipos-iva"><i class="fas fa-cogs"> Tipos de IVA</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['unidad'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-unidades"><i class="fas fa-cogs"> Unidades</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['familias'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-familias"><i class="fas fa-cogs"> Familias</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['agrupaciones'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-agrupaciones"><i class="fas fa-cogs"> Agrupaciones</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['tipo'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-tipos-productos"><i class="fas fa-cogs"> Tipos de Productos</i></a></li>
                <?php } ?>
                <?php if (in_array('listar', $permisos_asignados['formas_pago'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-forma-pagos"><i class="fas fa-cogs"> Formas de Pago</i></a></li>
                <?php } ?>
                <?php if (in_array('modificar', $permisos_asignados['usuarios'])) { ?>
                    <li><a href="#" id="btn-menu-configuracion-asignar-punto-venta"><i class="fas fa-cogs"> Asignar Punto de Venta</i></a></li>
                <?php } ?>

            </ul>
        </div>
        <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
    </div>
</div>
<?php if (false) { ?>
    <div id="menu-usuarios-modal" class="custom-modal">
        <div class="custom-modal-content-s">
            <span class="custom-modal-close">&times;</span>
            <h2>Menú Usuarios</h2>
            <div class="row">
                <ul>

                    <?php if (in_array('listar', $permisos_asignados['usuarios'])) { ?>
                        <li><a href="#" id="btn-menu-usuarios-listar"><i class="fas fa-list"> Usuarios</i></a></li>
                    <?php } ?>
                    <?php if (in_array('listar', $permisos_asignados['roles'])) { ?>
                        <li><a href="#" id="btn-menu-usuarios-roles"><i class="fas fa-list"> Roles</i></a></li>
                    <?php } ?>



                </ul>
            </div>
            <button id="custom-modal-close-btn" class="custom-modal-close-btn"><i class="fa-solid fa-times"></i>&nbsp;Cerrar</button>
        </div>
    </div>
<?php } ?>