<div class="modal-dialog modal-lg" role="document" id="panel_tramite_apostilla">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Candidatos </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            <div class="bg-verde-oscuro centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para importar candidatos</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div class="alert alert-danger alert-dismissible" id="panel_error_archivo" style="display: none">
                <div id="error_archivo" class="font-weight-bold">
                    * Ocurrio un error desconocido, revise que el archivo es el correcto
                </div>
            </div>
            <br/>
            <span class="text-primary">* Seleccione el archivo excel que contiene a los candidatos</span>
            <br/>
            <br/>
            <form id="form_excel" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="input-group">
                    <span class="font-weight-bold">Archivo : </span> &nbsp;&nbsp;
                    <input type="file"  class="form-control form-control-sm border-success" accept=".xlsx" name="lista">
                    <input type="hidden" name="cd" value="<?php echo e($cod_dtra); ?>">
                </div>
            </form>
            <br/>
            <div class="alert-danger p-2 rounded font-weight-bold"> <i class="fas fa-info-circle text-danger" style="font-size: 16px"> </i> El archivo excel debe contener las columnas : apellido, nombre, ci, cargo, unidad, sis</div>
            <br/>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-primary" type="button" onclick="enviarExcel('<?php echo e(url('guardar candidato excel convocatoria')); ?>','panel_noatentado');cargarDatos('<?php echo e(url('actualizar lista tramite convocatoria/'.$tramite_noatentado->cod_con)); ?>','panel_lista_tramites')">Guardar</button>
        </div>
    </div>
</div>
<script>
    function enviarExcel(ruta,panel){
        var token = "<?php echo e(csrf_token()); ?>";
        var form = new FormData($('#form_excel').get(0));
        $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
        $.ajax({
            url: ruta,
            type: 'POST',
            contentType: false,
            processData: false,
            data:form,
            success: function (resp) {
                $('#'+panel).html(resp);
                $('#Noatentado_agregar').modal('hide');
            },
            error: function (data) {
                $('#panel_error_archivo').show();
            }
        });
    }
</script>

<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/tramite/fe_agregar_excel.blade.php ENDPATH**/ ?>