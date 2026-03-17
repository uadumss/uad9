<?php $__env->startSection('contenido'); ?>
    <?php if(Session::has('exito_importacion')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo session('exito_importacion'); ?>

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

    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; Lista de importaciones de resoluciones</h5>

            </div>
        </div>
        <div class="card-body">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('importar - rr')): ?>
            <div class=" input-group -sm p-2">
                <a href="" class="btn btn-sm btn-outline-info text-dark" data-toggle="modal" data-target="#nuevaImportacion"><i class="fas fa-upload"> Nueva importación</i></a>
            </div>
            <?php endif; ?>
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Mis importaciones</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gray-600 text-white">
                                <th>Nº</th>
                                <th class="text-right">Nº Importación</th>
                                <th>Identificador</th>
                                <th class="text-left">Usuario</th>
                                <th class="text-right">Fecha</th>
                                <th>Tipo</th>
                                <th>Gestión</th>
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
                                <th>Opcionesss</th>
                            </tr>
                            </tfoot>-->
                            <tbody>
                            <?php $j=1;?>
                            <?php $__currentLoopData = $importaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr style="font-size: 0.9em">
                                    <th class="border-right font-weight-bolder text-primary"><?php echo e($j); ?></th>
                                    <td class="text-right"><?php echo e($i['cod_imp']); ?></td>
                                    <td class="text-right"><?php echo e($i['imp_identificador']); ?></td>
                                    <td class="text-left"><?php echo e($i['imp_usuario']); ?></td>
                                    <td class="text-right"><?php echo e(date('d/m/Y H:i:s', strtotime($i['imp_fecha']))); ?></td>
                                    <td><?php echo e($i['imp_tipo']); ?></td>
                                    <td><?php echo e($i['imp_gestion']); ?></td>
                                    <td class="text-right">
                                        <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#verImportacion" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("datos importacion resolucion/".$i['cod_imp'])); ?>','panel_ver_importacion')">
                                            <i class="fas fa-eye"></i>
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
    </div>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('importar - rr')): ?>
    <!--===========================MODAL NUEVA IMPORTACION===================-->
    <div class="modal fade" id="nuevaImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form action="<?php echo e(url('verificar importacion res/')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
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
                                            <th class="text-dark" style="font-size: 12px">Columnas del archivo:</th>
                                            <td>
                                                <span class="text-danger font-italic font-weight-bold" style="font-size: 12px">[AÑO, CODIGO, OTRO_CODIGO, DESCRIPTOR, AUTORIDAD, AUTORIDAD2, FECHA, NUMERO, REFERENCIA, NOMBRE, TOMO, CONSIDERANDO, OBSERVACION, RESUELVE, VISTOS, TIPO]</span>
                                            </td>
                                        </tr>
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

    <!--===========================END ==============================-->
    <?php endif; ?>
    <!--===========================MODAL VER IMPORTACION===================-->
    <div class="modal fade" id="verImportacion" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Importación</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="shadow-sm rounded p-2">
                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Datos de la importación</h5>
                        </div>
                        <div id="panel_ver_importacion">

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--===========================END ==============================-->
    <!-- =============================== ====================-->
    <script>
        function cargarDatos(ruta,panel){
            var link="<?php echo e(url('/')); ?>"+"/"+ruta;
            $.ajax({
                url: link,
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

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/resoluciones/importacion/lista_importaciones_res.blade.php ENDPATH**/ ?>