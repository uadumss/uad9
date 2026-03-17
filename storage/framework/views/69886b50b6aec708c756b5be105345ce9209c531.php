<div>
        <?php if(sizeof($titulos)>0): ?>
            <div>
                <?php if($respuesta==1): ?>
                    <i class="fas fa-check-circle text-success"></i>
                <?php else: ?>
                    <i class="fas fa-minus-circle text-danger"></i>
                <?php endif; ?>
                <span class="text-primary font-weight-bold">Títulos</span>

                <table>
                    <?php $__currentLoopData = $titulos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($t->tit_tipo." - ".$t->tit_nro_titulo."/".$t->tit_gestion); ?></td>
                            <td><?php echo e(date('d/m/Y H:i:s',strtotime($t->created_at))); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        <?php endif; ?>
        <?php if(sizeof($apostilla)>0): ?>
            <div>
                <span class="text-primary font-weight-bold">Trámites de apostilla</span>
                <table>
                    <?php $__currentLoopData = $apostilla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($t->apos_numero."/".$t->apos_gestion); ?></td>
                            <td><?php echo e(date('d/m/Y H:i:s',strtotime($t->created_at))); ?></td>

                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        <?php endif; ?>
        <?php if(sizeof($tramitas)>0): ?>
            <div>
                <span class="text-primary font-weight-bold">Trámites de legalización</span>
                <table>
                    <?php $__currentLoopData = $tramitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($t->tra_tipo_tramite.'-'.$t->tra_numero."/".$t->tra_gestion); ?></td>
                            <td><?php echo e(date('d/m/Y H:i:s',strtotime($t->created_at))); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
        <?php endif; ?>
        <?php if(sizeof($sancionados)>0): ?>
                <div>
                    <span class="text-primary font-weight-bold">Sancionados</span>
                    <table>
                        <?php $__currentLoopData = $sancionados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($t->san_sentencia); ?></td>
                                <td><?php echo e(date('d/m/Y H:i:s',strtotime($t->created_at))); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
        <?php endif; ?>
        <?php if(sizeof($noatentado)>0): ?>
                <div>
                    <span class="text-primary font-weight-bold">Trámites de noatentado</span>
                    <table>
                        <?php $__currentLoopData = $noatentado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($t->noa_cargo); ?></td>
                                <td><?php echo e(date('d/m/Y H:i:s',strtotime($t->created_at))); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
        <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/persona/duplicado/detalle_enlace.blade.php ENDPATH**/ ?>