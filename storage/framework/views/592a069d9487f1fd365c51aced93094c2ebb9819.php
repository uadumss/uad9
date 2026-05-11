<div class="modal-dialog modal-lg" role="document">
        <form action="<?php echo e(url('guardarTarea')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"> Tarea</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">

                 <?php if($cod_tar!=0): ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para editar Tarea</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                <table class="col-md-12">
                    <tr>
                        <th class="text-dark text-right font-italic" width=" 200">Nombre Tarea : </th>
                        <td class="border-bottom border-dark">
                        <input class="form-control form-control-sm border-0" placeholder="Ingrese el nombre de la actividad" required
                                   name="nombre" value="<?php echo e($tarea['tar_nombre']); ?>" /></td>
                    </tr>
                    <tr>
                        <th class="text-dark text-right font-italic">Fecha Inicio : </th>
                        <td class="border-bottom border-dark">
                            <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi" value="<?php echo e($tarea['tar_fi']); ?>"/></td>

                    </tr>
                    <tr>
                        <th class="text-dark text-right font-italic">Fecha Conclusión : </th>
                        <td class="border-bottom border-dark">
                            <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"
                                   value="<?php echo e($tarea['tar_ff']); ?>"/></td>
                    </tr>
                    <?php if($tarea->tar_cotidiano!='t'): ?>
                    <tr>
                        <th class="text-dark text-right font-italic">Porcentaje </th>
                        <td class="border-bottom border-dark">
                            <input type="number" name="por" class="form-control form-control-sm border-0" min="0" max="100" value="<?php echo e($tarea['tar_por']); ?>" />
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th class="text-dark text-right font-italic">Descripción: </th>
                        <td class="border-bottom border-dark">
                            <textarea class="form-control form-control-sm border-0" rows="5" name="desc"><?php echo e($tarea['tar_desc']); ?></textarea>
                        </td>
                    </tr>
                </table>
                    <input type="hidden" name="ct" value="<?php echo e($tarea->cod_tar); ?>">
                    <input type="hidden" name="ca" value="<?php echo e($tarea->cod_act); ?>">
                <?php else: ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                        <h6 class="text-white text-center">Formulario para Nueva Tarea</h6>
                    </div>
                    <hr class="sidebar-divider"/>
                    <table class="col-md-12">
                        <tr>
                            <th class="text-dark text-right font-italic" width=" 200">Nombre Tarea: </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese el nombre de la tarea" required
                                       name="nombre"/></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Fecha Inicio : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de inicio" type="date" name="fi"/></td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Fecha Conclusión : </th>
                            <td class="border-bottom border-dark">
                                <input class="form-control form-control-sm border-0" placeholder="Ingrese fecha de conclusion" type="date" name="ff"/></td>
                        </tr>
                        <?php if($actividad->act_cotidiano!='t'): ?>
                        <tr>
                            <th class="text-dark text-right font-italic">Porcentaje </th>
                            <td class="border-bottom border-dark">
                                <input type="number" name="por" class="form-control form-control-sm border-0" min="0" max="100" value="0" />
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th class="text-dark text-right font-italic">Designado a:</th>
                            <td class="border-bottom border-dark">
                                <select class="custom-select-sm custom-select border-0" placeholder="Elija un nombre" name="fun">
                                    <?php $__currentLoopData = $p_acargo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($r->id); ?>"><?php echo e($r->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-dark text-right font-italic">Descripción: </th>
                            <td class="border-bottom border-dark">
                                <textarea class="form-control border-0" rows="5" name="desc" id="desc"></textarea>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="ca" value="<?php echo e($actividad->cod_act); ?>">
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                <input class="btn btn-primary" type="submit" value="Aceptar"/>

            </div>
        </div>
    </form>
</div>
<?php /**PATH C:\Users\Pc\Desktop\UAD9V2\uad9\resources\views/actividad/tarea/f_editarTarea.blade.php ENDPATH**/ ?>