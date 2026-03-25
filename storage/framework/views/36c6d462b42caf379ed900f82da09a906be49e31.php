<form action="<?php echo e(url('g_documento titularidad/')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> Facultad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                <?php if($cod_dt==0): ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nuevo documento de titularidad</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DEL DOCUMENTO</span><br/><br/>
                    <div class="row">
                        <div class="col-md-6">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Materia :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="materia"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm" name="carrera" id="carrera">
                                            <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c->cod_car); ?>"><?php echo e($c->fac_abreviacion." - ".$c->car_nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Categoria:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm border-0" name="categoria" id="categoria">
                                            <option value="DOCENTE TITULAR">DOCENTE TITULAR</option>
                                            <option value="DOCENTE ORDINARIO">DOCENTE ORDINARIO</option>
                                            <option value="DOCENTE ASISTENTE">DOCENTE ASISTENTE</option>
                                            <option value="DOCENTE TITULAR ASISTENTE">DOCENTE TITULAR ASISTENTE</option>
                                            <option value="DOCENTE ADJUNTO">DOCENTE ADJUNTO</option>
                                            <option value="DOCENTE CATEDRÁTICO">DOCENTE CATEDRÁTICO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de emisión:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" name="fecha"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Número de resolución :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="numero" id="numero" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Gestión:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm border-0" name="gestion" id="gestion">
                                            <option value=""></option>
                                            <?php
                                                $gestion=date('Y');
                                                for ($gestion1=date('Y');$gestion>1960;$gestion--){
                                                    echo "<option value='".$gestion."'>".$gestion."</option>";
                                                }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de resolución:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" name="fecha_resolucion" id="fecha_resolucion" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Verificado :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="verificado"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Universidad :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="universidad"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Detalle :</th>
                                    <td class="border-bottom border-dark">
                                        <textarea type="text" class="form-control form-control-sm border-0" name="detalle"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Observación :</th>
                                    <td class="border-bottom border-dark">
                                        <textarea type="text" class="form-control form-control-sm border-0"  name="observacion"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <input type="hidden" name="cf" value="<?php echo e($cod_fun); ?>">
                <?php else: ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar el documento de titularidad</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * DATOS DEL DOCUMENTO</span><br/><br/>

                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Materia :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="<?php echo e($titularidad->dt_materia); ?>" name="materia"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Carrera:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm" name="carrera" id="carrera">
                                            <?php if($titularidad->cod_car!=''): ?>
                                            <option value="<?php echo e($titularidad->cod_car); ?>"><?php echo e($titularidad->fac_nombre." - ".$titularidad->car_nombre); ?></option>
                                            <?php endif; ?>
                                            <option></option>
                                            <?php $__currentLoopData = $carreras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($c->cod_car); ?>"><?php echo e($c->fac_abreviacion." - ".$c->car_nombre); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Categoria:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="categoria" id="categoria">
                                            <?php if($titularidad->dt_categoria!=''): ?>
                                            <option value="<?php echo e($titularidad->dt_categoria); ?>"><?php echo e($titularidad->dt_categoria); ?></option>
                                            <?php endif; ?>
                                            <option></option>
                                            <option value="DOCENTE TITULAR">DOCENTE TITULAR</option>
                                            <option value="DOCENTE ORDINARIO">DOCENTE ORDINARIO</option>
                                            <option value="DOCENTE ASISTENTE">DOCENTE ASISTENTE</option>
                                            <option value="DOCENTE TITULAR ASISTENTE">DOCENTE TITULAR ASISTENTE</option>
                                            <option value="DOCENTE ADJUNTO">DOCENTE ADJUNTO</option>
                                            <option value="DOCENTE CATEDRÁTICO">DOCENTE CATEDRÁTICO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de emisión:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" value="<?php echo e($titularidad->dt_fecha); ?>" name="fecha"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Número de resolución :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="<?php echo e($titularidad->dt_numero_resolucion); ?>" name="numero" id="numero" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Gestión:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="gestion" id="gestion">
                                            <option value="<?php echo e($titularidad->dt_gestion); ?>"><?php echo e($titularidad->dt_gestion); ?></option>
                                            <?php
                                                $gestion=date('Y');
                                                for ($gestion1=date('Y');$gestion>1960;$gestion--){
                                                    echo "<option value='".$gestion."'>".$gestion."</option>";
                                                }
                                            ?>

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de resolución:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" value="<?php echo e($titularidad->dt_fecha_resolucion); ?>"name="fecha_resolucion" id="fecha_resolucion"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Verificado :</th>
                                    <td class="border-bottom border-dark">
                                        <?php if($titularidad->dt_verificado=='t'): ?>
                                            <input type="checkbox" name="verificado" checked/>
                                        <?php else: ?>
                                            <input type="checkbox" name="verificado"/>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Universidad :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="<?php echo e($titularidad->dt_universidad); ?>" name="universidad"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Detalle :</th>
                                    <td class="border-bottom border-dark">
                                        <textarea type="text" class="form-control form-control-sm border-0" rows="4" name="detalle"><?php echo e($titularidad->dt_detalle); ?></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Observación :</th>
                                    <td class="border-bottom border-dark">
                                        <textarea type="text" class="form-control form-control-sm border-0" rows="4" name="observacion"><?php echo e($titularidad->dt_obs); ?></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="ct" value="<?php echo e($titularidad->cod_dt); ?>">
                    <input type="hidden" name="cf" value="<?php echo e($cod_fun); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>
<?php /**PATH C:\uad9\resources\views/funcionario/documento/fe_documento_titularidad.blade.php ENDPATH**/ ?>