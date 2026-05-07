<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Observaciones</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Observaciones del título</h6>
        </div>
        <hr class="sidebar-divider"/>

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
        <div class="row">
            <div class="col-md-8">

                <table class="col-md-11">
                    <tr class="border-bottom border-dark">
                        <th colspan="2"><span class="text-primary font-weight-bold text-right font-italic">Lista de observaciones</span><br/><br/></th>
                    </tr>
                    <?php $i=1;?>
                    <?php $__currentLoopData = $observaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="border-bottom border-dark">
                            <th><?php echo e($i); ?></th>
                            <td><div class="ml-2">
                                    <div class="text-justify p-2">
                                        <span style="font-size: 0.8em;color: #b91d19" class="font-weight-bold">Observación : </span>
                                        <span style="font-size: 0.8em;" class="text-dark font-weight-bold"><?php echo e(date('d/m/Y',strtotime($o->obs_fecha))); ?></span> <br/>
                                        <?php echo e($o['obs_observacion']); ?>

                                    </div>
                                    <?php if($o->obs_solucion==''): ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('registrar solucion titulo - dyt')): ?>
                                            <div class="text-right">
                                                <a class="btn btn-outline-info btn-sm text-dark" data-toggle="collapse" href="#solucion<?php echo e($i); ?>" role="button"
                                                   aria-expanded="false" aria-controls="collapseExample">
                                                    Solución <i class="fas fa-arrow-alt-circle-down text-primary"></i>
                                                </a>
                                            </div>

                                            <div class="collapse" id="solucion<?php echo e($i); ?>">
                                                <div>

                                                    <form action="<?php echo e(url('g_obs')); ?>" method="POST" id="formObs<?php echo e($i); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <table class="col-md-12">
                                                            <tr>
                                                                <th class="text-right"><span class="text-primary font-weight-bold text-right font-italic">Registrar solución</span></th>
                                                            </tr>
                                                            <tr>
                                                                <td><textarea name="obs" required class="form-control col-md-12"></textarea></td>
                                                            </tr>
                                                            <tr class="text-right">
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                        <input type="hidden" name="ct" value="<?php echo e($titulo[0]->cod_tit); ?>">
                                                        <input type="hidden" name="co" value="<?php echo e($o->cod_obs); ?>">
                                                    </form>
                                                    <div class=float-right>
                                                        <button class="btn btn-sm btn-primary" onclick="enviarObs(<?php echo e($i); ?>)">Guardar</button>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>

                                        <div class="alert-warning rounded p-2">
                                            <div><span style="font-size: 0.8em;color: #b91d19" class="font-weight-bold">Solucionado el : </span>
                                                <span style="font-size: 0.8em;" class="text-dark font-weight-bold"><?php echo e(date('d/m/Y',strtotime($o->obs_fecha_solucion))); ?></span> </div>
                                            <div class="justify-content-center"><?php echo e($o->obs_solucion); ?></div>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar observacion titulo - dyt')): ?>
                                                <div class="text-right">
                                                    <a class="btn btn-danger btn-sm  text-white " data-toggle="collapse" href="#solucion<?php echo e($i); ?>" role="button"
                                                       aria-expanded="false" aria-controls="collapseExample" onclick="eliminarObs(<?php echo e($i); ?>)">
                                                        Eliminar <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <form id="eliminarObs<?php echo e($i); ?>" method="POST">
                                                        <input type="hidden" name="co" value="<?php echo e($o->cod_obs); ?>">
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>

                        </tr>
                        <?php $i++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear observacion titulo - dyt')): ?>
                <div class="col-md-4">
                    <form action="<?php echo e(url('g_obs')); ?>" method="POST" id="formObs0">
                        <?php echo csrf_field(); ?>
                        <table class="col-md-12">
                            <tr>
                                <th><span class="text-primary font-weight-bold text-right font-italic">Nueva Observación</span></th>
                            </tr>
                            <tr>
                                <td><textarea name="obs" required class="form-control col-md-12"></textarea></td>
                            </tr>
                            <tr class="text-right">
                                <td></td>
                            </tr>
                        </table>
                        <input type="hidden" name="ct" value="<?php echo e($titulo[0]->cod_tit); ?>">
                    </form>
                    <div class=float-right>
                        <button class="btn btn-sm btn-primary" onclick="enviarObs(0)">Guardar</button>
                    </div>
                </div>
            <?php endif; ?>
            <script>
                function enviarObs(numero){
                    var link = "<?php echo e(url('g_obs/')); ?>";
                    var token = "<?php echo e(csrf_token()); ?>";
                    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                    $.ajax({
                        url: link,
                        type: 'POST',
                        data:$('#formObs'+numero).serialize(),
                        success: function (resp) {
                            $('#p_observacion').html(resp);
                            var fila=$('#fila_obs').val();
                            $('#obs'+fila).html('<i class="fas fa-eye text-danger"></i>')
                        },
                        error: function () {
                            $('#p_observacion').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
                        }
                    });
                }
                function eliminarObs(numero){
                    var link = "<?php echo e(url('e_obs/')); ?>";
                    var token = "<?php echo e(csrf_token()); ?>";
                    $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
                    $.ajax({
                        url: link,
                        type: 'POST',
                        data:$('#eliminarObs'+numero).serialize(),
                        success: function (resp) {
                            $('#p_observacion').html(resp);
                        },
                        error: function () {
                            $('#p_observacion').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
                        }
                    });
                }
            </script>
        </div>

    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
    </div>
</div>
<?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/diplomas/titulo/l_observacion.blade.php ENDPATH**/ ?>