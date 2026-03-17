
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-folder-open"></i>&nbsp;&nbsp;CONSEJO - <?php echo e(strtoupper($tipo)); ?></h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: 12px;">
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

            <div id="panel_frente">
                        <span class="text-dark font-italic">
                                <span>CONSEJO : </span>
                                <span class="font-weight-bold"><?php echo e(strtoupper($tipo)); ?></span> &nbsp; | &nbsp;
                                <span>Facultad : </span>
                                <span class="font-weight-bold"><?php echo e($facultad->fac_nombre); ?></span> &nbsp; | &nbsp;
                                <?php if(isset($carrera) && $carrera): ?>
                                    <span>Carrera : </span>
                                    <span class="font-weight-bold text-primary"><?php echo e($carrera->car_nombre); ?></span>
                                <?php endif; ?>
                        </span>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-5">
                            <span class="text-primary font-weight-bold text-center">* Lista de frentes universitarios DOCENTES</span>
                        <div style="height: 280px" class="overflow-auto">
                        <!--==========LISTA FRENTES DOCENTES===============-->

                            <table class="table table-sm">
                                <tr>
                                    <th>N°</th>
                                    <th>Nombre</th>
                                    <th>Periodo</th>

                                    <th>Opciones</th>
                                </tr>
                                <?php $i=0;?>
                                <?php $__currentLoopData = $frente_d; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($i+=1); ?></td>
                                        <td class="text-dark font-weight-bold"><?php echo e($f->fre_nombre." "); ?>

                                            <?php if($f->fre_vigente=='t'): ?>
                                                <span class="text-success font-weight-bold" title="Vigente"><i class="fas fa-check-circle"></i></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($f->fre_fecha_inicio!=''): ?>
                                                <?php echo e(date('d/m/Y',strtotime($f->fre_fecha_inicio))); ?>

                                            <?php endif; ?>
                                            <?php if($f->fre_fecha_fin!=''): ?>
                                                <?php echo e(" - ".date('d/m/Y',strtotime($f->fre_fecha_fin))); ?>

                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($tipo=='hcu'){$cod=$facultad->cod_fac;}else{$cod=$carrera->cod_car;}?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar frente - cla')): ?>
                                                 <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("fe_frente/".$tipo."/".$cod."/".$f->cod_fre); ?>','panel_consejeros')">
                                                    <i class="fas fa-arrow-circle-right text-primary"></i></a>
                                            <?php endif; ?>
                                                <a class="btn btn-sm btn-light btn-circle" onclick="cargarDatos('<?php echo e("lista frente consejeros/".$f->cod_fre); ?>','panel_datos_frente')">
                                                <i class="fas fa-list text-success"></i></a>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar frente - cla')): ?>
                                                <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("f_eli_frente/".$f->cod_fre); ?>','panel_consejeros')">
                                                    <i class="fas fa-trash-alt text-danger"></i></a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                        </div>
                        <!-- ==========LISTA FRENTES ESTUDIANTILES===============-->
                        <br/>

                            <span class="text-primary font-weight-bold">Lista de frentes universitarios ESTUDIANTILES</span>

                        <div style="height: 280px" class="overflow-auto">

                            <hr class="sidebar-divider"/>
                            <div>
                                <table class="table table-sm">
                                    <tr>
                                        <th>N°</th>
                                        <th>Nombre</th>
                                        <th>Periodo</th>
                                        <th>Opciones</th>
                                    </tr>
                                    <?php $i=0;?>
                                    <?php $__currentLoopData = $frente_e; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($i+=1); ?></td>
                                            <td class="text-dark font-weight-bold"><?php echo e($f->fre_nombre." "); ?>

                                                <?php if($f->fre_vigente=='t'): ?>
                                                    <span class="text-success font-weight-bold" title="Vigente"><i class="fas fa-check-circle"></i></span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($f->fre_fecha_inicio!=''): ?>
                                                    <?php echo e(date('d/m/y',strtotime($f->fre_fecha_inicio))); ?>

                                                <?php endif; ?>
                                                <?php if($f->fre_fecha_fin!=''): ?>
                                                    <?php echo e(" - ".date('d/m/Y',strtotime($f->fre_fecha_fin))); ?>

                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                    <?php if($tipo=='hcu'){$cod=$facultad->cod_fac;}else{$cod=$carrera->cod_car;}?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('editar frente - cla')): ?>
                                                    <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("fe_frente/".$tipo."/".$cod."/".$f->cod_fre); ?>','panel_consejeros')">
                                                        <i class="fas fa-arrow-circle-right text-primary"></i></a>
                                                <?php endif; ?>
                                                    <a class="btn btn-sm btn-light btn-circle" onclick="cargarDatos('<?php echo e("lista frente consejeros/".$f->cod_fre); ?>','panel_datos_frente')">
                                                        <i class="fas fa-list text-success"></i></a>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar frente - cla')): ?>
                                                    <a class="btn btn-sm btn-light btn-circle" data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('<?php echo e("f_eli_frente/".$f->cod_fre); ?>','panel_consejeros')">
                                                        <i class="fas fa-trash-alt text-danger"></i></a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 shadow border rounded">
                        <br/>
                        <div id="panel_datos_frente">

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear frente - cla')): ?>
                <?php if($tipo=='hcu'): ?>
                    <button class="btn btn-primary btn-sm" type="button" data-target="#consejeros" data-toggle="modal" onclick="cargarDatos('<?php echo e("fe_frente/".$tipo."/".$facultad->cod_fac."/0"); ?>','panel_consejeros')">+ Frente</button>
                <?php else: ?>
                    <button class="btn btn-primary btn-sm" type="button" data-target="#consejeros" data-toggle="modal" onclick="cargarDatos('<?php echo e("fe_frente/".$tipo."/".$carrera->cod_car."/0"); ?>','panel_consejeros')">+ Frente</button>
                <?php endif; ?>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcu/fu_hcu.blade.php ENDPATH**/ ?>