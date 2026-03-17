<script src="<?php echo e(asset('js/tinymce/tinymce.min.js')); ?>"></script>
<?php if(Session::has('exito')): ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold"><?php echo session('exito'); ?></span>
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
                <li class="font-weight-bold te"><?php echo e($e); ?> - </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
<?php endif; ?>

<div>
    <form action="<?php echo e(url('g_glosa')); ?>" id="form_modelo" enctype="multipart/form-data" method="POST">
        <?php echo csrf_field(); ?>
        <?php if($cod_glo=='0'): ?>
            <table class="col-md-11">
                <tr>
                    <th class="text-dark ">Nombre :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <input type="text" name="titulo" class="form-control border-0"/>
                    </td>
                </tr>
                <tr>
                    <th class="text-dark">Glosa :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <textarea class="form-control border-0" rows="15" name="glosa" id="cuerpo_glosa"></textarea>
                    </td>
                </tr>
            </table>
        <?php else: ?>
            <table class="col-md-11">
                <tr>
                    <th class="text-dark ">Nombre :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <input type="text" name="titulo" class="form-control border-0" value="<?php echo e($glosa->glo_titulo); ?>"/>
                    </td>
                </tr>
                <tr>
                    <th class="text-dark">Glosa :</th>
                </tr>
                <tr>
                    <td class="border-bottom border-dark">
                        <textarea class="form-control border-0" rows="15" name="glosa" id="cuerpo_glosa"><?php echo e($glosa->glo_glosa); ?></textarea>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="cg" value="<?php echo e($glosa->cod_glo); ?>">
        <?php endif; ?>
        <input type="hidden" name="ct" value="<?php echo e($cod_tre); ?>">
    </form>
    <?php if($cod_glo=='0'): ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear glosa - srv')): ?>
            <button class="btn btn-sm btn-primary" onclick="enviar('form_modelo','<?php echo e(url('g_glosa')); ?>','panel_tramite')">Guardar</button>
        <?php endif; ?>
    <?php else: ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar glosa - srv')): ?>
            <button class="btn btn-sm btn-primary" onclick="enviar('form_modelo','<?php echo e(url('g_glosa')); ?>','panel_tramite')">Guardar</button>
        <?php endif; ?>
    <?php endif; ?>
</div>
<script type="text/javascript">
    tinymce.init({
        selector: '#cuerpo_glosa',
        setup: function (editor) {
            editor.on('change', function () {
                editor.save();
            });
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tramite/fe_modelo.blade.php ENDPATH**/ ?>