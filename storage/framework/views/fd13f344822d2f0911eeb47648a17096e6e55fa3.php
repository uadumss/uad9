<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-danger">
        <form action="<?php echo e(url('eli_tramite')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar trámite</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if($tramites[0]->cantidad==0 && sizeof($glosa)==0): ?>
                    <span class="font-italic">Esta seguro de eliminar el trámite : </span><br/><br/>
                    <div class="row">

                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli">
                            <div>
                                <table class="text-dark col-md-12" >
                                    <tr>
                                        <th class="text-right">Nombre:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($tramite->tre_nombre); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Costo:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($tramite->tre_costo); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Descripción:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($tramite->tre_desc); ?></th>
                                    </tr>
                                </table>
                                <input type="hidden" name="ct" value="<?php echo e($tramite['cod_tre']); ?>">
                            </div>

                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                    </div>
                    <br/>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                <?php else: ?>
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                        No se puede eliminar el tramite
                    </div>
                <?php endif; ?>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <?php if($tramites[0]->cantidad==0): ?>
                    <input class="btn btn-danger" type="submit" value="Aceptar"/>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>


<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tramite/f_eli_tramite.blade.php ENDPATH**/ ?>