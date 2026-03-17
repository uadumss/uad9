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
            <span class="font-weight-bold text-dark"><?php echo session('error'); ?></span>
        </div>
    <?php endif; ?>
    <?php if(count($errors)>0): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($e); ?> - </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>
    <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-user-check"></i>&nbsp;Persona</h5>
                </div>
            </div>
        <div class="card-body">
            <div>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('corregir datos personales  ci - adm')): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-toggle="tab" data-target="#panel_persona" type="button" role="tab"
                                aria-controls="home" aria-selected="true" onclick="cargarDatos('<?php echo e(url('corregir por ci')); ?>','panel_persona')">Datos personales</button>
                    </li>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('corregir duplicados - adm')): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-toggle="tab" data-target="#panel_persona" type="button" role="tab"
                                aria-controls="profile" aria-selected="false" onclick="cargarDatos('<?php echo e(url('formulario duplicados')); ?>','panel_persona')">Duplicados</button>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <br/>
            <div id="panel_persona">

            </div>
        </div>
    </div>
    <div class="modal fade" id="Modal" style="z-index: 1500;" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document" id="panel_modal">

        </div>
    </div>
    <script>
        function cambiar(id,url){
            $('#panel').html("<br/><br/><div class='d-flex justify-content-center'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
            var i=0;
            for(i=0;i<10;i++){
                $('#'+i).removeClass('active');
            }
            $('#'+id).addClass('active');
            if(id==0){
                $('#panel').html('');
            }else{
                $.ajax({
                    url: url,
                    type: 'GET',
                    data:'',
                    success: function (resp) {
                        $('#panel').html(resp);
                    },
                    error: function () {
                        alert('No se puede ejecutar la petición');
                    }
                });
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/persona/persona.blade.php ENDPATH**/ ?>