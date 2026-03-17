<?php $__env->startSection('contenido'); ?>
    <?php if(Session::has('exito')): ?>
        <div class="alert alert-success alert-dismissible shadow">
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
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; <?php echo e(mb_strtoupper($tipo_completo)); ?>  GESTION <?php echo e($gestion); ?></h5>
                </div>
                <div class="col-md-6">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear tomo - dyt')): ?>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mr-2" data-target="#nuevoTitulo" data-toggle="modal">
                            <i class="fas fa-book-medical"></i> Nuevo Tomo</a>
                    <?php endif; ?>

                            <a href="<?php echo e(url('sintomo/'.$gestion."/".$tipo)); ?>" class=" d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right mr-2">
                                + Títulos sin tomo
                            </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class=" input-group shadow-sm p-2">


                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                    <select class="custom-select custom-select-sm border  col-md-1 border-info" name="gestion" onchange="$(location).attr('href','<?php echo e(url("tomo/$tipo")); ?>'+'/'+this.value);">
                        <option value="<?php echo e($gestion); ?>"><?php echo e($gestion); ?></option>
                        <?php $año=date('Y');?>
                        <?php for($i=$año;$i>1927;$i--): ?>
                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>

                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>

                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Tipo de documento :&nbsp; &nbsp;</span>
                    <select class="custom-select custom-select-sm col-md-1 border border-info" name="gestion" onchange="$(location).attr('href','<?php echo e(url("tomo/")); ?>'+'/'+this.value+'/<?php echo e($gestion); ?>');">
                        <option value="" disabled selected hidden></option>
                            <option value="db"> Diplomas de bachiller</option>
                            <option value="ca">Certificado académico</option>
                            <option value="da">Diploma académico</option>
                            <option value="tp">Título profesional</option>
                            <option value="di">Diplomado</option>
                            <option value="tpos">Títulos de posgrado</option>
                            <option value="re">Reválida</option>
                            <option value="su">Certificado supletorio</option>

                    </select>

                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('verificar titulos faltantes - dyt')): ?>
                    <form id="verificar_titulos">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="gestion" value="<?php echo e($gestion); ?>">
                        <input type="hidden" name="tipo" value="<?php echo e($tipo); ?>">
                        <input type="hidden" name="rango" value="0">
                    </form>

                    <a href="#" class="btn btn-danger btn-sm font-weight-bold" data-target="#editarTomo" data-toggle="modal"
                        onclick="enviar('verificar_titulos','<?php echo e(url('verificar titulos/')); ?>','panel_editar')">Verificar títulos</a>
                <?php endif; ?>
            </div>
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de tomos </h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-right">Nº Tomo</th>
                                <th class="text-right">Rango de documentos</th>
                                <th>Observación</th>
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
                                <?php $__currentLoopData = $tomo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($t->tom_numero!=0): ?>
                                        <tr>
                                                <td class="border-right"><?php echo e($i); ?> &nbsp;&nbsp;
                                                    <?php if(\Illuminate\Support\Facades\Auth::user()->id==$t['tom_usr']): ?>
                                                        <span style="font-size: 0.8em" class="border border-success rounded text-success font-weight-bold">&nbsp;Tú&nbsp;</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right"><?php echo e($t['tom_numero']); ?></td>
                                                <td class="text-right"><?php echo e($t['tom_rango']); ?></td>

                                                <td class="justify-content-center"><?php echo e($t['tom_obs']); ?></td>
                                                <td class="text-right">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar tomo - dyt')): ?>
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#editarTomo" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("fe_tomo/".$t["cod_tom"])); ?>','panel_editar')"
                                                        title="Editar Tomo"><i class="fas fa-edit"></i>
                                                    </a>
                                                    <?php endif; ?>

                                                    <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>

                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#editarCarrera" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("fe_car/".$t["cod_tom"])); ?>','panel_editarcar')"
                                                        title="Administrar carreras"><i class="fas fa-atlas"></i>

                                                        </a>

                                                <?php }?>
                                                <a href="<?php echo e(url('l_titulo/'.$t['cod_tom'])); ?>" class="btn btn-light btn-circle btn-sm" title="Listar titulos">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
						 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('imprimir listado tomo - dyt')): ?>

						<a href="<?php echo e(url('imprimir lista/'.$t['cod_tom'])); ?>" target="lista<?php echo e($t['tom_numero']); ?>" class="btn btn-light btn-circle btn-sm" title="Imprimir lista">
                                                     <i class="fas fa-print"></i>
                                                </a>
						<?php endif; ?>

                                                    <?php if($t['tom_cerrado']=='t'): ?>
                                                        &nbsp;<span style="font-size: 0.8em">  <i class="fas fa-lock text-dark"></i>     </span>&nbsp;
                                                    <?php else: ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('consolidar tomo - dyt')): ?>
                                                        <a class="btn btn-light btn-circle btn-sm text-success" data-target="#cerrarTomo" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("cerrar tomo/".$t['cod_tom'])); ?>','panel_cerrar_tomo')"
                                                            title="Consolidar Tomo"><i class="fas fa-lock-open"></i>
                                                        </a>
                                                        <?php else: ?>
                                                            &nbsp;<span style="font-size: 0.8em">  <i class="fas fa-lock-open text-success"></i>     </span>&nbsp;
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar tomo - dyt')): ?>
                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#eliminarTomo" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("f_eli_tomo/".$t['cod_tom'])); ?>','panel_eli_Tomo')"
                                                       title="Eliminar Tomo"> <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php $i++;?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--===========================MODAL NUEVO TOMO===================-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear tomo - dyt')): ?>
    <div class="modal fade" id="nuevoTitulo" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="<?php echo e(url('g_tomo')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Nuevo Tomo</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-hover">
                            <tr>
                                <th class="text-dark text-right" width=" 200">Número de tomo : </th>
                                <td><input class="form-control form-control-sm" placeholder="Ingrese el número del tomo"
                                           required name="n_tomo" pattern="[0-9]{1,3}"/></td>
                            </tr>
                            <tr>
                                <th class="text-dark text-right">Gestión: </th>
                                <td>
                                    <select class="form-control form-control-sm" name="gestion">
                                        <option value="<?php echo e($gestion); ?>"><?php echo e($gestion); ?></option>
                                        <?php $año=date('Y');?>
                                        <?php for($i=$año;$i>1927;$i--): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                            <?php endfor; ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-dark text-right">Rango: </th>
                                <td><div class="form-group row">
                                        &nbsp;&nbsp; De: &nbsp;&nbsp;<input name="r_menor" required class="form-control col-md-3 form-control-sm" pattern="[0-9]{1,5}"> &nbsp;&nbsp;
                                        Hasta: &nbsp;&nbsp;<input name="r_mayor" required class="form-control col-md-3 form-control-sm" pattern="[0-9]{1,5}">
                                    </div>
                                </td>

                            </tr>
                            <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                            <tr>
                                <th class="text-dark text-right">Carrera: </th>
                                <td>
                                    <select class="form-control form-control-sm" name="car">
                                        <?php $__currentLoopData = $carrera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($c['cod_car']); ?>"><?php echo e($c['car_nombre']); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </td>
                            </tr>
                            <?php }?>
                            <tr>
                                <th class="text-dark text-right">Observación: </th>
                                <td><textarea class="form-control" rows="3" name="obs" id="obs"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-primary" type="submit" value="Aceptar"/>
                    </div>
                </div>
                <input type="hidden" name="tipo" value="<?php echo e($tipo); ?>">
            </form>
        </div>
    </div>
    <?php endif; ?>
    <!--===========================END ==============================-->
    <!--===========================MODAL EDITAR TOMO===================-->

    <div class="modal fade" id="editarTomo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_editar">

        </div>
    </div>

    <!--===========================END===================-->
    <!--===========================MODAL CARRERA===================-->
    <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>

    <div class="modal fade" id="editarCarrera"  style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-book"></i> Editar Carrera</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="panel_editarcar">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Salir</button>
                </div>
            </div>
        </div>
    </div>

    <?php }?>
    <!-- ================== MODAL CERRAR TOMO===============-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('consolidar tomo - dyt')): ?>
    <div class="modal fade" id="cerrarTomo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-success">
                <form action="<?php echo e(url('cerrar_tomo/')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input  type="hidden" name="ct" id="ct" value=""/>
                    <div class="modal-header bg-success">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-lock"></i>&nbsp;&nbsp;Consolidar tomo</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="font-italic text-dark">Esta seguro de consolidar el tomo :</span> <br/><br/>
                        <div class="row">
                            <div class="font-weight-bold alert-success shadow text-center centrar_bloque col-md-8 p-2" id="panel_cerrar_tomo">

                            </div>
                            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                        </div>
                        <br/>
                        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-10" style="font-size: 0.8em">
                            * Ya no se modificaran los títulos de este tomo<br/>
                            * Esta acción se quedará registrado en el sistema
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <input class="btn btn-success" type="submit" value="Aceptar"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <!-- =============================== ====================-->
    <!-- ================== MODAL ELIMINAR TOMO-->
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar tomo - dyt')): ?>
    <div class="modal fade" id="eliminarTomo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="<?php echo e(url('e_tomo')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <input  type="hidden" name="ct" id="ct" value=""/>
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar Tomo</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <span class="font-italic">Esta seguro de eliminar : </span><br/><br/>
                    <div class="row">
                        <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="panel_eli_Tomo">

                        </div>
                        <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                    </div>
                    <br/>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
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
        function MsgEliminarTomo(tomo,ct){
            $('#ct').val(ct);
            $('#eliTomo').html("El tomo "+tomo+" de la gestión <?php echo e($gestion); ?>");
        }
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/diplomas/tomo/l_tomos.blade.php ENDPATH**/ ?>