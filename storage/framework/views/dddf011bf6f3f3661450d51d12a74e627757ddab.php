<?php $__env->startSection('contenido'); ?>
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
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-sign-in"></i>&nbsp;&nbsp; Apostilla</h5>
            </div>
        </div>
        <div class="card-body">
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="input-group">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear documento apostilla - apo')): ?>
                            <a href="#" class="btn btn-outline-info btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#Tramite" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url("editar documento apostilla/0")); ?>','panel_tramite')">
                                +  Trámite apostilla</a>
                            <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                        <?php endif; ?>
                    </div>

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de Trámites de Apostilla</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" style="font-size: 0.85em" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-left">Nombre</th>
                                <th class="text-left">N° Cuenta</th>
                                <th class="text-left">Asociado</th>
                                <th class="text-right">Costo</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php $i=1;?>
                            <?php $__currentLoopData = $tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($t['lis_hab']=='t'): ?>
                                    <tr style="font-size: 0.90em">
                                <?php else: ?>
                                    <tr class="alert-danger" style="font-size: 0.90em">
                                        <?php endif; ?>

                                        <th class="border-right font-weight-bolder">
                                            <span class="text-primary"><?php echo e($i); ?></span>
                                        </th>
                                        <td class="text-left"><?php echo e($t['lis_nombre']); ?> </td>
                                        <td><?php echo e($t['lis_cuenta']); ?></td>
                                        <td class="text-left"><?php echo e(strtoupper($t['lis_asociado'])); ?></td>
                                        <td class="text-left"><?php echo e($t['lis_monto']); ?> Bs.</td>
                                        <td class="text-right">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar documento apostilla - apo')): ?>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#Tramite" data-toggle="modal"
                                                   onclick="cargarDatos('<?php echo e(url("editar documento apostilla/".$t["cod_lis"])); ?>','panel_tramite')"
                                                   title="Editar trámite apostilla"><i class="fas fa-edit"></i>
                                                </a>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('habilitar documento apostilla - apo')): ?>
                                                <a href="<?php echo e(url('habilitar documento apostilla/'.$t['cod_lis'])); ?>" class="btn btn-light btn-circle btn-sm" title="Habilitar tramite apostilla">
                                                    <?php if($t['lis_hab']=='t'): ?>
                                                        <i class="fas fa-check-square text-success"></i>
                                                    <?php else: ?>
                                                        <i class="fas fa-minus-circle text-danger"></i>
                                                    <?php endif; ?>
                                                </a>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar documento apostilla - apo')): ?>
                                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Tramite" data-toggle="modal"
                                                   onclick="cargarDatos('<?php echo e(url("eliminar documento apostilla/".$t['cod_lis'])); ?>','panel_tramite')"
                                                   title="Eliminar trámite apostilla"> <i class="fas fa-trash-alt"></i>
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
        </div>
    </div>
    <!--===========================MODAL NUEVA LEGALIZACION===================-->
    <div class="modal fade" id="Tramite" style="z-index: 1500;" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_tramite">

        </div>
    </div>
    <div class="modal fade" id="glosa" style="z-index: 1500;" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_glosa">

        </div>
    </div>

    <!-- =============================== ====================-->
    <script>
        function enviar(formulario,ruta,panel){
            //$.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                type: "POST",
                url: ruta,
                data: $("#"+formulario).serialize(), // Adjuntar los campos del formulario enviado.
                success: function(resp)
                {
                    $('#'+panel).html(resp);
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/apostilla/apostilla/l_apostilla.blade.php ENDPATH**/ ?>