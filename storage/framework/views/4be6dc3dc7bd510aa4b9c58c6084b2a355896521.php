
<?php $__env->startSection('contenido'); ?>

    <script src="<?php echo e(asset('js/tinymce/tinymce.min.js')); ?>"></script>

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
            <div class=" ">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class=""><i class="fas fa-lock-open"></i>&nbsp;Plan de archivos</h5>
                    </div>
                    <div class="col-md-6">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear plan - rr')): ?>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right" data-target="#plan" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('lista planes')); ?>','panel_planes')">
                                + Plan</a>&nbsp;&nbsp;
                        <?php endif; ?>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear codigo - rr')): ?>
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mr-2" data-target="#nuevoCodigo"
                               data-toggle="modal" >+ Nuevo código</a>&nbsp;&nbsp;
                        <?php endif; ?>

                    </div>
                </div>

            </div>

        </div>
        <div class="card-body">
            <div class=" input-group -sm p-2">
                <div class="col-md-3 input-group shadow-sm p-1" style="font-size: 0.9em">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar plan :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm border border-info" name="gestion" onchange="$(location).attr('href','<?php echo e(url('lista codigos/')); ?>'+'/'+this.value);">
                        <option value="" disabled selected hidden></option>
                        <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($p['cod_plan']); ?>"><?php echo e($p['plan_numero'].' .- '.$p['plan_titulo']); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <a href="<?php echo e(url('listar temas resolucion corregir')); ?>" class="btn btn-sm btn-outline-info text-dark">Corregir temas</a>
                </div>
            </div>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-6 rounded shadow">
                        <?php if(sizeof($plan)>0){?>
                        <h5 class="text-white text-center"><?php echo e($plan1->plan_numero." - ".$plan1->plan_titulo); ?></h5>
                        <?php } ?>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr class="bg-gray-600 text-white">
                                    <th>Nº</th>
                                    <th class="">Código</th>
                                    <th class="">Título</th>
                                    <th class="">Descripcion</th>
                                    <th class="text-right">Opciones</th>
                                </tr>
                                </thead>
                            <tbody>
                            <?php $i=1;?>
                            <?php $__currentLoopData = $codigos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary"><?php echo e($i); ?></th>
                                    <td class="text-left font-weight-bold text-dark">
                                        <?php echo e($plan1->plan_numero.'/'.$c['carch_numero']); ?><br/>
                                    </td>
                                    <td class="text-left">
                                        <?php echo e($c['carch_titulo']); ?><br/>
                                    </td>
                                    <td class="text-left">
                                        <?php echo $c['carch_desc'];?>
                                    </td>
                                    <td class="text-right">
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar codigo - rr')): ?>
                                           <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#editarCodigo" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("fe_codigo/".$c['cod_carch'])); ?>','panel_editar')"
                                               title="Editar código"><i class="fas fa-edit"></i></a>

                                           <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#editarCodigo" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("editar detalle codigo/".$c['cod_carch'])); ?>','panel_editar')"
                                               title="Administrar código"><i class="fas fa-align-justify"></i></a>
                                        <?php endif; ?>
                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar codigo - rr')): ?>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#eliminarCodigo" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("f_eli_codigo/".$c->cod_carch)); ?>','panel_eliminar_codigo')"
                                                title="Eliminar código"><i class="fas fa-trash-alt"></i></a>
                                        <?php endif; ?>
                                            <a href="<?php echo e(url('ver archivado/'.$c['cod_carch'].'/f')); ?>" class="btn btn-light btn-circle btn-sm text-primary" title="Deshabilitar autoridad"> <i class="fas fa-angle-right"></i>
                                            </a>
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

    <!--===========================MODAL NUEVA CODIGO===================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear codigo - rr')): ?>
        <div class="modal fade" id="nuevoCodigo" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="<?php echo e(url('g_codigo')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content border-bottom-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user"></i> Nuevo Código</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span class="text-white" aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                <h6 class="text-white text-center">Formulario de nuevo código</h6>
                            </div>
                            <br/>
                            <table class="table-hover col-md-12" >
                                <tr>
                                    <th class="text-right font-italic" width="200">Número de código:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="codigo" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic"> Título :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="titulo" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic"> Plan :</th>
                                    <td class="border-bottom border-dark">
					<?php if($plan1): ?>
                                        <span class="text-danger font-weight-bold">&nbsp;&nbsp;<?php echo e($plan1->plan_numero); ?></span>
                                        <input type="hidden" name="cp" value="<?php echo e($plan1['cod_plan']); ?>">
					<?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic"> Temas :</th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control" name="desc" id="desc" cols="10" rows="5"></textarea>
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
    <script type="text/javascript">
        tinymce.init({
            selector: '#desc',

        });
    </script>
    <?php endif; ?>
    <!--===========================END ==============================-->
    <!--===========================MODAL PLAN===================-->
        <div class="modal fade" id="plan" style="" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-xl" role="document" id="panel_planes">


            </div>
        </div>
    <!--===========================END ==============================-->
    <!--===========================MODAL EDITAR CODIGO===================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar codigo - rr')): ?>
        <div class="modal fade" id="editarCodigo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_editar">

            </div>
        </div>
    <?php endif; ?>
    <!--===========================END===================-->
    <!--===========================MODAL EDITAR PLAN===================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar plan - rr')): ?>
    <div class="modal fade" id="editarPlan" style="z-index:1600;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" id="panel_editar_plan">

        </div>
    </div>
    <?php endif; ?>
    <!--===========================END===================-->

    <!-- ================== MODAL ELIMINAR PLAN===============-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar plan - rr')): ?>
        <div class="modal fade" id="eliminarPlan"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-danger">
                    <form action="<?php echo e(url('eliminar plan/')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <div class="modal-header bg-danger">
                            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar plan</h5>
                            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <span class="font-italic">Esta seguro de eliminar el plan :</span> <br/><br/>
                            <div class="row">
                                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eliminar_plan">

                                </div>
                                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                            </div>
                            <br/>
                            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-10" style="font-size: 0.8em">
                                * Esta acción se quedará registrado en el sistema
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-danger" type="submit" value="Aceptar"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <!-- =============================== ====================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar codigo - rr')): ?>
    <!-- ================== MODAL ELIMINAR CODIGO===============-->

    <div class="modal fade" id="eliminarCodigo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="<?php echo e(url('eliminar codigo/')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar código</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="font-italic">Esta seguro de eliminar el código de archivado :</span> <br/><br/>
                        <div class="row">
                            <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eliminar_codigo">

                            </div>
                            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                        </div>
                        <br/>
                        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-10" style="font-size: 0.8em">
                            * Esta acción se quedará registrado en el sistema
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-danger" type="submit" value="Aceptar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- =============================== ====================-->

    <script>
        function enviarPlan(id){
            var link = "<?php echo e(url('g_plan/')); ?>";
            var token = "<?php echo e(csrf_token()); ?>";
            var formulario = $('#'+id);
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                data:formulario.serialize(),
                //data:$('#form_editar').serialize(),
                success: function (resp) {
                    $('#panel_planes').html(resp);

                },
                error: function (data) {
                    $('#panel_planes').html(data);
                }
            });
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/codigo/l_codigo.blade.php ENDPATH**/ ?>