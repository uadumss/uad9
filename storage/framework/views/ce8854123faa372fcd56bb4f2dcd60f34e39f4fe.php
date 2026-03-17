<div>
    <table>
        <tr>
            <th>Nro.</th>
            <th>CI.</th>
            <th>NOMBRE</th>
            <th>CARGO ACTUAL</th>
            <th>CARGO ANTERIOR</th>
        </tr>
        <?php $i=1;?>
        <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i); ?></td>
                <td><?php echo e($l['ci']); ?></td>
                <td><?php echo e($l['nombre']); ?></td>
                <td><?php echo e($l['cargo1']); ?></td>
                <td><?php echo e($l['cargo2']); ?></td>
            </tr>
            <?php $i++;?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/prueba/resultado.blade.php ENDPATH**/ ?>