<?php $__env->startSection('contenido'); ?>
    <?php if(Session::has('exito')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold"><?php echo session('exito'); ?></span>
        </div>
    <?php endif; ?>

    <?php if(Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold text-dark"><?php echo session('error'); ?></span>
        </div>
    <?php endif; ?>
    <?php if(count($errors)>0): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="font-weight-bold te"><?php echo e($e); ?> - </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; LISTA DE SANCIONADOS POR ATENTADO CONTRA LA AUTONOMIA UNIVERSITARIA</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear sancionado - noa')): ?>
                            <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#Modal" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url('editar sancionado/0')); ?>','panel_modal');">
                                + Sancionado</a>
                            <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                        <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear sancionado - noa')): ?>
                                <a class="btn btn-outline-info btn-sm text-dark shadow-sm" data-target="#nuevaImportacion" data-toggle="modal">
                                    Importar
                                </a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                            <?php endif; ?>
                    </div>
                </div>
            </div>

            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de sancionados</h5>
                    </div>

                    <hr class="sidebar-divider">
                    <div id="panel_lista_sancionado">
                        <div>
                            <div class="table-responsive">
                                <div id="panel_tabla_tramites" class="col-md-12">
                                    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                                        <thead>
                                        <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                            <th>Nº</th>
                                            <th class="text-left">Nombre</th>
                                            <th class="text-right">CI</th>
                                            <th class="text-left">Referencia</th>
                                            <th class="text-center">Sentencia</th>
                                            <th class="text-center">Resolucion</th>
                                            <th class="text-center">PDF</th>
                                            <th>Opciones</th>
                                        </tr>
                                        </thead>
                                        <tbody id="cuerpo">
                                            <?php $i=1;?>
                                        <?php $__currentLoopData = $sancionados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <th class="border-right font-weight-bolder"><?php echo e($i); ?></th>
                                                <td><?php echo e($s->per_apellido.' '.$s->per_nombre); ?></td>
                                                <td><?php echo e($s->per_ci); ?></td>
                                                <td><?php echo e($s->san_referencia); ?></td>
                                                <td><?php echo e($s->san_sentencia); ?></td>
                                                <td><?php echo e($s->san_resolucion); ?></td>
                                                <td>

                                                    <?php if($s->res_pdf!=''): ?>
                                                        <a class="text-danger" style="font-size: 20px">
                                                            <a href="" class="btn btn-circle btn-light btn-sm text-danger float-right border" data-toggle="modal" data-target="#Modal"
                                                               onclick="cargarDatos('<?php echo e(url('ver datos resolucion/'.$s->cod_res)); ?>','panel_modal')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
                                                            </a>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar sancionado - noa')): ?>
                                                        <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal"
                                                           onclick="cargarDatos('<?php echo e(url("editar sancionado/".$s->cod_san)); ?>','panel_modal')"
                                                           title="Editar sancionado"><i class="fas fa-edit"></i>
                                                        </a>
                                                    <?php endif; ?>

                                                            <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Modal"
                                                               onclick="cargarDatos('<?php echo e(url("lista detalle sancion noatentado/".$s->cod_san)); ?>','panel_modal')"
                                                               title="Ver detalle de sanción"><i class="fas fa-align-justify"></i>
                                                            </a>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar sancionado - noa')): ?>
                                                        <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Modal" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("formulario eliminar sancionado noatentado/".$s->cod_san)); ?>','panel_modal')"
                                                           title="Eliminar sancionado"> <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                                <?php $i++;?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="Modal" style="z-index: 1500" role="dialog" aria-hidden="false">
        <div class="modal-dialog modal-xl" role="document" id="panel_modal">

        </div>
    </div>
    <!--===========================END===================-->

    <!-- ================== MODAL DOCLEG ====================-->
    <div class="modal fade" id="Modal2" role="dialog" style="z-index: 3000; margin-top: 40px;">
        <div class="modal-dialog modal-xl" role="document" id="panel_modal2">

        </div>
    </div>
    <!--===========================END ==============================-->
    <div class="modal fade" id="nuevaImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="<?php echo e(url('importar sancionado noatentado/')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Importar Sancionados</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="shadow-sm rounded p-2">
                            <h5 class="text-primary text-center">Importar Archivo</h5>
                            <br/>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic">Archivo :</th>
                                    <td class="">
                                        <div class="custom-file mb-3">
                                            <input type="file" class="form-control form-control-file" id="archivo" name="archivo" accept=".xlsx,.xls" required>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                        <input class="btn btn-primary" type="submit" value="Enviar"/>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/sancionado/l_sancionado.blade.php ENDPATH**/ ?>