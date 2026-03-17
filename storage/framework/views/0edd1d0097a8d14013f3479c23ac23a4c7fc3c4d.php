<span class="font-weight-bold text-danger m-2">Resolución N° <?php echo e($resolucion->res_numero."/".$resolucion->res_gestion); ?></span>
<a href="" class="btn btn-circle btn-light btn-sm text-danger float-right border" data-toggle="modal" data-target="#Modal2"
   onclick="cargarDatos('<?php echo e(url('ver datos resolucion/'.$resolucion->cod_res)); ?>','panel_modal2')" title="Ver detalle de la resolución"> <i class="fas fa-file-pdf"></i>
</a>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/sancionado/panel_resolucion.blade.php ENDPATH**/ ?>