<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar funcionario</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="<?php echo e(url('eli_funcionario')); ?>" method="post" id="form_eli_fun">
                <?php echo csrf_field(); ?>
                <span class="font-italic">Esta seguro de eliminar al funcionario : </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>
                            <?php if($eliminar==1): ?>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre :</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e($funcionario->fun_nombre); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">CI:</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e($funcionario->fun_ci); ?>

                                        </td>
                                    </tr>
                                <input type="hidden" name="cf" value="<?php echo e($funcionario->cod_fun); ?>"/>
                                </table>
                            <?php else: ?>
                                <span>
                                    No se puede eliminar al funcionario debido a que tiene documentos asociados
                                </span>
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
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <?php if($eliminar==1): ?>
                <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal" onclick="$('#form_eli_fun').submit()">Eliminar</button>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/funcionario/f_eli_funcionario.blade.php ENDPATH**/ ?>