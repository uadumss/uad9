<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> CONVOCATORIA</h5>
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
                    <div class="card-body" style="font-size: 12px;">
                        <?php if(!$convocatoria): ?>
                            <form id="form_convocatoria" enctype="multipart/form-data" method="POST" action="<?php echo e(url('guardar convocatoria noatentado')); ?>">
                                <?php echo csrf_field(); ?>
                            <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                                <h5 class="text-white text-center">Nueva convocatoria</h5>
                            </div>
                            <hr class="sidebar-divider text-bg-dark">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                    <table class="col-md-12">
                                        <tbody>
                                            <tr>
                                                <th class="text-right font-italic">Título :</th>
                                                <td class="border-bottom border-dark">
                                                    <input class="form-control-sm form-control border-0" type="text" name="titulo" placeholder="Título de la convocatoria" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Publicación Convocatoria :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <div class="col-md-12">
                                                        <input type="date" name="fi_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Entrega documentos :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <div class="col-md-12">
                                                        <input type="date" name="ff_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Fecha conclusión :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <div class="col-md-12">
                                                        <input type="date" name="fc_convocatoria" class="form-control form-control-sm border-0 form-control-user">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Tipo Convocatoria :</th>
                                                <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                    <select class="custom-select custom-select-sm border-0" name="tipo">
                                                        <option value=""></option>
                                                        <option value="ACADEMICO">ACADEMICO</option>
                                                        <option value="GREMIAL">GREMIAL</option>
                                                        <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <!-- POR HIDDEN LA CLASE ES NO ATENTADO-->
                                            <tr>
                                                <th class="text-right font-italic" style=" padding-top: 7px">Dirigido a :</th>
                                                <td class="border-bottom border-dark">
                                                     <select class="custom-select custom-select-sm border-0" name="dirigido">
                                                        <option value=""></option>
                                                        <option value="DOCENTE">DOCENTE</option>
                                                        <option value="ESTUDIANTE">ESTUDIANTE</option>
                                                         <option value="DOCENTE-ESTUDIANTE">DOCENTE-ESTUDIANTE</option>
                                                        <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic ">Periodo:</th>
                                                <td class="border-bottom  input-group">
                                                    <div class="input-group p-2">
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Incial : &nbsp;</span>
                                                        <select class="custom-select-sm custom-select border" name="inicial">
                                                            <option></option>
                                                                <?php $año=date('Y');?>
                                                            <?php for($i=$año;$i<($año+8);$i++): ?>
                                                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                        &nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Final : &nbsp;</span>
                                                        <select class="custom-select-sm custom-select border" name="final">
                                                            <option></option>
                                                                <?php $año=date('Y');?>
                                                            <?php for($i=$año;$i<($año+8);$i++): ?>
                                                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                            <?php endfor; ?>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-md-6">

                                </div>
                            </div>
                            </form>
                        <?php else: ?>
                            <div class="text-center">
                                <h4 class="text-primary font-weight-bold">Editar Convocatoria</h4>
                            </div>
                            <hr class="sidebar-divider text-bg-dark">
                            <div class="row">
                                <div class="col-md-6">
                                    <span
                                        class="text-primary font-weight-bold float-left">DATOS DE LA CONVOCATORIA</span>
                                    <br><br>
                                    <form id="form_convocatoria" enctype="multipart/form-data" method="POST" action="<?php echo e(url('guardar convocatoria noatentado')); ?>">
                                        <?php echo csrf_field(); ?>
                                    <table class="col-md-12">
                                        <tbody>
                                        <tr>
                                            <th class="text-right font-italic">Título :</th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control-sm form-control border-0" type="text" name="titulo" placeholder="Título de la convocatoria" value="<?php echo e($convocatoria->con_nombre); ?>"/>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Publicación Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="col-md-12">
                                                    <input type="date" name="fi_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user" value="<?php echo e($convocatoria->con_fecha_publicacion); ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Entrega documentos :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="col-md-12">
                                                    <input type="date" name="ff_convocatoria" required="" class="form-control form-control-sm border-0 form-control-user" value="<?php echo e($convocatoria->con_fecha_entrega); ?>">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Fecha conclusión :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="col-md-12">
                                                    <input type="date" name="fc_convocatoria" class="form-control form-control-sm border-0 form-control-user" value="<?php echo e($convocatoria->con_fecha_eleccion); ?>">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Tipo Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <select class="custom-select custom-select-sm border-0" name="tipo">
                                                    <?php if($convocatoria->con_tipo!=''): ?>
                                                        <option value="<?php echo e($convocatoria->con_tipo); ?>"><?php echo e($convocatoria->con_tipo); ?></option>
                                                    <?php endif; ?>
                                                    <option value=""></option>
                                                    <option value="ACADEMICO">ACADEMICO</option>
                                                    <option value="GREMIAL">GREMIAL</option>
                                                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <!-- POR HIDDEN LA CLASE ES NO ATENTADO-->
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Dirigido a :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" name="dirigido">
                                                    <?php if($convocatoria->con_dirigido_a!=''): ?>
                                                    <option value="<?php echo e($convocatoria->con_dirigido_a); ?>"><?php echo e($convocatoria->con_dirigido_a); ?></option>
                                                    <?php endif; ?>
                                                    <option value=""></option>
                                                    <option value="DOCENTE">DOCENTE</option>
                                                    <option value="ESTUDIANTE">ESTUDIANTE</option>
                                                    <option value="DOCENTE-ESTUDIANTE">DOCENTE-ESTUDIANTE</option>
                                                    <option value="ADMINISTRATIVO">ADMINISTRATIVO</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Periodo:</th>
                                            <td class="border-bottom  input-group">
                                                <div class="input-group p-2">
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Incial : &nbsp;</span>
                                                    <select class="custom-select-sm custom-select border" name="inicial">
                                                        <option value="<?php echo e($convocatoria->con_periodo_inicial); ?>"><?php echo e($convocatoria->con_periodo_inicial); ?></option>
                                                        <option></option>
                                                            <?php $año=date('Y');?>
                                                        <?php for($i=$año;$i<($año+8);$i++): ?>
                                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                    &nbsp;&nbsp;<span class="text-primary font-weight-bold font-italic pt-2"> Final : &nbsp;</span>
                                                    <select class="custom-select-sm custom-select border" name="final">
                                                        <option value="<?php echo e($convocatoria->con_periodo_final); ?>"><?php echo e($convocatoria->con_periodo_final); ?></option>
                                                        <option></option>
                                                            <?php $año=date('Y');?>
                                                        <?php for($i=$año;$i<($año+8);$i++): ?>
                                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                        <?php endfor; ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria PDF :
                                            </th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <div class="input-group">
                                                    <?php if($convocatoria->con_pdf!=''): ?>
                                                        <input type="file" class="form-control form-control-sm border-danger" name="pdf_conv" accept=".pdf" value="<?php echo e($convocatoria->archivo); ?>">
                                                        <a href="<?php echo e(url("PDF_convocatoria/".$convocatoria->cod_con)); ?>" type="button" data-target="#modal_noAtentado" target="_blank">
                                                            &nbsp;&nbsp;<i class="fas fa-file-pdf text-danger" style="font-size: 30px;"></i>
                                                        </a>
                                                        &nbsp;&nbsp;
                                                    <?php else: ?>
                                                            <input type="file" class="form-control form-control-sm border-0" name="pdf_conv" accept=".pdf" value="<?php echo e($convocatoria->archivo); ?>">
                                                    <?php endif; ?>

                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                        <input type="hidden" name="cc" value="<?php echo e($convocatoria->cod_con); ?>">
                                    </form>
                                    <br/><br/>

                                    <div class="shadow rounded p-3 m-2">
                                        <table class="col-md-12">
                                            <tr>
                                                <td class="font-italic text-primary font-weight-bold" >Unidad de la convocatoria:</td>
                                                <td class="border-bottom border-dark"><?php echo e($convocatoria->con_convocante); ?> </td>
                                            </tr>
                                        </table>
                                        <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#modal_agregar"
                                           onclick="cargarDatos('<?php echo e(url('editar unidad convocatoria noatentado/'.$convocatoria->cod_con)); ?>','panel_agregar')">
                                            Unidad</a>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                                <div class="shadow m-2 p-2 rounded">
                                                    <div class="border rounded">
                                                        <span class="font-weight-bold font-italic text-primary"> * Cargos registrados</span>
                                                        <br/>
                                                        <br/>
                                                        <div id="panel_cargos" class="overflow-auto ml-5" style="height: 400px;" >
                                                            <table class="col-md-12">
                                                                <tr class="bg-secondary text-white">
                                                                    <th>No.</th>
                                                                    <th>Nombre cargo</th>
                                                                    <th>Opciones</th>
                                                                </tr>
                                                                    <?php $i=1;?>
                                                                <?php $__currentLoopData = $cargos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr class="border-bottom">
                                                                        <td><?php echo e($i); ?></td>
                                                                        <td><?php echo e($c->carg_nombre); ?></td>
                                                                        <td>
                                                                            <form id="form_eliminar<?php echo e($i); ?>">
                                                                                <?php echo csrf_field(); ?>
                                                                                <input type="hidden" name="cc" value="<?php echo e($convocatoria->cod_con); ?>"/>
                                                                                <input type="hidden" name="ca" value="<?php echo e($c->cod_carg); ?>"/>
                                                                            </form>
                                                                            <a class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#modal_agregar" onclick="cargarDatos('<?php echo e(url('cargos convocatoria noatentado/'.$c->cod_carg.'/'.$convocatoria->cod_con)); ?>','panel_agregar')"><i class="fas fa-edit"></i></a>
                                                                            <a class="btn btn-light btn-circle btn-sm text-danger" onclick="enviar('form_eliminar<?php echo e($i); ?>','<?php echo e(url('eliminar cargo convocatoria noatentado')); ?>','panel_cargos')"><i class="fas fa-trash-alt"></i></a>
                                                                        </td>
                                                                    </tr>
                                                                        <?php $i++;?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </table>
                                                        </div>
                                                        <a class="btn btn-sm btn-primary text-white" data-toggle="modal" data-target="#modal_agregar"
                                                           onclick="cargarDatos('<?php echo e(url('cargos convocatoria noatentado/0/'.$convocatoria->cod_con)); ?>','panel_agregar')">
                                                            Cargos</a>
                                                    </div>
                                                </div>


                                </div>
                                <br/>

                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal"> Cerrar</button>
            <?php if(!$convocatoria): ?>
                <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_convocatoria','<?php echo e(url('guardar convocatoria noatentado')); ?>','panel_convocatoria');cargarDatos('<?php echo e(url('actualizar lista convocatoria noatentado/'.date('Y'))); ?>','panel_lista')"> Guardar</button>
            <?php else: ?>
                <a href="#" class="btn btn-primary btn-sm text-white" onclick="$('#form_convocatoria').submit();"> Guardar</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/fe_convocatoria.blade.php ENDPATH**/ ?>