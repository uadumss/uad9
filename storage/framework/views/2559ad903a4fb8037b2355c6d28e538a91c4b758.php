<div class="">
    <br/>
    <div class="bg-primary centrar_bloque  col-md-10 rounded shadow-sm p-1">
        <h5 class="text-white text-center">Lista de reportes diarios</h5>
    </div>
    <i class="fas fa-folder-open text-warning"></i>&nbsp;&nbsp;<span class="text-dark font-italic font-weight-bold">Actividad: </span>&nbsp;&nbsp;  <span class="text-dark font-italic"><?php echo e($actividad->act_nombre); ?></span><br/>
    <i class="fas fa-box text-danger"></i>&nbsp;&nbsp;<span class="text-dark font-italic font-weight-bold">Tarea: </span>&nbsp;&nbsp;  <span class="text-dark font-italic"><?php echo e($tarea->tar_nombre); ?></span>
    <br/>
    <?php if($tarea->tar_cotidiano=='t'): ?>
        <span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Tarea cotidiana</span>
    <?php endif; ?>
    <hr class="sidebar-divider"/>
    <br/>
    <div class="overflow-auto border" style="height: 500px">
        <table class="table-sm table shadow-sm rounded table-hover">
            <tr class="bg-gray-600 text-white">
                <th>Nº</th>
                <th>Fecha del reporte</th>
                <th>Calificación</th>
                <th>Porcentaje</th>
                <th>Fecha revisión</th>
                <th>Opciones</th>
            </tr>
            <?php $i=1;$porcentajeTotal=0;?>
            <?php $__currentLoopData = $diario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($d->dia_corregir=='t'): ?>
                    <tr class="alert-danger">
                <?php else: ?>
                    <?php if($d->dia_corregir=='c'): ?>
                        <tr class="alert-warning">
                    <?php else: ?>
                        <tr>
                    <?php endif; ?>

                <?php endif; ?>
                        <td><?php echo e($i); ?></td>
                        <td><?php echo e(date('d/m/Y',strtotime($d->dia_fech))); ?></td>
                        <?php if($d->dia_final=='t'): ?>
                            <td colspan="2"><span class="mensaje-peligro">INFORME FINAL</span></td>
                        <?php else: ?>
                            <td><?php echo e($d->dia_calificacion); ?></td>
                            <td>
                                <?php if($tarea->tar_cotidiano!='t'): ?>
                                        <?php echo e($d->dia_porcen); ?> %
                                        <?php $porcentajeTotal+=$d->dia_porcen;?>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                        <td>
                            <?php if($d->dia_fech_revision!=''): ?>
                                <?php echo e(date('d/m/Y',strtotime($d->dia_fech_revision))); ?>

                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="#" class="btn btn-light btn-circle btn-sm " data-target="#observacion" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url('revision diario adm/'.$d->cod_dia)); ?>','panel_observacion')">

                                <?php if($d->dia_aceptado!='t'): ?>
                                    <i class="fas fa-edit text-primary"></i>
                                <?php else: ?>
                                    <i class="fas fa-check text-success"></i>
                                <?php endif; ?>
                            </a>
                            <a href="#" class="btn btn-light btn-circle btn-sm " data-target="#observacion" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url('f_eliminar diario adm/'.$d->cod_dia)); ?>','panel_observacion')">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <?php if($porcentajeTotal==100): ?>
                            <td class="font-weight-bolder text-danger">Avance: <?php echo e($porcentajeTotal); ?> %</td>
                            <td colspan="2" class="font-weight-bolder text-danger italic">Tarea concluida</td>
                        <?php else: ?>
                            <td class="font-weight-bolder text-dark">Avance: <?php echo e($porcentajeTotal); ?> %</td>
                            <td colspan="2"></td>
                        <?php endif; ?>
                    </tr>
        </table>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/act/listar_diario_adm.blade.php ENDPATH**/ ?>