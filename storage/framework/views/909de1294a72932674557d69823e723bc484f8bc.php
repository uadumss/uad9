<form id="form_carrera">
    <?php echo csrf_field(); ?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> Carrera</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="rounded p-2">
                <?php if($cod_car==0): ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nueva Carrera</h5>
                    </div>
                    <br/>
                    <br/>
                    <span class="font-weight-bold font-italic text-dark">Facultad : <?php echo e($facultad->fac_nombre); ?></span>
                    <hr class="sidebar-divider"/>
                    <div class="shadow p-3">
                        <table class="col-md-12">
                            <tr>
                                <th class="text-right"><span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * Datos de la carrera</span><br/></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre de la carrera:</th>
                                <td class="border-bottom border-dark">
                                    <input type="text" class="form-control form-control-sm border-0" required name="nombre" />
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre corto:</th>
                                <td class="border-bottom border-dark">
                                    <input type="text" class="form-control form-control-sm border-0" name="corto_c" />
                                </td>
                            </tr>
                        </table>
                        <br/>
                    </div>

                    <input type="hidden" name="cf" value="<?php echo e($facultad->cod_fac); ?>">
                <?php else: ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar Carrera</h5>
                    </div>
                    <br/>
                    <br/>
                    <span class="font-weight-bold font-italic text-dark">Facultad : <?php echo e($facultad->fac_nombre); ?></span>
                    <hr class="sidebar-divider"/>
                    <div class="shadow p-3">
                        <table class="col-md-12">
                            <tr>
                                <th class="text-right"><span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * Datos de la carrera</span><br/></th>
                                <td></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre de la carrera:</th>
                                <td class="border-bottom border-dark">
                                    <input type="text" class="form-control form-control-sm border-0" required name="nombre" value="<?php echo e($carrera->car_nombre); ?>" />
                                </td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre corto:</th>
                                <td class="border-bottom border-dark">
                                    <input type="text" class="form-control form-control-sm border-0" name="corto_c" value="<?php echo e($carrera->car_abreviacion); ?>"/>
                                </td>
                            </tr>
                        </table>
                        <br/>
                    </div>
                    <input type="hidden" name="cf" value="<?php echo e($facultad->cod_fac); ?>">
                    <input type="hidden" name="cc" value="<?php echo e($carrera->cod_car); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary" type="button" onclick="enviar('form_carrera','g_carrera')" data-dismiss="modal">Guardar</button>
        </div>
    </div>
</form>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/unidad/carrera/fe_carrera.blade.php ENDPATH**/ ?>