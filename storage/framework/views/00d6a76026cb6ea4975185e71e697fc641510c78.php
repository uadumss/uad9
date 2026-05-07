
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
    <?php if(count($errors)>0): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($e); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-chart-area"></i>&nbsp;&nbsp;REPORTES DE RESOLUCIONES</h5>
                <div>
                    <a href="<?php echo e(url('exportar reportes resoluciones excel').'?anio='.($anioSeleccionado ?? '').'&tipo='.($tipoSeleccionado ?? '')); ?>" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                        <i class="fas fa-file-excel"></i> Descargar Excel</a>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-target="#nuevoReporte" data-toggle="modal">
                        <i class="fas fa-plus"></i> Nuevo reporte</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div>
                <div class="">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-4 rounded shadow">
                            <h6 class="text-white text-center">Resoluciones</h6>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="filtroAño" class="font-weight-bold">Filtrar por Año:</label>
                                <select class="form-control form-control-sm" id="filtroAño" onchange="aplicarFiltros()">
                                    <option value="todos" <?php echo e(($anioSeleccionado ?? '') === 'todos' ? 'selected' : ''); ?>>Todos los años</option>
                                    <?php $__currentLoopData = $años; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $año): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($año); ?>" <?php echo e((string)($anioSeleccionado ?? '') === (string)$año ? 'selected' : ''); ?>><?php echo e($año); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filtroTipo" class="font-weight-bold">Filtrar por Tipo de Resolución:</label>
                                <select class="form-control form-control-sm" id="filtroTipo" onchange="aplicarFiltros()">
                                    <option value="todos" <?php echo e(($tipoSeleccionado ?? '') === 'todos' ? 'selected' : ''); ?>>Todos los tipos</option>
                                    <?php $__currentLoopData = $tipos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tipo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($tipo); ?>" <?php echo e((string)($tipoSeleccionado ?? '') === (string)$tipo ? 'selected' : ''); ?>><?php echo e($tipo); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div class="table-responsive" id="panel_reportes">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th>NUM</th>
                                    <th>Fecha</th>
                                    <th>Referencia</th>
                                    <th>Nombre</th>
                                    <th>Descriptor</th>
                                    <th>Tipo de Resolución</th>
                                    <th>Código</th>
                                    <th>Año</th>
                                    <th>Tomo</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $resoluciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resolucion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr style="font-size: 0.9em" data-año="<?php echo e($resolucion->tom_gestion ?? $resolucion->res_gestion ?? ''); ?>">
                                    <td class="text-center"><?php echo e($resolucion->res_numero ?? ''); ?></td>
                                    <td><?php echo e($resolucion->res_fecha ? date('d/m/Y', strtotime($resolucion->res_fecha)) : ''); ?></td>
                                    <td><?php echo e($resolucion->res_desc ?? ''); ?></td>
                                    <td><?php echo e($resolucion->res_objeto ?? ''); ?></td>
                                    <td><?php echo e($resolucion->res_tema ?? ''); ?></td>
                                    <td><?php echo e(strtoupper($resolucion->res_tipo ?? '')); ?></td>
                                    <td><?php echo e($resolucion->codigos ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($resolucion->tom_gestion ?? $resolucion->res_gestion ?? ''); ?></td>
                                    <td class="text-center"><?php echo e($resolucion->tom_numero ?? ''); ?></td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-circle btn-light btn-sm text-info" data-toggle="modal" data-target="#verDatos"
                                           onclick="verDatos('<?php echo e(url('ver datos resolucion/'.$resolucion->cod_res)); ?>','p_detalle')" title="Ver detalle de la resolución">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if($resolucion->res_pdf ?? false): ?>
                                            <a href="<?php echo e(url('pdf resolucion/'.$resolucion->cod_res)); ?>" target="_blank" class="btn btn-circle btn-light btn-sm text-danger" title="Ver PDF">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted">
                                        <i class="fas fa-inbox"></i><br/>
                                        No hay resoluciones registradas
                                    </td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                            <div class="mt-3">
                                <?php echo e($resoluciones->links()); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--==========================MODAL NUEVO REPORTE==============-->
    <div class="modal fade" id="nuevoReporte" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_nuevo_reporte">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel">
                        <i class="fas fa-plus"></i>&nbsp;&nbsp;Nuevo Reporte
                    </h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario de Reporte</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <form action="<?php echo e(url('guardar reporte resolucion')); ?>" method="POST" id="form_reporte">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha" class="font-weight-bold">Fecha:</label>
                                    <input type="date" class="form-control form-control-sm" name="fecha" id="fecha" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="referencia" class="font-weight-bold">Referencia:</label>
                                    <input type="text" class="form-control form-control-sm" name="referencia" id="referencia" placeholder="Ej: Reporte mensual" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre" class="font-weight-bold">Nombre:</label>
                                    <input type="text" class="form-control form-control-sm" name="nombre" id="nombre" placeholder="Nombre del responsable" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descriptor" class="font-weight-bold">Descriptor:</label>
                                    <input type="text" class="form-control form-control-sm" name="descriptor" id="descriptor" placeholder="Ej: Transcriptor, Designaciones" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="codigo" class="font-weight-bold">Código:</label>
                                    <input type="text" class="form-control form-control-sm" name="codigo" id="codigo" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="anio" class="font-weight-bold">Año:</label>
                                    <select class="form-control form-control-sm" name="anio" id="anio" required>
                                        <option value="">Seleccione...</option>
                                        <?php $año=date('Y');?>
                                        <?php for($i=$año;$i>1927;$i--): ?>
                                            <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tomo" class="font-weight-bold">Tomo:</label>
                                    <input type="text" class="form-control form-control-sm" name="tomo" id="tomo" required>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary btn-sm" type="button" onclick="$('#form_reporte').submit()">Guardar</button>
                </div>
            </div>
        </div>
    </div>
    <!--============================END======================-->

    <!--=================================MODAL VER RESOLUCION ============================-->
    <div class="modal fade" id="verDatos" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-xl" role="document" id="p_detalle">

        </div>
    </div>
    <!--================================ END ===============================-->

    <script>
        function verDatos(url,panel){
            $.ajax({
                url:url,
                type:'GET',
                data:'',
                success:function (resp) {
                    $('#'+panel).html(resp);
                },
                error:function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }

        function aplicarFiltros(){
            const año = document.getElementById('filtroAño').value;
            const tipo = document.getElementById('filtroTipo').value;
            const destino = "<?php echo e(url('reportes resoluciones')); ?>?anio=" + encodeURIComponent(año) + "&tipo=" + encodeURIComponent(tipo);
            window.location.href = destino;
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/resoluciones/reporte/lista_reportes_resolucion.blade.php ENDPATH**/ ?>