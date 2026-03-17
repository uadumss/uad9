
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
                <div class="col-md-4">
                    <span class="text-dark text-uppercase font-weight-bold">Datos del tomo</span>
                    <hr class="sidebar-divider">
                    <table class="text-primary">
                        <tr class="border-bottom">
                            <td class="font-italic">Número de tomo </td>
                            <td>: <?php echo e($tomo['tom_numero']); ?></td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="font-italic">Gestión </td>
                            <td>: <?php echo e($tomo['tom_gestion']); ?></td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="font-italic">Tipo de tomo </td>
                            <td>: <?php echo e($tipo_completo); ?></td>
                        </tr>

                        <tr class="border-bottom">
                            <td class="font-italic">Rago de documentos </td>
                            <td>: <?php echo e($tomo['tom_rango']); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-8">
                    <div class="justify-content-center text-center">
                        <span class="text-dark text-uppercase font-weight-bold">Carreras y facultades</span><br/><br/>
                    </div>

                    <table class="table table-sm">
                        <tr>
                            <th>Carrera</th>
                            <th>Facultad</th>
                            <th>Eliminar</th>
                        </tr>
                        <?php $i=1;?>
                        <?php $__currentLoopData = $carrera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td style="font-size: 0.8em;"><?php echo e($c->car_nombre); ?></td>
                            <td style="font-size: 0.8em;"><?php echo e($c->fac_nombre); ?></td>
                            <td>
                                <!--<a href="#" class="btn btn-light btn-circle btn-sm" data-target="#eliminarCar" data-toggle="modal" onclick="">
                                    <i class="fas fa-trash text-danger"></i>
                                </a>-->
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar carrera tomo - dyt')): ?>
                                <a href="#" class="btn btn-light btn-circle btn-sm" onclick="eliminarCar('e_carTomo/<?php echo e($c->cod_tcar); ?>')">
                                    <i class="fas fa-trash text-danger"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                            <?php $i++;?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
            </div>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('asignar carrera - dyt')): ?>
            <hr class="sidebar-divider"/>
                <div class="shadow m-5">
                    <table class="table">
                        <tr>
                            <td><span class="pt-1 text-danger font-weight-bold">Nueva carrera :</span></td>
                            <td>
                                <div class="">
                                    <span class="text-dark font-weight-bold">Asignar por carrera.</span>
                                    <div class="col-md-8 input-group">
                                        <select name="n_carrera" class="custom-select" id="n_carrera">
                                            <?php $__currentLoopData = $n_carrera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $nc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($nc['cod_car']); ?>"><?php echo e($nc['car_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <button class="btn btn-primary" onclick="asignarCarrera('a_tomocarrera',$('#n_carrera').val())">Asignar</button>
                                    </div>
                                    <br/>
                                    <br/>
                                    <span class="text-dark font-weight-bold">Asignar por facultad.</span>
                                    <div class="col-md-8 input-group">
                                        <select name="n_carrera" class="custom-select" id="n_fac">
                                            <?php $__currentLoopData = $facultad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($f['cod_fac']); ?>"><?php echo e($f['fac_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        &nbsp;
                                        &nbsp;
                                        &nbsp;
                                        <button class="btn btn-primary" onclick="asignarCarrera('a_tomofac',$('#n_fac').val())">Asignar</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>

                </div>
                <?php endif; ?>



<script>
    function asignarCarrera(ruta,cod){
        var link="<?php echo e(url('/')); ?>/"+ruta;
        var token = "<?php echo e(csrf_token()); ?>";
        var data="cc="+cod+"&ct=<?php echo e($tomo['cod_tom']); ?>";
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            data:data,
            success: function (resp) {

                $('#panel_editarcar').html(resp);
            },
            error: function () {
                $('#panel_editarcar').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
            }
        });
    }
    function eliminarCar(ruta){
        var link="<?php echo e(url('')); ?>"+"/"+ruta;
        $.ajax({
            url: link,
            type: 'GET',
            data:'',
            success: function (resp) {
                $('#panel_editarcar').html(resp);
            },
            error: function () {
                $('#panel_editarcar').html('<span class="text-danger font-weight-bold">Ocurrio un error, probablemente no tenga permisos para esta acción</span>');
            }
        });
    }
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/diplomas/tomo/editar_carrera.blade.php ENDPATH**/ ?>