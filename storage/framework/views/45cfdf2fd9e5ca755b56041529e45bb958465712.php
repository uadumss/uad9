<form action="<?php echo e(url('guardar unidad/')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> UNIDAD</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">

            <div class="shadow-sm rounded p-2">
                <?php if($cod_uni==0): ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nueva unidad</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary float-right font-weight-bold font-italic" style="font-size: 0.8em"> * Datos de la Unidad</span><br/><br/>
                    <table class="col-md-12">
                        <tr>
                            <th class="text-right font-italic">Nombre de la unidad:</th>
                            <td class="border-bottom border-dark">
                                <input type="text" class="form-control form-control-sm border-0" required name="nombre" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre corto:</th>
                            <td class="border-bottom border-dark">
                                <input type="text" class="form-control form-control-sm border-0" name="corto" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Tipo de unidad:</th>
                            <td class="border-bottom border-dark">
                                <select class="custom-select custom-select-sm" name="nivel">
                                    <option></option>
                                    <option value="DIRECCION">DIRECCION</option>
                                    <option value="DEPARTAMENTO">DEPARTAMENTO</option>
                                    <option value="UNIDAD">UNIDAD</option>
                                    <option value="SECCION">SECCION</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar unidad</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * Datos de la unidad</span><br/><br/>

                    <table class="col-md-12">
                        <tr>
                            <th class="text-right font-italic ">Nombre de la unidad:</th>
                            <td class="border-bottom border-dark ">
                                <input type="text" class="form-control form-control-sm border-0" required name="nombre" value="<?php echo e($unidad->uni_nombre); ?>" />
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre corto:</th>
                            <td class="border-bottom border-dark">
                                <input type="text" class="form-control form-control-sm border-0" name="corto" value="<?php echo e($unidad->uni_abreviacion); ?>"/>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Tipo de unidad:</th>
                            <td class="border-bottom border-dark">
                                <select class="custom-select custom-select-sm" name="nivel">
                                    <?php if($unidad->uni_nivel!=''): ?>
                                        <option value="<?php echo e($unidad->uni_nivel); ?>"><?php echo e($unidad->uni_nivel); ?></option>
                                    <?php endif; ?>
                                    <option></option>
                                    <option value="DIRECCION">DIRECCION</option>
                                    <option value="DEPARTAMENTO">DEPARTAMENTO</option>
                                    <option value="UNIDAD">UNIDAD</option>
                                    <option value="SECCION">SECCION</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="cu" value="<?php echo e($unidad->cod_uni); ?>">
                <?php endif; ?>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/unidad/unidad/fe_unidad.blade.php ENDPATH**/ ?>