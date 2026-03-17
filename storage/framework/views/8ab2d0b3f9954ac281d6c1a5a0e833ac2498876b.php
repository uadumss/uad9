<?php $__env->startSection('contenido'); ?>
    <div class="row text-center centrar_bloque justify-content-center p-5">
        <div class="col-md-3 d-none d-lg-block bg-primary p-5">
            <a>
                <i class="fa fa-file-alt text-white" style="font-size: 10em"></i>
            </a>
        </div>
        <div class="col-md-5 border">
            <div class="p-5">
                <div class="text-left">
                    Estimado <span class="text-dark font-weight-bold"><?php echo e(Auth::user()->name); ?>.</span>
                </div>
                <br/>
                <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Bienvenidos al SID!</h1>
                </div>
                <hr>
                <div>
                    Sistema de información digital
                    <br/> ARCHIVOS - UMSS
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/inicio.blade.php ENDPATH**/ ?>