<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header alert-primary">
            <h5 class="modal-title text-dark" id="exampleModalLabel"> Búsqueda</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <form method="post" id="form_eli_docleg">
                <?php echo csrf_field(); ?>
                <?php if($docleg->dtra_solo_sello=='t'): ?>
                    <span class="font-italic">Esta seguro de legalizar este documento : </span><br/><br/>
                    <div class="row">
                        <div class="font-weight-bold alert-primary shadow text-center centrar_bloque col-md-9 p-2" >
                            <div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right">Trámite:</th>
                                        <th class="text-dark text-left border-bottom border-dark pl-3"><?php echo e($tramite->tre_nombre); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Nro. título:</th>
                                        <th class="text-dark text-left border-bottom border-dark pl-3"><?php echo e($docleg->dtra_numero." / ".$docleg->dtra_gestion); ?></th>
                                    </tr>

                                </table>
                                <input type="hidden" name="cdtra" value="<?php echo e($docleg->cod_dtra); ?>">
                            </div>
                        </div>
                        <div class="pt-2 col-md-2 text-primary font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                    </div>
                    <br/>
                    <div class="text-dark font-italic font-weight-bold border border-primary rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                <?php else: ?>
                    <span class="font-italic">Esta seguro de haber encontrado el documento : </span><br/><br/>
                    <div class="row">
                        <div class="font-weight-bold alert-primary shadow text-center centrar_bloque col-md-9 p-2" >
                            <div>
                                <table class="col-md-12">
                                    <tr>
                                        <th class="text-right">Documento:</th>
                                        <th class="text-dark text-left border-bottom border-dark pl-3"><?php echo e($documento->dcon_doc); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Nro. título:</th>
                                        <th class="text-dark text-left border-bottom border-dark pl-3"><?php echo e($docleg->dtra_numero." / ".$docleg->dtra_gestion); ?></th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">Buscado en :</th>
                                        <th class="text-dark text-left border-bottom border-dark pl-3">
                                            <?php echo e(\App\Models\Funciones::nombre_titulo($docleg->dtra_buscar_en)); ?>

                                        </th>
                                    </tr>
                                </table>
                                <input type="hidden" name="cdtra" value="<?php echo e($docleg->cod_dtra); ?>">
                            </div>
                        </div>
                        <div class="pt-2 col-md-2 text-primary font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                    </div>
                    <br/>
                    <div class="text-dark font-italic font-weight-bold border border-primary rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>

                <?php endif; ?>

            </form>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary" data-dismiss="modal" onclick="guardarDatos('<?php echo e(url("g_busqueda_encontrado")); ?>','panel_traleg','form_eli_docleg')"> Registrar</button>
        </div>

    </div>

</div>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/f_registro_busqueda.blade.php ENDPATH**/ ?>