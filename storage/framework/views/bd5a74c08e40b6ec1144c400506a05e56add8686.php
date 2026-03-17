<div class="modal-dialog" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-signature"></i> Nueva Legalización </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">  Número de trámite</h6>
            </div>
            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="centrar_bloque">
                    <div class="shadow-sm p-2">
                        <h1 class="text-danger pr-3 text-center"><?php echo e($tramite->tra_numero); ?></h1>
                        <span class="font-italic text-dark text-center"><?php if($tramite->tra_fecha_solicitud!=''){echo date('d/m/Y',strtotime($tramite->tra_fecha_solicitud));} ?></span>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>

</div>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/servicios/tra_legalizacion/numero_legalizacion.blade.php ENDPATH**/ ?>