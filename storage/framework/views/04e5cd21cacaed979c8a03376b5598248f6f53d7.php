<div class="modal-dialog modal-xl shadow-lg" role="document" id="panel_observacion">
    <div class="modal-content">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-edit"></i> Revisar reporte</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: 0.85em">
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-info">
                        <div class="card-body" id="d_personales">
                            <?php if($diario['dia_final']=='t'): ?>
                                <h6 class="bg-danger shadow rounded text-white col-md-5 centrar_bloque text-center mb-2">REPORTE FINAL</h6>
                                <?php if($diario['dia_aceptado']=='t'): ?>
                                    <div style="height: 650px;" class="overflow-auto border mb-1 shadow-sm">
                                        <?php else: ?>
                                            <div style="height: 330px;" class="overflow-auto border mb-1 shadow-sm">
                                                <?php endif; ?>
                                                <?php else: ?>
                                                    <?php if($diario['dia_aceptado']!='t'): ?>
                                                        <h6 class="bg-danger shadow rounded text-white col-md-5 centrar_bloque text-center mb-2">Reporte del funcionario</h6>
                                                        <div style="height: 300px;" class="overflow-auto border mb-1 shadow-sm">
                                                            <?php else: ?>
                                                                <h6 class="bg-info shadow rounded text-white col-md-5 centrar_bloque text-center mb-2">Reporte del funcionario</h6>
                                                                <span class="mensaje"> * Informe aceptado</span>
                                                                <div style="height: 350px;" class="mb-1 shadow-sm">
                                                                    <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <table class="table table-sm">
                                                                        <?php if($diario['dia_final']=='t'): ?>
                                                                            <tr><td colspan="2"><span class="mensaje-peligro">TRABAJO CONCLUIDO</span></td></tr>
                                                                        <?php else: ?>
                                                                            <?php if($diario['dia_aceptado']=='t'): ?>
                                                                                <tr>
                                                                                    <td><span class="text-dark font-weight-bolder">Calificación:</span>
                                                                                        <span class="text-primary font-weight-bolder" style="font-size: 1.3em">&nbsp;<?php echo e($diario['dia_calificacion']); ?></span></td>
                                                                                    <td><span class="text-dark font-weight-bolder">Avance:</span>
                                                                                        <span class="text-primary font-weight-bolder" style="font-size: 1.3em">&nbsp;<?php echo e($diario['dia_porcen']); ?> %</span></td>
                                                                                </tr>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                        <tr>
                                                                            <th class="text-dark">Fecha reporte:</th>
                                                                            <td><?php echo e(date('d/m/Y',strtotime($diario['dia_fech']))); ?></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" class="text-justify"><span class="font-weight-bolder text-dark">Reporte:</span><br/>
                                                                                <div >
                                                                                    <?php echo e($diario['dia_reporte']); ?>

                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                                <?php if($diario['dia_aceptado']!='t'): ?>
                                                                    <div>
                                                                        <h6 class="bg-primary shadow rounded text-white col-md-5 centrar_bloque text-center mt-2">Formulario de observación</h6>
                                                                        <form action="<?php echo e(url('g_observacion')); ?>" method="POST">
                                                                            <?php echo csrf_field(); ?>
                                                                            <span class="mensaje-peligro">* Introduzca la observación</span>
                                                                            <textarea class="form-control mb-2 shadow-sm" name="obs" rows="8"></textarea>
                                                                            <input type="submit" class="btn btn-sm btn-primary float-right shadow-sm" value="Guardar observación">
                                                                            <input type="hidden" name="cd" value="<?php echo e($diario['cod_dia']); ?>">
                                                                            <input type="hidden" name="ct" value="<?php echo e($diario['cod_tar']); ?>">
                                                                            <input type="hidden" name="redireccion" value="">

                                                                        </form>
                                                                    </div>
                                                                <?php endif; ?>
                                                        </div>

                                            </div>
                                            <?php if($diario['dia_aceptado']!='t'): ?>
                                                <div class="border border-info mt-2 rounded p-2 col-md-12">
                                                    <span class="mensaje-peligro"> * Aceptar el informe</span>
                                                    <?php if($diario['dia_final']=='t'): ?>
                                                        <form action="<?php echo e(url('aceptar_diario')); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="mb-2 col-md-8">&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="hidden" name="cal" value="0">
                                                                <input type="hidden" name="por" value="0">
                                                                <input type="hidden" name="cd" value="<?php echo e($diario['cod_dia']); ?>">
                                                                <input type="hidden" name="redireccion" value="<?php echo e($redireccion); ?>">
                                                                <input type="submit" class="btn btn-sm btn-primary shadow-sm form-control-sm float-right" value="Aceptar Informe final"/>
                                                            </div>
                                                        </form>
                                                    <?php else: ?>
                                                        <form action="<?php echo e(url('aceptar_diario')); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <div class="input-group mb-2 col-md-12">
                                                                Calificación:&nbsp; <select class="form-control form-control-sm col-md-3 shadow-sm" name="cal">
                                                                    <?php for($i=0;$i<11;$i++): ?>
                                                                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                                    <?php endfor; ?>
                                                                </select>&nbsp;&nbsp;
                                                                <?php if($totalPorcen[0]->porcentaje<1){$totalPorcen[0]->porcentaje=0;}?>
                                                                Porcentaje: <span class="text-danger" style="font-size: 0.8em">(completado : <?php echo e($totalPorcen[0]->porcentaje); ?> %)</span>&nbsp;
                                                                <select class="form-control form-control-sm col-md-3 shadow-sm" name="por" id="por">
                                                                    <?php
                                                                    $rango=100 - $totalPorcen[0]->porcentaje;
                                                                    ?>
                                                                    <?php for($i=0;$i<=$rango;$i++): ?>
                                                                        <option value="<?php echo e($i); ?>"><?php echo e($i); ?>%</option>
                                                                    <?php endfor; ?>
                                                                </select>&nbsp;&nbsp;&nbsp;&nbsp;
                                                                <input type="hidden" name="cd" value="<?php echo e($diario['cod_dia']); ?>">
                                                                <input type="hidden" name="redireccion" value="">
                                                                <input type="submit" class="btn btn-sm btn-primary shadow-sm form-control-sm" value="Aceptar Informe"/>
                                                            </div>
                                                        </form>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-body bg-light">
                                                <h6 class="bg-primary shadow rounded text-white col-md-5 centrar_bloque text-center mb-2">Observaciones anteriores</h6>
                                                <?php if(sizeof($observaciones)>0): ?>
                                                    <div>
                                                        <div id="panelObs">
                                                            <?php $i=1;?>
                                                            <?php $__currentLoopData = $observaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div>
                                                                    <div id="po<?php echo e($i); ?>" <?php if($i>1){echo 'style="display:none"';}?> class="border rounded">
                                                                        <div class="overflow-auto" style="height: 325px;">
                                                                            <table class="table table-sm">
                                                                                <tr>
                                                                                    <th class="text-dark">Fecha Registro:</th>
                                                                                    <td class="mensaje text-left"><?php echo e(date('d/m/Y',strtotime($o['od_fech_mod']))); ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2" class="text-justify"><span class="font-weight-bolder text-dark">Reporte anterior:</span> <br/><?php echo e($o['od_rep']); ?></td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="overflow-auto" style="height: 325px;">
                                                                            <table class="table table-sm">
                                                                                <tr>
                                                                                    <th class="text-dark">Fecha de observación:</th>
                                                                                    <td class="mensaje text-left"><?php echo e(date('d/m/Y',strtotime($o['od_fech']))); ?></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2" class="text-justify"><span class=" mensaje-peligro">Observación:</span> <br/><?php echo e($o['od_obs']); ?></td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                    <?php $i++;?>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                        <div class="mt-3">
                                                            <?php $i=1;?>
                                                            <?php $__currentLoopData = $observaciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <a class="btn btn-circle btn-sm <?php if($i==1){echo 'btn-primary text-white';}else{echo 'btn-light text-dark';}?>"
                                                                   id="o<?php echo e($i); ?>" onclick="cambiar(<?php echo e($i); ?>)"><i class="fas" id="i<?php echo e($i); ?>"><?php echo e($i); ?></i></a>
                                                                <?php $i++;?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="mensaje-peligro">No existen observaciones</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function cambiar(panel){
        var tam=<?php echo e(sizeof($observaciones)); ?>;
        for(var i=1;i<(tam+1);i++){
            $('#po'+i).css('display','none');
            $('#o'+i).removeClass('btn-primary');
            $('#o'+i).removeClass('text-white');
            $('#o'+i).addClass('btn-light');
            $('#o'+i).addClass('text-dark');
        }
        $('#po'+panel).css('display','block');
        $('#o'+panel).removeClass('btn-light');
        $('#o'+panel).removeClass('text-dark');
        $('#o'+panel).addClass('btn-primary');
        $('#o'+panel).addClass('text-white');
    }
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/act/l_observacionTarea_adm.blade.php ENDPATH**/ ?>