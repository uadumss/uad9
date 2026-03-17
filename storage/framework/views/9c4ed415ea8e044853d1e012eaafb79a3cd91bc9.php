<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> TRAMITE CONVOCATORIA</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Formulario Convocatoria -->
    <div class="card shadow">
        <div class="modal-body">
            <?php if(Session::has('exitoModal')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('exitoModal'); ?>

                </div>
            <?php endif; ?>
            <?php if(Session::has('errorModal')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('errorModal'); ?>

                </div>
            <?php endif; ?>

            <div class="d-flex justify-content-center">
                <div class="card-body" style="font-size: 14px;">

                        <div class="text-center">
                            <h4 class="text-primary font-weight-bold">Convocatoria</h4>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="shadow-sm p-2 col-md-5 float-md-right">
                                    <h1 class="text-danger pr-3 text-center"><?php echo e($tramite_noatentado->dtra_numero_tramite); ?></h1>
                                    <span class="font-italic text-dark text-center"><?php if($tramite_noatentado->dtra_fecha_registro!=''){echo date('d/m/Y',strtotime($tramite_noatentado->dtra_fecha_registro));} ?></span>
                                </div>
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                    <table class="col-md-12 text-dark table table-sm">
                                        <tbody>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <span class="text-secondary font-italic font-weight-bold"><?php echo e($convocatoria->con_nombre); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <span class="font-weight-bold"><?php echo e($tramite_noatentado->tre_nombre); ?></span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <?php if($tramite_noatentado->dtra_interno=='t'): ?>
                                                    <input type="radio" name="tipo_tramite" checked value="t">  INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <?php else: ?>
                                                    <input type="radio" name="tipo_tramite" checked value="f">  EXTERNO
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic text-primary">Nro. Control:</th>
                                            <td class="border-bottom  input-group">
                                                <div class="input-group">
                                                    <?php echo e($tramite_noatentado->dtra_control); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php if($tramite_noatentado->dtra_valorado_reintegro!=''): ?>
                                                        <span class="text-primary font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                                        <?php echo e($tramite_noatentado->dtra_valorado_reintegro); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                <div class="" id="apoderadoEntrega">
                                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                                    <br/>
                                    <br/>
                                    <?php if($apoderado): ?>
                                        <table class="table table-sm">
                                            <tr>
                                                <th class="text-right font-italic text-dark">CI : </th>
                                                <td class="border-bottom border-dark">
                                                    <?php if($apoderado): ?>
                                                        <?php echo e($apoderado['apo_ci']); ?>

                                                    <?php else: ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic text-dark font-italic">Nombre apoderado : </th>
                                                <td class="border-bottom border-dark">
                                                    <?php if($apoderado): ?>
                                                        <?php echo e($apoderado['apo_apellido']." ".$apoderado['apo_nombre']); ?>

                                                    <?php else: ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic text-dark">Tipo de apoderado : </th>
                                                <td class="border-bottom border-dark">
                                                    <span class="text-primary font-weight-bold">
                                                        <?php if($tramite_noatentado->dtra_tipo_apoderado=='d'): ?>
                                                            Declaración jurada
                                                        <?php else: ?>
                                                            <?php if($tramite_noatentado->dtra_tipo_apoderado=='p'): ?>
                                                                Poder notariado
                                                            <?php else: ?>
                                                                <?php if($tramite_noatentado->dtra_tipo_apoderado=='c'): ?>
                                                                    Carta de representación
                                                                <?php else: ?>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                    <?php endif; ?>
                                    <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderadoEntrega').show(500); $('#apoderadoEntrega').hide(500);"> Editar datos del Apoderado</button>
                                </div>
                                <div id="editarApoderadoEntrega" class="border rounded shadow" style="display: none;">
                                    <div class="p-3">
                                        <a onclick="$('#editarApoderadoEntrega').hide(500);$('#apoderadoEntrega').show(500); " id="ocultar" style="float:right">
                                            <i class="fas fa-minus-circle text-danger"></i></a>
                                        <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                        <br><br>
                                        <form id="form_apoderado_noa">
                                            <?php echo csrf_field(); ?>
                                            <?php
                                                $nombre='';    $apellido='';  $ci="";
                                                if($apoderado){   $ci=$apoderado->apo_ci;       $apellido=$apoderado->apo_apellido;     $nombre=$apoderado->apo_nombre;  }
                                            ?>

                                            <table class="table-hover col-md-12">
                                                <tr>
                                                    <th class="text-right font-italic">CI : </th>
                                                    <td class="border-bottom border-dark">
                                                        <input class="form-control form-control-sm border-0" placeholder=""
                                                               name="ci" value="<?php echo e($ci); ?>" onchange="cargarDatosApoderado(this.value)"/></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Apellidos : </th>
                                                    <td class="border-bottom border-dark">
                                                        <input class="form-control form-control-sm border-0" placeholder=""
                                                               required name="apellido" id="apellido_apoderado" value="<?php echo e($apellido); ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Nombres : </th>
                                                    <td class="border-bottom border-dark">
                                                        <input class="form-control form-control-sm border-0" placeholder=""
                                                               required name="nombre" id="nombre_apoderado" value="<?php echo e($nombre); ?>" /></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                                    <td class="border-bottom border-dark">
                                                        <?php if($tramite_noatentado->dtra_tipo_apoderado=='d'): ?>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado<br/>
                                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c"> Carta de representación
                                                        <?php else: ?>
                                                            <?php if($tramite_noatentado->dtra_tipo_apoderado=='p'): ?>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado<br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c"> Carta de representación
                                                            <?php else: ?>
                                                                <?php if($tramite_noatentado->dtra_tipo_apoderado=='c'): ?>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado<br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c" checked> Carta de representación
                                                                <?php else: ?>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado<br/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c"> Carta de representación
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                </tr>
                                            </table>
                                            <br/>
                                            <input type="hidden" name="cdtra" value="<?php echo e($tramite_noatentado->cod_dtra); ?>">
                                            <input type="hidden" name="pan" value="ent">
                                        </form>
                                        <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_noa','<?php echo e(url("guardar apoderado noatentado")); ?>','panel_traleg');" >Guardar</a><br/>
                                        <br/>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7 shadow border rounded p-2" >
                                <div>
                                    <div>
                                        <br/><br/>
                                        <span class="font-weight-bold text-primary font-italic">* Lista de candidatos</span>
                                        <div class="table-responsive overflow-auto" style="height: 200px">
                                            <table class="table table-sm table-hover" id="lista" width="100%" cellspacing="0" style="font-size: 12px">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nombre</th>
                                                    <th>CI</th>
                                                    <th>COD SIS</th>
                                                    <th>Cargo</th>
                                                    <th>Unidad</th>
                                                </tr>
                                                <?php $i=1;?>
                                                <?php $__currentLoopData = $noatentados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php $sancionado=App\Http\Controllers\Noatentado\SancionadosController::verificarSancionado($n->id_per);?>
                                                    <?php if($sancionado): ?>
                                                        <tr class="alert-danger">
                                                    <?php else: ?>
                                                        <tr>
                                                            <?php endif; ?>
                                                            <td><?php echo e($i++); ?></td>
                                                            <td><?php echo e($n->per_nombre." ".$n->per_apellido); ?></td>
                                                            <td><?php echo e($n->per_ci); ?></td>
                                                            <td><?php echo e($n->per_cod_sis); ?></td>
                                                            <td><?php echo e($n->carg_nombre); ?></td>
                                                            <td><?php echo e($n->noa_unidad); ?></td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <hr class="sidebar-divider"/>
                                <div>
                                    <br/>
                                    <span class="text-primary font-italic font-weight-bold" >*Datos del trámite</span>
                                    <br/>
                                    <table class="col-md-12 table table-sm table-hover border">
                                        <tr class="bg-gradient-secondary text-white p-2">
                                            <th>Nº</th>
                                            <th>Nombre</th>
                                            <th>Entregar</th>
                                        </tr>
                                        <?php $i=1;?>

                                        <tr style="font-size: 12px" class="alert-light">
                                            <td><?php echo e($i); ?></td>
                                            <td class="text-left"><?php echo e($tramite_noatentado->tre_nombre); ?> <br/>
                                                <span style="font-size: 0.85em">
                                                <?php if($tramite_noatentado->dtra_interno=='t'): ?> <span class="font-weight-bold text-dark font-italic">Trámite : </span><span class="text-danger font-weight-bold">Interno</span> | <?php endif; ?>
                                                 <span class="font-weight-bold text-dark font-italic">Valorado: </span> <span> <?php echo e($tramite_noatentado->dtra_control); ?></span> |
                                                 <?php if($tramite_noatentado->dtra_entregado=='t' || $tramite_noatentado->dtra_entregado=='a' ): ?><span class="font-weight-bold text-dark font-italic">Fecha entrega: </span> <span class="text-primary font-weight-bold"> <?php echo e(date('d/m/Y H:i:s', strtotime($tramite_noatentado->dtra_fecha_recojo))); ?></span> |<?php endif; ?>
                                                </span>
                                            </td>

                                            <td class="text-right">
                                                <?php if($tramite_noatentado->dtra_entregado!='a' && $tramite_noatentado->dtra_entregado!='t'): ?>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('entregar tramite - noa')): ?>
                                                        <?php if(sizeof($noatentados)>1 || $tramite_noatentado->cod_apo!=''): ?>
                                                            <a href="#" class="btn btn-primary btn-sm" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("datos tramite noa/".$tramite_noatentado->cod_dtra)); ?>','panel_docleg')"
                                                               title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar</a>
                                                        <?php else: ?>
                                                            <form id="form_g_entrega">
                                                                <?php echo csrf_field(); ?>
                                                                <input type="hidden" name="cdtra" value="<?php echo e($tramite_noatentado->cod_dtra); ?>">
                                                                <input type="hidden" name="tipo" value="<?php echo e($noatentados[0]->id_per); ?>">
                                                            </form>

                                                                <a href="#" class="btn btn-primary btn-sm" onclick="enviar('form_g_entrega','<?php echo e(url("g_entrega_noa")); ?>','panel_traleg');
                                                                        cargarDatos('<?php echo e(url('actualizar lista entrega noatentado')); ?>','panel_tabla_no-atentado');$('#traleg').modal('hide');"
                                                                title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar</a>

                                                        <?php endif; ?>

                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                    <?php if($tramite_noatentado->dtra_entregado=='a'): ?> <span class="font-weight-bold text-success font-italic">Apoderado </span> <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/entrega/fe_entrega_noa.blade.php ENDPATH**/ ?>