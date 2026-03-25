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
        <div class="col-lg-4 mb-4">
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
                                    <th style="width: 70px;">Acciones</th>
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
                                            <button class="btn btn-sm btn-info" type="button" 
                                                onclick="editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $uni->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
        <div class="col-lg-4 mb-4">
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
                                    <th style="width: 70px;">Acciones</th>
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
                                            <button class="btn btn-sm btn-info" type="button" 
                                                onclick="editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $uni->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
        <div class="col-lg-4 mb-4">
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
                                    <th style="width: 70px;">Acciones</th>
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
                                            <button class="btn btn-sm btn-info" type="button" 
                                                onclick="editarUniversidad({{ $uni->id }}, '{{ $uni->nombre }}', '{{ $uni->sigla }}', '{{ $uni->tipo }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminar({{ $uni->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
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
    </div>
</div>

<!-- MODAL NUEVA UNIVERSIDAD -->
<div class="modal fade" id="modalNuevaUniversidad" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="modalLabel"><i class="fas fa-plus"></i> Nueva Universidad</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('crear universidad')}}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" required>
                        @error('nombre')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="sigla">Sigla:</label>
                        <input type="text" class="form-control @error('sigla') is-invalid @enderror" id="sigla" name="sigla" required>
                        @error('sigla')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="custom-select @error('tipo') is-invalid @enderror" id="tipo" name="tipo" required>
                            <option value="">Seleccionar tipo...</option>
                            <option value="Pública">Pública</option>
                            <option value="Privada">Privada</option>
                            <option value="Extranjera">Extranjera</option>
                        </select>
                        @error('tipo')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR UNIVERSIDAD -->
<div class="modal fade" id="modalEditarUniversidad" tabindex="-1" role="dialog" aria-labelledby="modalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="modalLabel2"><i class="fas fa-edit"></i> Editar Universidad</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreEdit">Nombre:</label>
                        <input type="text" class="form-control" id="nombreEdit" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="siglaEdit">Sigla:</label>
                        <input type="text" class="form-control" id="siglaEdit" name="sigla" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoEdit">Tipo:</label>
                        <select class="custom-select" id="tipoEdit" name="tipo" required>
                            <option value="Pública">Pública</option>
                            <option value="Privada">Privada</option>
                            <option value="Extranjera">Extranjera</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Limpiar completamente al cargar
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
});

function abrirModalNueva() {
    // Limpiar
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
    $('.modal').modal('hide');
    
    // Limpiar campos
    document.getElementById('nombre').value = '';
    document.getElementById('sigla').value = '';
    document.getElementById('tipo').value = '';
    
    // Abrir
    setTimeout(function() {
        $('#modalNuevaUniversidad').modal('show');
    }, 50);
}

function editarUniversidad(id, nombre, sigla, tipo) {
    // Limpiar
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
    $('.modal').modal('hide');
    
    // Rellenar formulario
    document.getElementById('nombreEdit').value = nombre;
    document.getElementById('siglaEdit').value = sigla;
    document.getElementById('tipoEdit').value = tipo;
    document.getElementById('formEditar').action = "{{url('actualizar universidad')}}/" + id;
    
    // Abrir
    setTimeout(function() {
        $('#modalEditarUniversidad').modal('show');
    }, 50);
}

function confirmarEliminar(id) {
    if(confirm('¿Estás seguro de que deseas eliminar esta universidad?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{url('eliminar universidad')}}/" + id;
        
        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{csrf_token()}}';
        form.appendChild(csrf);
        
        var method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Limpiar cuando se cierre un modal
$(document).on('hidden.bs.modal', '.modal', function() {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
});

// Prevenir que se agregue backdrop
$(document).on('show.bs.modal', '.modal', function() {
    $('.modal-backdrop').remove();
});
</script>
@endsection

