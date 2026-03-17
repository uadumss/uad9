<?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> CORRECCION DE DUPLICADOS </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" style="font-size: smaller">
        <?php if(Session::has('exito')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold"><?php echo session('exito'); ?></span>
            </div>
        <?php endif; ?>
            <?php if(Session::has('error_modal')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold"><?php echo session('error_modal'); ?></span>
                </div>
            <?php endif; ?>
        <div class="bg-info centrar_bloque p-1 col-md-6 rounded shadow-lg">
            <h6 class="text-white text-center">Formulario de duplicados</h6>
        </div>
        <hr class="sidebar-divider"/>
                <div class="shadow-sm p-2 centrar_bloque">
                    <table class="table table-sm table-hover">
                        <tr>
                            <th>N°</th>
                            <th>CI</th>
                            <th>Nombre</th>
                            <th>Enlaces</th>
                            <th>Opciones</th>
                        </tr>
                        <?php $i=1;?>
                        <?php $__currentLoopData = $duplicados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($i); ?></td>
                                <th><?php echo e($d->per_ci); ?>

                                </th>
                                <td><?php echo e($d->id_per." - ".$d->per_apellido." ".$d->per_nombre); ?><br/>
                                    <span>
                                        <span class="font-weight-bold font-italic text-dark">Sistema:</span><span class="text-danger"><?php echo e($d->per_sistema); ?></span> &nbsp;|
                                        <span class="font-weight-bold font-italic text-dark">Creado:</span><span class="text-danger"><?php echo e(date('d/m/Y H:i:s',strtotime($d->created_at))); ?></span> &nbsp;|
                                        <span class="font-weight-bold font-italic text-dark">Actualizado:</span><span class="text-danger"><?php echo e(date('d/m/Y H:i:s',strtotime($d->updated_at))); ?></span> &nbsp;|
                                    </span>
                                    <?php if($tipo=='ci'): ?>
                                    <div class="input-group">
                                            <form id="form_corregir<?php echo e($i); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="input-group pt-2">
                                                    <span class="font-weight-bold text-dark font-italic pt-2">CI : </span> &nbsp;
                                                    <input type="text" class="form-control form-control-sm rounded" name="ci" value="<?php echo e($d->per_ci); ?>">&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark font-italic pt-2">Apellidos : &nbsp;</span><input type="text" class="form-control form-control-sm rounded" name="apellido" value="<?php echo e($d->per_apellido); ?>">&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark font-italic pt-2">Nombres : &nbsp;</span><input type="text" class="form-control form-control-sm rounded" name="nombre" value="<?php echo e($d->per_nombre); ?>">&nbsp;&nbsp;
                                                </div>
                                                <input type="hidden" name="ip" value="<?php echo e($d->id_per); ?>">
                                                <input type="hidden" name="tipo" value="<?php echo e($tipo); ?>">
                                            </form>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo \App\Models\Persona::getDatosTablas($d->id_per)?></td>

                                <td>
                                    <?php if($tipo=='ci'): ?>
                                        <a href="#" class="btn btn-sm btn-success shadow-lg font-weight-bold" onclick="enviar('form_corregir<?php echo e($i); ?>','<?php echo e(url('corregir duplicados ci')); ?>','nombre'+$('#fila').val());
                                            $('#fila'+$('#fila').val()).addClass('alert-info');$('#Modal').modal('hide');">Corregir</a>
                                    <?php else: ?>
                                        <form id="form_duplicado<?php echo e($i); ?>">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="ip" value="<?php echo e($d->id_per); ?>">
                                            <input type="hidden" name="ci" value="<?php echo e($d->per_ci); ?>">
                                            <input type="hidden" name="tipo" value="<?php echo e($tipo); ?>">
                                        </form>
                                        <a href="#" class="btn btn-sm btn-danger shadow-lg font-weight-bold"
                                           onclick="enviar('form_duplicado<?php echo e($i); ?>','<?php echo e(url('corregir duplicados')); ?>','nombre'+$('#fila').val());$('#fila'+$('#fila').val()).addClass('alert-info');
                                           $('#Modal').modal('hide');
                                           ">Mantener</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php $i++;?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/persona/duplicado/fe_duplicado.blade.php ENDPATH**/ ?>