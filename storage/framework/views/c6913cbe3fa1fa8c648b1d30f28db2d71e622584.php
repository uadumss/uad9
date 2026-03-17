<div class="modal-dialog modal-xl" role="document">
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

            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-5 rounded shadow">
                <h5 class="text-white text-center">Formulario para nuevo cargo</h5>
            </div>
            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-6">
                    <div class="">
                        <table class="table-sm table">
                            <tr>
                                <td class="font-italic font-weight-bold text-dark">Nombre convocatoria :</td>
                                <td class="">: <?php echo e($convocatoria->con_nombre); ?></td>
                            </tr>
                            <tr>
                                <td class="font-italic font-weight-bold text-dark">Gestión :</td>
                                <td class="">: <?php echo e($convocatoria->con_gestion); ?></td>
                            </tr>
                            <tr>
                                <td class="font-italic font-weight-bold text-dark">Tipo de convocatoria :</td>
                                <td class="">: <?php echo e($convocatoria->con_tipo); ?></td>
                            </tr>
                            <tr>
                                <td class="font-italic font-weight-bold text-dark">Dirigido a :</td>
                                <td class="">: <?php echo e($convocatoria->con_dirigido_a); ?></td>
                            </tr>
                            <tr>
                                <td class="font-italic font-weight-bold text-primary">Unidad:</td>
                                <td class=""><?php echo e($convocatoria->con_convocante); ?></td>
                            </tr>
                            <tr>
                                <td class="font-italic font-weight-bold text-primary">Tipo de unidad:</td>
                                <td class=""><?php echo e(strtoupper($convocatoria->con_tipo_convocante)); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 shadow overflow-auto p-3" style="height: 450px;" >
                    <span class="font-weight-bold font-italic text-primary"> * Seleccione unidad</span>
                    <br/>
                    <br/>

                        <form id="form_unidad">
                            <?php echo csrf_field(); ?>
                            <div id="panel_unidad">
                                <select name="unidad" class="custom-control custom-select">
                                    <option></option>
                                </select>
                            </div>
                            <input type="hidden" name="cc" value="<?php echo e($convocatoria->cod_con); ?>">
                        </form>
                    <br/><br/>
                    <button class="btn- btn-sm btn-primary" onclick="cargarDatos('<?php echo e(url('obtener unidad convocatoria noatentado/unidad/'.$convocatoria->cod_con)); ?>','panel_unidad')">Unidad </button>
                    <button class="btn- btn-sm btn-primary" onclick="cargarDatos('<?php echo e(url('obtener unidad convocatoria noatentado/facultad/'.$convocatoria->cod_con)); ?>','panel_unidad')">Facultad </button>
                    <button class="btn- btn-sm btn-primary" onclick="cargarDatos('<?php echo e(url('obtener unidad convocatoria noatentado/carrera/'.$convocatoria->cod_con)); ?>','panel_unidad')">Carrera </button>
                </div>
            </div>

            <br/>
            <br/>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" onclick="$('#modal_agregar').modal('hide')">Cerrar</button>
            <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_unidad','<?php echo e(url('guardar unidad convocatoria noatentado')); ?>','panel_convocatoria');$('#modal_agregar').modal('hide')">Guardar</button>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/convocatoria/fe_unidad.blade.php ENDPATH**/ ?>