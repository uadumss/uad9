
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-folder-open"></i>&nbsp;&nbsp;Consejo</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <?php if(Session::has('exitof')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('exitof'); ?>

                </div>
            <?php endif; ?>
            <?php if(Session::has('errorf')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('errorf'); ?>

                </div>
            <?php endif; ?>

            <div id="panel_frente" style="font-size: 14px;">
                        <span class="text-dark font-italic">
                                <span>CONSEJO : </span>
                                <span class="font-weight-bold text-primary">HCF</span> &nbsp; | &nbsp;
                                <span>FACULTAD : </span>
                                <span class="font-weight-bold  text-primary"><?php echo e($facultad->fac_nombre); ?></span>&nbsp; | &nbsp;
                                <span>CARRERA : </span>
                                <span class="font-weight-bold text-primary"><?php echo e($carrera->car_nombre); ?></span>
                        </span>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-5 p-1 mr-5">
                        <div class="bg-primary centrar_bloque p-1 col-md-11 rounded shadow">
                            <h5 class="text-white text-center">Lista de consejos facultativos DOCENTES</h5>
                        </div>
                        <div style="height: 300px" class="overflow-auto">
                        <!--==========LISTA FRENTES DOCENTES===============-->

                            <table class="table table-sm">
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Periodo</th>
                                    <th>Estado</th>
                                    <th>Opciones</th>
                                </tr>
                                <?php $i=0;?>
                                <?php $__currentLoopData = $frente_d; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+=1); ?></td>
                                        <td class="text-dark font-weight-bold"><?php echo e($f->fre_nombre); ?></td>
                                        <td>
                                            <?php if($f->fre_fecha_inicio!=''): ?>
                                                <?php echo e(date('d/m/Y',strtotime($f->fre_fecha_inicio))); ?>

                                            <?php endif; ?>
                                            <?php if($f->fre_fecha_fin!=''): ?>
                                                <?php echo e(" - ".date('d/m/Y',strtotime($f->fre_fecha_fin))); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($f->fre_vigente=='t'): ?>
                                                <span class="bg-danger text-white font-weight-bold font-italic rounded pr-1 pl-1">Vigente</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("fe_frente/hcf/".$carrera->cod_car."/".$f->cod_fre); ?>','panel_consejeros')">
                                                <i class="fas fa-arrow-circle-right text-primary"></i></a>
                                            <a class="btn btn-sm btn-light btn-circle" onclick="cargarDatos('<?php echo e("lista frente consejeros/".$f->cod_fre); ?>','panel_datos_frente')">
                                                <i class="fas fa-list text-success"></i></a>
                                            <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("f_eli_frente/".$f->cod_fre); ?>','panel_consejeros')">
                                                <i class="fas fa-trash-alt text-danger"></i></a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                            </table>
                        </div>
                        <!-- ==========LISTA FRENTES ESTUDIANTILES===============-->
                        <br/>
                        <div class="bg-primary centrar_bloque p-1 col-md-11 rounded shadow">
                            <h5 class="text-white text-center">Lista de consejos facultativos ESTUDIANTILES</h5>
                        </div>
                        <div style="height: 300px" class="overflow-auto">

                            <hr class="sidebar-divider"/>
                            <div>
                                <table class="table table-sm">
                                    <tr>
                                        <th>N°</th>
                                        <th>Nombre</th>
                                        <th>Periodo</th>
                                        <th>Estado</th>
                                        <th>Opciones</th>
                                    </tr>
                                    <?php $i=0;?>
                                    <?php $__currentLoopData = $frente_e; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i+=1); ?></td>
                                            <td><?php echo e($f->fre_nombre); ?></td>
                                            <td>
                                                <?php if($f->fre_fecha_inicio!=''): ?>
                                                    <?php echo e(date('d/m/y',strtotime($f->fre_fecha_inicio))); ?>

                                                <?php endif; ?>
                                                <?php if($f->fre_fecha_fin!=''): ?>
                                                    <?php echo e(" - ".date('d/m/Y',strtotime($f->fre_fecha_fin))); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($f->fre_vigente=='t'): ?>
                                                    <span class="bg-danger text-white font-weight-bold font-italic rounded pr-1 pl-1">Vigente</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("fe_frente/hcf/".$carrera->cod_car."/".$f->cod_fre); ?>','panel_consejeros')">
                                                    <i class="fas fa-arrow-circle-right text-primary"></i></a>
                                                <a class="btn btn-sm btn-light btn-circle" onclick="cargarDatos('<?php echo e("lista frente consejeros/".$f->cod_fre); ?>','panel_datos_frente')">
                                                    <i class="fas fa-list text-success"></i></a>
                                                <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("f_eli_frente/".$f->cod_fre); ?>','panel_consejeros')">
                                                    <i class="fas fa-trash-alt text-danger"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 p-1 shadow border rounded">
                        <br/>
                        <div id="panel_datos_frente">

                        </div>

                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="button" data-target="#consejeros" data-toggle="modal" onclick="cargarDatos('<?php echo e("fe_frente/hcf/".$carrera->cod_car."/0"); ?>','panel_consejeros')">+ Frente</button>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcf/fu_hcf.blade.php ENDPATH**/ ?>