<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Apostilla </h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" style="font-size: smaller">
        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
            <h6 class="text-white text-center">Formulario de búsqueda de apostilla</h6>
        </div>
        <hr class="sidebar-divider"/>
        <div class="row">
            <div class="col-md-4">
                    <form id="form_busqueda_apostilla">
                        <?php echo csrf_field(); ?>
                        <table class="table-hover col-md-12 text-dark">
                            <tr>
                                <th colspan="2" class="text-right text-primary"><br/>* DATOS DE BUSQUEDA <br/><br/></th>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">N° Trámite : </th>
                                <td class="border-bottom border-dark">
                                    <div class="input-group">
                                        &nbsp;&nbsp;
                                        <span style="font-size: 18px" class="font-weight-bold font-italic">
                                            UAD
                                        </span>&nbsp;

                                        <input class="form-control form-control-sm col-md-3" placeholder="" name="numero" />&nbsp;&nbsp; / &nbsp;&nbsp;
                                        <select class="custom-select-sm custom-select col-md-3" name="gestion">
                                            <option></option>
                                            <?php $gestion=date('Y');?>
                                            <?php for($i=2018;$i<=$gestion;$i++): ?>
                                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                            <?php endfor; ?>
                                        </select>
                                    </div>
                                    </td>

                            </tr>
                            <tr>
                                <th class="text-right font-italic">CI : </th>
                                <td class="border-bottom border-dark">

                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           name="ci" /></td>

                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombres : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="nombre" id="nombre" /></td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Apellidos : </th>
                                <td class="border-bottom border-dark">
                                    <input class="form-control form-control-sm border-0" placeholder=""
                                           required name="apellido" id="apellido" /></td>
                            </tr>
                        </table>
                    </form>
                <br/>
                <br/>
                    <a class="btn btn-sm btn-primary text-white" onclick="enviar('form_busqueda_apostilla','<?php echo e(url('buscar tramite apostilla')); ?>','panel_resultado_busqueda')">Buscar</a>
            </div>
            <div class="col-md-8 border rounded shadow">
                <br/>
                <span class="text-primary font-weight-bold">* Resultado de la búsqueda</span>
                <div id="panel_resultado_busqueda">

                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/apostilla/buscar/fe_busqueda.blade.php ENDPATH**/ ?>