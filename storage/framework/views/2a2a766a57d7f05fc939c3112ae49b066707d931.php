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
                <li><?php echo e($e); ?> - </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div class="">
            <div class="bg-primary centrar_bloque col-md-6 p-2 mb-2 rounded shadow">
                <h5 class="text-white text-center">Lista de Actividades</h5>
            </div>

            <br/>
                <table class="table table-sm shadow-sm rounded table-hover">
                    <tr class="bg-gradient-light shadow-sm text-dark">
                        <th>Nº</th>
                        <th>Nombre</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Conclusión</th>
                        <th>% Suma de tareas</th>
                        <th>% Avance de tareas</th>
                        <th>Gráfica</th>
                        <th>Opciones</th>
                        <th>Ingresar</th>
                    </tr>
                    <?php $i=1;?>
                    <?php $__currentLoopData = $act; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class="text-dark"><?php echo e($i); ?></th>
                        <td><?php echo e($a['act_nombre']); ?></td>
                        <td><?php echo e(date("d/m/Y", strtotime($a['act_inicio']))); ?></td>
                        <td>
                        <?php if($a['act_fin']!=''){?>
                            <?php echo e(date("d/m/Y", strtotime($a['act_fin']))); ?>

                        <?php }?>
                        </td>
                        <td>
                            <?php $__currentLoopData = $por; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($po->cod_act==$a['cod_act']){
                                    if($po->por==100){?>
                                        <span class="bg-primary text-white rounded font-weight-bolder" style="font-size: 0.8em;">&nbsp; <?php echo e($po->por); ?> % &nbsp;</span>
                                    <?php }else{?>
                                        <span class="bg-danger text-white rounded font-weight-bolder" style="font-size: 0.8em;" >&nbsp;<?php echo e($po->por); ?> % &nbsp;</span>
                                 <?php } }?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td>
                            <?php if($a['act_cotidiano']!='t'): ?>
                            <?php
                            $porcentaje=0;
                            foreach ($porcen as $p):
                                if($a['cod_act']==$p->cod_act){
                                    $porcentaje=$p->suma;
                                }
                            endforeach;
                                ?>
                                <div class="progress bg-gray-500">
                                    <?php if($porcentaje<33): ?>
                                        <?php if($porcentaje<1){$porcentaje=0;}?>
                                    <div class="progress-bar progress-bar-striped bg-danger text-white" role="progressbar" style="width: <?php echo e($porcentaje); ?>%" aria-valuenow="<?php echo e($porcentaje); ?>" aria-valuemin="0" aria-valuemax="100">

                                        <span class="font-weight-bolder"><?php echo e($porcentaje); ?> %</span>
                                    <?php else: ?>

                                         <?php if($porcentaje<66): ?>
                                                <div class="progress-bar progress-bar-striped bg-warning text-white" role="progressbar" style="width: <?php echo e($porcentaje); ?>%" aria-valuenow="<?php echo e($porcentaje); ?>" aria-valuemin="0" aria-valuemax="100">
                                         <?php else: ?>
                                                <div class="progress-bar progress-bar-striped bg-success text-white" role="progressbar" style="width: <?php echo e($porcentaje); ?>%" aria-valuenow="<?php echo e($porcentaje); ?>" aria-valuemin="0" aria-valuemax="100">
                                         <?php endif; ?>
                                             <span class="font-weight-bolder"><?php echo e($porcentaje); ?> %</span>
                                    <?php endif; ?>
                                    </div>
                                </div>
                                <?php else: ?>
                                    <span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Actividad cotidiana</span>
                                <?php endif; ?>
                        </td>
                        <td><?php if($a['act_cotidiano']=='f'): ?>
                                <a class="btn btn-primary btn-sm btn-circle btn-light text-primary"><i class="fas fa-chart-pie"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($a['act_hab']=='t'): ?>
                                <a onclick="cargarDatos('<?php echo e(url('habilitar actividad adm/'.$a['cod_act'])); ?>','panel')" class="btn btn-light btn-circle btn-sm text-success">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php else: ?>
                                <a onclick="cargarDatos('<?php echo e(url('habilitar actividad adm/'.$a['cod_act'])); ?>','panel')" class="btn btn-light btn-circle btn-sm text-dark">
                                    <i class="fas fa-lock"></i>
                                </a>
                            <?php endif; ?>
                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#actividad" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url("datos actividad adm/".$a['cod_act'].'/'.$id)); ?>','panel_actividad')">
                                <i class="fas fa-edit"></i>
                            </a>

                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#actividad" data-toggle="modal"
                                   onclick="cargarDatos('<?php echo e(url("f_eliminar actividad adm/".$a['cod_act'])); ?>','panel_actividad')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                        <td>
                            <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#actividad" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url('listar tareas actividad adm/'.$a['cod_act'])); ?>','panel_actividad')">
                                <h6 class="pt-2"><i class="fas fa-arrow-alt-circle-right"></i></h6>
                            </a>
                        </td>
                    </tr>
                        <?php $i++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
        </div>

        <!--============================MODAL EDITAR ACTIVIDAD-->
        <div class="modal fade" id="actividad" style="z-index: 1500;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" >
            <div class="modal-dialog modal-xl" role="document" id="panel_actividad">

            </div>
        </div>
        <!-- ==========================FIN EDITAR ACTIVIDAD-->
    <div class="modal fade shadow" id="tarea" style="z-index: 1500;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_tarea">

        </div>
    </div>
    <div class="modal fade" id="observacion" style="z-index: 1500;" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_observacion">

        </div>
    </div>





<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/act/listar_actividad_responsable.blade.php ENDPATH**/ ?>