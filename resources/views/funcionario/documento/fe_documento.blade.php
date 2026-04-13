<form action="{{url('g_documento/')}}" method="POST" id="form_importar" enctype="multipart/form-data">
    @csrf

    <style>
        input[name="tesis"] {
            accent-color: #4e73df;
        }
    </style>

    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary ">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-university"></i> Facultad</h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="shadow-sm rounded p-2">
                @if($cod_doc==0)
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para nuevo documento</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.8em"> * DATOS DEL DOCUMENTO</span><br/><br/>
                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Título :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" required name="titulo"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de Documento:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo" id="tipo">
                                            <option value="DIPLOMA DE BACHILLER">DIPLOMA DE BACHILLER</option>
                                            <option value="DIPLOMA ACADEMICO">DIPLOMA ACADEMICO</option>
                                            <option value="TITULO PROFESIONAL">TITULO PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Gestión:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="gestion" id="gestion">
                                            <option value=""></option>
                                            @php
                                                $gestion=date('Y');
                                                for ($gestion1=date('Y');$gestion>1960;$gestion--){
                                                    echo "<option value='".$gestion."'>".$gestion."</option>";
                                                }
                                            @endphp

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de emisión:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" name="fecha"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Universidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control form-control-sm border-0" name="universidad" id="universidad">
                                            <option value="">Seleccionar...</option>
                                            @php
                                                $universidades = \App\Models\Universidad::orderByRaw("CASE WHEN tipo='Pública' THEN 1 WHEN tipo='Privada' THEN 2 WHEN tipo='Extranjera' THEN 3 WHEN tipo='Otro' THEN 4 ELSE 5 END")->orderBy('nombre')->get();
                                                $universidadesPorTipo = [];
                                                foreach ($universidades as $uni) {
                                                    $universidadesPorTipo[$uni->tipo][] = $uni;
                                                }
                                            @endphp
                                            @foreach(['Pública' => 'Universidad Pública', 'Privada' => 'Universidad Privada', 'Extranjera' => 'Universidad Extranjera', 'Otro' => 'Otros (CEUB, Instituto, Ministerio de Educación, etc)'] as $tipo => $label)
                                                @if(isset($universidadesPorTipo[$tipo]))
                                                    <optgroup label="{{ $label }}">
                                                        @foreach($universidadesPorTipo[$tipo] as $uni)
                                                            <option value="{{ $uni->sigla }}">{{ $uni->nombre }} ({{ $uni->sigla }})</option>
                                                        @endforeach
                                                    </optgroup>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nro Reválida:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="revalida" id="revalida" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Número de Registro:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="numero_registro" id="numero_registro" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Legalizado:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="legalizado" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Verificado:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="verificado" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Educación superior:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="superior" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento de la UMSS:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="umss"/>
                                    </td>
                                </tr>
                                <tr id="fila_titulo_tesis" style="display: none;">
                                    <th class="text-right font-italic text-dark">Título de Tesis:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="tesis_titulo" id="tesis_titulo" placeholder="Ej: Análisis de sistemas de información"/>
                                    </td>
                                </tr>
                                <tr id="fila_es_tesis" style="display: none;">
                                    <th class="text-right font-italic text-dark">Es tesis:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="checkbox" name="tesis" id="tesis"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Grado:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select-sm custom-select border-0" name="grado" id="grado">
                                            <option></option>
                                            <option value="BACHILLER">BACHILLER</option>
                                            <option value="TECNICO MEDIO">TECNICO MEDIO</option>
                                            <option value="TECNICO SUPERIOR">TECNICO SUPERIOR</option>
                                            <option value="PROFESIONAL">PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento en PDF:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="cf" value="{{$cod_fun}}">
                @else
                    <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                        <h5 class="text-white text-center"> Formulario para editar Funcionario</h5>
                    </div>
                    <hr class="sidebar-divider"/>
                    <span class="text-primary font-weight-bold font-italic float-right" style="font-size: 0.8em"> * DATOS DEL FUNCIONARIO</span><br/><br/>

                    <div class="row">
                        <div class="col-md-5">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Título :</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$documento->doc_titulo}}" required name="titulo"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Tipo de Documento:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="tipo" id="tipo">

                                            <option value="{{$documento->doc_tipo}}">{{$documento->doc_tipo}}</option>
                                            <option value="DIPLOMA DE BACHILLER">DIPLOMA DE BACHILLER</option>
                                            <option value="DIPLOMA ACADEMICO">DIPLOMA ACADEMICO</option>
                                            <option value="TITULO PROFESIONAL">TITULO PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Gestión:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control border-0 form-control-sm" name="gestion" id="gestion">
                                            <option value="{{$documento->doc_gestion}}">{{$documento->doc_gestion}}</option>
                                            @php
                                                $gestion=date('Y');
                                                for ($gestion1=date('Y');$gestion>1960;$gestion--){
                                                    echo "<option value='".$gestion."'>".$gestion."</option>";
                                                }
                                            @endphp

                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Fecha de emisión:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="date" class="form-control form-control-sm border-0" value="{{$documento->doc_fecha_emision}}" name="fecha"/>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Universidad:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="form-control form-control-sm border-0" name="universidad" id="universidad">
                                            <option value="{{ $documento->doc_universidad }}">{{ $documento->doc_universidad }}</option>
                                            @php
                                                $universidades = \App\Models\Universidad::orderByRaw("CASE WHEN tipo='Pública' THEN 1 WHEN tipo='Privada' THEN 2 WHEN tipo='Extranjera' THEN 3 WHEN tipo='Otro' THEN 4 ELSE 5 END")->orderBy('nombre')->get();
                                                $universidadesPorTipo = [
                                                    'Pública' => [],
                                                    'Privada' => [],
                                                    'Extranjera' => [],
                                                    'Otro' => []
                                                ];
                                                foreach ($universidades as $uni) {
                                                    if ($uni->sigla !== $documento->doc_universidad && isset($universidadesPorTipo[$uni->tipo])) {
                                                        $universidadesPorTipo[$uni->tipo][] = $uni;
                                                    }
                                                }
                                            @endphp
                                            <optgroup label="Universidad Pública">
                                                @forelse($universidadesPorTipo['Pública'] as $uni)
                                                    <option value="{{ $uni->sigla }}">{{ $uni->nombre }} ({{ $uni->sigla }})</option>
                                                @empty
                                                @endforelse
                                            </optgroup>
                                            <optgroup label="Universidad Privada">
                                                @forelse($universidadesPorTipo['Privada'] as $uni)
                                                    <option value="{{ $uni->sigla }}">{{ $uni->nombre }} ({{ $uni->sigla }})</option>
                                                @empty
                                                @endforelse
                                            </optgroup>
                                            <optgroup label="Universidad Extranjera">
                                                @forelse($universidadesPorTipo['Extranjera'] as $uni)
                                                    <option value="{{ $uni->sigla }}">{{ $uni->nombre }} ({{ $uni->sigla }})</option>
                                                @empty
                                                @endforelse
                                            </optgroup>
                                            <optgroup label="Otros (CEUB, Instituto, Ministerio de Educación, etc)">
                                                @forelse($universidadesPorTipo['Otro'] as $uni)
                                                    <option value="{{ $uni->sigla }}">{{ $uni->nombre }} ({{ $uni->sigla }})</option>
                                                @empty
                                                @endforelse
                                            </optgroup>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Nro Reválida:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$documento->doc_numero_revalida}}" name="revalida" id="revalida" />
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Número de Registro:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" value="{{$documento->doc_numero_registro ?? ''}}" name="numero_registro" id="numero_registro" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-7">
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-right font-italic text-dark">Legalizado:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_legalizado=='t')
                                            <input type="checkbox" name="legalizado" checked />
                                        @else
                                            <input type="checkbox" name="legalizado"/>
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Verificado:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_verificado=='t')
                                            <input type="checkbox" name="verificado" checked/>
                                        @else
                                            <input type="checkbox" name="verificado" />
                                        @endif


                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Educación superior:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_edu_superior=='t')
                                            <input type="checkbox" name="superior" checked/>
                                        @else
                                            <input type="checkbox" name="superior" />
                                        @endif

                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento de la UMSS:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_umss=='t')
                                            <input type="checkbox" name="umss" checked/>
                                        @else
                                            <input type="checkbox" name="umss"/>
                                        @endif
                                    </td>
                                </tr>
                                <tr id="fila_titulo_tesis_edit" style="display: none;">
                                    <th class="text-right font-italic text-dark">Título de Tesis:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="text" class="form-control form-control-sm border-0" name="tesis_titulo" id="tesis_titulo_edit" value="{{$documento->doc_tesis_titulo ?? ''}}"/>
                                    </td>
                                </tr>
                                <tr id="fila_es_tesis_edit" style="display: none;">
                                    <th class="text-right font-italic text-dark">Es tesis:</th>
                                    <td class="border-bottom border-dark">
                                        @if($documento->doc_tesis=='t')
                                            <input type="checkbox" name="tesis" id="tesis_edit" checked/>
                                        @else
                                            <input type="checkbox" name="tesis" id="tesis_edit"/>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Grado:</th>
                                    <td class="border-bottom border-dark">
                                        <select class="custom-select-sm custom-select border-0" name="grado" id="grado">
                                            @if($documento->doc_grado!='')
                                                <option value="{{$documento->doc_grado}}">{{$documento->doc_grado}}</option>
                                            @endif
                                            <option></option>
                                            <option value="BACHILLER">BACHILLER</option>
                                            <option value="TECNICO MEDIO">TECNICO MEDIO</option>
                                            <option value="TECNICO SUPERIOR">TECNICO SUPERIOR</option>
                                            <option value="PROFESIONAL">PROFESIONAL</option>
                                            <option value="DIPLOMADO">DIPLOMADO</option>
                                            <option value="MAESTRIA">MAESTRIA</option>
                                            <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                            <option value="DOCTORADO">DOCTORADO</option>
                                            <option value="OTRO">OTRO</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic text-dark">Documento en PDF:</th>
                                    <td class="border-bottom border-dark">
                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <input type="hidden" name="cd" value="{{$documento->cod_doc}}">
                    <input type="hidden" name="cf" value="{{$cod_fun}}">
                @endif
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
            <input class="btn btn-primary" type="submit" value="Guardar"/>
        </div>
    </div>
</form>

<script>
    // Función para mostrar/ocultar campos de tesis según el tipo de documento
    function toggleTesisCampos() {
        const tiposConTesis = ['DIPLOMADO', 'MAESTRIA', 'ESPECIALIDAD', 'DOCTORADO'];
        
        // Obtener todos los selects de tipo en la página
        const selects = document.querySelectorAll('select[name="tipo"]');
        
        selects.forEach(selectElement => {
            const valor = selectElement.value;
            const mostrar = tiposConTesis.includes(valor);
            
            // Buscar las filas en el mismo formulario/contenedor
            const form = selectElement.closest('form');
            
            if (form) {
                const filaTitleNew = form.querySelector('#fila_titulo_tesis');
                const filaCheckNew = form.querySelector('#fila_es_tesis');
                const filaTitleEdit = form.querySelector('#fila_titulo_tesis_edit');
                const filaCheckEdit = form.querySelector('#fila_es_tesis_edit');
                
                if (filaTitleNew) filaTitleNew.style.display = mostrar ? '' : 'none';
                if (filaCheckNew) filaCheckNew.style.display = mostrar ? '' : 'none';
                if (filaTitleEdit) filaTitleEdit.style.display = mostrar ? '' : 'none';
                if (filaCheckEdit) filaCheckEdit.style.display = mostrar ? '' : 'none';
            }
        });
    }
    
    // Función para marcar automáticamente checkbox si hay título de tesis
    function autoCheckTesis(inputElement) {
        const form = inputElement.closest('form');
        if (form) {
            // Buscar checkbox en el mismo formulario
            let checkbox = null;
            
            if (inputElement.id === 'tesis_titulo') {
                checkbox = form.querySelector('input#tesis[type="checkbox"]');
            } else if (inputElement.id === 'tesis_titulo_edit') {
                checkbox = form.querySelector('input#tesis_edit[type="checkbox"]');
            }
            
            if (checkbox) {
                const hasTítulo = inputElement.value.trim() !== '';
                checkbox.checked = hasTítulo;
            }
        }
    }
    
    // Función para validar el desmarcado del checkbox
    function validateTesisCheckbox(checkboxElement) {
        const form = checkboxElement.closest('form');
        if (form) {
            // Buscar el input de título de tesis
            let tituloInput = null;
            
            if (checkboxElement.id === 'tesis') {
                tituloInput = form.querySelector('input#tesis_titulo[type="text"]');
            } else if (checkboxElement.id === 'tesis_edit') {
                tituloInput = form.querySelector('input#tesis_titulo_edit[type="text"]');
            }
            
            if (tituloInput && tituloInput.value.trim() !== '' && !checkboxElement.checked) {
                // El usuario intenta desmarcar pero hay un título, no lo permitimos
                checkboxElement.checked = true;
                alert('No puedes desmarcar "Es tesis" si hay un título de tesis ingresado. Primero borra el título de tesis.');
            }
        }
    }
    
    // Ejecutar cuando se carga el documento
    document.addEventListener('DOMContentLoaded', function() {
        toggleTesisCampos();
        attachEventListeners();
    });
    
    // Función para asignar event listeners
    function attachEventListeners() {
        // Event listeners para cambios en tipo
        document.querySelectorAll('select[name="tipo"]').forEach(select => {
            select.removeEventListener('change', toggleTesisCampos);
            select.addEventListener('change', toggleTesisCampos);
        });
        
        // Event listeners para cambios en título de tesis
        document.querySelectorAll('input[name="tesis_titulo"]').forEach(input => {
            input.removeEventListener('input', function() {
                autoCheckTesis(this);
            });
            input.addEventListener('input', function() {
                autoCheckTesis(this);
            });
        });
        
        // Event listeners para validar cambios en checkbox de tesis
        document.querySelectorAll('input[name="tesis"]').forEach(checkbox => {
            checkbox.removeEventListener('change', function() {
                validateTesisCheckbox(this);
            });
            checkbox.addEventListener('change', function() {
                validateTesisCheckbox(this);
            });
        });
    }
    
    // Usar MutationObserver para detectar cuando se carga nuevo contenido en el modal
    const modalPanel = document.getElementById('panel_documento');
    if (modalPanel) {
        const observer = new MutationObserver(function(mutations) {
            // Pequeño delay para asegurar que el DOM esté completamente actualizado
            setTimeout(function() {
                toggleTesisCampos();
                attachEventListeners();
            }, 100);
        });
        
        observer.observe(modalPanel, {
            childList: true,
            subtree: true
        });
    }
</script>
