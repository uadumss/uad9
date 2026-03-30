<form action="{{url('enviar dpa')}}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="cod_fun" value="{{$cod_fun}}"/>

    <div class="modal-content border-bottom-success">
        <div class="modal-header bg-success">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-paper-plane"></i> Enviar a la DPA</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>

        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                <div class="alert alert-warning mb-3" role="alert">
                    Esta accion marcara al funcionario como <strong>enviado a la DPA</strong>. Si registra un nuevo diploma o titulo, el estado volvera a no enviado.
                </div>

                <p class="mb-2">
                    <span class="text-primary font-italic">Funcionario:</span>
                    <span class="font-weight-bold text-dark">{{$funcionario->fun_nombre}}</span>
                </p>

                <div class="form-group mb-3">
                    <label class="font-weight-bold text-dark">PDF de control de envio</label>
                    <input type="file" class="form-control-file" name="pdf_control" accept="application/pdf,.pdf" required>
                    <small class="form-text text-muted">Solo archivos PDF. Maximo 5 MB.</small>
                </div>

                <div class="form-group mb-0">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="confirmar_envio" name="confirmar_envio" value="1" required>
                        <label class="custom-control-label" for="confirmar_envio">Confirmo que se realizo el envio a la DPA.</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <button class="btn btn-success" type="submit"><i class="fas fa-check-circle"></i> Confirmar envio</button>
        </div>
    </div>
</form>
