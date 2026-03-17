    <?php $fecha=date('Y-m-d',strtotime($tramite->tra_fecha_solicitud))?>
    <div class="modal-content border-bottom-primary" xmlns="http://www.w3.org/1999/html">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Legalización </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <?php if(Session::has('exito')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold"><?php echo session('exito'); ?></span>
                </div>
            <?php endif; ?>
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar legalización</h6>
            </div>
            <?php echo e($tipos_array); ?>

            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-4">
                    <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Datos personales</span>
                        <div class="shadow-sm p-2 col-md-5 float-md-right">
                            <h1 class="text-danger pr-3 text-center"><?php echo e($tramite->tra_numero); ?></h1>
                            <span class="font-italic text-dark text-center"><?php if($tramite->tra_fecha_solicitud!=''){echo date('d/m/Y',strtotime($tramite->tra_fecha_solicitud));} ?></span>
                        </div>
                    <?php if($tramite->per_ci==''): ?>
                    <form id="form_traleg">
                        <?php echo csrf_field(); ?>
                            <table class="table-hover col-md-12 text-dark">
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">

                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci" value="<?php echo e($tramite->per_ci); ?>" onchange="cargarDatosPersonales(this.value)" /></td>

                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Passaporte : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="pasaporte" value="<?php echo e($tramite->per_pasaporte); ?>" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido" id="apellido" value="<?php echo e($tramite->per_apellido); ?>" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre" id="nombre" value="<?php echo e($tramite->per_nombre); ?>" /></td>
                                </tr>
                            </table>
                            <br/>
                            <input type="hidden" name="ctra" value="<?php echo e($tramite->cod_tra); ?>">
                            <input type="hidden" name="ip" value="<?php echo e($tramite->id_per); ?>">

                        </form>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar datos traleg - srv')): ?>
                                <button type="submit" class="btn btn-primary btn-sm float-md-right" onclick="guardarDatos('<?php echo e(url("g_traleg")); ?>','panel_traleg','form_traleg')"> Guardar </button>
                            <?php endif; ?>
                        <?php else: ?>
                        <table class="col-md-12 text-dark table table-sm">
                            <tr>
                                <th class="text-right font-italic">CI : </th>
                                <td class="border-bottom border-dark"><?php echo e($tramite->per_ci); ?></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Passaporte : </th>
                                <td class="border-bottom border-dark"><?php echo e($tramite->per_pasaporte); ?></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark"><?php echo e($tramite->per_nombre." ".$tramite->per_apellido); ?></td>
                            </tr>

                        </table>
                        <?php endif; ?>
                    <div>
                        <ul class="list-group-item-danger rounded">
                            <?php if(sizeof($ptaang)>0): ?>
                                <?php $__currentLoopData = $ptaang; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li class="text-darkr">Ya tiene
                                        <?php echo \App\Models\Funciones::tipo_ptaang($p->dtra_ptaang)." Nº " ?>
                                        <span class="font-weight-bold"><?php echo e($p->dtra_numero."/".$p->dtra_gestion); ?> </span> por PTAANG</li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <br/>
                    <div>
                        <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                        <div class="" id="apoderado">
                            <table class=" table table-sm">
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
                                        <?php if($tramite->tra_tipo_apoderado=='d'): ?>
                                            Declaración jurada
                                        <?php else: ?>
                                            <?php if($tramite->tra_tipo_apoderado=='p'): ?>
                                                Poder notariado
                                            <?php else: ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>

                            </table>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar apoderado traleg - srv')): ?>
                                <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderado').show(500); $('#apoderado').hide(500);"> Editar datos</button>
                            <?php endif; ?>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar apoderado traleg - srv')): ?>
                        <div id="editarApoderado" class="border rounded shadow" style="display: none;">
                            <div class="p-3">
                                <a onclick="$('#editarApoderado').hide(500);$('#apoderado').show(500); " id="ocultar" style="float:right">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                <form id="form_apoderado_edi">
                                <br/><br/>
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
                                                <?php if($tramite->tra_tipo_apoderado=='d'): ?>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                <?php else: ?>
                                                    <?php if($tramite->tra_tipo_apoderado=='p'): ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                                    <?php else: ?>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                    <?php endif; ?>
                                            <?php endif; ?>

                                        </tr>
                                    </table>
                                    <br/>
                                    <input type="hidden" name="ctra" value="<?php echo e($tramite->cod_tra); ?>">
                                    <?php echo csrf_field(); ?>
                                </form>

                                    <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_edi','<?php echo e(url("guardar apoderado")); ?>','panel_traleg');" >Guardar</a><br/>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                <div class="col-md-8 pl-3">
                    <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Documentos del trámite</span>
                    <div>
                        <?php if(Session::has('error')): ?>
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="font-weight-bold"><?php echo session('error'); ?></span>
                            </div>
                        <?php endif; ?>
                        <table class="col-md-12 table table-sm table-hover table-dark">
                            <tr class="bg-gradient-secondary text-white p-2">
                                <th>Nº</th>
                                <th>Sitra</th>
                                <!--<th>Estado</th>-->
                                <th>Nombre</th>

                                <th>Número trámite</th>
                                <?php if($tramite->tra_tipo_tramite=='B'): ?>
                                    <th>Documentos</th>
                                <?php endif; ?>
                                <?php if($tramite->tra_tipo_tramite=='F'): ?>
                                    <th>Documentos</th>
                                <?php else: ?>
                                    <th>Nº Título</th>
                                    <th colspan="4">Opciones</th>
                                <?php endif; ?>
                            </tr>
                            <?php $i=1;?>
                            <?php $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($d->dtra_falso=='t'): ?>
                                        <tr style="font-size: 10px" class="alert-danger border">
                                    <?php else: ?>
                                        <?php if($d->dtra_generado=='t'): ?>
                                        <tr style="font-size: 10px" class="alert-success border">
                                        <?php else: ?>
                                        <tr style="font-size: 10px" class="alert-light">
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <td><?php echo e($i); ?></td>
                                    <td><?php if($d->dtra_verificacion_sitra=='0'): ?>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("verificacion sitra/".$d->cod_dtra)); ?>','panel_docleg')"
                                               title="Verificado en el sitra"><i class="fas fa-check-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <?php if($d->dtra_verificacion_sitra=='2'): ?>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("verificacion sitra/".$d->cod_dtra)); ?>','panel_docleg')"
                                                   title="No existe en el sitra"><i class="fas fa-minus-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <!--<td>if($d->dtra_estado_doc==0 || $d->dtra_estado_doc==4 )
                                            <div class="border border-success font-weight-bold text-success rounded pl-2" ><?php echo \App\Http\Controllers\TramiteLegalizacionController::estado($d->dtra_estado_doc)?></div>
                                        else
                                            <div class="border border-danger font-weight-bold text-danger rounded pl-2" ><?php echo \App\Http\Controllers\TramiteLegalizacionController::estado($d->dtra_estado_doc)?></div>
                                        endif
                                    </td>-->
                                    <td class="text-left"><?php echo e($d->tre_nombre); ?> <?php if($d->dtra_interno=='t'): ?> <span class="text-danger font-weight-bold">(Int.)</span> <?php endif; ?></td>
                                    <td>

                                            <?php echo e($d->dtra_numero_tramite." / ".$d->dtra_gestion_tramite); ?>


                                    </td>

                                    <?php if($tramite->tra_tipo_tramite=='B'): ?>
                                                <td>
                                                    <?php $__currentLoopData = $confrontacion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($c->cod_dtra==$d->cod_dtra): ?>
                                                            <span class="font-weight-bold font-italic"><?php echo  $c->dcon_doc; ?> </span><br/>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                    <?php endif; ?>
                                    <?php if($tramite->tra_tipo_tramite=='F'): ?>
                                        <td>
                                            <?php $__currentLoopData = $confrontacion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="font-weight-bold font-italic"><?php echo  \App\Http\Controllers\ConfrontacionController::nombreDocumento($c->dcon_doc); ?> </span><br/>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                    <?php else: ?>
                                                <td class="text-left">
                                                    <?php if($d->dtra_numero==0): ?>
                                                        <?php echo e("-/".substr($d->dtra_gestion,-2)); ?></td>
                                                    <?php else: ?>
                                                        <?php echo e($d->dtra_numero."/".substr($d->dtra_gestion,-2)); ?></td>
                                                    <?php endif; ?>

                                                <td class="text-right">
                                                    <?php if($d->dtra_generado=='t'): ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deshacer generado glosa - srv')): ?>
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("fe_corregir_docleg/".$d->cod_dtra)); ?>','panel_docleg')"
                                                               title="Corregir tramite"><i class="fas fa-arrow-circle-left"></i> </a>
                                                        <?php endif; ?>
                                                            <?php if($tramite->tra_tipo_tramite!='B'): ?>
                                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('imprimir legalizacion docleg - srv')): ?>
                                                                    <a class="btn btn-light btn-sm btn-circle" data-target='#docleg' data-toggle="modal" onclick="cargarDatos('<?php echo e(url("configurar impresion pdf leg/".$d->cod_dtra)); ?>','panel_docleg')"
                                                                       title="Ver Glosa"><i class="text-dark fas fa-file-pdf" ></i></a>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("ver documento pdf legalizado/".$d->cod_dtra)); ?>','panel_docleg')"
                                                               title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>

                                                    <?php else: ?>
                                                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('deshacer generado glosa - srv')): ?>
                                                            <?php if($tramite->tra_tipo_tramite=='L' ||$tramite->tra_tipo_tramite=='C'): ?>
                                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm font-weight-bold"  onclick="cargarDatos('<?php echo e(url("cambiar interno docleg/".$d->cod_dtra)); ?>','panel_traleg')"
                                                                   title="Cambiar destino de trámite">
                                                                    <?php if($d->dtra_interno=='t'): ?>
                                                                        <span class="text-danger">Int</span>
                                                                    <?php else: ?>
                                                                        <span class="text-primary">Ext</span>
                                                                    <?php endif; ?>
                                                                </a>
                                                            <?php endif; ?>
                                                        <?php endif; ?>

                                                        <?php if($d->dtra_obs!='' || $d->dtra_falso=='t'): ?>
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("obs_docleg/".$d->cod_dtra)); ?>','panel_docleg')"
                                                               title="Observado"> <i class="fas fa-eye text-danger"></i></a>
                                                        <?php else: ?>
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("obs_docleg/".$d->cod_dtra)); ?>','panel_docleg')"
                                                               title="Observado"> <i class="fas fa-eye"></i></a>
                                                        <?php endif; ?>
                                                            </a>
                                                            <?php if($d->dtra_falso!='t'): ?>
                                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('generar glosa docleg - srv')): ?>
                                                                    <?php if($tramite->tra_tipo_tramite=='B' || $d->dtra_solo_sello=='t'): ?>

                                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("busqueda doc encontrado/".$d->cod_dtra)); ?>','panel_docleg')"
                                                                           title="Registrar verificación"><i class="fas fa-file-signature"></i>
                                                                        </a>
                                                                    <?php else: ?>

                                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("generar glosa_leg/".$d->cod_dtra)); ?>','panel_docleg')"
                                                                                title="Generar glosa"><i class="fas fa-file-signature"></i>
                                                                            </a>

                                                                   <?php endif; ?>
                                                                <?php endif; ?>
                                                                <?php if($d->dtra_tipo!='E'): ?>
                                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("ver documento pdf legalizado/".$d->cod_dtra)); ?>','panel_docleg')"
                                                                       title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>
                                                                <?php endif; ?>
                                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar docleg - srv')): ?>
                                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("f_eli_docleg/".$d->cod_dtra)); ?>','panel_docleg')"
                                                                       title="Eliminar"> <i class="fas fa-trash-alt"></i>
                                                                    </a>
                                                               <?php endif; ?>
                                                            <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>
                                </tr>
                                <?php $i++;?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </table>
                    </div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear docleg - srv')): ?>
                    <!--Solo cuando es BUSQUEDA SE MUESTRA EL FORMULARIO-->
                    <?php if($tramite->id_per!='' && $tramite->tra_tipo_tramite=='B'): ?>
                        <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Trámite</button><br/>
                        <div class="shadow-sm border col-md-10 float-right" id="divNueTram" style="display: none">
                            <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                                <i class="fas fa-minus-circle text-danger"></i></a>
                            <br/>
                            <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                                    <span id="error_datos_span"></span>
                            </div>
                                <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                    <h6 class="text-dark text-center font-weight-bold">Añadir documento para Búsqueda</h6>
                                </div>
                            <br/>
                            <div class="col-md-11 float-right">
                                <form id="form_docleg">
                                    <?php echo csrf_field(); ?>
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic">Trámite : </th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                    <?php $__currentLoopData = $lista_tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($l->cod_tre); ?>"><?php echo e($l->tre_nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic"> Nº control valorado: </th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group">
                                                    <input type="text" class=" form-control form-control-sm" name="control" required>
                                                    &nbsp;&nbsp;<span class="font-italic font-weight-bold"> Nro. control Reimpresión : </span>&nbsp;&nbsp;
                                                    <input class="form-control form-control-sm" name="reimpresion" />
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="font-italic text-dark font-weight-bold"> CUADIS :
                                                            <input type="checkbox" name="cuadis" />
                                                        </span>&nbsp;&nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><th class="text-right font-italic">Nro. Título:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group ">
                                                    <input name="numero" required class="form-control col-md-2 form-control-sm border " pattern="[0-9]{1,6}"> &nbsp;&nbsp;
                                                    / &nbsp;&nbsp;<input name="gestion" required class="form-control col-md-2 form-control-sm border" pattern="[0-9]{1,4}"> &nbsp;&nbsp;(e.j. 1999)
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Buscar en :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" required name="buscar_en">
                                                    <option value="db">DB</option>
                                                    <option value="ca">CA</option>
                                                    <option value="da">DA</option>
                                                    <option value="tp">TP</option>
                                                    <option value="di">DI</option>
                                                    <option value="tpos">TPOS</option>
                                                    <option value="re">RE</option>
                                                    <option value="su">SU</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><th class="text-right font-italic">
                                                <span class="text-dark font-weight-bold font-italic" style="font-size: 0.9em"> Documentos : </span>
                                            </th>
                                            <td>
                                                <textarea name="documentos" class="form-control form-control-sm" required></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="ctra" value="<?php echo e($tramite->cod_tra); ?>">
                                    <input type="hidden" name="tipo_tramite" value="t">
                                </form>
                                <a href="#" class="btn btn-sm btn-primary float-right mr-4" onclick="enviar1('form_docleg','<?php echo e(url('g_docleg')); ?>','panel_traleg')"
                                   title="Editar legalización">+ Crear </a>
                                <br/><br/>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($tramite->id_per!='' && ($tramite->tra_tipo_tramite=='L' || $tramite->tra_tipo_tramite=='C' || $tramite->tra_tipo_tramite=='E' )): ?>
                        <br/>
                    <hr class="sidebar-divider"/>
                        <!--==============================Añadir Documentos=================-->
                    <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Trámite</button><br/>
                    <div class="shadow-sm" id="divNueTram" style="display: none">

                        <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                            <i class="fas fa-minus-circle text-danger"></i></a>
                        <br/>
                        <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                            <span id="error_datos_span"></span>
                        </div>
                            <div>
                                <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                    <h6 class="text-dark text-center font-weight-bold">Añadir documento</h6>
                                </div>

                                <form id="form_docleg">
                                    <?php echo csrf_field(); ?>
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de legalización :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                    <?php $__currentLoopData = $lista_tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($l->cod_tre); ?>"><?php echo e($l->tre_nombre); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <input type="radio" name="tipo_tramite" checked value="f"> EXTERNO  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="tipo_tramite" value="t"> INTERNO
                                                &nbsp;&nbsp;
                                                <span class="font-weight-bold text-danger" style="font-size: 20px">|</span>
                                                &nbsp;&nbsp;
                                                <?php if($tramite->tra_tipo_tramite=='L'): ?>
                                                    <span class="font-weight-bold text-dark font-italic">&nbsp;&nbsp;PTAG : &nbsp;&nbsp;
                                                            <input type="checkbox" name="ptaang">
                                                        </span>
                                                <?php endif; ?>
                                                &nbsp;&nbsp;<span class="font-italic text-dark font-weight-bold"> CUADIS :
                                                            <input type="checkbox" name="cuadis" />
                                                        </span>&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Título o Resolución:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group ">
                                                    &nbsp;&nbsp;  &nbsp;&nbsp;<input name="numero" class="form-control col-md-2 form-control-sm border "> &nbsp;&nbsp;
                                                    / &nbsp;&nbsp;<input name="gestion" required class="form-control col-md-2 form-control-sm border" pattern="[0-9]{1,4}"> &nbsp;&nbsp;(e.j. 1999)
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark float-right">
                                                        Supletorio : <input type="checkbox" name="supletorio">
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Control:</th>
                                            <td class="border-bottom border-dark input-group">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm border-0" required name="control" />
                                                    <span class="text-primary font-weight-bold font-italic"> Reintegro : &nbsp;</span>
                                                    <input class="form-control form-control-sm border" required name="reintegro" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">N° control Búsqueda:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm" name="valorado_bus" />
                                                    &nbsp;&nbsp;<span class="font-italic font-weight-bold"> Nro. control Reimpresión : </span>&nbsp;&nbsp;
                                                    <input class="form-control form-control-sm" name="reimpresion" />
                                                </div>
                                            </td>

                                        </tr>
                                    </table>
                                    <input type="hidden" name="ctra" value="<?php echo e($tramite->cod_tra); ?>">
                                </form>
                                <br/>
                                <a href="#" class="btn btn-sm btn-primary float-right mr-4" onclick="enviar1('form_docleg','<?php echo e(url('g_docleg')); ?>','panel_traleg')"
                                   title="Editar legalización">+ Crear </a>
                                <br/><br/>
                            </div>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>


    <script>
        function cargarDatosPersonales(ci){
            var link="<?php echo e(url('datos_per/')); ?>"+"/"+ci;
            $.ajax({
                url: link,
                type: 'GET',
                success: function (resp) {
                    if(resp=="No"){
                        $('#apellido').val('');
                        $('#nombre').val('');
                    }else{
                        var res=JSON.parse(resp);
                        $('#apellido').val(res['per_apellido']);
                        $('#nombre').val(res['per_nombre']);
                    }
                },
                error: function () {
                    $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }
        function cargarDatosApoderado(ci){
            var link="<?php echo e(url('datos_apo/')); ?>"+"/"+ci;
            $.ajax({
                url: link,
                type: 'GET',
                success: function (resp) {
                    if(resp=="No"){
                        $('#apellido_apoderado').val('');
                        $('#nombre_apoderado').val('');
                    }else{
                        var res=JSON.parse(resp);
                        $('#apellido_apoderado').val(res['apo_apellido']);
                        $('#nombre_apoderado').val(res['apo_nombre']);
                    }
                },
                error: function () {
                    $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }

    </script>

<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/servicios/tra_legalizacion/fe_traleg.blade.php ENDPATH**/ ?>