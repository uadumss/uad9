<div class="modal-dialog modal-lg shadow-lg" role="document" id="panel_actividad">
<div class="modal-content border-bottom-danger">
    <div class="modal-header bg-danger">
        <h5 class="modal-title text-white" id="exampleModalLabel"><img src="<?php echo e(url('img/icon/eliminar.png')); ?>"> Eliminar tarea</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <span class="font-italic text-dark">Esta seguro de eliminar el reporte diario:</span> <br/>
        <div class="text-white font-italic font-weight-bold bg-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción podria generar inconsistencia en la Base de Datos</div>
        <br/>
        <div class="row shadow ml-3 mr-3 rounded p-3">
            <div class="alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">
                <table class="col-md-12">
                    <tr>
                        <th class="text-right"> Fecha de reporte: </th>
                        <td class="text-dark text-left border-bottom border-danger pl-3"><?php echo e(date('d/m/Y',strtotime($diario->dia_fech))); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right"> Usuario: </th>
                        <td class="text-dark text-left border-bottom border-danger pl-3">
                            <?php if($usuario->foto!=''): ?>
                                <img src="<?php echo e(url('img/foto/'.$usuario->foto)); ?>" width="40" height="40" class="imgRedonda float-left"/>
                            <?php endif; ?>
                            <?php echo e($usuario->name); ?>

                        </td>
                    </tr>
                    <tr>
                        <th class="text-right"> Tarea: </th>
                        <td class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($tarea->tar_nombre); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right"> Reporte: </th>
                        <td class="text-dark text-left border-bottom border-danger pl-3"><?php echo e($diario->dia_reporte); ?></td>
                    </tr>
                </table>
            </div>
            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2><i class="fas fa-question-circle"></i></h2></div>
        </div>
        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>

        <form id="form_eliminar_diario">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="cd" value="<?php echo e($diario->cod_dia); ?>">
        </form>
            <button class="btn btn-danger btn-sm" type="button" onclick="enviar('form_eliminar_diario','<?php echo e(url('eliminar diario adm')); ?>','panel_diario');$('#observacion').modal('hide');"> Eliminar</button>
    </div>
</div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/act/f_eliminar_diario_adm.blade.php ENDPATH**/ ?>