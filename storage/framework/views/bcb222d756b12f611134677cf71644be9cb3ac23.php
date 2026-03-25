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
<div>
    <div class="row">
        <div class="col-md-2">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"
               onclick="cargarDatos('<?php echo e(url("historial asignacion/".$id)); ?>','panel')">
                <i class="fas fa-user-friends fa-sm"></i> Ver historial</a>
        </div>
        <div class="col-md-6 bg-primary p-1 centrar_bloque rounded shadow">
            <h5 class="text-center text-white">Asignación de funcionarios</h5>
        </div>
    </div>
    <br/>
    <div>
        <?php if($responsable['responsable']=='t'): ?>
        <div class="float-right">
            <span class="mensaje">* Asignación de nuevo funcionarios</span>

            <div class="input-group">
                <select class="form-control mr-2 form-control-sm shadow-sm" id="fun">
                    <?php $__currentLoopData = $personas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($p->id); ?>"><?php echo e($p->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <button class="btn btn-primary btn-sm shadow-sm" id="enviar" onclick="asignar()"><i class="fas fa-user "></i> Asignar</button>
            </div>
        </div>
        <?php else: ?>
            <div class="text-danger float-right font-weight-bolder">
                <div class="mensaje-peligro">El usuario no esta habilitado como responsable</div>
                <form id="form_hab_responsable">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="res" value="<?php echo e($id); ?>">
                </form>
                <button class="btn btn-danger btn-sm float-right" onclick="enviar('form_hab_responsable','<?php echo e(url('habilitar responsable')); ?>','panel')"><i class="fas fa-user-check"></i> Habilitar</button>
            </div>
        <?php endif; ?>
        <br/>
        <br/>
        <div class="">
            <span class="mensaje">* Lista de funcionarios asignados</span>
            <table class="table table-sm shadow-sm rounded">
                <tr class="bg-gradient-light text-dark">
                    <th>Nº</th>
                    <th>Nombre</th>
                    <th>Fecha Asignación</th>
                    <th>Fecha conclusión</th>
                    <th>Opciones</th>
                </tr>
                <?php $i=1;?>
                <?php $__currentLoopData = $fun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($i); ?></td>
                        <?php if($f->foto!=''): ?>
                            <td>    <img src="<?php echo e(url('img/foto/'.$f->foto)); ?>" width="40" height="40" class="imgRedonda"/>
                                <?php echo e($f->name); ?>

                            </td>
                        <?php else: ?>
                            <td class="pl-5">
                                <?php echo e($f->name); ?>

                            </td>
                        <?php endif; ?>

                        <td><?php if($f->ac_inicio!=''): ?>
                                <?php echo e(date('d/m/Y',strtotime($f->ac_inicio))); ?>

                            <?php endif; ?>
                        </td>
                        <td><?php if($f->ac_fin!=''): ?>
                                <?php echo e(date('d/m/Y',strtotime($f->ac_fin))); ?>

                            <?php endif; ?>
                        </td>
                        <td>

                            <?php if($f->ac_hab=='t'): ?>
                                <a href="#" class="btn btn-light btn-circle btn-sm text-success" title="Deshabilitar" data-target="#opcionesAsignacion"
                                   data-toggle="modal" onclick="cargarDatos('<?php echo e(url('obtener asignacion/a/'.$f->cod_ac)); ?>','panel_asignacion_contenido')"> <i class="fas fa-user-check"></i>
                                </a>
                            <?php else: ?>
                                <a href="#" class="btn btn-light btn-circle btn-sm text-dark" title="Habilitar" data-target="#opcionesAsignacion" data-toggle="modal"
                                   onclick="cargarDatos('<?php echo e(url('obtener asignacion/a/'.$f->cod_ac)); ?>','panel_asignacion_contenido')"> <i class="fas fa-user-lock"></i>
                                </a>
                            <?php endif; ?>

                            <!-- FINALIZACION DE ASIGNACION -->
                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#opcionesAsignacion" title="Finalizar asignación"
                                   data-toggle="modal" onclick="cargarDatos('<?php echo e(url('obtener asignacion/f/'.$f->cod_ac)); ?>','panel_asignacion_contenido')"> <i class="fas fa-book"></i>
                                </a>

                                <a href="#" class="btn btn-light btn-circle btn-sm text-dark" title="Eliminar" data-target="#opcionesAsignacion" data-toggle="modal"
                                   onclick="cargarDatos('<?php echo e(url('obtener asignacion/e/'.$f->cod_ac)); ?>','panel_asignacion_contenido')">
                                    <i class="fas fa-trash"></i>
                                </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </table>
        </div>
    </div>
</div>

<!--=======================MODAL OPCIONES DE ASIGNACION-->
<div class="modal fade" id="opcionesAsignacion" role="dialog" style="z-index:1500" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" id="panel_asignacion_contenido">

    </div>
</div>

<!--===========================END ==============================-->

<div class="modal fade" id="advertencia" style="z-index: 1500" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-left-danger border-bottom-danger">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalLabel">Asignar funcionario</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body row">
                <div  id="mensaje">
                    &nbsp;
                </div>
                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-3" >
                    Esta seguro de asignarlo nuevamente
                </div>
                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="asignar1()">Asignar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function asignar(){
        var fun = $('#fun').val();
        var url="<?php echo e(url('a_v_acargo')); ?>";
        var data="ip="+fun;
        var token = "<?php echo e(csrf_token()); ?>";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (resp) {
                var res="<?php echo e($id); ?>";
                data="fun="+fun+"&res="+res;
                if(resp){
                    $('#mensaje').html(resp);
                    $('#advertencia').modal({
                        show: true
                    });
                }else{
                    $('#advertencia').modal({
                        show: false
                    });
                    asignar1();
                }

            }
        });
    }
    function asignar1(){
        var fun = $('#fun').val();
        var res="<?php echo e($id); ?>";
        var url="<?php echo e(url('a_g_acargo')); ?>";
        var data="fun="+fun+"&res="+res;
        var token = "<?php echo e(csrf_token()); ?>";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });
        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (resp) {
                $("#panel").html(resp);
            }
        });
    }
</script>
<?php /**PATH C:\uad9\resources\views/session/administracion/e_asignacion.blade.php ENDPATH**/ ?>