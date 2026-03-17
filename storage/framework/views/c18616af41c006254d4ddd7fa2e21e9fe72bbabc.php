<?php if(Session::has('exitoagregar')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold"><?php echo session('exitoagregar'); ?></span>
    </div>
<?php endif; ?>
<?php if(Session::has('erroragregar')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold text-dark"><?php echo session('erroragregar'); ?></span>
    </div>
<?php endif; ?>
<table class="col-md-12 table table-sm table-hover table-info rounded" style="font-size: 12px">
    <tr class="bg-gradient-info text-white p-2">
        <th>Nº</th>
        <th>Sitra</th>
        <th>Nombre</th>
        <th>N° trámite</th>
        <th>N° Documento</th>
        <th>Opciones</th>
    </tr>
    <?php $i=1?>
    <?php $__currentLoopData = $detalle_apostilla; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
            <td><?php echo e($i); ?></td>
            <td></td>
            <td><?php echo e($d->lis_nombre); ?></td>
            <td><?php echo e($d->dapo_numero); ?></td>
            <td><?php echo e($d->dapo_numero_documento."/".$d->dapo_gestion_documento); ?></td>
            <td>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('quitar doumento - apo')): ?>
                    <?php if($tramite_apostilla->apos_estado<=1): ?>
                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark"
                           onclick="cargarDatos('<?php echo e(url("eliminar tramite agregado apostilla/$d->cod_dapo")); ?>','panel_lista_tramites_apostilla');cargarDatos('<?php echo e(url("listar tramite apostilla tabla/".$fecha)); ?>','panel_tabla_tramites')"
                           title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                        </a>
                    <?php else: ?>
                        <i class="fas fa-minus"></i>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
            <?php $i+=1?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/apostilla/tramite/fe_tramite_apostilla_tabla.blade.php ENDPATH**/ ?>