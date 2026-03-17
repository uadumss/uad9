<table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
    <thead>
    <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
        <th>Nº</th>
        <th class="text-left">Tipo</th>
        <th class="text-left">CI</th>
        <th class="text-left">Nombre</th>
        <th class="text-left">Número Atención</th>
        <th class="text-left">Número trámite</th>
        <th class="text-left">Fecha solicitud</th>
        <th class="text-left">Fecha firma</th>
        <th class="text-center">Entrega</th>
    </tr>
    </thead>
    <tbody id="cuerpo">
    <?php $i=1;?>
    <?php $__currentLoopData = $l_entregas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <tr>

            <th class="border-right font-weight-bolder">
                <span class="text-primary"><?php echo e($i); ?></span>
            </th>
            <td>
                <?php   $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                ?>
                <span class="font-weight-bold rounded pl-2 pr-2 <?php echo e($tipo_tramite[$t->dtra_tipo.'C']); ?>" style="font-size: 0.75em">
                                                <?php echo e($tipo_tramite[$t->dtra_tipo]); ?>

                                            </span>
            </td>
            <td class="text-left"><?php echo e($t->per_ci); ?></td>
            <td class="text-left"><?php echo e($t->per_apellido." ".$t->per_nombre); ?>

                <?php if($t->tra_tipo_apoderado=='p'): ?>
                    <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                <?php else: ?>
                    <?php if($t->tra_tipo_apoderado=='d'): ?>
                        <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
            <td class="text-left"><?php echo e($t->tra_numero); ?></td>
            <td><?php echo e($t->dtra_numero_tramite." / ".$t->dtra_gestion_tramite); ?></td>
            <td class="text-right"><?php echo e(date('d/m/Y',strtotime($t->tra_fecha_solicitud))); ?></td>

            <td class="text-right"><?php if($t->dtra_fecha_firma!=''){echo date('d/m/Y',strtotime($t->dtra_fecha_firma));} ?> </td>
            <td class="text-right">
                <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("panel entrega legalizacion/$t->cod_tra")); ?>','panel_traleg')"
                   title="Entregar legalizaciones"> <i class="fas fa-hand-point-right"></i></a>
            </td>
        </tr>
        <?php $i++;?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<script src="<?php echo e(url('vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(url('vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
<!-- Page level custom scripts -->
<script src="<?php echo e(url('js/demo/datatables-demo.js')); ?>"></script>

<script>
    $('#dataTable').dataTable( {
        "pageLength": 500
    });
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/entrega/l_entregas_ajax.blade.php ENDPATH**/ ?>