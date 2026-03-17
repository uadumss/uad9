<div class="modal-dialog modal-lg" role="document" id="">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;ELIMINAR SANCIONADO</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <?php if($sancionado && $eliminar==1): ?>
                <div class="text-center text-dark">
                    <span class="font-italic">Está seguro de eliminar al sancionado</span> <i class="fas fa-question-circle text-danger" style="font-size: 35px"></i>
                </div><br>
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-12 p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">CI : </th>
                                        <td class="border-bottom border-dark text-left"><?php echo e($sancionado->per_ci); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre : </th>
                                        <td class="border-bottom border-dark text-left"><?php echo e($sancionado->per_nombre." ".$sancionado->per_apellido); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Referencia: </th>
                                        <td class="border-bottom border-dark text-left"><?php echo e($sancionado->san_referencia); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Sentencia: </th>
                                        <td class="border-bottom border-dark text-left"><?php echo e($sancionado->san_sentencia); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Resolución: </th>
                                        <td class="border-bottom border-dark text-left"><?php echo e($sancionado->san_resolucion); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    No se puede eliminar al candidato
                </div>
            <?php endif; ?>
        </div>

        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em; padding: 2px; margin:7px">* Esta acción quedará registrado en el sistema</div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <?php if($sancionado && $eliminar==1): ?>
                <form id="form_eli_sancionado" method="POST" action="<?php echo e(url('eliminar sancionado noatentado')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="cs" value="<?php echo e($sancionado->cod_san); ?>"/>
                    <input type="submit" name="envair" class="btn btn-sm btn-danger" value="Eliminar">
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/sancionado/f_eli_sancionado.blade.php ENDPATH**/ ?>