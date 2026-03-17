<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar trámite</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form action="<?php echo e(url('eli_docleg')); ?>" method="post" id="form_eli_docleg">
                <?php echo csrf_field(); ?>
                <span class="font-italic">Esta seguro de eliminar el trámite de legalización : </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>
                            <?php if($docleg->dtra_falso=='t' || $docleg->dtra_obs!=''): ?>
                                <div>
                                    <p> No puede eliminar este trámite</p>
                                </div>
                            <?php else: ?>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right ">Nombre trámite:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($docleg->tre_nombre); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Nro. título:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($docleg->dtra_numero." / ".$docleg->dtra_gestion); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Interesado:</th>
                                        <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($persona->per_apellido.' '.$persona->per_nombre); ?></th>
                                    </tr>
                                </table>
                                <input type="hidden" name="cdtra" value="<?php echo e($docleg->cod_dtra); ?>">
                                <input type="hidden" name="ctra" value="<?php echo e($docleg->cod_tra); ?>">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <?php if($docleg->dtra_falso!='t'): ?>
                <button class="btn btn-danger" data-dismiss="modal" onclick="guardarDatos('<?php echo e(url("eli_docleg")); ?>','panel_traleg','form_eli_docleg')"> Eliminar</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/servicios/tra_legalizacion/f_eli_docleg.blade.php ENDPATH**/ ?>