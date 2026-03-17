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
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;Funcionarios</h5>

                    <a href="" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#docente"
                    onclick="cargarDatos('<?php echo e(url('fe_funcionario/0')); ?>','panel_docente')">+ Funcionario</a>

                        <a href="" class="btn btn-sm btn-outline-info text-dark" data-toggle="modal" data-target="#nuevaImportacion"><i class="fas fa-upload"> Nueva importación</i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="">
                    <div class="">
                        <a href="<?php echo e(url('listar funcionario/docente')); ?>" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-right"></i> Listar docente</a> &nbsp;&nbsp;
                        <a href="<?php echo e(url('listar funcionario/administrativo')); ?>" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-right"></i> Listar Administrativo</a>
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Lista de <?php echo e($funcionario); ?>s</h5>
                        </div>

                                <hr class="sidebar-divider">
                                <table class="table table-sm table-hover"  width="100%" cellspacing="0" style="font-size: 0.8em">
                                    <thead>
                                    <tr class="bg-gray-600 text-white">
                                        <th>Nº</th>
                                        <th class="">Nombre</th>
                                        <th class="">CI</th>
                                        <th class="">Sexo</th>
                                        <th class="">Telefonos</th>
                                        <th class="">Correo</th>
                                        <th class="">Fecha Ingreso</th>
                                        <th class="">Nacionalidad</th>
                                        <th class="">Pais Origen</th>
                                        <th>Opciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $j=1;?>
                                    <?php $__currentLoopData = $funcionarios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if($f->fun_obs!='t'): ?>
                                            <tr>
                                        <?php else: ?>
                                            <?php if($f->fun_folder!='t'): ?>
                                                <tr class="bg-warning">
                                            <?php else: ?>
                                                <tr class="alert-danger">
                                            <?php endif; ?>

                                        <?php endif; ?>
                                            <td>
                                                <?php echo e($j); ?>


                                            </td>
                                            <td><?php echo e($f->fun_nombre); ?>

                                                <?php if($f->fun_folder!='t'): ?>
                                                    <span class="bg-danger p-1 rounded text-white">*</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($f->fun_ci); ?> - <?php echo e($f->cod_fun); ?></td>
                                            <?php $sexo=$f->fun_sexo=='M'?'Masculino':'Femenino' ?>
                                            <td><?php echo e($sexo); ?></td>
                                            <td><?php echo e($f->fun_telefonos); ?></td>
                                            <td><?php echo e($f->fun_email); ?></td>
                                            <td>
                                                <?php if($f->fun_fecha_ingreso!=''): ?>
                                                    <?php echo e(date('d/m/Y',strtotime($f->fun_fecha_ingreso))); ?>

                                                <?php endif; ?>
                                            </td>
                                            <?php $nacionalidad=$f->fun_nacionalidad=='B'?'Boliviano':'Extranjero' ?>
                                                <td><?php echo e($nacionalidad); ?></td>
                                            <td><?php echo e($f->cod_nac); ?></td>
                                            <td>
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docente" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_funcionario/'.$f->cod_fun)); ?>','panel_docente')"
                                                   title="Editar funcionario"><i class="fas fa-edit"></i>
                                                </a>
                                                <a href="<?php echo e(url('listar documentos funcionario/'.$f->cod_fun)); ?>" class="btn btn-light btn-circle btn-sm text-primary" title="Mostrar documentos">
                                                    <i class="fas fa-arrow-alt-circle-right"></i>
                                                </a>
                                                <?php if($f->fun_obs=='t'): ?>
                                                    <a href="" class="btn btn-light btn-circle btn-sm text-danger" title="Ver Observacion">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <?php if($f->fun_folder!='t'): ?>
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docente" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_presentar folder/'.$f->cod_fun)); ?>','panel_docente')"
                                                        title="Presentar Folder"><i class="text-primary fas fa-folder-open"></i>
                                                    </a>
                                                <?php endif; ?>

                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docente" data-toggle="modal" onclick="cargarDatos('<?php echo e(url('fe_eliminar funcionario/'.$f->cod_fun)); ?>','panel_docente')"
                                                   title="Eliminar funcionario"><i class="text-danger fas fa-trash-alt"></i>
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
            <div class="modal fade" id="docente" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document" id="panel_docente">

                </div>
            </div>
            <!--===========================END ==============================-->
    <?php endif; ?>
        <div class="modal fade" id="nuevaImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form action="<?php echo e(url('importar nuevos')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-content border-bottom-primary">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Nueva importación</h5>
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


        <script>
            function cargarDatos(ruta,panel){
                $.ajax({
                    url: ruta,
                    type: 'GET',
                    data:'',
                    success: function (resp) {
                        $('#'+panel).html(resp);
                    },
                    error: function () {
                        alert('No se puede ejecutar la petición');
                    }
                });
            }
        </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco.pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/funcionario/l_funcionario.blade.php ENDPATH**/ ?>