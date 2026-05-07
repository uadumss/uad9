
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file"></i>&nbsp;&nbsp;RESOLUCION - <?php echo e($tomo->tom_gestion); ?></h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
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
    <?php if($cod_res!=0): ?>
        <div class="alert alert-danger alert-dismissible" id="panel_error_archivo" style="display: none">
            <div id="error_archivo">
                * Ocurrio un error desconocido, revise todos los datos y asegurece que :
                <ul>
                    <li>El título en PDF deben ser menor a 2048 KB</li>
                    <li>Los antecedentes en PDF deben ser menor a 15360 KB</li>
                </ul>
            </div>
        </div>
        <form method="POST" id="form_editar" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <div class="justify-content-center">
                <div class="card shadow">
                    <div class="card-body" style="font-size: 0.9em">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">Formulario para editar resolución</h6>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="row">
                            <div class="col-md-5">
                                <span class="font-italic text-primary font-weight-bold float-right">* Datos de la resolución</span>
                                <br/><br/>

                                    <table class="col-md-12">
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Nº Resolución :</th>
                                            <td class="border-bottom border-dark">
                                                <input type="text" class="form-control form-control-sm border-0" pattern="[0-9][A-Z]{1,5}" required name="numero" id="numero" value="<?php echo e($resolucion['res_numero']); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Fecha :</th>
                                            <td class="border-bottom border-dark">
                                                <input type="date" class="form-control form-control-sm border-0" required name="fecha" id="fecha" value="<?php echo e($resolucion['res_fecha']); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Tipo de Resolución :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                    <option value="<?php echo e($resolucion['res_tipo']); ?>"><?php echo e(strtoupper($resolucion['res_tipo'])); ?></option>
                                                    <option value="rcu">RCU </option>
                                                    <option value="rr">RR</option>
                                                    <option value="rvr">RVR </option>
                                                    <option value="rs">RS </option>
                                                    <option value="rc">RC </option>
                                                    <option value="rcf">RCF </option>
                                                    <option value="rcc">RCC </option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-right font-italic border-bottom">Objeto:</th>
                                            <td class="border-bottom border-dark">
                                                <input type="text" class="form-control form-control-sm border-0" required name="objeto" id="objeto" value="<?php echo e($resolucion['res_objeto']); ?>" />
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-right font-italic border-bottom">Plan de archivo:</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="temas" onchange="cargarDatos('<?php echo e(url('codigo tema resolucion/'.$cod_res)); ?>/'+this.value,'panel_temas');$('#DatosTemas').modal('show');">
                                                    <option></option>
                                                    <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($p->cod_carch); ?>"><?php echo e($p->plan_numero."/".$p->carch_numero." - ".$p->carch_titulo); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <div id="archivados">
                                                    <span class="font-weight-bold">
                                                        <?php $codificacion="";?>
                                                        <?php $__currentLoopData = $archivado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <a class="btn btn-light btn-circle btn-sm text-danger"onclick="cargarPlan('eliminar plan resolucion/<?php echo e($a->cod_arc); ?>','archivados')"><i class="fas fa-trash-alt"></i></a>
                                                            <?php echo e($a->plan_numero.'/'.$a->carch_numero.'-'.$a->det_nombre); ?><br/>
                                                        <?php //$codificacion.=$a->plan_numero.'/'.$a->carch_numero."<br/>"?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Tema:</th>
                                            <td class="border-bottom border-dark">
                                                <input type="text" class="form-control form-control-sm border-0" required name="tema" id="tema" value="<?php echo e($resolucion['res_tema']); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Firma primera autoridad:</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="firma1">
                                                    <?php
                                                    $nombre="";
                                                    $codigo="";
                                                    $cargo='';
                                                    $ban=false;
                                                    if(isset($fir)){
                                                        foreach($autoridad as $a){
                                                            if($a->cod_aut==$fir->cod_aut){
                                                                $nombre=$a->aut_nombre;
                                                                $cargo=$a->aut_cargo;
                                                                $codigo=$a->cod_aut;
                                                                $ban=true;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <?php if($ban): ?>
                                                        <option value="<?php echo e($codigo); ?>"><?php echo e($nombre); ?>-><?php echo e($cargo); ?></option>
                                                    <?php else: ?>
                                                        <option><?php echo e($nombre); ?></option>
                                                    <?php endif; ?>

                                                    <?php $__currentLoopData = $autoridad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($a->cod_aut); ?>"><?php echo e($a->aut_nombre); ?>->
                                                            <span class="font-weight-bold"><?php echo e($a->aut_cargo); ?></span></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Firma segunda autoridad:</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" name="firma2">
                                                    <?php
                                                    $nombre="";
                                                    $codigo="";
                                                    $cargo='';
                                                    $ban=false;
                                                    if(isset($fir)){
                                                        foreach($autoridad as $a){
                                                            if($a->cod_aut==$fir->cod_aut2){
                                                                $nombre=$a->aut_nombre;
                                                                $cargo=$a->aut_cargo;
                                                                $codigo=$a->cod_aut;
                                                                $ban=true;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                    <?php if($ban): ?>
                                                        <option value="<?php echo e($codigo); ?>"><?php echo e($nombre); ?>-><?php echo e($cargo); ?></option>
                                                    <?php endif; ?>
                                                        <option></option>
                                                    <?php $__currentLoopData = $autoridad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($a->cod_aut); ?>"><?php echo e($a->aut_nombre); ?>->
                                                            <span class="font-weight-bold"><?php echo e($a->aut_cargo); ?></span></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Descripción:</th>
                                            <td class="border-bottom border-dark">
                                                <textarea rows="3" class="form-control-sm form-control border-0" name="desc" id="desc" ><?php echo e($resolucion['res_desc']); ?></textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Observación:</th>
                                            <td class="border-bottom border-dark">
                                                <textarea rows="3" class="form-control-sm form-control border-0" name="obs"><?php echo e($resolucion['res_obs']); ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                            <div class="col-md-7">
                                <span class="font-italic text-primary font-weight-bold float-right">* Contenido de la resolución</span>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right font-italic ">Vistos :</th>
                                        <td class="border-bottom border-dark col-md-9">
                                            <textarea  rows="6" class="form-control-sm form-control border-0" name="visto" id="visto"><?php echo e($resolucion['res_vistos']); ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Considerando :</th>
                                        <td class="border-bottom border-dark col-md-9">
                                            <textarea rows="6" class="form-control-sm form-control border-0" name="considerando" id="considerando"><?php echo e($resolucion['res_considerando']); ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Resuelve :</th>
                                        <td class="border-bottom border-dark col-md-9">
                                            <textarea  rows="6" class="form-control-sm form-control border-0" name="resuelve" id="resuelve"><?php echo e($resolucion['res_resuelve']); ?></textarea>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Resolución en PDF:</th>
                                        <td class="">
                                            <div class="input-group">
                                                <input type="file" class="form-control form-control-sm" accept=".pdf" name="pdf" />
                                                <?php if($resolucion['res_pdf']!=''): ?>
                                                    <img src="<?php echo e(url('img/icon/titulo.gif')); ?>" width="30" height="30">
                                                    <input type="hidden" name="pdf_val" id="pdf_val" value="1">
                                                <?php else: ?>
                                                    <input type="hidden" name="pdf_val" id="pdf_val" value="0">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Antecedentes en PDF:</th>
                                        <td class="">
                                            <div class="input-group">
                                                <input type="file" class="form-control form-control-sm" accept=".pdf" name="pdf_ant" />
                                                <?php if($resolucion['res_ant']!=''): ?>
                                                    <img src="<?php echo e(url('img/icon/antecedente.gif')); ?>" width="30" height="30">
                                                    <input type="hidden" name="pdf_val_ant" id="pdf_val_ant" value="1">
                                                <?php else: ?>
                                                    <input type="hidden" name="pdf_val_ant" id="pdf_val_ant" value="0">
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" name="cr" value="<?php echo e($resolucion['cod_res']); ?>">
                                <input type="hidden" name="ct" value="<?php echo e($resolucion['cod_tom']); ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
    <?php else: ?>
        <form action="<?php echo e(url('g_resolucion')); ?>" method="POST" enctype="multipart/form-data" id="form_nueva_resolucion">
        <?php echo csrf_field(); ?>
        <div class="justify-content-center">
            <div class="card shadow">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para nueva resolución</h6>
                    </div>
                    <hr class="sidebar-divider"/>

                    <div class="row">
                        <div class="col-md-5">
                            <span class="font-italic text-primary font-weight-bold float-right">* Datos de la resolución</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic border-bottom">Nº Resolución :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="numero" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Fecha :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" required name="fecha" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Tipo de Resolución :</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm border-0 " name="tipo">
                                            <option value="rcu">RCU </option>
                                            <option value="rr">RR</option>
                                            <option value="rvr">RVR </option>
                                            <option value="rs">RS </option>
                                            <option value="rc">RC </option>
                                            <option value="rcf">RCF </option>
                                            <option value="rcc">RCC </option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Plan de archivo:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm border-0 " name="temas" onchange="cargarDatos('<?php echo e(url('codigo tema resolucion/0/')); ?>/'+this.value,'panel_temas');$('#DatosTemas').modal('show');">
                                            <option></option>
                                            <?php $__currentLoopData = $plan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($p->cod_carch); ?>"><?php echo e($p->plan_numero."/".$p->carch_numero." - ".$p->carch_titulo); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Código:</th>
                                    <td class="border-bottom border-dark">
                                        <div id="archivados">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Tema:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="tema"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Objeto:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="objeto" />
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-right font-italic border-bottom">Firma primera autoridad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm border-0 " name="firma1">
                                            <option value="" disabled selected hidden></option>
                                            <option></option>
                                            <?php $__currentLoopData = $autoridad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($a->cod_aut); ?>"><?php echo e($a->aut_nombre); ?>->
                                                    <span class="font-weight-bold"><?php echo e($a->aut_cargo); ?></span></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic border-bottom">Firma segunda autoridad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select custom-select-sm border-0 " name="firma2">
                                            <option value="" disabled selected hidden></option>
                                            <option></option>
                                            <?php $__currentLoopData = $autoridad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $a): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($a->cod_aut); ?>"><?php echo e($a->aut_nombre); ?>->
                                                    <span class="font-weight-bold"><?php echo e($a->aut_cargo); ?></span></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Descripción:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea rows="5" class="form-control-sm form-control border-0" name="desc"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Observación:</th>
                                    <td class="border-bottom border-dark">
                                        <textarea rows="5" class="form-control-sm form-control border-0" name="obs"></textarea>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <span class="font-italic text-primary font-weight-bold float-right">* Contenido de la resolución</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Vistos :</th>
                                    <td class="border-bottom border-dark col-md-9">
                                        <textarea  rows="6" class="form-control-sm form-control border-0" name="visto"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Considerando :</th>
                                    <td class="border-bottom border-dark col-md-9">
                                        <textarea rows="6" class="form-control-sm form-control border-0" name="considerando"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Resuelve :</th>
                                    <td class="border-bottom border-dark col-md-9">
                                        <textarea  rows="6" class="form-control-sm form-control border-0" name="resuelve"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Resolución en PDF:</th>
                                    <td class="">
                                        <input type="file" class="form-control form-control-sm" accept=".pdf" name="pdf" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Antecedentes en PDF:</th>
                                    <td class="">
                                        <input type="file" class="form-control form-control-sm" accept=".pdf" name="pdf_ant" />
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="ct" value="<?php echo e($tomo->cod_tom); ?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    <?php endif; ?>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        <?php if($cod_res!=0): ?>
            <button class="btn btn-primary btn-sm" type="button" id="g_tit" onclick="enviar_res()">Guardar</button>
        <?php else: ?>
            <button class="btn btn-primary btn-sm" type="button" onclick="$('#form_nueva_resolucion').submit()">Guardar</button>
        <?php endif; ?>
    </div>
</div>
<div class="modal fade" id="DatosTemas" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" id="panel_temas">

    </div>
</div>

<?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/resolucion/fe_resolucion.blade.php ENDPATH**/ ?>