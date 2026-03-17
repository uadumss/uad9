<?php if(Session::has('exitoCargo')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo session('exitoCargo'); ?>

    </div>
<?php endif; ?>
<?php if(Session::has('errorCargo')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo session('errorCargo'); ?>

    </div>
<?php endif; ?>
<table class="col-md-12 " >
    <tr class="bg-secondary text-white">
        <th>No.</th>
        <th>Nombre cargo</th>
        <th>Opciones</th>
    </tr>
    <?php $i=1;?>
    <?php $__currentLoopData = $cargos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="border-bottom">
            <td><?php echo e($i); ?></td>
            <td><?php echo e($c->carg_nombre); ?></td>
            <td>
                <form id="form_eliminar<?php echo e($i); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="cc" value="<?php echo e($c->cod_con); ?>">
                    <input type="hidden" name="ca" value="<?php echo e($c->cod_carg); ?>">
                </form>
                <a class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#modal_agregar" onclick="cargarDatos('<?php echo e(url('cargos convocatoria noatentado/'.$c->cod_carg.'/'.$convocatoria->cod_con)); ?>','panel_agregar')"><i class="fas fa-edit"></i></a>
                <a class="btn btn-light btn-circle btn-sm text-danger" onclick="enviar('form_eliminar<?php echo e($i); ?>','<?php echo e(url('eliminar cargo convocatoria noatentado')); ?>','panel_cargos')"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
        <?php $i++;?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/fe_tabla_cargo.blade.php ENDPATH**/ ?>