<div class="modal-dialog modal-xl" role="document" id="panel_tramite_apostilla">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Apostilla </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <?php if(Session::has('exito')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold"><?php echo session('exito'); ?></span>
                </div>
            <?php endif; ?>
            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para agregar tramite de apostilla</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-4">

                   <div class="shadow-sm p-2 col-md-7 centrar_bloque">
                        <span class="text-primary font-weight-bold"> TRÁMITE</span>
                                        <h1 class="text-danger pr-3 text-center">UAD<?php echo e($tramite_apostilla->apos_numero); ?></h1>
                   </div>
                   <table class="col-md-12 text-dark table table-sm">
                        <tr>
                            <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">CI : </th>
                            <td class="border-bottom border-dark"><?php echo e($persona->per_ci); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Passaporte : </th>
                            <td class="border-bottom border-dark"><?php echo e($persona->per_pasaporte); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre : </th>
                            <td class="border-bottom border-dark"><?php echo e($persona->per_nombre." ".$persona->per_apellido); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Telefono celular : </th>
                            <td class="border-bottom border-dark"><?php echo e($persona->per_celular); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Fecha de ingreso : </th>
                            <td class="border-bottom border-dark"><?php echo e(date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso))); ?></td>
                        </tr>
                        <?php if($apoderado): ?>
                        <tr>
                            <th colspan="2" class="text-right text-primary">* DATOS DEL APODERADO</th>
                        </tr>

                        <tr>
                            <th class="text-right font-italic">CI apoderado: </th>
                            <td class="border-bottom border-dark"><?php echo e($apoderado->apo_ci); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre : </th>
                            <td class="border-bottom border-dark"><?php echo e($apoderado->apo_nombre." ".$apoderado->apo_apellido); ?></td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                            <td class="border-bottom border-dark">
                                <?php if($tramite_apostilla->apos_apoderado=='d'): ?>
                                    &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                <?php else: ?>
                                    <?php if($tramite_apostilla->apos_apoderado=='p'): ?>
                                        &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                <?php endif; ?>
                            <?php endif; ?>
                        </tr>
                        <?php endif; ?>
                   </table>

                    <br/>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                <div class="col-md-7 pl-3 border shadow pt-2">
                    <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Caracteristicas del trámite</span>
                    <br/>
                    <br/>
                    <br/>
                    <br/>
                    <form id="form_agregar_tramite">
                        <?php echo csrf_field(); ?>
                        <table class="">
                            <tr>
                                <th class="text-dark font-italic">Nombre del trámite : </th>
                                <td class="border-bottom border-dark"><?php echo e($apostilla->lis_nombre); ?></td>
                            </tr>
                            <?php if($apostilla->lis_tipo=='sid'): ?>
                                <tr>
                                    <th class="text-dark font-italic">Numero del trámite : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group pt-1">
                                            <input type="text" class="form-control form-control-sm col-md-2" name="numero"> &nbsp;&nbsp;/&nbsp;&nbsp; Gestión : &nbsp;&nbsp;
                                            <input type="text" class="form-control form-control-sm col-md-2" pattern="[0-9]{4}" name="gestion">
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <tr>
                                    <th class="text-dark font-italic">Numero del título : </th>
                                    <td class="border-bottom border-dark">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" name="numero"> &nbsp;&nbsp;/&nbsp;&nbsp; Gestión : &nbsp;&nbsp;
                                            <input type="text" class="form-control form-control-sm" pattern="[0-9]{4}" name="gestion">
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </table>
                        <input type="hidden" name="cl" value="<?php echo e($cod_lis); ?>">
                        <input type="hidden" name="ca" value="<?php echo e($cod_apos); ?>">
                    </form>
                    <br/>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary btn-sm" onclick="enviar('form_agregar_tramite','<?php echo e(url("guardar agregar tramite apostilla")); ?>','panel_lista_tramites_apostilla')
            ;cargarDatos('<?php echo e(url("listar tramite apostilla tabla/".date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso)))); ?>','panel_tabla_tramites');$('#tramite_apostilla').modal('hide'); ">+ Agregar </button>
        </div>
    </div>
</div>


<?php /**PATH C:\xampp\htdocs\uad9\resources\views/apostilla/tramite/fe_agregar_tramite_apostilla.blade.php ENDPATH**/ ?>