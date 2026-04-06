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
                        <strong>Como usar:</strong> selecciona primero el tipo de funcionario y folder. Luego, en cada documento, elige si debe existir o no y opcionalmente su estado legalizado/verificado/UMSS.
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
                                ['titulo' => 'Bachiller', 'con' => 'bachiller', 'sin' => 'nobachiller', 'l_si' => 'lbachiller', 'l_no' => 'nlbachiller', 'v_si' => 'vbachiller', 'v_no' => 'nvbachiller', 'u_si' => 'ubachiller', 'u_no' => 'nubachiller'],
                                ['titulo' => 'Tecnico medio', 'con' => 'tmedio', 'sin' => 'notmedio', 'l_si' => 'ltmedio', 'l_no' => 'nltmedio', 'v_si' => 'vtmedio', 'v_no' => 'nvtmedio', 'u_si' => 'utmedio', 'u_no' => 'nutmedio'],
                                ['titulo' => 'Tecnico superior', 'con' => 'tsuperior', 'sin' => 'notsuperior', 'l_si' => 'ltsuperior', 'l_no' => 'nltsuperior', 'v_si' => 'vtsuperior', 'v_no' => 'nvtsuperior', 'u_si' => 'utsuperior', 'u_no' => 'nutsuperior'],
                                ['titulo' => 'Diploma academico', 'con' => 'academico', 'sin' => 'noacademico', 'l_si' => 'lacademico', 'l_no' => 'nlacademico', 'v_si' => 'vacademico', 'v_no' => 'nvacademico', 'u_si' => 'uacademico', 'u_no' => 'nuacademico'],
                                ['titulo' => 'Titulo profesional', 'con' => 'profesional', 'sin' => 'noprofesional', 'l_si' => 'lprofesional', 'l_no' => 'nlprofesional', 'v_si' => 'vprofesional', 'v_no' => 'nvprofesional', 'u_si' => 'uprofesional', 'u_no' => 'nuprofesional'],
                                ['titulo' => 'Educacion superior', 'con' => 'ddu', 'sin' => 'noddu', 'l_si' => 'lddu', 'l_no' => 'nlddu', 'v_si' => 'vddu', 'v_no' => 'nvddu', 'u_si' => 'uddu', 'u_no' => 'nuddu'],
                                ['titulo' => 'Diplomado', 'con' => 'diplomado', 'sin' => 'nodiplomado', 'l_si' => 'ldiplomado', 'l_no' => 'nldiplomado', 'v_si' => 'vdiplomado', 'v_no' => 'nvdiplomado', 'u_si' => 'udiplomado', 'u_no' => 'nudiplomado'],
                                ['titulo' => 'Especialidad', 'con' => 'especialidad', 'sin' => 'noespecialidad', 'l_si' => 'lespecialidad', 'l_no' => 'nlespecialidad', 'v_si' => 'vespecialidad', 'v_no' => 'nvespecialidad', 'u_si' => 'uespecialidad', 'u_no' => 'nuespecialidad'],
                                ['titulo' => 'Maestria', 'con' => 'maestria', 'sin' => 'nomaestria', 'l_si' => 'lmaestria', 'l_no' => 'nlmaestria', 'v_si' => 'vmaestria', 'v_no' => 'nvmaestria', 'u_si' => 'umaestria', 'u_no' => 'numaestria'],
                                ['titulo' => 'Doctorado', 'con' => 'doctorado', 'sin' => 'nodoctorado', 'l_si' => 'ldoctorado', 'l_no' => 'nldoctorado', 'v_si' => 'vdoctorado', 'v_no' => 'nvdoctorado', 'u_si' => 'udoctorado', 'u_no' => 'nudoctorado'],
                            ];
                        @endphp

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                                    <h6 class="mb-2 mb-md-0 text-dark font-weight-bold">Filtros por tipo de documento</h6>
                                    <div class="quick-actions">
                                        <button class="btn btn-outline-success btn-sm" type="button" id="btn_con_todos">Con documento en todos</button>
                                        <button class="btn btn-outline-danger btn-sm" type="button" id="btn_sin_todos">Sin documento en todos</button>
                                        <button class="btn btn-outline-secondary btn-sm" type="button" id="btn_reset_documentos">Quitar filtro de documentos</button>
                                    </div>
                                </div>

                                <div class="filter-grid">
                                    @foreach($filtros as $filtro)
                                        <div class="filter-card">
                                            <div class="filter-label">{{ $filtro['titulo'] }}</div>
                                            <div class="form-group mb-2">
                                                <label class="mb-1 text-muted small">Presencia del documento</label>
                                                <select class="custom-select custom-select-sm presence-select" data-on="{{ $filtro['con'] }}" data-off="{{ $filtro['sin'] }}">
                                                    <option value="indiferente" selected>Indiferente</option>
                                                    <option value="con">Con documento</option>
                                                    <option value="sin">Sin documento</option>
                                                </select>
                                            </div>
                                            <div class="state-grid">
                                                <div class="state-item">
                                                    <label>Legalizado</label>
                                                    <select class="custom-select custom-select-sm state-select" data-yes="{{ $filtro['l_si'] }}" data-no="{{ $filtro['l_no'] }}">
                                                        <option value="indiferente" selected>Indiferente</option>
                                                        <option value="si">Si</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                                <div class="state-item">
                                                    <label>Verificado</label>
                                                    <select class="custom-select custom-select-sm state-select" data-yes="{{ $filtro['v_si'] }}" data-no="{{ $filtro['v_no'] }}">
                                                        <option value="indiferente" selected>Indiferente</option>
                                                        <option value="si">Si</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                                <div class="state-item">
                                                    <label>Documento UMSS</label>
                                                    <select class="custom-select custom-select-sm state-select" data-yes="{{ $filtro['u_si'] }}" data-no="{{ $filtro['u_no'] }}">
                                                        <option value="indiferente" selected>Indiferente</option>
                                                        <option value="si">Si</option>
                                                        <option value="no">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['con'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['sin'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['l_si'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['l_no'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['v_si'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['v_no'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['u_si'] }}">
                                        <input type="checkbox" class="hidden-check" name="{{ $filtro['u_no'] }}">
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-2">
                            <div class="card-body d-flex flex-wrap align-items-center justify-content-between">
                                <div class="custom-control custom-checkbox mb-2 mb-md-0">
                                    <input type="checkbox" class="custom-control-input" name="excel" id="excel">
                                    <label class="custom-control-label text-success font-weight-bold" for="excel">Exportar resultado a Excel</label>
                                </div>
                                <button class="btn btn-primary btn-sm" type="button" onclick="enviar('form_reporte','{{url('procesar reporte dya')}}','panel_reporte')">
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
        (function() {
            function setCheckboxValue(name, enabled) {
                var element = document.querySelector('input[type="checkbox"][name="' + name + '"]');
                if (element) {
                    element.checked = !!enabled;
                }
            }

            function syncPresenceSelect(selectElement) {
                var withName = selectElement.dataset.on;
                var withoutName = selectElement.dataset.off;
                var value = selectElement.value;

                setCheckboxValue(withName, value === 'con');
                setCheckboxValue(withoutName, value === 'sin');
            }

            function syncStateSelect(selectElement) {
                var yesName = selectElement.dataset.yes;
                var noName = selectElement.dataset.no;
                var value = selectElement.value;

                setCheckboxValue(yesName, value === 'si');
                setCheckboxValue(noName, value === 'no');
            }

            function syncAllSelects() {
                document.querySelectorAll('.presence-select').forEach(syncPresenceSelect);
                document.querySelectorAll('.state-select').forEach(syncStateSelect);
            }

            function getPresenceSelectByCheckboxName(name) {
                return document.querySelector('.presence-select[data-on="' + name + '"]');
            }

            function setPresenceByName(name, value) {
                var selectElement = getPresenceSelectByCheckboxName(name);
                if (selectElement) {
                    selectElement.value = value;
                    syncPresenceSelect(selectElement);
                }
            }

            function setStateByName(name, value) {
                var selectElement = document.querySelector('.state-select[data-yes="' + name + '"]');
                if (selectElement) {
                    selectElement.value = value;
                    syncStateSelect(selectElement);
                }
            }

            function setFolderPresence(value) {
                var folderSelect = document.querySelector('.presence-select[data-on="folder"]');
                if (folderSelect) {
                    folderSelect.value = value;
                    syncPresenceSelect(folderSelect);
                }
            }

            function setTipoFuncionario(value) {
                var tipoSelect = document.querySelector('select[name="funcionario"]');
                if (tipoSelect) {
                    tipoSelect.value = value;
                }
            }

            function setEstadoCarpeta(value) {
                var estadoSelect = document.getElementById('estado_carpeta');
                if (estadoSelect) {
                    estadoSelect.value = value;
                }
            }

            function applyPreset() {
                var presetSelect = document.getElementById('preset_busqueda');
                if (!presetSelect || !presetSelect.value) {
                    return;
                }

                // Base neutral antes de aplicar preset
                setTipoFuncionario('');
                setFolderPresence('con');
                setEstadoCarpeta('');
                resetDocumentFilters();

                var preset = presetSelect.value;

                if (preset === 'carpeta_completa') {
                    setEstadoCarpeta('completo');
                    setPresenceByName('bachiller', 'con');
                    setPresenceByName('academico', 'con');
                    setPresenceByName('profesional', 'con');
                }

                if (preset === 'carpeta_incompleta') {
                    setFolderPresence('con');
                    setEstadoCarpeta('incompleto');
                }

                if (preset === 'solo_docentes_con_folder') {
                    setTipoFuncionario('D');
                    setFolderPresence('con');
                }

                if (preset === 'solo_administrativos_con_folder') {
                    setTipoFuncionario('A');
                    setFolderPresence('con');
                }

                if (preset === 'documentos_no_verificados') {
                    setFolderPresence('indiferente');
                    setPresenceByName('bachiller', 'con');
                    setStateByName('vbachiller', 'no');
                    setPresenceByName('academico', 'con');
                    setStateByName('vacademico', 'no');
                    setPresenceByName('profesional', 'con');
                    setStateByName('vprofesional', 'no');
                }

                if (preset === 'sin_folder') {
                    setFolderPresence('sin');
                }
            }

            function setAllPresence(value) {
                document.querySelectorAll('.filter-card .presence-select').forEach(function(selectElement) {
                    selectElement.value = value;
                    syncPresenceSelect(selectElement);
                });
            }

            function resetDocumentFilters() {
                document.querySelectorAll('.filter-card .presence-select').forEach(function(selectElement) {
                    selectElement.value = 'indiferente';
                    syncPresenceSelect(selectElement);
                });

                document.querySelectorAll('.filter-card .state-select').forEach(function(selectElement) {
                    selectElement.value = 'indiferente';
                    syncStateSelect(selectElement);
                });
            }

            document.querySelectorAll('.presence-select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    syncPresenceSelect(selectElement);
                });
            });

            document.querySelectorAll('.state-select').forEach(function(selectElement) {
                selectElement.addEventListener('change', function() {
                    syncStateSelect(selectElement);
                });
            });

            var buttonWithAll = document.getElementById('btn_con_todos');
            var buttonWithoutAll = document.getElementById('btn_sin_todos');
            var buttonReset = document.getElementById('btn_reset_documentos');
            var buttonApplyPreset = document.getElementById('btn_aplicar_preset');

            if (buttonWithAll) {
                buttonWithAll.addEventListener('click', function() {
                    setAllPresence('con');
                });
            }

            if (buttonWithoutAll) {
                buttonWithoutAll.addEventListener('click', function() {
                    setAllPresence('sin');
                });
            }

            if (buttonReset) {
                buttonReset.addEventListener('click', function() {
                    resetDocumentFilters();
                });
            }

            if (buttonApplyPreset) {
                buttonApplyPreset.addEventListener('click', function() {
                    applyPreset();
                });
            }

            syncAllSelects();
        })();
    </script>
@endsection
