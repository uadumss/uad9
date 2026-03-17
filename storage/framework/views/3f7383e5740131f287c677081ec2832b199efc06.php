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
        <div class="justify-content-center">
            <div class="card shadow">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para nuevo título</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <div class="row">
                        <div class="col-md-6">
                            <span class="text-primary font-weight-bold float-right">DATOS DEL TITULO</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Nº título:</th>
                                    <td class="border-bottom border-dark">

                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}"
                                                   required name="nro" value="<?php echo e($titulo[0]->tit_nro_titulo); ?>" id="e_nro"/>
                                            <?php if($tipo=='re'): ?>
                                                <span class="text-danger font-weight-bold pt-1" style="font-size: 0.8em">Reconocimiento</span>&nbsp;&nbsp;

                                                <?php if($titulo[0]->tit_reconocimiento=='t'): ?>
                                                    <input type="checkbox" name="reconocimiento" id="reconocimiento" class="" checked/>
                                                <?php else: ?>
                                                    <input type="checkbox" name="reconocimiento" id="reconocimiento"/>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Fecha:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" required
                                               name="fecha" value="<?php echo e($titulo[0]->tit_fecha_emision); ?>" id="e_fec"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Grado :</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="grado" >
                                            <option value="<?php echo e($titulo[0]->tit_grado); ?>"><?php echo e($titulo[0]->tit_grado); ?></option>
                                            <?php $__currentLoopData = $grado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($g); ?>"><?php echo e($g); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                                <tr>
                                    <th class="text-right font-italic">Carrera: </th>
                                    <td class="border-bottom border-dark">
                                        <div class="row">
                                            <div id="fila_e_car" class="col-md-11">
                                                <select class="custom-select custom-select-sm" name="car" id="e_car">
                                                    <option value="<?php echo e($titulo[0]->cod_car); ?>"><?php echo e($titulo[0]->car_nombre); ?></option>
                                                    <?php $__currentLoopData = $carrera; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($c->cod_car); ?>"><?php echo e($c->fac_abreviacion." - ".$c->car_nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                                                <a href='#' class="btn btn-sm btn-info btn-circle btn-info ml-1" data-toggle="modal" data-target="#verObs"
                                                   onclick="verDatos('<?php echo e(url('añadir carrera tomo/'.$tomo["cod_tom"].'/fila_e_car')); ?>','p_observacion','')" title="Añadir Carrera">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php }?>
                                <tr>
                                    <th class="text-right font-italic">Nº folio:</th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}"
                                                   name="folio" value="<?php echo e($titulo[0]->tit_nro_folio); ?>" id="e_fol"/>
                                            <span class="text-primary font-weight-bold" style="font-size: 0.9em">Fecha Folio. </span>&nbsp;&nbsp;
                                            <input type="date" class="form-control form-control-sm border-0" name="fecha_folio"
                                                   value="<?php echo e($titulo[0]->tit_fecha_folio); ?>" id="e_fecha_folio"/>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Título en PDF:</th>
                                    <td class="border-bottom border-dark input-group">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" id="pdf" />
                                        <?php if($titulo[0]->tit_pdf!=''): ?>
                                            <img src="<?php echo e(url('img/icon/tit.gif')); ?>" width="30" height="30">
                                            <input type="hidden" name="pdf_val" id="pdf_val" value="1">
                                        <?php else: ?>
                                            <input type="hidden" name="pdf_val" id="pdf_val" value="0">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Antecedentes en PDF:</th>
                                    <td class="border-bottom border-dark input-group">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf_ant" id="pdf_ant"/>
                                        <?php if($titulo[0]->tit_antecedentes!=''): ?>
                                            <img src="<?php echo e(url('img/icon/antecedente.gif')); ?>" width="30" height="30">
                                            <input type="hidden" name="pdf_val_ant" id="pdf_val_ant" value="1">
                                        <?php else: ?>
                                            <input type="hidden" name="pdf_val_ant" id="pdf_val_ant" value="0">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa' || $tipo=='tpos' || $tipo=='di' || $tipo=='re' || $tipo=='db'){?>
                                <tr>
                                    <th class="text-right font-italic">Título:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea rows="2" class="form-control-sm form-control border-0" name="titulo" id="e_tit"><?php echo e($titulo[0]->tit_titulo); ?></textarea>
                                    </td>
                                </tr>
                                <?php }?>
                                <?php if($tipo=='su'){?>
                                <tr>
                                    <th class="text-right font-italic">Referencia A:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea rows="2" class="form-control-sm form-control border-0" name="ref"><?php echo e($titulo[0]->tit_ref); ?></textarea>
                                    </td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <span class="text-primary font-weight-bold float-right">DATOS PERSONALES</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Nº CI:</th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm border-0" name="ci" id="e_ci"
                                                   value="<?php echo e($titulo[0]->per_ci); ?>"/>
                                            <span class="text-danger font-weight-bold" style="font-size: 0.9em">Exp. </span>&nbsp;&nbsp;
                                            <select name="expedido" class="custom-select-sm custom-select col-md-4" id="expedido">
                                                <option value="<?php echo e($titulo[0]->per_ci_exp); ?>"><?php echo e($titulo[0]->per_ci_exp); ?></option>
                                                <option value=""></option>
                                                <option value="CB">CB</option>
                                                <option value="LP">LP</option>
                                                <option value="SC">SC</option>
                                                <option value="PT">PT</option>
                                                <option value="OR">OR</option>
                                                <option value="TA">TA</option>
                                                <option value="BE">BE</option>
                                                <option value="PA">PA</option>
                                                <option value="CH">CH</option>
                                            </select>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nº passaporte:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="pass" id="e_pas"
                                            value="<?php echo e($titulo[0]->per_pasaporte); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="apellido" id="e_ape"
                                        value="<?php echo e($titulo[0]->per_apellido); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="nombre" id="e_nom"
                                            value="<?php echo e($titulo[0]->per_nombre); ?>"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Sexo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="sexo" id="e_sex">
                                            <?php if($titulo[0]->per_sexo=='M'){?>
                                                <option value="M">MASCULINO</option>
                                                <option value="F">FEMENINO</option>
                                            <?php }else{?>
                                                <option value="F">FEMENINO</option>
                                                <option value="M">MASCULINO</option>
                                            <?php }?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nacionalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="nac" id="e_nac">
                                            <option value="<?php echo e($titulo[0]->cod_nac); ?>"><?php echo e($titulo[0]->nac_nombre); ?></option>
                                            <?php $__currentLoopData = $nacionalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($n['cod_nac']); ?>"><?php echo e($n['nac_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa' || $tipo=='tpos' || $tipo=='di'){?>
                                <tr>
                                    <th class="text-right font-italic">Modalidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="mod" id="e_mod" onchange="e_habilitarMod($('#e_mod option:selected').text())">
                                            <option value="<?php echo e($titulo[0]->cod_mod); ?>"><?php echo e($titulo[0]->mod_nombre); ?></option>
                                            <?php $__currentLoopData = $modalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($m['cod_mod']); ?>"><?php echo e($m['mod_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($titulo[0]->tit_otra_modalidad==''): ?>
                                            <div id="e_otraMod" style="display: none">
                                        <?php else: ?>
                                            <div id="e_otraMod">
                                        <?php endif; ?>
                                                <input type="text" class="form-control-sm form-control border border-primary text-danger" name="otra_modalidad"
                                                       placeholder="Ingrese la modalidad" value="<?php echo e($titulo[0]->tit_otra_modalidad); ?>">
                                            </div>
                                            <script>
                                                function e_habilitarMod(valor){
                                                    if(valor=='Otro...'){
                                                        $('#e_otraMod input').prop('disabled', false);
                                                        $('#e_otraMod').show(250);
                                                    }else{
                                                        $('#e_otraMod input').prop('disabled', true);
                                                        $('#e_otraMod').hide(250);
                                                    }
                                                }
                                            </script>

                                    </td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                    </div>

                    <?php if($tipo=='re' || ($tipo=='tp' && $titulo[0]->tit_revalida=='t')){?>
                    <hr class="sidebar-divider"/>
                        <input type="hidden" name="revalida" id="revalida" value="t">
                        <div class="col-md-5" id="div_revalida">
                            <span class="text-primary font-weight-bold float-right">DATOS DE REVÁLIDA</span>
                            <br/>
                            <table>
                                <tr>
                                    <th class="text-right font-italic">País de origen:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="pais_origen" id="e_pao">
                                            <option value="<?php echo e($revalida[0]->cod_nac); ?>"><?php echo e($revalida[0]->nac_nombre); ?></option>
                                            <?php $__currentLoopData = $nacionalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($n['cod_nac']); ?>"><?php echo e($n['nac_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Universidad:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0 col-md-12" value="<?php echo e($revalida[0]->re_universidad); ?>" name="universidad" id="e_uni"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Otorgado el:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0 col-md-12" value="<?php echo e($revalida[0]->re_fecha); ?>" name="fecha_revalida" id="e_fre"/>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        <input type="hidden" name="tipo" id="tipo" value="<?php echo e($tipo); ?>">
        <input type="hidden" name="ctit" value="<?php echo e($titulo[0]->cod_tit); ?>">
        <input type="hidden" name="ct" value="<?php echo e($tomo['cod_tom']); ?>"/>
        <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
            <input type="hidden" name="fac" id="e_fac" value="<?php echo e($titulo[0]->fac_nombre); ?>"/>
        <?php }?>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/diplomas/titulo/editar_titulo.blade.php ENDPATH**/ ?>