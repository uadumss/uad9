<div class="modal-content enoa">

    {{-- ════ HEADER ════ --}}
    <div class="e-header">
        <span class="e-header-icon"><i class="fas fa-file-alt"></i></span>
        <span class="e-header-title">Trámite No Atentado</span>

        @if($tramite_noatentado)
            @php($noaFueGenerado = in_array(strtolower(trim((string)($tramite_noatentado->dtra_generado ?? ''))), ['t','1','true','si','s'], true))
            <div class="e-header-chip">
                <span class="chip-code">NOA-{{ $tramite_noatentado->dtra_numero_tramite }}</span>
                <span class="chip-date">
                    <?php if($tramite_noatentado->dtra_fecha_registro != ''){ echo date('d/m/Y', strtotime($tramite_noatentado->dtra_fecha_registro)); } ?>
                </span>
                <span class="chip-badge {{ !$noaFueGenerado ? 'badge-edit' : 'badge-done' }}">
                    {{ !$noaFueGenerado ? 'En edición' : 'Generado' }}
                </span>
            </div>
        @endif

        <button class="e-btn-outline-sm" type="button"
                data-toggle="modal" data-target="#Noatentado_agregar"
                data-url="{{url('lista escala precios noatentado')}}"
                onclick="cargarDatos(this.dataset.url,'panel_agregar')"
                title="Ver escala de precios">
            <i class="fas fa-table"></i> Escala
        </button>

        <button class="e-close" type="button" data-dismiss="modal" aria-label="Cerrar" title="Cerrar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- ════ BODY ════ --}}
    <div class="e-body">

        {{-- Alerts --}}
        @if(Session::has('exitoModal'))
            <div class="e-alert success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('exitoModal') }}</span>
                <button class="e-alert-dismiss" onclick="this.closest('.e-alert').remove();">&times;</button>
            </div>
        @endif
        @if(Session::has('errorModal'))
            <div class="e-alert danger">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('errorModal') }}</span>
                <button class="e-alert-dismiss" onclick="this.closest('.e-alert').remove();">&times;</button>
            </div>
        @endif
        <div id="noa_feedback_js" class="mb-2" style="display:none;"></div>

        @if(!$tramite_noatentado)
        {{-- ══ MODO NUEVO ══ --}}
        <form id="form_tramite">
            @csrf

            <div class="e-grid-noa">

                {{-- ─── COLUMNA IZQUIERDA: Pago ─── --}}
                <div class="e-col-left">
                    <div class="e-panel">
                        <div class="e-panel-head">
                            <span class="ph-bar"></span>
                            <span class="ph-title">Paso 2 · Datos del trámite y pago</span>
                        </div>
                        <div class="e-panel-body">

                            <div class="e-field">
                                <label>Convocatoria</label>
                                <div class="e-val">{{ $convocatoria->con_nombre }}</div>
                            </div>

                            <div class="e-field">
                                <label>Trámite</label>
                                <select class="e-input e-select" name="tramite" id="tramite_noa" disabled>
                                    <option value="">Seleccione</option>
                                    @foreach($tramites as $t)
                                        <option value="{{$t->cod_tre}}">{{$t->tre_nombre}}</option>
                                    @endforeach
                                </select>
                                <small id="ayuda_tramite_noa" class="e-hint">Se define al validar el pago.</small>
                            </div>

                            <div class="e-field">
                                <label>Tipo de trámite</label>
                                <div class="e-radio-row">
                                    <label class="e-radio-opt">
                                        <input type="radio" name="tipo_tramite" checked value="t">
                                        <span>Interno</span>
                                    </label>
                                    <label class="e-radio-opt">
                                        <input type="radio" name="tipo_tramite" value="f">
                                        <span>Externo</span>
                                    </label>
                                </div>
                            </div>

                            <div class="e-section-divider">
                                <span>Pago principal</span>
                            </div>

                            {{-- Nro. Control (ancho completo) --}}
                            <div class="e-field">
                                <label>Nro. Control</label>
                                <div class="e-input-status-row">
                                    <input class="e-input e-input-control" required
                                           name="control" id="control_noa"
                                           placeholder="Ingrese número de control"
                                           oninput="programarValidacionPagoNoAtentado();">
                                    <a href="#" class="e-status-pill idle noa-estado-pago-icon"
                                       data-campo="estado-pago-control-icon"
                                       data-pago-campo="control"
                                       title="Ver detalle de validación de pago"
                                       onclick="abrirDetallePagoNoatentado(this); return false;">
                                        <i class="fas fa-minus-circle"></i>
                                        <span>Pendiente</span>
                                    </a>
                                </div>
                            </div>

                            {{-- Preimpreso --}}
                            <div class="e-field">
                                <label>Preimpreso <span class="e-badge-hint">multi-candidato</span></label>
                                <div class="e-input-status-row">
                                    <input class="e-input e-input-control" style="flex:1;"
                                           name="preimpreso_pago" id="preimpreso_pago_noa"
                                           placeholder="Solo con varios candidatos"
                                           oninput="programarValidacionPagoNoAtentado();" disabled>
                                    <a href="#" class="e-status-pill idle noa-estado-pago-icon"
                                       data-campo="estado-pago-preimpreso-icon"
                                       data-pago-campo="preimpreso"
                                       title="Ver detalle de validación de pago"
                                       onclick="abrirDetallePagoNoatentado(this); return false;">
                                        <i class="fas fa-minus-circle"></i>
                                        <span>N/A</span>
                                    </a>
                                </div>
                                <small class="e-hint">Requerido con varios candidatos.</small>
                            </div>

                            <div class="e-section-divider">
                                <span>Reintegro <em>· opcional</em></span>
                            </div>

                            <div class="e-field">
                                <label>Nro. Control Reintegro</label>
                                <div class="e-input-status-row">
                                    <input class="e-input e-input-control" style="flex:1;"
                                           name="reintegro" id="reintegro_noa"
                                           placeholder="Opcional"
                                           oninput="programarValidacionPagoNoAtentado();">
                                    <a href="#" class="e-status-pill idle noa-estado-pago-icon"
                                       data-campo="estado-pago-reintegro-icon"
                                       data-pago-campo="reintegro"
                                       title="Ver detalle de validación de pago"
                                       onclick="abrirDetallePagoNoatentado(this); return false;">
                                        <i class="fas fa-minus-circle"></i>
                                        <span>Opcional</span>
                                    </a>
                                </div>
                                <small class="e-hint">Se valida con N° control + CI del pagador principal.</small>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ─── COLUMNA DERECHA: Candidatos ─── --}}
                <div class="e-col-right">
                    <div class="e-panel" style="display:flex;flex-direction:column;">
                        <div class="e-panel-head" style="justify-content:space-between;">
                            <div style="display:flex;align-items:center;gap:8px;">
                                <span class="ph-bar red"></span>
                                <span class="ph-title">Paso 1 · Candidatos</span>
                            </div>
                            <span id="noa_cupo_resumen" class="e-status-pill idle" style="cursor:default;">Pendiente</span>
                        </div>

                        {{-- Formulario alta rápida --}}
                        <div class="e-qa-band-noa">
                            <div class="e-qa-grid">
                                <div class="e-qa-field">
                                    <label>CI / Carnet</label>
                                    <input class="e-input" id="noa_ci"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                           onchange="cargarDatosPersonalesNoa(this.value)"
                                           autocomplete="off" placeholder="Ej: 12345678">
                                </div>
                                <div class="e-qa-field">
                                    <label>Nombres</label>
                                    <input class="e-input" id="noa_nombre" autocomplete="off">
                                </div>
                                <div class="e-qa-field">
                                    <label>Apellidos</label>
                                    <input class="e-input" id="noa_apellido" autocomplete="off">
                                </div>
                                <div class="e-qa-field e-qa-field--sm">
                                    <label>Cod. SIS</label>
                                    <input class="e-input" id="noa_cod_sis" autocomplete="off" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                </div>
                                <div class="e-qa-field">
                                    <label>Cargo texto</label>
                                    <input class="e-input" id="noa_cargo" autocomplete="off">
                                </div>
                                <div class="e-qa-field e-qa-field--lg">
                                    <label>Cargo convocatoria</label>
                                    <select class="e-input e-select" id="noa_cargo_convocatoria">
                                        <option value="">Seleccione</option>
                                        @foreach($cargos as $cargo)
                                            <option value="{{$cargo->cod_carg}}">{{$cargo->carg_nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="e-qa-field e-qa-field--btn">
                                    <label>&nbsp;</label>
                                    <button type="button" class="e-btn e-btn-primary e-btn-full"
                                            onclick="agregarCandidatoNoAtentado()">
                                        <i class="fas fa-user-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Importar Excel --}}
                        <div class="e-excel-band">
                            <div class="e-excel-file-wrap">
                                <input type="file" class="e-file-input" id="excel_candidatos_noa"
                                       accept=".xlsx,.xls"
                                       onchange="actualizarNombreExcelNoatentado(this); importarExcelCandidatosNoAtentado();">
                                <label class="e-btn e-btn-green e-btn-full" id="label_excel_candidatos_noa" for="excel_candidatos_noa" style="margin: 0;">
                                    <i class="fas fa-file-excel"></i>
                                    <span>Importar desde Excel</span>
                                </label>
                            </div>
                        </div>

                        {{-- Barra de cupo --}}
                        <div id="noa_cupo_candidatos_panel" class="e-cupo-bar" style="display:none;">
                            <div class="e-cupo-info">
                                <small id="noa_cupo_detalle" class="e-hint" style="display:block;"></small>
                                <small id="noa_cupo_montos" class="e-hint" style="display:block;opacity:.7;"></small>
                            </div>
                            <div class="e-cupo-progress-wrap">
                                <div id="noa_cupo_progress" class="e-cupo-progress bg-secondary" style="width:0%;"></div>
                            </div>
                        </div>

                        {{-- Tabla candidatos --}}
                        <div class="e-tbl-wrap" style="flex:1;">
                            <table class="e-tbl" id="tabla_candidatos_noa">
                                <thead>
                                    <tr>
                                        <th class="td-num">#</th>
                                        <th>Apellidos y nombres</th>
                                        <th>CI</th>
                                        <th>Cod. SIS</th>
                                        <th>Cargo</th>
                                        <th style="width:36px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="fila_vacia_candidatos_noa">
                                        <td colspan="6" class="center" style="color:var(--e-s400);font-size:12px;padding:22px 0;">
                                            <i class="fas fa-users" style="font-size:18px;margin-bottom:6px;display:block;opacity:.3;"></i>
                                            No hay candidatos registrados.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer --}}
                        <div class="e-doc-footer">
                            <div style="flex:1;"></div>
                            <button class="e-btn e-btn-primary" id="btn_guardar_noa"
                                    type="button" onclick="guardarTramiteNoAtentado()">
                                <i class="fas fa-save"></i> Guardar trámite
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <input type="hidden" name="candidatos_json" id="candidatos_json_noa">
            <input type="hidden" name="cc" value="{{ $convocatoria->cod_con }}">
        </form>

        @else
        {{-- ══ MODO EDICIÓN ══ --}}
        <div class="e-grid-noa">

            <div class="e-col-left">
                <form id="form_tramite">
                    @csrf
                    <div class="e-panel">
                        <div class="e-panel-head">
                            <span class="ph-bar"></span>
                            <span class="ph-title">Paso 1 · Datos del trámite</span>
                        </div>
                        <div class="e-panel-body">
                            <div class="e-field">
                                <label>Convocatoria</label>
                                <div class="e-val muted">{{ $convocatoria->con_nombre }}</div>
                            </div>
                            <div class="e-field">
                                <label>Trámite</label>
                                <div class="e-val">{{ $tramite_noatentado->tre_nombre }}</div>
                                <small class="e-hint">Definido por el pago validado.</small>
                                <input type="hidden" id="tramite_noa_edit" value="{{ $tramite_noatentado->cod_tre }}">
                            </div>
                            <div class="e-field">
                                <label>Tipo de trámite</label>
                                <div class="e-radio-row">
                                    <label class="e-radio-opt">
                                        <input type="radio" name="tipo_tramite"
                                               {{ $tramite_noatentado->dtra_interno == 't' ? 'checked' : '' }} value="t">
                                        <span>Interno</span>
                                    </label>
                                    <label class="e-radio-opt">
                                        <input type="radio" name="tipo_tramite"
                                               {{ $tramite_noatentado->dtra_interno == 'f' ? 'checked' : '' }} value="f">
                                        <span>Externo</span>
                                    </label>
                                </div>
                            </div>

                            <div class="e-section-divider"><span>Pago (bloqueado)</span></div>

                            <div class="e-field">
                                <label>Nro. Control</label>
                                <input class="e-input e-input-readonly"
                                       name="control" id="control_noa_edit"
                                       value="{{ $tramite_noatentado->dtra_control }}" readonly>
                            </div>
                            <div class="e-field">
                                <label>Nro. Control Reintegro</label>
                                <input class="e-input e-input-readonly"
                                       name="reintegro" id="reintegro_noa_edit"
                                       value="{{ $tramite_noatentado->dtra_valorado_reintegro }}" readonly>
                            </div>

                            <div class="e-info-note" style="margin-top:8px;">
                                <i class="fas fa-lock" style="font-size:10px;flex-shrink:0;"></i>
                                El pago fue validado y bloqueado al crear el trámite. En edición no se puede modificar.
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cd" value="{{ $tramite_noatentado->cod_dtra }}">
                    <input type="hidden" name="cc" value="{{ $tramite_noatentado->cod_con }}">

                    @can('editar tramite - noa')
                        <div style="margin-top:10px;display:flex;justify-content:flex-end;">
                            <button class="e-btn e-btn-primary" id="btn_guardar_noa_edit"
                                    type="button" onclick="guardarEdicionTramiteNoAtentado()">
                                <i class="fas fa-save"></i> Guardar cambios
                            </button>
                        </div>
                    @endcan
                </form>
            </div>

            <div class="e-col-right">
                <div class="e-panel">
                    <div class="e-panel-head" style="justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span class="ph-bar red"></span>
                            <span class="ph-title">Paso 2 · Edición de candidatos</span>
                        </div>
                        <span style="font-size:11px;color:var(--e-s500);font-weight:600;background:var(--e-s100);padding:3px 10px;border-radius:20px;">
                            {{ count($noatentados) }} registro(s)
                        </span>
                    </div>
                    <div class="e-closed-note">
                        <i class="fas fa-info-circle" style="font-size:11px;flex-shrink:0;"></i>
                        Solo se permite actualizar datos de candidatos ya registrados.
                    </div>

                    <div class="e-tbl-wrap" id="panel_candidato">
                        <table class="e-tbl" id="lista">
                            <thead>
                                <tr>
                                    <th class="td-num">#</th>
                                    <th>Nombre</th>
                                    <th>CI</th>
                                    <th>COD SIS</th>
                                    <th>Cargo</th>
                                    <th>Unidad</th>
                                    <th style="width:60px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($noatentados as $n)
                                    <?php $sancionado = App\Http\Controllers\Noatentado\SancionadosController::verificarSancionado($n->id_per); ?>
                                    <tr class="{{ $sancionado ? 'noa-row-sancionado' : '' }}">
                                        <td class="td-num">{{ $i++ }}</td>
                                        <td class="td-main">{{ $n->per_nombre . ' ' . $n->per_apellido }}</td>
                                        <td>{{ $n->per_ci }}</td>
                                        <td>{{ $n->per_cod_sis }}</td>
                                        <td>{{ $n->carg_nombre }}</td>
                                        <td style="color:var(--e-s500);font-size:11px;">{{ $n->noa_unidad }}</td>
                                        <td class="center">
                                            @if($sancionado && $sancionado->cod_res != '')
                                                <a href="" class="e-icon-action text-danger"
                                                   data-toggle="modal" data-target="#Noatentado_agregar"
                                                   data-url="{{ url('ver datos resolucion/' . $sancionado->cod_res) }}"
                                                   onclick="cargarDatos(this.dataset.url,'panel_agregar')"
                                                   title="Ver resolución">
                                                    <i class="fas fa-file-pdf"></i>
                                                </a>
                                            @endif
                                            @if(!$noaFueGenerado)
                                                <a href="#" class="e-icon-action"
                                                   data-toggle="modal" data-target="#Noatentado_agregar"
                                                   title="Editar candidato"
                                                   data-url="{{ url('editar candidato convocatoria/' . $tramite_noatentado->cod_dtra . '/' . $n->cod_noa) }}"
                                                   onclick="cargarDatos(this.dataset.url,'panel_agregar');">
                                                    <i class="fas fa-pencil-alt" style="color:var(--e-blue);"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        @endif

    </div>{{-- /body --}}
</div>{{-- /enoa --}}

{{-- ════════════════════ ESTILOS ════════════════════ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&display=swap');

/* ── Tokens ── */
:root {
    --e-navy:      #0f2d5e;
    --e-blue:      #1a56db;
    --e-blue-h:    #1443b0;
    --e-blue-lt:   #dbeafe;
    --e-red:       #b91c1c;
    --e-red-lt:    #fef2f2;
    --e-green:     #047857;
    --e-green-lt:  #ecfdf5;
    --e-amber:     #b45309;
    --e-amber-lt:  #fffbeb;
    --e-s50:       #f8fafc;
    --e-s100:      #f1f5f9;
    --e-s200:      #e2e8f0;
    --e-s300:      #cbd5e1;
    --e-s400:      #94a3b8;
    --e-s500:      #64748b;
    --e-s700:      #334155;
    --e-s900:      #0f172a;
    --e-r:         4px;
    --e-r-md:      6px;
    --ff:          'IBM Plex Sans', system-ui, sans-serif;
}

.enoa * { box-sizing: border-box; font-family: var(--ff); }
.enoa i.fas, .enoa i.far, .enoa i.fab {
    font-family: var(--fa-style-family,"Font Awesome 6 Free"),"Font Awesome 5 Free","FontAwesome" !important;
    font-style: normal; line-height: 1;
}
.enoa i.fas { font-weight: 900; }
.enoa i.far { font-weight: 400; }

/* ── Modal shell ── */
.enoa.modal-content {
    border: none; border-radius: 8px; overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,.22), 0 4px 16px rgba(0,0,0,.12);
}

/* ── Header ── */
.enoa .e-header {
    background: var(--e-navy);
    padding: 0 16px;
    height: 52px;
    display: flex; align-items: center; gap: 10px;
    border-bottom: 2px solid var(--e-blue);
}
.enoa .e-header-icon {
    width: 28px; height: 28px;
    background: rgba(255,255,255,.1); border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: rgba(255,255,255,.8); flex-shrink: 0;
}
.enoa .e-header-title {
    font-size: 13px; font-weight: 600; color: #fff;
    letter-spacing: .2px; flex: 1; white-space: nowrap;
}
.enoa .e-header-chip {
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 4px; padding: 4px 10px; flex-shrink: 0;
}
.enoa .e-header-chip .chip-code { font-size: 13px; font-weight: 700; color: #fbbf24; letter-spacing: .5px; }
.enoa .e-header-chip .chip-date { font-size: 11px; color: rgba(255,255,255,.55); border-left: 1px solid rgba(255,255,255,.2); padding-left: 8px; }
.enoa .e-header-chip .chip-badge { font-size: 10px; font-weight: 600; border-radius: 3px; padding: 2px 6px; margin-left: 4px; }
.enoa .e-header-chip .chip-badge.badge-edit { background: #fbbf24; color: #78350f; }
.enoa .e-header-chip .chip-badge.badge-done { background: #34d399; color: #064e3b; }

.enoa .e-btn-outline-sm {
    display: inline-flex; align-items: center; gap: 5px;
    height: 28px; padding: 0 10px;
    border-radius: var(--e-r); border: 1px solid rgba(255,255,255,.25);
    font-size: 11px; font-weight: 600; font-family: var(--ff);
    color: rgba(255,255,255,.8); background: transparent;
    cursor: pointer; white-space: nowrap; flex-shrink: 0;
    transition: background .12s;
}
.enoa .e-btn-outline-sm:hover { background: rgba(255,255,255,.12); color: #fff; }

.enoa .e-close {
    width: 30px; height: 30px; border-radius: 4px;
    border: 1px solid rgba(255,255,255,.2); background: transparent;
    color: rgba(255,255,255,.7); font-size: 13px; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .12s; flex-shrink: 0;
}
.enoa .e-close:hover { background: rgba(255,255,255,.12); color: #fff; }

/* ── Body ── */
.enoa .e-body {
    background: var(--e-s100);
    padding: 16px 18px 18px;
}

/* ── Alerts ── */
.enoa .e-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 9px 12px; border-radius: var(--e-r-md);
    font-size: 12px; font-weight: 500;
    margin-bottom: 14px; border-left: 3px solid;
}
.enoa .e-alert.success { background: var(--e-green-lt); color: #065f46; border-color: var(--e-green); }
.enoa .e-alert.danger  { background: var(--e-red-lt);   color: #7f1d1d; border-color: var(--e-red); }
.enoa .e-alert-dismiss { margin-left: auto; background: none; border: none; cursor: pointer; opacity: .5; font-size: 14px; }
.enoa .e-alert-dismiss:hover { opacity: 1; }

/* ── Grid principal ── */
.enoa .e-grid-noa {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 14px;
    align-items: start;
}
.enoa .e-col-left { display: flex; flex-direction: column; gap: 10px; }
.enoa .e-col-right { min-width: 0; }

/* ── Panels ── */
.enoa .e-panel {
    background: #fff;
    border: 1px solid var(--e-s200);
    border-radius: var(--e-r-md);
}
.enoa .e-panel-head {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--e-s200);
    background: var(--e-s50);
    border-radius: var(--e-r-md) var(--e-r-md) 0 0;
}
.enoa .e-panel-head .ph-bar {
    width: 3px; height: 13px; border-radius: 2px;
    background: var(--e-blue); flex-shrink: 0;
}
.enoa .e-panel-head .ph-bar.red { background: var(--e-red); }
.enoa .e-panel-head .ph-title {
    font-size: 10.5px; font-weight: 600;
    letter-spacing: .6px; text-transform: uppercase;
    color: var(--e-s700);
}
.enoa .e-panel-body { padding: 12px 14px; }

/* ── Fields ── */
.enoa .e-field { display: flex; flex-direction: column; padding-bottom: 10px; }
.enoa .e-field:last-child { padding-bottom: 0; }
.enoa .e-field label {
    font-size: 10px; font-weight: 600; color: var(--e-s500);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px;
}

/* ── Inputs ── */
.enoa .e-input {
    height: 34px;
    border: 1px solid var(--e-s300); border-radius: var(--e-r);
    padding: 0 10px; font-size: 13px; font-family: var(--ff);
    color: var(--e-s900); background: #fff; outline: none; width: 100%;
    transition: border-color .12s, box-shadow .12s;
}
.enoa .e-input:focus { border-color: var(--e-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.enoa .e-input[disabled] { background: var(--e-s100); color: var(--e-s400); cursor: not-allowed; }
.enoa .e-input-control { flex: 1; min-width: 0; }
.enoa .e-input-readonly {
    background: var(--e-s100); color: var(--e-s500);
    border-color: var(--e-s200); cursor: default;
}
.enoa .e-select { appearance: auto; cursor: pointer; }

.enoa .e-val {
    font-size: 12.5px; color: var(--e-s900); font-weight: 500;
    padding: 5px 0; border-bottom: 1px solid var(--e-s100);
    min-height: 28px; display: flex; align-items: center; line-height: 1.4;
}
.enoa .e-val.muted { color: var(--e-s500); font-weight: 400; }

.enoa .e-hint { font-size: 10.5px; color: var(--e-s400); margin-top: 3px; display: block; }
.enoa .e-badge-hint {
    font-size: 9px; color: var(--e-s400); font-weight: 400;
    text-transform: none; letter-spacing: 0;
    background: var(--e-s100); border: 1px solid var(--e-s200);
    border-radius: 3px; padding: 1px 5px; margin-left: 4px;
}

/* ── Input + status pill en fila ── */
.enoa .e-input-status-row {
    display: flex; align-items: center; gap: 8px;
}
.enoa .e-input-status-row .e-input { flex: 1; min-width: 0; }

/* ── Section divider ── */
.enoa .e-section-divider {
    display: flex; align-items: center; gap: 8px;
    margin: 12px 0 10px;
}
.enoa .e-section-divider::before,
.enoa .e-section-divider::after {
    content: ''; flex: 1; height: 1px; background: var(--e-s200);
}
.enoa .e-section-divider::before { flex: 0 0 0; }
.enoa .e-section-divider span {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: var(--e-blue); white-space: nowrap;
}
.enoa .e-section-divider span em { font-style: normal; color: var(--e-s400); font-weight: 400; }

/* ── Radio ── */
.enoa .e-radio-row { display: flex; gap: 16px; align-items: center; padding-top: 2px; }
.enoa .e-radio-opt { display: flex; align-items: center; gap: 6px; cursor: pointer; }
.enoa .e-radio-opt input[type="radio"] { accent-color: var(--e-blue); width: 13px; height: 13px; cursor: pointer; }
.enoa .e-radio-opt span { font-size: 12px; color: var(--e-s700); }

/* ── Buttons ── */
.enoa .e-btn {
    display: inline-flex; align-items: center; gap: 6px;
    height: 34px; padding: 0 14px;
    border-radius: var(--e-r); border: 1px solid transparent;
    font-size: 12px; font-weight: 600; font-family: var(--ff);
    cursor: pointer; white-space: nowrap;
    transition: background .12s, transform .08s;
}
.enoa .e-btn:active { transform: scale(.98); }
.enoa .e-btn-primary { background: var(--e-blue); color: #fff; border-color: var(--e-blue); }
.enoa .e-btn-primary:hover { background: var(--e-blue-h); border-color: var(--e-blue-h); }
.enoa .e-btn-green { background: var(--e-green); color: #fff; border-color: var(--e-green); }
.enoa .e-btn-green:hover { background: #065f46; }
.enoa .e-btn-full { width: 100%; justify-content: center; }

/* ── Status pills (pago) ── */
.enoa .e-status-pill {
    display: inline-flex; align-items: center; gap: 5px;
    height: 26px; padding: 0 9px;
    border-radius: 13px; font-size: 10.5px; font-weight: 600;
    border: 1px solid; cursor: pointer;
    transition: opacity .12s; white-space: nowrap; text-decoration: none;
    flex-shrink: 0;
}
.enoa .e-status-pill:hover { opacity: .8; }
.enoa .e-status-pill.ok   { background: var(--e-green-lt); color: var(--e-green);  border-color: #a7f3d0; }
.enoa .e-status-pill.err  { background: var(--e-red-lt);   color: var(--e-red);    border-color: #fca5a5; }
.enoa .e-status-pill.warn { background: var(--e-amber-lt); color: var(--e-amber);  border-color: #fcd34d; }
.enoa .e-status-pill.idle { background: var(--e-s100);     color: var(--e-s400);   border-color: var(--e-s200); cursor: default; }
.enoa .e-status-pill.spin { background: var(--e-blue-lt);  color: var(--e-blue);   border-color: #93c5fd; cursor: default; }
/* Retrocompat con clase "e-pill" usada en JS */
.enoa .e-pill { display: inline-flex; align-items: center; gap: 5px; height: 26px; padding: 0 9px; border-radius: 13px; font-size: 10.5px; font-weight: 600; border: 1px solid; cursor: pointer; transition: opacity .12s; white-space: nowrap; text-decoration: none; flex-shrink: 0; }
.enoa .e-pill:hover { opacity: .8; }
.enoa .e-pill.ok   { background: var(--e-green-lt); color: var(--e-green);  border-color: #a7f3d0; }
.enoa .e-pill.err  { background: var(--e-red-lt);   color: var(--e-red);    border-color: #fca5a5; }
.enoa .e-pill.warn { background: var(--e-amber-lt); color: var(--e-amber);  border-color: #fcd34d; }
.enoa .e-pill.idle { background: var(--e-s100);     color: var(--e-s400);   border-color: var(--e-s200); cursor: default; }
.enoa .e-pill.spin { background: var(--e-blue-lt);  color: var(--e-blue);   border-color: #93c5fd; cursor: default; }

/* ── Banda rápida candidatos ── */
.enoa .e-qa-band-noa {
    padding: 12px 14px;
    border-bottom: 1px solid var(--e-s200);
    background: var(--e-s50);
}
.enoa .e-qa-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    align-items: flex-end;
}
.enoa .e-qa-field { display: flex; flex-direction: column; flex: 1 1 110px; min-width: 0; }
.enoa .e-qa-field--sm { flex: 0 1 90px; }
.enoa .e-qa-field--lg { flex: 2 1 160px; }
.enoa .e-qa-field--btn { flex: 0 0 110px; }
.enoa .e-qa-field label {
    font-size: 10px; font-weight: 600; color: var(--e-s500);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* ── Excel band ── */
.enoa .e-excel-band {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 14px; border-bottom: 1px solid var(--e-s200);
    background: #fff;
}
.enoa .e-excel-file-wrap { flex: 1; min-width: 0; }
.enoa .e-file-input { display: none; }
.enoa .e-file-label {
    display: inline-flex; align-items: center; gap: 6px;
    height: 30px; padding: 0 10px;
    border: 1px dashed var(--e-s300); border-radius: var(--e-r);
    font-size: 11.5px; color: var(--e-s500); cursor: pointer;
    background: #fff; width: 100%;
    transition: border-color .12s, background .12s; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.enoa .e-file-label:hover { border-color: var(--e-green); background: var(--e-green-lt); color: var(--e-green); }

/* ── Cupo bar ── */
.enoa .e-cupo-bar {
    padding: 8px 14px; background: var(--e-s50);
    border-bottom: 1px solid var(--e-s200);
}
.enoa .e-cupo-progress-wrap {
    height: 4px; background: var(--e-s200); border-radius: 2px;
    overflow: hidden; margin-top: 5px;
}
.enoa .e-cupo-progress { height: 100%; border-radius: 2px; transition: width .3s ease, background .2s; }
.enoa .e-cupo-progress.bg-success { background: var(--e-green); }
.enoa .e-cupo-progress.bg-danger  { background: var(--e-red); }
.enoa .e-cupo-progress.bg-warning { background: #f59e0b; }
.enoa .e-cupo-progress.bg-secondary { background: var(--e-s300); }

/* ── Tables ── */
.enoa .e-tbl-wrap { overflow-y: auto; max-height: 280px; }
.enoa .e-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
.enoa .e-tbl thead tr { background: var(--e-navy); position: sticky; top: 0; z-index: 1; }
.enoa .e-tbl thead th {
    padding: 8px 12px; font-size: 10px; font-weight: 600;
    letter-spacing: .4px; color: rgba(255,255,255,.85);
    text-align: left; white-space: nowrap;
}
.enoa .e-tbl tbody tr { border-bottom: 1px solid var(--e-s100); }
.enoa .e-tbl tbody tr:last-child { border-bottom: none; }
.enoa .e-tbl tbody tr:hover { background: #f5f8ff; }
.enoa .e-tbl tbody tr.noa-row-sancionado { background: #fef2f2; }
.enoa .e-tbl tbody tr.noa-row-sancionado:hover { background: #fee2e2; }
.enoa .e-tbl tbody td { padding: 7px 12px; color: var(--e-s900); vertical-align: middle; }
.enoa .e-tbl .td-num { color: var(--e-s400); font-size: 11px; width: 28px; }
.enoa .e-tbl .td-main { font-weight: 500; }
.enoa .e-tbl td.center { text-align: center; }

/* Semáforo */
.enoa #tabla_candidatos_noa tbody tr.noa-candidato-permitido { background-color: #f0fdf4; }
.enoa #tabla_candidatos_noa tbody tr.noa-candidato-exceso    { background-color: #fef2f2; }

/* ── Icon actions ── */
.enoa .e-icon-action {
    width: 26px; height: 26px; border-radius: var(--e-r);
    border: 1px solid var(--e-s200); background: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--e-s400); font-size: 11px; cursor: pointer;
    text-decoration: none; transition: all .12s; margin: 1px;
}
.enoa .e-icon-action:hover { background: var(--e-red-lt); border-color: #fca5a5; color: var(--e-red); }
.enoa .e-icon-action.text-danger { color: var(--e-red); }

/* ── Footer ── */
.enoa .e-doc-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px; border-top: 1px solid var(--e-s200);
    background: var(--e-s50);
    border-radius: 0 0 var(--e-r-md) var(--e-r-md); gap: 10px;
}

/* ── Notes ── */
.enoa .e-info-note {
    display: flex; align-items: flex-start; gap: 7px;
    background: var(--e-s100); border-radius: var(--e-r);
    padding: 8px 10px; font-size: 11px; color: var(--e-s500);
    border: 1px solid var(--e-s200);
}
.enoa .e-closed-note {
    display: flex; align-items: center; gap: 8px;
    padding: 8px 14px; background: var(--e-s100);
    border-bottom: 1px solid var(--e-s200);
    font-size: 11.5px; color: var(--e-s500);
}

/* ── Animations ── */
.enoa .noa-estado-pago-icon { transition: transform .18s ease, opacity .18s ease; }
.enoa .noa-anim-pop   { animation: noaIconPop  .28s ease-out; }
.enoa .noa-anim-alert { animation: noaIconAlert .32s ease-out; }
@keyframes noaIconPop   { 0%{transform:scale(.82)}55%{transform:scale(1.16)}100%{transform:scale(1)} }
@keyframes noaIconAlert { 0%{transform:translateX(0)}25%{transform:translateX(-2px)}50%{transform:translateX(2px)}75%{transform:translateX(-1px)}100%{transform:translateX(0)} }
@media (prefers-reduced-motion: reduce) {
    .enoa .noa-estado-pago-icon { transition: none; }
    .enoa .noa-anim-pop, .enoa .noa-anim-alert { animation: none; }
}
</style>

{{-- ════════════════════ DATA + JS (sin cambios funcionales) ════════════════════ --}}
<script type="application/json" id="noa_escala_candidatos_json">{!! json_encode($escalaCandidatosNoa ?? [], JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) !!}</script>

<script>
    var candidatosNoAtentado=[];
    var pagoNoAtentadoValidado=false;
    var controlValidadoNoAtentado='';
    var reintegroValidadoNoAtentado='';
    var tramiteValidadoNoAtentado='';
    var detalleValidacionPagoNoAtentado=null;
    var opcionesOriginalesTramiteNoa='';
    var estadoControlPagoNoa={resumen:'Pendiente',clase:'badge-warning',detalle:'Antes de guardar debe validar el número de control.',codigo:''};
    var detalleExtendidoPagoNoa='';
    var montoPrincipalValidadoNoa=0;
    var montoReintegroValidadoNoa=0;
    var montoTotalValidadoNoa=0;
    var escalaCandidatosNoa=[];
    var escalaCandidatosNoaNormalizada=[];
    var codTramitePlanchaNoa=Number("{{ (int)($codTramitePlanchaNoa ?? 0) }}");
    var timerValidacionPagoNoa=null;
    var secuenciaValidacionPagoNoa=0;
    var xhrValidacionPagoNoa=null;
    var validacionPagoNoaEnCurso=false;
    var guardandoTramiteNoaEnCurso=false;

    /* ─── Helpers de pill ─── */
    function _pillCfgNoa(categoria){
        const m={
            ok:          {cls:'ok',   icon:'fa-check-circle',        label:'Validado'},
            loading:     {cls:'spin', icon:'fa-spinner fa-spin',      label:'Validando…'},
            rate_limit:  {cls:'warn', icon:'fa-clock',                label:'Espere…'},
            used:        {cls:'warn', icon:'fa-ban',                  label:'Ya usado'},
            connection:  {cls:'warn', icon:'fa-plug',                 label:'Sin conexión'},
            not_configured:{cls:'idle',icon:'fa-cog',                 label:'No conf.'},
            pending:     {cls:'idle', icon:'fa-minus-circle',         label:'Pendiente'},
            na:          {cls:'idle', icon:'fa-minus-circle',         label:'N/A'},
            not_match:   {cls:'warn', icon:'fa-exclamation-circle',   label:'No coincide'},
            not_found:   {cls:'warn', icon:'fa-exclamation-circle',   label:'No encontrado'},
            warning:     {cls:'warn', icon:'fa-exclamation-triangle', label:'Atención'},
            error:       {cls:'err',  icon:'fa-times-circle',         label:'Inválido'},
        };
        return m[categoria]||m['error'];
    }
    function _aplicarPillNoa(el,cfg,title){
        el.className='e-pill '+cfg.cls+' noa-estado-pago-icon';
        el.setAttribute('title',title||cfg.label);
        el.innerHTML='<i class="fas '+cfg.icon+'" style="font-size:10px;"></i><span> '+cfg.label+'</span>';
    }

    function reiniciarMontosValidadosNoatentado(){
        montoPrincipalValidadoNoa=0;montoReintegroValidadoNoa=0;montoTotalValidadoNoa=0;
        window.noaMontosValidados={principal:0,reintegro:0,total:0};
        actualizarControlCupoCandidatosNoatentado();
    }
    function asignarMontosValidadosNoatentado(resp){
        const principal=Number(resp&&resp.monto_principal_validado?resp.monto_principal_validado:0);
        const reintegro=Number(resp&&resp.monto_reintegro_validado?resp.monto_reintegro_validado:0);
        const total=Number(resp&&resp.monto_total_validado?resp.monto_total_validado:(principal+reintegro));
        montoPrincipalValidadoNoa=isFinite(principal)?principal:0;
        montoReintegroValidadoNoa=isFinite(reintegro)?reintegro:0;
        montoTotalValidadoNoa=isFinite(total)?total:0;
        window.noaMontosValidados={principal:montoPrincipalValidadoNoa,reintegro:montoReintegroValidadoNoa,total:montoTotalValidadoNoa};
        actualizarControlCupoCandidatosNoatentado();
    }
    function formatoMontoNoatentado(valor){const n=Number(valor);return isFinite(n)?n.toFixed(2):'0.00';}
    function esTramitePlanchaNoatentado(codTramite){
        const cod=Number(codTramite||0),codP=Number(codTramitePlanchaNoa||0);
        if(!isFinite(cod)||cod<=0||!isFinite(codP)||codP<=0)return false;
        return cod===codP;
    }
    function obtenerEscalaCandidatosConfigNoatentado(){
        const nodo=document.getElementById('noa_escala_candidatos_json');
        if(!nodo)return [];
        try{const d=JSON.parse(nodo.textContent||'[]');return Array.isArray(d)?d:[];}catch(e){return [];}
    }
    function rangoTextoEscalaNoatentado(regla){
        if(!regla)return '';
        const min=Number(regla.cantidad_min||0),max=Number(regla.cantidad_max||0);
        if(!isFinite(min)||!isFinite(max)||max<=0)return '';
        return min===max?String(max):String(min)+' a '+String(max);
    }
    function normalizarEscalaCandidatosNoatentado(){
        if(!Array.isArray(escalaCandidatosNoa))return [];
        const salida=[];
        for(let i=0;i<escalaCandidatosNoa.length;i++){
            const fila=escalaCandidatosNoa[i]||{};
            const cantidadMin=Number(fila.cantidad_min||0),cantidadMax=Number(fila.cantidad_max||0);
            let montoTotal=Number(fila.monto_total||0);
            const costo=Number(fila.costo||0),aporte=Number(fila.aporte_umss||0);
            if(!isFinite(montoTotal)||montoTotal<=0)montoTotal=(isFinite(costo)?costo:0)+(isFinite(aporte)?aporte:0);
            if(!isFinite(montoTotal)||montoTotal<=0||!isFinite(cantidadMax)||cantidadMax<=0)continue;
            const minF=(isFinite(cantidadMin)&&cantidadMin>0)?cantidadMin:1;
            const maxF=Math.max(minF,cantidadMax);
            salida.push({cantidad_min:minF,cantidad_max:maxF,monto_total:montoTotal,costo:isFinite(costo)?costo:0,aporte_umss:isFinite(aporte)?aporte:0});
        }
        salida.sort(function(a,b){return a.monto_total===b.monto_total?a.cantidad_max-b.cantidad_max:a.monto_total-b.monto_total;});
        return salida;
    }
    function resolverCupoCandidatosPorMontoNoatentado(montoTotal){
        const escala=escalaCandidatosNoaNormalizada,monto=Number(montoTotal||0);
        if(!Array.isArray(escala)||escala.length===0)return{ok:false,maxPermitidos:0,resumen:'Sin escala',detalle:'No hay escala de precios configurada.',regla:null};
        if(!isFinite(monto)||monto<=0)return{ok:false,maxPermitidos:0,resumen:'Monto pendiente',detalle:'Valide el pago para calcular la cantidad permitida.',regla:null};
        let regla=null;const tolerancia=0.01;
        for(let i=0;i<escala.length;i++){if((monto+tolerancia)>=escala[i].monto_total){regla=escala[i];continue;}break;}
        if(!regla)return{ok:false,maxPermitidos:0,resumen:'Monto insuficiente',detalle:'El monto Bs '+formatoMontoNoatentado(monto)+' es menor al mínimo de escala.',regla:null};
        return{ok:true,maxPermitidos:parseInt(regla.cantidad_max,10)||0,resumen:'Hasta '+(parseInt(regla.cantidad_max,10)||0)+' candidato(s)',detalle:'Escala: rango '+rangoTextoEscalaNoatentado(regla)+' para Bs '+formatoMontoNoatentado(regla.monto_total)+'.',regla:regla};
    }
    function aplicarSemaforoFilasCandidatosNoatentado(maxPermitidos,activar){
        const filas=$('#tabla_candidatos_noa tbody tr[data-candidato-index]');
        filas.removeClass('noa-candidato-permitido noa-candidato-exceso');
        if(!activar)return;
        const limite=Math.max(0,parseInt(maxPermitidos,10)||0);
        filas.each(function(){const fila=$(this),indice=parseInt(fila.attr('data-candidato-index'),10);if(!isFinite(indice))return;if(indice<limite)fila.addClass('noa-candidato-permitido');else fila.addClass('noa-candidato-exceso');});
    }
    function actualizarControlCupoCandidatosNoatentado(){
        const panel=$('#noa_cupo_candidatos_panel');
        const badge=$('#noa_cupo_resumen');
        const detalle=$('#noa_cupo_detalle');
        const montos=$('#noa_cupo_montos');
        const progress=$('#noa_cupo_progress');
        const cantidad=candidatosNoAtentado.length;
        badge.attr('class','e-pill idle').html('<i class="fas fa-minus-circle" style="font-size:10px;"></i> Pendiente');
        if(panel.length)panel.hide();
        if(cantidad===0){aplicarSemaforoFilasCandidatosNoatentado(0,false);return;}
        if(panel.length)panel.show();
        if(montos.length)montos.text('Monto: Bs '+formatoMontoNoatentado(montoPrincipalValidadoNoa)+' + reintegro Bs '+formatoMontoNoatentado(montoReintegroValidadoNoa)+' = total Bs '+formatoMontoNoatentado(montoTotalValidadoNoa));
        const cupo=resolverCupoCandidatosPorMontoNoatentado(montoTotalValidadoNoa);
        if(!cupo.ok){
            badge.attr('class','e-pill warn').html('<i class="fas fa-clock" style="font-size:10px;"></i> '+cupo.resumen);
            if(detalle.length)detalle.text(cupo.detalle||'Valide el pago.');
            if(progress.length)progress.attr('class','e-cupo-progress bg-warning').css('width','0%');
            aplicarSemaforoFilasCandidatosNoatentado(0,false);return;
        }
        const permitidos=Math.max(0,parseInt(cupo.maxPermitidos,10)||0);
        const porcentaje=permitidos>0?Math.min(100,Math.round((cantidad/permitidos)*100)):0;
        if(cantidad<=permitidos){
            badge.attr('class','e-pill ok').html('<i class="fas fa-check-circle" style="font-size:10px;"></i> '+cantidad+' / '+permitidos);
            if(detalle.length)detalle.text('Dentro del límite. '+(cupo.detalle||''));
            if(progress.length)progress.attr('class','e-cupo-progress bg-success').css('width',String(porcentaje)+'%');
        }else{
            badge.attr('class','e-pill err').html('<i class="fas fa-times-circle" style="font-size:10px;"></i> '+cantidad+' / '+permitidos+' Excede');
            if(detalle.length)detalle.text('Lista excede el límite. '+(cupo.detalle||''));
            if(progress.length)progress.attr('class','e-cupo-progress bg-danger').css('width','100%');
        }
        aplicarSemaforoFilasCandidatosNoatentado(permitidos,true);
    }
    function validarCantidadCandidatosPorMontoNoatentadoUI(){
        const cantidad=candidatosNoAtentado.length;
        if(cantidad===0)return{ok:false,message:'Debe agregar al menos un candidato antes de guardar.'};
        const tramiteSeleccionado=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(tramiteSeleccionado==='')return{ok:false,message:'Debe seleccionar el tipo de trámite antes de guardar.'};
        if(!esTramitePlanchaNoatentado(tramiteSeleccionado)){
            if(cantidad>1)return{ok:false,message:'Para este tipo de trámite solo se permite registrar un candidato.'};
            return{ok:true,message:'Cantidad válida.'};
        }
        const cupo=resolverCupoCandidatosPorMontoNoatentado(montoTotalValidadoNoa);
        if(!cupo.ok)return{ok:false,message:cupo.detalle||'No se pudo determinar el cupo.'};
        const permitidos=Math.max(0,parseInt(cupo.maxPermitidos,10)||0);
        if(cantidad>permitidos)return{ok:false,message:'Con el monto validado (Bs '+formatoMontoNoatentado(montoTotalValidadoNoa)+') solo se permiten hasta '+permitidos+' candidato(s). Registró '+cantidad+'.'};
        return{ok:true,message:'Cantidad válida.'};
    }
    function programarValidacionPagoNoAtentado(inmediata){
        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        if(controlActual===''||controlActual!==controlValidadoNoAtentado)limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
        if(timerValidacionPagoNoa){clearTimeout(timerValidacionPagoNoa);timerValidacionPagoNoa=null;}
        if(inmediata===true){validarPagoNoAtentado();return;}
        timerValidacionPagoNoa=setTimeout(function(){validarPagoNoAtentado();},450);
    }
    function limpiarTextoNoAtentado(valor){return(valor||'').toString().trim();}
    function normalizarNumeroNoAtentado(valor){return(valor||'').toString().replace(/\D+/g,'');}
    function normalizarDocumentoNoAtentado(valor){return limpiarTextoNoAtentado(valor).toUpperCase().replace(/[^A-Z0-9]/g,'');}
    function escaparHtmlNoa(valor){return $('<div>').text((valor||'').toString()).html();}
    function limpiarMensajeNoatentado(){const c=$('#noa_feedback_js');if(c.length)c.stop(true,true).hide().html('');}
    function mostrarMensajeNoatentado(tipo,mensaje,enfocarSelector){
        const contenedor=$('#noa_feedback_js'),texto=limpiarTextoNoAtentado(mensaje);
        if(contenedor.length===0||texto==='')return;
        const mapaClases={error:'alert-danger',warning:'alert-warning',success:'alert-success',info:'alert-info'};
        const clase=mapaClases[tipo]||'alert-warning';
        contenedor.html('<div class="alert '+clase+' alert-dismissible fade show py-2 mb-0" role="alert"><span>'+escaparHtmlNoa(texto)+'</span><button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button></div>').show();
        const modalBody=contenedor.closest('.modal-body');
        if(modalBody.length)modalBody.stop(true).animate({scrollTop:0},180);
        if(enfocarSelector){const campo=$(enfocarSelector);if(campo.length&&!campo.prop('disabled')){campo.trigger('focus');campo.addClass('is-invalid');setTimeout(function(){campo.removeClass('is-invalid');},1200);}}
        if(tipo==='success'||tipo==='info')setTimeout(function(){contenedor.find('.alert').alert('close');},4500);
    }
    function obtenerMensajeAjaxNoatentado(xhr,mensajePorDefecto){
        let mensaje=limpiarTextoNoAtentado(mensajePorDefecto||'Error interno.');
        if(xhr&&xhr.status===422&&xhr.responseJSON){
            if(xhr.responseJSON.errors){const errores=[];Object.keys(xhr.responseJSON.errors).forEach(function(campo){const lista=xhr.responseJSON.errors[campo]||[];if(Array.isArray(lista))for(let i=0;i<lista.length;i++){const t=limpiarTextoNoAtentado(lista[i]);if(t!=='')errores.push(t);}});if(errores.length>0)mensaje=errores.join(' ');}
            else if(xhr.responseJSON.message)mensaje=limpiarTextoNoAtentado(xhr.responseJSON.message);
        }else if(xhr&&xhr.status===419)mensaje='La sesión expiró. Recargue la página.';
        else if(xhr&&xhr.status===403)mensaje='Sin permisos para esta acción.';
        else if(xhr&&xhr.status===404)mensaje='Ruta no encontrada.';
        else if(xhr&&xhr.responseJSON&&xhr.responseJSON.message)mensaje=limpiarTextoNoAtentado(xhr.responseJSON.message);
        return mensaje;
    }
    function actualizarEstadoGuardadoNoatentado(enCurso){
        guardandoTramiteNoaEnCurso=enCurso===true;
        const botones=$('#btn_guardar_noa, #btn_guardar_noa_edit');
        botones.each(function(){
            const boton=$(this);
            if(!boton.data('texto-original'))boton.data('texto-original',boton.html());
            boton.prop('disabled',guardandoTramiteNoaEnCurso);
            if(guardandoTramiteNoaEnCurso)boton.html('<i class="fas fa-spinner fa-spin"></i> Guardando…');
            else boton.html(boton.data('texto-original'));
        });
    }
    function enviarFormularioTramiteNoAtentado(){
        if(guardandoTramiteNoaEnCurso)return;
        const form=$('#form_tramite');if(form.length===0)return;
        actualizarEstadoGuardadoNoatentado(true);limpiarMensajeNoatentado();
        const datosSerializados=form.serializeArray().filter(function(item){if(item.name==='reintegro')return normalizarNumeroNoAtentado(item.value)!=='';return true;});
        $.ajax({
            type:'POST',url:"{{url('guardar tramite convocatoria noatentado')}}",
            headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json, text/html;q=0.9, */*;q=0.8'},
            data:$.param(datosSerializados),
            success:function(resp){
                if(resp&&typeof resp==='object'){
                    if(resp.ok===false){mostrarMensajeNoatentado('error',limpiarTextoNoAtentado(resp.message||'No se pudo guardar el trámite.'));return;}
                    if(resp.ok===true&&resp.cerrar_modal===true){const urlLista=limpiarTextoNoAtentado(resp.refresh_url||"{{url('actualizar lista tramite convocatoria/'.$cod_con)}}");if(urlLista!=='')cargarDatos(urlLista,'panel_lista_tramites');$('#Noatentado').modal('hide');return;}
                    if(resp.ok===true&&resp.redirect){cargarDatos(resp.redirect,'panel_noatentado');cargarDatos("{{url('actualizar lista tramite convocatoria/'.$cod_con)}}",'panel_lista_tramites');return;}
                }
                $('#panel_noatentado').html(resp);
                cargarDatos("{{url('actualizar lista tramite convocatoria/'.$cod_con)}}",'panel_lista_tramites');
            },
            error:function(xhr){mostrarMensajeNoatentado('error',obtenerMensajeAjaxNoatentado(xhr,'No se pudo guardar el trámite.'));},
            complete:function(){actualizarEstadoGuardadoNoatentado(false);}
        });
    }
    function sincronizarEstadoCandidatosNoAtentado(){
        const tabla=$('#tabla_candidatos_noa tbody');if(tabla.length===0)return;
        const filas=tabla.find('tr');
        const sinFilasReales=filas.length===0||(filas.length===1&&filas.first().attr('id')==='fila_vacia_candidatos_noa');
        if(sinFilasReales&&Array.isArray(candidatosNoAtentado)&&candidatosNoAtentado.length>0){candidatosNoAtentado=[];$('#candidatos_json_noa').val('[]');}
    }
    function obtenerResumenCandidatosPagoNoAtentado(){
        sincronizarEstadoCandidatosNoAtentado();
        const documentos={};
        for(let i=0;i<candidatosNoAtentado.length;i++){const doc=normalizarDocumentoNoAtentado(candidatosNoAtentado[i].ci);if(doc!=='')documentos[doc]=true;}
        const lista=Object.keys(documentos);
        return{cantidad:lista.length,ciUnico:lista.length===1?lista[0]:'',documentos:lista};
    }
    function actualizarFiltroPreimpresoNoAtentado(){
        const inputCrear=$('#preimpreso_pago_noa');
        if(inputCrear.length){const resumenCrear=obtenerResumenCandidatosPagoNoAtentado(),habilitarCrear=resumenCrear.cantidad>1;inputCrear.prop('disabled',!habilitarCrear);if(!habilitarCrear)inputCrear.val('');}
    }
    function actualizarEstadoPagoNoAtentado(estado,clase,detalle,codigo){
        estadoControlPagoNoa={resumen:limpiarTextoNoAtentado(estado||'Pendiente'),clase:limpiarTextoNoAtentado(clase||'badge-warning'),detalle:limpiarTextoNoAtentado(detalle||''),codigo:limpiarTextoNoAtentado(codigo||'')};
        refrescarEstadoControlPagoNoatentado();
    }
    function normalizarClavePagoNoatentado(valor){return limpiarTextoNoAtentado(valor).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');}
    function selectorIconosPagoNoatentado(){return '[data-campo="estado-pago-control-icon"],[data-campo="estado-pago-reintegro-icon"],[data-campo="estado-pago-preimpreso-icon"]';}
    function cerrarPopoversPagoNoatentado(excepto){$(selectorIconosPagoNoatentado()).each(function(){if(excepto&&this===excepto)return;$(this).popover('hide').removeAttr('data-popover-visible');});}
    function abrirDetallePagoNoatentado(trigger){
        const icono=$(trigger);if(!icono.length)return false;
        const visible=icono.attr('data-popover-visible')==='1';
        if(visible){icono.popover('hide').removeAttr('data-popover-visible');return false;}
        cerrarPopoversPagoNoatentado(icono.get(0));
        icono.popover('dispose');
        icono.popover({container:'body',trigger:'manual',placement:'top',content:(icono.attr('data-detalle-pago')||'Sin detalle disponible').toString(),html:false}).popover('show');
        icono.attr('data-popover-visible','1');return false;
    }
    function tipoEstadoPagoNoatentadoDesdeClase(clase,resumen){
        const cn=limpiarTextoNoAtentado(clase).toLowerCase(),rn=limpiarTextoNoAtentado(resumen).toLowerCase();
        if(cn==='badge-success')return 'ok';
        if(cn==='badge-danger')return 'error';
        if(cn==='badge-info'){return rn==='validando'?'loading':'info';}
        if(cn==='badge-warning'){if(rn==='pendiente')return 'pending';if(rn==='no aplica'||rn==='n/a'||rn==='sin contexto')return 'na';return 'warning';}
        return 'pending';
    }
    function categoriaEstadoPagoNoatentado(tipo,resumen,detalle,codigo){
        const codigoNorm=limpiarTextoNoAtentado(codigo).toUpperCase();
        if(tipo==='loading')return 'loading';if(tipo==='ok')return 'ok';if(tipo==='pending')return 'pending';if(tipo==='na'||tipo==='no_aplica')return 'na';if(tipo==='warning')return 'warning';
        if(codigoNorm==='RATE_LIMIT')return 'rate_limit';if(codigoNorm==='SISTEMA_NO_CONFIGURADO')return 'not_configured';if(codigoNorm==='API_NO_DISPONIBLE')return 'connection';if(codigoNorm==='PAGO_YA_USADO')return 'used';if(codigoNorm==='CONTROL_NO_ENCONTRADO')return 'not_found';
        if(codigoNorm==='CUENTA_NO_CORRESPONDE'||codigoNorm==='CUENTA_SIN_TRAMITE_HABILITADO'||codigoNorm==='CUENTA_NO_IDENTIFICADA')return 'not_match';
        if(codigoNorm.indexOf('REINTEGRO_')===0){const cb=codigoNorm.replace(/^REINTEGRO_/,'');if(cb==='RATE_LIMIT')return 'rate_limit';if(cb==='SISTEMA_NO_CONFIGURADO')return 'not_configured';if(cb==='API_NO_DISPONIBLE'||cb==='API_RESPUESTA_INVALIDA')return 'connection';if(cb==='PAGO_YA_USADO')return 'used';if(cb==='CONTROL_NO_ENCONTRADO')return 'not_found';return 'not_match';}
        const texto=normalizarClavePagoNoatentado((resumen||'')+' '+(detalle||''));
        if(texto.indexOf('too many')!==-1||texto.indexOf('demasiadas solicitudes')!==-1||texto.indexOf('429')!==-1||texto.indexOf('rate limit')!==-1)return 'rate_limit';
        if(texto.indexOf('no esta configurado')!==-1||texto.indexOf('no esta configurada')!==-1)return 'not_configured';
        if(texto.indexOf('sin conexion')!==-1||texto.indexOf('no hay conexion')!==-1||texto.indexOf('no se pudo conectar')!==-1||texto.indexOf('api_no_disponible')!==-1||texto.indexOf('timeout')!==-1)return 'connection';
        if(texto.indexOf('ya fue utilizado')!==-1||texto.indexOf('ya usado')!==-1)return 'used';
        if(texto.indexOf('no se encontro')!==-1||texto.indexOf('no se encontró')!==-1||texto.indexOf('boleta no encontrada')!==-1)return 'not_found';
        if(texto.indexOf('no corresponde')!==-1)return 'not_match';
        return 'error';
    }
    function limpiarDetalleConResumenPagoNoatentado(resumen,detalle){
        let detalleTxt=limpiarTextoNoAtentado(detalle||'');if(detalleTxt==='')return '';
        const rn=normalizarClavePagoNoatentado(resumen||''),dn=normalizarClavePagoNoatentado(detalleTxt);
        if(rn!==''&&(dn===rn||dn.indexOf(rn+' ')===0||dn.indexOf(rn+':')===0||dn.indexOf(rn+'.')===0)){const escaped=(resumen||'').replace(/[.*+?^${}()|[\]\\]/g,'\\$&');detalleTxt=detalleTxt.replace(new RegExp('^'+escaped+'[\\s:\\.-]*','i'),'').trim();}
        return detalleTxt.replace(/^detalle\s*:\s*/i,'').trim();
    }
    function actualizarIconoPagoNoatentado(campo,tipo,resumen,detalle,codigo){
        const icono=$('[data-campo="estado-pago-'+campo+'-icon"]');if(!icono.length)return;
        let etiqueta='Control principal';
        if(campo==='reintegro')etiqueta='Control de reintegro';
        else if(campo==='preimpreso')etiqueta='Preimpreso';
        const resumenTxt=limpiarTextoNoAtentado(resumen||'Pendiente'),detalleTxt=limpiarTextoNoAtentado(detalle||'');
        const categoria=categoriaEstadoPagoNoatentado(tipo,resumenTxt,detalleTxt,codigo||'');
        const cfg=_pillCfgNoa(categoria);
        const el=icono.get(0);if(!el)return;
        const prevCls=(el.className||'').toString();
        _aplicarPillNoa(el,cfg,etiqueta+': '+cfg.label);
        icono.removeClass('noa-anim-pop noa-anim-alert');void el.offsetWidth;
        if(categoria==='ok'&&prevCls.indexOf('ok')===-1)icono.addClass('noa-anim-pop');
        else if(categoria!=='loading'&&categoria!=='pending'&&categoria!=='na'&&prevCls.indexOf(cfg.cls)===-1)icono.addClass('noa-anim-alert');
        let contenido=etiqueta+': '+cfg.label+'.';
        const dv=limpiarDetalleConResumenPagoNoatentado(cfg.label,detalleTxt);
        if(dv!=='')contenido+=' '+dv;
        icono.attr('data-detalle-pago',contenido).removeAttr('data-popover-visible').popover('hide');
    }
    function detalleDocumentoReintegroNoatentado(v){if(!v||v.aplica!==true)return '';return 'Verificación de titular aplicada internamente.';}
    function mostrarIconoPagoNoatentado(campo,mostrar){
        const icono=$('[data-campo="estado-pago-'+campo+'-icon"]');if(!icono.length)return;
        if(mostrar){icono.removeClass('invisible').attr('aria-hidden','false').css('pointer-events','auto');return;}
        icono.popover('hide').removeAttr('data-popover-visible').addClass('invisible').attr('aria-hidden','true').css('pointer-events','none');
    }
    function actualizarVisibilidadIconosPagoNoatentado(){
        const resumen=obtenerResumenCandidatosPagoNoAtentado(),esMulti=resumen.cantidad>1;
        mostrarIconoPagoNoatentado('control',!esMulti);
        mostrarIconoPagoNoatentado('preimpreso',esMulti);
    }
    function detalleControlPagoNoatentado(){
        const partes=[],principal=limpiarTextoNoAtentado(estadoControlPagoNoa.detalle||''),extendido=limpiarTextoNoAtentado(detalleExtendidoPagoNoa||'');
        if(principal!=='')partes.push(principal);if(extendido!=='')partes.push(extendido);
        return partes.join(' ');
    }
    function refrescarEstadoControlPagoNoatentado(){
        const tipo=tipoEstadoPagoNoatentadoDesdeClase(estadoControlPagoNoa.clase,estadoControlPagoNoa.resumen);
        actualizarIconoPagoNoatentado('control',tipo,estadoControlPagoNoa.resumen,detalleControlPagoNoatentado(),estadoControlPagoNoa.codigo||'');
    }
    function refrescarEstadoCamposPagoNoatentado(){
        const resumen=obtenerResumenCandidatosPagoNoAtentado();
        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        const reintegro=limpiarTextoNoAtentado($('#reintegro_noa').val());
        const preimpreso=limpiarTextoNoAtentado($('#preimpreso_pago_noa').val());
        const codigoControl=limpiarTextoNoAtentado(estadoControlPagoNoa.codigo||'').toUpperCase();
        const tipoControl=tipoEstadoPagoNoatentadoDesdeClase(estadoControlPagoNoa.clase,estadoControlPagoNoa.resumen);
        const detalleControl=detalleControlPagoNoatentado();
        const validando=validacionPagoNoaEnCurso===true&&controlActual!=='';
        actualizarVisibilidadIconosPagoNoatentado();
        const validacionReintegro=(detalleValidacionPagoNoAtentado&&detalleValidacionPagoNoAtentado.validacion_reintegro)?detalleValidacionPagoNoAtentado.validacion_reintegro:null;
        if(resumen.cantidad===0){actualizarIconoPagoNoatentado('reintegro','no_aplica','No aplica','Sin candidatos.');actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Sin candidatos.');return;}
        if(validando){
            if(resumen.cantidad>1)actualizarIconoPagoNoatentado('preimpreso','loading','Validando','Consultando…');
            else actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere preimpreso.');
            if(reintegro!=='')actualizarIconoPagoNoatentado('reintegro','loading','Validando','Consultando reintegro…');
            else actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
            return;
        }
        if(pagoNoAtentadoValidado&&controlActual!==''&&controlActual===controlValidadoNoAtentado){
            if(reintegro!==''){
                const reintegroCoincide=validacionReintegro&&reintegro===reintegroValidadoNoAtentado;
                if(reintegroCoincide&&validacionReintegro.ok===true){const dd=detalleDocumentoReintegroNoatentado(validacionReintegro);actualizarIconoPagoNoatentado('reintegro','ok','Validado',(limpiarTextoNoAtentado(validacionReintegro.message||'Reintegro validado.')+' '+dd).trim());}
                else if(reintegroCoincide&&validacionReintegro.ok===false){const er=resolverEstadoErrorPagoNoatentado(validacionReintegro,0),dd=detalleDocumentoReintegroNoatentado(validacionReintegro);actualizarIconoPagoNoatentado('reintegro','warning','No válido',(limpiarTextoNoAtentado(er.detalle)+' '+dd).trim(),er.codigo);}
                else actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro modificado; valide nuevamente.');
            }else actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
            if(resumen.cantidad>1)actualizarIconoPagoNoatentado('preimpreso','ok','Validado','Preimpreso validado.');
            else actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere.');
            return;
        }
        if(reintegro!=='')actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Ingresado; se validará con control y CI del pagador.');
        else actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');
        if(resumen.cantidad>1){
            if(preimpreso!==''){
                const hayError=codigoControl!==''&&codigoControl.indexOf('REINTEGRO_')!==0;
                if(hayError&&tipoControl!=='ok'&&tipoControl!=='loading'&&tipoControl!=='pending'){const ep=resolverEstadoErrorPagoNoatentado({code:codigoControl,message:detalleControl!==''?detalleControl:limpiarTextoNoAtentado(estadoControlPagoNoa.detalle||estadoControlPagoNoa.resumen||'No se pudo validar.')},0);actualizarIconoPagoNoatentado('preimpreso',ep.clase==='badge-danger'?'error':'warning',ep.resumen||'No válido',ep.detalle,ep.codigo||codigoControl);return;}
                actualizarIconoPagoNoatentado('preimpreso','pending','Pendiente','Preimpreso ingresado; valide pago para confirmar.');
            }else actualizarIconoPagoNoatentado('preimpreso','pending','Pendiente','Ingrese preimpreso para seleccionar el valorado correcto.');
            return;
        }
        actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere.');
    }
    function actualizarContextoControlPagoNoAtentado(){
        const control=$('#control_noa');if(control.length===0)return;
        const resumen=obtenerResumenCandidatosPagoNoAtentado();
        const sinContexto=resumen.cantidad===0;
        const reintegro=$('#reintegro_noa'),preimpreso=$('#preimpreso_pago_noa'),controlActual=limpiarTextoNoAtentado(control.val());
        if(sinContexto){
            control.val('').prop('disabled',true);reintegro.val('').prop('disabled',true);preimpreso.val('').prop('disabled',true);
            if(xhrValidacionPagoNoa&&xhrValidacionPagoNoa.readyState!==4)xhrValidacionPagoNoa.abort();
            pagoNoAtentadoValidado=false;controlValidadoNoAtentado='';reintegroValidadoNoAtentado='';tramiteValidadoNoAtentado='';detalleValidacionPagoNoAtentado=null;detalleExtendidoPagoNoa='';
            limpiarSeleccionTramiteNoatentado('Primero registre candidatos.');
            actualizarEstadoPagoNoAtentado('Sin contexto','badge-warning','Agregue candidatos para validar.');
            refrescarEstadoCamposPagoNoatentado();return;
        }
        control.prop('disabled',false);reintegro.prop('disabled',false);
        if(resumen.cantidad>1)preimpreso.prop('disabled',false);
        else{preimpreso.val('').prop('disabled',true);}
        if(controlActual===''){actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Ingrese número de control y valide.');limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');}
        else if(validacionPagoNoaEnCurso===true)actualizarEstadoPagoNoAtentado('Validando','badge-info','Consultando recaudaciones…');
        refrescarEstadoCamposPagoNoatentado();
    }
    function inicializarOpcionesTramiteNoatentado(){const s=$('#tramite_noa');if(!s.length)return;if(opcionesOriginalesTramiteNoa==='')opcionesOriginalesTramiteNoa=s.html();}
    function restaurarOpcionesTramiteNoatentado(){
        const s=$('#tramite_noa');if(!s.length)return;
        inicializarOpcionesTramiteNoatentado();const actual=limpiarTextoNoAtentado(s.val());
        s.html(opcionesOriginalesTramiteNoa);
        if(actual!==''&&s.find('option[value="'+actual+'"]').length)s.val(actual);
        s.prop('disabled',true);s.find('option').prop('disabled',false).show();
    }
    function limpiarSeleccionTramiteNoatentado(mensaje){
        const s=$('#tramite_noa');if(!s.length)return;
        restaurarOpcionesTramiteNoatentado();s.val('').prop('disabled',true);
        const ayuda=$('#ayuda_tramite_noa');if(ayuda.length)ayuda.text(limpiarTextoNoAtentado(mensaje||'Se define al validar el pago.'));
    }
    function obtenerTiposPermitidosNoatentado(resp){
        const lista=(resp&&Array.isArray(resp.tipos_noatentado_permitidos))?resp.tipos_noatentado_permitidos:[];
        const tipos=[];for(let i=0;i<lista.length;i++){const item=lista[i]||{},cod=limpiarTextoNoAtentado(item.cod_tre);if(cod!=='')tipos.push({cod_tre:cod,tre_nombre:limpiarTextoNoAtentado(item.tre_nombre)});}
        return tipos;
    }
    function renderDetallePagoNoatentado(resp){
        if(!resp||!resp.ok){detalleExtendidoPagoNoa='';refrescarEstadoControlPagoNoatentado();return;}
        const partes=[],principal=Number(resp&&resp.monto_principal_validado?resp.monto_principal_validado:0),reintegro=Number(resp&&resp.monto_reintegro_validado?resp.monto_reintegro_validado:0);
        const total=Number(resp&&resp.monto_total_validado?resp.monto_total_validado:(principal+reintegro));
        const tipoSugerido=limpiarTextoNoAtentado(resp.nombre_tipo_noatentado_sugerido),tiposPermitidos=obtenerTiposPermitidosNoatentado(resp);
        if(isFinite(total)&&total>0)partes.push('Monto total: Bs '+total.toFixed(2));
        if(tiposPermitidos.length>1){const nombres=tiposPermitidos.map(function(item){return item.tre_nombre!==''?item.tre_nombre:item.cod_tre;}).join(', ');if(nombres!=='')partes.push('Tipos permitidos: '+nombres);}
        else if(tipoSugerido!=='')partes.push('Tipo sugerido: '+tipoSugerido);
        detalleExtendidoPagoNoa=partes.join('. ');refrescarEstadoControlPagoNoatentado();
    }
    function aplicarEstadoReintegroDesdeRespuestaNoatentado(resp){
        const reintegroActual=limpiarTextoNoAtentado($('#reintegro_noa').val());
        if(reintegroActual===''){actualizarIconoPagoNoatentado('reintegro','no_aplica','Opcional','Sin reintegro.');return;}
        const vr=(resp&&resp.validacion_reintegro)?resp.validacion_reintegro:null;
        if(!vr){actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro ingresado; valide con control y CI.');return;}
        if(vr.ok===true){const dd=detalleDocumentoReintegroNoatentado(vr),msg=limpiarTextoNoAtentado(vr.message||'Reintegro validado.');actualizarIconoPagoNoatentado('reintegro','ok','Validado',(msg+' '+dd).trim());return;}
        if(vr.ok===false){const er=resolverEstadoErrorPagoNoatentado(vr,0),dd=detalleDocumentoReintegroNoatentado(vr);actualizarIconoPagoNoatentado('reintegro','warning','No válido',(limpiarTextoNoAtentado(er.detalle)+' '+dd).trim(),er.codigo);return;}
        actualizarIconoPagoNoatentado('reintegro','pending','Pendiente','Reintegro ingresado; valide.');
    }
    function aplicarAutoseleccionTramiteNoatentado(resp){
        const select=$('#tramite_noa'),ayuda=$('#ayuda_tramite_noa');if(!select.length)return;
        restaurarOpcionesTramiteNoatentado();
        const tiposPermitidos=obtenerTiposPermitidosNoatentado(resp),sugerido=limpiarTextoNoAtentado(resp&&resp.tipo_noatentado_sugerido);
        const requiereSeleccionManual=!!(resp&&resp.requiere_seleccion_manual===true);
        if(tiposPermitidos.length>0){
            const permitidosMap={};for(let i=0;i<tiposPermitidos.length;i++)permitidosMap[tiposPermitidos[i].cod_tre]=true;
            select.find('option').each(function(){const op=$(this),valor=limpiarTextoNoAtentado(op.val());if(valor===''){op.prop('disabled',false).show();return;}if(permitidosMap[valor])op.prop('disabled',false).show();else op.prop('disabled',true).hide();});
            if(requiereSeleccionManual){const valorActual=limpiarTextoNoAtentado(select.val());if(valorActual!==''&&!permitidosMap[valorActual])select.val('');select.prop('disabled',false);if(ayuda.length)ayuda.text('Existen varios tipos con el mismo monto. Seleccione manualmente.');return;}
            let sugeridoFinal=sugerido;if(sugeridoFinal===''||!select.find('option[value="'+sugeridoFinal+'"]').length)sugeridoFinal=tiposPermitidos[0].cod_tre;
            if(sugeridoFinal!==''&&select.find('option[value="'+sugeridoFinal+'"]').length)select.val(sugeridoFinal);
            else if(!permitidosMap[limpiarTextoNoAtentado(select.val())])select.val('');
            select.prop('disabled',true);if(ayuda.length)ayuda.text('Tipo de trámite autoseleccionado.');return;
        }
        if(sugerido!==''&&select.find('option[value="'+sugerido+'"]').length){select.val(sugerido);if(ayuda.length)ayuda.text('Tipo de trámite sugerido automáticamente.');select.prop('disabled',true);return;}
        select.val('').prop('disabled',true);if(ayuda.length)ayuda.text('No se pudo autoseleccionar el tipo de trámite.');
    }
    function intentarActivarPagoValidadoNoatentado(control){
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());if(tramite==='')return false;
        if(!detalleValidacionPagoNoAtentado||!detalleValidacionPagoNoAtentado.ok)return false;
        const permitidos=obtenerTiposPermitidosNoatentado(detalleValidacionPagoNoAtentado);
        if(permitidos.length>0){let encontrado=false;for(let i=0;i<permitidos.length;i++)if(permitidos[i].cod_tre===tramite){encontrado=true;break;}if(!encontrado)return false;}
        pagoNoAtentadoValidado=true;controlValidadoNoAtentado=control;tramiteValidadoNoAtentado=tramite;return true;
    }
    function onCambioTramiteNoa(){
        if(!detalleValidacionPagoNoAtentado)return;
        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());
        if(controlActual===''||controlActual!==controlValidadoNoAtentado){pagoNoAtentadoValidado=false;tramiteValidadoNoAtentado='';actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Valide nuevamente el pago.');return;}
        if(intentarActivarPagoValidadoNoatentado(controlActual))actualizarEstadoPagoNoAtentado('Pago válido','badge-success',detalleValidacionPagoNoAtentado.message||'Pago validado correctamente.');
        else{pagoNoAtentadoValidado=false;tramiteValidadoNoAtentado='';actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','No se pudo definir automáticamente el tipo de trámite.');}
        programarValidacionPagoNoAtentado();
    }
    function resetValidacionPagoNoAtentado(){
        pagoNoAtentadoValidado=false;controlValidadoNoAtentado='';reintegroValidadoNoAtentado='';tramiteValidadoNoAtentado='';detalleValidacionPagoNoAtentado=null;detalleExtendidoPagoNoa='';
        reiniciarMontosValidadosNoatentado();actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Antes de guardar debe validar el número de control.');
        limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');actualizarContextoControlPagoNoAtentado();
        const controlActual=limpiarTextoNoAtentado($('#control_noa').val());if(controlActual!=='')programarValidacionPagoNoAtentado();
    }
    function renderTablaCandidatosNoAtentado(){
        const tabla=$('#tabla_candidatos_noa tbody');if(tabla.length===0)return;
        tabla.html('');
        if(candidatosNoAtentado.length===0){
            tabla.append('<tr id="fila_vacia_candidatos_noa"><td colspan="6" class="center" style="color:var(--e-s400);font-size:12px;padding:22px 0;"><i class="fas fa-users" style="font-size:18px;margin-bottom:6px;display:block;opacity:.3;"></i>No hay candidatos registrados.</td></tr>');
        }else{
            for(let i=0;i<candidatosNoAtentado.length;i++){
                const c=candidatosNoAtentado[i],cargo=c.cargo!==''?c.cargo:c.cargo_nombre;
                const nombreCompleto=escaparHtmlNoa((c.apellido||'')+' '+(c.nombre||''));
                tabla.append('<tr data-candidato-index="'+i+'"><td class="td-num">'+(i+1)+'</td><td class="td-main">'+nombreCompleto+'</td><td>'+escaparHtmlNoa(c.ci||'')+'</td><td>'+escaparHtmlNoa(c.cod_sis||'')+'</td><td>'+escaparHtmlNoa(cargo||'-')+'</td><td class="center"><button type="button" class="e-icon-action" title="Quitar" onclick="quitarCandidatoNoAtentado('+i+')"><i class="fas fa-trash-alt" style="color:var(--e-red);font-size:11px;"></i></button></td></tr>');
            }
        }
        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        actualizarFiltroPreimpresoNoAtentado();
        if($('#control_noa').length>0){resetValidacionPagoNoAtentado();return;}
        actualizarControlCupoCandidatosNoatentado();
    }
    function limpiarFormularioCandidatoNoAtentado(){$('#noa_ci,#noa_nombre,#noa_apellido,#noa_cod_sis,#noa_cargo').val('');$('#noa_cargo_convocatoria').val('');}
    function agregarCandidatoNoAtentado(){
        sincronizarEstadoCandidatosNoAtentado();
        const ci=normalizarDocumentoNoAtentado($('#noa_ci').val());
        const nombre=limpiarTextoNoAtentado($('#noa_nombre').val()).toUpperCase();
        const apellido=limpiarTextoNoAtentado($('#noa_apellido').val()).toUpperCase();
        const codSis=limpiarTextoNoAtentado($('#noa_cod_sis').val());
        let cargo=limpiarTextoNoAtentado($('#noa_cargo').val()).toUpperCase();
        const cargoConvocatoria=limpiarTextoNoAtentado($('#noa_cargo_convocatoria').val());
        const cargoNombreSeleccionado=limpiarTextoNoAtentado($('#noa_cargo_convocatoria option:selected').text()).toUpperCase();
        if(cargo==='SELECCIONE'||cargo==='SELECCIONAR')cargo='';
        let cargoNombre='';
        if(cargoConvocatoria!==''){cargoNombre=cargoNombreSeleccionado;if(cargoNombre==='SELECCIONE'||cargoNombre==='SELECCIONAR')cargoNombre='';}
        if(ci===''||nombre===''||apellido===''){mostrarMensajeNoatentado('warning','Complete CI, nombres y apellidos del candidato.','#noa_ci');return;}
        let duplicado=false;for(let i=0;i<candidatosNoAtentado.length;i++)if(normalizarDocumentoNoAtentado(candidatosNoAtentado[i].ci)===ci){duplicado=true;break;}
        if(duplicado){mostrarMensajeNoatentado('info','El CI '+ci+' ya está registrado.','#noa_ci');return;}
        const tramiteSeleccionado=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(tramiteSeleccionado!==''&&!esTramitePlanchaNoatentado(tramiteSeleccionado)&&candidatosNoAtentado.length>=1){mostrarMensajeNoatentado('warning','Para este tipo de trámite solo se permite un candidato.','#noa_ci');return;}
        candidatosNoAtentado.push({ci:ci,nombre:nombre,apellido:apellido,cod_sis:codSis,unidad:'',cargo:cargo,cargo_convocatoria:cargoConvocatoria,cargo_nombre:cargoNombre});
        renderTablaCandidatosNoAtentado();limpiarFormularioCandidatoNoAtentado();mostrarMensajeNoatentado('success','Candidato agregado correctamente.');
    }
    function quitarCandidatoNoAtentado(indice){if(indice<0||indice>=candidatosNoAtentado.length)return;candidatosNoAtentado.splice(indice,1);renderTablaCandidatosNoAtentado();}
    function cargarDatosPersonalesNoa(ci){
        ci=limpiarTextoNoAtentado(ci);if(ci==='')return;
        $.ajax({url:"{{url('datos_per/')}}/"+ci,type:'GET',success:function(resp){
            if(resp==='No'){$('#noa_nombre').val('');$('#noa_apellido').val('');$('#noa_cod_sis').val('');}
            else{const datos=JSON.parse(resp);$('#noa_nombre').val(datos['per_nombre']||'');$('#noa_apellido').val(datos['per_apellido']||'');$('#noa_cod_sis').val(datos['per_cod_sis']||'');}
        }});
    }
    function actualizarNombreExcelNoatentado(input){
        const label=$('#label_excel_candidatos_noa');if(!label.length)return;
        if(input&&input.files&&input.files.length>0){
            label.find('span').text('Importando...');
            label.find('i').removeClass('fa-file-excel').addClass('fa-spinner fa-spin');
        }else{
            label.find('span').text('Importar desde Excel');
            label.find('i').removeClass('fa-spinner fa-spin').addClass('fa-file-excel');
        }
    }
    function importarExcelCandidatosNoAtentado(){
        const ctrl=$('#excel_candidatos_noa');if(!ctrl.length||!ctrl[0].files||ctrl[0].files.length===0){mostrarMensajeNoatentado('warning','Seleccione un archivo Excel antes de importar.');return;}
        const label=$('#label_excel_candidatos_noa');
        const resetLabel = function() {
            if(label.length){
                label.find('span').text('Importar desde Excel');
                label.find('i').removeClass('fa-spinner fa-spin').addClass('fa-file-excel');
            }
        };
        const data=new FormData();data.append('_token','{{csrf_token()}}');data.append('lista',ctrl[0].files[0]);
        $.ajax({url:"{{url('importar candidato excel temporal noatentado/'.$cod_con)}}",type:'POST',processData:false,contentType:false,data:data,
            success:function(resp){
                if(!resp||!resp.ok){mostrarMensajeNoatentado('error',(resp&&resp.message)?resp.message:'No se pudo importar el archivo.');resetLabel();ctrl.val('');return;}
                const lista=Array.isArray(resp.candidatos)?resp.candidatos:[];let agregados=0;
                for(let i=0;i<lista.length;i++){
                    const candidato=lista[i]||{},ci=normalizarDocumentoNoAtentado(candidato.ci);if(ci==='')continue;
                    let existe=false;for(let j=0;j<candidatosNoAtentado.length;j++)if(normalizarDocumentoNoAtentado(candidatosNoAtentado[j].ci)===ci){existe=true;break;}
                    if(existe)continue;
                    candidatosNoAtentado.push({ci:ci,nombre:limpiarTextoNoAtentado(candidato.nombre).toUpperCase(),apellido:limpiarTextoNoAtentado(candidato.apellido).toUpperCase(),cod_sis:limpiarTextoNoAtentado(candidato.cod_sis),unidad:limpiarTextoNoAtentado(candidato.unidad).toUpperCase(),cargo:(function(){const v=limpiarTextoNoAtentado(candidato.cargo).toUpperCase();return(v==='SELECCIONE'||v==='SELECCIONAR')?'':v;})(),cargo_convocatoria:limpiarTextoNoAtentado(candidato.cargo_convocatoria),cargo_nombre:(function(){const v=limpiarTextoNoAtentado(candidato.cargo_nombre).toUpperCase();return(v==='SELECCIONE'||v==='SELECCIONAR')?'':v;})()});
                    agregados++;
                }
                renderTablaCandidatosNoAtentado();ctrl.val('');resetLabel();
                let mensaje='Importación completada. Candidatos agregados: '+agregados+'.';
                if(Array.isArray(resp.errores)&&resp.errores.length>0)mensaje+=' Observaciones: '+resp.errores.join(' ');
                mostrarMensajeNoatentado('success',mensaje);
            },
            error:function(xhr){
                let mensaje='No se pudo importar el archivo.';
                if(xhr&&xhr.responseJSON){if(xhr.responseJSON.message)mensaje=xhr.responseJSON.message;else if(xhr.responseJSON.errors){const ks=Object.keys(xhr.responseJSON.errors);if(ks.length>0&&xhr.responseJSON.errors[ks[0]].length>0)mensaje=xhr.responseJSON.errors[ks[0]][0];}}
                mostrarMensajeNoatentado('error',mensaje);
                resetLabel();ctrl.val('');
            }
        });
    }
    function inferirCodigoErrorPagoNoatentado(mensaje,statusCode){
        const texto=normalizarClavePagoNoatentado(mensaje||'');
        if(parseInt(statusCode,10)===429||texto.indexOf('too many')!==-1||texto.indexOf('demasiadas solicitudes')!==-1||texto.indexOf('rate limit')!==-1)return 'RATE_LIMIT';
        if(texto.indexOf('no esta configurado')!==-1||texto.indexOf('no esta configurada')!==-1||texto.indexOf('sistema_no_configurado')!==-1||texto.indexOf('services/.env')!==-1)return 'SISTEMA_NO_CONFIGURADO';
        if(texto.indexOf('sin conexion')!==-1||texto.indexOf('sin conexión')!==-1||texto.indexOf('no se pudo conectar')!==-1||texto.indexOf('api_no_disponible')!==-1||texto.indexOf('timeout')!==-1)return 'API_NO_DISPONIBLE';
        if(texto.indexOf('no se encontro')!==-1||texto.indexOf('no se encontró')!==-1||texto.indexOf('boleta no encontrada')!==-1)return 'CONTROL_NO_ENCONTRADO';
        if(texto.indexOf('ya fue utilizado')!==-1||texto.indexOf('ya usado')!==-1)return 'PAGO_YA_USADO';
        if(texto.indexOf('no corresponde')!==-1)return 'CUENTA_NO_CORRESPONDE';
        return 'API_RECAUDACIONES_ERROR';
    }
    function resolverEstadoErrorPagoNoatentado(resp,statusCode){
        const mensaje=(resp&&resp.message)?limpiarTextoNoAtentado(resp.message):'No se pudo validar el pago.';
        let codigo=(resp&&resp.code)?limpiarTextoNoAtentado(resp.code).toUpperCase():'';
        if(codigo==='')codigo=inferirCodigoErrorPagoNoatentado(mensaje,statusCode||0);
        if(codigo==='RATE_LIMIT')return{resumen:'Demasiadas solicitudes',clase:'badge-warning',detalle:mensaje||'Intente en unos segundos.',codigo:codigo};
        if(codigo==='SISTEMA_NO_CONFIGURADO')return{resumen:'API no configurada',clase:'badge-warning',detalle:mensaje||'Contacte al área de sistemas.',codigo:codigo};
        if(codigo==='API_NO_DISPONIBLE'||codigo==='API_RESPUESTA_INVALIDA')return{resumen:'Sin conexión',clase:'badge-warning',detalle:mensaje||'Sin conexión con recaudaciones.',codigo:'API_NO_DISPONIBLE'};
        if(codigo==='CONTROL_NO_ENCONTRADO')return{resumen:'No encontrado',clase:'badge-danger',detalle:mensaje||'No se encontró información del control.',codigo:codigo};
        if(codigo==='PAGO_YA_USADO')return{resumen:'Ya utilizado',clase:'badge-warning',detalle:mensaje||'Este pago ya fue utilizado en otro trámite.',codigo:codigo};
        if(codigo==='PREIMPRESO_REQUERIDO_MULTI_CANDIDATO')return{resumen:'Preimpreso requerido',clase:'badge-warning',detalle:mensaje,codigo:codigo};
        if(codigo==='CONTEXTO_CANDIDATOS_REQUERIDO')return{resumen:'Sin contexto',clase:'badge-warning',detalle:mensaje,codigo:codigo};
        if(codigo.indexOf('REINTEGRO_')===0)return{resumen:'Reintegro no válido',clase:'badge-warning',detalle:mensaje,codigo:codigo};
        if(codigo==='CI_CANDIDATO_NO_COINCIDE'||codigo==='CARNET_CANDIDATO_NO_COINCIDE'||codigo==='DOCUMENTO_PAGO_NO_COINCIDE'||codigo==='FILTRO_PAGO_SIN_COINCIDENCIA'||codigo==='PREIMPRESO_PAGO_NO_COINCIDE'||codigo==='CUENTA_NO_CORRESPONDE'||codigo==='CUENTA_SIN_TRAMITE_HABILITADO'||codigo==='CUENTA_NO_IDENTIFICADA')return{resumen:'No corresponde',clase:'badge-warning',detalle:mensaje,codigo:codigo};
        return{resumen:'Pago no válido',clase:'badge-danger',detalle:mensaje,codigo:codigo};
    }
    function validarPagoNoAtentado(){
        const control=limpiarTextoNoAtentado($('#control_noa').val());
        const tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        const preimpreso=limpiarTextoNoAtentado($('#preimpreso_pago_noa').val());
        const resumenCandidatos=obtenerResumenCandidatosPagoNoAtentado();
        const esPreconsultaMulti=resumenCandidatos.cantidad>1&&preimpreso==='';
        if(control!==controlValidadoNoAtentado){pagoNoAtentadoValidado=false;reintegroValidadoNoAtentado='';tramiteValidadoNoAtentado='';limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');}
        if(resumenCandidatos.cantidad===0){if(xhrValidacionPagoNoa&&xhrValidacionPagoNoa.readyState!==4)xhrValidacionPagoNoa.abort();validacionPagoNoaEnCurso=false;limpiarSeleccionTramiteNoatentado('Primero registre candidatos.');actualizarEstadoPagoNoAtentado('Sin contexto','badge-warning','Primero agregue candidatos.');refrescarEstadoCamposPagoNoatentado();return;}
        if(control===''){if(xhrValidacionPagoNoa&&xhrValidacionPagoNoa.readyState!==4)xhrValidacionPagoNoa.abort();validacionPagoNoaEnCurso=false;reiniciarMontosValidadosNoatentado();limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');actualizarEstadoPagoNoAtentado('Pendiente','badge-warning','Ingrese el número de control para validar.');refrescarEstadoCamposPagoNoatentado();return;}
        const secuencia=((secuenciaValidacionPagoNoa||0)+1);secuenciaValidacionPagoNoa=secuencia;
        if(xhrValidacionPagoNoa&&xhrValidacionPagoNoa.readyState!==4)xhrValidacionPagoNoa.abort();
        validacionPagoNoaEnCurso=true;actualizarEstadoPagoNoAtentado('Validando','badge-info','Consultando recaudaciones…');refrescarEstadoCamposPagoNoatentado();
        xhrValidacionPagoNoa=$.ajax({
            url:"{{url('validar pago noatentado/'.$cod_con)}}",type:'POST',
            data:{_token:"{{csrf_token()}}",control:control,tramite:tramite,reintegro:limpiarTextoNoAtentado($('#reintegro_noa').val()),preimpreso_pago:preimpreso,preconsulta_control:esPreconsultaMulti?1:0,documento_pago:resumenCandidatos.ciUnico,cantidad_candidatos:resumenCandidatos.cantidad,ci_candidato_unico:resumenCandidatos.ciUnico,ci_candidatos:JSON.stringify(resumenCandidatos.documentos)},
            success:function(resp){
                if(secuenciaValidacionPagoNoa!==secuencia)return;if(limpiarTextoNoAtentado($('#control_noa').val())!==control)return;
                validacionPagoNoaEnCurso=false;const reintegroActual=limpiarTextoNoAtentado($('#reintegro_noa').val());
                if(resp&&resp.ok){
                    detalleValidacionPagoNoAtentado=resp;controlValidadoNoAtentado=control;reintegroValidadoNoAtentado=reintegroActual;
                    asignarMontosValidadosNoatentado(resp);aplicarAutoseleccionTramiteNoatentado(resp);renderDetallePagoNoatentado(resp);
                    if(intentarActivarPagoValidadoNoatentado(control)){
                        actualizarEstadoPagoNoAtentado('Pago válido','badge-success',resp.message||'Pago validado correctamente.');
                        if(resumenCandidatos.cantidad>1)actualizarIconoPagoNoatentado('preimpreso','ok','Validado','Preimpreso validado.');
                        else actualizarIconoPagoNoatentado('preimpreso','no_aplica','No aplica','Con candidato único no se requiere.');
                    }else{pagoNoAtentadoValidado=false;tramiteValidadoNoAtentado='';actualizarEstadoPagoNoAtentado('Pendiente','badge-warning',resp.message||'Pago validado, pero no se pudo autoseleccionar el trámite.');refrescarEstadoCamposPagoNoatentado();}
                    aplicarEstadoReintegroDesdeRespuestaNoatentado(resp);
                }else{
                    const estadoError=resolverEstadoErrorPagoNoatentado(resp||{},0);
                    const validacionPrincipal=(resp&&resp.validacion_principal&&resp.validacion_principal.ok)?resp.validacion_principal:null;
                    const codigoError=limpiarTextoNoAtentado((resp&&resp.code)?resp.code:'').toUpperCase();
                    const pendientePreimpreso=esPreconsultaMulti&&(codigoError==='PREIMPRESO_REQUERIDO_MULTI_CANDIDATO'||codigoError==='PAGO_AMBIGUO');
                    if(pendientePreimpreso){
                        pagoNoAtentadoValidado=false;controlValidadoNoAtentado='';reintegroValidadoNoAtentado='';tramiteValidadoNoAtentado='';detalleValidacionPagoNoAtentado=null;detalleExtendidoPagoNoa='';
                        reiniciarMontosValidadosNoatentado();renderDetallePagoNoatentado(null);limpiarSeleccionTramiteNoatentado('Ingrese preimpreso para definir el tipo de trámite.');
                        actualizarEstadoPagoNoAtentado('Pendiente','badge-info',resp&&resp.message?resp.message:'Control encontrado. Ingrese preimpreso para seleccionar el valorado correcto.');
                        refrescarEstadoCamposPagoNoatentado();aplicarEstadoReintegroDesdeRespuestaNoatentado(resp||{});return;
                    }
                    if(validacionPrincipal){
                        detalleValidacionPagoNoAtentado=$.extend({},validacionPrincipal,{validacion_reintegro:(resp&&resp.validacion_reintegro)?resp.validacion_reintegro:null});
                        controlValidadoNoAtentado=control;reintegroValidadoNoAtentado=reintegroActual;
                        asignarMontosValidadosNoatentado(resp||{});aplicarAutoseleccionTramiteNoatentado(validacionPrincipal);renderDetallePagoNoatentado(validacionPrincipal);
                        const detallePrincipal=limpiarTextoNoAtentado(validacionPrincipal.message||'Pago principal validado.');
                        if(intentarActivarPagoValidadoNoatentado(control))actualizarEstadoPagoNoAtentado('Pago principal válido','badge-success',detallePrincipal);
                        else{pagoNoAtentadoValidado=false;tramiteValidadoNoAtentado='';actualizarEstadoPagoNoAtentado('Pendiente','badge-warning',estadoError.detalle,estadoError.codigo);}
                    }else{
                        pagoNoAtentadoValidado=false;controlValidadoNoAtentado='';reintegroValidadoNoAtentado='';tramiteValidadoNoAtentado='';detalleValidacionPagoNoAtentado=null;detalleExtendidoPagoNoa='';
                        reiniciarMontosValidadosNoatentado();renderDetallePagoNoatentado(null);limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
                        actualizarEstadoPagoNoAtentado(estadoError.resumen,estadoError.clase,estadoError.detalle,estadoError.codigo);
                    }
                    if(codigoError.indexOf('REINTEGRO_')===0){refrescarEstadoCamposPagoNoatentado();aplicarEstadoReintegroDesdeRespuestaNoatentado(resp||{});return;}
                    refrescarEstadoCamposPagoNoatentado();aplicarEstadoReintegroDesdeRespuestaNoatentado(resp||{});
                }
            },
            error:function(xhr){
                if(secuenciaValidacionPagoNoa!==secuencia)return;if(xhr&&xhr.statusText==='abort')return;if(limpiarTextoNoAtentado($('#control_noa').val())!==control)return;
                validacionPagoNoaEnCurso=false;pagoNoAtentadoValidado=false;controlValidadoNoAtentado='';reintegroValidadoNoAtentado='';tramiteValidadoNoAtentado='';detalleValidacionPagoNoAtentado=null;detalleExtendidoPagoNoa='';
                reiniciarMontosValidadosNoatentado();renderDetallePagoNoatentado(null);limpiarSeleccionTramiteNoatentado('Se define al validar el pago.');
                let mensaje='No se pudo validar el pago.';
                if(xhr&&xhr.responseJSON){if(xhr.responseJSON.message)mensaje=xhr.responseJSON.message;else if(xhr.responseJSON.errors){const ks=Object.keys(xhr.responseJSON.errors);if(ks.length>0&&xhr.responseJSON.errors[ks[0]].length>0)mensaje=xhr.responseJSON.errors[ks[0]][0];}}
                const estadoError=resolverEstadoErrorPagoNoatentado(xhr&&xhr.responseJSON?xhr.responseJSON:{message:mensaje},xhr&&xhr.status?xhr.status:0);
                actualizarEstadoPagoNoAtentado(estadoError.resumen,estadoError.clase,estadoError.detalle,estadoError.codigo);refrescarEstadoCamposPagoNoatentado();
            },
            complete:function(){validacionPagoNoaEnCurso=false;if(secuenciaValidacionPagoNoa===secuencia)xhrValidacionPagoNoa=null;actualizarContextoControlPagoNoAtentado();}
        });
    }
    function guardarEdicionTramiteNoAtentado(){if($('#form_tramite').length===0)return;enviarFormularioTramiteNoAtentado();}
    function guardarTramiteNoAtentado(){
        if($('#form_tramite').length===0)return;
        if(candidatosNoAtentado.length===0){mostrarMensajeNoatentado('warning','Debe agregar al menos un candidato antes de guardar.','#noa_ci');return;}
        const control=limpiarTextoNoAtentado($('#control_noa').val()),tramite=limpiarTextoNoAtentado($('#tramite_noa').val());
        if(control===''||tramite===''){mostrarMensajeNoatentado('warning','Complete el número de control y confirme el trámite antes de guardar.','#control_noa');return;}
        if(!pagoNoAtentadoValidado&&detalleValidacionPagoNoAtentado&&control===controlValidadoNoAtentado)intentarActivarPagoValidadoNoatentado(control);
        if(!pagoNoAtentadoValidado||controlValidadoNoAtentado!==control||tramiteValidadoNoAtentado!==tramite){mostrarMensajeNoatentado('warning','No se guardó el trámite: primero valide correctamente el pago principal.','#control_noa');return;}
        const reintegro=limpiarTextoNoAtentado($('#reintegro_noa').val());
        if(reintegro!==''){
            const vr=(detalleValidacionPagoNoAtentado&&detalleValidacionPagoNoAtentado.validacion_reintegro)?detalleValidacionPagoNoAtentado.validacion_reintegro:null;
            const reintegroOk=!!(vr&&vr.ok===true&&vr.aplica===true&&reintegro===reintegroValidadoNoAtentado);
            if(!reintegroOk){mostrarMensajeNoatentado('warning','El reintegro no fue validado. Corrija el control de reintegro o déjelo vacío si no aplica.','#reintegro_noa');return;}
        }
        const controlCantidad=validarCantidadCandidatosPorMontoNoatentadoUI();
        if(!controlCantidad.ok){mostrarMensajeNoatentado('warning',controlCantidad.message,'#control_noa');return;}
        $('#candidatos_json_noa').val(JSON.stringify(candidatosNoAtentado));
        enviarFormularioTramiteNoAtentado();
    }

    function enfocarCiNoAtentado(){
        var campo=$('#noa_ci');
        if(!campo.length)return;
        if(campo.prop('disabled')||campo.prop('readonly'))return;
        setTimeout(function(){
            campo.trigger('focus');
            campo.trigger('select');
        },120);
    }

    $(function(){
        escalaCandidatosNoa=obtenerEscalaCandidatosConfigNoatentado();
        escalaCandidatosNoaNormalizada=normalizarEscalaCandidatosNoatentado();
        $('#tramite_noa').off('change.noaTramite').on('change.noaTramite',onCambioTramiteNoa);
        $(document).off('click.noaPagoPopover').on('click.noaPagoPopover',function(e){
            if($(e.target).closest(selectorIconosPagoNoatentado()+', .popover').length===0)cerrarPopoversPagoNoatentado();
        });
        $('#Noatentado').off('hidden.bs.modal.noaPagoPopover').on('hidden.bs.modal.noaPagoPopover',function(){cerrarPopoversPagoNoatentado();});
        $('#Noatentado').off('shown.bs.modal.noaFocus').on('shown.bs.modal.noaFocus',function(){enfocarCiNoAtentado();});
        if($('#tabla_candidatos_noa').length>0){
            renderTablaCandidatosNoAtentado();inicializarOpcionesTramiteNoatentado();resetValidacionPagoNoAtentado();
            actualizarFiltroPreimpresoNoAtentado();actualizarContextoControlPagoNoAtentado();actualizarControlCupoCandidatosNoatentado();
            enfocarCiNoAtentado();
        }
        if($('#control_noa_edit').length>0)actualizarFiltroPreimpresoNoAtentado();
    });
</script>
