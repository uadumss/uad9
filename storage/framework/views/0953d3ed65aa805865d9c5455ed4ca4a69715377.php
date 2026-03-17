<form action="<?php echo e(url('g_funcionario/')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-user-alt"></i> Funcionario</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                <?php if($cod_fun==0): ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nuevo funcionario</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DEL FUNCIONARIO</span><br/><br/>
                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Apellidos y Nombres :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="nombre" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nº CI:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="ci"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Sexo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="sexo" id="sexo">
                                            <option value="M">MASCULINO</option>
                                            <option value="F">FEMENINO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Teléfonos:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="telefonos" id="telefonos" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha ingreso:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" name="fecha" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Email:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="email" class="form-control form-control-sm border-0" name="email" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Presentación de Folder:</th>
                                    <td class="border-bottom border-dark">
                                        &nbsp;<input type="checkbox" class="custom-checkbox" name="folder" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de funcionario:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo">
                                            <option value="D">Docente</option>
                                            <option value="A">Administrativo</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="carrera">
                                            <option value=""></option>
                                            <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ca): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($ca->cod_car); ?>"><?php echo e($ca->fac_abreviacion." - ".$ca->car_nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Nacionalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="nacionalidad">
                                            <option value="B">Boliviano</option>
                                            <option value="E">Extranjero</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">País origen:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="pais">
                                            <option value="29">Bolivia</option>
                                            <?php $__currentLoopData = $nacionalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($n['cod_nac']); ?>"><?php echo e($n['nac_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Facultad * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="facultad"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="carrera1"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Observaciones</th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="observacion"></textarea>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar Funcionario</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * DATOS DEL FUNCIONARIO</span><br/><br/>

                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Apellidos y Nombres :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="<?php echo e($funcionario->fun_nombre); ?>" required name="nombre" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nº CI:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="<?php echo e($funcionario->fun_ci); ?>" name="ci"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Sexo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="sexo" id="sexo">
                                            <?php if($funcionario->fun_sexo=='M'): ?>
                                                <option value="M">MASCULINO</option>
                                                <option value="F">FEMENINO</option>
                                            <?php else: ?>
                                                <option value="F">FEMENINO</option>
                                                <option value="M">MASCULINO</option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Teléfonos:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0"  value="<?php echo e($funcionario->fun_telefonos); ?>"name="telefonos" id="telefonos" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha ingreso:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0"  value="<?php echo e($funcionario->fun_fecha_ingreso); ?>" name="fecha" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Email:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="email" class="form-control form-control-sm border-0"  value="<?php echo e($funcionario->fun_email); ?>" name="email" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Presentación de Folder:</th>
                                    <td class="border-bottom border-dark">
                                        &nbsp;
                                        <?php if($funcionario->fun_folder=='t'): ?>
                                            <i class="text-primary fas fa-check-square"></i>
                                        <?php else: ?>
                                            <input type="checkbox" class="" name="folder" />
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de funcionario:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo">
                                            <?php if($funcionario->fun_doc_adm=='D'): ?>
                                                <option value="D">Docente</option>
                                                <option value="A">Administrativo</option>
                                            <?php else: ?>
                                                <option value="A">Administrativo</option>
                                                <option value="D">Docente</option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark align-text-top">Carrera:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select-sm custom-select border " name="carrera">
                                            <option value=""></option>
                                            <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ca): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($ca->cod_car); ?>"><?php echo e($ca->fac_abreviacion." - ".$ca->car_nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <span class="font-weight-bold" style="font-size: 12px">
                                            <?php if(sizeof($carrera)>0): ?>
                                                <?php $__currentLoopData = $carrera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a class="btn btn-light btn-circle btn-sm text-danger"onclick="cargarPlan('<?php echo e(url('e_carrera funcionario/'.$c->cod_trb)); ?>','panel_docente')"><i class="fas fa-trash-alt"></i></a>
                                                    <span><?php echo e($c->fac_abreviacion." - ".$c->car_nombre); ?></span><br/>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </span>
                                    </td>
                                </tr>

                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Nacionalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="nacionalidad">
                                            <?php if($funcionario->fun_nacionalidad=='B'): ?>
                                                <option value="B">Boliviano</option>
                                                <option value="E">Extranjero</option>
                                            <?php else: ?>
                                                <option value="E">Extranjero</option>
                                                <option value="B">Boliviano</option>
                                            <?php endif; ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">País origen:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="pais">
                                            <?php if($pais): ?>
                                                <option value="<?php echo e($pais->cod_nac); ?>"><?php echo e($pais->nac_nombre); ?></option>
                                            <?php endif; ?>
                                            <option value="29">Bolivia</option>
                                            <?php $__currentLoopData = $nacionalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($n['cod_nac']); ?>"><?php echo e($n['nac_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Facultad * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="facultad"><?php echo e($funcionario->fun_facultad); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera * </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="carrera1"><?php echo e($funcionario->fun_carrera); ?></textarea>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-right font-italic text-dark">Observaciones</th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control form-control-sm border-0" name="observacion"><?php echo e($funcionario->fun_obs_personal); ?></textarea>
                                    </td>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="cf" value="<?php echo e($funcionario->cod_fun); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>
<script>
    function cargarPlan(ruta,panel){
        $('#panel_error_archivo').hide();
        $.ajax({
            url: ruta,
            type: 'GET',
            data:'',
            success: function (resp) {
                $('#'+panel).html(resp);
            },
            error: function () {
                $('#'+panel).html("<br/><div class='alert-danger p-2 rounded'><span class='font-weight-bold'>Error: </span>Quiza no tenga permisos para esta acción </div>");
            }
        });
    }

</script>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/funcionario/fe_funcionario.blade.php ENDPATH**/ ?>