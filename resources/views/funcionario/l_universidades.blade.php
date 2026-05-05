@extends('marco.pagina')

@section('contenido')
<style>
    /* Eliminar backdrop completamente */
    .modal-backdrop {
        display: none !important;
        opacity: 0 !important;
        z-index: -9999 !important;
    }
    
    /* Asegurar que el modal se vea correctamente */
    .modal.show {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-dialog {
        z-index: 9999 !important;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-graduation-cap"></i> Universidades</h1>
        <button type="button" class="btn btn-primary" onclick="abrirModalNueva()">
            <i class="fas fa-plus"></i> Nueva Universidad
        </button>
    </div>

    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> {!! session('exito') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {!! session('error') !!}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- UNIVERSIDADES PÚBLICAS -->
        <div class="col-12 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-university text-primary"></i> Universidades Públicas
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universidadesPublicas as $uni)
                                    <tr>
                                        <td class="text-dark">{{ $uni->nombre }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $uni->sigla }}</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" onclick="return editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')" title="Editar universidad">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" onclick="confirmarEliminar({{ $uni->id }}); return false;" title="Eliminar universidad">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay universidades públicas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- UNIVERSIDADES PRIVADAS -->
        <div class="col-12 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-building text-success"></i> Universidades Privadas
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universidadesPrivadas as $uni)
                                    <tr>
                                        <td class="text-dark">{{ $uni->nombre }}</td>
                                        <td>
                                            <span class="badge badge-success">{{ $uni->sigla }}</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" onclick="return editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')" title="Editar universidad">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" onclick="confirmarEliminar({{ $uni->id }}); return false;" title="Eliminar universidad">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay universidades privadas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- UNIVERSIDADES EXTRANJERAS -->
        <div class="col-12 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-globe text-warning"></i> Universidades Extranjeras
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universidadesExtranjeras as $uni)
                                    <tr>
                                        <td class="text-dark">{{ $uni->nombre }}</td>
                                        <td>
                                            <span class="badge badge-warning">{{ $uni->sigla }}</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" onclick="return editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')" title="Editar universidad">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" onclick="confirmarEliminar({{ $uni->id }}); return false;" title="Eliminar universidad">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay universidades extranjeras</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- OTROS (CEUB, INSTITUTOS, MINISTERIO, ETC) -->
        <div class="col-12 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-building text-info"></i> Otros (CEUB, Institutos, Ministerio de Educación, etc)
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($universidadesOtros as $uni)
                                    <tr>
                                        <td class="text-dark">{{ $uni->nombre }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $uni->sigla }}</span>
                                        </td>
                                        <td>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" onclick="return editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')" title="Editar universidad">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-danger" onclick="confirmarEliminar({{ $uni->id }}); return false;" title="Eliminar universidad">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay otras instituciones</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVA UNIVERSIDAD -->
<div class="modal fade" id="modalNuevaUniversidad" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #184e77 0%, #1e6091 45%, #2a6f97 100%);">
                <div>
                    <h5 class="modal-title font-weight-bolder mb-0" id="modalLabel"><i class="fas fa-plus mr-1"></i> Nueva Universidad</h5>
                    <small class="text-white-50">Registro de institución académica</small>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('crear universidad')}}" method="POST">
                @csrf
                <div class="modal-body p-0" style="background: linear-gradient(180deg, #f8fbff 0%, #eef5fb 100%);">
                    <div class="p-3 p-md-4">
                        <div class="border rounded-lg shadow-sm bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <div class="text-primary font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">Datos de la universidad</div>
                                <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">Nuevo registro</span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nombre" class="text-secondary font-italic">Nombre:</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror shadow-sm" id="nombre" name="nombre" value="{{ old('nombre') }}" required style="text-transform: uppercase; border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <small id="nombreError" class="text-danger d-none"></small>
                                @error('nombre')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="sigla" class="text-secondary font-italic">Sigla:</label>
                                <input type="text" class="form-control @error('sigla') is-invalid @enderror shadow-sm" id="sigla" name="sigla" value="{{ old('sigla') }}" required style="text-transform: uppercase; border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <small id="siglaError" class="text-danger d-none"></small>
                                @error('sigla')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="tipo" class="text-secondary font-italic">Tipo:</label>
                                <select class="custom-select @error('tipo') is-invalid @enderror shadow-sm" id="tipo" name="tipo" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                    <option value="">Seleccionar tipo...</option>
                                    <option value="Pública">Pública</option>
                                    <option value="Privada">Privada</option>
                                    <option value="Extranjera">Extranjera</option>
                                    <option value="Otro">Otro (CEUB, Institutos, Ministerio de Educación, etc)</option>
                                </select>
                                @error('tipo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0" style="background: #f8fbff;">
                    <button type="button" class="btn btn-light border px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 shadow-sm" id="btnGuardarUniversidad" disabled style="background: linear-gradient(135deg, #1d4e89 0%, #2c7da0 100%); border: none;">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR UNIVERSIDAD -->
<div class="modal fade" id="modalEditarUniversidad" tabindex="-1" role="dialog" aria-labelledby="modalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #0f4c5c 0%, #1d6f8a 48%, #3aa6b9 100%);">
                <div>
                    <h5 class="modal-title font-weight-bolder mb-0" id="modalLabel2"><i class="fas fa-edit mr-1"></i> Editar Universidad</h5>
                    <small class="text-white-50">Actualización de datos institucionales</small>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-0" style="background: linear-gradient(180deg, #f7fbfc 0%, #eef7fa 100%);">
                    <div class="p-3 p-md-4">
                        <div class="border rounded-lg shadow-sm bg-white p-3">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <div class="text-info font-weight-bold text-uppercase" style="letter-spacing: .04em; font-size: .78rem;">Datos de la universidad</div>
                                <span class="badge px-3 py-2" style="background: #dbeafe; color: #1d4ed8;">Edición</span>
                            </div>
                            <div class="form-group mb-3">
                                <label for="nombreEdit" class="text-secondary font-italic">Nombre:</label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror shadow-sm" id="nombreEdit" name="nombre" value="{{ old('nombre') }}" required style="text-transform: uppercase; border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <small id="nombreEditError" class="text-danger d-none"></small>
                                @error('nombre')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="siglaEdit" class="text-secondary font-italic">Sigla:</label>
                                <input type="text" class="form-control @error('sigla') is-invalid @enderror shadow-sm" id="siglaEdit" name="sigla" value="{{ old('sigla') }}" required style="text-transform: uppercase; border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                <small id="siglaEditError" class="text-danger d-none"></small>
                                @error('sigla')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group mb-0">
                                <label for="tipoEdit" class="text-secondary font-italic">Tipo:</label>
                                <select class="custom-select @error('tipo') is-invalid @enderror shadow-sm" id="tipoEdit" name="tipo" required style="border: 1px solid #c7d7ea; border-radius: .8rem; background: #fff;">
                                    <option value="Pública">Pública</option>
                                    <option value="Privada">Privada</option>
                                    <option value="Extranjera">Extranjera</option>
                                    <option value="Otro">Otro (CEUB, Institutos, Ministerio de Educación, etc)</option>
                                </select>
                                @error('tipo')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0" style="background: #f7fbfc;">
                    <button type="button" class="btn btn-light border px-4" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info px-4 shadow-sm" id="btnActualizarUniversidad" disabled style="background: linear-gradient(135deg, #0f4c5c 0%, #1d6f8a 100%); border: none;">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL CONFIRMAR ELIMINACIÓN -->
<div class="modal fade" id="modalConfirmarEliminar" tabindex="-1" role="dialog" aria-labelledby="modalLabelEliminar" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, #8a1538 0%, #c1121f 48%, #e5383b 100%);">
                <div>
                    <h5 class="modal-title font-weight-bolder mb-0" id="modalLabelEliminar"><i class="fas fa-exclamation-triangle mr-1"></i> Confirmar Eliminación</h5>
                    <small class="text-white-50">Acción irreversible sobre el registro</small>
                </div>
                <button type="button" class="close text-white opacity-100" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" style="background: linear-gradient(180deg, #fff7f7 0%, #fff1f2 100%);">
                <div class="p-3 p-md-4">
                    <div class="alert alert-danger border-0 shadow-sm mb-0 d-flex align-items-start" style="border-left: 4px solid #dc3545;">
                        <div class="font-weight-bolder mr-3" style="font-size: 1.7rem; line-height: 1;">!</div>
                        <div>
                            <div class="font-weight-bolder">¿Estás seguro de que deseas eliminar esta universidad?</div>
                            <div>Esta acción no se puede deshacer.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0" style="background: #fff7f7;">
                <button type="button" class="btn btn-light border px-4" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger px-4 shadow-sm" onclick="confirmarEliminacionModal()" style="background: linear-gradient(135deg, #c1121f 0%, #e5383b 100%); border: none;">Eliminar</button>
            </div>
        </div>
    </div>
</div>

<script>
var tiempoValidacionCrear = null;
var tiempoValidacionEditar = null;

document.addEventListener('DOMContentLoaded', function() {
    // Obtener token CSRF
    var metaToken = document.querySelector('meta[name="csrf-token"]');
    var inputToken = document.querySelector('input[name="_token"]');
    window.CSRFToken = metaToken ? metaToken.getAttribute('content') : (inputToken ? inputToken.value : '{{ csrf_token() }}');
    
    console.log('Token CSRF:', window.CSRFToken);
    
    // Convertir inputs a mayúsculas automáticamente
    var nombre = document.getElementById('nombre');
    var sigla = document.getElementById('sigla');
    var nombreEdit = document.getElementById('nombreEdit');
    var siglaEdit = document.getElementById('siglaEdit');
    
    if (nombre) {
        nombre.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        nombre.addEventListener('keyup', function() {
            clearTimeout(tiempoValidacionCrear);
            tiempoValidacionCrear = setTimeout(validarCrear, 300);
        });
        nombre.addEventListener('change', function() {
            clearTimeout(tiempoValidacionCrear);
            tiempoValidacionCrear = setTimeout(validarCrear, 300);
        });
    }
    
    if (sigla) {
        sigla.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        sigla.addEventListener('keyup', function() {
            clearTimeout(tiempoValidacionCrear);
            tiempoValidacionCrear = setTimeout(validarCrear, 300);
        });
        sigla.addEventListener('change', function() {
            clearTimeout(tiempoValidacionCrear);
            tiempoValidacionCrear = setTimeout(validarCrear, 300);
        });
    }
    
    if (nombreEdit) {
        nombreEdit.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        nombreEdit.addEventListener('keyup', function() {
            clearTimeout(tiempoValidacionEditar);
            tiempoValidacionEditar = setTimeout(validarEditar, 300);
        });
        nombreEdit.addEventListener('change', function() {
            clearTimeout(tiempoValidacionEditar);
            tiempoValidacionEditar = setTimeout(validarEditar, 300);
        });
    }
    
    if (siglaEdit) {
        siglaEdit.addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });
        siglaEdit.addEventListener('keyup', function() {
            clearTimeout(tiempoValidacionEditar);
            tiempoValidacionEditar = setTimeout(validarEditar, 300);
        });
        siglaEdit.addEventListener('change', function() {
            clearTimeout(tiempoValidacionEditar);
            tiempoValidacionEditar = setTimeout(validarEditar, 300);
        });
    }
    
    
    var tipo = document.getElementById('tipo');
    var tipoEdit = document.getElementById('tipoEdit');
    
    if (tipo) {
        tipo.addEventListener('change', function() {
            clearTimeout(tiempoValidacionCrear);
            tiempoValidacionCrear = setTimeout(validarCrear, 300);
        });
    }
    
    if (tipoEdit) {
        tipoEdit.addEventListener('change', function() {
            clearTimeout(tiempoValidacionEditar);
            tiempoValidacionEditar = setTimeout(validarEditar, 300);
        });
    }
});

function validarCrear() {
    var nombre = document.getElementById('nombre').value.trim();
    var sigla = document.getElementById('sigla').value.trim();
    var tipo = document.getElementById('tipo').value.trim();
    var boton = document.getElementById('btnGuardarUniversidad');
    var nombreError = document.getElementById('nombreError');
    var siglaError = document.getElementById('siglaError');
    
    console.log('Validando crear:', { nombre, sigla, tipo });
    
    // Limpiar errores
    if (nombreError) nombreError.classList.add('d-none');
    if (siglaError) siglaError.classList.add('d-none');
    
    // Si campos vacíos, deshabilitar
    if (!nombre || !sigla || !tipo) {
        boton.disabled = true;
        console.log('Campos vacíos, botón deshabilitado');
        return;
    }
    
    // AJAX con fetch
    console.log('Enviando AJAX a verificar-universidad');
    fetch("{{ url('verificar-universidad') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': window.CSRFToken
        },
        body: new URLSearchParams({
            nombre: nombre,
            sigla: sigla,
            _token: window.CSRFToken
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta AJAX:', data);
        if (data.valido) {
            boton.disabled = false;
            if (nombreError) nombreError.classList.add('d-none');
            if (siglaError) siglaError.classList.add('d-none');
            console.log('Datos válidos, botón habilitado');
        } else {
            boton.disabled = true;
            if (data.nombre_existe && nombreError) {
                nombreError.classList.remove('d-none');
                nombreError.textContent = 'Esta universidad ya existe';
            }
            if (data.sigla_existe && siglaError) {
                siglaError.classList.remove('d-none');
                siglaError.textContent = 'Esta sigla ya existe';
            }
            console.log('Datos inválidos, botón deshabilitado');
        }
    })
    .catch(error => {
        console.error('Error en AJAX:', error);
        boton.disabled = true;
    });
}

function validarEditar() {
    var nombre = document.getElementById('nombreEdit').value.trim();
    var sigla = document.getElementById('siglaEdit').value.trim();
    var tipo = document.getElementById('tipoEdit').value.trim();
    var boton = document.getElementById('btnActualizarUniversidad');
    var nombreError = document.getElementById('nombreEditError');
    var siglaError = document.getElementById('siglaEditError');
    var formEditar = document.getElementById('formEditar');
    var id = formEditar.action.split('/').pop();
    
    console.log('Validando editar:', { nombre, sigla, tipo, id });
    
    // Limpiar errores
    if (nombreError) nombreError.classList.add('d-none');
    if (siglaError) siglaError.classList.add('d-none');
    
    // Si campos vacíos, deshabilitar
    if (!nombre || !sigla || !tipo) {
        boton.disabled = true;
        console.log('Campos vacíos, botón deshabilitado');
        return;
    }
    
    // AJAX con fetch
    console.log('Enviando AJAX a verificar-universidad');
    fetch("{{ url('verificar-universidad') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': window.CSRFToken
        },
        body: new URLSearchParams({
            nombre: nombre,
            sigla: sigla,
            id: id,
            _token: window.CSRFToken
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta AJAX:', data);
        if (data.valido) {
            boton.disabled = false;
            if (nombreError) nombreError.classList.add('d-none');
            if (siglaError) siglaError.classList.add('d-none');
            console.log('Datos válidos, botón habilitado');
        } else {
            boton.disabled = true;
            if (data.nombre_existe && nombreError) {
                nombreError.classList.remove('d-none');
                nombreError.textContent = 'Esta universidad ya existe';
            }
            if (data.sigla_existe && siglaError) {
                siglaError.classList.remove('d-none');
                siglaError.textContent = 'Esta sigla ya existe';
            }
            console.log('Datos inválidos, botón deshabilitado');
        }
    })
    .catch(error => {
        console.error('Error en AJAX:', error);
        boton.disabled = true;
    });
}

function abrirModalNueva() {
    document.getElementById('nombre').value = '';
    document.getElementById('sigla').value = '';
    document.getElementById('tipo').value = '';
    
    var nombreError = document.getElementById('nombreError');
    var siglaError = document.getElementById('siglaError');
    if (nombreError) nombreError.classList.add('d-none');
    if (siglaError) siglaError.classList.add('d-none');
    
    document.getElementById('btnGuardarUniversidad').disabled = true;
    
    // Usar Bootstrap para abrir el modal
    var modal = new bootstrap.Modal(document.getElementById('modalNuevaUniversidad'));
    modal.show();
}

function editarUniversidad(id, nombre, sigla, tipo) {
    document.getElementById('nombreEdit').value = nombre;
    document.getElementById('siglaEdit').value = sigla;
    document.getElementById('tipoEdit').value = tipo;
    document.getElementById('formEditar').action = "{{ url('actualizar universidad') }}/" + id;
    
    var nombreError = document.getElementById('nombreEditError');
    var siglaError = document.getElementById('siglaEditError');
    if (nombreError) nombreError.classList.add('d-none');
    if (siglaError) siglaError.classList.add('d-none');
    
    document.getElementById('btnActualizarUniversidad').disabled = false;
    
    // Usar Bootstrap para abrir el modal
    var modal = new bootstrap.Modal(document.getElementById('modalEditarUniversidad'));
    modal.show();
    
    return false;
}

function confirmarEliminar(id) {
    window.idUniversidadAEliminar = id;
    
    // Usar Bootstrap para abrir el modal
    var modal = new bootstrap.Modal(document.getElementById('modalConfirmarEliminar'));
    modal.show();
    
    return false;
}

function confirmarEliminacionModal() {
    var id = window.idUniversidadAEliminar;
    
    var form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ url('eliminar universidad') }}/" + id;
    
    var csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);
    
    var method = document.createElement('input');
    method.type = 'hidden';
    method.name = '_method';
    method.value = 'DELETE';
    form.appendChild(method);
    
    document.body.appendChild(form);
    form.submit();
}

</script>
@endsection
