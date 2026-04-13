<div class="modal-content">
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bold text-white">Editar Formulario de Conformidad {{ $formulario->codigo }}</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body shadow p-4">
        <form method="POST" action="{{ url('editar_conformidad') }}">
            @csrf
            <input type="hidden" name="cod_fcon" value="{{ $formulario->cod_fcon }}">
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label>Lugar de trabajo</label>
                    <select class="form-control" name="lugar_trabajo" required>
                        <option value="">Seleccione lugar de trabajo</option>
                        <option value="Facultad de Economía" {{ $formulario->lugar_trabajo == 'Facultad de Economía' ? 'selected' : '' }}>Facultad de Economía</option>
                        <option value="Facultad de Arquitectura" {{ $formulario->lugar_trabajo == 'Facultad de Arquitectura' ? 'selected' : '' }}>Facultad de Arquitectura</option>
                        <option value="Facultad de Derecho" {{ $formulario->lugar_trabajo == 'Facultad de Derecho' ? 'selected' : '' }}>Facultad de Derecho</option>
                        <option value="Facultad de Ciencias Jurídicas" {{ $formulario->lugar_trabajo == 'Facultad de Ciencias Jurídicas' ? 'selected' : '' }}>Facultad de Ciencias Jurídicas</option>
                        <option value="Facultad de Ingeniería" {{ $formulario->lugar_trabajo == 'Facultad de Ingeniería' ? 'selected' : '' }}>Facultad de Ingeniería</option>
                        <option value="Facultad de Humanidades" {{ $formulario->lugar_trabajo == 'Facultad de Humanidades' ? 'selected' : '' }}>Facultad de Humanidades</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label>Carrera</label>
                    <input type="text" class="form-control" name="carrera" value="{{ $formulario->carrera }}" required>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group col-md-12">
                    <label>Observaciones</label>
                    <textarea class="form-control" name="observaciones" rows="2">{{ $formulario->observaciones }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-3">
                <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>
