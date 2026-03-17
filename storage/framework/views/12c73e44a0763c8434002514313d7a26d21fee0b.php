<div class="modal-dialog modal-xl" role="document" id="panel_reporte">
    <div class="modal-content border-bottom-primary shadow-lg">
            <input  type="hidden" name="ct" id="ct" value=""/>
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-chart-line"></i>&nbsp;&nbsp;Reporte general</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <?php echo csrf_field(); ?>
            <div class="modal-body" style="font-size: 13px">
                <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow">
                    <h5 class="text-white text-center">Reportes de entregas en PDF </h5>
                </div>
                <hr class="sidebar-divider"/>
                <div class="row">
                    <div class="col-md-3 border rounded shadow-lg">
                        <br/>
                        <span class="text-primary font-weight-bold col-md-12 centrar_bloque"> * DATOS PARA EL REPORTE</span>
                        <br/><br/>
                            <form id="form_general" action="<?php echo e(url('reporte servicios entregas')); ?>" method="post" target="nuevo">
                                <?php echo csrf_field(); ?>
                                <table>
                                    <tr class="border-bottom border-dark">
                                        <th class="text-right font-italic">Por trámite : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm border-0 text-primary" name="tramite" id="tramite">
                                                <option></option>
                                                <?php $__currentLoopData = $tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($t->cod_tre); ?>"><?php echo e($t->tre_nombre); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic border-bottom border-dark">Tipo de trámite :</th>
                                        <td class="border-bottom border-dark text-primary">
                                            &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo_tramite" value="E"> EXTERNO  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <input type="radio" name="tipo_tramite" value="I"> INTERNO
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-dark">
                                        <th class="text-right font-italic">Fecha : </th>
                                        <td class="border-bottom border-dark">
                                            <input type="date" name="inicial" required class="form-control-sm form-control border-0 text-primary" value="<?php echo e(date('Y-m-d')); ?>">
                                        </td>
                                    </tr>
                                    <tr class="border-bottom border-dark">
                                        <th class="text-right font-italic">Fecha final : </th>
                                        <td class="border-bottom border-dark">
                                            <input type="date" name="final" class="form-control-sm form-control border-0 text-primary">
                                        </td>
                                    </tr>
                                </table>
                                <br/>
                                <input type="submit" name="" value="Generar" class="btn btn-sm btn-primary">
                            </form>
                            <br/>
                        <br/>
                        <br/>

                    </div>
                    <div class="col-md-9">
                        <div id="panel_resultado" style="height: 600px">

                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/reporte/entrega/entregas.blade.php ENDPATH**/ ?>