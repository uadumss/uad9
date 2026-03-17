<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-folder-open"></i>&nbsp;&nbsp;Guardar folder</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <form action="<?php echo e(url('g_folder')); ?>" method="post" id="form_folder">
                <?php echo csrf_field(); ?>
                <span class="font-italic text-dark">Está seguro de que el funcionario <span class="font-weight-bold">presentó folder</span>: </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-primary shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>

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
                        </div>
                    </div>
                    <div class="pt-2 col-md-2 text-primary font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            </form>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary btn-sm" type="button" data-dismiss="modal" onclick="$('#form_folder').submit()">Guardar</button>
        </div>

    </div>
</div>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/funcionario/fe_folder.blade.php ENDPATH**/ ?>