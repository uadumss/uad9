<div class="overflow-auto" style="height: 600px">
    <br/>
    <span style="font-size: 0.9em" class="font-italic">
        <span class="font-weight-bold">Nº Importación :</span> <span><?php echo e($importacion->cod_imp); ?></span> |
        <span class="font-weight-bold">Fecha :</span> <span><?php echo e($importacion->imp_fecha); ?></span> |
        <span class="font-weight-bold">Tipo :</span> <span><?php echo e($importacion->imp_tipo); ?></span> |
        <span class="font-weight-bold">Revocado :</span> <span><?php if($importacion->imp_deshecho=='t'): ?> Si <?php else: ?> No <?php endif; ?> </span> |
        <span class="font-weight-bold">Identificador :</span> <span><?php echo e($importacion->imp_identificador); ?></span> |
        <span class="font-weight-bold">Usuario :</span> <span><?php echo e($importacion->imp_usuario); ?></span>

    </span>

    <hr class="sidebar-divider"/>
    <table class="table table-hover table-sm">
        <tr class="bg-light">
            <th>Nro.</th>
            <th>Nombre</th>
            <th>CI</th>
            <th>Título</th>
            <th>Fecha</th>
            <th>Tomo</th>
            <th>Gestión</th>
            <th>tipo</th>
            <th>Importado</th>
        </tr>
        <?php $i=1;?>
        <tbody>
        <?php $__currentLoopData = $titulosExcel[0]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

            <tr style="font-size: 0.9em">
                <td><?php echo e($i); ?></td>
                <td><?php echo e($t['apellido'].', '.$t['nombre']); ?></td>
                <td><?php echo e($t['ci']); ?></td>
                <td><?php echo e($t['numero']); ?></td>
                <td><?php echo e($t['fecha']); ?></td>
                <td><?php echo e($t['tomo']); ?></td>
                <td><?php echo e($t['ano']); ?></td>
                <td><?php echo e($t['tipo']); ?></td>
                <?php if($t['importado']==0): ?>
                    <td class="text-danger font-weight-bold font-italic">No importado</td>
                <?php else: ?>
                    <td class="text-success font-italic font-weight-bold">Si</td>
                <?php endif; ?>
            </tr>
            <?php $i++;?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

    </table>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/importacion/ver_datos_importacion.blade.php ENDPATH**/ ?>