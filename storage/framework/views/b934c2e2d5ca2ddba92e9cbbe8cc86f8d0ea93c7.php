
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
                <h5 class=""><i class="fas fa-user"></i>&nbsp;&nbsp; Autoridades</h5>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear autoridad - rr')): ?>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#nuevoTitulo" data-toggle="modal">
                        <i class="fas fa-user-plus"></i> Nueva autoridad</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('habilitar autoridad - rr')): ?>
                        <a class="btn btn-outline-info text-dark shadow-sm" href="<?php echo e(url('autoridad deshabilitado/')); ?>">Listar deshabilitados</a>
                    <?php endif; ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de autoridades</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="">Datos</th>
                                <th class="">Cargo</th>
                                <th>Periodo</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <!--<tfoot>
                            <tr class="bg-gradient-secondary text-white">
                                <th>Nº</th>
                                <th>Número de Tomo</th>
                                <th>Rango de documentos</th>
                                <th>Cantidad de registros</th>
                                <th>Observación</th>
                                <th>Opciones</th>
                            </tr>
                            </tfoot>-->
                            <tbody>
                            <?php $i=1;?>
                            <?php $__currentLoopData = $autoridad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary"><?php echo e($i); ?></th>
                                    <td class="text-left">
                                        <?php echo e($a['aut_nombre']); ?><br/>
                                    </td>
                                    <td>
                                        <span class="text-left text-dark font-weight-bold"><?php echo e($a['aut_cargo']); ?></span>
                                    </td>
                                    <td>
                                        <span class="text-left text-dark font-weight-bold"><?php echo e($a['aut_inicio']." - ".$a['aut_fin']); ?></span>
                                    </td>
                                    <td class="text-right">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar autoridad - rr')): ?>
                                           <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#editarAut" data-toggle="modal" onclick="cargarDatos('fe_autoridad/<?php echo e($a['cod_aut']); ?>','panel_editar')"
                                               title="Editar autoridad"><i class="fas fa-user-edit"></i>
                                           </a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deshabilitar autoridad - rr')): ?>
                                            <a href="<?php echo e(url('/hab_autoridad/'.$a['cod_aut'].'/f')); ?>" class="btn btn-light btn-circle btn-sm text-danger" title="Deshabilitar autoridad"> <i class="fas fa-user-lock"></i>
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
    <!--===========================MODAL NUEVA AUTORIDAD===================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear autoridad - rr')): ?>
        <div class="modal fade" id="nuevoTitulo" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="<?php echo e(url('g_autoridad')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content border-bottom-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Nueva autoridad</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span class="text-white" aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                <h6 class="text-white text-center">Formulario de nueva autoridad</h6>
                            </div>
                            <br/>
                            <table class="table-hover col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Nombre :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="nombre" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Cargo :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="cargo" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Año de inicio :</th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" required pattern="[0-9]{1,4}" name="inicio" />
                                            <span style="font-size: 0.8em" class="text-primary font-weight-bold font-italic pt-2">Ejm: 2000</span>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Año de conclusión :</th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,4}" required name="fin" />
                                            <span style="font-size: 0.8em" class="text-primary font-weight-bold font-italic pt-2">Ejm: 2004</span>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-primary" type="submit" value="Aceptar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <!--===========================END ==============================-->
    <!--===========================MODAL EDITAR AUTORIDAD===================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar autoridad - rr')): ?>
        <div class="modal fade" id="editarAut" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_editar">

            </div>
        </div>
    <?php endif; ?>
    <!--===========================END===================-->

    <script>
        function cargarDatos(ruta,panel){
            var link="<?php echo e(url('/')); ?>"+"/"+ruta;
            $.ajax({
                url: link,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    $('#'+panel).html("Ocurrio un error, probablemente no tenga permisos para esta acción");
                }
            });
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/autoridad/l_autoridad.blade.php ENDPATH**/ ?>