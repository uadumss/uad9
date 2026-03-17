
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


            <div class="row">
                <div class="col-md-3" style="font-size: 0.8em;">
                    <span class="text-right"><span class="font-italic font-weight-bold text-dark">Usuario : </span><a href="#" class="text-primary"> <?php echo e($usuario['name']); ?> </a></span><br/>
                </div>
            </div>
            <div>
            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">

                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Lista de conexiones</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr class="bg-gradient-light text-dark">
                                <th>Nº</th>
                                <th>Identificador</th>
                                <th class="text-left">Conexion</th>
                                <th class="text-left">Desconexion</th>
                                <th class="text-left">Host</th>
                                <th class="text-right">Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=1;?>
                            <?php $__currentLoopData = $conexion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <th class="border-right font-weight-bolder text-primary"><?php echo e($i); ?></th>
                                    <td><?php echo e($c['cod_bit']); ?></td>
                                    <td><?php if($c['bit_inicio']!=''): ?>
                                            <?php echo e(date('d/m/Y H:i:s',strtotime($c['bit_inicio']))); ?>

                                        <?php endif; ?>
                                    </td>
                                    <td><?php if($c['bit_fin']!=''): ?>
                                            <?php echo e(date('d/m/Y H:i:s',strtotime($c['bit_fin']))); ?></td>
                                        <?php endif; ?>
                                    <td><?php echo e($c['bit_host']); ?></td>
                                    <td class="text-right">
                                        <a href="#" onclick="cargarDatos('<?php echo e(url('lista acciones/'.$c['cod_bit'])); ?>','panel')" class="btn btn-light btn-circle btn-sm text-danger"  >
                                            <i class="fas fa-list-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $i++;?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
                <script>
                    $('#dataTable').dataTable( {
                        "pageLength": 500
                    });
                </script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/lista_conexiones.blade.php ENDPATH**/ ?>