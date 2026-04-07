@extends('marco.pagina')
@section('contenido')
    <style>
        .report-shell {
            border: 1px solid #d8e2ef;
            background: linear-gradient(160deg, #f7fbff 0%, #ffffff 100%);
        }
        .report-title {
            color: #15436d;
            font-weight: 700;
            letter-spacing: 0.2px;
        }
        .report-subtitle {
            color: #486581;
            font-size: 0.9rem;
        }
        .help-strip {
            border: 1px solid #bee3f8;
            background: #ebf8ff;
            color: #2a4365;
            border-radius: 0.4rem;
            padding: 0.75rem;
        }
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 0.85rem;
        }
        .filter-card {
            border: 1px solid #d8e2ef;
            border-radius: 0.55rem;
            background: #fff;
            padding: 0.9rem;
            box-shadow: 0 1px 4px rgba(20, 65, 108, 0.08);
        }
        .filter-label {
            font-weight: 700;
            color: #1f3f5b;
            margin-bottom: 0.55rem;
            font-size: 0.95rem;
        }
        .state-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.6rem;
        }
        .state-item label {
            margin-bottom: 0.2rem;
            font-size: 0.82rem;
            color: #4a5568;
            font-weight: 600;
        }
        .quick-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.55rem;
        }
        .custom-select.custom-select-sm {
            border-color: #c3d3e6;
        }
        .panel-results {
            overflow-x: auto;
            border: 1px solid #d8e2ef;
            background: #ffffff;
        }
        .hidden-check {
            display: none !important;
        }
        @media (max-width: 767px) {
            .state-grid {
                grid-template-columns: 1fr;
            }
        }
        .table-reporte {
            width: 100%;
        }
        .table-reporte .form-control,
        .table-reporte .custom-select {
            min-width: 140px;
        }
    </style>
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito') !!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('error') !!}
        </div>
    @endif
    @if(Session::has('errores'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('errores') !!}
        </div>
    @endif

    @if(isset($fallas) && count($fallas)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($fallas as $f)
                    <li>
                        <?php echo "Fila: ".$f->row()." - ";?>
                        <?php $errores=(array) $f->errors();
                        foreach ($errores as $e):
                            echo $e;
                        endforeach;
                        ?>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-university"></i>&nbsp;Reportes</h5>
                </div>
            </div>
            <div class="card-body">
                <div class="report-shell rounded p-3 p-md-4">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                        <div>
                            <h4 class="report-title mb-1">Reportes Docente - Administrativo</h4>
                            <div class="report-subtitle">Define filtros por tipo de documento y estados en una interfaz guiada.</div>
                        </div>
                        <a href="{{url('reporte dya')}}" class="btn btn-outline-info btn-sm text-dark mt-2 mt-md-0 shadow-sm"><i class="fas fa-recycle"></i> Limpiar formulario</a>
                    </div>

                    <div class="help-strip mb-3">
                        <strong>Como usar:</strong> 
                        <ul class="mb-0 pl-3 mt-2">
                            <li>Primero: selecciona tipo de funcionario, folder y estado de carpeta (estos siempre están disponibles)</li>
                            <li>Luego: escoge <strong>solo uno</strong> de los filtros de documento o usa "Solo Tesis" para búsquedas rápidas</li>
                            <li>En el filtro elegido, define si debe existir y sus estados (legalizado, verificado, UMSS, tesis)</li>
                            <li>Alternativamente, usa los presets para búsquedas predefinidas (sin activar filtros de documento)</li>
                        </ul>
                    </div>

                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body py-3">
                            <div class="row align-items-end">
                                <div class="col-md-8 mb-2 mb-md-0">
                                    <label class="font-weight-bold text-dark">Preset de busqueda</label>
                                    <select id="preset_busqueda" class="custom-select custom-select-sm">
                                        <option value="">Seleccionar preset...</option>
                                        <option value="carpeta_completa">Carpeta completa minima (DB + DA + TP + Postgrado)</option>
                                        <option value="carpeta_incompleta">Carpeta incompleta (falta algun documento base)</option>
                                        <option value="solo_docentes_con_folder">Solo docentes con folder</option>
                                        <option value="solo_administrativos_con_folder">Solo administrativos con folder</option>
                                        <option value="documentos_no_verificados">Documentos no verificados (cualquier tipo)</option>
                                        <option value="sin_folder">Funcionarios sin folder</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <button class="btn btn-outline-primary btn-sm btn-block" type="button" id="btn_aplicar_preset">
                                        <i class="fas fa-magic"></i> Aplicar preset
                                    </button>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">Los presets cargan filtros sugeridos. Puedes ajustarlos despues manualmente.</small>
                        </div>
                    </div>

                    <form id="form_reporte" action="{{url('procesar reporte dya')}}" method="POST">
                        @csrf

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body py-3">
                                <div class="row">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="font-weight-bold text-dark">Tipo de funcionario</label>
                                        <select name="funcionario" class="custom-select custom-select-sm">
                                            <option value="">Todos</option>
                                            <option value="D">Docente</option>
                                            <option value="A">Administrativo</option>
                                            <option value="E">Ambos (registros con tipo E)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="font-weight-bold text-dark">Folder presentado <span class="text-danger">*</span></label>
                                        <select class="custom-select custom-select-sm presence-select" data-on="folder" data-off="nofolder">
                                            <option value="con" selected>Con folder</option>
                                            <option value="sin">Sin folder</option>
                                            <option value="indiferente">Indiferente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label class="font-weight-bold text-dark">Estado de carpeta (calculado)</label>
                                        <select class="custom-select custom-select-sm" name="estado_carpeta" id="estado_carpeta">
                                            <option value="">Indiferente</option>
                                            <option value="completo">Solo completos</option>
                                            <option value="incompleto">Solo incompletos</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @php
                            $filtros = [
                                ['titulo' => 'Bachiller', 'con' => 'bachiller', 'sin' => 'nobachiller', 'l_si' => 'lbachiller', 'l_no' => 'nlbachiller', 'v_si' => 'vbachiller', 'v_no' => 'nvbachiller', 'u_si' => 'ubachiller', 'u_no' => 'nubachiller', 'con_umss' => false],
                                ['titulo' => 'Tecnico medio', 'con' => 'tmedio', 'sin' => 'notmedio', 'l_si' => 'ltmedio', 'l_no' => 'nltmedio', 'v_si' => 'vtmedio', 'v_no' => 'nvtmedio', 'u_si' => 'utmedio', 'u_no' => 'nutmedio', 'con_umss' => false],
                                ['titulo' => 'Tecnico superior', 'con' => 'tsuperior', 'sin' => 'notsuperior', 'l_si' => 'ltsuperior', 'l_no' => 'nltsuperior', 'v_si' => 'vtsuperior', 'v_no' => 'nvtsuperior', 'u_si' => 'utsuperior', 'u_no' => 'nutsuperior', 'con_umss' => false],
                                ['titulo' => 'Diploma academico', 'con' => 'academico', 'sin' => 'noacademico', 'l_si' => 'lacademico', 'l_no' => 'nlacademico', 'v_si' => 'vacademico', 'v_no' => 'nvacademico', 'u_si' => 'uacademico', 'u_no' => 'nuacademico', 'con_umss' => false],
                                ['titulo' => 'Titulo profesional', 'con' => 'profesional', 'sin' => 'noprofesional', 'l_si' => 'lprofesional', 'l_no' => 'nlprofesional', 'v_si' => 'vprofesional', 'v_no' => 'nvprofesional', 'u_si' => 'uprofesional', 'u_no' => 'nuprofesional', 'con_umss' => false],
                                ['titulo' => 'Educacion superior', 'con' => 'ddu', 'sin' => 'noddu', 'l_si' => 'lddu', 'l_no' => 'nlddu', 'v_si' => 'vddu', 'v_no' => 'nvddu', 'u_si' => 'uddu', 'u_no' => 'nuddu', 't_si' => 'tddu', 't_no' => 'ntddu', 'con_umss' => true],
                                ['titulo' => 'Diplomado', 'con' => 'diplomado', 'sin' => 'nodiplomado', 'l_si' => 'ldiplomado', 'l_no' => 'nldiplomado', 'v_si' => 'vdiplomado', 'v_no' => 'nvdiplomado', 'u_si' => 'udiplomado', 'u_no' => 'nudiplomado', 't_si' => 'tdiplomado', 't_no' => 'ntdiplomado', 'con_umss' => true],
                                ['titulo' => 'Especialidad', 'con' => 'especialidad', 'sin' => 'noespecialidad', 'l_si' => 'lespecialidad', 'l_no' => 'nlespecialidad', 'v_si' => 'vespecialidad', 'v_no' => 'nvespecialidad', 'u_si' => 'uespecialidad', 'u_no' => 'nuespecialidad', 't_si' => 'tespecialidad', 't_no' => 'ntespecialidad', 'con_umss' => true],
                                ['titulo' => 'Maestria', 'con' => 'maestria', 'sin' => 'nomaestria', 'l_si' => 'lmaestria', 'l_no' => 'nlmaestria', 'v_si' => 'vmaestria', 'v_no' => 'nvmaestria', 'u_si' => 'umaestria', 'u_no' => 'numaestria', 't_si' => 'tmaestria', 't_no' => 'ntmaestria', 'con_umss' => true],
                                ['titulo' => 'Doctorado', 'con' => 'doctorado', 'sin' => 'nodoctorado', 'l_si' => 'ldoctorado', 'l_no' => 'nldoctorado', 'v_si' => 'vdoctorado', 'v_no' => 'nvdoctorado', 'u_si' => 'udoctorado', 'u_no' => 'nudoctorado', 't_si' => 'tdoctorado', 't_no' => 'ntdoctorado', 'con_umss' => true],
                                ['titulo' => 'Solo Tesis', 'con' => 'solotesis', 'sin' => 'nosolotesis', 'l_si' => 'lsolotesis', 'l_no' => 'nlsolotesis', 'v_si' => 'vsolotesis', 'v_no' => 'nvsolotesis', 'u_si' => 'usolotesis', 'u_no' => 'nusolotesis', 'con_umss' => false, 'is_tesis_only' => true],
                            ];
                        @endphp

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-end justify-content-between mb-3 gap-2">
                                    <div style="flex: 1; min-width: 260px;">
                                        <label class="font-weight-bold text-dark mb-2 d-block">Seleccionar filtro de documento</label>
                                        <select id="select_documento_filtro" class="custom-select custom-select-sm">
                                            <option value="">-- Elegir filtro --</option>
                                            @foreach($filtros as $index => $filtro)
                                                <option value="{{ $index }}">{{ $filtro['titulo'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button class="btn btn-outline-primary btn-sm" type="button" id="btn_agregar_filtro">
                                        <i class="fas fa-plus"></i> Añadir Filtro
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" id="btn_reset_todos_documentos">
                                        <i class="fas fa-times"></i> Limpiar Todo
                                    </button>
                                    <button class="btn btn-outline-info btn-sm" type="button" data-toggle="modal" data-target="#instruccionesModal">
                                        <i class="fas fa-question-circle"></i> Instrucciones
                                    </button>
                                </div>

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div id="filtros_activos_container" class="filter-grid"></div>
                                <div id="sin_filtros_mensaje" class="alert alert-secondary text-center mb-0">
                                    <small><i class="fas fa-info-circle"></i> No hay filtros de documento activos. Selecciona uno arriba para comenzar.</small>
                                </div>
                            </div>
                        </div>
                                    @foreach($filtros as $filtro)

                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['con'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['sin'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['l_si'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['l_no'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['v_si'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['v_no'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['u_si'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['u_no'] }}">
                                        @if(!isset($filtro['is_tesis_only']))
                                            @if(isset($filtro['con_umss']) && $filtro['con_umss'])
                                                <input type="checkbox" class="hidden-check" name="{{ $filtro['t_si'] }}">
                                                <input type="checkbox" class="hidden-check" name="{{ $filtro['t_no'] }}">
                                            @endif
                                        @endif
                                    @endforeach

                        <div class="card border-0 shadow-sm mb-2">
                            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                                <div class="custom-control custom-checkbox mb-2 mb-md-0">
                                    <input type="checkbox" class="custom-control-input" name="excel" id="excel">
                                    <label class="custom-control-label text-success font-weight-bold" for="excel">Exportar resultado a Excel</label>
                                </div>
                                <button class="btn btn-primary btn-sm" type="button" onclick="prepararFormularioParaEnvio('form_reporte','{{url('procesar reporte dya')}}','panel_reporte')">
                                    <i class="fas fa-search"></i> Generar reporte
                                </button>
                            </div>
                        </div>

                        <input type="checkbox" class="hidden-check" name="folder" checked>
                        <input type="checkbox" class="hidden-check" name="nofolder">
                    </form>

                    <div class="row">
                        <div class="col-md-12 shadow-lg rounded p-3 mt-4 panel-results" id="panel_reporte"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Datos de filtros disponibles
        const filtrosData = @json($filtros);
        const filtrosActivos = new Set(); // Rastrear qué filtros están abiertos

        function renderizarFiltroCard(filtroIndex) {
            const filtro = filtrosData[filtroIndex];
            const uniqueId = 'filtro_' + filtro.con;
            
            let html = `
                <div class="filter-card" id="${uniqueId}_container" ${filtro.is_tesis_only ? 'style="border-left: 4px solid #fd7e14; background: #fff8f5;"' : ''}>
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="filter-label">
                            ${filtro.titulo}
                            ${filtro.is_tesis_only ? '<span class="badge badge-warning ml-2" style="font-size: 0.65rem;">ESPECIAL</span>' : ''}
                        </div>
                        <button type="button" class="btn-close-filtro btn btn-sm btn-link text-danger p-0" data-filtro="${filtro.con}" title="Cerrar filtro">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    ${filtro.is_tesis_only ? '<small class="text-muted d-block mb-2">Busca documentos que sean tesis, independientemente del tipo.</small>' : ''}
                    <div class="form-group mb-2">
                        <label class="mb-1 text-muted small">Presencia del documento</label>
                        <select class="custom-select custom-select-sm presence-select" data-on="${filtro.con}" data-off="${filtro.sin}" data-filtro-id="${filtroIndex}">
                            <option value="indiferente" selected>Indiferente</option>
                            <option value="con">Con documento</option>
                            <option value="sin">Sin documento</option>
                        </select>
                    </div>
                    <div class="state-grid">
                        <div class="state-item">
                            <label>Legalizado</label>
                            <select class="custom-select custom-select-sm state-select" data-yes="${filtro.l_si}" data-no="${filtro.l_no}" data-filtro-id="${filtroIndex}">
                                <option value="indiferente" selected>Indiferente</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="state-item">
                            <label>Verificado</label>
                            <select class="custom-select custom-select-sm state-select" data-yes="${filtro.v_si}" data-no="${filtro.v_no}" data-filtro-id="${filtroIndex}">
                                <option value="indiferente" selected>Indiferente</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <div class="state-item">
                            <label>Documento UMSS</label>
                            <select class="custom-select custom-select-sm state-select" data-yes="${filtro.u_si}" data-no="${filtro.u_no}" data-filtro-id="${filtroIndex}">
                                <option value="indiferente" selected>Indiferente</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        ${filtro.con_umss && !filtro.is_tesis_only ? `
                        <div class="state-item">
                            <label>Es Tesis</label>
                            <select class="custom-select custom-select-sm state-select" data-yes="${filtro.t_si}" data-no="${filtro.t_no}" data-filtro-id="${filtroIndex}">
                                <option value="indiferente" selected>Indiferente</option>
                                <option value="si">Si</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `;
            return html;
        }

        function agregarFiltro() {
            const select = document.getElementById('select_documento_filtro');
            const filtroIndex = parseInt(select.value);
            
            if (isNaN(filtroIndex)) {
                alert('Por favor selecciona un filtro');
                return;
            }

            if (filtrosActivos.has(filtroIndex)) {
                alert('Este filtro ya está activo');
                return;
            }

            filtrosActivos.add(filtroIndex);
            const filtro = filtrosData[filtroIndex];
            
            // Mostrar mensaje o esconder según sea necesario
            updateFiltrosDisplay();
            
            // Añadir la tarjeta
            const container = document.getElementById('filtros_activos_container');
            const html = renderizarFiltroCard(filtroIndex);
            container.innerHTML += html;
            
            // Attachar event listeners
            attachFilterListeners();
            
            // Limpiar select
            select.value = '';
        }

        function cerrarFiltro(filtroKey) {
            // Encontrar el índice del filtro por su key
            for (let i = 0; i < filtrosData.length; i++) {
                if (filtrosData[i].con === filtroKey) {
                    filtrosActivos.delete(i);
                    document.getElementById('filtro_' + filtroKey + '_container').remove();
                    updateFiltrosDisplay();
                    // Resetear valores de este filtro
                    const inputs = document.querySelectorAll(`input[name="${filtrosData[i].con}"], input[name="${filtrosData[i].sin}"], input[name="${filtrosData[i].l_si}"], input[name="${filtrosData[i].l_no}"], input[name="${filtrosData[i].v_si}"], input[name="${filtrosData[i].v_no}"], input[name="${filtrosData[i].u_si}"], input[name="${filtrosData[i].u_no}"]`);
                    inputs.forEach(inp => inp.checked = false);
                    break;
                }
            }
        }

        function updateFiltrosDisplay() {
            const container = document.getElementById('filtros_activos_container');
            const mensaje = document.getElementById('sin_filtros_mensaje');
            
            if (filtrosActivos.size === 0) {
                mensaje.style.display = 'block';
            } else {
                mensaje.style.display = 'none';
            }
        }

        function attachFilterListeners() {
            // Listeners para cerrar filtros
            document.querySelectorAll('.btn-close-filtro').forEach(btn => {
                btn.addEventListener('click', function() {
                    cerrarFiltro(this.getAttribute('data-filtro'));
                });
            });

            // Listeners para presence-select
            document.querySelectorAll('.presence-select').forEach(select => {
                select.addEventListener('change', function() {
                    const withName = this.getAttribute('data-on');
                    const withoutName = this.getAttribute('data-off');
                    const value = this.value;

                    document.querySelectorAll(`input[name="${withName}"]`).forEach(el => el.checked = (value === 'con'));
                    document.querySelectorAll(`input[name="${withoutName}"]`).forEach(el => el.checked = (value === 'sin'));
                });
            });

            // Listeners para state-select
            document.querySelectorAll('.state-select').forEach(select => {
                select.addEventListener('change', function() {
                    const yesName = this.getAttribute('data-yes');
                    const noName = this.getAttribute('data-no');
                    const value = this.value;

                    document.querySelectorAll(`input[name="${yesName}"]`).forEach(el => el.checked = (value === 'si'));
                    document.querySelectorAll(`input[name="${noName}"]`).forEach(el => el.checked = (value === 'no'));
                });
            });
        }

        // Event listeners para botones
        document.getElementById('btn_agregar_filtro').addEventListener('click', agregarFiltro);
        
        document.getElementById('btn_reset_todos_documentos').addEventListener('click', function() {
            if (confirm('¿Cerrar todos los filtros de documento?')) {
                document.getElementById('select_documento_filtro').value = '';
                document.getElementById('filtros_activos_container').innerHTML = '';
                filtrosActivos.clear();
                updateFiltrosDisplay();
                
                // Limpiar todos los checkboxes
                document.querySelectorAll('.hidden-check').forEach(inp => inp.checked = false);
            }
        });

        // Permitir Enter en el select para agregar
        document.getElementById('select_documento_filtro').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                agregarFiltro();
            }
        });

        // Función para limpiar checkboxes no visibles antes de enviar
        window.prepararFormularioParaEnvio = function(formId, url, panelId) {
            // Desmarca todos los checkboxes de filtros no activos
            for (let i = 0; i < filtrosData.length; i++) {
                if (!filtrosActivos.has(i)) {
                    const filtro = filtrosData[i];
                    const checkboxes = [
                        filtro.con, filtro.sin, 
                        filtro.l_si, filtro.l_no, 
                        filtro.v_si, filtro.v_no, 
                        filtro.u_si, filtro.u_no
                    ];
                    
                    if (filtro.con_umss && !filtro.is_tesis_only) {
                        checkboxes.push(filtro.t_si, filtro.t_no);
                    }
                    
                    checkboxes.forEach(name => {
                        document.querySelectorAll(`input[name="${name}"]`).forEach(el => el.checked = false);
                    });
                }
            }
            
            // Llamar la función enviar original
            enviar(formId, url, panelId);
        };

        // Inicializar display
        updateFiltrosDisplay();

        // Manejo del modal de instrucciones
        document.addEventListener('DOMContentLoaded', function() {
            // Mover el modal al body para evitar problemas de overflow
            const modal = document.getElementById('instruccionesModal');
            if (modal) {
                document.body.appendChild(modal);
            }
        });
    </script>

    <!-- Modal de Instrucciones -->
    <div class="modal fade" id="instruccionesModal" tabindex="-1" role="dialog" aria-labelledby="instruccionesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="instruccionesModalLabel">
                        <i class="fas fa-lightbulb"></i> Instrucciones de Uso
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                    <h6><strong>Cómo usar correctamente:</strong></h6>
                    
                    <div style="margin-top: 15px; margin-bottom: 15px;">
                        <strong style="color: #0c5460;">✓ DEBES HACER:</strong>
                        <ul class="mb-2 pl-3">
                            <li>Selecciona <strong>UN SOLO</strong> filtro de documento a la vez</li>
                            <li>Si necesitas cambiar de filtro, cierra el actual con la X antes de abrir otro</li>
                            <li>Los filtros de tipo de funcionario, folder y estado de carpeta siempre se pueden usar juntos</li>
                            <li>Configura las opciones (Legalizado, Verificado, UMSS, etc.) antes de generar el reporte</li>
                        </ul>
                    </div>

                    <div style="margin-top: 15px; margin-bottom: 15px;">
                        <strong style="color: #721c24;">✗ NO HAGAS:</strong>
                        <ul class="mb-2 pl-3">
                            <li>NO abras múltiples filtros de documento simultáneamente</li>
                            <li>NO combines "Bachiller" con "Maestría" en filtros abiertos a la vez</li>
                            <li>NO uses dos filtros de documento aunque sean del mismo tipo</li>
                        </ul>
                    </div>

                    <div style="background-color: #fff3cd; padding: 12px; border-radius: 4px; border-left: 4px solid #ffc107; margin-bottom: 15px;">
                        <strong style="color: #856404;"><i class="fas fa-exclamation-triangle"></i> ¿Qué pasa si usas múltiples filtros?</strong>
                        <ul class="mb-0 pl-3 mt-2" style="color: #856404;">
                            <li>Los criterios entran en <strong>contradicción</strong> lógica</li>
                            <li>El sistema busca funcionarios que cumplan TODOS los criterios simultáneamente</li>
                            <li>Si pones "Bachiller CON" y "Maestría CON" no hay funcionarios con AMBOS a la vez</li>
                            <li><strong>Resultado: No aparece ningún reporte o solo aparecen datos vacíos</strong></li>
                        </ul>
                    </div>

                    <div>
                        <strong style="color: #155724;"><i class="fas fa-thumbs-up"></i> Consejo final:</strong>
                        <ul class="mb-0 pl-3 mt-2" style="color: #155724;">
                            <li>Usa un filtro de documento a la vez para obtener resultados precisos</li>
                            <li>Combina ese filtro con tipo de funcionario y estado de carpeta para refinar tu búsqueda</li>
                            <li>Si quieres comparar entre tipos de documento, haz varias consultas separadas</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

@endsection