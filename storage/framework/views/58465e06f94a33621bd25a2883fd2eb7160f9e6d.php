<select name="unidad" class="custom-control custom-select">
    <option></option>
    <?php $__currentLoopData = $unidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <option value="<?php echo e($u->cod); ?>"><?php echo e($u->nombre); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
<input type="hidden" name="tipo" value="<?php echo e($nombreUnidad); ?>">
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/fe_tabla_unidad.blade.php ENDPATH**/ ?>