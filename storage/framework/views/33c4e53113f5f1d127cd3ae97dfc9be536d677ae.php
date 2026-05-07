
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
    <?php if(count($errors)>0): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($e); ?> - </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php

    ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;RESOLUCIONES  <?php echo e($gestion); ?></h5>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('ver tomos - rr')): ?>
                    <a href="<?php echo e(url('listar tomos resoluciones/'.$gestion)); ?>" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                <?php endif; ?>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="col-md-2 input-group shadow-sm p-1" style="font-size: 0.9em; ">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" onchange="$(location).attr('href','<?php echo e(url("listar resoluciones gestion")); ?>'+'/'+this.value+'/<?php echo e($tipo); ?>');">
                        <option value="<?php echo e($gestion); ?>"><?php echo e($gestion); ?></option>
                        <?php $año=date('Y');?>
                        <?php for($i=$año;$i>1927;$i--): ?>
                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="col-md-2 input-group shadow-sm p-1" style="font-size: 0.9em; ">
                    <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Tipo de resolución :&nbsp; &nbsp;</span>
                    <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" onchange="$(location).attr('href','<?php echo e(url("listar resoluciones gestion")); ?>'+'/<?php echo e($gestion); ?>/'+this.value);">
                        <option value="<?php echo e($tipo); ?>"><?php echo e(strtoupper($tipo)); ?></option>
                        <option value="rcu">RCU</option>
                        <option value="rr">RR</option>
                        <option value="rvr">RVR</option>
                        <option value="rs">RS</option>
                        <option value="rc">RC</option>
                        <option value="rcf">RCF</option>
                        <option value="rcc">RCC</option>
                    </select>
                </div>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('importar pdf - rr')): ?>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <form action="<?php echo e(url('complementar_pdf')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="input-group shadow-sm p-1" style="font-size: 0.9em; ">
                        <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Complementar PDF:&nbsp; &nbsp;</span>
                        <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" >
                            <option value="<?php echo e($gestion); ?>"><?php echo e($gestion); ?></option>
                            <?php $año=date('Y');?>
                            <?php for($i=$año;$i>1927;$i--): ?>
                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>&nbsp;&nbsp;
                        <input type="submit" class="btn btn-sm btn-danger shadow" value="Cambiar"/>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center"><?php echo e($tipoCompleto); ?> (<?php echo e(strtoupper($tipo)); ?>)</h6>
                        </div>
                        <span class="font-weight-bold text-dark" style="font-size: 0.9em"> Cantidad de Resoluciones : </span><span style="font-size: 0.9em"><?php echo e(sizeof($resoluciones)); ?> </span>
                        <hr class="sidebar-divider"/>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th>Resolución</th>
                                    <th>Descripción</th>
                                    <th>Objeto</th>
                                    <th>Tema</th>
                                    <th>Códigos</th>
                                    <th>Tomo</th>
                                    <th>Tipo</th>
                                    <th>Observaciones</th>

                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                <?php $__currentLoopData = $resoluciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr id="fila<?php echo e($i); ?>" style="font-size: 0.9em">
                                        <td class="text-primary border-right"><?php echo e($i); ?></td>
                                        <td id="num<?php echo e($i); ?>" class="text-right"><?php echo e($r->res_numero); ?><br/></td>
                                        <td id="desc<?php echo e($i); ?>">
                                            <div class="text-dark border-bottom "><?php echo e($r->res_desc); ?></div>
                                            <span style="font-size: 0.8em">
                                                <span class="font-weight-bold text-dark font-italic">Fecha: </span> <span><?php if($r->res_fecha!=''){?>
                                                                                                    <?php echo e(date('d/m/Y',strtotime($r->res_fecha))); ?>

                                                                                                    <?php }?>
                                                                                                </span> |
                                                    <span class="font-weight-bold text-dark font-italic">Tomo: </span> <span><?php echo e($r->tom_numero); ?></span> |

                                                    <?php if($r->res_pdf!=''): ?>
                                                        <span class="font-weight-bold text-dark font-italic">Resolución: </span><img src="<?php echo e(url('img/icon/tit.gif')); ?>" width="15" height="15">
                                                    <?php endif; ?>

                                                <?php if($r->res_ant!=''): ?>
                                                    <span class="font-weight-bold text-dark font-italic">Antecedentes: </span><img src="<?php echo e(url('img/icon/antecedente.gif')); ?>" width="15" height="15">
                                                <?php endif; ?>
                                            </span>
                                        </td>

                                        <td id="obj<?php echo e($i); ?>"><?php echo e($r->res_objeto); ?></td>
                                        <td id="tem<?php echo e($i); ?>"><?php echo e($r->res_tema); ?></td>

                                        <td id="cod<?php echo e($i); ?>">
                                            <?php $archivados=\App\Http\Controllers\ResolucionController::l_codigo($r->cod_res);
                                            echo $archivados;
                                            ?>
                                        </td>
                                        <td><?php echo e($r->tom_numero); ?></td>
                                        <td id="tip"><?php echo e($r->res_tipo); ?></td>
                                        <td>
                                            <?php if($r->res_obs!=''): ?>
                                                <i class="fas fa-eye text-danger"></i>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-right">
                                            <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verDatos"
                                               onclick="verDatos('<?php echo e(url('ver datos resolucion/'.$r->cod_res)); ?>','p_detalle')" title="Ver detalle de la resolución"> <i class="fas fa-arrow-right"></i></a>
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

        <!--=================================MODAL VER RESOLUCION ============================-->
        <div class="modal fade" id="verDatos" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
            <div class="modal-dialog modal-xl" role="document" id="p_detalle">

            </div>
        </div>
        <!--================================ END?===============================-->
        <script>
            function verDatos(url,panel,fila){
                $.ajax({
                    url:url,
                    type:'GET',
                    data:'',
                    success:function (resp) {
                        $('#'+panel).html(resp);
                        $('#fila_obs').val(fila);
                    },
                    error:function () {
                        alert('No se puede ejecutar la petición');
                    }
                });
            }
        </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/resolucion/l_resolucion_gestion.blade.php ENDPATH**/ ?>