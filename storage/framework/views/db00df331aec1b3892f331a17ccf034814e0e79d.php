<div class="modal-dialog modal-lg" role="document" id="">
    <div class="modal-content border-bottom-info shadow-lg">
        <div class="modal-header bg-info">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-arrow-circle-left"></i>&nbsp;&nbsp;Corregir trámite</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <?php if($tramite_noatentado): ?>
                <div class="text-dark">
                    <span class="font-italic">Está seguro de rehacer el trámite</span> <i class="fas fa-question-circle text-info" style="font-size: 35px"></i>
                </div><br>

                <div class="shadow alert-info rounded col-md-11 p-3">
                    <div class="row">
                        <div>
                            <div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nro. Trámite : </th>
                                        <td class="border-bottom border-dark">&nbsp;&nbsp;<?php echo e($tramite_noatentado->dtra_numero_tramite." / ".$tramite_noatentado->dtra_gestion_tramite); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre trámite : </th>
                                        <td class="border-bottom border-dark">&nbsp;&nbsp;<?php echo e($tramite->tre_nombre); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombres : </th>
                                        <td class="border-bottom border-dark">
                                            <ul>
                                                <?php $__currentLoopData = $noatentado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li><?php echo e($n->per_nombre." ".$n->per_apellido); ?></li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </ul>

                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <br/>
                <span class="text-white font-italic bg-danger font-weight-bold border border-warning rounded" style="font-size: 0.7em; padding: 2px; margin:7px">* Esta acción quedará registrado en el sistema</span>
            <?php else: ?>
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    No se puede eliminar al candidato
                </div>
            <?php endif; ?>

        </div>



        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <?php if($tramite_noatentado): ?>
                <form id="form_corregir_tramite_noa" method="POST" action="<?php echo e(url('corregir tramite noatentado')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="cd" value="<?php echo e($tramite_noatentado->cod_dtra); ?>"/>
                    <input type="submit" value="corregir" class="btn btn-sm btn-info">
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/tramite/f_corregir_tramite_noa.blade.php ENDPATH**/ ?>