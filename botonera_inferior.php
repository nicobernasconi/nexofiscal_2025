 <section class="botoneradown">
     <ul>
         <?php if (in_array('crear', $permisos_asignados['comprobantes'])) { ?>
             <li>
                 <a class="botoneradownbtn" id="btnfacturar" href="#"><i class="fas fa-cash-register"></i> COBRAR (F10)</a>
             </li>
             <li>
                 <a class="botoneradownbtn" id="btnpedido" href="#"><i class="fas fa-cash-register"></i> PEDIDOS (CTRL+F10)</a>
             </li>
         <?php } else { ?>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-cash-register"></i> COBRAR (F10)</a>
             </li>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-cash-register"></i> PEDIDOS (CTRL+F10)</a>
             </li>
         <?php } ?>
         <li>
             <a class="botoneradownbtn" id="btnlimpiar" href="#"><i class="fas fa-trash"></i> LIMPIAR (F4)</a>
         </li>

         <?php if (in_array('listar', $permisos_asignados['promociones'])) { ?>
             <li>
                 <a class="botoneradownbtn" id="btnpromociones" href="#"><i class="fas fa-tag"></i> DESCUENTOS (F6)</a>
             </li>
         <?php } else { ?>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-tag"></i> DESCUENTOS (F6)</a>
             </li>
         <?php } ?>
         <?php if (in_array('listar', $permisos_asignados['productos'])) { ?>
             <li>
                 <a class="botoneradownbtn" id="btnlistaprecio" href="#"><i class="fas fa-file-alt"></i> LISTA PRECIOS (F7)</a>
             </li>
         <?php } else { ?>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-file-alt"></i> LISTA PRECIOS (F7)</a>
             </li>
         <?php } ?>

         <?php if (in_array('listar', $permisos_asignados['vendedores'])) { ?>
             <li>
                 <a class="botoneradownbtn" id="btnlistavendedores" href="#"><i class="fas fa-user-tie"></i> VENDEDOR (F8)</a>
             </li>
         <?php } else { ?>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-user-tie"></i> VENDEDOR (F8)</a>
             </li>
         <?php } ?>
        
         <?php if (in_array('crear', $permisos_asignados['gastos'])) { ?>
             <li>
                 <a class="botoneradownbtn" id="btngastos" href="#"><i class="fas fa-money-bill-wave"></i> GASTOS (F2)</a>
             </li>
         <?php } else { ?>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-money-bill-wave"></i> GASTOS (F2)</a>
             </li>
         <?php } ?>

         <?php if (in_array('crear', $permisos_asignados['cierre_cajas'])) { ?>
             <li>
                 <a class="botoneradownbtn" id="btncierredecaja" href="#"><i class="fas fa-book"></i> CERRAR CAJA (F9)</a>
             </li>
         <?php } else { ?>
             <li>
                 <a class="botoneradownbtn" href="#" style="pointer-events: none; cursor: default;background-color: #c2c2c2;"><i class="fas fa-book"></i> CERRAR CAJA (F9)</a>
             </li>
         <?php } ?>
     </ul>
 </section>