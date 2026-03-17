<div class="table-responsive">
    <div id="" class="col-md-12">
        <br/>
        <table class="table table-sm table-hover" id="dataTable2" width="100%" cellspacing="0" style="font-size: smaller">
            <thead>
            <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                <th>Nº</th>
                <th class="text-left">Tipo</th>
                <th class="text-left">Nro. Trámite</th>
                <th>Trámite</th>
                <th>Nombres</th>
                <th class="text-left">Fecha solicitud</th>
                <th class="text-center">Opciones</th>
            </tr>
            </thead>
            <tbody id="cuerpo">
            <?php $i=1;?>
            <?php $__currentLoopData = $noatentado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <th class="border-right font-weight-bolder"><?php echo e($i); ?></th>
                    <td>
                        <span class="font-weight-bold rounded pl-2 pr-2 bg-primary text-white" style="font-size: 0.75em">NO-ATENTADO</span>
                    </td>
                    <td class="text-right"><span class="text-primary font-weight-bold"><?php echo e($t->dtra_numero_tramite); ?></span>/<?php echo e($t->dtra_gestion_tramite); ?></td>
                    <td class=""><?php echo e($t->tre_nombre); ?></td>
                    <td><span class="font-weight-bold text-dark" style="font-size: 12px; font-family: 'Times New Roman'">
                                                        <?php echo \App\Http\Controllers\Noatentado\TramiteNoAtentadoController::listaCandidatos($t->cod_dtra)?>
                                                    </span>
                    </td>
                    <td class="text-right"><?php echo e(date('d/m/Y',strtotime($t->dtra_fecha_registro))); ?></td>
                    <td class="text-right">
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar tramite - noa')): ?>
                            <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal"
                               onclick="cargarDatos('<?php echo e(url("formulario entrega tramite noatentado/$t->cod_dtra")); ?>','panel_traleg')"
                               title="Entregar trámite"> <i class="fas fa-hand-point-right"></i>
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
<script src="<?php echo e(url('vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(url('vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
<!-- Page level custom scripts -->
<script src="<?php echo e(url('js/demo/datatables-demo.js')); ?>"></script>

<script>
    $('#dataTable2').dataTable( {
        "pageLength": 500
    });
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/entrega/l_entrega_noa_ajax.blade.php ENDPATH**/ ?>