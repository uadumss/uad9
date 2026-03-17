<div class="modal-dialog" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-signature"></i> Confrontación</h5>
        </div>
        <form method="post" action="<?php echo e(url('guardar confrontacion')); ?>">
            <?php echo csrf_field(); ?>
            <div class="modal-body">
                <div class="alert-primary centrar_bloque p-1 rounded shadow col-md-8">
                    <h6 class="text-dark font-weight-bold text-center">  Número de trámite</h6>
                </div>
                <hr class="sidebar-divider"/>
                <div>
                    <div id="" class="centrar_bloque">
                        <div class="shadow-sm p-2 col-md-12">
                            <h1 class="text-danger pr-3 text-center"><?php echo e($tramite->tra_numero); ?></h1>
                            <h6 class="font-italic text-dark text-center centrar_bloque">
                                <?php if($tramite->tra_fecha_solicitud!=''){echo date('d/m/Y',strtotime($tramite->tra_fecha_solicitud));} ?><br/>
                            </h6>
                            <table class="table table-sm font-italic font-weight-bold text-dark">
                                <tr>
                                    <td class="">Trámite : </td>
                                    <td>
                                        <select class="custom-select custom-select-sm border-0 " name="tramite">
                                            <?php $__currentLoopData = $lista_tramites; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $l): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($l['cod_tre']); ?>"><?php echo e($l['tre_nombre']); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </td>
                                </tr>
                                <tr><td> Nº Valorado </td><td><input type="text" class=" form-control form-control-sm" name="valorado" required></td></tr>
                                <tr><td colspan="2" class=""><span class="text-primary font-weight-bold font-italic" style="font-size: 0.9em">
                                Documentos:
                            </span>
                                    </td></tr>
                                <tr><td> <input type="checkbox" name="ci" value="ci"> </td><td> CI </td></tr>
                                <tr><td> <input type="checkbox" name="cn" value="cn"> </td><td> Certificado de Nacimiento</td></tr>
                                <tr><td> <input type="checkbox" name="lm" value="lm"> </td><td> Libreta Servicio Militar</td></tr>
                                <tr><td> <input type="checkbox" name="ce" value="ce"> </td><td> Carnet Extranjería</td></tr>
                                <tr><td> <input type="checkbox" name="pa" value="pa"> </td><td> Passaporte </td></tr>
                                <tr><td> <input type="checkbox" name="lc" value="lc"> </td><td> Libreta de colegio</td></tr>
                            </table>
                            <input type="hidden" name="ctra" value="<?php echo e($tramite->cod_tra); ?>">
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a class="btn btn-secondary btn-sm text-white" href="<?php echo e(url('cancelar_tra/'.$tramite->cod_tra)); ?>" type="button" onclick="">Cancelar</a>
                <input type="submit" class="btn btn-primary btn-sm" value="aceptar">
            </div>
        </form>
    </div>
</div>

<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/servicios/tra_legalizacion/numero_confrontacion.blade.php ENDPATH**/ ?>