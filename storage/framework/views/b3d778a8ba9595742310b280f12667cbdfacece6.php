<div>
    <div class="table-responsive">
        <div id="panel_tabla_tramites" class="col-md-12">
            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                <thead>
                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                    <th>Nº</th>
                    <th class="text-left">Nro. Trámite</th>
                    <th>Trámite</th>
                    <th>Nombres</th>
                    <th class="text-left">Fecha solicitud</th>
                    <th class="text-center">Opciones</th>
                    <th class="text-center">Entrega</th>
                </tr>
                </thead>
                <tbody id="cuerpo">
                <?php $i=1;?>
                <?php $__currentLoopData = $tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <th class="border-right font-weight-bolder"><?php echo e($i); ?></th>
                        <td class="text-right"><span class="text-primary font-weight-bold"><?php echo e($t->dtra_numero_tramite); ?></span>/<?php echo e($t->dtra_gestion_tramite); ?></td>
                        <td class=""><?php echo e($t->tre_nombre); ?></td>
                        <td><span class="font-weight-bold text-dark" style="font-size: 12px; font-family: 'Times New Roman'">
                                                        <?php echo \App\Http\Controllers\Noatentado\TramiteNoAtentadoController::listaCandidatos($t->cod_dtra)?>
                                                    </span>
                        </td>
                        <td class="text-right"><?php echo e(date('d/m/Y',strtotime($t->dtra_fecha_registro))); ?></td>
                        <td class="text-right">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar tramite - noa')): ?>
                                <a href="#Noatentado" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#Noatentado"
                                   onclick="cargarDatos('<?php echo e(url("editar tramite convocatoria/".$t->cod_con."/".$t->cod_dtra)); ?>','panel_noatentado')"
                                   title="Editar trámite"><i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>

                            <?php if($t->dtra_generado!='t'): ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('generar glosa - noa')): ?>
                                    <a href="#Noatentado" onclick="cargarDatos('<?php echo e(url("formulario glosa noatentado/$t->cod_dtra")); ?>','panel_noatentado')"
                                       class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal"
                                       title="Generar glosa"> <i class="fas fa-file-pdf"></i>
                                    </a>
                                <?php endif; ?>
                            <?php else: ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('imprimir pdf - noa')): ?>
                                    <a href="<?php echo e(url('generar pdf noatentado/'.$t->cod_dtra)); ?>" target="No-atentado"
                                       class="btn btn-primary btn-sm btn-circle btn-light text-danger" title="Generar glosa"> <i class="fas fa-file-pdf"></i>
                                    </a>
                                <?php endif; ?>
                            <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('rehacer tramite - noa')): ?>
                                    <a href="#" class="btn btn-light btn-circle btn-sm text-info" data-target="#Noatentado" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("formulario corregir tramite noatentado/$t->cod_dtra")); ?>','panel_noatentado')" title="Corregir tramite">
                                        <i class="fas fa-arrow-circle-left"></i>
                                    </a>
                                <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar tramite - noa')): ?>
                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#Noatentado" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("formulario eliminar tramite noatentado/$t->cod_dtra")); ?>','panel_noatentado')"
                                   title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                </a>
                            <?php endif; ?>

                        </td>
                        <td class="text-right">
                            <?php if($t->dtra_entregado=='a' && $t->dtra_entregado!='t' && $t->dtra_entregado!='c'): ?>
                                <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                <span class="font-weight-bold text-success font-italic">Apoderado </span>
                            <?php else: ?>
                                <?php if($t->dtra_entregado=='c'): ?>
                                    <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                    <span class="font-weight-bold text-success font-italic">Apoderado </span>
                                <?php else: ?>
                                    <?php if($t->dtra_entregado=='t'): ?>
                                        <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                    <?php endif; ?>
                                <?php endif; ?>
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
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/tramite/l_tramite_convocatoria_tabla.blade.php ENDPATH**/ ?>