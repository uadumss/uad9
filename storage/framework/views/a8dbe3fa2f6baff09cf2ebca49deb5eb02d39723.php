<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i> Observar </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="alert-danger centrar_bloque p-1 col-md-9 rounded shadow">
                <h6 class="text-dark text-center font-weight-bold"> Formulario de registro de observaciones</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form method="post" id="form_g_obs_docleg">
                    <?php echo csrf_field(); ?>
                    <table class="col-md-11">
                        <tr>
                            <th class="text-right font-italic text-danger">Trámite : </th>
                            <td class="border-bottom border-dark"><?php echo e($docleg->tre_nombre); ?></td>
                        </tr>
                        <?php if($docleg->dtra_falso=='t'): ?>
                            <tr>
                                <th class="text-right font-italic text-danger">Observación : </th>
                                <td class="border-bottom border-dark">
                                    <?php echo e($docleg->dtra_obs); ?>

                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic text-danger">Bloqueado : </th>
                                <td class="border-bottom border-dark">
                                    <i class="fas fa-check text-danger"></i>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <th class="text-right font-italic text-danger">Observación : </th>
                                <td class="border-bottom border-dark">
                                    <textarea class="form-control" name="obs"><?php echo e($docleg->dtra_obs); ?></textarea>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic text-danger">Bloquear : </th>
                                <td class="border-bottom border-dark">
                                    <input type="checkbox" class="custom-checkbox" name="falso">
                                </td>
                            </tr>
                        <?php endif; ?>
                    </table>
                    <input type="hidden" name="cdtra" value="<?php echo e($docleg->cod_dtra); ?>">
                    <input type="hidden" name="ctra" value="<?php echo e($docleg->cod_tra); ?>">
                    <br/>
                    <div class="text-danger font-italic font-weight-bold border border-danger rounded" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema<br/>
                        * Si bloquea el trámite, ya no se podra modificar su estado</div>
                </form>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <?php if($docleg->dtra_falso!='t'): ?>
                <button class="btn btn-sm btn-danger" onclick="guardarDatos('<?php echo e(url("g_obs_docleg")); ?>','panel_traleg','form_g_obs_docleg')" data-dismiss="modal">Guardar</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/obs_docleg.blade.php ENDPATH**/ ?>