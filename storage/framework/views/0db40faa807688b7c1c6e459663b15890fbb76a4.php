<br/>
<div>

    <span class="text-dark">
        <a href="#" class="float-right btn btn-sm btn-primary" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("fe_consejo/".$cod."/0/".$tipo); ?>','panel_consejeros')">+ Consejeros</a>
        <span class="font-italic"> Tipo : </span>
        <span class="font-weight-bold text-primary font-italic"><?php echo e($tipoConsejo); ?></span><br/>
        <span class="font-italic"> Unidad : </span>
        <span class="font-weight-bold text-primary font-italic"><?php echo e($nombreUnidad); ?></span>
    </span>

    <hr class="sidebar-divider"/>

        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
            <h5 class="text-white text-center">Lista de consejeros facultativos</h5>
        </div>
        <hr class="sidebar-divider"/>
        <table class="col-md-10">
            <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ca): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="border-bottom">
                    <td class="align-text-top font-weight-bold text-dark"><?php echo e($ca->car_abreviacion." - ".$ca->car_nombre); ?></td>
                    <td class="align-text-top">
                        <a class="btn btn-light btn-circle btn-sm" title="Nuevo consejo" data-toggle="modal" data-target="#frente"
                           onclick="cargarDatos('<?php echo e(url("hcf_consejo/".$ca->cod_car)); ?>','panel_frente')" ><i class="fas fa-arrow-circle-right text-primary"></i></a>
                    </td>
                    <td>
                        <table>
                            <?php $i=0;?>
                            <?php $__currentLoopData = $consejeros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($c->cod_fac==$f->cod_fac): ?>
                                <tr style="font-size: 13px" class="text-dark">
                                    <td><?php echo e($i+=1); ?>. </td>
                                    <td><?php echo e(". ".$c->per_apellido." - ".$c->per_nombre); ?></td>
                                    <td class="pl-2">
                                        <?php if($c->fre_docente=='t'): ?>
                                            <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docente</span>
                                        <?php else: ?>
                                            <span class="bg-danger rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiante</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pl-2">
                                        <?php if($c->ele_titular=='t'): ?>
                                            <span class="bg-dark rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Titular</span>
                                        <?php else: ?>
                                            <span class="bg-light rounded pr-1 pl-1 text-dark font-weight-bold font-italic" style="font-size: 14px;">Suplente</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcf/panel_consejo_hcf.blade.php ENDPATH**/ ?>