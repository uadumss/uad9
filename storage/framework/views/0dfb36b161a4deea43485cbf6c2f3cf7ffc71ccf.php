
<?php $__env->startSection('contenido'); ?>
    <?php if(Session::has('exito')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo session('exito'); ?>

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
                <h5 class="m-0 font-weight-bold text-dark">Reportes diarios</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <?php if($redireccion=='dependiente'): ?>
                        <a href="<?php echo e(url('listar dependientes/')); ?>" class="btn btn-sm btn-outline-info text-dark "><i class="fas fa-arrow-circle-left"></i> Atrás</a><br/><br/>
                    <?php else: ?>
                        <a href="<?php echo e(url('listar tareas/'.$actividad['cod_act'])); ?>" class="btn btn-sm btn-outline-info text-dark "><i class="fas fa-arrow-circle-left"></i> Atrás</a><br/><br/>
                    <?php endif; ?>
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 alert-primary">
                            <div class="">
                                <h5 class="m-0 font-weight-bold text-dark">Datos de la tarea</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <i class="fas fa-folder-open text-warning"></i>&nbsp;&nbsp;<span class="text-primary font-weight-bolder">Actividad</span><br/>
                            <span class="text-dark font-weight-bolder"><?php echo e($actividad['act_nombre']); ?></span>

                            <br/><br/>
                            <table class="table table-sm ml-4 mr-2" style="font-size: 0.8em">
                                <tr>
                                    <td colspan="2"> <span class="text-primary font-weight-bolder">Tarea:</span><br/>
                                        <span class="text-dark"><?php echo e($tarea['tar_nombre']); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class="text-primary font-weight-bolder">Responsable: </span><br/>
                                        <span class="text-dark">
                                            <?php if($designa->foto!=''): ?>
                                                <img src="<?php echo e(url('img/foto/'.$designa->foto)); ?>" width="40" height="40" class="imgRedonda"/>
                                            <?php else: ?>
                                                <img src="<?php echo e(url('img/icon/sin foto'.$designa->sexo.'.png')); ?>" class="imgRedonda centrar_bloque" width="40" height="40">
                                            <?php endif; ?>

                                            <span><?php echo e($designa->name); ?></span>
                                        </span>
                                    </td>
                                </tr>
                                <tr>

                                    <td> <span class="text-primary font-weight-bolder">Fecha de inicio: </span><br/>
                                        <span class="text-dark"><?php echo e(date('d/m/Y',strtotime($tarea['tar_fi']))); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td> <span class="text-primary font-weight-bolder">Fecha de conclusión: </span><br/>
                                        <span class="text-dark">
                                            <?php if($tarea['tar_ff']!=''): ?>
                                                <?php echo e(date('d/m/Y',strtotime($tarea['tar_ff']))); ?>

                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <?php if($tarea->tar_cotidiano!='t'): ?>
                                    <td> <span class="text-primary font-weight-bolder">Avance de la tarea: </span><br/>
                                        <div class="col-md-10 pt-2">
                                            <div class="progress bg-gray-500">

                                                <?php $porcentaje=$totalPorcen[0]->porcentaje?>
                                                <?php if($porcentaje<1){$porcentaje=0;}?>
                                                <div class="progress-bar progress-bar-striped bg-success text-white" role="progressbar" style="width: <?php echo e($porcentaje); ?>%" aria-valuenow="<?php echo e($porcentaje); ?>" aria-valuemin="0" aria-valuemax="100">
                                                    <span class="font-weight-bolder"><?php echo e($porcentaje); ?> %</span>

                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <?php else: ?>
                                        <td><span class="bg-info rounded p-1 font-italic text-white font-weight-bold" style="font-size: 1em">Tarea cotidiana</span></td>
                                    <?php endif; ?>
                                </tr>

                                <?php if($tarea['tar_con']=='t'): ?>
                                    <tr>
                                        <td colspan="2" ><span class="text-danger font-weight-bolder font-italic">Esta tarea ya esta concluida</span></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-9">
                    <div class="bg-primary centrar_bloque col-md-3 rounded shadow-sm p-1">
                        <h5 class="text-white text-center">Lista de reportes diarios</h5>
                    </div>
                    <br/>
                    <table class="table-sm table shadow-sm rounded table-hover">
                        <tr class="bg-gray-600 text-white">
                            <th>Nº</th>
                            <th>Fecha del reporte</th>
                            <th>Calificación</th>
                            <th>Porcentaje</th>
                            <th>Fecha revisión</th>
                            <th>Revisar</th>
                        </tr>
                        <?php $i=1;$porcentajeTotal=0;?>
                        <?php $__currentLoopData = $diario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($d->dia_corregir=='c'): ?>
                                <tr class="alert-warning">
                            <?php else: ?>
                                <tr>
                            <?php endif; ?>
                                    <td><?php echo e($i); ?></td>
                                    <td><?php echo e(date('d/m/Y',strtotime($d->dia_fech))); ?></td>

                                        <?php if($d->dia_final=='t'): ?>
                                            <td colspan="2"><span class="mensaje-peligro">INFORME FINAL</span></td>
                                        <?php else: ?>
                                            <td><?php echo e($d->dia_calificacion); ?></td>
                                            <?php if($tarea->tar_cotidiano!='t'): ?>
                                                <td>
                                                    <?php echo e($d->dia_porcen); ?> %
                                                    <?php $porcentajeTotal+=$d->dia_porcen;?>

                                                </td>
                                            <?php else: ?>
                                                <td></td>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    <td><?php if($d->dia_fech_revision!=''): ?>
                                            <?php echo e(date('d/m/Y',strtotime($d->dia_fech_revision))); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="#" class="btn btn-light btn-circle btn-sm " data-target="#revisar" data-toggle="modal"
                                           onclick="cargarDatos('<?php echo e(url('revision diario/'.$d->cod_dia.'/'.$redireccion)); ?>','panel_revisar')">

                                            <?php if($d->dia_aceptado!='t'): ?>
                                                <i class="fas fa-edit text-primary"></i>
                                            <?php else: ?>
                                                <i class="fas fa-check text-success"></i>
                                            <?php endif; ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i++;?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <?php if($porcentajeTotal==100): ?>
                                        <td class="font-weight-bolder text-danger">Avance: <?php echo e($porcentajeTotal); ?> %</td>
                                        <td colspan="2" class="font-weight-bolder text-danger italic">Tarea concluida</td>
                                    <?php else: ?>
                                        <td class="font-weight-bolder text-dark">Avance: <?php echo e($porcentajeTotal); ?> %</td>
                                        <td colspan="2"></td>
                                    <?php endif; ?>
                                </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="revisar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_revisar">



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

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/actividad/reporte/l_reporte_diario.blade.php ENDPATH**/ ?>