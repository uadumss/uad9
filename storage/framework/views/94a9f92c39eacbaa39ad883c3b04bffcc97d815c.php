<div class="modal-dialog modal-lg" role="document" id="">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-trash-alt"></i>&nbsp;&nbsp;ELIMINAR CANDIDATO</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <?php if($eliminar==1): ?>
                <div class="text-center text-dark">
                    <span class="font-italic">Está seguro de eliminar el trámite</span> <i class="fas fa-question-circle text-danger" style="font-size: 35px"></i>
                </div><br>

                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    <div class="row">
                        <div class="">
                            <div>
                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic text-dark">Nro. Trámite : </th>
                                        <td class="text-left border-bottom border-dark"><?php echo e($documento_tramite->dtra_numero_tramite."/".$documento_tramite->dtra_gestion_tramite); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Trámite : </th>
                                        <td class="border-bottom border-dark"><?php echo e($tramite->tre_nombre); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="font-weight-bold shadow text-center centrar_bloque  alert-danger rounded col-md-9 p-3">
                    No se puede eliminar el trámite
                </div>
            <?php endif; ?>
        </div>

        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em; padding: 2px; margin:7px">* Esta acción quedará registrado en el sistema</div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <?php if($eliminar==1): ?>
                <form id="form_eli_tramite" method="POST" action="<?php echo e(url('eliminar tramite convocatoria noatentado')); ?>">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="cd" value="<?php echo e($documento_tramite->cod_dtra); ?>"/>
                </form>
                <button class="btn btn-danger btn-sm" type="button" onclick="$('#form_eli_tramite').submit()">Eliminar</button>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/tramite/f_eli_tramite.blade.php ENDPATH**/ ?>