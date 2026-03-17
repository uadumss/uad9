<?php $__env->startSection('contenido'); ?>
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
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; LEGALIZACIONES</h5>
            </div>
        </div>
        <div class="card-body">

            <div class="input-group">
                <div class="float-left ">
                    <div class="input-group">
                        <span class="text-dark font-weight-bold pt-1" style="font-size: .9em;"> Buscar fecha :&nbsp; &nbsp;</span>
                        <input class="form-control form-control-sm" type="date" name="fecha" onchange="$(location).attr('href','<?php echo e(url("listar tramite legalizacion/")); ?>'+'/'+this.value);" />
                    </div>
                </div>&nbsp;&nbsp;|&nbsp;&nbsp;
            </div>


            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Reportes y estadísticas</h5>
                    </div>
                    <hr class="sidebar-divider">
                    <div>
                        <ul>
                            <li><a href="#" data-toggle="modal" data-target="#reporte" onclick="cargarDatos('<?php echo e(url('modal reporte servicios/personal')); ?>','panel_reporte')">1. Reporte mediante datos personales</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#reporte" onclick="cargarDatos('<?php echo e(url('modal reporte servicios/general')); ?>','panel_reporte')">2. Reporte general de tr&aacute;mites</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#reporte" onclick="cargarDatos('<?php echo e(url('modal reporte servicios/estadistico')); ?>','panel_reporte')">3. Reporte Estadistico</a></li>


                                <li><a href="#" data-toggle="modal" data-target="#reporte" onclick="cargarDatos('<?php echo e(url('modal reporte servicios/listas')); ?>','panel_reporte')">4. Reporte en PDF de trámites mediante fecha </a></li>
                                <li><a href="#" data-toggle="modal" data-target="#reporte" onclick="cargarDatos('<?php echo e(url('modal reporte servicios/entregas')); ?>','panel_reporte')">5. Reporte en PDF de entrega de trámites</a></li>

                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade"  id="reporte" role="dialog"  aria-hidden="false" >
        <div class="modal-dialog modal-xl" role="document" id="panel_reporte">

        </div>
    </div>
    <!--===========================END===================-->
    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" style="z-index: 1500 " id="detalle" role="dialog"  aria-hidden="false" >
        <div class="modal-dialog modal-xl" role="document" id="panel_detalle">

        </div>
    </div>
    <!--===========================END===================-->

    <script>
        function cargarDatosPersonales(ci){
            var link="<?php echo e(url('datos_per/')); ?>"+"/"+ci;
            $.ajax({
                url: link,
                type: 'GET',
                success: function (resp) {
                    if(resp=="No"){
                        $('#apellido').val('');
                        $('#nombre').val('');
                    }else{
                        var res=JSON.parse(resp);
                        $('#apellido').val(res['per_apellido']);
                        $('#nombre').val(res['per_nombre']);
                    }
                },
                error: function () {
                    $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('marco/pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/reporte/l_reportes.blade.php ENDPATH**/ ?>