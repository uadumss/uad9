<div>
    <?php if($tipo=='hcu'): ?>
        <span class="font-weight-bold font-italic text-dark">UNIVERSIDAD MAYOR DE SAN SIMON </span><br/>
        <?php if($unidad): ?>
            <span class="font-weight-bold font-italic text-dark">Facultad :</span>
            <span class="text-primary font-weight-bolder"><?php echo e($unidad->fac_nombre); ?></span>
        <?php endif; ?>
    <?php else: ?>
        <?php if($tipo=='hcf'): ?>
                <span class="font-weight-bold font-italic text-dark">Carrera :</span>
                <span class="text-primary font-weight-bolder"><?php echo e($unidad->car_nombre); ?></span>
        <?php endif; ?>

    <?php endif; ?>
    <br/><br/>
</div>
<div class="overflow-auto col-md-12 p-2 border rounded" style="height: 600px">
    <table class="table table-sm">
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>CI</th>
            <th>Participac&oacuten</th>
            <th>Periodo</th>
            <th>Renuncia</th>
            <th>Estamento</th>
            <th>Opciones</th>
        </tr>
        <?php $i=0;?>

        <?php $__currentLoopData = $electos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($i+=1); ?></td>
                <td><?php echo e($c->per_apellido." ".$c->per_nombre); ?></td>
                <td><?php echo e($c->per_ci); ?></td>
                <td>
                    <?php if($c->ele_titular=='t'): ?>
                        <span class="bg-info rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Titular</span>
                    <?php else: ?>
                        <span class="bg-light rounded pr-1 pl-1 text-dark font-weight-bold font-italic" style="font-size: 14px;">Suplente</span>
                    <?php endif; ?>
                </td>
                <td class="text-dark font-weight-bold">
                    <?php echo e(date('d/m/Y',strtotime($c->ele_fecha_inicio))." - ".date('d/m/Y',strtotime($c->ele_fecha_fin))); ?>

                        <?php if(strtotime($c->ele_fecha_fin)>strtotime(date("d-m-Y H:i:00",time()))){ ?>
                                <i class='fas fa-check-circle text-success'></i>
                        <?php } ?>
                </td>
                <td>
                    <?php if($c->ele_fecha_renuncia!=''): ?>
                    <?php echo e(date('d/m/Y',strtotime($c->ele_fecha_renuncia))); ?>

                    <?php endif; ?>
                </td>
                <td>
                    <?php if($c->ele_docente=='t'): ?>
                        <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docente</span>
                    <?php else: ?>
                        <span class="bg-danger rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiantil</span>
                    <?php endif; ?>
                </td>
                <td>

                </td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcu/l_consejeros.blade.php ENDPATH**/ ?>