<br/>
<div class="alert-success centrar_bloque p-1 col-md-3 rounded shadow">
    <h5 class="text-dark text-center">Resultado de la consulta</h5>
</div>
<span class="text-dark">
    <span>Cantidad encontrada : </span><span class="font-weight-bold text-primary"><?php echo e(sizeof($resultado)); ?></span> | 

    <?php
        if($tipo_funcionario!=''){
            if($tipo_funcionario=='A'){
                $tipo_funcionario='ADMINISTRATIVO';
            }else{
                if($tipo_funcionario=='D'){
                    $tipo_funcionario='DOCENTE';
            }else{
                    $tipo_funcionario='DOCENTE Y ADMINISTRATIVO';
                }
            }
    }?>
    <?php if($tipo_funcionario!=''): ?>
        <span> Tipo de funcionario : </span><span class="font-weight-bold text-primary"><?php echo e($tipo_funcionario); ?></span>
    <?php endif; ?>
</span>

<hr class="sidebar-divider">
<table class="table table-sm table-hover"  width="100%" cellspacing="0" style="font-size: 0.8em">
    <thead>
    <tr class="bg-gray-600 text-white">
        <th>Nº</th>
        <th class="">Nombre</th>
        <th class="">CI</th>
        <th>Facultad</th>
        <th>Carrera</th>
    </tr>
    </thead>
    <tbody>
    <?php $j=1;?>
    <?php $__currentLoopData = $resultado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="">
                    <td>
                        <?php echo e($j); ?>

                    </td>
                    <td><?php echo e($f->fun_nombre); ?> </td>
                    <td><?php echo e($f->fun_ci); ?></td>
                    <td><?php echo e($f->fun_facultad); ?></td>
                    <td><?php echo e($f->fun_carrera); ?></td>
                </tr>
                <?php $j++;?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<?php /**PATH C:\uad9\resources\views/funcionario/reporte/resultado_titulos.blade.php ENDPATH**/ ?>