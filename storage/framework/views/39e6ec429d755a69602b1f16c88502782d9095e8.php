<div class="p-1">
    <div class="bg-info centrar_bloque p-1 col-md-3 rounded shadow">
        <h6 class="text-white text-center">Lista de Duplicados</h6>
    </div>
    <?php if(Session::has('exitoDuplicado')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo session('exitoDuplicado'); ?>

        </div>
    <?php endif; ?>
    <?php if($tipo=='total'): ?>
        <button class="btn btn-sm btn-danger float-right" onclick="enviar('form_bloque_duplicado','<?php echo e(url('corregir bloque duplicado')); ?>','panel_duplicados')"><i class="fas fa-arrow-circle-right"></i> Corregir 500 duplicados</button>
        <form id="form_bloque_duplicado">
            <?php echo csrf_field(); ?>
        </form>
    <?php endif; ?>
    <br/>
    <div style="clear: both"></div>
    <div>
        <div style="font-size: 12px;height: 500px;" class="overflow-auto">
            <table class="table table-sm">
                <tr>
                    <th>N°</th>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Opciones</th>
                </tr>
                <?php $i=1;?>
                <?php $__currentLoopData = $lista; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr id="fila<?php echo e($i); ?>">
                        <td><?php echo e($i); ?></td>
                        <td><?php echo e($l->per_ci); ?></td>
                        <td id="nombre<?php echo e($i); ?>">
                        <?php if($tipo=='total'): ?>
                            <?php echo e($l->per_apellido." ".$l->per_nombre); ?>

                        <?php else: ?>
                            <?php echo \App\Models\Persona::getDatosPersonales($l->per_ci)?>
                        <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-light btn-circle text-primary" onclick="cargarDatos('<?php echo e(url('listar duplicado/'.$tipo.'/'.$l->per_ci)); ?>','panel_modal');$('#fila').val(<?php echo e($i); ?>);"
                                data-toggle="modal" data-target="#Modal"><i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
            <input type="hidden" name="fila" id="fila" value="0">
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/persona/duplicado/l_duplicados.blade.php ENDPATH**/ ?>