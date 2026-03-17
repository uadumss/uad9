<table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
    <thead>
    <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
        <th>N°</th>
        <th>Título</th>
        <th>Publicación</th>
        <th>Entrega de Documentos</th>
        <th>Gestión</th>
        <th>Opciones</th>
    </tr>
    </thead>
    <?php $i = 0;?>
    <tbody>
    <?php $__currentLoopData = $convocatorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($i+=1); ?></td>
            <td><?php echo e($c->con_nombre); ?> </td>
            <td><?php if($c->con_fecha_publicacion!=''): ?>
                    <?php echo e(date('d/m/Y',strtotime($c->con_fecha_publicacion))); ?>

                <?php endif; ?>
            </td>
            <td><?php if($c->con_fecha_entrega!=''): ?>
                <?php echo e(date('d/m/Y',strtotime($c->con_fecha_entrega))); ?>

            <?php endif; ?>

            <td><?php echo e($c->con_gestion); ?></td>
            <td class="text-right">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar convocatoria - noa')): ?>
                    <a href="" class="btn btn-sm btn-light btn-circle" data-toggle="modal" title="Editar convocatoria" data-target="#modal_convocatoria"
                       onclick="cargarDatos('<?php echo e(url("editar convocatoria noatentado/".$c->cod_con)); ?>','panel_convocatoria')">
                        <i class="fas fa-edit text-primary"></i>
                    </a>
                <?php endif; ?>
                <?php if($c->con_pdf!=''): ?>
                    <a href="<?php echo e(url("PDF_convocatoria/".$c->cod_con)); ?>" class="btn btn-sm btn-light btn-circle" title="Descargar convocatoria"
                       data-target="#modal_noAtentado" target="_blank">
                        <i class="far fa-file-pdf"></i>
                    </a>
                <?php else: ?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar convocatoria - noa')): ?>
                    <a href="" class="btn btn-sm btn-light btn-circle" data-toggle="modal" title="Eliminar onvocatoria"
                       data-target="#modal_agregar" onclick="cargarDatos('<?php echo e(url("formulario eliminar convocatoria noatentado/".$c->cod_con)); ?>','panel_agregar')">
                        <i class="fas fa-trash-alt text-danger"></i>
                    </a>
                <?php endif; ?>
                &nbsp;&nbsp;&nbsp;
                <a href="<?php echo e(url('listar tramite convocatoria/'.$c->cod_con)); ?>" class="btn btn-sm btn-light btn-circle" title="Mostrar trámites">
                    <i class="fas fa-arrow-circle-right text-primary"></i>
                </a>
            </td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/l_tabla_convocatoria.blade.php ENDPATH**/ ?>