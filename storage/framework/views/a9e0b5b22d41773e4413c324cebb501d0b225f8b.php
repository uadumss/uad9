<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-info">
        <div class="modal-header bg-info">
            <h5 class="modal-title text-white font-weight-bolder"><i class="fas fa-history"></i> Historial de asignaciones de la actividad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert alert-info">
                <strong>Actividad:</strong> <?php echo e($act->act_nombre); ?>

            </div>

            <div class="table-responsive">
                <table class="table table-sm table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th>Nº</th>
                            <th>Tarea</th>
                            <th>Responsables</th>
                            <th>Historial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $tar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($t['tar_nombre']); ?></td>
                                <td>
                                    <?php $__currentLoopData = $designados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $des): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($des->cod_tar==$t['cod_tar']): ?>
                                            <div class="small">
                                                <?php if($des->foto!=''): ?>
                                                    <img src="<?php echo e(url('img/foto/'.$des->foto)); ?>" width="24" height="24" class="imgRedonda mr-1"/>
                                                <?php else: ?>
                                                    <img src="<?php echo e(url('img/icon/sin foto'.$des->sexo.'.png')); ?>" width="24" height="24" class="imgRedonda mr-1"/>
                                                <?php endif; ?>
                                                <?php echo e($des->name); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-target="#tarea" data-toggle="modal"
                                            onclick="cargarDatos('<?php echo e(url("historial designaciones/".$t['cod_tar'])); ?>','panel_tarea')">
                                        <i class="fas fa-history"></i> Ver historial / reasignar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/actividad/tarea/f_historial_actividad.blade.php ENDPATH**/ ?>