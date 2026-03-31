<form action="{{ url('guardar-conformidad') }}" method="POST" id="form_conformidad">
    @csrf
    <input type="hidden" name="cod_fun" value="{{ $cod_fun ?? 0 }}" />

    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> Nuevo formulario de conformidad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            {{--<div class="mb-3 d-flex justify-content-start">
                <a href="#" class="btn btn-sm btn-secondary mr-2" data-toggle="modal" data-target="#documento" onclick="cargarDatos('{{ url('fe_documento/0/'.$cod_fun) }}','panel_documento')">
                    <i class="fas fa-file"></i> + Documento
                </a>
                <a href="#" class="btn btn-sm btn-secondary" data-toggle="modal" data-target="#documento" onclick="cargarDatos('{{ url('fe_documento titularidad/0/'.$cod_fun) }}','panel_documento')">
                    <i class="fas fa-file-alt"></i> + Titularidad
                </a>
            </div>--}}
            <div class="shadow-sm rounded p-2">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Nombre del Funcionario</label>
                            <input type="text" class="form-control" name="fun_nombre" value="{{ $funcionario->fun_nombre ?? '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>CI</label>
                            <input type="text" class="form-control" name="fun_ci" value="{{ $funcionario->fun_ci ?? '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Teléfonos</label>
                            <input type="text" class="form-control" name="fun_telefonos" value="{{ $funcionario->fun_telefonos ?? '' }}" readonly>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" name="fun_email" value="{{ $funcionario->fun_email ?? '' }}" readonly>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Facultad (lugar de trabajo)</label>
                            <input type="text" class="form-control" name="facultad" value="{{ old('facultad') }}">
                        </div>

                        <div class="form-group">
                            <label>Carrera (lugar de trabajo)</label>
                            <input type="text" class="form-control" name="carrera" value="{{ old('carrera') }}">
                        </div>

                        <div class="form-group">
                            <label>Fecha (dd/mm/aa)</label>
                            <input type="text" class="form-control" name="fecha" value="{{ old('fecha') }}" placeholder="dd/mm/aa" pattern="\d{2}/\d{2}/\d{2}">
                        </div>

                        <div class="form-group">
                            <label>Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 d-flex justify-content-start">
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary mr-2" onclick="cargarDatos('{{ url('fe_documento/0/'.$cod_fun) }}','panel_documento');">
                    <i class="fas fa-file"></i> + Documento
                </a>
                <a href="javascript:void(0)" class="btn btn-sm btn-secondary" onclick="cargarDatos('{{ url('fe_documento titularidad/0/'.$cod_fun) }}','panel_documento');">
                    <i class="fas fa-file-alt"></i> + Titularidad
                </a>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-success">Guardar conformidad</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</form>
