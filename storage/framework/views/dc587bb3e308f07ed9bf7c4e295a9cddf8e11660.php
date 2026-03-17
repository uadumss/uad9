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
            <div class="card-header py-3 alert-primary col-md-12">
                <div class="d-sm-flex align-items-center col-md-12">
                    <div class="col-md-6">
                        <h5 class=""><i class="fas fa-user-circle"></i>&nbsp;Funcionarios</h5>
                    </div>
                    <div class="col-md-6">
                        <a href="" class="btn btn-sm btn-primary float-right mr-1" data-toggle="modal" data-target="#documento"
                           onclick="cargarDatos('<?php echo e(url('fe_documento/0/'.$cod_fun)); ?>','panel_documento')">+ Documento</a>
                        <a href="" class="btn btn-sm btn-primary float-right mr-1" data-toggle="modal" data-target="#documento"
                           onclick="cargarDatos('<?php echo e(url('fe_documento titularidad/0/'.$cod_fun)); ?>','panel_documento')">+ Titularidad</a>
                    </div>
                </div>
            </div>
            <div class="card-body" style="font-size: 15px">
                <div class="">
                    <div class="">
                        <?php  $redireccion=$funcionario->fun_doc_adm=='D'?'docente':'administrativo';?>
                        <a href="<?php echo e(url('listar funcionario/'.$redireccion)); ?>" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista Titularidades</h5>
                        </div>
                        <span style="font-size: 0.85em">
                            <span class="text-primary font-italic">Funcionario : </span><span class="text-dark font-weight-bold"><?php echo e($funcionario->fun_nombre); ?></span> |
                            <span class="text-primary font-italic">Tipo : </span><span class="text-dark font-weight-bold">
                                <?php
                                    switch($funcionario->fun_doc_adm){
                                        case 'D': echo 'DOCENTE'; break;
                                        case 'E': echo 'DOCENTE - ADMINISTRATIVO'; break;
                                        case 'A': echo 'ADMINISTRATIVO'; break;
                                    }
                                ?>
                            </span>
                        </span>

                        <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="">Tipo Titularidad</th>
                                <th class="">Detalle</th>
                                <th class="">Materia</th>
                                <th class="">Carrera</th>
                                <th class="">Facultad</th>
                                <th class="">Fecha emisión</th>
                                <th class="">Resolución</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <?php $j=1;?>

                            <tbody> <?php $__currentLoopData = $titularidades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($j); ?></td>
                                        <td><?php echo e($d->dt_categoria); ?></td>
                                        <td><?php echo e($d->dt_detalle); ?></td>
                                        <td><?php echo e($d->dt_materia); ?></td>
                                        <td><?php echo e($d->car_nombre); ?></td>
                                        <td><?php echo e($d->fac_nombre); ?></td>
                                        <td>
                                            <?php if($d->dt_fecha!=''): ?>
                                                <?php echo e(date('d/m/Y',strtotime($d->dt_fecha))); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($d->dt_numero_resolucion); ?></td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_documento titularidad/'.$d->cod_dt.'/'.$d->cod_fun)); ?>','panel_documento')"
                                               title="Editar titularidad"><i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_eliminar titularidad/'.$d->cod_dt.'/'.$d->cod_fun)); ?>','panel_documento')"
                                               title="Eliminar titularidad"><i class="text-danger fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php $j++;?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista de Diplomas y Títulos</h5>
                        </div>
                        <hr class="sidebar-divider">
                                <table class="table table-sm table-hover" width="100%" cellspacing="0" style="font-size: 0.8em">
                                    <thead>
                                    <tr class="bg-gray-600 text-white">
                                        <th>Nº</th>
                                        <th class="">Tipo</th>
                                        <th class="">Tìtulo</th>
                                        <th class="">Grado</th>
                                        <th class="">Universidad</th>
                                        <th class="">Educación Superior</th>
                                        <th class="">Reválida</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $j=1;?>
                                    <?php $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($d->doc_obs!='t'): ?>
                                            <tr>
                                        <?php else: ?>
                                            <tr class="alert-danger">
                                        <?php endif; ?>
                                            <td><?php echo e($j); ?></td>
                                            <td><?php echo e($d->doc_tipo); ?></td>
                                            <td>
                                                <span class="font-weight-bold text-dark"><?php echo e($d->doc_titulo); ?></span><br/>
                                                <span style="font-size: 0.9em">
                                                    <span class="text-primary font-italic">Gestión : </span><span class="text-dark "><?php echo e($d->doc_gestion); ?></span> |
                                                    <span class="text-primary font-italic">Legalizado : </span><span class="text-dark font-weight-bold">
                                                    <?php
                                                        echo $d->doc_legalizado=='t'?"<i class='fas fa-check-circle text-success'></i>":"<i class='fas fa-minus-circle text-danger'></i>";
                                                    ?>
                                                    </span> |
                                                    <span class="text-primary font-italic">Verificado : </span><span class="text-dark font-weight-bold">
                                                    <?php
                                                        echo $d->doc_verificado=='t'?"<i class='fas fa-check-circle text-success'></i>":"<i class='fas fa-minus-circle text-danger'></i>";
                                                    ?>
                                                    </span> |
                                                    <span class="text-primary font-italic">Fecha emisión : </span><span class="text-dark">
                                                    <?php
                                                        if($d->doc_fecha_emision!=''){
                                                            echo date('d/m/Y',strtotime($d->doc_fecha_emision));
                                                        }
                                                    ?>
                                                    </span>
                                                </span>

                                            </td>
                                            <td><?php echo e($d->doc_grado); ?></td>
                                            <td><?php echo e($d->doc_universidad); ?></td>
                                            <td>
                                                <?php if($d->doc_edu_superior=='t'): ?>
                                                    <span class="bg-success text-white rounded font-italic pr-1 pl-1 font-weight-bold"> Docencia </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($d->doc_numero_revalida); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_documento/'.$d['cod_doc'].'/'.$d->cod_fun)); ?>','panel_documento')"
                                                   title="Editar documento"><i class="fas fa-edit"></i>
                                                </a>
                                                <?php if($d->doc_obs=='t'): ?>
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#documento" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_observacion documento/'.$d['cod_doc'])); ?>','panel_documento')"
                                                        title="Observar Documento"><i class="fas fa-eye"></i>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_observacion documento/'.$d['cod_doc'])); ?>','panel_documento')"
                                                       title="Observar Documento"><i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#documento" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_eliminar documento/'.$d->cod_doc.'/'.$d->cod_fun)); ?>','panel_documento')"
                                                   title="Eliminar documento"><i class="text-danger fas fa-trash-alt"></i>
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
        </div>

    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('acceder al sistema - dya')): ?>
        <!--===========================MODAL DOCENTE===================-->
            <div class="modal fade" id="documento" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document" id="panel_documento">

                </div>
            </div>
            <!--===========================END ==============================-->
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco.pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/funcionario/documento/l_documento.blade.php ENDPATH**/ ?>