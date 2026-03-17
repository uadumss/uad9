
<div class="modal-content border-bottom-primary shadow-lg">
    <div class="modal-header bg-verde-oscuro">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Glosa de Legalización </h5>
        <button class="close text-dark" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-dark" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" >

        <?php if(Session::has('exito')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold"><?php echo session('exito'); ?></span>
            </div>
        <?php endif; ?>
        <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
            <h6 class="text-dark font-weight-bold text-center">Glosa de Legalización</h6>
        </div>
        <?php if($docleg->dtra_falso!='t'): ?>
        <hr class="sidebar-divider"/>
        <div class="row">
            <div class="col-md-4" style="font-size: 0.85em;">
                <table class="table table-sm col-md-11">
                    <tr>
                        <td colspan="2" class="text-right text-primary font-weight-bold font-italic">* Datos personales</td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark font-italic">Nombre : </th>
                        <td class="border-bottom border-dark">
                            <?php echo e($persona->per_apellido." ".$persona->per_nombre); ?>

                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark">CI : </th>
                        <td class="border-bottom border-dark"><?php echo e($persona->per_ci); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark">Trámite : </th>
                        <td class="border-bottom border-dark"><?php echo e($tramite->tre_nombre); ?></td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark">Fecha de solicitud : </th>
                        <td class="border-bottom border-dark"><?php if($tramita->tra_fecha_solicitud!='')
                                    echo date('d/m/Y',strtotime($tramita->tra_fecha_solicitud))
                            ?>
                        </td>
                    </tr>
                </table>
                <?php if($tramita->cod_apo!=''): ?>
                    <hr class="sidebar-divider"/>
                    <table class="table table-sm">
                        <tr>
                            <td colspan="2" class="text-right text-primary font-weight-bold font-italic">* Datos del apoderado</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Nombre apoderado : </th>
                            <td class="border-bottom border-dark">
                                <?php echo e($apoderado->apo_apellido." ".$apoderado->per_nombre); ?>

                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark">CI : </th>
                            <td class="border-bottom border-dark"><?php echo e($apoderado->apo_ci); ?></td>
                        </tr>
                    </table>
                <?php endif; ?>

                <?php if(sizeof($glosas)>0): ?>
                    <hr class="sidebar-divider"/>
                    <table class="table-sm col-sm-11">
                        <tr>
                            <td  colspan="2" class="text-right font-italic font-weight-bold text-primary">* Modelos de glosa</td>
                        </tr>
                        <?php $__currentLoopData = $glosas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($g->cod_glo==$docleg->dtra_cod_glosa): ?>
                            <tr class="alert-danger">
                                <?php else: ?>
                                <tr>
                                <?php endif; ?>
                                <td class="border-dark border-bottom"><?php echo e($g->glo_titulo); ?></td>
                                    <td class="border-dark border-bottom">
                                    <a class="btn btn-sm btn-light btn-circle text-primary"
                                       onclick="cargarDatos('<?php echo e(url("elegir_modelo/".$g->cod_glo.'/'.$docleg->cod_dtra)); ?>','panel_docleg')">
                                        <i class="fas fa-arrow-alt-circle-right"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </table>
                <?php endif; ?>
            </div>
            <!-- ================================GLOSA====================================-->
            <div class="col-md-8 pl-3 shadow-sm border" style="height: 600px">
                <script src="<?php echo e(asset('js/tinymce/tinymce.min.js')); ?>"></script>
                <br/>
                <form id="form_generar_legalización" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-md-2 pr-1 ">
                            <div class="float-right">
                                <?php echo e(QrCode::size(100)->generate($qr_generado)); ?><br/><br/>
                            </div>
                        </div>
                        <div class="col-md-10" id="panel_glosa_legalizacion">
                            <span class="font-weight-bold"><?php echo e($docleg->dtra_titulo); ?></span><br/><br/>
                            <span class="font-weight-bold font-italic"><?php echo e("ARCH ".$docleg->dtra_numero_tramite."/".$docleg->dtra_gestion_tramite); ?></span><br/>
                            <textarea name="glosa" id="glosa1" class="form-control" style="z-index: 3500" rows="15"><?php echo e($docleg->dtra_glosa); ?></textarea>
                            <div class="text-right">
                                <span class="text-right pr-3"><?php echo e($docleg->dtra_fecha_literal); ?></span>
                            </div>
                            <br/>
                            <input type="hidden" name="cdtra" value="<?php echo e($docleg->cod_dtra); ?>">
                            <input type="hidden" name="ctra" value="<?php echo e($docleg->cod_tra); ?>">

                        </div>
                    </div>
                    <table class="col-md-10">
                        <tr>
                            <th>Imprimir en :  </th>
                            <td><img src="<?php echo e(url('img/icon/glosa_pdf_inicio.gif')); ?>" width="35" height="35"/> Inicio<input type="radio" name="posicion" value="0"></td>
                            <td><img src="<?php echo e(url('img/icon/glosa_pdf_arriba.gif')); ?>" width="35" height="35"/> Superior  <input type="radio" name="posicion" value="1"></td>
                            <td><img src="<?php echo e(url('img/icon/glosa_pdf_medio.gif')); ?>" width="35" height="35"/> Medio  <input type="radio" checked name="posicion" value="2"></td>
                            <td><img src="<?php echo e(url('img/icon/glosa_pdf_abajo.gif')); ?>" width="35" height="35"/> Inferior  <input type="radio" name="posicion" value="3"></td>
                            <td><img src="<?php echo e(url('img/icon/glosa_pdf_final.gif')); ?>" width="35" height="35"/> Final  <input type="radio" name="posicion" value="4"></td>
                        </tr>
                    </table>
                </form>
                <script type="text/javascript">
                    tinymce.init({
                        selector: '#glosa1',
                        format: {title: 'Format', items: 'bold italic underline strikethrough superscript subscript | formats | removeformat'},
                    });
                </script>
                <br/>

            </div>

        </div>
        <?php else: ?>
            <br/><br/>
            <div class="border border-danger font-weight-bold font-italic text-danger centrar_bloque col-md-6">
                * El trámite está bloqueado, no se puede generar una glosa
            </div>
        <?php endif; ?>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        <?php if($docleg->dtra_falso!='t'): ?>
            <a class="btn btn-primary btn-sm" href="<?php echo e(url('generar pdf/'.$docleg->cod_dtra)); ?>" target="otro"
                onclick="guardarGlosa('legalizar titulo','panel_traleg','form_generar_legalización');" >Generar PDF</a>
        <?php endif; ?>
    </div>
</div>

<script>
    function guardarGlosa(ruta,panel,form){
        var link = "<?php echo e(url('/')); ?>/"+ruta;
        var token = "<?php echo e(csrf_token()); ?>";
        $('#glosa1').val(tinyMCE.get('glosa1').getContent());
        var form = new FormData($('#'+form).get(0));
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: link,
            type: 'POST',
            contentType: false,
            processData: false,
            data:form,
            //data:$('#form_generar_legalizacion').serialize(),
            success: function (resp) {
                $('#'+panel).html(resp);
                $('#docleg').modal('hide');
                //cargarDatosTabla('ltl_ajax/<?php echo e($fecha); ?>','panel_tabla_tramites');
            },
            error: function (data) {
                $('#error_datos').show();
               // setTimeout(function(){ $('#error_datos').hide(500); }, 4000);
            }
        });
    }
</script>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/glosa_legalizacion.blade.php ENDPATH**/ ?>