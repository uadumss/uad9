<?php if(Session::has('exito')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo session('exito'); ?>

    </div>
<?php endif; ?>
<?php if(Session::has('error')): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <?php echo session('error'); ?>

    </div>
<?php endif; ?>

<div>
    <hr class="sidebar-divider">
    <div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear editar carrera - f')): ?>
        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-white float-right" data-toggle="modal" data-target="#facultad"
           onclick="cargarDatos('fe_carrera/<?php echo e($facultad->cod_fac); ?>/0','panel_contenido')">
            + Carrera
        </a>
    <?php endif; ?>
    </div>
    <?php if(sizeof($carreras)>0): ?>
        <span class="font-weight-bold text-danger font-italic">* <?php echo e($facultad->fac_nombre); ?></span>

        <br/><br/>
        <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
            <thead>
            <tr class="bg-gray-600 text-white">
                <th>Nº</th>
                <th class="">Nombre</th>
                <th>Nombre corto</th>
                <th>Opciones</th>
            </tr>
            </thead>
            <tbody>
            <?php $j=1;?>
            <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th class="border-right font-weight-bolder text-primary"><?php echo e($j); ?></th>
                    <td class="text-left"><?php echo e($c['car_nombre']); ?></td>
                    <td class="text-left"><?php echo e($c['car_abreviacion']); ?></td>
                    <td class="text-right">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear editar carrera - f')): ?>
                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#facultad" data-toggle="modal"
                           onclick="cargarDatos('fe_carrera/0/<?php echo e($c['cod_car']); ?>','panel_contenido')" title="Editar carrera">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar carrera - f')): ?>
                        <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#efacultad" data-toggle="modal"
                           onclick="cargarDatos('f_eli_carrera/0/<?php echo e($c['cod_car']); ?>','panel_econtenido')" title="Eliminar carrera">
                            <i class="fas fa-trash-alt"></i>
                        </a>
                        <?php endif; ?>
                        &nbsp;&nbsp;
                    </td>

                </tr>
                <?php $j++;?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    <?php else: ?>
        <br/><br/>
        <div class="alert-info p-3">
            La facultad <span class="font-weight-bold"><?php echo e($facultad->fac_nombre); ?></span>, no tiene carreras registradas
        </div>
    <?php endif; ?>
</div>
<script>
    function enviar(formulario,accion){
        var link = "<?php echo e(url('/')); ?>/"+accion+"/";
        var token = "<?php echo e(csrf_token()); ?>";
        var form = new FormData($('#'+formulario).get(0));
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            contentType: false,
            processData: false,
            data:form,
            //data:$('#form_editar').serialize(),
            success: function (resp) {
                cargarDatos('l_carrera/<?php echo e($facultad['cod_fac']); ?>','panel_carrera')
            },
            error: function (data) {
                alert('Error al crear la carrera')
            }
        });
    }
</script>


<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/unidad/carrera/l_carrera.blade.php ENDPATH**/ ?>