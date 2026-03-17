<script src="<?php echo e(asset('js/tinymce/tinymce.min.js')); ?>"></script>

<form action="<?php echo e(url('g_legalizacion')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Trámite </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <?php                                $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                    $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                    $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                    $tipo_tramite['B']='BUSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                    $tipo_tramite['A']='NO-ATENTADO';$tipo_tramite['AC']='bg-primary text-white';
                                                    $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                ?>

                <h6 class="text-white text-center">Formulario para editar trámite</h6>
            </div>
            <hr class="sidebar-divider"/>
            <?php if($cod_tre==0): ?>
                <div class="row">
                    <div class="col-sm-5">
                        <span class="text-primary font-italic font-weight-bold">* Datos del trámite</span>
                        <table class="table-hover col-md-12">
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="nombre" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">N° Cuenta : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="cuenta" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Costo (Bs.): </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="costo" pattern="[0-9]{1,4}"/></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Duración (Hrs): </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="duracion"/></td>
                            </tr>
                            <?php if($tipo=='L' || $tipo=='C' || $tipo=='A'): ?>
                            <tr>
                                <th class="text-right font-italic border-bottom">Buscar en :</th>
                                <td class="border-bottom border-dark">
                                    <select class="custom-select custom-select-sm border-0 " name="buscar_en">
                                        <option></option>
                                        <option value="db">DB</option>
                                        <option value="ca">CA</option>
                                        <option value="da">DA</option>
                                        <option value="tp">TP</option>
                                        <option value="di">DI</option>
                                        <option value="tpos">TPOS</option>
                                        <option value="re">RE</option>
                                        <option value="su">SU</option>
                                        <option value="res">RESOLUCION</option>
                                        <option value="db-ant">DB-ANTECEDENTE</option>
                                        <option value="ca-ant">CA-ANTECEDENTE</option>
                                        <option value="da-ant">DA-ANTECEDENTE</option>
                                        <option value="tp-ant">TP-ANTECEDENTE</option>
                                        <option value="di-ant">DI-ANTECEDENTE</option>
                                        <option value="tpos-ant">TPOS-ANTECEDENTE</option>
                                        <option value="re-ant">RE-ANTECEDENTE</option>
                                        <option value="su-ant">SU-ANTECEDENTE</option>

                                    </select>
                                </td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th class="text-right font-italic">Descripción : </th>
                                <td class="border-bottom border-dark">
                                    <textarea class="form-control border-0" rows="5" name="desc" id="desc"></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <?php if($tipo=='L' || $tipo=='C' || $tipo=='A' || $tipo=='E'): ?>
                        <div class="col-sm-7">
                            <span class="text-primary font-italic font-weight-bold">* Datos de glosa</span>
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic"> Título de glosa: </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control border-0" rows="2" name="titulo" id="titulo"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic"> Título de glosa (Interno): </th>
                                    <td class="border-bottom border-dark">
                                        <textarea class="form-control border-0" rows="2" name="titulo_interno" id="titulo_interno"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Solo sello: </th>
                                    <td class="border-bottom border-dark">
                                        &nbsp;&nbsp; <input type="checkbox" name="sello">
                                    </td>
                                </tr>
                            </table>
                        </div>
                    <?php endif; ?>
                    <input type="hidden" name="tipo" value="<?php echo e($tipo); ?>">
                </div>
            <?php else: ?>
                <div class="row">

                    <?php if($tramite->tre_tipo=='L' || $tramite['tre_tipo']=='C'): ?>
                        <div class="col-sm-5">
                    <?php else: ?>
                                <div class="col-md-12">
                    <?php endif; ?>
                                    <span class="text-primary font-italic font-weight-bold">* Datos del trámite de:
                                        <span class="font-italic font-weight-bold rounded pl-2 pr-2 <?php echo e($tipo_tramite[$tramite['tre_tipo'].'C']); ?>" style="font-size: 0.8em">
                                                <?php echo e($tipo_tramite[$tramite['tre_tipo']]); ?>

                                            </span>
                                        </span>
                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">Nombre : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" value="<?php echo e($tramite['tre_nombre']); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">N° Cuenta : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="cuenta" value="<?php echo e($tramite['tre_numero_cuenta']); ?>" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Costo (Bs.): </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="costo" pattern="[0-9]{1,4}" value="<?php echo e($tramite['tre_costo']); ?>"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Duración (Hrs): </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="duracion" value="<?php echo e($tramite['tre_duracion']); ?>"/></td>
                                        </tr>
                                        <?php if($tipo=='L' || $tipo=='C' || $tipo=='A'): ?>
                                        <tr>
                                            <th class="text-right font-italic border-bottom">Buscar en :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="buscar_en">
                                                    <option value="<?php echo e($tramite['tre_buscar_en']); ?>"><?php echo e(strtoupper($tramite['tre_buscar_en'])); ?></option>
                                                    <option></option>
                                                    <option value="db">DB</option>
                                                    <option value="ca">CA</option>
                                                    <option value="da">DA</option>
                                                    <option value="tp">TP</option>
                                                    <option value="di">DI</option>
                                                    <option value="tpos">TPOS</option>
                                                    <option value="re">RE</option>
                                                    <option value="su">SU</option>
                                                    <option value="res">RESOLUCION</option>
                                                    <option value="db-ant">DB-ANT</option>
                                                    <option value="ca-ant">CA-ANT</option>
                                                    <option value="da-ant">DA-ANT</option>
                                                    <option value="tp-ant">TP-ANT</option>
                                                    <option value="di-ant">DI-ANT</option>
                                                    <option value="tpos-ant">TPOS-ANT</option>
                                                    <option value="re-ant">RE-ANT</option>
                                                    <option value="su-ant">SU-ANT</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                        <tr>
                                            <th class="text-right font-italic">Descripción : </th>
                                            <td class="border-bottom border-dark">
                                                <textarea class="form-control border-0" rows="5" name="desc" id="desc"><?php echo e($tramite['tre_desc']); ?></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <?php if($tramite['tre_tipo']=='L' || $tramite['tre_tipo']=='C' || $tramite['tre_tipo']=='A' || $tramite['tre_tipo']=='E'): ?>
                                    <div class="col-sm-7">
                                        <span class="text-primary font-italic font-weight-bold">* Datos de glosa</span>
                                        <table class="col-md-12">
                                            <tr>
                                                <th class="text-right font-italic"> Título de glosa: </th>
                                                <td class="border-bottom border-dark">
                                                    <textarea class="form-control border-0" rows="2" name="titulo" id="titulo"><?php echo e($tramite['tre_titulo']); ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic"> Título de glosa (Interno): </th>
                                                <td class="border-bottom border-dark">
                                                    <textarea class="form-control border-0" rows="2" name="titulo_interno" id="titulo_interno"><?php echo e($tramite['tre_titulo_interno']); ?></textarea>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Solo sello: </th>
                                                <td class="border-bottom border-dark">
                                                    <?php if($tramite->tre_solo_sello=='t'): ?>
                                                        &nbsp;&nbsp; <input type="checkbox" name="sello" checked>
                                                    <?php else: ?>
                                                        &nbsp;&nbsp; <input type="checkbox" name="sello">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                <?php endif; ?>
                        </div>
                        <input type="hidden" name="ct" value="<?php echo e($tramite['cod_tre']); ?>">
            <?php endif; ?>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            <input class="btn btn-primary" type="submit" value="Aceptar"/>
        </div>
    </div>

</form>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tramite/fe_tramite.blade.php ENDPATH**/ ?>