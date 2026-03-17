<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-hand-point-right"></i> Entregas </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-dark" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert-primary centrar_bloque p-1 col-md-9 rounded shadow">
                <h6 class="text-dark text-center font-weight-bold"> Formulario de entrega de legalizaciones</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form method="post" id="form_g_entrega">
                    <?php echo csrf_field(); ?>
                    <table class="col-md-11 text-dark">
                        <tr>
                            <th class="text-right font-italic text-dark">Nro. Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark"><?php echo e($tramite_noatentado->dtra_numero_tramite); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">Trámite : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark">

                                    <?php echo e($tramite_noatentado->tre_nombre); ?>


                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark" valign="top"><br/>Entregar A : &nbsp;&nbsp;</th>
                            <td class="border-bottom border-dark"><br/>

                                <?php $__currentLoopData = $noatentado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="<?php echo e($c->id_per); ?>"> <?php echo e($c->per_apellido." ".$c->per_nombre); ?><br/>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if($tramite_noatentado->cod_apo!=''): ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="a" checked> <?php echo e($apoderado->apo_apellido." ".$apoderado->apo_nombre); ?><span class="bg-danger rounded text-white pl-1 pr-1" style="font-size: 0.8em">Apo</span><br/>
                                <?php endif; ?>
                                <br/>
                            </td>
                        </tr>

                    </table>

                        <input type="hidden" name="cdtra" value="<?php echo e($tramite_noatentado->cod_dtra); ?>">

                    <div class="text-danger font-italic font-weight-bold border border-danger rounded" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema<br/>
                        * Si hace la entrega de este trámite, ya no se podra modificar su estado</div>
                </form>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-sm btn-primary" onclick="guardarDatos('<?php echo e(url("g_entrega_noa")); ?>','panel_traleg','form_g_entrega');cargarDatos('<?php echo e(url('actualizar lista entrega noatentado')); ?>','panel_tabla_no-atentado');$('#traleg').modal('hide');" data-dismiss="modal">Guardar</button>
        </div>
    </div>

</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/entrega/f_conf_entrega_noa.blade.php ENDPATH**/ ?>