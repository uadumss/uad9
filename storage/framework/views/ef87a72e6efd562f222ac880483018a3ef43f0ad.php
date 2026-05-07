
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
    <?php if(Session::has('errores')): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo session('errores'); ?>

        </div>
    <?php endif; ?>

    <?php if(isset($fallas) && count($fallas)>0): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                <?php $__currentLoopData = $fallas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <?php echo "Fila: ".$f->row()." - ";?>
                        <?php $errores=(array) $f->errors();
                        foreach ($errores as $e):
                            echo $e;
                        endforeach;
                        ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;Facultades</h5>
                </div>
            </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" style="background-color:#e1edff">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="text-dark"><i class="fas fa-university"></i>&nbsp;Lista facultades</h5>
                                <div>
                                    <a class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm text-white mr-1" data-toggle="modal" data-target="#exportarFacultadesExcel">
                                        <i class="fas fa-file-excel"></i> Exportar Excel
                                    </a>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear editar facultad - f')): ?>
                                        <a class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm text-white" data-toggle="modal" data-target="#facultad"
                                           onclick="cargarDatos('fe_facultad/0','panel_contenido')" >
                                            + Facultad
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <hr class="sidebar-divider">
                                <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
                                    <thead>
                                    <tr class="bg-gray-600 text-white">
                                        <th>Nº</th>
                                        <th class="">Nombre</th>
                                        <th class="">Nombre corto</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <!--<tfoot>
                                    <tr class="bg-gradient-secondary text-white">
                                        <th>Nº</th>
                                        <th>Número de Tomo</th>
                                        <th>Rango de documentos</th>
                                        <th>Cantidad de registros</th>
                                        <th>Observación</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </tfoot>-->
                                    <tbody>
                                    <?php $j=1;?>
                                    <?php $__currentLoopData = $facultades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <th class="border-right font-weight-bolder text-primary"><?php echo e($j); ?></th>
                                            <td class="text-left"><?php echo e($f['fac_nombre']); ?></td>
                                            <td class="text-left"><?php echo e($f['fac_abreviacion']); ?></td>
                                            <td class="text-right">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear editar facultad - f')): ?>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#efacultad" data-toggle="modal"
                                                   onclick="cargarDatos('fe_facultad/<?php echo e($f['cod_fac']); ?>','panel_econtenido')" title="Editar facultad">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar facultad - f')): ?>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#efacultad" data-toggle="modal"
                                                   onclick="cargarDatos('f_eli_facultad/<?php echo e($f['cod_fac']); ?>','panel_econtenido')" title="Eliminar facultad">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                <?php endif; ?>
                                                &nbsp;&nbsp;
                                                <a href="#panel_carrera" class="btn btn-light btn-circle btn-sm text-primary" data-target="#verImportacion" data-toggle="modal"
                                                   onclick="cargarDatos('l_carrera/<?php echo e($f['cod_fac']); ?>','panel_carrera')" title="Ver carreras">
                                                    <i class="fas fa-angle-right"></i>
                                                </a>
                                            </td>

                                        </tr>
                                        <?php $j++;?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>

                        </div>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3" style="background-color:#e1edff">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="text-dark"><i class="fas fa-book"></i>&nbsp;Lista de carreras</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="panel_carrera">

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acceso al sistema - f')): ?>
        <!--===========================MODAL EXPORTAR FACULTADES===================-->
        <div class="modal fade" id="exportarFacultadesExcel" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content border-bottom-success">
                    <form action="<?php echo e(url('exportar facultades carreras excel')); ?>" method="GET">
                        <div class="modal-header bg-success">
                            <h5 class="modal-title text-white" id="exampleModalLabel"><i class="fas fa-file-excel"></i> Exportar facultades y carreras</h5>
                            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-light border text-dark" style="font-size: 0.85em;">
                                Seleccione una facultad específica o exporte todas las facultades con sus carreras.
                            </div>
                            <label class="font-italic font-weight-bold">Facultad</label>
                            <select class="custom-select" name="facultad">
                                <option value="0">Todas las facultades</option>
                                <?php $__currentLoopData = $facultades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($f['cod_fac']); ?>"><?php echo e($f['fac_nombre']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                            <button class="btn btn-success" type="submit">Descargar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--===========================END ==============================-->

        <!--===========================MODAL NUEVA FACULTAD===================-->
        <div class="modal fade" id="facultad" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_contenido">

            </div>
        </div>

        <!--===========================END ==============================-->

        <!--===========================MODAL ELIMINAR FACULTAD===================-->
        <div class="modal fade" id="efacultad" role="dialog" style="z-index:1500" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document" id="panel_econtenido">

            </div>
        </div>

        <!--===========================END ==============================-->
    <?php endif; ?>
    <!-- =============================== ====================-->

<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/unidad/facultad/l_facultad.blade.php ENDPATH**/ ?>