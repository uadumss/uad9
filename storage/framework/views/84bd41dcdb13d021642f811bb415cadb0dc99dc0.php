
<script src="<?php echo e(asset('js/tinymce/tinymce.min.js')); ?>"></script>
<form action="<?php echo e(url('guardar documento apostilla')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="modal-dialog modal-lg" role="document" id="panel_tramite">
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Trámite </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar trámite</h6>
            </div>
            <hr class="sidebar-divider"/>
            <?php if($cod_lis==0): ?>

                    <div class="col-sm-10">
                        <span class="text-primary font-italic font-weight-bold">* Datos del trámite</span>
                        <table class="table-hover col-md-12">
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="nombre" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Alias : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="alias" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">N° Cuenta : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="cuenta" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Monto (Bs.): </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="monto" pattern="[0-9]{1,4}"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Resolución: </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""name="resolucion"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic border-bottom">Buscar en :</th>
                                <td class="border-bottom border-dark">
                                    <select class="custom-select custom-select-sm border-0 " name="tipo">
                                        <option></option>
                                        <option value="db">DIPLOMA DE BACHILLER</option>
                                        <option value="ca">CERTIFICADO ACEDEMICO</option>
                                        <option value="da">DIPLOMA ACADEMICO</option>
                                        <option value="tp">TITULO PROFESIONAL</option>
                                        <option value="di">DIPLOMADO</option>
                                        <option value="ma">MAESTRIA</option>
                                        <option value="es">ESPECIALIDAD</option>
                                        <option value="do">DOCTORADO</option>
                                        <option value="re">REVALIDA</option>
                                        <option value="su">SUPLETORIO</option>
                                        <option value="sid">TRAMITE MEDIANTE EL SID</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Descripción : </th>
                                <td class="border-bottom border-dark">
                                    <textarea class="form-control border-0" rows="5" name="desc" id="desc"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>

            <?php else: ?>

                                <div class="col-md-12">

                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">Nombre : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" value="<?php echo e($tramite['lis_nombre']); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Alias : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="alias" value="<?php echo e($tramite['lis_alias']); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">N° Cuenta : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" required name="cuenta" value="<?php echo e($tramite['lis_cuenta']); ?>"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Monto (Bs.): </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" required name="monto" pattern="[0-9]{1,4}" value="<?php echo e($tramite['lis_monto']); ?>"/></td>
                                        </tr>

                                            <th class="text-right font-italic border-bottom">Buscar en :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                    <option value="<?php echo e($tramite['tre_buscar_en']); ?>"><?php echo e(strtoupper($tramite['lis_tipo'])); ?></option>
                                                    <option></option>
                                                    <option value="db">DB</option>
                                                    <option value="ca">CA</option>
                                                    <option value="da">DA</option>
                                                    <option value="tp">TP</option>
                                                    <option value="di">DI</option>
                                                    <option value="tpos">TPOS</option>
                                                    <option value="re">RE</option>
                                                    <option value="su">SU</option>
                                                </select>
                                            </td>
                                        </tr>

                                        <tr>
                                            <th class="text-right font-italic">Descripción : </th>
                                            <td class="border-bottom border-dark">
                                                <textarea class="form-control border-0" rows="5" name="desc" id="desc"><?php echo e($tramite['lis_desc']); ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>


                        <input type="hidden" name="cl" value="<?php echo e($tramite['cod_lis']); ?>">
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <input class="btn btn-primary" type="submit" value="Aceptar"/>
        </div>
    </div>
    </div>
</form>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/apostilla/apostilla/fe_tra_apostilla.blade.php ENDPATH**/ ?>