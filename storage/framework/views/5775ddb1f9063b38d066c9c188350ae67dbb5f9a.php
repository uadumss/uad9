<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header alert-primary">
            <h5 class="modal-title font-weight-bolder text-dark" id="exampleModalLabel"><i class="fas fa-hand-point-right"></i> Entregas </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-dark" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert-primary centrar_bloque p-1 col-md-9 rounded shadow">
                <h6 class="text-dark text-center font-weight-bold"> Formulario de entrega de legalizaciones</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form method="post" id="form_g_entrega">
                    <?php echo csrf_field(); ?>
                    <table class="col-md-11 text-dark">
                        <tr>
                            <th class="text-right font-italic text-dark">Nro. Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark"><?php echo e($tramita->tra_numero); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark">
                                <?php if($varios==1): ?>
                                    <?php echo e($docleg->tre_nombre); ?>

                                <?php else: ?>
                                    <?php $__currentLoopData = $docleg; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($d->dtra_entregado==''): ?>
                                            <?php echo e($d->tre_nombre); ?><br/>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark" valign="top"><br/>Entregar A : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark"><br/>
                                <?php if($tramita->cod_apo!=''): ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="a" checked> <?php echo e($apoderado->apo_apellido." ".$apoderado->apo_nombre); ?> <span class="bg-danger rounded text-white pl-1 pr-1" style="font-size: 0.8em">Apo</span><br/>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="t"> <?php echo e($persona->per_apellido." ".$persona->per_nombre); ?><br/>
                                <?php else: ?>
                                    <input type="radio" name="tipo" value="t" checked> <?php echo e($persona->per_apellido." ".$persona->per_nombre); ?><br/>
                                <?php endif; ?>
                                <br/>
                            </td>
                        </tr>

                    </table>
                    <?php if($varios==1): ?>
                        <input type="hidden" name="cdtra" value="<?php echo e($docleg->cod_dtra); ?>">
                        <input type="hidden" name="ctra" value="<?php echo e($docleg->cod_tra); ?>">
                    <br/>
                    <?php else: ?>
                        <input type="hidden" name="ctra" value="<?php echo e($tramita->cod_tra); ?>">
                        <input type="hidden" name="todo" value="t">
                    <?php endif; ?>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema<br/>
                        * Si hace la entrega de este trámite, ya no se podra modificar su estado</div>
                </form>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <?php if($varios==1): ?>
                <?php if($docleg->dtra_falso!='t'): ?>
                    <button class="btn btn-sm btn-primary" onclick="guardarDatos('<?php echo e(url("g_entrega")); ?>','panel_traleg','form_g_entrega')" data-dismiss="modal">Guardar</button>
                <?php endif; ?>
            <?php else: ?>
                <button class="btn btn-sm btn-primary" onclick="guardarDatos('<?php echo e(url("g_entrega")); ?>','panel_traleg','form_g_entrega')" data-dismiss="modal">Guardar</button>
            <?php endif; ?>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/f_conf_entrega.blade.php ENDPATH**/ ?>