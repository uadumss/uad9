<div class="modal-content border-bottom-primary shadow-lg">
    <div class="modal-header bg-verde-oscuro">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-text-o"></i> GLOSA </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
            <h6 class="text-white text-center">  Impresión de glosa</h6>
        </div>
        <hr class="sidebar-divider"/>
        <div class="row">

                <table class="table table-hover col-md-10 ml-5">
                    <tr class="">
                        <td class="text-dark">
                            <a href="<?php echo e(url('cambiar posicion pdf/'.$docleg->cod_dtra).'/0'); ?>" onclick="$('#docleg').modal('hide');" target="pdf<?php echo e(rand(1,1000)); ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        Imprimir glosa al inicio
                                        <br/>
                                        <br/>
                                        <?php if($docleg->dtra_glosa_posicion==0): ?>
                                            <span style="font-size: 0.8em" class="bg-danger rounded text-white col-sm-8 -underline"> * Actualmente seleccionado </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="<?php echo e(url('img/icon/glosa_pdf_inicio.gif')); ?>"/>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-dark">
                            <a href="<?php echo e(url('cambiar posicion pdf/'.$docleg->cod_dtra).'/1'); ?>" onclick="$('#docleg').modal('hide');" target="pdf<?php echo e(rand(1,1000)); ?>">
                            <div class="row">
                                <div class="col-md-8">
                                    Imprimir glosa arriba
                                    <br/>
                                    <br/>
                                    <?php if($docleg->dtra_glosa_posicion==1): ?>
                                        <span style="font-size: 0.8em" class="bg-danger rounded text-white col-sm-8 -underline"> * Actualmente seleccionado </span>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-2">
                                        <img src="<?php echo e(url('img/icon/glosa_pdf_arriba.gif')); ?>"/>
                                </div>
                            </div>
                            </a>
                        </td>
                    </tr>

                    <tr class="">
                        <td class="text-dark">
                            <a href="<?php echo e(url('cambiar posicion pdf/'.$docleg->cod_dtra.'/2')); ?>" onclick="$('#docleg').modal('hide');" target="pdf<?php echo e(rand(1,1000)); ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        Imprimir glosa al medio
                                        <br/>
                                        <br/>
                                        <?php if($docleg->dtra_glosa_posicion==2): ?>
                                            <span style="font-size: 0.8em" class="bg-danger rounded text-white col-sm-8 -underline"> * Actualmente seleccionado </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="<?php echo e(url('img/icon/glosa_pdf_medio.gif')); ?>"/>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-dark">
                            <a href="<?php echo e(url('cambiar posicion pdf/'.$docleg->cod_dtra.'/3')); ?>" onclick="$('#docleg').modal('hide');" target="pdf<?php echo e(rand(1,1000)); ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        Imprimir glosa abajo
                                        <br/>
                                        <br/>
                                        <?php if($docleg->dtra_glosa_posicion==3): ?>
                                        <span style="font-size: 0.8em" class="bg-danger rounded text-white col-sm-8 -underline"> * Actualmente seleccionado </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="<?php echo e(url('img/icon/glosa_pdf_abajo.gif')); ?>"/>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                    <tr class="">
                        <td class="text-dark">
                            <a href="<?php echo e(url('cambiar posicion pdf/'.$docleg->cod_dtra.'/4')); ?>" onclick="$('#docleg').modal('hide');" target="pdf<?php echo e(rand(1,1000)); ?>">
                                <div class="row">
                                    <div class="col-md-8">
                                        Imprimir glosa al final
                                        <br/>
                                        <br/>
                                        <?php if($docleg->dtra_glosa_posicion==4): ?>
                                            <span style="font-size: 0.8em" class="bg-danger rounded text-white col-sm-8 -underline"> * Actualmente seleccionado </span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-2">
                                        <img src="<?php echo e(url('img/icon/glosa_pdf_final.gif')); ?>"/>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                </table>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>


<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/conf_imprimir_glosa.blade.php ENDPATH**/ ?>