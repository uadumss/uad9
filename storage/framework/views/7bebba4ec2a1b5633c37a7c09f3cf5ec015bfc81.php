<?php $__env->startSection('contenido'); ?>
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

<div class="container-fluid">
        <div id="panel_l_convocatoria">
            <div class="card shadow mb-4">
                <div class="card-header py-3 alert-primary">
                    <div class="row">
                        <div class="col-md-6">
                            <h5><i class="fas fa-file-alt"></i>&nbsp;&nbsp;LISTA DE CONVOCATORIAS TRAMITE DE NO-ATENTADO&nbsp;</h5>
                        </div>
                        <div class="col-md-6 d-flex justify-content-end">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear convocatoria - noa')): ?>
                                <?php if($gestion==date('Y')): ?>
                                <a href="#" class="btn btn-sm btn-primary btn-sm" style="margin: 5px" data-toggle="modal" data-target="#modal_convocatoria"
                                   onclick="cargarDatos('<?php echo e(url("editar convocatoria noatentado/0")); ?>','panel_convocatoria')">
                                    + Convocatoria
                                </a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class=" input-group -sm p-2">
                        <div class="col-md-3 input-group shadow-sm p-1" style="font-size: 0.9em">
                            <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                            <select class="form-control form-control-sm border border-info" name="gestion" onchange="$(location).attr('href','<?php echo e(url('lista convocatoria noatentado')); ?>/'+this.value);">
                                <option value="<?php echo e($gestion); ?>"><?php echo e($gestion); ?></option>
                                <?php $año=date('Y');?>
                                <?php for($i=$año;$i>2021;$i--): ?>
                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h5 class="text-white text-center">Lista de convocatorias trámite no atentado</h5>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div id="panel_lista">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                                <thead>
                                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                    <th>N°</th>
                                    <th>Título</th>
                                    <th>Publicación</th>
                                    <th>Entrega de Documentos</th>
                                    <th>Gestión</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <?php $i = 0;?>
                                <tbody>
                                <?php $__currentLoopData = $convocatorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+=1); ?></td>
                                        <td><?php echo e($c->con_nombre); ?> </td>
                                        <td><?php if($c->con_fecha_publicacion!=''): ?>
                                                <?php echo e(date('d/m/Y',strtotime($c->con_fecha_publicacion))); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php if($c->con_fecha_entrega!=''): ?>
                                            <?php echo e(date('d/m/Y',strtotime($c->con_fecha_entrega))); ?>

                                        <?php endif; ?>

                                        <td><?php echo e($c->con_gestion); ?></td>
                                        <td class="text-right">
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar convocatoria - noa')): ?>
                                                <a href="" class="btn btn-sm btn-light btn-circle" data-toggle="modal" title="Editar convocatoria" data-target="#modal_convocatoria"
                                                   onclick="cargarDatos('<?php echo e(url("editar convocatoria noatentado/".$c->cod_con)); ?>','panel_convocatoria')">
                                                    <i class="fas fa-edit text-primary"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if($c->con_pdf!=''): ?>
                                                <a href="<?php echo e(url("PDF_convocatoria/".$c->cod_con)); ?>" class="btn btn-sm btn-light btn-circle" title="Descargar convocatoria"
                                                   data-target="#modal_noAtentado" target="_blank">
                                                    <i class="far fa-file-pdf"></i>
                                                </a>
                                            <?php else: ?>
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar convocatoria - noa')): ?>
                                                <a href="" class="btn btn-sm btn-light btn-circle" data-toggle="modal" title="Eliminar onvocatoria"
                                                    data-target="#modal_agregar" onclick="cargarDatos('<?php echo e(url("formulario eliminar convocatoria noatentado/".$c->cod_con)); ?>','panel_agregar')">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                            <?php endif; ?>
                                            &nbsp;&nbsp;&nbsp;
                                            <a href="<?php echo e(url('listar tramite convocatoria/'.$c->cod_con)); ?>" class="btn btn-sm btn-light btn-circle" title="Mostrar trámites">
                                                <i class="fas fa-arrow-circle-right text-primary"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="modal_convocatoria" style="z-index: 1500" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-xl" role="document" id="panel_convocatoria">

        </div>
    </div>
    <!--===========================END===================-->

    <!-- ================== MODAL DOCLEG ====================-->
    <div class="modal fade" id="modal_agregar" role="dialog" style="z-index: 3000; margin-top: 40px;">
        <div class="modal-dialog modal-xl" role="document" id="panel_agregar">

        </div>
    </div>
    <!--===========================END ==============================-->

<script>$('#dataTable').dataTable({"pageLength": 50});</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/l_convocatoria.blade.php ENDPATH**/ ?>