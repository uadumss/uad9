<div>
    <div class=" ml-5">
        <span class="font-italic text-dark">
            <table class="col-md-12">
                <tr>
                    <td><span>Frente : </span><span class="font-weight-bold"><?php echo e($frente->fre_nombre); ?></span></td>
                    <td>
                        <span> Estamento : </span>
                        <?php if($frente->fre_docente=='t'): ?>
                            <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docente</span>
                        <?php else: ?>
                            <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiantil</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Fechas : </span><span class="font-weight-bold">
                            <?php if($frente->fre_fecha_inicio!=''): ?>
                                <?php echo e(date('d/m/Y',strtotime($frente->fre_fecha_inicio))); ?>

                            <?php endif; ?>
                            <?php if($frente->fre_fecha_fin!=''): ?>
                                <?php echo e(" - ".date('d/m/Y',strtotime($frente->fre_fecha_fin))); ?>

                            <?php endif; ?>
                        </span>
                    </td>
                    <td>
                        <span>Periodo : </span><span class="font-weight-bold">
                            <?php if($frente->fre_fecha_inicio!=''): ?>
                                <?php echo e(date('Y',strtotime($frente->fre_fecha_inicio))); ?>

                            <?php endif; ?>
                            <?php if($frente->fre_fecha_fin!=''): ?>
                                <?php echo e(" - ".date('Y',strtotime($frente->fre_fecha_fin))); ?>

                            <?php endif; ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span> Tipo de consejo :
                            <?php if($frente->fre_tipo=='u'): ?>
                                <span class="font-weight-bold text-primary">HCU</span>
                            <?php else: ?>
                                <?php if($frente->fre_tipo=='f'): ?>
                                    <span class="font-weight-bold text-primary">HCF</span>
                                <?php else: ?>
                                    <span class="font-weight-bold text-primary">HCC</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </span>
                    </td>
                </tr>
            </table>

    </span>
    </div>

    <br/>
    <br/>
    <div class="bg-info centrar_bloque p-1 col-md-8 rounded shadow">
        <h5 class="text-white text-center">Lista de consejeros</h5>
    </div>
    <hr class="sidebar-divider"/>
    <div class="overflow-auto" style="height: 400px">
        <table class="table table-sm">
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>CI</th>
                <th>Participacion</th>
                <th>Periodo</th>
                <th>Renuncia</th>
            </tr>
            <?php $i=0;?>
            <?php $__currentLoopData = $consejeros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($i+=1); ?></td>
                    <td><?php echo e($c->per_apellido." ".$c->per_nombre); ?></td>
                    <td><?php echo e($c->per_ci); ?></td>
                    <td>
                        <?php if($c->ele_titular=='t'): ?>
                            <span class="bg-dark rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Titular</span>
                        <?php else: ?>
                            <span class="bg-light rounded pr-1 pl-1 text-dark font-weight-bold font-italic" style="font-size: 14px;">Suplente</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-dark font-weight-bold">
                        <?php echo e(date('d/m/Y',strtotime($c->ele_fecha_inicio))." - ".date('d/m/Y',strtotime($c->ele_fecha_fin))); ?>

                    </td>
                    <td>
                        <?php if($c->ele_fecha_renuncia!=''): ?>
                            <?php echo e(date('d/m/Y',strtotime($c->ele_fecha_renuncia))); ?>

                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </table>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcu/lista_frente.blade.php ENDPATH**/ ?>