<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar titularidad</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <form action="<?php echo e(url('eli_documento')); ?>" method="post" id="form_eli_doc">
        <div class="modal-body">

                <?php echo csrf_field(); ?>
                <span class="font-italic">Esta seguro de eliminar el documento : </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre :</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e($funcionario->fun_nombre); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre de documento:</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e($documento->doc_titulo); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Tipo de documento :</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e($documento->doc_tipo); ?>

                                        </td>
                                    </tr>
                                </table>
                            <input type="hidden" name="cd" value="<?php echo e($documento->cod_doc); ?>"/>
                        </div>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>

        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <input class="btn btn-danger btn-sm"  type="submit" value="Eliminar"/>
        </div>
        </form>
    </div>
</div>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/funcionario/documento/f_eli_documento.blade.php ENDPATH**/ ?>