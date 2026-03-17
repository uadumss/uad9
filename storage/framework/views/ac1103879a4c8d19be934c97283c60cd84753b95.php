<div class="modal-dialog modal-lg" role="document">
    <form action="<?php echo e(url('verificar importacion legalizacion/')); ?>" method="POST" id="form_importar" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <div class="modal-content border-bottom-primary">
            <div class="modal-header bg-primary">
                <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Nueva importación</h5>
                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                    <span class="text-white" aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="shadow-sm rounded p-2">
                    <h5 class="text-primary text-center">Importar Archivo</h5>
                    <br/>
                    <table class="col-md-12">
                        <tr>
                            <th class="text-right font-italic">Archivo :</th>
                            <td class="">
                                <div class="custom-file mb-3">
                                    <input type="file" class="form-control form-control-file" id="archivo" name="archivo" accept=".xlsx,.xls" required>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                <input class="btn btn-primary" type="submit" value="Enviar"/>
            </div>
        </div>
    </form>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/importar/fe_importar_legalizacion.blade.php ENDPATH**/ ?>