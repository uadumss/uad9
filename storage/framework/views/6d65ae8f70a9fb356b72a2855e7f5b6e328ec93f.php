<table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
    <thead>
    <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
        <th>Nº</th>
        <th class="text-left">Número</th>
        <th class="text-left">CI</th>
        <th class="text-left">Nombre</th>
        <th class="text-left">Fecha solicitud</th>
        <th class="text-left">Fecha firma</th>
        <th class="text-right">Fecha recojo</th>
        <th class="text-center">Opciones</th>
        <th class="text-center">Entrega</th>
    </tr>
    </thead>
    <tbody id="cuerpo">
    <?php $i=1;?>
    <?php $__currentLoopData = $tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <th class="border-right font-weight-bolder">
                <span class="text-primary"><?php echo e($i); ?></span>
            </th>
            <td class="text-left">UAD<?php echo e($t->apos_numero); ?></td>
            <td class="text-left"><?php echo e($t->per_ci); ?></td>
            <td class="text-left"><?php echo e($t->per_apellido." ".$t->per_nombre); ?>

                <?php if($t->apos_apoderado=='p'): ?>
                    <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                <?php else: ?>
                    <?php if($t->apos_apoderado=='d'): ?>
                        <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td class="text-right"><?php echo e(date('d/m/Y',strtotime($t->apos_fecha_ingreso))); ?></td>
            <td class="text-right"><?php if($t->apos_fecha_firma!=''){echo date('d/m/Y',strtotime($t->apos_fecha_firma));} ?> </td>
            <td class="text-right"><?php if($t->apos_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->apos_fecha_recojo));} ?> </td>
            <td class="text-right">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar trámite - apo')): ?>
                    <a href="#apostilla" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal"
                       onclick="cargarDatos('<?php echo e(url("editar tramite apostilla/$t->cod_apos")); ?>','panel_apostilla')"
                       title="Insertar datos al trámite"><i class="fas fa-edit"></i>
                    </a>
                <?php endif; ?>

                <?php if($t->apos_estado==1): ?>
                   <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('firma trámite - apo')): ?>
                        <a href="<?php echo e(url("firmar tramite apostilla/$t->cod_apos")); ?>" class="btn btn-light btn-circle btn-sm text-primary"
                           title="Firmar trámite"> <i class="fas fa-pen-alt"></i>
                        </a>
                   <?php endif; ?>
                <?php else: ?>
                    <?php if($t->apos_estado==2): ?>
                        &nbsp;&nbsp;<i class="fas fa-pen-alt text-success"></i>&nbsp;&nbsp;
                    <?php else: ?>
                        &nbsp;&nbsp;<i class="fas fa-pen-alt text-dark"></i>&nbsp;&nbsp;
                    <?php endif; ?>

                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar trámite - apo')): ?>
                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#apostilla" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("formulario eliminar tramite apostilla/$t->cod_apos")); ?>','panel_apostilla')"
                       title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                    </a>
                <?php endif; ?>

            </td>
            <td class="text-right">
                <?php if($t->apos_estado==2): ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('entregar trámite - apo')): ?>
                        <?php if($t->cod_apo==''): ?>
                            <form method="POST" action="<?php echo e(url("entrega tramite apostilla")); ?>" id="<?php echo e($t->apos_numero); ?>">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="ca" value="<?php echo e($t->cod_apos); ?>">
                                <input type="hidden" name="apo" value="T">

                            </form>
                            <a class="btn btn-light btn-circle btn-sm text-success" onclick="$('#<?php echo e($t->apos_numero); ?>').submit();">
                                <i class="fas fa-hand-point-right"></i>
                            </a>
                        <?php else: ?>
                            <a class="btn btn-light btn-circle btn-sm text-success" data-target="#apostilla" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("formulario entrega tramite apostilla/$t->cod_apos")); ?>','panel_apostilla')"
                               title="Entregar tramite de apostilla"> <i class="fas fa-hand-point-right"></i></a>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if($t->apos_estado==3): ?>
                        <span class="border rounded border-info p-1 text-success">Entregado...</span>
                    <?php endif; ?>
                <?php endif; ?>
            </td>

        </tr>
            <?php $i++;?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<script>
    $('#dataTable').dataTable( {
        "pageLength": 500
    });
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/apostilla/tramite/l_tramite_apostilla_tabla.blade.php ENDPATH**/ ?>