<div class="modal-content">
    <div class="modal-header bg-danger text-white">
        <h5 class="modal-title font-weight-bold">Eliminar Formulario de Conformidad {{ $formulario->codigo }}</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body shadow p-4 text-center">
        <i class="fas fa-exclamation-triangle text-danger fa-4x mb-3"></i>
        <h5 class="font-weight-bold">¿Está seguro que desea eliminar el formulario <span>{{ $formulario->codigo }}</span>?</h5>
        <p class="text-muted">
            Los documentos y titularidades asociadas no serán eliminadas, pero dejarán de pertenecer a este formulario.
        </p>
        <form method="POST" action="{{ url('eliminar_conformidad') }}" class="mt-4">
            @csrf
            <input type="hidden" name="cod_fcon" value="{{ $formulario->cod_fcon }}">
            
            <div class="d-flex justify-content-center">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i> Sí, Eliminar</button>
            </div>
        </form>
    </div>
</div>
