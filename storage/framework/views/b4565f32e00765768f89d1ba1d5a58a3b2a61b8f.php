<div class="modal-dialog" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file"></i> Editar cargos</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: 14px">
            <?php if(Session::has('exitoModal2')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('exitoModal2'); ?>

                </div>
            <?php endif; ?>
            <?php if(Session::has('errorModal2')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('errorModal2'); ?>

                </div>
            <?php endif; ?>

            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-10 rounded shadow">
                <h5 class="text-white text-center">Formulario para nuevo cargo</h5>
            </div>
            <hr class="sidebar-divider"/>
            <div class="">
                <div class="">
                    <div class="">
                        <table class="table-sm table">
                            <tr>
                                <td class="font-italic font-weight-bold text-primary">Nuevo cargo :</td>
                                <td>
                                    <form id="form_cargo">
                                        <?php echo csrf_field(); ?>
                                        <?php if($cargo): ?>
                                            <input type="hidden" name="ca" value="<?php echo e($cargo->cod_carg); ?>">
                                            <input type="text" name="nombre" class="form-control form-control-sm" value="<?php echo e($cargo->carg_nombre); ?>">
                                        <?php else: ?>
                                            <input type="text" name="nombre" value="" class="form-control form-control-sm">
                                        <?php endif; ?>
                                        <input type="hidden" name="cc" value="<?php echo e($convocatoria->cod_con); ?>">
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" onclick="$('#modal_agregar').modal('hide')">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_cargo','<?php echo e(url('guardar cargo convocatoria noatentado')); ?>','panel_cargos');$('#modal_agregar').modal('hide')" >Guardar</button>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/fe_cargo.blade.php ENDPATH**/ ?>