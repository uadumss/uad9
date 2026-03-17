<div>
    <div class="table-responsive">
        <div id="panel_tabla_tramites" class="col-md-12">
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                <thead>
                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th class="text-left">Nombre</th>
                    <th class="text-right">CI</th>
                    <th class="text-left">Referencia</th>
                    <th class="text-center">Sentencia</th>
                    <th class="text-center">Resolucion</th>
                    <th class="text-center">PDF</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody id="cuerpo">
                <?php $i=1;?>
                <?php $__currentLoopData = $sancionados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class="border-right font-weight-bolder"><?php echo e($i); ?></th>
                        <td><?php echo e($s->per_apellido.' '.$s->per_nombre); ?></td>
                        <td><?php echo e($s->per_ci); ?></td>
                        <td><?php echo e($s->san_referencia); ?></td>
                        <td><?php echo e($s->san_sentencia); ?></td>
                        <td><?php echo e($s->san_resolucion); ?></td>
                        <td>
                            <?php if($s->res_pdf!=''): ?>
                                <a class="text-danger" style="font-size: 20px">
                                    <a href="" class="btn btn-circle btn-light btn-sm text-danger float-right border" data-toggle="modal" data-target="#Modal"
                                       onclick="cargarDatos('<?php echo e(url('ver datos resolucion/'.$s->cod_res)); ?>','panel_modal')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                    </a>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td class="text-right">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar sancionado - noa')): ?>
                                <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal"
                                   onclick="cargarDatos('<?php echo e(url("editar sancionado/".$s->cod_san)); ?>','panel_modal')"
                                   title="Editar trámite"><i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>

                            <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal"
                               onclick="cargarDatos('<?php echo e(url("lista detalle sancion noatentado/".$s->cod_san)); ?>','panel_modal')"
                               title="Ver detalle de sanción"><i class="fas fa-align-justify"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar sancionado - noa')): ?>
                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Modal" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("formulario eliminar sancionado noatentado/".$s->cod_san)); ?>','panel_modal')"
                                   title="Eliminar sancionado"> <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                        <?php $i++;?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/sancionado/l_sancionado_tabla.blade.php ENDPATH**/ ?>