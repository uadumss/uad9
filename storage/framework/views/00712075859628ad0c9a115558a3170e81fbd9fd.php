<div class="modal-dialog modal-lg" role="document" id="panel_tramite_apostilla">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Detalle de sanción </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar detalle de la sanción</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <form id="form_detalle">
                    <?php echo csrf_field(); ?>
                    <table class="col-md-12">
                        <tr>
                            <th class="font-italic text-dark">Detalle de la sancion :
                                <?php if($detalle): ?>
                                    <textarea class="form-control form-control-sm" name="detalle"><?php echo e($detalle->dsan_detalle); ?></textarea>
                                <?php else: ?>
                                    <textarea class="form-control form-control-sm" name="detalle"></textarea>
                                <?php endif; ?>
                            </th>
                        </tr>
                        <?php if($detalle): ?>
                            <input type="hidden" name="cd" value="<?php echo e($detalle->cod_dsan); ?>">
                        <?php endif; ?>
                        <input type="hidden" name="cs" value="<?php echo e($cod_san); ?>">
                    </table>
                </form>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_detalle','<?php echo e(url('guardar detalle sancion')); ?>','panel_modal');
                                $('#Modal2').modal('hide');cargarDatos('<?php echo e(url('lista detalle sancion noatentado/'.$cod_san)); ?>','panel_lista_tramites')">
                Guardar</button>
        </div>
    </div>
</div>



<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/sancionado/fe_detalle.blade.php ENDPATH**/ ?>