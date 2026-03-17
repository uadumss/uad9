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
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-dark"><i class="fas fas fa-newspaper"></i>&nbsp;&nbsp;Actividades</h5>
                <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#nuevaActividad" data-toggle="modal"
                   onclick="cargarDatos('<?php echo e(url("obtener actividad/0")); ?>','panel_actividad')">
                    + Actividad</a>
            </div>
        </div>

        <div class="card-body">
            <div class="bg-primary centrar_bloque col-md-6 p-2 mb-2 rounded shadow">
                <h5 class="text-white text-center">Lista de Actividades</h5>
            </div>
            <br/>
                <table class="table table-sm shadow-sm rounded table-hover">
                    <tr class="bg-gray-600 shadow-sm text-white">
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

                        <td class=""><?php echo e($i); ?></td>
                        <td><?php echo e($a['act_nombre']); ?></td>
                        <td><?php echo e(date("d/m/Y", strtotime($a['act_inicio']))); ?></td>
                        <td>
                        <?php if($a['act_fin']!=''){?>
                            <?php echo e(date("d/m/Y", strtotime($a['act_fin']))); ?>

                        <?php }?>
                        </td>
                        <td>
                            <?php if($a['act_cotidiano']=='f'): ?>
                                <?php $__currentLoopData = $por; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $po): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($po->cod_act==$a['cod_act']){
                                        if($po->por==100){?>
                                            <span class="bg-primary text-white rounded font-weight-bolder" style="font-size: 0.8em;">&nbsp; <?php echo e($po->por); ?> % &nbsp;</span>
                                        <?php }else{?>
                                            <span class="bg-danger text-white rounded font-weight-bolder" style="font-size: 0.8em;" >&nbsp;<?php echo e($po->por); ?> % &nbsp;</span>
                                    <?php } }?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                                <span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 0.8em">Actividad cotidiana</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($a['act_cotidiano']=='f'): ?>
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
                                        <span class="font-weight-bolder"><?php echo e($porcentaje); ?> % </span>
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
                            <?php endif; ?>

                        </td>
                        <td><?php if($a['act_cotidiano']=='f'): ?>
                                <a class="btn btn-primary btn-sm btn-circle btn-light text-primary"><i class="fas fa-chart-pie"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?php if(Auth::user()->id==$a['id']): ?>
                                <?php if($a['act_hab']=='t'): ?>
                                    <a href="<?php echo e(url('habilitar_Actividad/'.$a['cod_act'])); ?>" class="btn btn-light btn-circle btn-sm text-success">
                                        <i class="fas fa-check"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(url('habilitar_Actividad/'.$a['cod_act'])); ?>" class="btn btn-light btn-circle btn-sm text-dark">
                                        <i class="fas fa-lock"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#nuevaActividad" data-toggle="modal"
                                    onclick="cargarDatos('<?php echo e(url("obtener actividad/".$a['cod_act'])); ?>','panel_actividad')">
                                    <i class="fas fa-edit"></i>
                                </a>


                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#nuevaActividad" data-toggle="modal"
                                    onclick="cargarDatos('<?php echo e(url("f_eliminar actividad/".$a['cod_act'])); ?>','panel_actividad')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(url('listar tareas/'.$a['cod_act'])); ?>" class="btn btn-light btn-circle btn-sm text-primary">
                                <h6 class="pt-2"><i class="fas fa-angle-right"></i></h6>
                            </a>
                        </td>
                    </tr>
                        <?php $i++;?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
        </div>
    </div>
    <div class="modal fade" id="nuevaActividad" tabindex="-1" style="z-index: 1500" role="dialog" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-lg" role="document" id="panel_actividad">

        </div>
    </div>
    <script type="text/javascript">
        function cargarDatos(ruta,panel){
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center'><div class='spinner-border text-danger' role='status'> <span class='visually-hidden'></span></div></div>");
            $.ajax({
                url: ruta,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/actividad/l_actividad.blade.php ENDPATH**/ ?>