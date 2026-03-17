<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i>Glosas de trámite </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <?php if(Session::has('exito')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold"><?php echo session('exito'); ?></span>
            </div>
        <?php endif; ?>
        <?php if(Session::has('error')): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold text-dark"><?php echo session('error'); ?></span>
            </div>
        <?php endif; ?>
        <?php if(count($errors)>0): ?>
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="font-weight-bold te"><?php echo e($e); ?> - </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
            <h6 class="text-white text-center">Formulario para editar glosas</h6>
        </div>
        <div style="font-size: 0.8em">
            <span class="font-weight-bold text-dark">Trámite: </span> <span class="font-italic"><?php echo e($tramite->tre_nombre); ?></span>
        </div>
        <hr class="sidebar-divider"/>
        <div class="row">
            <div class="col-md-4">
                <table class="table-sm">
                    <tr>
                        <td class="text-right font-italic font-weight-bold text-primary">* LISTA DE MODELOS</td>
                    </tr>
                    <?php $__currentLoopData = $glosas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>

                            <td class="border-dark border-bottom"><?php echo e($g->glo_titulo); ?></td>

                            <td class="border-dark border-bottom">
                                <a href="#" class="btn btn-sm btn-light btn-circle text-primary"
                                   onclick="cargarDatos('<?php echo e(url('fe_glosa/'.$g->cod_glo.'/'.$tramite->cod_tre)); ?>','panelGlosas')">
                                    <i class="fas fa-arrow-alt-circle-right"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar glosa - srv')): ?>
                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#glosa" data-toggle="modal"
                                   onclick="cargarDatos('<?php echo e(url('f_eliminar_glosa/'.$g->cod_glo)); ?>','panel_glosa')"
                                   title="Eliminar Glosa"> <i class="fas fa-trash-alt"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </table>
                <br/>
                <br/>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear glosa - srv')): ?>
                    <a href="#" class="btn btn-sm btn-primary" onclick="cargarDatos('<?php echo e(url('fe_glosa/0/'.$tramite->cod_tre)); ?>','panelGlosas')"> + Nuevo</a>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h6><span class="font-weight-bold text-primary font-italic text-right">* Formulario</span></h6>
                <div id="panelGlosas">

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tramite/fe_glosa.blade.php ENDPATH**/ ?>