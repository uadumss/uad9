<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp;Eliminar frente</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <?php if($eliminar==1): ?>
            <form id="form_eli_frente">
                <?php echo csrf_field(); ?>
                <span class="font-italic">Esta seguro de eliminar el consejo  : </span><br/><br/>
                <div class="row">
                    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2" >
                        <div>

                                <table class="col-md-12">

                                    <tr>
                                        <th class="text-right font-italic text-dark">Nombre :</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e($frente->fre_nombre); ?>

                                        </td>
                                    </tr>
                                        <tr>
                                            <?php if($frente->fre_tipo=='u'): ?>
                                                <th class="text-right font-italic text-dark">Facultad :</th>
                                                <td class="border-bottom border-dark text-left">
                                                    <?php echo e($facultad->fac_nombre); ?>

                                                </td>
                                            <?php else: ?>
                                                <?php if($frente->fre_tipo=='f'): ?>
                                                    <th class="text-right font-italic text-dark">Carrera :</th>
                                                    <td class="border-bottom border-dark text-left">
                                                        <?php echo e($carrera->car_nombre); ?>

                                                    </td>
                                                <?php else: ?>

                                                <?php endif; ?>

                                            <?php endif; ?>

                                        </tr>

                                    <tr>
                                        <th class="text-right font-italic text-dark">Fecha inicio:</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e(date('d/m/Y',strtotime($frente->fre_fecha_inicio))); ?>

                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic text-dark">Fecha conclusión:</th>
                                        <td class="border-bottom border-dark text-left">
                                            <?php echo e(date('d/m/Y',strtotime($frente->fre_fecha_fin))); ?>

                                        </td>
                                    </tr>
                                    <input type="hidden" name="cf" value="<?php echo e($frente->cod_fre); ?>"/>
                                </table>


                        </div>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                <?php else: ?>
                    <br/>
                    <div class="alert-danger rounded p-2 col-md-8 centrar_bloque">
                        <span class="font-weight-bold">
                                    No se puede eliminar al frente debido a que tiene consejeros registrados
                        </span>
                    </div>
                    <br/>
                <?php endif; ?>

            </form>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <?php if($eliminar==1): ?>
                <button class="btn btn-danger btn-sm" type="button" data-dismiss="modal" onclick="enviar('form_eli_frente','<?php echo e(url('eli_frente')); ?>','panel_frente')">Eliminar</button>
            <?php endif; ?>
        </div>

    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcu/f_eli_frente.blade.php ENDPATH**/ ?>