
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <td>Tipo</td>
                                <th class="text-left">Número</th>
                                <th class="text-left">CI</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">Fecha solicitud</th>
                                <th class="text-left">Fecha de firma</th>
                                <th class="text-right">Fecha de recojo</th>
                                <th class="text-right">Opciones</th>
                                <th class="text-center">Entrega</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            <?php $__currentLoopData = $tramitas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($t->tra_anulado=='t'): ?>
                                    <tr class="alert-danger">
                                <?php else: ?>
                                    <tr>
                                        <?php endif; ?>
                                        <th class="border-right font-weight-bolder">
                                            <span class="text-primary"><?php echo e($i); ?></span>
                                        </th>
                                        <td>
                                            <?php   $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                                            ?>
                                            <span class="font-weight-bold rounded pl-2 pr-2 <?php echo e($tipo_tramite[$t->tra_tipo_tramite.'C']); ?>" style="font-size: 0.75em">
                                                <?php echo e($tipo_tramite[$t->tra_tipo_tramite]); ?>

                                            </span>
                                            <?php if($t->tra_obs=='t'): ?>
                                                &nbsp;<i class="fas fa-info-circle text-danger"></i>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-left"><?php echo e($t->tra_numero); ?></td>
                                        <td class="text-left"><?php echo e($t->per_ci); ?></td>
                                        <td class="text-left"><?php echo e($t->per_apellido." ".$t->per_nombre); ?>

                                            <?php if($t->tra_tipo_apoderado=='p'): ?>
                                                <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                                            <?php else: ?>
                                                <?php if($t->tra_tipo_apoderado=='d'): ?>
                                                    <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-right"><?php echo e(date('d/m/Y',strtotime($t->tra_fecha_solicitud))); ?></td>
                                        <td class="text-right"><?php if($t->tra_fecha_firma!=''){echo date('d/m/Y',strtotime($t->tra_fecha_firma));} ?> </td>
                                        <td class="text-right"><?php if($t->tra_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->tra_fecha_recojo));} ?> </td>
                                        <td class="text-right">
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("datos tramite legalizacion/".$t->cod_tra)); ?>','panel_traleg')"
                                               title="Editar legalización"><i class="fas fa-pen-alt"></i>
                                            </a>

                                            <a href="#traleg" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("f_cambiar tipo tramite/$t->cod_tra")); ?>','panel_traleg')"
                                               title="Cambiar tipo de trámite"><i class="fas fa-arrows-alt"></i>
                                            </a>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar traleg - srv')): ?>
                                            <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("f_eli_tra_legalizacion/".$t->cod_tra)); ?>','panel_traleg')"
                                               title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                            </a>
                                            <?php endif; ?>

                                        </td>
                                        <td class="text-right">
                                            <?php if($t->id_per!=''): ?>
                                                <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("panel entrega legalizacion/".$t->cod_tra)); ?>','panel_traleg')"
                                                   title="Entregar legalizaciones"> <i class="fas fa-hand-point-right"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $i++;?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                        <script src="<?php echo e(url('vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
                        <script src="<?php echo e(url('vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
                        <!-- Page level custom scripts -->
                        <script src="<?php echo e(url('js/demo/datatables-demo.js')); ?>"></script>

                        <script>
                            $('#dataTable').dataTable( {
                                "pageLength": 500
                            });
                        </script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/l_tabla_traleg.blade.php ENDPATH**/ ?>