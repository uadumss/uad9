
<?php $__env->startSection('contenido'); ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class=" ">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class=""><i class="fas fa-check"></i>&nbsp;Corregir resoluciones</h5>
                    </div>

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="bg-primary centrar_bloque p-1 col-md-6 rounded shadow">
                <h5 class="text-white text-center">Lista de temas</h5>
            </div>

            <div class=" input-group -sm p-2">
                <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form id="form_corregir_temas" action="<?php echo e(url('corregir temas resolucion')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                            <input type="text"  class="form-control-sm form-control" name="criterio" /> &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" class="btn btn-sm btn-outline-info text-dark" name="enviar" value="Buscar"/>
                        </div>
                    </form>
                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form id="form_corregir_temas" action="<?php echo e(url('asignar temas resolucion corregido')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                            <input type="hidden" name="criterio" value="<?php echo e($criterio); ?>">
                            <select class="custom-select custom-select-sm " name="tema">
                                <option></option>
                                <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($p->cod_det); ?>"><?php echo e($p->plan_numero."/".$p->carch_numero." ".$p->carch_titulo." - ".$p->det_nombre); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <input type="submit" class="btn btn-sm btn-outline-info text-dark" name="enviar" value="Asignar tema"/>
                        </div>
                    </form>
                </div>
            </div>
            <hr/>
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                <thead>
                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th>Tema</th>
                    <th>Cantidad</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                <?php $i=1;?>
                <?php $__currentLoopData = $resultado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="fila<?php echo e($i); ?>" style="font-size: 0.9em">
                        <td class="text-primary border-right"><?php echo e($i); ?></td>
                        <td ><?php echo e($r->tema); ?></td>
                        <td class="text-right"><?php echo e($r->cantidad); ?>

                            <a class="btn btn-sm btn-light btn-circle text-danger" data-toggle="modal" data-target="#Detalle" onclick="cargarDatos('<?php echo e(url('mostrar resoluciones tema corregir/'.$r->tema)); ?>','panel_detalle')">
                                <i class="fas fa-file-pdf"></i>
                            </a>
                        </td>

                        <td class="text-right">
                            <div id="panel_asignar<?php echo e($i); ?>">
                                <form id="form_corregir_temas<?php echo e($i); ?>" action="" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <div class="input-group shadow-sm p-1" style="font-size: 0.9em">
                                        <input type="hidden" name="criterio" value="<?php echo e($r->tema); ?>">
                                        <select class="custom-select custom-select-sm  rounded" name="tema">
                                            <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($p->cod_det); ?>"><?php echo e($p->plan_numero."/".$p->carch_numero." ".$p->carch_titulo." - ".$p->det_nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        &nbsp;&nbsp;&nbsp;&nbsp;<button class="btn btn-sm btn-outline-info text-dark" type="button"
                                                    onclick="enviar('form_corregir_temas<?php echo e($i); ?>','<?php echo e(url('asignar temas resolucion corregido')); ?>','panel_asignar<?php echo e($i); ?>')">Asignar tema</button>
                                    </div>
                                </form>
                            </div>
                        </td>
                    </tr>
                        <?php $i++;?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="Detalle" style="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_detalle">

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/codigo/l_corregir_tema.blade.php ENDPATH**/ ?>