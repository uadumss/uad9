<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-danger">
        <form action="<?php echo e(url('e_resolucion')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar resolución</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($enlace[0]->enlace>0): ?>
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                        No se puede eliminar la Resolución debido a que hay copias en otros tomos
                    </div>
                <?php else: ?>
                    <span class="font-italic">Esta seguro de eliminar la resolución :</span> <br/><br/>
                    <div class="row">
                        <div class="alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                            <div>
                                <table>
                                    <tr>
                                        <th class="text-right">Nro. Resolución:</th>
                                        <td class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($resolucion['res_numero']."/".date('y',strtotime($resolucion['res_fecha']))); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Fecha:</th>
                                        <td class="text-dark text-left border-bottom border-danger pl-3"><?php echo e(date('d/m/Y',strtotime($resolucion['res_fecha']))); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Descripción:</th>
                                        <td class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($resolucion['res_desc']); ?></td>
                                    </tr>
                                </table>
                                <input type="hidden" name="cr" value="<?php echo e($resolucion['cod_res']); ?>">
                                <input type="hidden" name="ct" value="<?php echo e($resolucion['cod_tom']); ?>">
                            </div>
                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>
                    </div>
                <?php endif; ?>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-danger" type="submit" value="Aceptar" />
            </div>
        </form>
    </div>
</div>


<?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/resolucion/f_eli_resolucion.blade.php ENDPATH**/ ?>