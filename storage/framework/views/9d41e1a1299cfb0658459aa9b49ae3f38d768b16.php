<div>
    <?php if($cod_res==0): ?>

            <input type="hidden" name="temas" value="<?php echo e($detalle->cod_det); ?>">
            <span><?php echo e($plan->plan_numero."/".$codigo->carch_numero." - ".$detalle->det_nombre); ?></span>

    <?php else: ?>

        <span class="font-weight-bold">
           <?php $codificacion="";?>
            <?php $__currentLoopData = $archivado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="btn btn-light btn-circle btn-sm text-danger"onclick="cargarPlan('eliminar plan resolucion/<?php echo e($a->cod_arc); ?>','archivados')"><i class="fas fa-trash-alt"></i></a>
                <?php echo e($a->plan_numero.'/'.$a->carch_numero.'-'.$a->det_nombre); ?><br/>
                    <?php //$codificacion.=$a->plan_numero.'/'.$a->carch_numero."<br/>"?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </span>
    <?php endif; ?>
</div>
<?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/resolucion/panel_tema.blade.php ENDPATH**/ ?>