<?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>

{{-- ═══════════════════════════════════════════════════════════════
     ESTILOS — diseño enterprise, desktop-first
══════════════════════════════════════════════════════════════════ --}}
<style>
@import url('https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600&display=swap');

/* ── Tokens ─────────────────────────────────────────────── */
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

/* ── Reset dentro del modal ─────────────────────────────── */
.eapo * { box-sizing: border-box; font-family: var(--ff); }

/* Mantener la fuente original de Font Awesome para que los iconos no se rompan */
.eapo i.fa,
.eapo i.fas,
.eapo i.far,
.eapo i.fal,
.eapo i.fab,
.eapo [class^="fa-"],
.eapo [class*=" fa-"] {
    font-family: var(--fa-style-family, "Font Awesome 6 Free"), "Font Awesome 5 Free", "Font Awesome 5 Pro", "Font Awesome 5 Brands", "FontAwesome" !important;
    font-style: normal;
    line-height: 1;
}

.eapo i.fa,
.eapo i.fas,
.eapo i.fal,
.eapo [class^="fa-"],
.eapo [class*=" fa-"] {
    font-weight: 900;
}

.eapo i.far {
    font-weight: 400;
}

/* ── Modal shell ────────────────────────────────────────── */
.eapo.modal-content {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,.22), 0 4px 16px rgba(0,0,0,.12);
}

/* ── Header ─────────────────────────────────────────────── */
.eapo .e-header {
    background: var(--e-navy);
    padding: 0 20px;
    height: 52px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 2px solid var(--e-blue);
}
.eapo .e-header-icon {
    width: 28px; height: 28px;
    background: rgba(255,255,255,.1);
    border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: rgba(255,255,255,.8);
    flex-shrink: 0;
}
.eapo .e-header-title {
    font-size: 13px; font-weight: 600; color: #fff;
    letter-spacing: .2px; flex: 1;
}
/* Trámite chip en el header — visible cuando cod_apos != 0 */
.eapo .e-header-chip {
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 4px;
    padding: 4px 10px;
}
.eapo .e-header-chip .chip-code {
    font-size: 13px; font-weight: 700; color: #fbbf24; letter-spacing: .5px;
}
.eapo .e-header-chip .chip-date {
    font-size: 11px; color: rgba(255,255,255,.55);
    border-left: 1px solid rgba(255,255,255,.2);
    padding-left: 8px;
}
/* Close — único botón de cierre, solo en header */
.eapo .e-close {
    width: 30px; height: 30px;
    border-radius: 4px;
    border: 1px solid rgba(255,255,255,.2);
    background: transparent;
    color: rgba(255,255,255,.7);
    font-size: 13px;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    transition: background .12s, color .12s;
    flex-shrink: 0;
}
.eapo .e-close:hover { background: rgba(255,255,255,.12); color: #fff; }

/* ── Body ───────────────────────────────────────────────── */
.eapo .e-body {
    background: var(--e-s100);
    padding: 16px 20px 20px;
}

/* ── Alerts ─────────────────────────────────────────────── */
.eapo .e-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 9px 12px;
    border-radius: var(--e-r-md);
    font-size: 12px; font-weight: 500;
    margin-bottom: 14px;
    border-left: 3px solid;
}
.eapo .e-alert.success { background: var(--e-green-lt); color: #065f46; border-color: var(--e-green); }
.eapo .e-alert.danger  { background: var(--e-red-lt);   color: #7f1d1d; border-color: var(--e-red); }
.eapo .e-alert-dismiss { margin-left: auto; background: none; border: none; cursor: pointer; opacity: .5; font-size: 14px; line-height: 1; color: inherit; }
.eapo .e-alert-dismiss:hover { opacity: 1; }

/* ── Main grid ──────────────────────────────────────────── */
.eapo .e-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 16px;
    align-items: start;
}

/* ── Section panels ─────────────────────────────────────── */
.eapo .e-panel {
    background: #fff;
    border: 1px solid var(--e-s200);
    border-radius: var(--e-r-md);
}
.eapo .e-panel + .e-panel { margin-top: 12px; }
.eapo .e-panel-head {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--e-s200);
    background: var(--e-s50);
    border-radius: var(--e-r-md) var(--e-r-md) 0 0;
}
.eapo .e-panel-head .ph-bar {
    width: 3px; height: 13px;
    border-radius: 2px;
    background: var(--e-blue);
    flex-shrink: 0;
}
.eapo .e-panel-head .ph-bar.red   { background: var(--e-red); }
.eapo .e-panel-head .ph-bar.slate { background: var(--e-s400); }
.eapo .e-panel-head .ph-title {
    font-size: 10.5px; font-weight: 600;
    letter-spacing: .6px; text-transform: uppercase;
    color: var(--e-s700);
}
.eapo .e-panel-body { padding: 14px; }

/* ── Form grid – campo compacto ─────────────────────────── */
.eapo .fg { display: grid; gap: 0 12px; }
.eapo .fg-2 { grid-template-columns: 1fr 1fr; }
.eapo .fg-3 { grid-template-columns: 1fr 1fr 1fr; }
.eapo .fg-span2 { grid-column: span 2; }

.eapo .e-field { display: flex; flex-direction: column; padding-bottom: 10px; }
.eapo .e-field:last-child { padding-bottom: 0; }
.eapo .e-field label {
    font-size: 10px; font-weight: 600;
    color: var(--e-s500); text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px;
}

/* Input editable */
.eapo .e-input {
    height: 32px;
    border: 1px solid var(--e-s300);
    border-radius: var(--e-r);
    padding: 0 9px;
    font-size: 12.5px; font-family: var(--ff);
    color: var(--e-s900);
    background: #fff;
    outline: none;
    transition: border-color .12s, box-shadow .12s;
    width: 100%;
}
.eapo .e-input:focus { border-color: var(--e-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.eapo .e-input[readonly],
.eapo .e-input.readonly { background: var(--e-s100); color: var(--e-s500); cursor: default; }

/* Valor estático (modo lectura) */
.eapo .e-val {
    font-size: 12.5px; color: var(--e-s900); font-weight: 500;
    padding: 5px 0;
    border-bottom: 1px solid var(--e-s100);
    min-height: 28px; display: flex; align-items: center;
}
.eapo .e-val.muted { color: var(--e-s500); font-weight: 400; }

/* Radio inline */
.eapo .e-radio-row { display: flex; gap: 16px; align-items: center; padding-top: 2px; }
.eapo .e-radio-opt { display: flex; align-items: center; gap: 6px; cursor: pointer; }
.eapo .e-radio-opt input[type="radio"] { accent-color: var(--e-blue); width: 13px; height: 13px; cursor: pointer; }
.eapo .e-radio-opt span { font-size: 12px; color: var(--e-s700); }

/* ── Buttons ─────────────────────────────────────────────── */
.eapo .e-btn {
    display: inline-flex; align-items: center; gap: 6px;
    height: 32px; padding: 0 14px;
    border-radius: var(--e-r); border: 1px solid transparent;
    font-size: 12px; font-weight: 600; font-family: var(--ff);
    cursor: pointer; white-space: nowrap;
    transition: background .12s, box-shadow .12s, transform .08s;
}
.eapo .e-btn:active { transform: scale(.98); }
.eapo .e-btn.is-loading {
    opacity: .75;
    pointer-events: none;
}
.eapo .e-btn.is-loading .fa-spinner { margin-right: 6px; }
.eapo .e-btn-primary { background: var(--e-blue); color: #fff; border-color: var(--e-blue); }
.eapo .e-btn-primary:hover { background: var(--e-blue-h); border-color: var(--e-blue-h); }
.eapo .e-btn-ghost {
    background: transparent; color: var(--e-s500);
    border-color: var(--e-s300);
}
.eapo .e-btn-ghost:hover { background: var(--e-s100); color: var(--e-s700); }
.eapo .e-btn-danger { background: var(--e-red); color: #fff; border-color: var(--e-red); }
.eapo .e-btn-danger:hover { background: #991b1b; }
.eapo .e-btn-full { width: 100%; justify-content: center; margin-top: 10px; }

/* ── Status pill (reemplaza iconos solos) ────────────────── */
.eapo .e-pill {
    display: inline-flex; align-items: center; gap: 5px;
    height: 24px; padding: 0 8px;
    border-radius: 12px; font-size: 10.5px; font-weight: 600;
    border: 1px solid;
    cursor: pointer; transition: opacity .12s;
    white-space: nowrap;
}
.eapo .e-pill:hover { opacity: .8; }
.eapo .e-pill.ok     { background: var(--e-green-lt); color: var(--e-green);  border-color: #a7f3d0; }
.eapo .e-pill.err    { background: var(--e-red-lt);   color: var(--e-red);    border-color: #fca5a5; }
.eapo .e-pill.warn   { background: var(--e-amber-lt); color: var(--e-amber);  border-color: #fcd34d; }
.eapo .e-pill.idle   { background: var(--e-s100);     color: var(--e-s400);   border-color: var(--e-s200); cursor: default; }
.eapo .e-pill.spin   { background: var(--e-blue-lt);  color: var(--e-blue);   border-color: #93c5fd; cursor: default; }

/* ── Quick-add section ───────────────────────────────────── */
/* Todo en una sola banda horizontal — desktop tiene espacio */
.eapo .e-qa-band {
    display: grid;
    grid-template-columns: 180px 1fr 3px 170px 100px auto auto;
    gap: 0 10px;
    align-items: end;
    padding: 14px;
}
.eapo .e-qa-sep {  /* separador visual entre paso 1 y 2 */
    width: 1px; background: var(--e-s200); margin-bottom: 2px; align-self: stretch;
}
.eapo .e-step-tag {
    display: flex; align-items: center; gap: 6px; margin-bottom: 6px;
}
.eapo .e-step-tag .sn {
    width: 17px; height: 17px; border-radius: 50%;
    background: var(--e-blue); color: #fff;
    font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.eapo .e-step-tag .sl {
    font-size: 10px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--e-s500);
}
.eapo .e-qa-col { display: flex; flex-direction: column; }
.eapo .e-qa-col label {
    font-size: 10px; font-weight: 600;
    color: var(--e-s500); text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px;
}
.eapo .e-qa-error {
    position: absolute;
    top: calc(100% + 2px);
    left: 0;
    white-space: nowrap;
    font-size: 10px;
    color: var(--e-red);
    line-height: 1.3;
    pointer-events: none;
}
.eapo .e-qa-error:empty { display: none; }
/* Wrapper relativo para que el error absoluto no desborde el grid */
.eapo .e-qa-input-wrap {
    position: relative;
    /* reserva espacio solo cuando hay error para no desplazar el boton */
}
/* Input con error — borde rojo */
.eapo .e-input.is-invalid {
    border-color: var(--e-red);
    box-shadow: 0 0 0 2px rgba(185,28,28,.12);
}
.eapo .e-qa-status {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    margin-top: 6px;
    padding: 2px 6px;
    font-size: 10.5px;
    color: var(--e-s500);
    background: var(--e-s50);
    border: 1px solid var(--e-s200);
    border-radius: var(--e-r);
    text-align: center;
}
.eapo .e-qa-status:empty { display: none; }
.eapo .e-qa-status.is-loading { color: var(--e-blue); background: var(--e-blue-lt); border-color: #bfdbfe; }
.eapo .e-qa-status.is-ok { color: var(--e-green); background: var(--e-green-lt); border-color: #a7f3d0; }
.eapo .e-qa-status.is-error { color: var(--e-red); background: var(--e-red-lt); border-color: #fca5a5; }

/* ── Closed notice ───────────────────────────────────────── */
.eapo .e-closed {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px;
    background: var(--e-s100); border-bottom: 1px solid var(--e-s200);
    font-size: 11.5px; color: var(--e-s500);
}

/* ── Documents table ─────────────────────────────────────── */
.eapo .e-tbl-wrap { overflow-y: auto; max-height: 240px; }
.eapo .e-tbl {
    width: 100%; border-collapse: collapse;
    font-size: 12px;
}
.eapo .e-tbl thead tr {
    background: var(--e-navy); position: sticky; top: 0; z-index: 1;
}
.eapo .e-tbl thead th {
    padding: 8px 12px;
    font-size: 10px; font-weight: 600; letter-spacing: .4px;
    color: rgba(255,255,255,.85); text-align: left; white-space: nowrap;
}
.eapo .e-tbl tbody tr { border-bottom: 1px solid var(--e-s100); }
.eapo .e-tbl tbody tr:last-child { border-bottom: none; }
.eapo .e-tbl tbody tr:hover { background: #f5f8ff; }
.eapo .e-tbl tbody td { padding: 7px 12px; color: var(--e-s900); vertical-align: middle; }
.eapo .e-tbl .td-num { color: var(--e-s400); font-size: 11px; width: 28px; }
.eapo .e-tbl .td-main { font-weight: 500; }
.eapo .e-tbl .td-sub { color: var(--e-s400); font-size: 11px; display: block; }
.eapo .e-tbl td .bold { font-weight: 700; }
.eapo .e-tbl td.center { text-align: center; }

/* Acción — icono trashcan */
.eapo .e-icon-del {
    width: 26px; height: 26px; border-radius: var(--e-r);
    border: 1px solid var(--e-s200); background: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--e-s400); font-size: 11px; cursor: pointer;
    text-decoration: none; transition: all .12s;
}
.eapo .e-icon-del:hover { background: var(--e-red-lt); border-color: #fca5a5; color: var(--e-red); }

/* ── Footer del panel de docs (barra de acciones) ────────── */
.eapo .e-doc-footer {
    display: flex; align-items: center; justify-content: space-between;
    padding: 10px 14px;
    border-top: 1px solid var(--e-s200);
    background: var(--e-s50);
    border-radius: 0 0 var(--e-r-md) var(--e-r-md);
    gap: 10px;
}
.eapo .e-doc-footer-left { display: flex; align-items: center; gap: 8px; }

/* ── Obs área ────────────────────────────────────────────── */
.eapo .e-obs {
    background: var(--e-s50);
    border-top: 1px solid var(--e-s100);
    padding: 10px 14px;
}
.eapo .e-obs-label {
    font-size: 10px; font-weight: 600; color: var(--e-s500);
    text-transform: uppercase; letter-spacing: .5px; margin-bottom: 5px;
}
.eapo .e-obs-box {
    font-size: 12px; color: var(--e-s700); line-height: 1.5;
    min-height: 36px; max-height: 64px; overflow-y: auto;
}
.eapo .e-obs-box:empty::before { content: '—'; color: var(--e-s300); }

/* ── Divider ─────────────────────────────────────────────── */
.eapo .e-divider { border: none; border-top: 1px solid var(--e-s200); margin: 0; }
</style>

{{-- ─────────────────────────────────────────────────────────────
     MARKUP
──────────────────────────────────────────────────────────────── --}}
<div class="modal-content eapo">

    {{-- ════ HEADER ════ --}}
    <div class="e-header">
        <span class="e-header-icon"><i class="fas fa-book"></i></span>
        <span class="e-header-title">Trámite de Apostilla</span>

        @if($cod_apos != 0)
            <div class="e-header-chip">
                <span class="chip-code">UAD{{ $tramite_apostilla->apos_numero }}</span>
                <span class="chip-date">
                    <?php if($tramite_apostilla->apos_fecha_ingreso != ''){ echo date('d/m/Y', strtotime($tramite_apostilla->apos_fecha_ingreso)); } ?>
                </span>
            </div>
        @endif

        {{-- Único botón de cierre --}}
        <button class="e-close" type="button" data-dismiss="modal" aria-label="Cerrar"
                title="Cerrar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- ════ BODY ════ --}}
    <div class="e-body">

        {{-- Alerts --}}
        @if(Session::has('exitoagregar'))
            <div class="e-alert success">
                <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{!! session('exitoagregar') !!}</span>
                <button class="e-alert-dismiss" onclick="this.closest('.e-alert').remove();">&times;</button>
            </div>
        @endif
        @if(Session::has('erroragregar'))
            <div class="e-alert danger">
                <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{!! session('erroragregar') !!}</span>
                <button class="e-alert-dismiss" onclick="this.closest('.e-alert').remove();">&times;</button>
            </div>
        @endif

        @php
            $urlGuardarTramiteApostilla          = url('guardar tramite apostilla');
            $urlGuardarApoderadoTramiteApostilla = url('guardar apoderado tramite apostilla');
            $urlTablaTramiteApostilla            = url('listar tramite apostilla tabla/' . date('Y-m-d'));
            $urlMostrarObservacionApostilla      = url('mostrar observacion tramite apostilla/' . ($tramite_apostilla->cod_apos ?? ''));
        @endphp

        {{-- ════ GRID PRINCIPAL ════ --}}
        <div class="e-grid {{ $cod_apos == 0 ? '' : '' }}">

            {{-- ─── COLUMNA IZQUIERDA — datos persona / apoderado ─── --}}
            <div>
                @if($cod_apos == 0)
                    {{-- ===== MODO NUEVO ===== --}}
                    <form id="form_tramite_apostilla">
                        @csrf

                        {{-- Datos personales --}}
                        <div class="e-panel">
                            <div class="e-panel-head">
                                <span class="ph-bar"></span>
                                <span class="ph-title">Datos personales</span>
                            </div>
                            <div class="e-panel-body">
                                <div class="fg fg-2">
                                    <div class="e-field">
                                        <label>CI</label>
                                        <input class="e-input" type="text" name="ci" id="ci_apostilla"
                                               onchange="cargarDatosPersonales(this.value)" autocomplete="off">
                                    </div>
                                    <div class="e-field">
                                        <label>Celular</label>
                                        <input class="e-input" type="text" name="celular" id="celular"
                                               required pattern="[0-8]{1-8}" autocomplete="off">
                                    </div>
                                    <div class="e-field">
                                        <label>Nombres</label>
                                        <input class="e-input" type="text" name="nombre" id="nombre" required autocomplete="off">
                                    </div>
                                    <div class="e-field">
                                        <label>Apellidos</label>
                                        <input class="e-input" type="text" name="apellido" id="apellido" required autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Apoderado --}}
                        <div class="e-panel">
                            <div class="e-panel-head">
                                <span class="ph-bar slate"></span>
                                <span class="ph-title">Apoderado</span>
                            </div>
                            <div class="e-panel-body">
                                <div class="fg fg-2">
                                    <div class="e-field fg-span2">
                                        <label>CI apoderado</label>
                                        <input class="e-input" type="text" name="ci_apoderado"
                                               onchange="cargarDatosApoderado(this.value)" autocomplete="off">
                                    </div>
                                    <div class="e-field">
                                        <label>Nombres</label>
                                        <input class="e-input" type="text" name="nombre_apoderado"
                                               id="nombre_apoderado" required autocomplete="off">
                                    </div>
                                    <div class="e-field">
                                        <label>Apellidos</label>
                                        <input class="e-input" type="text" name="apellido_apoderado"
                                               id="apellido_apoderado" required autocomplete="off">
                                    </div>
                                    <div class="e-field fg-span2">
                                        <label>Tipo de apoderado</label>
                                        <div class="e-radio-row">
                                            <label class="e-radio-opt">
                                                <input type="radio" name="tipo" value="d" checked>
                                                <span>Declaración jurada</span>
                                            </label>
                                            <label class="e-radio-opt">
                                                <input type="radio" name="tipo" value="p">
                                                <span>Poder notariado</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="ca" value="{{ $cod_apos }}">

                        @can('crear trámite - apo')
                            <button type="button" class="e-btn e-btn-primary e-btn-full"
                                    data-campo="btn-guardar-apostilla"
                                    onclick="guardarTramiteApostillaYEnfocar();return false;">
                                <i class="fas fa-save" style="font-size:11px;"></i> Guardar trámite
                            </button>
                            <div class="e-qa-status" data-campo="estado-guardar-tramite"></div>
                        @endcan
                    </form>

                @else
                    {{-- ===== MODO EDICIÓN — datos de solo lectura ===== --}}
                    <form id="form_tramite_apostilla">
                        @csrf

                        <div class="e-panel">
                            <div class="e-panel-head">
                                <span class="ph-bar"></span>
                                <span class="ph-title">Datos personales</span>
                            </div>
                            <div class="e-panel-body">
                                <div class="fg fg-2">
                                    <div class="e-field">
                                        <label>CI</label>
                                        <div class="e-val">{{ $persona->per_ci }}</div>
                                    </div>
                                    <div class="e-field">
                                        <label>Celular</label>
                                        <div class="e-val">{{ $persona->per_celular }}</div>
                                    </div>
                                    <div class="e-field">
                                        <label>Nombre</label>
                                        <div class="e-val">{{ $persona->per_nombre }}</div>
                                    </div>
                                    <div class="e-field">
                                        <label>Apellido</label>
                                        <div class="e-val">{{ $persona->per_apellido }}</div>
                                    </div>
                                    <div class="e-field fg-span2" style="padding-bottom:0;">
                                        <label>Fecha de ingreso</label>
                                        <div class="e-val muted">{{ date('d/m/Y', strtotime($tramite_apostilla->apos_fecha_ingreso)) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="e-panel">
                            <div class="e-panel-head">
                                <span class="ph-bar slate"></span>
                                <span class="ph-title">Apoderado</span>
                            </div>
                            <div class="e-panel-body">
                                @if($apoderado)
                                    <div class="fg fg-2">
                                        <div class="e-field">
                                            <label>CI apoderado</label>
                                            <div class="e-val">{{ $apoderado->apo_ci }}</div>
                                        </div>
                                        <div class="e-field">
                                            <label>Tipo</label>
                                            <div class="e-val">
                                                @if($tramite_apostilla->apos_apoderado == 'd') Decl. jurada
                                                @elseif($tramite_apostilla->apos_apoderado == 'p') Poder notariado
                                                @endif
                                            </div>
                                        </div>
                                        <div class="e-field">
                                            <label>Nombre</label>
                                            <div class="e-val">{{ $apoderado->apo_nombre }}</div>
                                        </div>
                                        <div class="e-field" style="padding-bottom:0;">
                                            <label>Apellido</label>
                                            <div class="e-val">{{ $apoderado->apo_apellido }}</div>
                                        </div>
                                    </div>
                                @else
                                    @can('editar apoderado - apo')
                                        <div class="fg fg-2">
                                            <div class="e-field fg-span2">
                                                <label>CI apoderado</label>
                                                <input class="e-input" type="text" name="ci_apoderado"
                                                       onchange="cargarDatosApoderado(this.value)" autocomplete="off">
                                            </div>
                                            <div class="e-field">
                                                <label>Nombres</label>
                                                <input class="e-input" type="text" name="nombre_apoderado"
                                                       id="nombre_apoderado" required autocomplete="off">
                                            </div>
                                            <div class="e-field">
                                                <label>Apellidos</label>
                                                <input class="e-input" type="text" name="apellido_apoderado"
                                                       id="apellido_apoderado" required autocomplete="off">
                                            </div>
                                            <div class="e-field fg-span2" style="padding-bottom:0;">
                                                <label>Tipo de apoderado</label>
                                                <div class="e-radio-row">
                                                    <label class="e-radio-opt">
                                                        <input type="radio" name="tipo" value="d" checked>
                                                        <span>Declaración jurada</span>
                                                    </label>
                                                    <label class="e-radio-opt">
                                                        <input type="radio" name="tipo" value="p">
                                                        <span>Poder notariado</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                @endif
                            </div>
                        </div>

                        <input type="hidden" name="ca" value="{{ $tramite_apostilla->cod_apos }}">

                        @can('editar apoderado - apo')
                            @if(!$apoderado)
                                <button type="button" class="e-btn e-btn-primary e-btn-full"
                                        onclick="enviar('form_tramite_apostilla','{{ $urlGuardarApoderadoTramiteApostilla }}','panel_apostilla');return false;">
                                    <i class="fas fa-user-check" style="font-size:11px;"></i> Guardar apoderado
                                </button>
                            @endif
                        @endcan
                    </form>
                @endif
            </div>

            {{-- ─── COLUMNA DERECHA — registro + documentos ─── --}}
            @if($cod_apos != 0)
            <div style="min-width:0;display:flex;flex-direction:column;gap:14px;">

                {{-- ── Panel registro rápido ── --}}
                @can('agregar documento - apo')
                <div class="e-panel">
                    <div class="e-panel-head">
                        <span class="ph-bar red"></span>
                        <span class="ph-title">Registro de apostillas</span>
                    </div>

                    @if($tramite_apostilla->apos_estado < 2)

                        <form id="form_agregar_tramite_rapido">
                            @csrf
                            {{-- ── Banda horizontal desktop ── --}}
                            <div class="e-qa-band">

                                {{-- PASO 1 --}}
                                <div class="e-qa-col">
                                    <div class="e-step-tag"><span class="sn">1</span><span class="sl">Pago</span></div>
                                    <label>N° control</label>
                                    <div style="display:flex;align-items:center;gap:6px;">
                                        <input type="text" class="e-input" name="nro_control"
                                               id="nro_control_rapido" autocomplete="off" inputmode="numeric" pattern="[0-9]*" style="flex:1;min-width:0;">
                                        <button type="button" class="e-pill idle"
                                                data-campo="estado-pago-icon"
                                                title="Ver detalle de validación de pago"
                                                tabindex="0"
                                                onclick="mostrarDetallePagoRapido(event,this);">
                                            <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                            <span data-label-pago>Pendiente</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="e-qa-col">
                                    <div class="e-step-tag" style="opacity:0;pointer-events:none;"><span class="sn">·</span><span class="sl">·</span></div>
                                    <label>Trámite detectado</label>
                                    <input type="text" class="e-input readonly" data-campo="tramite-detectado"
                                           readonly placeholder="—" style="width:100%;">
                                </div>

                                {{-- Separador visual --}}
                                <div class="e-qa-sep"></div>

                                {{-- PASO 2 --}}
                                <div class="e-qa-col">
                                    <div class="e-step-tag"><span class="sn">2</span><span class="sl">Documento</span></div>
                                    <label data-campo="label-documento">N° título / resolución</label>
                                    <div class="e-qa-input-wrap">
                                        <input type="text" class="e-input" name="numero" autocomplete="off" inputmode="numeric" pattern="[0-9]*" maxlength="20" style="width:100%;">
                                        <div class="e-qa-error" data-campo="error-numero"></div>
                                    </div>
                                </div>

                                <div class="e-qa-col">
                                    <div class="e-step-tag" style="opacity:0;pointer-events:none;"><span class="sn">·</span><span class="sl">·</span></div>
                                    <label>Gestión</label>
                                    <div class="e-qa-input-wrap">
                                         <input type="text" class="e-input" name="gestion" pattern="[0-9]{4}"
                                             autocomplete="off" inputmode="numeric" maxlength="4" placeholder="2024" style="width:100%;">
                                        <div class="e-qa-error" data-campo="error-gestion"></div>
                                    </div>
                                </div>

                                {{-- SITRA pill --}}
                                <div class="e-qa-col" style="align-self:end;padding-bottom:0;">
                                    <a href="#" class="e-pill idle" data-campo="estado-sitra-icon"
                                       title="Ver detalle SITRA"
                                       onclick="abrirModalSitraFormularioApostilla(this); return false;"
                                       style="text-decoration:none;margin-bottom:1px;">
                                        <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                        <span>SITRA</span>
                                    </a>
                                </div>

                                {{-- Botón agregar --}}
                                <div class="e-qa-col" style="align-self:end;">
                                    <button type="button" class="e-btn e-btn-primary"
                                            data-campo="btn-agregar-rapido"
                                            onclick="return submitAgregarApostillaRapida();"
                                            style="width:100%;justify-content:center;">
                                        <i class="fas fa-plus" style="font-size:10px;"></i> Agregar
                                    </button>
                                </div>

                            </div>{{-- /band --}}

                            {{-- Hidden fields — todos originales --}}
                            <input type="hidden" name="cl" value="" data-campo="tipo-apostilla-hidden">
                            <input type="hidden" name="ca" value="{{ $cod_apos }}">
                            <input type="hidden" name="gestion_valorado" value="" data-campo="gestion-api">
                            <input type="hidden" value="0"  data-campo="validacion-recaudacion-ok">
                            <input type="hidden" value=""   data-campo="preimpreso-api">
                            <input type="hidden" value=""   data-campo="estado-sitra">
                            <input type="hidden" value=""   data-campo="fuente-sitra">
                        </form>

                    @else
                        <div class="e-closed">
                            <i class="fas fa-lock" style="font-size:12px;"></i>
                            Este trámite ya fue firmado / entregado. No se pueden agregar más documentos.
                        </div>
                    @endif
                </div>
                @endcan

                {{-- ── Panel trámites seleccionados ── --}}
                <div class="e-panel" style="flex:1;">
                    <div class="e-panel-head" style="justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:8px;">
                            <span class="ph-bar red"></span>
                            <span class="ph-title">Trámites seleccionados</span>
                        </div>
                        <span style="font-size:10px;color:var(--e-s400);font-weight:600;"
                              data-campo="conteo-tramites">
                            {{ count($detalle_apostilla) }} registro(s)
                        </span>
                    </div>

                    <div id="panel_lista_tramites_apostilla" class="e-tbl-wrap">
                        <table class="e-tbl">
                            <thead>
                                <tr>
                                    <th class="td-num">#</th>
                                    <th>Nombre</th>
                                    <th>N° trámite</th>
                                    <th>N° Documento&nbsp;/&nbsp;Gestión</th>
                                    <th>Valorado&nbsp;/&nbsp;Gestión</th>
                                    <th style="width:90px;">SITRA</th>
                                    <th style="width:36px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($detalle_apostilla as $d)
                                    <tr>
                                        <td class="td-num">{{ $i }}</td>
                                        <td class="td-main" style="max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"
                                            title="{{ $d->lis_nombre }}">{{ $d->lis_nombre }}</td>
                                        <td>{{ $d->dapo_numero }}</td>
                                        <td>
                                            <span class="bold">{{ $d->dapo_numero_documento }}</span>
                                            <span class="td-sub">{{ $d->dapo_gestion_documento }}</span>
                                        </td>
                                        <td>
                                            <span class="bold">{{ $d->dapo_valorado_preimpreso }}</span>
                                            <span class="td-sub">{{ $d->dapo_valorado_gestion }}</span>
                                        </td>
                                        <td class="center">
                                            @if(($d->dapo_verificacion_sitra ?? '') === '0')
                                                <a href="#" class="e-pill ok"
                                                   data-target="#docleg" data-toggle="modal"
                                                   data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                                                   onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                                                   style="text-decoration:none;" title="Coincide en SITRA">
                                                    <i class="fas fa-check-circle" style="font-size:12px;"></i>
                                                </a>
                                            @elseif(($d->dapo_verificacion_sitra ?? '') === '1' || ($d->dapo_verificacion_sitra ?? '') === '2')
                                                <a href="#" class="e-pill err"
                                                   data-target="#docleg" data-toggle="modal"
                                                   data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                                                   onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                                                   style="text-decoration:none;" title="No coincide / no existe">
                                                    <i class="fas fa-times-circle" style="font-size:12px;"></i>
                                                </a>
                                            @else
                                                <span class="e-pill idle" style="cursor:default;" title="Sin verificación">
                                                    <i class="fas fa-minus-circle" style="font-size:12px;"></i>
                                                </span>
                                            @endif
                                        </td>
                                        <td class="center">
                                            @can('quitar doumento - apo')
                                                @if($tramite_apostilla->apos_estado <= 1)
                                                    <a href="#" class="e-icon-del"
                                                       onclick="cargarDatos('{{ url('eliminar tramite agregado apostilla/' . $d->cod_dapo) }}','panel_lista_tramites_apostilla');cargarDatos('{{ url('listar tramite apostilla tabla/' . date('Y-m-d', strtotime($tramite_apostilla->apos_fecha_ingreso))) }}','panel_tabla_tramites')"
                                                       title="Eliminar trámite">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Observaciones --}}
                    <div class="e-obs">
                        <div class="e-obs-label">Observaciones</div>
                        <div id="panel_observacion" class="e-obs-box">{{ $tramite_apostilla->apos_obs }}</div>
                    </div>

                    {{-- Barra de acciones — reemplaza al modal footer --}}
                    <div class="e-doc-footer">
                        <div class="e-doc-footer-left">
                            <a href="#tramite_apostilla" class="e-btn e-btn-ghost" data-toggle="modal"
                               onclick="cargarDatos('{{ $urlMostrarObservacionApostilla }}','panel_tramite_apostilla');"
                               style="text-decoration:none;">
                                <i class="fas fa-comment-alt" style="font-size:11px;"></i> Observar
                            </a>
                        </div>
                        @can('generar pdf - apo')
                            <a href="{{ url('generar pdf tramites apostilla/' . $cod_apos) }}"
                               class="e-btn e-btn-danger"
                               onclick="$('#apostilla').modal('hide');"
                               target="otro" style="text-decoration:none;">
                                <i class="fas fa-file-pdf" style="font-size:11px;"></i> Generar PDF
                            </a>
                        @endcan
                    </div>
                </div>

            </div>{{-- /col right --}}
            @endif

        </div>{{-- /grid --}}
    </div>{{-- /body --}}

    {{-- SIN modal-footer — el único cierre es la X del header --}}
    {{-- Las acciones de documento viven dentro del panel de docs --}}

</div>{{-- /eapo --}}

@php
    $fechaListadoApostilla = data_get($tramite_apostilla ?? null, 'apos_fecha_ingreso');
    $fechaListadoApostilla = $fechaListadoApostilla ? date('Y-m-d', strtotime($fechaListadoApostilla)) : date('Y-m-d');
@endphp

{{-- ═══════════════════ JS — idéntico al original, adaptado solo en visualización de pills ═══════ --}}
<script>
function cargarDatosPersonales(ci){
    var link="{{url('datos_per/')}}"+"/"+ci;
    $.ajax({url:link,type:'GET',success:function(resp){
        if(resp=="No"){$('#apellido').val('');$('#nombre').val('');}
        else{var res=JSON.parse(resp);$('#apellido').val(res['per_apellido']);$('#nombre').val(res['per_nombre']);$('#celular').val(res['per_celular']);}
    },error:function(){$('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");}});
}
function cargarDatosApoderado(ci){
    var link="{{url('datos_apo/')}}"+"/"+ci;
    $.ajax({url:link,type:'GET',success:function(resp){
        if(resp=="No"){$('#apellido_apoderado').val('');$('#nombre_apoderado').val('');}
        else{var res=JSON.parse(resp);$('#apellido_apoderado').val(res['apo_apellido']);$('#nombre_apoderado').val(res['apo_nombre']);}
    },error:function(){$('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");}});
}

function setBotonCargandoApostillaUi(btn,texto){
    if(!btn){return;}
    if(btn.dataset.loading==='1'){return;}
    btn.dataset.loading='1';
    btn.dataset.originalHtml=btn.innerHTML;
    btn.classList.add('is-loading');
    btn.setAttribute('aria-busy','true');
    btn.setAttribute('disabled','disabled');
    btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>'+(texto ? ' '+texto : ' Procesando...');
}
function limpiarBotonCargandoApostillaUi(btn){
    if(!btn || btn.dataset.loading!=='1'){return;}
    if(btn.dataset.originalHtml){btn.innerHTML=btn.dataset.originalHtml;}
    btn.classList.remove('is-loading');
    btn.removeAttribute('aria-busy');
    btn.removeAttribute('disabled');
    btn.dataset.loading='0';
}
function setErrorRapidoApostilla(campo,mensaje){
    const form=formApostillaRapida();
    if(!form.length){return;}
    const el=form.find('[data-campo="error-'+campo+'"]');
    if(!el.length){return;}
    el.text((mensaje||'').toString());
    // Marcar / desmarcar el input con borde rojo
    const input=form.find('input[name="'+campo+'"]');
    if(input.length){
        if(mensaje){input.addClass('is-invalid');}else{input.removeClass('is-invalid');}
    }
}
function limpiarErroresRapidoApostilla(){
    setErrorRapidoApostilla('numero','');
    setErrorRapidoApostilla('gestion','');
}
function setEstadoAccionRapida(mensaje,estado){
    // Sin panel secundario — el estado se refleja directamente en el botón y en el pill de pago
}
function setEstadoGuardarTramite(mensaje,estado){
    const el=$('[data-campo="estado-guardar-tramite"]');
    if(!el.length){return;}
    el.removeClass('is-loading is-ok is-error');
    if(estado==='loading'){el.addClass('is-loading');}
    else if(estado==='ok'){el.addClass('is-ok');}
    else if(estado==='error'){el.addClass('is-error');}
    el.html((mensaje||'').toString());
}
function obtenerBotonAgregarRapidoApostilla(){
    const form=formApostillaRapida();
    if(!form.length){return null;}
    return form.find('[data-campo="btn-agregar-rapido"]').first()[0] || null;
}
function obtenerBotonGuardarApostillaUi(){
    return document.querySelector('[data-campo="btn-guardar-apostilla"]');
}

let apostillaRapidaEnvioEnCurso=false;
let apostillaGuardarEnCurso=false;
let apostillaRapidaValidacionOk=false,apostillaRapidaControlValidado='',apostillaRapidaCodLisDetectado='';
let apostillaRapidaTimer=null,apostillaRapidaValidacionSeq=0,apostillaRapidaRetryTimer=null;
let apostillaRapidaDetallePago='Pendiente de validacion.',apostillaRapidaSitraSeq=0;

function compactarMensajeUxPagoApostilla(mensaje,respaldo){
    const texto=(mensaje||'').toString().trim(),fallback=(respaldo||'').toString().trim();
    if(texto==='')return fallback;
    const n=texto.toLowerCase();
    if(n.indexOf('no esta configurado')!==-1||n.indexOf('no esta configurada')!==-1)return 'Recaudaciones no configurado.';
    if(n.indexOf('no se pudo conectar')!==-1||n.indexOf('no hay conexion')!==-1||n.indexOf('api_no_disponible')!==-1)return 'Sin conexión.';
    if(n.indexOf('no se encontro')!==-1||n.indexOf('no se encontró')!==-1)return 'Boleta no encontrada.';
    if(n.indexOf('ya fue utilizado')!==-1||n.indexOf('ya fue registrada')!==-1||n.indexOf('ya esta registrada')!==-1||n.indexOf('ya está registrada')!==-1)return 'Boleta ya registrada.';
    if(n.indexOf('no corresponde')!==-1)return 'No corresponde.';
    if(texto.length>110)return fallback!==''?fallback:texto.substring(0,107)+'...';
    return texto;
}
function limpiarTextoUxApostilla(t){return(t||'').toString().replace(/\s+/g,' ').trim();}
function limitarTextoUxApostilla(t,m){const txt=limpiarTextoUxApostilla(t),max=(typeof m==='number'&&m>10)?m:240;return txt.length<=max?txt:txt.substring(0,max-3)+'...';}
function normalizarClaveUxApostilla(t){return limpiarTextoUxApostilla(t).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');}
function detectarCategoriaPagoUxApostilla(tipo,mensaje){
    if(tipo==='loading')return 'loading';if(tipo==='ok')return 'ok';if(tipo==='pending')return 'pending';
    const n=normalizarClaveUxApostilla(mensaje||'');
    if(n.indexOf('too many')!==-1||n.indexOf('demasiadas solicitudes')!==-1||n.indexOf('429')!==-1||n.indexOf('rate limit')!==-1)return 'rate_limit';
    if(n.indexOf('no esta configurado')!==-1||n.indexOf('no esta configurada')!==-1||n.indexOf('sistema_no_configurado')!==-1)return 'not_configured';
    if(n.indexOf('sin conexion')!==-1||n.indexOf('no hay conexion')!==-1||n.indexOf('no se pudo conectar')!==-1||n.indexOf('api_no_disponible')!==-1||n.indexOf('timeout')!==-1)return 'connection';
    if(n.indexOf('ya fue utilizado')!==-1||n.indexOf('ya fue registrada')!==-1||n.indexOf('ya esta registrada')!==-1||n.indexOf('no se puede usar nuevamente')!==-1)return 'used';
    if(n.indexOf('no se encontro')!==-1)return 'not_found';
    if(n.indexOf('no corresponde')!==-1||n.indexOf('no pertenece')!==-1)return 'not_match';
    return 'error';
}

/* ── Renderizar pill de pago ── */
function _pillConfig(categoria){
    const m={
        ok:          {cls:'ok',   icon:'fa-check-circle', label:'Validado'},
        loading:     {cls:'spin', icon:'fa-spinner fa-spin', label:'Validando…'},
        rate_limit:  {cls:'warn', icon:'fa-clock',        label:'Espere…'},
        used:        {cls:'warn', icon:'fa-ban',           label:'Ya usado'},
        connection:  {cls:'warn', icon:'fa-plug',          label:'Sin conexión'},
        not_configured:{cls:'idle',icon:'fa-cog',          label:'No configurado'},
        pending:     {cls:'idle', icon:'fa-minus-circle',  label:'Pendiente'},
        na:          {cls:'idle', icon:'fa-minus-circle',  label:'N/A'},
        not_match:   {cls:'warn', icon:'fa-exclamation-circle',label:'No coincide'},
        not_found:   {cls:'warn', icon:'fa-exclamation-circle',label:'No encontrada'},
        error:       {cls:'err',  icon:'fa-times-circle', label:'Inválido'},
    };
    return m[categoria]||m['error'];
}
function _aplicarPill(el,cfg,title){
    el.className='e-pill '+cfg.cls;
    el.setAttribute('title',title||cfg.label);
    el.innerHTML='<i class="fas '+cfg.icon+'" style="font-size:10px;"></i> <span data-label-pago>'+cfg.label+'</span>';
}
function _aplicarPillSitra(el,cfg,resumen){
    // Este método ya no se usará directamente, se maneja en actualizarEstadoSitraRapido para soporte de badge dinámico
}

function resumenCategoriaPagoUxApostilla(categoria,fallback){
    const m={ok:'Pago validado.',loading:'Validando pago…',pending:'Pendiente de validacion.',rate_limit:'Demasiadas solicitudes.',not_configured:'API no configurada.',connection:'Sin conexión.',used:'Ya utilizado.',not_found:'Boleta no encontrada.',not_match:'No corresponde.'};
    return m[categoria]||(fallback||'Pago no válido.').toString();
}
function deduplicarDetalleApostilla(resumen,detalle){
    let r=limpiarTextoUxApostilla(resumen||''),d=limpiarTextoUxApostilla(detalle||'');
    if(d==='')return '';
    const rn=normalizarClaveUxApostilla(r),dn=normalizarClaveUxApostilla(d);
    if(rn!==''&&(dn===rn||dn.indexOf(rn+' ')===0||dn.indexOf(rn+':')===0||dn.indexOf(rn+'.')===0)){const re=new RegExp('^'+r.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+'[\\s:\\.-]*','i');d=d.replace(re,'').trim();}
    return d;
}
function limpiarReintentoRapidoApostilla(){if(apostillaRapidaRetryTimer!==null){clearTimeout(apostillaRapidaRetryTimer);apostillaRapidaRetryTimer=null;}}
function construirDetalleUxPagoApostilla(tipo,mensajeOriginal,resumenCorto){
    const resumen=limpiarTextoUxApostilla(resumenCorto||'Pendiente de validacion.'),original=limpiarTextoUxApostilla(mensajeOriginal||'');
    const categoria=detectarCategoriaPagoUxApostilla(tipo,mensajeOriginal||resumenCorto);
    if(original===''||original.toLowerCase()===resumen.toLowerCase())return resumen;
    if(tipo==='error'){
        if(categoria==='rate_limit'){const l=deduplicarDetalleApostilla(resumen,original);return l===''?'Reintentando en 15 segundos.':limitarTextoUxApostilla(l+' Reintentando en 15 segundos.',300);}
        const l=deduplicarDetalleApostilla(resumen,original).replace(/^detalle\s*:\s*/i,'').trim();
        return l===''?resumen:limitarTextoUxApostilla('Detalle: '+l,280);
    }
    if(tipo==='ok'&&original.length<=140){const l=deduplicarDetalleApostilla(resumen,original).replace(/^detalle\s*:\s*/i,'').trim();return l===''?resumen:limitarTextoUxApostilla(resumen+' '+l,240);}
    return resumen;
}
function formApostillaRapida(){return $('#form_agregar_tramite_rapido');}

function setIconoPagoRapido(tipo,mensaje,categoriaForzada){
    const form=formApostillaRapida();if(!form.length)return;
    const el=form.find('[data-campo="estado-pago-icon"]')[0];if(!el)return;
    const categoria=(categoriaForzada||detectarCategoriaPagoUxApostilla(tipo,mensaje||'')).toString();
    const title=compactarMensajeUxPagoApostilla(mensaje||'',resumenCategoriaPagoUxApostilla(categoria,mensaje||''));
    const cfg=_pillConfig(categoria);
    _aplicarPill(el,cfg,title);
    $(el).attr('data-detalle-pago',title).removeAttr('data-popover-visible').popover('hide');
}
function estadoRegistroRapido(tipo,mensaje){
    const mensajeOriginal=(mensaje||'').toString().trim();
    const categoria=detectarCategoriaPagoUxApostilla(tipo,mensajeOriginal);
    let texto=compactarMensajeUxPagoApostilla(mensajeOriginal,resumenCategoriaPagoUxApostilla(categoria,'')).toString().trim();
    if(texto===''){if(tipo==='loading')texto='Validando pago…';else if(tipo==='ok')texto='Pago validado.';else if(tipo==='error')texto='Pago no válido.';else if(tipo==='warn')texto='Revise el dato.';else texto='Pendiente de validacion.';}
    apostillaRapidaDetallePago=construirDetalleUxPagoApostilla(tipo,mensajeOriginal,texto);
    setIconoPagoRapido(tipo,texto,categoria);
    const form=formApostillaRapida();
    if(form.length){const el=form.find('[data-campo="estado-pago-icon"]');if(el.length)el.attr('data-detalle-pago',apostillaRapidaDetallePago);}
}
function mostrarDetallePagoRapido(evento,elemento){
    if(evento){evento.preventDefault();evento.stopPropagation();}
    const icono=$(elemento);if(!icono.length)return false;
    const detalle=(icono.attr('data-detalle-pago')||apostillaRapidaDetallePago||'Pendiente de validacion.').toString();
    const visible=icono.attr('data-popover-visible')==='1';
    icono.popover('dispose');
    if(visible){icono.removeAttr('data-popover-visible');return false;}
    icono.popover({container:'body',trigger:'manual',placement:'top',content:detalle,html:false}).popover('show');
    icono.attr('data-popover-visible','1');
    return false;
}
function compactarMensajeSitraUxApostilla(mensaje,respaldo){
    const texto=(mensaje||'').toString().trim(),fallback=(respaldo||'').toString().trim();
    if(texto==='')return fallback;
    if(texto.length>110)return fallback!==''?fallback:texto.substring(0,107)+'...';
    return texto;
}
function actualizarEstadoSitraRapido(formulario,clase,mensaje){
    const el=formulario.find('[data-campo="estado-sitra-icon"]')[0];if(!el)return;
    const resumen=compactarMensajeSitraUxApostilla(mensaje,'SITRA pendiente.');
    const estadoInput=formulario.find('[data-campo="estado-sitra"]');
    let cfg;
    if(clase==='text-success'){cfg=_pillConfig('ok');estadoInput.val('0');}
    else if(clase==='text-danger'){cfg=_pillConfig('error');if(estadoInput.val()==='')estadoInput.val('1');}
    else if(clase==='text-info'){cfg=_pillConfig('loading');}
    else{cfg=_pillConfig('pending');if(['SITRA: no aplica para este tipo.','SITRA pendiente.','Complete gestión para validar SITRA.','Seleccione trámite para validar SITRA.'].indexOf(mensaje)!==-1)estadoInput.val('');}
    
    var fuente = String(formulario.find('[data-campo="fuente-sitra"]').val()||'sitra').toLowerCase();
    var textoBadge = 'SITRA';
    if(fuente === 'sid') textoBadge = 'SID';

    el.className='e-pill '+cfg.cls;
    el.setAttribute('title','Ver detalle de validación ' + textoBadge);
    el.setAttribute('aria-label',resumen);
    el.setAttribute('data-detalle-sitra',resumen);
    el.innerHTML='<i class="fas '+cfg.icon+'" style="font-size:10px;"></i> <span>' + textoBadge + '</span>';

    $(el).removeAttr('data-popover-visible').popover('hide');
}
function abrirModalSitraFormularioApostilla(trigger){
    const form=$(trigger).closest('form');
    const estado=(form.find('[data-campo="estado-sitra"]').val()||'').toString();
    const fuente=(form.find('[data-campo="fuente-sitra"]').val()||'sitra').toString();
    let detalle=(($(trigger).attr('data-detalle-sitra')||'').toString()||'').trim();
    if(detalle===''){if(estado==='0')detalle='Coincide en SITRA/SID.';else if(estado==='1')detalle='Existe, pero no coincide.';else if(estado==='2')detalle='No existe en SITRA/SID.';else detalle='SITRA pendiente.';}
    if(fuente==='ninguno'&&detalle.toLowerCase().indexOf('pendiente')!==-1){detalle='No existe en SITRA/SID.';}
    if(fuente==='sid')detalle+=' Fuente: SID.';else if(fuente==='ninguno')detalle+=' Fuente: Ninguna.';
    const icono=$(trigger);const visible=icono.attr('data-popover-visible')==='1';
    icono.popover('dispose');if(visible){icono.removeAttr('data-popover-visible');return false;}
    $('[data-campo="estado-pago-icon"],[data-campo="estado-sitra-icon"]').not(icono).popover('hide').removeAttr('data-popover-visible');
    icono.popover({container:'body',trigger:'manual',placement:'top',content:detalle,html:false}).popover('show');
    icono.attr('data-popover-visible','1');
    return false;
}
function validarSitraRapidaApostilla(){
    const form=formApostillaRapida();if(!form.length)return;
    const numero=(form.find('input[name="numero"]').val()||'').toString().trim();
    const gestion=(form.find('input[name="gestion"]').val()||'').toString().trim();
    const codLis=(form.find('input[name="cl"]').val()||'').toString().trim();
    const requestSeq=++apostillaRapidaSitraSeq;
    form.find('[data-campo="fuente-sitra"]').val('');
    if(numero===''||numero==='-'){actualizarEstadoSitraRapido(form,'text-muted','SITRA pendiente.');return;}
    if(codLis===''){actualizarEstadoSitraRapido(form,'text-muted','Seleccione trámite para validar SITRA.');return;}
    if(gestion===''){actualizarEstadoSitraRapido(form,'text-muted','Complete gestión para validar SITRA.');return;}
    actualizarEstadoSitraRapido(form,'text-info','Validando en SITRA/SID…');
    $.ajax({
        url:'{{url("validar sitra apostilla/".$cod_apos)}}',type:'POST',dataType:'json',
        data:{_token:form.find('input[name="_token"]').val(),numero:numero,gestion:gestion,cl:parseInt(codLis,10)||0},
        success:function(resp){
            if(requestSeq!==apostillaRapidaSitraSeq)return;
            if((form.find('input[name="numero"]').val()||'').toString().trim()!==numero)return;
            if((form.find('input[name="gestion"]').val()||'').toString().trim()!==gestion)return;
            if(!resp||resp.aplica===false){form.find('[data-campo="estado-sitra"]').val('');form.find('[data-campo="fuente-sitra"]').val('');actualizarEstadoSitraRapido(form,'text-muted',resp&&resp.message?resp.message:'SITRA: no aplica para este tipo.');return;}
            let estado=(resp&&resp.estado!==undefined&&resp.estado!==null)?String(resp.estado).trim():'';
            const fuente=(resp&&resp.fuente)?String(resp.fuente).toLowerCase():'sitra';
            const mensaje=(resp&&resp.message)?String(resp.message).toLowerCase():'';
            if((estado===''||estado==='null'||estado==='undefined')&&fuente==='sitra_sid')estado='2';
            if((estado===''||estado==='null'||estado==='undefined')&&mensaje.indexOf('no existe')!==-1)estado='2';
            if((estado===''||estado==='null'||estado==='undefined')&&mensaje.indexOf('no coincide')!==-1)estado='1';
            form.find('[data-campo="estado-sitra"]').val(estado);form.find('[data-campo="fuente-sitra"]').val(fuente);
            if(estado==='0')actualizarEstadoSitraRapido(form,'text-success','Coincide en SITRA/SID.');
            else if(estado==='1')actualizarEstadoSitraRapido(form,'text-danger','Existe, pero no coincide.');
            else if(estado==='2')actualizarEstadoSitraRapido(form,'text-danger','No existe en SITRA/SID.');
            else actualizarEstadoSitraRapido(form,'text-muted','SITRA pendiente.');
        },
        error:function(xhr){
            if(requestSeq!==apostillaRapidaSitraSeq)return;
            const msg=(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'SITRA/SID no disponible.';
            form.find('[data-campo="estado-sitra"]').val('2');form.find('[data-campo="fuente-sitra"]').val('ninguno');
            actualizarEstadoSitraRapido(form,'text-danger',msg);
        }
    });
}
function obtenerControlRapidoApostilla(){const form=formApostillaRapida();if(!form.length)return '';return(form.find('input[name="nro_control"]').val()||'').toString().trim();}
function aplicarEtiquetaDocumentoRapida(label){const form=formApostillaRapida();if(!form.length)return;form.find('[data-campo="label-documento"]').text((label||'N° título / resolución').toString());}
function extraerAnioDesdeFechaPagoApostilla(fechaPago){const v=(fechaPago||'').toString().trim();if(v==='')return '';const m=v.match(/(19|20)\d{2}/);return m?m[0]:'';}
function setGestionValoradoApostilla(anio){const form=formApostillaRapida();if(!form.length)return;form.find('input[data-campo="gestion-api"]').val((anio||'').toString());}
function limpiarEstadoValidacionRapida(){
    const form=formApostillaRapida();if(!form.length)return;
    form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
    form.find('input[data-campo="preimpreso-api"]').val('');form.find('input[data-campo="gestion-api"]').val('');
    apostillaRapidaValidacionOk=false;apostillaRapidaControlValidado='';apostillaRapidaCodLisDetectado='';
    form.find('input[name="cl"]').val('');form.find('[data-campo="tramite-detectado"]').val('');
    aplicarEtiquetaDocumentoRapida('N° título / resolución');
    setIconoPagoRapido('pending','Pendiente de validacion.');
    form.find('[data-campo="estado-sitra"]').val('');form.find('[data-campo="fuente-sitra"]').val('');
    actualizarEstadoSitraRapido(form,'text-muted','SITRA pendiente.');
    limpiarErroresRapidoApostilla();
    setEstadoAccionRapida('','');
}
function solicitarValidacionRapidaApostilla(callbackOk,callbackError){
    const form=formApostillaRapida();if(!form.length)return;
    const nroControl=obtenerControlRapidoApostilla();const requestSeq=++apostillaRapidaValidacionSeq;
    if(nroControl===''){limpiarReintentoRapidoApostilla();limpiarEstadoValidacionRapida();estadoRegistroRapido('pending','Ingrese N° de control.');if(typeof callbackError==='function')callbackError('Ingrese N° de control.');return;}
    estadoRegistroRapido('loading','');
    $.ajax({
        url:'{{url("validar valorado apostilla/".$cod_apos)}}',type:'POST',dataType:'json',
        data:{_token:form.find('input[name="_token"]').val(),nro_control:parseInt(nroControl,10)||0,ca:(form.find('input[name="ca"]').val()||'').toString().trim()},
        success:function(resp){
            if(requestSeq!==apostillaRapidaValidacionSeq)return;if(obtenerControlRapidoApostilla()!==nroControl)return;
            if(!(resp&&resp.ok)){
                const msg=(resp&&resp.message)?resp.message:'No se pudo validar el pago.';
                limpiarEstadoValidacionRapida();
                const esRate=detectarCategoriaPagoUxApostilla('error',msg)==='rate_limit';
                if(esRate){estadoRegistroRapido('error','Demasiadas solicitudes. Reintentando en 15 segundos.');limpiarReintentoRapidoApostilla();apostillaRapidaRetryTimer=setTimeout(function(){if(obtenerControlRapidoApostilla()!==nroControl)return;solicitarValidacionRapidaApostilla();},15000);}
                else estadoRegistroRapido('error',msg);
                if(typeof callbackError==='function')callbackError(msg);return;
            }
            limpiarReintentoRapidoApostilla();
            const codSugerido=(resp.cod_lis_sugerido||'').toString().trim();
            if(codSugerido!==''){form.find('input[name="cl"]').val(codSugerido);form.find('[data-campo="tramite-detectado"]').val((resp.lis_alias_sugerido||resp.lis_nombre_sugerido||'').toString());aplicarEtiquetaDocumentoRapida((resp.documento_label_sugerido||'N° título / resolución').toString());}
            else{limpiarEstadoValidacionRapida();estadoRegistroRapido('error','Boleta sin trámite válido.');if(typeof callbackError==='function')callbackError('Boleta sin trámite válido.');return;}
            const anio=extraerAnioDesdeFechaPagoApostilla(resp.fecha_pago||'');if(anio!=='')setGestionValoradoApostilla(anio);
            form.find('input[data-campo="preimpreso-api"]').val(resp.preimpreso||'');form.find('[data-campo="validacion-recaudacion-ok"]').val('1');
            apostillaRapidaValidacionOk=true;apostillaRapidaControlValidado=nroControl;apostillaRapidaCodLisDetectado=codSugerido;
            let resumen='Pago validado.';if(resp.lis_alias_sugerido)resumen='Pago validado. Trámite: '+resp.lis_alias_sugerido+'.';
            estadoRegistroRapido('ok',resumen);validarSitraRapidaApostilla();
            if(typeof callbackOk==='function')callbackOk(resp);
        },
        error:function(xhr){
            if(requestSeq!==apostillaRapidaValidacionSeq)return;if(obtenerControlRapidoApostilla()!==nroControl)return;
            const msg=(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'Sin conexión. Intente nuevamente.';
            limpiarEstadoValidacionRapida();
            const esRate=(xhr.status===429)||detectarCategoriaPagoUxApostilla('error',msg)==='rate_limit';
            if(esRate){estadoRegistroRapido('error','Demasiadas solicitudes. Reintentando en 15 segundos.');limpiarReintentoRapidoApostilla();apostillaRapidaRetryTimer=setTimeout(function(){if(obtenerControlRapidoApostilla()!==nroControl)return;solicitarValidacionRapidaApostilla();},15000);}
            else{limpiarReintentoRapidoApostilla();estadoRegistroRapido('error',msg);}
            if(typeof callbackError==='function')callbackError(msg);
        }
    });
}
function programarValidacionRapidaApostilla(){
    limpiarReintentoRapidoApostilla();
    if(apostillaRapidaTimer!==null)clearTimeout(apostillaRapidaTimer);
    apostillaRapidaTimer=setTimeout(function(){solicitarValidacionRapidaApostilla();},400);
}
function guardarAgregarApostillaRapida(onDone){
    const form=formApostillaRapida();if(!form.length)return;
    const codApos=(form.find('input[name="ca"]').val()||'').toString();
    const finalizar=function(){if(typeof onDone==='function')onDone();};
    $.ajax({
        url:'{{url("guardar agregar tramite apostilla")}}',type:'POST',dataType:'json',headers:{'Accept':'application/json'},data:form.serialize(),
        success:function(resp){
            if(resp&&resp.ok){
                cargarDatos('{{url("ajax tabla agregar")}}/'+codApos,'panel_lista_tramites_apostilla');
                cargarDatos('{{url("listar tramite apostilla tabla/$fechaListadoApostilla")}}','panel_tabla_tramites');
                form.find('input[name="nro_control"]').val('');form.find('input[name="numero"]').val('');form.find('input[name="gestion"]').val('');
                limpiarEstadoValidacionRapida();
                estadoRegistroRapido('pending','Trámite agregado. Ingrese nuevo N° de control.');
                setEstadoAccionRapida('Listo.','ok');
                form.find('input[name="nro_control"]').trigger('focus');
                finalizar();
                return;
            }
            const msg=(resp&&resp.message)?resp.message:'No se pudo registrar el trámite.';estadoRegistroRapido('error',msg);
            setEstadoAccionRapida('Error.','error');
            finalizar();
        },
        error:function(xhr){
            const msg=(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'No se pudo registrar el trámite.';
            estadoRegistroRapido('error',msg);
            setEstadoAccionRapida('Error.','error');
            finalizar();
        }
    });
}

function guardarTramiteApostillaYEnfocar(){
    const form=$('#form_tramite_apostilla');
    if(!form.length)return false;
    if(apostillaGuardarEnCurso){return false;}
    const btn=obtenerBotonGuardarApostillaUi();
    apostillaGuardarEnCurso=true;
    setBotonCargandoApostillaUi(btn,'Guardando...');
    setEstadoGuardarTramite('<i class="fas fa-spinner fa-spin"></i> Procesando...','loading');
    $.ajax({
        url:'{{ $urlGuardarTramiteApostilla }}',
        type:'POST',
        dataType:'json',
        headers:{'Accept':'application/json'},
        data:form.serialize(),
        success:function(resp){
            if(resp&&resp.ok&&resp.redirect){
                $.ajax({
                    url:resp.redirect,
                    type:'GET',
                    success:function(vista){
                        $('#panel_apostilla').html(vista);
                        setTimeout(function(){
                            const campo=$('#panel_apostilla').find('#nro_control_rapido').first();
                            if(campo.length && !campo.prop('disabled') && !campo.prop('readonly')){
                                campo.trigger('focus');
                                campo.trigger('select');
                            }
                        },120);
                    },
                    error:function(){
                        cargarDatos(resp.redirect,'panel_apostilla');
                    }
                });
                apostillaGuardarEnCurso=false;
                limpiarBotonCargandoApostillaUi(btn);
                setEstadoGuardarTramite('Listo.','ok');
                return;
            }
            if(resp&&resp.ok===false){
                estadoRegistroRapido('error',(resp.message||'No se pudo guardar el trámite.').toString());
                setEstadoGuardarTramite('Error.','error');
            }
            apostillaGuardarEnCurso=false;
            limpiarBotonCargandoApostillaUi(btn);
        },
        error:function(xhr){
            const msg=(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'No se pudo guardar el trámite.';
            estadoRegistroRapido('error',msg);
            apostillaGuardarEnCurso=false;
            limpiarBotonCargandoApostillaUi(btn);
            setEstadoGuardarTramite('Error.','error');
        }
    });
    return false;
}
function submitAgregarApostillaRapida(){
    const form=formApostillaRapida();if(!form.length)return false;
    const nroControl=obtenerControlRapidoApostilla();
    if(nroControl===''){estadoRegistroRapido('pending','Ingrese N° de control.');return false;}
    if(apostillaRapidaEnvioEnCurso){return false;}
    const btn=obtenerBotonAgregarRapidoApostilla();
    apostillaRapidaEnvioEnCurso=true;
    setBotonCargandoApostillaUi(btn,'Agregando...');
    setEstadoAccionRapida('<i class="fas fa-spinner fa-spin"></i> Procesando...','loading');
    const finalizarEnvio=function(){
        apostillaRapidaEnvioEnCurso=false;
        limpiarBotonCargandoApostillaUi(btn);
    };
    const listoParaGuardar=apostillaRapidaValidacionOk&&apostillaRapidaControlValidado===nroControl&&apostillaRapidaCodLisDetectado!==''&&(form.find('input[name="cl"]').val()||'').toString().trim()===apostillaRapidaCodLisDetectado&&form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';
    const intentarGuardar=function(){
        const codLis=(form.find('input[name="cl"]').val()||'').toString().trim();
        limpiarErroresRapidoApostilla();
        if(codLis===''||codLis!==apostillaRapidaCodLisDetectado){
            estadoRegistroRapido('error','No se detectó trámite válido.');
            setEstadoAccionRapida('Error.','error');
            finalizarEnvio();
            return;
        }
        const numeroDocumento=(form.find('input[name="numero"]').val()||'').toString().trim();
        const gestionDocumento=(form.find('input[name="gestion"]').val()||'').toString().trim();
        if(numeroDocumento!=='' && !/^\d+$/.test(numeroDocumento)){
            setErrorRapidoApostilla('numero','El numero debe ser numerico.');
            setEstadoAccionRapida('Revise los datos.','error');
            finalizarEnvio();
            return;
        }
        if(gestionDocumento!=='' && !/^\d{4}$/.test(gestionDocumento)){
            setErrorRapidoApostilla('gestion','La gestion debe tener 4 digitos.');
            setEstadoAccionRapida('Revise los datos.','error');
            finalizarEnvio();
            return;
        }
        const gestionValorado=(form.find('input[data-campo="gestion-api"]').val()||'').toString().trim();
        if(gestionValorado===''){
            estadoRegistroRapido('error','No se obtuvo gestión del pago.');
            setEstadoAccionRapida('Error.','error');
            finalizarEnvio();
            return;
        }
        guardarAgregarApostillaRapida(finalizarEnvio);
    };
    if(listoParaGuardar){intentarGuardar();return false;}
    solicitarValidacionRapidaApostilla(function(){intentarGuardar();},function(){finalizarEnvio();});
    return false;
}

$(document)
    .off('input.apoControlRapido','#nro_control_rapido')
    .on('input.apoControlRapido','#nro_control_rapido',function(){
        limpiarEstadoValidacionRapida();limpiarReintentoRapidoApostilla();
        if((this.value||'').toString().trim()===''){apostillaRapidaValidacionSeq+=1;estadoRegistroRapido('pending','Ingrese N° de control.');return;}
        programarValidacionRapidaApostilla();
    });
$(document)
    .off('input.apoSitraRapida','#form_agregar_tramite_rapido input[name="numero"],#form_agregar_tramite_rapido input[name="gestion"]')
    .on('input.apoSitraRapida','#form_agregar_tramite_rapido input[name="numero"],#form_agregar_tramite_rapido input[name="gestion"]',function(){
        if($(this).attr('name')==='numero'){setErrorRapidoApostilla('numero','');}
        if($(this).attr('name')==='gestion'){setErrorRapidoApostilla('gestion','');}
        validarSitraRapidaApostilla();
    });
$(document)
    .off('click.apoEstadoPagoDetalle')
    .on('click.apoEstadoPagoDetalle',function(e){
        if($(e.target).closest('[data-campo="estado-pago-icon"],[data-campo="estado-sitra-icon"],.popover').length===0){
            $('[data-campo="estado-pago-icon"],[data-campo="estado-sitra-icon"]').popover('hide').removeAttr('data-popover-visible');
        }
    });
$(function(){
    const form=formApostillaRapida();
    if(form.length){
        estadoRegistroRapido('pending','Pendiente de validacion.');
        actualizarEstadoSitraRapido(form,'text-muted','SITRA pendiente.');
        const campoControl=form.find('input[name="nro_control"]').first();
        if(campoControl.length && !campoControl.prop('disabled') && !campoControl.prop('readonly')){
            setTimeout(function(){
                campoControl.trigger('focus');
                campoControl.trigger('select');
            },90);
        }
        // MutationObserver: actualiza contador cada vez que el panel AJAX cambia
        const panelTabla=document.getElementById('panel_lista_tramites_apostilla');
        if(panelTabla){
            const actualizarContador=function(){
                const contador=document.querySelector('[data-campo="conteo-tramites"]');
                if(!contador)return;
                // Contar <tr> del tbody, excluyendo la fila de "sin registros"
                const filas=panelTabla.querySelectorAll('tbody tr');
                let n=0;
                filas.forEach(function(tr){
                    // La fila de "sin registros" tiene colspan=7
                    const celdas=tr.querySelectorAll('td[colspan]');
                    if(celdas.length===0){n++;}
                });
                contador.textContent=n+' registro(s)';
            };
            const obs=new MutationObserver(actualizarContador);
            obs.observe(panelTabla,{childList:true,subtree:true});
        }
        return;
    }

    const campoCi=$('#form_tramite_apostilla').find('input[name="ci"]').first();
    if(campoCi.length && !campoCi.prop('disabled') && !campoCi.prop('readonly')){
        setTimeout(function(){
            campoCi.trigger('focus');
            campoCi.trigger('select');
        },90);
    }
});
</script>
