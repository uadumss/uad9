<?php $__env->startSection('contenido'); ?>
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
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;UNIDADES</h5>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear editar facultad - f')): ?>
                        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-white" data-toggle="modal" data-target="#Modal"
                           onclick="cargarDatos('fe_unidad/0','panel_modal')" >
                            + Unidad
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <div class="card-body">
            <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                <h5 class="text-white text-center">Lista de unidades</h5>
            </div>
            <hr class="sidebar-divider">
            <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
                <thead>
                <tr class="bg-gray-600 text-white">
                    <th>Nº</th>
                    <th class="">Nombre</th>
                    <th class="">Nivel</th>
                    <th>Opciones</th>
                </tr>
                </thead>
                <tbody>
                    <?php $j=1;?>
                <?php $__currentLoopData = $unidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class="border-right font-weight-bolder text-primary"><?php echo e($j); ?></th>
                        <td class="text-left"><?php echo e($u['uni_nombre']); ?></td>
                        <td class="text-left"><?php echo e($u['uni_nivel']); ?></td>
                        <td class="text-right">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear editar facultad - f')): ?>
                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#Modal" data-toggle="modal"
                                   onclick="cargarDatos('fe_unidad/<?php echo e($u['cod_uni']); ?>','panel_modal')" title="Editar unidad">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar facultad - f')): ?>
                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#Modal" data-toggle="modal"
                                   onclick="cargarDatos('formulario eliminar unidad/<?php echo e($u['cod_uni']); ?>','panel_modal')" title="Eliminar Unidad">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                        <?php $j++;?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acceso al sistema - f')): ?>
        <!--===========================MODAL NUEVA FACULTAD===================-->
        <div class="modal fade" id="Modal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_modal">

            </div>
        </div>
        <!--===========================END ==============================-->
    <?php endif; ?>
    <!-- =============================== ====================-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/unidad/unidad/l_unidad.blade.php ENDPATH**/ ?>