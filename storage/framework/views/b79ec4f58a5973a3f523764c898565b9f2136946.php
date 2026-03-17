<?php $__env->startSection('contenido'); ?>

        <a href="<?php echo e(url('lista importaciones resolucion/'.Auth::user()->id)); ?>" class="btn btn-outline-danger text-dark"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
        <br/>
        <br/>

<?php if(isset($fallas) && count($fallas)>0): ?>
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold">Ocurrió los siguientes errores :</span>
        <br/>
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

<?php $__env->stopSection(); ?>


<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/resoluciones/importacion/resultado_importacion_res.blade.php ENDPATH**/ ?>