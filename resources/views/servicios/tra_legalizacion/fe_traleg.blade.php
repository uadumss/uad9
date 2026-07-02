<?php $fecha=date('Y-m-d',strtotime($tramite->tra_fecha_solicitud))?>

{{-- ═══════════════════════════════════════════════════════════════
     ESTILOS — diseño enterprise, desktop-first (igual a apostilla)
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
.eleg * { box-sizing: border-box; font-family: var(--ff); }

.eleg i.fa,
.eleg i.fas,
.eleg i.far,
.eleg i.fal,
.eleg i.fab,
.eleg [class^="fa-"],
.eleg [class*=" fa-"] {
    font-family: var(--fa-style-family, "Font Awesome 6 Free"), "Font Awesome 5 Free", "Font Awesome 5 Pro", "Font Awesome 5 Brands", "FontAwesome" !important;
    font-style: normal;
    line-height: 1;
}
.eleg i.fa,
.eleg i.fas,
.eleg i.fal,
.eleg [class^="fa-"],
.eleg [class*=" fa-"] { font-weight: 900; }
.eleg i.far { font-weight: 400; }

/* ── Modal shell ────────────────────────────────────────── */
.eleg.modal-content {
    border: none;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 24px 64px rgba(0,0,0,.22), 0 4px 16px rgba(0,0,0,.12);
}

/* ── Header ─────────────────────────────────────────────── */
.eleg .e-header {
    background: var(--e-navy);
    padding: 0 20px;
    height: 52px;
    display: flex;
    align-items: center;
    gap: 12px;
    border-bottom: 2px solid var(--e-blue);
}
.eleg .e-header-icon {
    width: 28px; height: 28px;
    background: rgba(255,255,255,.1);
    border-radius: 4px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; color: rgba(255,255,255,.8);
    flex-shrink: 0;
}
.eleg .e-header-title {
    font-size: 13px; font-weight: 600; color: #fff;
    letter-spacing: .2px; flex: 1;
}
.eleg .e-header-chip {
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 4px;
    padding: 4px 10px;
}
.eleg .e-header-chip .chip-code {
    font-size: 13px; font-weight: 700; color: #fbbf24; letter-spacing: .5px;
}
.eleg .e-header-chip .chip-date {
    font-size: 11px; color: rgba(255,255,255,.55);
    border-left: 1px solid rgba(255,255,255,.2);
    padding-left: 8px;
}
.eleg .e-close {
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
.eleg .e-close:hover { background: rgba(255,255,255,.12); color: #fff; }

/* ── Body ───────────────────────────────────────────────── */
.eleg .e-body {
    background: var(--e-s100);
    padding: 16px 20px 20px;
}

/* ── Alerts ─────────────────────────────────────────────── */
.eleg .e-alert {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 9px 12px;
    border-radius: var(--e-r-md);
    font-size: 12px; font-weight: 500;
    margin-bottom: 14px;
    border-left: 3px solid;
}
.eleg .e-alert.success { background: var(--e-green-lt); color: #065f46; border-color: var(--e-green); }
.eleg .e-alert.danger  { background: var(--e-red-lt);   color: #7f1d1d; border-color: var(--e-red); }
.eleg .e-alert-dismiss { margin-left: auto; background: none; border: none; cursor: pointer; opacity: .5; font-size: 14px; line-height: 1; color: inherit; }
.eleg .e-alert-dismiss:hover { opacity: 1; }

/* ── Main grid ──────────────────────────────────────────── */
.eleg .e-grid {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 16px;
    align-items: start;
}

/* ── Section panels ─────────────────────────────────────── */
.eleg .e-panel {
    background: #fff;
    border: 1px solid var(--e-s200);
    border-radius: var(--e-r-md);
}
.eleg .e-panel + .e-panel { margin-top: 12px; }
.eleg .e-panel-head {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px;
    border-bottom: 1px solid var(--e-s200);
    background: var(--e-s50);
    border-radius: var(--e-r-md) var(--e-r-md) 0 0;
    justify-content: space-between;
}
.eleg .e-panel-head-left { display: flex; align-items: center; gap: 8px; }
.eleg .e-panel-head .ph-bar {
    width: 3px; height: 13px;
    border-radius: 2px;
    background: var(--e-blue);
    flex-shrink: 0;
}
.eleg .e-panel-head .ph-bar.red   { background: var(--e-red); }
.eleg .e-panel-head .ph-bar.slate { background: var(--e-s400); }
.eleg .e-panel-head .ph-bar.green { background: var(--e-green); }
.eleg .e-panel-head .ph-title {
    font-size: 10.5px; font-weight: 600;
    letter-spacing: .6px; text-transform: uppercase;
    color: var(--e-s700);
}
.eleg .e-panel-body { padding: 14px; }

/* ── Form fields ─────────────────────────────────────────── */
.eleg .fg { display: grid; gap: 0 12px; }
.eleg .fg-2 { grid-template-columns: 1fr 1fr; }
.eleg .fg-span2 { grid-column: span 2; }

.eleg .e-field { display: flex; flex-direction: column; padding-bottom: 10px; }
.eleg .e-field:last-child { padding-bottom: 0; }
.eleg .e-field label {
    font-size: 10px; font-weight: 600;
    color: var(--e-s500); text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px;
}

.eleg .e-input {
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
.eleg .e-input:focus { border-color: var(--e-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.eleg .e-input[readonly],
.eleg .e-input.readonly { background: var(--e-s100); color: var(--e-s500); cursor: default; }

.eleg .e-select {
    height: 32px;
    border: 1px solid var(--e-s300);
    border-radius: var(--e-r);
    padding: 0 9px;
    font-size: 12.5px; font-family: var(--ff);
    color: var(--e-s900);
    background: #fff;
    outline: none;
    width: 100%;
    cursor: pointer;
}
.eleg .e-select:focus { border-color: var(--e-blue); box-shadow: 0 0 0 3px rgba(26,86,219,.1); }
.eleg .e-select:disabled { background: var(--e-s100); color: var(--e-s500); cursor: default; }

.eleg .e-val {
    font-size: 12.5px; color: var(--e-s900); font-weight: 500;
    padding: 5px 0;
    border-bottom: 1px solid var(--e-s100);
    min-height: 28px; display: flex; align-items: center;
}
.eleg .e-val.muted { color: var(--e-s500); font-weight: 400; }

.eleg .e-radio-row { display: flex; gap: 16px; align-items: center; padding-top: 2px; flex-wrap: wrap; }
.eleg .e-radio-opt { display: flex; align-items: center; gap: 6px; cursor: pointer; }
.eleg .e-radio-opt input[type="radio"] { accent-color: var(--e-blue); width: 13px; height: 13px; cursor: pointer; }
.eleg .e-radio-opt span { font-size: 12px; color: var(--e-s700); }

/* ── Buttons ─────────────────────────────────────────────── */
.eleg .e-btn {
    display: inline-flex; align-items: center; gap: 6px;
    height: 32px; padding: 0 14px;
    border-radius: var(--e-r); border: 1px solid transparent;
    font-size: 12px; font-weight: 600; font-family: var(--ff);
    cursor: pointer; white-space: nowrap; text-decoration: none;
    transition: background .12s, box-shadow .12s, transform .08s;
}
.eleg .e-btn:active { transform: scale(.98); }
.eleg .e-btn-primary { background: var(--e-blue); color: #fff; border-color: var(--e-blue); }
.eleg .e-btn-primary:hover { background: var(--e-blue-h); border-color: var(--e-blue-h); color: #fff; }
.eleg .e-btn-ghost { background: transparent; color: var(--e-s500); border-color: var(--e-s300); }
.eleg .e-btn-ghost:hover { background: var(--e-s100); color: var(--e-s700); }
.eleg .e-btn-danger { background: var(--e-red); color: #fff; border-color: var(--e-red); }
.eleg .e-btn-danger:hover { background: #991b1b; color: #fff; }
.eleg .e-btn-sm { height: 26px; padding: 0 10px; font-size: 11px; }
.eleg .e-btn-full { width: 100%; justify-content: center; margin-top: 10px; }

/* ── Status pills ─────────────────────────────────────────── */
.eleg .e-pill {
    display: inline-flex; align-items: center; gap: 5px;
    height: 24px; padding: 0 8px;
    border-radius: 12px; font-size: 10.5px; font-weight: 600;
    border: 1px solid; cursor: pointer;
    transition: opacity .12s; white-space: nowrap; text-decoration: none;
}
.eleg .e-pill:hover { opacity: .8; }
.eleg .e-pill.ok     { background: var(--e-green-lt); color: var(--e-green);  border-color: #a7f3d0; }
.eleg .e-pill.err    { background: var(--e-red-lt);   color: var(--e-red);    border-color: #fca5a5; }
.eleg .e-pill.warn   { background: var(--e-amber-lt); color: var(--e-amber);  border-color: #fcd34d; }
.eleg .e-pill.idle   { background: var(--e-s100);     color: var(--e-s400);   border-color: var(--e-s200); cursor: default; }
.eleg .e-pill.spin   { background: var(--e-blue-lt);  color: var(--e-blue);   border-color: #93c5fd; cursor: default; }

/* ── Badge inline (Int./Ext.) ─────────────────────────────── */
.eleg .e-badge {
    display: inline-flex; align-items: center;
    height: 18px; padding: 0 6px;
    border-radius: 3px; font-size: 10px; font-weight: 700;
    letter-spacing: .3px; text-transform: uppercase;
}
.eleg .e-badge.int { background: var(--e-red-lt); color: var(--e-red); border: 1px solid #fca5a5; }
.eleg .e-badge.ext { background: var(--e-blue-lt); color: var(--e-blue); border: 1px solid #93c5fd; }

/* ── Notice list (ptaang / supletorios / títulos) ─────────── */
.eleg .e-notice-list { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 4px; }
.eleg .e-notice-item {
    display: flex; align-items: flex-start; gap: 6px;
    font-size: 11px; color: var(--e-s700);
    background: var(--e-amber-lt);
    border: 1px solid #fcd34d;
    border-radius: var(--e-r);
    padding: 5px 8px;
}
.eleg .e-notice-item i { color: var(--e-amber); margin-top: 1px; flex-shrink: 0; font-size: 10px; }

/* ── Documents table ─────────────────────────────────────── */
.eleg .e-tbl-wrap { overflow-y: auto; max-height: 260px; }
.eleg .e-tbl {
    width: 100%; border-collapse: collapse; font-size: 12px;
}
.eleg .e-tbl thead tr {
    background: var(--e-navy); position: sticky; top: 0; z-index: 1;
}
.eleg .e-tbl thead th {
    padding: 8px 10px;
    font-size: 10px; font-weight: 600; letter-spacing: .4px;
    color: rgba(255,255,255,.85); text-align: left; white-space: nowrap;
}
.eleg .e-tbl tbody tr { border-bottom: 1px solid var(--e-s100); }
.eleg .e-tbl tbody tr:last-child { border-bottom: none; }
.eleg .e-tbl tbody tr:hover { background: #f5f8ff; }
.eleg .e-tbl tbody tr.row-falso { background: var(--e-red-lt); }
.eleg .e-tbl tbody tr.row-falso:hover { background: #fde8e8; }
.eleg .e-tbl tbody tr.row-generado { background: var(--e-green-lt); }
.eleg .e-tbl tbody tr.row-generado:hover { background: #d1fae5; }
.eleg .e-tbl tbody td { padding: 6px 10px; color: var(--e-s900); vertical-align: middle; }
.eleg .e-tbl .td-num { color: var(--e-s400); font-size: 11px; width: 28px; }
.eleg .e-tbl .td-main { font-weight: 500; }
.eleg .e-tbl .td-sub { color: var(--e-s400); font-size: 11px; }
.eleg .e-tbl td.center { text-align: center; }
.eleg .e-tbl .td-actions { display: flex; align-items: center; gap: 4px; }

/* ── Icon action buttons ─────────────────────────────────── */
.eleg .e-icon-btn {
    width: 26px; height: 26px; border-radius: var(--e-r);
    border: 1px solid var(--e-s200); background: #fff;
    display: inline-flex; align-items: center; justify-content: center;
    color: var(--e-s500); font-size: 11px; cursor: pointer;
    text-decoration: none; transition: all .12s; flex-shrink: 0;
}
.eleg .e-icon-btn:hover { background: var(--e-s100); color: var(--e-s700); border-color: var(--e-s300); }
.eleg .e-icon-btn.del:hover { background: var(--e-red-lt); border-color: #fca5a5; color: var(--e-red); }
.eleg .e-icon-btn.primary:hover { background: var(--e-blue-lt); border-color: #93c5fd; color: var(--e-blue); }
.eleg .e-icon-btn.success:hover { background: var(--e-green-lt); border-color: #a7f3d0; color: var(--e-green); }

/* ── Panel footer (acciones) ─────────────────────────────── */
.eleg .e-panel-footer {
    display: flex; align-items: center; justify-content: flex-end;
    padding: 10px 14px;
    border-top: 1px solid var(--e-s200);
    background: var(--e-s50);
    border-radius: 0 0 var(--e-r-md) var(--e-r-md);
    gap: 8px;
}

/* ── Add-doc panel (formulario añadir documento) ─────────── */
.eleg .e-add-panel {
    border: 1px solid var(--e-s200);
    border-radius: var(--e-r-md);
    background: #fff;
    margin-top: 12px;
    overflow: hidden;
}
.eleg .e-add-panel-head {
    display: flex; align-items: center; justify-content: space-between;
    padding: 8px 14px;
    background: var(--e-s50);
    border-bottom: 1px solid var(--e-s200);
}
.eleg .e-add-panel-head-left { display: flex; align-items: center; gap: 8px; }
.eleg .e-add-panel-step {
    display: flex; align-items: center; gap: 6px; margin-bottom: 5px;
}
.eleg .e-add-panel-step .sn {
    width: 17px; height: 17px; border-radius: 50%;
    background: var(--e-blue); color: #fff;
    font-size: 9px; font-weight: 700;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.eleg .e-add-panel-step .sl {
    font-size: 10px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .5px;
    color: var(--e-s500);
}

/* Band para el formulario de tipo B (Búsqueda) */
.eleg .e-add-body { padding: 14px; }
.eleg .e-add-grid { display: grid; gap: 10px 14px; }
.eleg .e-add-grid.g2 { grid-template-columns: 1fr 1fr; }
.eleg .e-add-grid.g3 { grid-template-columns: 1fr 1fr 1fr; }
.eleg .e-add-grid.g4 { grid-template-columns: 1fr 1fr 1fr 1fr; }
.eleg .e-add-col { display: flex; flex-direction: column; }
.eleg .e-add-col label {
    font-size: 10px; font-weight: 600;
    color: var(--e-s500); text-transform: uppercase; letter-spacing: .5px;
    margin-bottom: 4px;
}
.eleg .e-add-row-inline {
    display: flex; align-items: end; gap: 8px;
}
.eleg .e-num-pair {
    display: flex; align-items: center; gap: 6px;
}
.eleg .e-num-pair .sep { font-size: 13px; color: var(--e-s400); font-weight: 500; }
.eleg .e-num-pair input { flex: 1; min-width: 0; }

/* ── Inline badges CUADIS / PTAG ─────────────────────────── */
.eleg .e-indicator {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 11px; font-weight: 600; color: var(--e-s700);
}
.eleg .e-indicator .badge {
    display: inline-flex; align-items: center;
    height: 18px; padding: 0 6px;
    border-radius: 9px; font-size: 10px; font-weight: 700;
}
.eleg .e-indicator .badge.off { background: var(--e-s200); color: var(--e-s500); }
.eleg .e-indicator .badge.on  { background: var(--e-green-lt); color: var(--e-green); border: 1px solid #a7f3d0; }

/* ── SITRA estado inline ─────────────────────────────────── */
[data-campo="estado-sitra"].eleg-sitra-box {
    font-size: 11.5px;
    line-height: 1.4;
    width: 100%;
    padding: 6px 10px;
    border-radius: var(--e-r);
    margin-top: 6px;
}
[data-campo="estado-sitra"].eleg-sitra-box .alert {
    margin: 0;
    font-size: 11.5px;
    border-radius: var(--e-r);
}

/* ── Apoderado toggle area ────────────────────────────────── */
.eleg .e-apo-edit { display: none; }

/* ── Expand/collapse icon ─────────────────────────────────── */
.eleg .e-collapse-btn {
    background: none; border: none; cursor: pointer;
    color: var(--e-red); font-size: 13px; padding: 0; line-height: 1;
}

/* ── Closed notice ───────────────────────────────────────── */
.eleg .e-closed {
    display: flex; align-items: center; gap: 8px;
    padding: 10px 14px;
    font-size: 11.5px; color: var(--e-s500);
}

/* ── Modal footer ─────────────────────────────────────────── */
.eleg .e-modal-footer {
    display: flex; align-items: center; justify-content: flex-end;
    padding: 12px 20px;
    background: var(--e-s50);
    border-top: 1px solid var(--e-s200);
    gap: 8px;
}
</style>

{{-- ─────────────────────────────────────────────────────────────
     MARKUP
──────────────────────────────────────────────────────────────── --}}
<div class="modal-content eleg border-bottom-primary ui-modal-traleg" xmlns="http://www.w3.org/1999/html">

    {{-- ════ HEADER ════ --}}
    <div class="e-header">
        <span class="e-header-icon"><i class="fas fa-book"></i></span>
        <span class="e-header-title">Legalización</span>
        <div class="e-header-chip">
            <span class="chip-code">{{ $tramite->tra_numero }}</span>
            <span class="chip-date">
                <?php if($tramite->tra_fecha_solicitud != ''){ echo date('d/m/Y', strtotime($tramite->tra_fecha_solicitud)); } ?>
            </span>
        </div>
        <button class="e-close" type="button" data-dismiss="modal" aria-label="Cerrar" title="Cerrar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- ════ BODY ════ --}}
    <div class="e-body">

        {{-- Alerts --}}
        @if(Session::has('exito'))
            <div class="e-alert success">
                <i class="fas fa-check-circle" style="margin-top:1px;flex-shrink:0;"></i>
                <span>{!! session('exito') !!}</span>
                <button class="e-alert-dismiss" onclick="this.closest('.e-alert').remove();">&times;</button>
            </div>
        @endif

        {{-- ════ MAIN GRID ════ --}}
        <div class="e-grid">

            {{-- ─── COLUMNA IZQUIERDA — datos persona / información adicional / apoderado ─── --}}

            @php
                $apoderadoHabilitado = (bool) config('apoderado.habilitado', true);

                $tieneApoderado = !empty(data_get($apoderado, 'apo_ci'));

                $modoEdicionPersona = empty($tramite->per_ci);

                $requiereBoletaDj = (bool) config(
                    'apoderado.requiere_boleta_dj',
                    false
                );

                $tipoApoderado = data_get(
                    $tramite,
                    'tra_tipo_apoderado',
                    ''
                ) ?: '';

                $mostrarBoleta = (
                    $tipoApoderado === 'd'
                    && $requiereBoletaDj
                );

                $tiposTramiteDj = [
                    'L' => 'Legalización',
                    'C' => 'Certificación',
                    'F' => 'Confrontación',
                    'B' => 'Búsqueda',
                    'E' => 'Consejo',
                ];

                $tiposTitulo = [
                    'da' => 'Diploma Académico',
                    'tp' => 'Título Profesional',
                    'di' => 'Diplomado',
                    'db' => 'Diploma de Bachiller',
                    'ca' => 'Certificado Académico',
                ];
            @endphp

            <div>

                {{-- Un único formulario para todo el bloque izquierdo --}}
                <form id="form_traleg">
                    @csrf

                    {{-- ===================================================== --}}
                    {{-- 1. DATOS PERSONALES                                   --}}
                    {{-- ===================================================== --}}

                    <div class="e-panel">
                        <div class="e-panel-head">
                            <div class="e-panel-head-left">
                                <span class="ph-bar"></span>
                                <span class="ph-title">Datos personales</span>
                            </div>
                        </div>

                        <div class="e-panel-body">

                            @if($modoEdicionPersona)

                                {{-- Persona todavía no registrada --}}
                                <div class="fg fg-2">

                                    <div class="e-field">
                                        <label>CI</label>

                                        <input
                                            class="e-input"
                                            name="ci"
                                            value="{{ $tramite->per_ci }}"
                                            onchange="cargarDatosPersonales(this.value)"
                                            autocomplete="off"
                                        >
                                    </div>

                                    <div class="e-field">
                                        <label>Pasaporte</label>

                                        <input
                                            class="e-input"
                                            name="pasaporte"
                                            value="{{ $tramite->per_pasaporte }}"
                                            autocomplete="off"
                                        >
                                    </div>

                                    <div class="e-field">
                                        <label>Apellidos</label>

                                        <input
                                            class="e-input"
                                            required
                                            name="apellido"
                                            id="apellido"
                                            value="{{ $tramite->per_apellido }}"
                                            autocomplete="off"
                                        >
                                    </div>

                                    <div
                                        class="e-field"
                                        style="padding-bottom:0;"
                                    >
                                        <label>Nombres</label>

                                        <input
                                            class="e-input"
                                            required
                                            name="nombre"
                                            id="nombre"
                                            value="{{ $tramite->per_nombre }}"
                                            autocomplete="off"
                                        >
                                    </div>

                                </div>

                            @else

                                {{-- Persona ya registrada: solo lectura --}}
                                <div class="fg fg-2">

                                    <div class="e-field">
                                        <label>CI</label>

                                        <div class="e-val">
                                            {{ $tramite->per_ci }}
                                        </div>
                                    </div>

                                    <div class="e-field">
                                        <label>Pasaporte</label>

                                        <div class="e-val muted">
                                            {{ $tramite->per_pasaporte ?: '—' }}
                                        </div>
                                    </div>

                                    <div
                                        class="e-field fg-span2"
                                        style="padding-bottom:0;"
                                    >
                                        <label>Nombre completo</label>

                                        <div class="e-val">
                                            {{ $tramite->per_nombre }}
                                            {{ $tramite->per_apellido }}
                                        </div>
                                    </div>

                                </div>

                                {{--
                                    Se mantienen ocultos para que g_traleg_completo
                                    reciba los datos personales aunque estén en modo lectura.
                                --}}
                                <input
                                    type="hidden"
                                    name="ci"
                                    value="{{ $tramite->per_ci }}"
                                >

                                <input
                                    type="hidden"
                                    name="pasaporte"
                                    value="{{ $tramite->per_pasaporte }}"
                                >

                                <input
                                    type="hidden"
                                    name="apellido"
                                    value="{{ $tramite->per_apellido }}"
                                >

                                <input
                                    type="hidden"
                                    name="nombre"
                                    value="{{ $tramite->per_nombre }}"
                                >

                            @endif

                        </div>
                    </div>


                    {{-- ===================================================== --}}
                    {{-- 2. INFORMACIÓN ADICIONAL                              --}}
                    {{-- ===================================================== --}}

                    @if(
                        isset($declaracionesJuradasDelDia)
                        && $declaracionesJuradasDelDia->isNotEmpty()
                    )

                        <div
                            class="e-panel"
                            style="margin-top:10px;"
                        >
                            <div class="e-panel-head">
                                <div class="e-panel-head-left">

                                    <span
                                        class="ph-bar"
                                        style="background:var(--e-amber);"
                                    ></span>

                                    <span class="ph-title">
                                        Declaración jurada registrada hoy
                                    </span>

                                </div>
                            </div>

                            <div
                                class="e-panel-body"
                                style="padding:10px 14px;"
                            >
                                <ul class="e-notice-list">

                                    @foreach($declaracionesJuradasDelDia as $dj)

                                        <li class="e-notice-item">

                                            <i class="fas fa-exclamation-triangle"></i>

                                            <span>
                                                Esta persona ya tiene una declaración jurada
                                                en

                                                <strong>
                                                    {{
                                                        $tiposTramiteDj[
                                                            $dj->tra_tipo_tramite
                                                        ] ?? 'Trámite'
                                                    }}
                                                </strong>

                                                Nº

                                                <strong>
                                                    {{ $dj->tra_numero }}
                                                </strong>

                                                del

                                                <strong>
                                                    {{
                                                        date(
                                                            'd/m/Y',
                                                            strtotime(
                                                                $dj->tra_fecha_solicitud
                                                            )
                                                        )
                                                    }}
                                                </strong>.
                                            </span>

                                        </li>

                                    @endforeach

                                </ul>
                            </div>
                        </div>

                    @endif


                    @if(
                        sizeof($ptaang) > 0
                        || sizeof($supletorios) > 0
                        || sizeof($titulos) > 0
                    )

                        <div
                            class="e-panel"
                            style="margin-top:10px;"
                        >
                            <div class="e-panel-head">
                                <div class="e-panel-head-left">

                                    <span
                                        class="ph-bar"
                                        style="background:var(--e-amber);"
                                    ></span>

                                    <span class="ph-title">
                                        Registros previos
                                    </span>

                                </div>
                            </div>

                            <div
                                class="e-panel-body"
                                style="padding:10px 14px;"
                            >
                                <ul class="e-notice-list">

                                    {{-- Registros PTAANG --}}
                                    @if(sizeof($ptaang) > 0)

                                        @foreach($ptaang as $p)

                                            <li class="e-notice-item">

                                                <i class="fas fa-exclamation-triangle"></i>

                                                <span>
                                                    Ya tiene

                                                    {{
                                                        \App\Models\Funciones::tipo_ptaang(
                                                            $p->dtra_ptaang
                                                        )
                                                    }}

                                                    Nº

                                                    <strong>
                                                        {{
                                                            $p->dtra_numero
                                                            . '/'
                                                            . $p->dtra_gestion
                                                        }}
                                                    </strong>

                                                    por PTAG
                                                </span>

                                            </li>

                                        @endforeach

                                    @endif


                                    {{-- Documentos supletorios --}}
                                    @if(sizeof($supletorios) > 0)

                                        @foreach($supletorios as $s)

                                            <li class="e-notice-item">

                                                <i class="fas fa-exclamation-triangle"></i>

                                                <span>
                                                    Ya tiene

                                                    <strong>
                                                        {{
                                                            \App\Models\Funciones::
                                                            tipoSupletorioDesdeReferencia(
                                                                $s->tit_ref
                                                            )
                                                        }}
                                                    </strong>

                                                    @if(!empty($s->titulo_original))

                                                        :
                                                        {{
                                                            $s->titulo_original
                                                                ->tit_titulo
                                                        }}

                                                        — emitido el

                                                        {{
                                                            $s->titulo_original
                                                                ->tit_fecha_emision
                                                        }}
                                                        @if(($t->nota_marginal ?? 'f') == 't')
                                                            <strong class="text-danger">(con N.M.)</strong>
                                                        @endif
                                                    @endif
                                                </span>

                                            </li>

                                        @endforeach

                                    @endif


                                    {{-- Títulos --}}
                                    @if(sizeof($titulos) > 0)

                                        @foreach($titulos as $t)

                                            <li class="e-notice-item">

                                                <i class="fas fa-exclamation-triangle"></i>

                                                <span>
                                                    Ya tiene el

                                                    <strong>
                                                        {{
                                                            $tiposTitulo[$t->tit_tipo]
                                                            ?? strtoupper($t->tit_tipo)
                                                        }}
                                                    </strong>

                                                    :
                                                    {{ $t->tit_titulo }}

                                                    — emitido el
                                                    {{ $t->tit_fecha_emision }}
                                                    @if(($t->nota_marginal ?? 'f') == 't')
                                                        <strong class="text-danger">(con N.M.)</strong>
                                                    @endif
                                                </span>

                                            </li>

                                        @endforeach

                                    @endif

                                </ul>
                            </div>
                        </div>

                    @endif


                    {{-- ===================================================== --}}
                    {{-- 3. APODERADO                                         --}}
                    {{-- ===================================================== --}}

                    @if($apoderadoHabilitado)

                        <div
                            class="e-panel"
                            style="margin-top:10px;"
                        >
                            <div class="e-panel-head">

                                <div class="e-panel-head-left">
                                    <span class="ph-bar slate"></span>
                                    <span class="ph-title">Apoderado</span>
                                </div>

                                @if(!$tieneApoderado)

                                    @can('editar apoderado traleg - srv')

                                        <button
                                            class="e-btn e-btn-sm e-btn-ghost"
                                            type="button"
                                            onclick="
                                                $('#procesar_apoderado').val('1');
                                                $('#eleg-apo-edit :input').prop(
                                                    'disabled',
                                                    false
                                                );
                                                $('#eleg-apo-view').hide(300);
                                                $('#eleg-apo-edit').show(300);

                                                setTimeout(function(){
                                                    $('#ci_apoderado_edi').trigger(
                                                        'focus'
                                                    );
                                                }, 320);
                                            "
                                        >
                                            <i
                                                class="fas fa-user-plus"
                                                style="font-size:10px;"
                                            ></i>

                                            Registrar apoderado
                                        </button>

                                    @endcan

                                @endif

                            </div>

                            <div class="e-panel-body">

                                {{-- Vista del apoderado --}}
                                <div id="eleg-apo-view">

                                    <div class="fg fg-2">

                                        <div class="e-field">
                                            <label>CI apoderado</label>

                                            <div class="e-val">

                                                @if($tieneApoderado)

                                                    {{ data_get($apoderado, 'apo_ci') }}

                                                @else

                                                    <span class="muted">
                                                        Sin registro
                                                    </span>

                                                @endif

                                            </div>
                                        </div>

                                        <div class="e-field">
                                            <label>Tipo</label>

                                            <div class="e-val muted">

                                                @if($tipoApoderado === 'd')

                                                    Decl. jurada

                                                @elseif($tipoApoderado === 'p')

                                                    Poder notariado

                                                @else

                                                    —

                                                @endif

                                            </div>
                                        </div>

                                        <div
                                            class="e-field fg-span2"
                                            style="padding-bottom:0;"
                                        >
                                            <label>Nombre apoderado</label>

                                            <div class="e-val">

                                                @if($tieneApoderado)

                                                    {{
                                                        data_get(
                                                            $apoderado,
                                                            'apo_apellido'
                                                        )
                                                    }}

                                                    {{
                                                        data_get(
                                                            $apoderado,
                                                            'apo_nombre'
                                                        )
                                                    }}

                                                @else

                                                    <span class="muted">
                                                        Sin registro
                                                    </span>

                                                @endif

                                            </div>
                                        </div>

                                    </div>

                                </div>


                                {{--
                                    El formulario de registro solamente existe cuando
                                    todavía no hay un apoderado asociado.
                                --}}
                                @if(!$tieneApoderado)

                                    @can('editar apoderado traleg - srv')

                                        <div
                                            id="eleg-apo-edit"
                                            style="display:none;"
                                        >

                                            <div class="fg fg-2">

                                                <div class="e-field fg-span2">
                                                    <label>CI apoderado</label>

                                                    <input
                                                        class="e-input"
                                                        name="ci_apoderado"
                                                        id="ci_apoderado_edi"
                                                        value=""
                                                        oninput="
                                                            verificarBoletaApoderadoEdi();
                                                        "
                                                        autocomplete="off"
                                                        disabled
                                                    >
                                                </div>


                                                <div
                                                    class="e-field fg-span2"
                                                    id="contenedor_boleta_apoderado_edi"
                                                    style="{{
                                                        $mostrarBoleta
                                                            ? ''
                                                            : 'display:none;'
                                                    }}"
                                                >
                                                    <label>N° control boleta</label>

                                                    <input
                                                        class="e-input"
                                                        name="control_boleta"
                                                        id="control_boleta_apoderado_edi"
                                                        oninput="
                                                            verificarBoletaApoderadoEdi()
                                                        "
                                                        autocomplete="off"
                                                        placeholder="Ingrese número de control"
                                                        disabled
                                                    >

                                                    <div style="margin-top:6px;">

                                                        <span
                                                            id="estado_pago_apoderado_edi"
                                                            class="badge badge-secondary"
                                                        >
                                                            Sin validar
                                                        </span>

                                                    </div>

                                                    <input
                                                        type="hidden"
                                                        name="control_boleta_valido"
                                                        id="control_boleta_valido_edi"
                                                        value="{{
                                                            $mostrarBoleta ? '0' : '1'
                                                        }}"
                                                        disabled
                                                    >

                                                    <input
                                                        type="hidden"
                                                        name="monto_boleta"
                                                        id="monto_boleta_edi"
                                                        value="0"
                                                        disabled
                                                    >
                                                </div>


                                                <div class="e-field">
                                                    <label>Apellidos</label>

                                                    <input
                                                        class="e-input"
                                                        name="apellido_apoderado"
                                                        id="apellido_apoderado"
                                                        value=""
                                                        autocomplete="off"
                                                        disabled
                                                    >
                                                </div>


                                                <div class="e-field">
                                                    <label>Nombres</label>

                                                    <input
                                                        class="e-input"
                                                        name="nombre_apoderado"
                                                        id="nombre_apoderado"
                                                        value=""
                                                        autocomplete="off"
                                                        disabled
                                                    >
                                                </div>


                                                <div
                                                    class="e-field fg-span2"
                                                    style="padding-bottom:0;"
                                                >
                                                    <label>Tipo de apoderado</label>

                                                    <div class="e-radio-row">

                                                        <label class="e-radio-opt">

                                                            <input
                                                                type="radio"
                                                                name="tipo"
                                                                value="d"
                                                                onchange="
                                                                    actualizarModoApoderadoTraleg()
                                                                "
                                                                disabled
                                                            >

                                                            <span>
                                                                Declaración jurada
                                                            </span>

                                                        </label>


                                                        <label class="e-radio-opt">

                                                            <input
                                                                type="radio"
                                                                name="tipo"
                                                                value="p"
                                                                onchange="
                                                                    actualizarModoApoderadoTraleg()
                                                                "
                                                                disabled
                                                            >

                                                            <span>
                                                                Poder notariado
                                                            </span>

                                                        </label>

                                                    </div>
                                                </div>

                                            </div>


                                            <div
                                                style="
                                                    display:flex;
                                                    gap:8px;
                                                    margin-top:12px;
                                                    justify-content:flex-end;
                                                "
                                            >

                                                <button
                                                    class="e-btn e-btn-ghost e-btn-sm"
                                                    type="button"
                                                    onclick="
                                                        $('#procesar_apoderado').val('0');

                                                        $('#ci_apoderado_edi').val('');

                                                        $('#apellido_apoderado').val('');

                                                        $('#nombre_apoderado').val('');

                                                        $('#control_boleta_apoderado_edi')
                                                            .val('');

                                                        $('#control_boleta_valido_edi')
                                                            .val('1');

                                                        $('#monto_boleta_edi').val('0');

                                                        $('#estado_pago_apoderado_edi')
                                                            .removeClass(
                                                                'badge-success badge-danger'
                                                            )
                                                            .addClass(
                                                                'badge-secondary'
                                                            )
                                                            .text('Sin validar');

                                                        $('#form_traleg input[name=tipo]')
                                                            .prop('checked', false);

                                                        $('#contenedor_boleta_apoderado_edi')
                                                            .hide();

                                                        $('#apellido_apoderado')
                                                            .prop('readonly', false);

                                                        $('#nombre_apoderado')
                                                            .prop('readonly', false);

                                                        $('#eleg-apo-edit :input')
                                                            .prop('disabled', true);

                                                        $('#eleg-apo-edit').hide(300);

                                                        $('#eleg-apo-view').show(300);
                                                    "
                                                >
                                                    Cancelar
                                                </button>

                                            </div>

                                        </div>

                                    @endcan

                                @endif

                            </div>
                        </div>

                    @endif


                    {{-- ===================================================== --}}
                    {{-- CAMPOS OCULTOS GENERALES                              --}}
                    {{-- ===================================================== --}}

                    <input
                        type="hidden"
                        name="ctra"
                        value="{{ $tramite->cod_tra }}"
                    >

                    <input
                        type="hidden"
                        name="ip"
                        value="{{ $tramite->id_per }}"
                    >

                    <input
                        type="hidden"
                        name="procesar_apoderado"
                        id="procesar_apoderado"
                        value="0"
                    >


                    {{-- ===================================================== --}}
                    {{-- 4. BOTÓN ÚNICO DE GUARDADO                            --}}
                    {{-- ===================================================== --}}

                    @can('editar datos traleg - srv')

                        @if($modoEdicionPersona || !$tieneApoderado)

                            <button
                                type="button"
                                class="e-btn e-btn-primary e-btn-full"
                                onclick="
                                    guardarDatos(
                                        '{{ url('g_traleg_completo') }}',
                                        'panel_traleg',
                                        'form_traleg',
                                        this
                                    )
                                "
                            >
                                <i
                                    class="fas fa-save"
                                    style="font-size:11px;"
                                ></i>

                                Guardar trámite
                            </button>

                        @endif

                    @endcan

                </form>

            </div>

            {{-- ─── COLUMNA DERECHA — documentos + formulario ─── --}}
            <div style="min-width:0;display:flex;flex-direction:column;gap:14px;">

                {{-- ── Panel documentos del trámite ── --}}
                <div class="e-panel">
                    <div class="e-panel-head">
                        <div class="e-panel-head-left">
                            <span class="ph-bar red"></span>
                            <span class="ph-title">Documentos del trámite</span>
                        </div>
                        <span style="font-size:10px;color:var(--e-s400);font-weight:600;">
                            {{ count($documentos) }} registro(s)
                        </span>
                    </div>

                    @if(Session::has('error'))
                        <div class="e-alert danger" style="margin:12px 14px 0;">
                            <i class="fas fa-exclamation-circle" style="margin-top:1px;flex-shrink:0;"></i>
                            <span>{!! session('error') !!}</span>
                            <button class="e-alert-dismiss" onclick="this.closest('.e-alert').remove();">&times;</button>
                        </div>
                    @endif

                    <div class="e-tbl-wrap">
                        <table class="e-tbl">
                            <thead>
                                <tr>
                                    <th class="td-num">#</th>
                                    @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                        <th>Sitra</th>
                                    @endif
                                    <th>Nombre</th>
                                    <th>N° trámite</th>
                                    @if($tramite->tra_tipo_tramite=='B')
                                        <th>Documentos</th>
                                    @endif
                                    @if($tramite->tra_tipo_tramite=='F')
                                        <th>Documentos</th>
                                    @else
                                        <th>N° Título</th>
                                        <th style="width:120px;">Opciones</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; ?>
                                @foreach($documentos as $d)
                                    <tr class="{{ $d->dtra_falso=='t' ? 'row-falso' : ($d->dtra_generado=='t' ? 'row-generado' : '') }}">
                                        <td class="td-num">{{ $i }}</td>

                                        @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                            <td class="center">
                                                @if($d->dtra_verificacion_sitra=='0')
                                                    <a href="#" class="e-pill ok"
                                                       data-target="#docleg" data-toggle="modal"
                                                       onclick="cargarDatos('{{ url('verificacion sitra/'.$d->cod_dtra) }}','panel_docleg')"
                                                       title="Coincide en SITRA/SID" style="text-decoration:none;">
                                                        <i class="fas fa-check-circle" style="font-size:12px;"></i>
                                                    </a>
                                                @elseif($d->dtra_verificacion_sitra=='1' || $d->dtra_verificacion_sitra=='2')
                                                    <a href="#" class="e-pill err"
                                                       data-target="#docleg" data-toggle="modal"
                                                       onclick="cargarDatos('{{ url('verificacion sitra/'.$d->cod_dtra) }}','panel_docleg')"
                                                       title="No coincide / no existe" style="text-decoration:none;">
                                                        <i class="fas fa-times-circle" style="font-size:12px;"></i>
                                                    </a>
                                                @else
                                                    <span class="e-pill idle" title="SITRA pendiente">
                                                        <i class="fas fa-minus-circle" style="font-size:12px;"></i>
                                                    </span>
                                                @endif
                                            </td>
                                        @endif

                                        <td class="td-main">
                                            {{ $d->tre_nombre }}
                                            @if($d->dtra_interno=='t')
                                                <span class="e-badge int">Int.</span>
                                            @endif
                                        </td>

                                        <td class="td-sub">{{ $d->dtra_numero_tramite . ' / ' . $d->dtra_gestion_tramite }}</td>

                                        @if($tramite->tra_tipo_tramite=='B')
                                            <td>
                                                @foreach($confrontacion as $c)
                                                    @if($c->cod_dtra==$d->cod_dtra)
                                                        <span class="td-sub" style="font-weight:600;">{{ $c->dcon_doc }}</span><br/>
                                                    @endif
                                                @endforeach
                                            </td>
                                        @endif

                                        @if($tramite->tra_tipo_tramite=='F')
                                            <td>
                                                @foreach($confrontacion as $c)
                                                    <span class="td-sub" style="font-weight:600;"><?php echo \App\Http\Controllers\ConfrontacionController::nombreDocumento($c->dcon_doc); ?></span><br/>
                                                @endforeach
                                            </td>
                                        @else
                                            <td class="td-sub" style="font-weight:600;">
                                                @if($d->dtra_numero==0)
                                                    -/{{ substr($d->dtra_gestion,-2) }}
                                                @else
                                                    {{ $d->dtra_numero }}/{{ substr($d->dtra_gestion,-2) }}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="td-actions">
                                                @if($d->dtra_generado=='t')
                                                    @can('deshacer generado glosa - srv')
                                                        <a href="#" class="e-icon-btn primary"
                                                           data-target="#docleg" data-toggle="modal"
                                                           onclick="cargarDatos('{{ url('fe_corregir_docleg/'.$d->cod_dtra) }}','panel_docleg')"
                                                           title="Corregir trámite">
                                                            <i class="fas fa-arrow-circle-left"></i>
                                                        </a>
                                                    @endcan
                                                    @if($tramite->tra_tipo_tramite!='B')
                                                        @can('imprimir legalizacion docleg - srv')
                                                            <a class="e-icon-btn"
                                                               data-target="#docleg" data-toggle="modal"
                                                               onclick="cargarDatos('{{ url('configurar impresion pdf leg/'.$d->cod_dtra) }}','panel_docleg')"
                                                               title="Ver Glosa">
                                                                <i class="fas fa-file-pdf" style="color:var(--e-red);"></i>
                                                            </a>
                                                        @endcan
                                                    @endif
                                                    <a href="#" class="e-icon-btn"
                                                       data-target="#docleg" data-toggle="modal"
                                                       onclick="cargarDatos('{{ url('ver documento pdf legalizado/'.$d->cod_dtra) }}','panel_docleg')"
                                                       title="Ver documento PDF">
                                                        <i class="fas fa-file-code" style="color:var(--e-blue);"></i>
                                                    </a>
                                                @else
                                                    @can('deshacer generado glosa - srv')
                                                        @if($tramite->tra_tipo_tramite=='L' || $tramite->tra_tipo_tramite=='C')
                                                            <a href="#traleg" class="e-icon-btn"
                                                               onclick="cargarDatos('{{ url('cambiar interno docleg/'.$d->cod_dtra) }}','panel_traleg')"
                                                               title="Cambiar destino de trámite">
                                                                @if($d->dtra_interno=='t')
                                                                    <i class="fas fa-building" style="color:var(--e-red);font-size:12px;"></i>
                                                                @else
                                                                    <i class="fas fa-globe-americas" style="color:var(--e-blue);font-size:12px;"></i>
                                                                @endif
                                                            </a>
                                                        @endif
                                                    @endcan

                                                    <a href="#" class="e-icon-btn {{ ($d->dtra_obs!='' || $d->dtra_falso=='t') ? 'del' : '' }}"
                                                       data-target="#docleg" data-toggle="modal"
                                                       onclick="cargarDatos('{{ url('obs_docleg/'.$d->cod_dtra) }}','panel_docleg')"
                                                       title="{{ ($d->dtra_obs!='' || $d->dtra_falso=='t') ? 'Ver observación' : 'Observar' }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if($d->dtra_falso!='t')
                                                        @can('generar glosa docleg - srv')
                                                            @if($tramite->tra_tipo_tramite=='B' || $d->dtra_solo_sello=='t')
                                                                <a href="#" class="e-icon-btn success"
                                                                   data-target="#docleg" data-toggle="modal"
                                                                   onclick="cargarDatos('{{ url('busqueda doc encontrado/'.$d->cod_dtra) }}','panel_docleg')"
                                                                   title="Registrar verificación">
                                                                    <i class="fas fa-file-signature"></i>
                                                                </a>
                                                            @else
                                                                <a href="#" class="e-icon-btn success"
                                                                   data-target="#docleg" data-toggle="modal"
                                                                   onclick="cargarDatos('{{ url('generar glosa_leg/'.$d->cod_dtra) }}','panel_docleg')"
                                                                   title="Generar glosa">
                                                                    <i class="fas fa-file-signature"></i>
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @if($d->dtra_tipo!='E')
                                                            <a href="#" class="e-icon-btn"
                                                               data-target="#docleg" data-toggle="modal"
                                                               onclick="cargarDatos('{{ url('ver documento pdf legalizado/'.$d->cod_dtra) }}','panel_docleg')"
                                                               title="Ver documento PDF">
                                                                <i class="fas fa-file-code" style="color:var(--e-blue);"></i>
                                                            </a>
                                                        @endif

                                                        @can('eliminar docleg - srv')
                                                            <a class="e-icon-btn del"
                                                               data-target="#docleg" data-toggle="modal"
                                                               onclick="cargarDatos('{{ url('f_eli_docleg/'.$d->cod_dtra) }}','panel_docleg')"
                                                               title="Eliminar">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </a>
                                                        @endcan
                                                    @endif
                                                @endif
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>{{-- /panel docs --}}

                {{-- ════ FORMULARIO AGREGAR DOCUMENTO ════ --}}
                @can('crear docleg - srv')

                {{-- ── BÚSQUEDA (tipo B) ── --}}
                @if($tramite->id_per != '' && $tramite->tra_tipo_tramite == 'B')
                <div class="e-panel" id="eleg-add-panel-b">
                    <div class="e-panel-head">
                        <div class="e-panel-head-left">
                            <span class="ph-bar green"></span>
                            <span class="ph-title">Añadir documento — Búsqueda</span>
                        </div>
                        <button class="e-btn e-btn-sm e-btn-ghost"
                                id="btnNuevoTra"
                                onclick="$('#divNueTram').toggle(300);">
                            <i class="fas fa-plus" style="font-size:10px;"></i> Trámite
                        </button>
                    </div>
                    <div id="divNueTram" style="display:none;">
                        <div class="e-add-body">
                            <div id="error_datos" style="display:none;margin-bottom:10px;" class="e-alert danger">
                                <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                                <span id="error_datos_span"></span>
                            </div>
                            <form id="form_docleg">
                                @csrf
                                <div class="e-add-grid g2" style="margin-bottom:10px;">
                                    <div class="e-add-col">
                                        <label>Trámite</label>
                                        <select class="e-select" data-campo="tipo-legalizacion" disabled>
                                            <option value="" selected></option>
                                            @foreach($lista_tramites as $l)
                                                @if(strtoupper((string)($l->tre_tipo ?? ''))==='R') @continue @endif
                                                <option value="{{ $l->cod_tre }}">{{ $l->tre_nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="e-add-col">
                                        <label>N° control valorado</label>
                                        <div class="e-add-row-inline">
                                            <input type="text" class="e-input" name="control" required
                                                   oninput="programarValidacionControl(this)" style="flex:1;min-width:0;">
                                            <a href="#" class="e-pill idle"
                                               data-campo="estado-pago-control-icon"
                                               data-pago-campo="control"
                                               title="Ver detalle de validación de pago"
                                               onclick="abrirDetallePagoFormulario(this); return false;"
                                               style="text-decoration:none;white-space:nowrap;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>Pendiente</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="e-add-col" style="align-items:flex-start;padding-top:2px;">
                                        <label>CUADIS</label>
                                        <span class="e-indicator">
                                            <span class="badge {{ !empty($cuadisPersona) ? 'on badge-success' : 'off badge-secondary' }}" data-campo="cuadis-indicador">
                                                {{ !empty($cuadisPersona) ? 'SI' : 'NO' }}
                                            </span>
                                            <input type="checkbox" name="cuadis" class="d-none" tabindex="-1" aria-hidden="true"
                                                   {{ !empty($cuadisPersona) ? 'checked data-cuadis-auto=1' : '' }}/>
                                        </span>
                                        <small class="d-none" data-campo="cuadis-estado"></small>
                                    </div>
                                    <div class="e-add-col">
                                        <label>Nro. Título</label>
                                        <div class="e-num-pair">
                                            <input name="numero" required class="e-input" pattern="[0-9]{1,6}" style="max-width:90px;">
                                            <span class="sep">/</span>
                                            <input name="gestion" required class="e-input" pattern="[0-9]{1,4}" placeholder="1999" style="max-width:80px;">
                                            <a href="#" class="e-pill idle" data-campo="estado-sitra-icon"
                                               title="Ver detalle SITRA"
                                               onclick="abrirModalSitraFormulario(this); return false;"
                                               style="text-decoration:none;margin-left:4px;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>SITRA</span>
                                            </a>
                                            <span class="td-sub" data-campo="sitra-fuente" style="margin-left:4px;"></span>
                                        </div>
                                    </div>
                                    <div class="e-add-col">
                                        <label>Buscar en</label>
                                        <select class="e-select" required name="buscar_en">
                                            <option value="db">DB</option>
                                            <option value="ca">CA</option>
                                            <option value="da">DA</option>
                                            <option value="tp">TP</option>
                                            <option value="di">DI</option>
                                            <option value="tpos">TPOS</option>
                                            <option value="su">SU</option>
                                        </select>
                                    </div>
                                    <div class="e-add-col fg-span2" style="grid-column:span 2;">
                                        <label>Documentos</label>
                                        <textarea name="documentos" class="e-input" required
                                                  style="height:60px;resize:vertical;padding-top:6px;"></textarea>
                                    </div>
                                </div>
                                <div data-campo="estado-sitra" class="mt-2"></div>
                                <input type="hidden" name="ctra" value="{{ $tramite->cod_tra }}">
                                <input type="hidden" name="tipo_tramite" value="t">
                                <input type="hidden" name="tipo" data-campo="tipo-legalizacion-hidden" value="">
                                <input type="hidden" name="reimpresion" data-campo="preimpreso-api" value="">
                                <input type="hidden" data-campo="validacion-recaudacion-ok" value="0">
                            </form>
                            <div style="display:flex;justify-content:flex-end;gap:8px;margin-top:10px;">
                                <a href="#" class="e-btn e-btn-primary"
                                   onclick="crearDoclegConValidacion('form_docleg','{{ url('g_docleg') }}','panel_traleg', this)">
                                    <i class="fas fa-plus" style="font-size:10px;"></i> Crear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── L / C / E / F ── --}}
                @if($tramite->id_per != '' && in_array($tramite->tra_tipo_tramite, ['L','C','E','F']))
                    @php $puedeAgregarDoc = !($tramite->tra_tipo_tramite=='F' && count($documentos)>0); @endphp
                <div class="e-panel">
                    <div class="e-panel-head">
                        <div class="e-panel-head-left">
                            <span class="ph-bar green"></span>
                            <span class="ph-title">Añadir documento</span>
                        </div>
                        @if($puedeAgregarDoc)
                            <button class="e-btn e-btn-sm e-btn-ghost"
                                    id="btnNuevoTra"
                                    onclick="$('#divNueTram').toggle(300);">
                                <i class="fas fa-plus" style="font-size:10px;"></i> Trámite
                            </button>
                        @else
                            <span style="font-size:10.5px;color:var(--e-s400);font-style:italic;">
                                Confrontación permite un solo trámite por registro.
                            </span>
                        @endif
                    </div>

                    @if($puedeAgregarDoc)
                    <div id="divNueTram" style="display:none;">
                        <div class="e-add-body">
                            <div id="error_datos" style="display:none;margin-bottom:10px;" class="e-alert danger">
                                <i class="fas fa-exclamation-circle" style="flex-shrink:0;"></i>
                                <span id="error_datos_span"></span>
                            </div>

                            @if($tramite->tra_tipo_tramite=='F')
                            {{-- Formulario Confrontación --}}
                            <form id="form_docleg_f">
                                @csrf
                                <div class="e-add-grid g2">
                                    <div class="e-add-col">
                                        <label>Tipo de legalización</label>
                                        <select class="e-select" data-campo="tipo-legalizacion" disabled>
                                            <option value="" selected></option>
                                            @foreach($lista_tramites as $l)
                                                @if(strtoupper((string)($l->tre_tipo ?? ''))==='R') @continue @endif
                                                <option value="{{ $l->cod_tre }}">{{ $l->tre_nombre }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tipo" data-campo="tipo-legalizacion-hidden" value="">
                                    </div>
                                    <div class="e-add-col">
                                        <label>Nro. Control</label>
                                        <div class="e-add-row-inline">
                                            <input class="e-input" name="control" required oninput="programarValidacionControl(this)" style="flex:1;">
                                            <a href="#" class="e-pill idle" data-campo="estado-pago-control-icon" data-pago-campo="control"
                                               title="Ver detalle de validación de pago"
                                               onclick="abrirDetallePagoFormulario(this); return false;"
                                               style="text-decoration:none;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>Pendiente</span>
                                            </a>
                                            <span style="font-size:11px;font-weight:600;color:var(--e-blue);margin:0 4px;">Reintegro:</span>
                                            <input class="e-input" name="reintegro" oninput="programarValidacionControl(this)" style="flex:1;">
                                            <a href="#" class="e-pill idle" data-campo="estado-pago-reintegro-icon" data-pago-campo="reintegro"
                                               title="Ver detalle de validación de pago"
                                               onclick="abrirDetallePagoFormulario(this); return false;"
                                               style="text-decoration:none;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>—</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="e-add-col" style="grid-column:span 2;">
                                        <label>Documentos</label>
                                        <div style="display:flex;flex-wrap:wrap;gap:8px;padding-top:2px;">
                                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;cursor:pointer;">
                                                <input type="checkbox" name="ci" value="ci" style="accent-color:var(--e-blue);"> Cédula de identidad
                                            </label>
                                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;cursor:pointer;">
                                                <input type="checkbox" name="cn" value="cn" style="accent-color:var(--e-blue);"> Cert. de nacimiento
                                            </label>
                                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;cursor:pointer;">
                                                <input type="checkbox" name="lm" value="lm" style="accent-color:var(--e-blue);"> Lib. servicio militar
                                            </label>
                                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;cursor:pointer;">
                                                <input type="checkbox" name="ce" value="ce" style="accent-color:var(--e-blue);"> Carnet extranjería
                                            </label>
                                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;cursor:pointer;">
                                                <input type="checkbox" name="pa" value="pa" style="accent-color:var(--e-blue);"> Pasaporte
                                            </label>
                                            <label style="display:flex;align-items:center;gap:5px;font-size:12px;cursor:pointer;">
                                                <input type="checkbox" name="lc" value="lc" style="accent-color:var(--e-blue);"> Libreta de colegio
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" name="ctra" value="{{ $tramite->cod_tra }}">
                                <input type="hidden" data-campo="ci-tramite" value="{{ $tramite->per_ci }}">
                                <input type="hidden" data-campo="validacion-recaudacion-ok" value="0">
                            </form>
                            <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                                <a href="#" class="e-btn e-btn-primary"
                                   onclick="crearConfrontacionConValidacion('form_docleg_f','{{ url('g_docleg') }}','panel_traleg', this)">
                                    <i class="fas fa-plus" style="font-size:10px;"></i> Crear
                                </a>
                            </div>

                            @else
                            {{-- Formulario L / C / E --}}
                            <form id="form_docleg">
                                @csrf
                                <div class="e-add-grid g2">
                                    <!-- ROW 1 -->
                                    <div class="e-add-col">
                                        <label>Tipo de trámite</label>
                                        <div class="e-radio-row" style="padding-top:4px;">
                                            <label class="e-radio-opt">
                                                <input type="radio" name="tipo_tramite" checked value="f">
                                                <span>Externo</span>
                                            </label>
                                            <label class="e-radio-opt">
                                                <input type="radio" name="tipo_tramite" value="t">
                                                <span>Interno</span>
                                            </label>
                                            <span style="color:var(--e-s300);font-weight:700;font-size:16px;">|</span>
                                            @if($tramite->tra_tipo_tramite=='L')
                                            <span class="e-indicator d-none" data-campo="ptag-wrap">
                                                PTAG: <span class="badge off" data-campo="ptag-indicador">NO</span>
                                                <input type="checkbox" name="ptaang" class="d-none" tabindex="-1" aria-hidden="true">
                                            </span>
                                            @endif
                                            <span class="e-indicator">
                                                CUADIS:
                                                <span class="badge {{ !empty($cuadisPersona) ? 'on badge-success' : 'off badge-secondary' }}" data-campo="cuadis-indicador">
                                                    {{ !empty($cuadisPersona) ? 'SI' : 'NO' }}
                                                </span>
                                                <input type="checkbox" name="cuadis" class="d-none" tabindex="-1" aria-hidden="true"
                                                       {{ !empty($cuadisPersona) ? 'checked data-cuadis-auto=1' : '' }}/>
                                            </span>
                                            <small class="d-none" data-campo="cuadis-estado"></small>
                                        </div>
                                    </div>
                                    <div class="e-add-col">
                                        <label>Tipo de legalización</label>
                                        <select class="e-select" data-campo="tipo-legalizacion" disabled>
                                            <option value="" selected></option>
                                            @foreach($lista_tramites as $l)
                                                @if(strtoupper((string)($l->tre_tipo ?? ''))==='R') @continue @endif
                                                <option value="{{ $l->cod_tre }}">{{ $l->tre_nombre }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="tipo" data-campo="tipo-legalizacion-hidden" value="">
                                    </div>

                                    <!-- ROW 2 -->
                                    <div class="e-add-col" data-campo="fila-pago-principal">
                                        <label>Nro. Control</label>
                                        <div class="e-add-row-inline">
                                            <input class="e-input" required name="control" oninput="programarValidacionControl(this)" style="flex:1;min-width:0;">
                                            <a href="#" class="e-pill idle" data-campo="estado-pago-control-icon" data-pago-campo="control"
                                               title="Ver detalle de validación de pago"
                                               onclick="abrirDetallePagoFormulario(this); return false;"
                                               style="text-decoration:none;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>Pendiente</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="e-add-col">
                                        <label>Nro. Título o Resolución</label>
                                        <div class="e-num-pair">
                                            <input name="numero" class="e-input" style="max-width:90px;">
                                            <span class="sep">/</span>
                                            <input name="gestion" required class="e-input" pattern="[0-9]{1,4}" placeholder="1999" style="max-width:80px;">
                                            @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                                <a href="#" class="e-pill idle" data-campo="estado-sitra-icon"
                                                   title="Ver detalle SITRA"
                                                   onclick="abrirModalSitraFormulario(this); return false;"
                                                   style="text-decoration:none;margin-left:4px;">
                                                    <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                    <span>SITRA</span>
                                                </a>
                                                <span class="td-sub" data-campo="sitra-fuente" style="margin-left:4px;"></span>
                                            @endif
                                            <span style="font-size:12px;font-weight:600;color:var(--e-s700);margin-left:8px;">
                                                Supletorio: <input type="checkbox" name="supletorio" style="accent-color:var(--e-blue);vertical-align:middle;" onchange="validarSitraEnFormulario(this.closest('form'))">
                                            </span>
                                        </div>
                                    </div>

                                    <!-- ROW 3 -->
                                    <div class="e-add-col" data-campo="fila-pago-complementario">
                                        <label>N° control Búsqueda</label>
                                        <div class="e-add-row-inline">
                                            <input class="e-input" name="valorado_bus" oninput="programarValidacionControl(this)" style="flex:1;min-width:0;">
                                            <a href="#" class="e-pill idle" data-campo="estado-pago-busqueda-icon" data-pago-campo="busqueda"
                                               title="Ver detalle de validación de pago"
                                               onclick="abrirDetallePagoFormulario(this); return false;"
                                               style="text-decoration:none;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>—</span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="e-add-col" data-campo="fila-pago-principal">
                                        <label>Reintegro</label>
                                        <div class="e-add-row-inline">
                                            <input class="e-input" name="reintegro" oninput="programarValidacionControl(this)" style="flex:1;min-width:0;">
                                            <a href="#" class="e-pill idle" data-campo="estado-pago-reintegro-icon" data-pago-campo="reintegro"
                                               title="Ver detalle de validación de pago"
                                               onclick="abrirDetallePagoFormulario(this); return false;"
                                               style="text-decoration:none;">
                                                <i class="fas fa-minus-circle" style="font-size:10px;"></i>
                                                <span>—</span>
                                            </a>
                                        </div>
                                    </div>

                                    <!-- HIDDEN FIELDS (Carrera, Reimpresion) -->
                                    <div class="e-add-col" data-campo="columna-carrera" style="display:none;grid-column:span 2;">
                                        <label>Carrera del interesado</label>
                                        <select class="e-select" id="select_carrera_interesado">
                                            <option value="">-- Seleccionar carrera --</option>
                                            @foreach($carreras_persona as $cp)
                                                <option value="{{ $cp->cod_tit }}" 
                                                        data-num="{{ $cp->tit_nro_titulo }}" 
                                                        data-ges="{{ $cp->tit_gestion }}">
                                                    {{ $cp->car_nombre }} ({{ $cp->tit_nro_titulo }}/{{ $cp->tit_gestion }})
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="cod_tit" id="cod_tit_seleccionado" value="">
                                    </div>
                                    <input type="hidden" name="reimpresion" data-campo="preimpreso-api" value="">
                                </div>
                                @if(!in_array($tramite->tra_tipo_tramite,['E','F']))
                                    <div data-campo="estado-sitra" class="mt-2"></div>
                                @endif
                                <input type="hidden" name="ctra" value="{{ $tramite->cod_tra }}">
                                <input type="hidden" data-campo="validacion-recaudacion-ok" value="0">
                            </form>
                            <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                                <a href="#" class="e-btn e-btn-primary"
                                   onclick="crearDoclegConValidacion('form_docleg','{{ url('g_docleg') }}','panel_traleg', this)">
                                    <i class="fas fa-plus" style="font-size:10px;"></i> Crear
                                </a>
                            </div>
                            @endif

                        </div>{{-- /add-body --}}
                    </div>
                    @endif
                </div>
                @endif

                @endcan{{-- /can crear docleg --}}

            </div>{{-- /col right --}}
        </div>{{-- /grid --}}
    </div>{{-- /body --}}

    {{-- ════ MODAL FOOTER ════ --}}
    <div class="e-modal-footer">
        <button class="e-btn e-btn-ghost" type="button" data-dismiss="modal">
            <i class="fas fa-times" style="font-size:11px;"></i> Cerrar
        </button>
    </div>

</div>{{-- /eleg --}}

{{-- ═══════════════════ JS — idéntico al original ═══════════════════ --}}
<script>
    function cargarDatosPersonales(ci){
        var link="{{url('datos_per/')}}"+"/"+encodeURIComponent((ci || '').toString().trim());
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp=="No"){
                    $('#apellido').val('');
                    $('#nombre').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido').val(res['per_apellido']);
                    $('#nombre').val(res['per_nombre']);
                }
                consultarEstadoCuadisPorCi(ci);
            },
            error: function () {
                limpiarEstadoCuadisEnFormularios();
            }
        });
    }

    function obtenerCiPrincipalTramite(){
        return ($.trim($('#form_traleg input[name="ci"]').first().val()) || '');
    }

    function actualizarIndicadorCuadis(formulario,activo){
        var indicador=$(formulario).find('[data-campo="cuadis-indicador"]');
        if(!indicador.length) return;
        if(activo){ indicador.text('SI').removeClass('off badge-secondary').addClass('on badge-success'); }
        else { indicador.text('NO').removeClass('on badge-success').addClass('off badge-secondary'); }
    }

    function actualizarIndicadorPtag(formulario,activo){
        var indicador=$(formulario).find('[data-campo="ptag-indicador"]');
        if(!indicador.length) return;
        if(activo){ indicador.text('SI').removeClass('off badge-secondary').addClass('on badge-success'); }
        else { indicador.text('NO').removeClass('on badge-success').addClass('off badge-secondary'); }
    }

    function limpiarEstadoCuadisEnFormularios(){
        $('form').find('input[name="cuadis"]').each(function(){
            var check=$(this), form=check.closest('form');
            if(check.attr('data-cuadis-auto')==='1') check.prop('checked',false);
            check.removeAttr('data-cuadis-auto');
            actualizarIndicadorCuadis(form,false);
            form.find('[data-campo="cuadis-estado"]').text('No registrado en CUADIS.').removeClass('text-success text-warning').addClass('text-muted');
            sincronizarCamposObligatorios(form);
        });
    }

    function aplicarEstadoCuadisEnFormularios(esCuadis,detalle){
        $('form').find('input[name="cuadis"]').each(function(){
            var check=$(this), form=check.closest('form');
            var estado=form.find('[data-campo="cuadis-estado"]');
            if(esCuadis){
                check.prop('checked',true).attr('data-cuadis-auto','1');
                actualizarIndicadorCuadis(form,true);
                if(estado.length){
                    var texto='CUADIS detectado';
                    if((detalle||'').toString().trim()!=='') texto+=': '+detalle;
                    estado.text(texto).removeClass('text-muted text-warning').addClass('text-success');
                }
            }else{
                if(check.attr('data-cuadis-auto')==='1') check.prop('checked',false);
                check.removeAttr('data-cuadis-auto');
                actualizarIndicadorCuadis(form,false);
                if(estado.length) estado.text('No registrado en CUADIS.').removeClass('text-success text-warning').addClass('text-muted');
            }
            sincronizarCamposObligatorios(form);
            if(esCuadis){
                abrirAutoNuevoTramiteServicios(true);
            }
        });
    }

    function consultarEstadoCuadisPorCi(ci){
        var ciLimpio=(ci||'').toString().trim();
        if(ciLimpio===''){limpiarEstadoCuadisEnFormularios();return;}
        $('form').find('[data-campo="cuadis-estado"]').text('Consultando CUADIS...').removeClass('text-muted text-success text-warning').addClass('text-info');
        $.ajax({
            url:"{{url('estado cuadis/')}}"+"/"+encodeURIComponent(ciLimpio),
            type:'GET', dataType:'json',
            success:function(resp){
                var esCuadis=!!(resp&&resp.ok&&resp.cuadis===true);
                aplicarEstadoCuadisEnFormularios(esCuadis, esCuadis?(resp.respaldo||'').toString().trim():'');
            },
            error:function(){
                $('form').find('input[name="cuadis"]').each(function(){
                    var check=$(this), form=check.closest('form');
                    if(check.attr('data-cuadis-auto')==='1') check.prop('checked',false);
                    check.removeAttr('data-cuadis-auto');
                    actualizarIndicadorCuadis(form,false);
                    form.find('[data-campo="cuadis-estado"]').text('No se pudo validar CUADIS.').removeClass('text-muted text-success text-info').addClass('text-warning');
                    sincronizarCamposObligatorios(form);
                });
            }
        });
    }

    function consultarEstadoCuadisPorPersona(idPer){
        var id=parseInt(idPer,10)||0;
        if(id<=0) return false;
        $('form').find('[data-campo="cuadis-estado"]').text('Consultando CUADIS...').removeClass('text-muted text-success text-warning').addClass('text-info');
        $.ajax({
            url:"{{url('estado cuadis persona/')}}"+"/"+id,
            type:'GET', dataType:'json',
            success:function(resp){
                var esCuadis=!!(resp&&resp.ok&&resp.cuadis===true);
                if(!esCuadis){
                    var ciFallback=obtenerCiPrincipalTramite();
                    if(ciFallback!==''){consultarEstadoCuadisPorCi(ciFallback);return;}
                }
                aplicarEstadoCuadisEnFormularios(esCuadis, esCuadis?(resp.respaldo||'').toString().trim():'');
            },
            error:function(){
                var ciFallback=obtenerCiPrincipalTramite();
                if(ciFallback!==''){consultarEstadoCuadisPorCi(ciFallback);return;}
                limpiarEstadoCuadisEnFormularios();
            }
        });
        return true;
    }

    function cargarDatosApoderado(ci){
        var link="{{url('datos_apo/')}}"+"/"+encodeURIComponent((ci||'').toString().trim());
        $.ajax({
            url:link, type:'GET',
            success:function(resp){
                if(resp=="No"){
                    if ($('#nombre_apoderado').prop('readonly')) {
                        $('#apellido_apoderado').val('');
                        $('#nombre_apoderado').val('');
                    }
                }
                else{var res=JSON.parse(resp);$('#apellido_apoderado').val(res['apo_apellido']);$('#nombre_apoderado').val(res['apo_nombre']);}
            }
        });
    }

    var verificarBoletaApoderadoEdiTimer = null;
    var verificarBoletaApoderadoEdiXHR = null;

    function actualizarModoApoderadoTraleg() {
        var tipo = $('#form_traleg input[name="tipo"]:checked').val() || '';
        if (tipo === 'p' || (tipo === 'd' && !window.GLOB_REQUIERE_BOLETA_DJ)) {
            $('#contenedor_boleta_apoderado_edi').hide();
            $('#control_boleta_apoderado_edi').val('');
            $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-secondary').text('Sin validar');
            $('#control_boleta_valido_edi').val('1'); // Permitir guardar
            
            $('#nombre_apoderado').removeAttr('readonly');
            $('#apellido_apoderado').removeAttr('readonly');
            
            var ci = ($('#ci_apoderado_edi').val()||'').toString().trim();
            if(ci !== '') {
                 cargarDatosApoderado(ci);
            }
        } else {
            $('#contenedor_boleta_apoderado_edi').show();
            $('#nombre_apoderado').prop('readonly', true).val('');
            $('#apellido_apoderado').prop('readonly', true).val('');
            $('#control_boleta_valido_edi').val('0');
            verificarBoletaApoderadoEdi();
        }
    }

    $(function(){
        actualizarModoApoderadoTraleg();
    });

    function verificarBoletaApoderadoEdi(){
        if(verificarBoletaApoderadoEdiTimer) clearTimeout(verificarBoletaApoderadoEdiTimer);

        verificarBoletaApoderadoEdiTimer = setTimeout(function(){
            var tipo = $('#form_traleg input[name="tipo"]:checked').val() || '';
            if (tipo === 'p' || (tipo === 'd' && !window.GLOB_REQUIERE_BOLETA_DJ)) {
                var ci = ($('#ci_apoderado_edi').val()||'').toString().trim();
                if(ci !== '') {
                    cargarDatosApoderado(ci);
                }
                return;
            }

            var control=($('#control_boleta_apoderado_edi').val()||'').toString().trim();
            var ci=($('#ci_apoderado_edi').val()||'').toString().trim();
            if(control===''){
                $('#nombre_apoderado').val('');
                $('#apellido_apoderado').val('');
                $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-secondary').text('Sin validar');
                $('#control_boleta_valido_edi').val('0');
                return;
            }
            if(ci===''){
                $('#nombre_apoderado').val('');
                $('#apellido_apoderado').val('');
                $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-warning').text('Complete CI');
                $('#control_boleta_valido_edi').val('0');
                return;
            }
            var link="{{ url('verificar_boleta') }}"+"/"+encodeURIComponent(control)+'?documento='+encodeURIComponent(ci)+'&modulo=servicios';
            $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-info').text('Validando...');
            $('#control_boleta_valido_edi').val('0');

            if(verificarBoletaApoderadoEdiXHR && verificarBoletaApoderadoEdiXHR.readyState !== 4){
                verificarBoletaApoderadoEdiXHR.abort();
            }

            verificarBoletaApoderadoEdiXHR = $.ajax({
                url:link,
                type:'GET',
                success:function(resp){
                    if(resp=="No" || resp===null || resp===''){
                        $('#nombre_apoderado').val('');
                        $('#apellido_apoderado').val('');
                        $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-danger').text('No encontrado');
                        $('#control_boleta_valido_edi').val('0');
                        return;
                    }
                    try{
                        var res = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                        if (res.error) {
                            $('#nombre_apoderado').val('');
                            $('#apellido_apoderado').val('');
                            $('#monto_boleta_edi').val('0');
                            $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-danger').text(res.error);
                            $('#control_boleta_valido_edi').val('0');
                        } else {
                            $('#apellido_apoderado').val(res['apellido_apoderado'] || '');
                            $('#nombre_apoderado').val(res['nombre_apoderado'] || '');
                            $('#monto_boleta_edi').val(res['monto'] || '0');
                            $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-success').text('Pago validado');
                            $('#control_boleta_valido_edi').val('1');
                        }
                    }catch(e){
                        $('#nombre_apoderado').val('');
                        $('#apellido_apoderado').val('');
                        $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-warning').text('Respuesta inválida');
                        $('#control_boleta_valido_edi').val('0');
                    }
                },
                error:function(xhr, textStatus){
                    if(textStatus === 'abort') return;
                    $('#nombre_apoderado').val('');
                    $('#apellido_apoderado').val('');
                    $('#estado_pago_apoderado_edi').removeClass().addClass('badge badge-warning').text('Error API');
                    $('#control_boleta_valido_edi').val('0');
                }
            });
        }, 500);
    }

    /* ── Funciones UX de pills de pago/SITRA ── */
    function escaparTextoHtml(texto){return String(texto||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');}
    function limpiarTextoUxServicios(texto){return(texto||'').toString().replace(/\s+/g,' ').trim();}
    function limitarTextoUxServicios(texto,maximo){var txt=limpiarTextoUxServicios(texto),max=(typeof maximo==='number'&&maximo>10)?maximo:260;return txt.length<=max?txt:txt.substring(0,max-3)+'...';}
    function normalizarClaveUxServicios(texto){return limpiarTextoUxServicios(texto).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');}

    function compactarMensajeUxServicios(mensaje,respaldo){
        var texto=(mensaje||'').toString().trim(),fallback=(respaldo||'').toString().trim();
        if(texto==='')return fallback;
        var normal=texto.toLowerCase();
        if(normal.indexOf('no esta configurado')!==-1||normal.indexOf('no esta configurada')!==-1)return 'Recaudaciones no configurado.';
        if(normal.indexOf('no se pudo conectar')!==-1||normal.indexOf('api_no_disponible')!==-1||normal.indexOf('no hay conexion')!==-1||normal.indexOf('no hay conexión')!==-1)return 'Sin conexion con recaudaciones.';
        if(normal.indexOf('no se encontro')!==-1||normal.indexOf('no se encontró')!==-1)return 'Boleta no encontrada.';
        if(normal.indexOf('ya fue utilizado')!==-1||normal.indexOf('ya fue registrada')!==-1||normal.indexOf('ya esta registrada')!==-1||normal.indexOf('ya está registrada')!==-1)return 'Boleta ya registrada.';
        if(normal.indexOf('no corresponde')!==-1)return 'Boleta no corresponde.';
        if(normal.indexOf('pendiente de validacion')!==-1||normal.indexOf('pendiente de validación')!==-1)return fallback!==''?fallback:'Pendiente.';
        if(texto.length>120)return fallback!==''?fallback:texto.substring(0,117)+'...';
        return texto;
    }
    function compactarMensajeSitraUx(mensaje,respaldo){
        var texto=(mensaje||'').toString().trim(),fallback=(respaldo||'').toString().trim();
        if(texto==='')return fallback;
        var normal=texto.toLowerCase();
        if(normal.indexOf('verificando')!==-1||normal.indexOf('validando')!==-1)return 'Validando en SITRA/SID...';
        if(normal==='sitra pendiente.'||normal==='sitra pendiente')return 'SITRA pendiente.';
        if((normal.indexOf('complete')!==-1||normal.indexOf('completar')!==-1)&&normal.indexOf('gestion')!==-1)return 'Complete gestion para validar SITRA.';
        if(normal.indexOf('seleccione')!==-1&&normal.indexOf('tipo')!==-1)return 'Seleccione tipo para validar SITRA.';
        if(normal.indexOf('no aplica')!==-1)return 'No aplica para este tipo.';
        if(normal.indexOf('no disponible')!==-1||normal.indexOf('no se pudo conectar')!==-1)return 'SITRA/SID no disponible.';
        if(normal.indexOf('no existe')!==-1||normal.indexOf('no se encontro')!==-1||normal.indexOf('no se encontró')!==-1)return 'No existe en SITRA/SID.';
        if(normal.indexOf('no coincide')!==-1)return 'Existe, pero no coincide.';
        if(normal.indexOf('coincide')!==-1)return 'Coincide en SITRA/SID.';
        if(texto.length>120)return fallback!==''?fallback:texto.substring(0,117)+'...';
        return texto;
    }
    function detectarCategoriaPagoUx(tipo,resumenOriginal,detalleOriginal){
        if(tipo==='loading')return 'loading';if(tipo==='ok')return 'ok';if(tipo==='pendiente'||tipo==='pending')return 'pending';if(tipo==='no_aplica'||tipo==='oculto')return 'na';
        var texto=normalizarClaveUxServicios((resumenOriginal||'')+' '+(detalleOriginal||''));
        if(texto.indexOf('too many')!==-1||texto.indexOf('demasiadas solicitudes')!==-1||texto.indexOf('429')!==-1||texto.indexOf('rate limit')!==-1)return 'rate_limit';
        if(texto.indexOf('no esta configurado')!==-1||texto.indexOf('sistema_no_configurado')!==-1)return 'not_configured';
        if(texto.indexOf('sin conexion')!==-1||texto.indexOf('no hay conexion')!==-1||texto.indexOf('no se pudo conectar')!==-1||texto.indexOf('api_no_disponible')!==-1||texto.indexOf('timeout')!==-1)return 'connection';
        if(texto.indexOf('ya fue utilizado')!==-1||texto.indexOf('ya fue registrada')!==-1||texto.indexOf('ya esta registrada')!==-1||texto.indexOf('no se puede usar nuevamente')!==-1)return 'used';
        if(texto.indexOf('no se encontro')!==-1||texto.indexOf('boleta no encontrada')!==-1||texto.indexOf('boleta_no_existe')!==-1)return 'not_found';
        if(texto.indexOf('no corresponde')!==-1)return 'not_match';
        if(texto.indexOf('numero repetido')!==-1||texto.indexOf('numero duplicado')!==-1)return 'duplicate';
        return 'error';
    }
    function resumenCategoriaPagoUx(categoria,resumenFallback){
        if(categoria==='ok')return 'Validado';if(categoria==='loading')return 'Validando';if(categoria==='pending')return 'Pendiente';if(categoria==='na')return 'No aplica';if(categoria==='rate_limit')return 'Demasiadas solicitudes';if(categoria==='not_configured')return 'API no configurada';if(categoria==='connection')return 'Sin conexion';if(categoria==='used')return 'Ya utilizado';if(categoria==='not_found')return 'No encontrado';if(categoria==='not_match')return 'No corresponde';if(categoria==='duplicate')return 'Numero repetido';
        return(resumenFallback||'No valido').toString();
    }
    function deduplicarDetalleConResumen(resumen,detalle){
        var resumenTxt=limpiarTextoUxServicios(resumen||''),detalleTxt=limpiarTextoUxServicios(detalle||'');
        if(detalleTxt==='')return '';
        var resumenNorm=normalizarClaveUxServicios(resumenTxt),detalleNorm=normalizarClaveUxServicios(detalleTxt);
        if(resumenNorm!==''&&(detalleNorm===resumenNorm||detalleNorm.indexOf(resumenNorm+' ')===0||detalleNorm.indexOf(resumenNorm+':')===0||detalleNorm.indexOf(resumenNorm+'.')===0)){var re=new RegExp('^'+resumenTxt.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+'[\\s:\\.-]*','i');detalleTxt=detalleTxt.replace(re,'').trim();}
        return detalleTxt;
    }

    /* ── Renderizar pill (reemplaza iconos sueltos) ── */
    function _pillConfigLeg(categoria){
        var m={ok:{cls:'ok',icon:'fa-check-circle',label:'Validado'},loading:{cls:'spin',icon:'fa-spinner fa-spin',label:'Validando…'},rate_limit:{cls:'warn',icon:'fa-clock',label:'Espere…'},used:{cls:'warn',icon:'fa-ban',label:'Ya usado'},connection:{cls:'warn',icon:'fa-plug',label:'Sin conexión'},not_configured:{cls:'idle',icon:'fa-cog',label:'No configurado'},pending:{cls:'idle',icon:'fa-minus-circle',label:'Pendiente'},na:{cls:'idle',icon:'fa-minus-circle',label:'N/A'},not_match:{cls:'warn',icon:'fa-exclamation-circle',label:'No coincide'},not_found:{cls:'warn',icon:'fa-exclamation-circle',label:'No encontrada'},duplicate:{cls:'warn',icon:'fa-exclamation-circle',label:'Duplicado'},error:{cls:'err',icon:'fa-times-circle',label:'Inválido'}};
        return m[categoria]||m['error'];
    }
    function visualizarCategoriaPagoIcono(icoEl,categoria){
        var cfg=_pillConfigLeg(categoria);
        icoEl.attr('class','e-pill '+cfg.cls);
        icoEl.html('<i class="fas '+cfg.icon+'" style="font-size:10px;"></i> <span>'+cfg.label+'</span>');
    }

    function construirDetallePagoUx(tipo,resumenCorto,resumenOriginal,detalleOriginal){
        var resumen=limpiarTextoUxServicios(resumenCorto||'Pendiente'),detalleRaw=limpiarTextoUxServicios(detalleOriginal||''),resumenRaw=limpiarTextoUxServicios(resumenOriginal||'');
        var categoria=detectarCategoriaPagoUx(tipo,resumenOriginal,detalleOriginal);
        if(tipo==='error'){if(categoria==='rate_limit'){var dr=deduplicarDetalleConResumen(resumen,detalleRaw).replace(/^detalle\s*:\s*/i,'').trim();return dr===''?'Reintentando en 15 segundos.':limitarTextoUxServicios(dr+' Reintentando en 15 segundos.',300);}var p=detalleRaw!==''?detalleRaw:resumenRaw;p=deduplicarDetalleConResumen(resumen,p).replace(/^detalle\s*:\s*/i,'').trim();return p!==''?limitarTextoUxServicios(p,300):'';}
        if(tipo==='ok'){var dok=deduplicarDetalleConResumen(resumen,detalleRaw).replace(/^detalle\s*:\s*/i,'').trim();return dok!==''&&dok.length<=180?limitarTextoUxServicios(dok,220):'';}
        var dOtro=deduplicarDetalleConResumen(resumen,detalleRaw!==''?detalleRaw:resumenRaw).replace(/^detalle\s*:\s*/i,'').trim();
        return dOtro!==''&&dOtro.toLowerCase()!==resumen.toLowerCase()?limitarTextoUxServicios(dOtro,220):'';
    }
    function construirDetalleSitraUx(resumenCorto,mensajeOriginal){
        var resumen=limpiarTextoUxServicios(resumenCorto||'SITRA pendiente.'),original=limpiarTextoUxServicios(mensajeOriginal||'');
        if(original===''||original.toLowerCase()===resumen.toLowerCase())return resumen;
        return limitarTextoUxServicios(resumen+' Detalle: '+original,280);
    }
    function construirEstadoPago(campo,etiqueta,estado,ok,resumen,detalle){return{campo:campo,etiqueta:etiqueta,estado:estado,ok:ok,resumen:resumen,detalle:(detalle||resumen||'').toString()};}
    function construirEstadoPagosBase(formulario){
        var tieneReintegroInput=formulario.find('input[name="reintegro"]').length>0,tieneBusquedaInput=formulario.find('input[name="valorado_bus"]').length>0;
        var reintegroValor=($.trim(formulario.find('input[name="reintegro"]').val())||''),busquedaValor=($.trim(formulario.find('input[name="valorado_bus"]').val())||'');
        var reintegroEstado=tieneReintegroInput?(reintegroValor!==''?construirEstadoPago('reintegro','Reintegro','pendiente',null,'Pendiente','Ingrese reintegro y valide.'):construirEstadoPago('reintegro','Reintegro','no_aplica',true,'Opcional','Sin reintegro.')):construirEstadoPago('reintegro','Reintegro','oculto',true,'No aplica','No aplica en este formulario.');
        var busquedaEstado=tieneBusquedaInput?(busquedaValor!==''?construirEstadoPago('busqueda','N° control Búsqueda','pendiente',null,'Pendiente','Ingrese control de búsqueda y valide.'):construirEstadoPago('busqueda','N° control Búsqueda','no_aplica',true,'Opcional','Sin control de búsqueda.')):construirEstadoPago('busqueda','N° control Búsqueda','oculto',true,'No aplica','No aplica en este formulario.');
        return{control:construirEstadoPago('control','Control principal','pendiente',null,'Pendiente','Ingrese control principal y valide.'),reintegro:reintegroEstado,busqueda:busquedaEstado};
    }
    function aplicarEstadoPagoIcono(formulario,campo,estado){
        var icono=formulario.find('[data-campo="estado-pago-'+campo+'-icon"]');if(!icono.length)return;
        var estadoCampo=estado||{},tipo=(estadoCampo.estado||'pendiente').toString();
        var resumenOriginal=(estadoCampo.resumen||'').toString(),detalleOriginal=(estadoCampo.detalle||'').toString();
        var categoria=detectarCategoriaPagoUx(tipo,resumenOriginal,detalleOriginal);
        var resumen=resumenCategoriaPagoUx(categoria,resumenOriginal);
        var etiqueta=(estadoCampo.etiqueta||campo||'Pago').toString();
        resumen=compactarMensajeUxServicios(resumen,resumen);
        var detalle=construirDetallePagoUx(tipo,resumen,resumenOriginal,detalleOriginal);
        visualizarCategoriaPagoIcono(icono,categoria);
        var detalleCompleto=(etiqueta+': '+resumen+'.').trim();
        if(detalle!=='') detalleCompleto+=' Detalle: '+detalle;
        icono.attr('title','Ver detalle de validación de pago').attr('aria-label',etiqueta+': '+resumen).attr('data-detalle-pago',detalleCompleto).removeAttr('data-popover-visible').popover('hide');
    }
    function aplicarEstadoPagosFormulario(formulario,estadoPagos){
        var base=construirEstadoPagosBase(formulario);
        var combinado={control:$.extend({},base.control,(estadoPagos&&estadoPagos.control)?estadoPagos.control:{}),reintegro:$.extend({},base.reintegro,(estadoPagos&&estadoPagos.reintegro)?estadoPagos.reintegro:{}),busqueda:$.extend({},base.busqueda,(estadoPagos&&estadoPagos.busqueda)?estadoPagos.busqueda:{})};
        formulario.data('estado-pagos',combinado);
        aplicarEstadoPagoIcono(formulario,'control',combinado.control);
        aplicarEstadoPagoIcono(formulario,'reintegro',combinado.reintegro);
        aplicarEstadoPagoIcono(formulario,'busqueda',combinado.busqueda);
    }
    function selectorIconosValidacion(){return '[data-campo="estado-pago-control-icon"],[data-campo="estado-pago-reintegro-icon"],[data-campo="estado-pago-busqueda-icon"],[data-campo="estado-sitra-icon"]';}
    function cerrarPopoversValidacion(excepto){$(selectorIconosValidacion()).each(function(){if(excepto&&this===excepto)return;$(this).popover('hide').removeAttr('data-popover-visible');});}
    function togglePopoverValidacion(trigger,detalle){
        var icono=$(trigger);if(!icono.length)return false;
        var visible=icono.attr('data-popover-visible')==='1';
        if(visible){icono.popover('hide').removeAttr('data-popover-visible');return false;}
        cerrarPopoversValidacion(icono.get(0));
        icono.popover('dispose').popover({container:'body',trigger:'manual',placement:'top',content:(detalle||'Sin detalle disponible').toString(),html:false}).popover('show');
        icono.attr('data-popover-visible','1');
        return false;
    }
    function abrirDetallePagoFormulario(trigger){
        var form=$(trigger).closest('form');
        var campo=(($(trigger).attr('data-pago-campo')||'').toString()||'control');
        var estadoPagos=form.data('estado-pagos')||construirEstadoPagosBase(form);
        var info=estadoPagos[campo]||construirEstadoPago(campo,'Pago','pendiente',null,'Pendiente','Sin detalle disponible.');
        var tipo=(info.estado||'pendiente').toString(),etiqueta=(info.etiqueta||'Pago').toString();
        var resumenOriginal=(info.resumen||'').toString(),detalleOriginal=(info.detalle||'').toString();
        var categoria=detectarCategoriaPagoUx(tipo,resumenOriginal,detalleOriginal);
        var resumen=compactarMensajeUxServicios(resumenCategoriaPagoUx(categoria,resumenOriginal),resumenCategoriaPagoUx(categoria,resumenOriginal));
        var detalle=construirDetallePagoUx(tipo,resumen,resumenOriginal,detalleOriginal);
        var contenido=(etiqueta+': '+resumen+'.').trim();
        if(detalle!=='')contenido+=' Detalle: '+detalle;
        return togglePopoverValidacion(trigger,contenido);
    }
    function limpiarReintentoValidacionControl(formulario){var timer=formulario.data('retry-control-timer');if(timer){clearTimeout(timer);formulario.removeData('retry-control-timer');}}
    function mensajeEsRateLimit(texto,statusCode){if(parseInt(statusCode,10)===429)return true;var normal=normalizarClaveUxServicios(texto||'');return normal.indexOf('too many')!==-1||normal.indexOf('demasiadas solicitudes')!==-1||normal.indexOf('429')!==-1||normal.indexOf('rate limit')!==-1;}
    function construirEstadoRateLimit(base){return{control:construirEstadoPago('control','Control principal','error',false,'Demasiadas solicitudes','El sistema esta recibiendo muchas solicitudes. Reintentando en 15 segundos.'),reintegro:base.reintegro,busqueda:base.busqueda};}
    function programarReintentoValidacionControl(formulario,inputControl,control,reintegro,busqueda){
        limpiarReintentoValidacionControl(formulario);
        var timer=setTimeout(function(){if(($.trim(formulario.find('input[name="control"]').val())||'')!==control)return;if(($.trim(formulario.find('input[name="reintegro"]').val())||'')!==reintegro)return;if(($.trim(formulario.find('input[name="valorado_bus"]').val())||'')!==busqueda)return;if(control==='')return;validarControlRecaudaciones(inputControl);},15000);
        formulario.data('retry-control-timer',timer);
    }

    function validarControlRecaudaciones(inputControl){
        var formulario=$(inputControl).closest('form');
        sincronizarCamposObligatorios(formulario);
        var control=($.trim(formulario.find('input[name="control"]').val())||'');
        var reintegro=($.trim(formulario.find('input[name="reintegro"]').val())||'');
        var valoradoBusqueda=($.trim(formulario.find('input[name="valorado_bus"]').val())||'');
        limpiarReintentoValidacionControl(formulario);
        formulario.data('connection-retry-count',0);
        var secuencia=((formulario.data('validacion-control-seq')||0)+1);
        formulario.data('validacion-control-seq',secuencia);
        var okInput=formulario.find('[data-campo="validacion-recaudacion-ok"]');
        var estadoBase=construirEstadoPagosBase(formulario);
        okInput.val('0');

        if(!control){
            limpiarTipoLegalizacion(formulario);limpiarPtagSugerido(formulario);
            formulario.find('input[data-campo="preimpreso-api"]').val('');formulario.find('input[data-campo="gestion-api"]').val('');
            limpiarSitraFormulario(formulario);actualizarEstadoSitra(formulario,'text-muted','SITRA pendiente.');
            formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');
            aplicarEstadoPagosFormulario(formulario,estadoBase);return;
        }

        if(reintegro!==''&&control===reintegro){estadoBase.reintegro=construirEstadoPago('reintegro','Reintegro','error',false,'Numero repetido','Debe ser distinto del control principal.');limpiarTipoLegalizacion(formulario);formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');aplicarEstadoPagosFormulario(formulario,estadoBase);return;}
        if(valoradoBusqueda!==''&&control===valoradoBusqueda){estadoBase.busqueda=construirEstadoPago('busqueda','N° control Búsqueda','error',false,'Numero repetido','Debe ser distinto del control principal.');limpiarTipoLegalizacion(formulario);formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');aplicarEstadoPagosFormulario(formulario,estadoBase);return;}
        if(reintegro!==''&&valoradoBusqueda!==''&&reintegro===valoradoBusqueda){estadoBase.busqueda=construirEstadoPago('busqueda','N° control Búsqueda','error',false,'Numero repetido','Debe ser distinto del reintegro.');limpiarTipoLegalizacion(formulario);formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');aplicarEstadoPagosFormulario(formulario,estadoBase);return;}

        var estadoLoading={control:construirEstadoPago('control','Control principal','loading',null,'Validando','Validando control principal...'),reintegro:estadoBase.reintegro,busqueda:estadoBase.busqueda};
        if(reintegro!=='')estadoLoading.reintegro=construirEstadoPago('reintegro','Reintegro','loading',null,'Validando','Validando reintegro...');
        if(valoradoBusqueda!=='')estadoLoading.busqueda=construirEstadoPago('busqueda','N° control Búsqueda','loading',null,'Validando','Validando control de busqueda...');
        aplicarEstadoPagosFormulario(formulario,estadoLoading);

        $.ajax({
            url:"{{url('validar valorado recaudaciones/'.$tramite->cod_tra)}}",type:'POST',
            data:{_token:formulario.find('input[name="_token"]').val(),control:control,reintegro:reintegro,valorado_bus:valoradoBusqueda,reimpresion:formulario.find('input[name="reimpresion"]').val()||''},
            success:function(resp){
                if((formulario.data('validacion-control-seq')||0)!==secuencia)return;
                if(($.trim(formulario.find('input[name="control"]').val())||'')!==control)return;
                formulario.data('connection-retry-count',0);
                if(!resp.ok){
                    var textoRate='';
                    if(resp&&resp.message)textoRate=String(resp.message);
                    if(textoRate===''&&resp&&resp.estado_pagos&&resp.estado_pagos.control)textoRate=((resp.estado_pagos.control.resumen||'')+' '+(resp.estado_pagos.control.detalle||'')).toString();
                    if(mensajeEsRateLimit(textoRate,resp&&resp.status?resp.status:0)){aplicarEstadoPagosFormulario(formulario,construirEstadoRateLimit(estadoBase));limpiarTipoLegalizacion(formulario);limpiarPtagSugerido(formulario);formulario.find('input[data-campo="preimpreso-api"]').val('');formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');limpiarSitraFormulario(formulario);actualizarEstadoSitra(formulario,'text-muted','SITRA pendiente.');programarReintentoValidacionControl(formulario,inputControl,control,reintegro,valoradoBusqueda);return;}
                    okInput.val('0');limpiarTipoLegalizacion(formulario);aplicarPtagSugerido(formulario,resp);formulario.find('input[data-campo="preimpreso-api"]').val('');limpiarSitraFormulario(formulario);actualizarEstadoSitra(formulario,'text-muted','SITRA pendiente.');formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');aplicarEstadoPagosFormulario(formulario,resp.estado_pagos||estadoBase);return;
                }
                aplicarTiposPermitidosPorMonto(formulario,resp);autoseleccionarTipoLegalizacion(formulario,resp);sincronizarTipoLegalizacion(formulario);aplicarPtagSugerido(formulario,resp);
                formulario.find('input[data-campo="preimpreso-api"]').val(resp.preimpreso||'');okInput.val('1');aplicarEstadoPagosFormulario(formulario,resp.estado_pagos||estadoBase);
                formulario.data('control-validado-ok',1).data('control-validado-valor',control).data('reintegro-validado-valor',reintegro).data('busqueda-validado-valor',valoradoBusqueda);
                programarValidacionSitra(formulario);
            },
            error:function(xhr){
                if((formulario.data('validacion-control-seq')||0)!==secuencia)return;
                var mensajeError=(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'';
                var esConexion=(xhr.status===0||xhr.status===502||xhr.status===503||xhr.status===504)||mensajeError.toLowerCase().indexOf('sin conexión')!==-1||mensajeError.toLowerCase().indexOf('api_no_disponible')!==-1;
                var connectionRetries=(formulario.data('connection-retry-count')||0);
                
                if(esConexion&&connectionRetries<3){
                    formulario.data('connection-retry-count',connectionRetries+1);
                    var estadoReintento={control:construirEstadoPago('control','Control principal','warn',false,'Sin conexión','Reintentando ('+( connectionRetries+1)+'/3)...'),reintegro:estadoBase.reintegro,busqueda:estadoBase.busqueda};
                    aplicarEstadoPagosFormulario(formulario,estadoReintento);
                    limpiarReintentoValidacionControl(formulario);
                    var retryTimer=setTimeout(function(){validarControlRecaudaciones(inputControl);},3000);
                    formulario.data('retry-control-timer',retryTimer);
                    return;
                }
                
                if(mensajeEsRateLimit(mensajeError,xhr.status)){aplicarEstadoPagosFormulario(formulario,construirEstadoRateLimit(estadoBase));limpiarTipoLegalizacion(formulario);limpiarPtagSugerido(formulario);formulario.find('input[data-campo="preimpreso-api"]').val('');formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');limpiarSitraFormulario(formulario);actualizarEstadoSitra(formulario,'text-muted','SITRA pendiente.');programarReintentoValidacionControl(formulario,inputControl,control,reintegro,valoradoBusqueda);return;}
                var respError=xhr.responseJSON||null;okInput.val('0');limpiarTipoLegalizacion(formulario);aplicarPtagSugerido(formulario,respError);formulario.find('input[data-campo="preimpreso-api"]').val('');limpiarSitraFormulario(formulario);actualizarEstadoSitra(formulario,'text-muted','SITRA pendiente.');formulario.removeData('control-validado-ok').removeData('control-validado-valor').removeData('reintegro-validado-valor').removeData('busqueda-validado-valor');
                aplicarEstadoPagosFormulario(formulario,(respError&&respError.estado_pagos)?respError.estado_pagos:estadoBase);
                programarValidacionSitra(formulario);
            }
        });
    }

    function crearDoclegConValidacion(formulario,ruta,panel,btn){
        var form=$('#'+formulario);sincronizarCamposObligatorios(form);
        var cuadis=form.find('input[name="cuadis"]').is(':checked'),validado=form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';
        if(!cuadis&&!validado){$('#error_datos_span').html('Valide control primero.');$('#error_datos').show();setTimeout(function(){$('#error_datos').hide(500);},4000);return;}
        var tipoSeleccionado=(form.find('input[data-campo="tipo-legalizacion-hidden"]').val()||'').toString().trim();
        if(tipoSeleccionado===''){$('#error_datos_span').html('Seleccione tipo para continuar.');$('#error_datos').show();setTimeout(function(){$('#error_datos').hide(500);},4000);return;}
        
        var sitraElement = form.find('[data-campo="estado-sitra"]');
        var tieneSitra = sitraElement.length > 0 && sitraElement.is(':visible');
        if (tieneSitra) {
            var estadoSitra = form.data('sitra-estado');
            if (estadoSitra !== '0' && estadoSitra !== 'no-aplica') {
                $('#error_datos_span').html('SITRA/SID debe estar validado como correcto.');
                $('#error_datos').show();
                setTimeout(function(){$('#error_datos').hide(500);},4000);
                return;
            }
        }
        
        enviar1(formulario,ruta,panel,btn);
    }
    function crearConfrontacionConValidacion(formulario,ruta,panel,btn){
        var form=$('#'+formulario);sincronizarCamposObligatorios(form);
        var validado=form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';
        if(!validado){$('#error_datos_span').html('Valide control primero.');$('#error_datos').show();setTimeout(function(){$('#error_datos').hide(500);},4000);return;}
        var tipoSeleccionado=(form.find('input[data-campo="tipo-legalizacion-hidden"]').val()||'').toString().trim();
        if(tipoSeleccionado===''){$('#error_datos_span').html('Seleccione tipo para continuar.');$('#error_datos').show();setTimeout(function(){$('#error_datos').hide(500);},4000);return;}
        enviar1(formulario,ruta,panel,btn);
    }

    function actualizarEstadoSitra(formulario,clase,mensaje){
        var icono=formulario.find('[data-campo="estado-sitra-icon"]');if(!icono.length)return;
        var resumen=compactarMensajeSitraUx(mensaje,'SITRA pendiente.'),detalle=construirDetalleSitraUx(resumen,mensaje);
        var detalleLower=resumen.toLowerCase(),estadoSitra='pending';
        if(clase==='text-success')estadoSitra='ok';else if(clase==='text-danger')estadoSitra='error';else if(detalleLower.indexOf('verificando')!==-1||detalleLower.indexOf('validando')!==-1)estadoSitra='loading';
        var cfg=_pillConfigLeg(estadoSitra==='ok'?'ok':estadoSitra==='error'?'error':estadoSitra==='loading'?'loading':'pending');
        
        var fuente = String(formulario.data('sitra-fuente')||'sitra').toLowerCase();
        var textoBadge = 'SITRA';
        if(fuente === 'sid') textoBadge = 'SID';
        
        icono.attr('class','e-pill '+cfg.cls).html('<i class="fas '+cfg.icon+'" style="font-size:10px;"></i> <span>' + textoBadge + '</span>');
        icono.attr('title','Ver detalle de validación ' + textoBadge).attr('aria-label',resumen).attr('data-detalle-sitra',detalle).removeAttr('data-popover-visible').popover('hide');
    }
    function limpiarSitraFormulario(form){form.removeData('sitra-response').removeData('sitra-estado').removeData('sitra-fuente');form.find('[data-campo="sitra-fuente"]').text('');}
    function actualizarFuenteSitra(form,fuente){
        var etiqueta=form.find('[data-campo="sitra-fuente"]');if(!etiqueta.length)return;
        etiqueta.text(''); // Limpiamos la etiqueta externa para evitar confusión, ya que el icono ahora muestra la fuente.
    }
    function abrirModalSitraFormulario(trigger){
        var form=$(trigger).closest('form');
        var resp=form.data('sitra-response')||null,estado=(form.data('sitra-estado')||'').toString(),fuente=(form.data('sitra-fuente')||'').toString().toLowerCase();
        var detalle=(($(trigger).attr('data-detalle-sitra')||'').toString()||'').trim();
        if(detalle===''){if(estado==='0')detalle='Coincide en SITRA/SID.';else if(estado==='1')detalle='Existe, pero no coincide.';else if(estado==='2')detalle='No existe en SITRA/SID.';else detalle='SITRA pendiente.';}
        if(fuente==='sitra_sid'&&detalle.toLowerCase().indexOf('pendiente')!==-1){detalle='No existe en SITRA/SID.';}
        if(resp&&estado==='0'){var extra=[];if(resp.numero)extra.push('Nro: '+resp.numero);if(resp.gestion)extra.push('Gestión: '+resp.gestion);if(resp.tipo)extra.push('Tipo: '+resp.tipo);if(resp.fecha_impresion)extra.push('Impresión: '+resp.fecha_impresion);if(extra.length)detalle+=' '+extra.join(' | ');}
        if(resp&&estado==='1'){var extra=[];if(resp.numero)extra.push('Nro: '+resp.numero);if(resp.gestion)extra.push('Gestión: '+resp.gestion);if(resp.fecha_impresion)extra.push('Impresión: '+resp.fecha_impresion);if(extra.length)detalle+=' '+extra.join(' | ');}
        if(fuente==='sid')detalle+=' Fuente: SID.';else if(fuente==='sitra_sid')detalle+=' Fuente: SITRA y SID.';
        return togglePopoverValidacion(trigger,detalle);
    }

    function validarSitraEnFormulario(formulario){
        var form=$(formulario);if(!form.length)return;
        if(!form.find('[data-campo="estado-sitra"]').length)return;
        var numero=(form.find('input[name="numero"]').val()||'').trim(),gestion=(form.find('input[name="gestion"]').val()||'').trim();
        var codTipo=(form.find('input[data-campo="tipo-legalizacion-hidden"]').val()||'').trim(),buscarEn=(form.find('select[name="buscar_en"]').val()||'').trim();
        var supletorioFlag = form.find('input[name="supletorio"]').is(':checked') ? '1' : '0';
        var secuencia=((form.data('sitra-req-seq')||0)+1);form.data('sitra-req-seq',secuencia);
        form.find('[data-campo="fuente-sitra"]').val('');
        if(numero===''||numero==='-'){limpiarSitraFormulario(form);form.data('sitra-estado','pendiente');actualizarEstadoSitra(form,'text-muted','SITRA pendiente.');return;}
        if(form.find('input[name="gestion"]').length){
            if(gestion===''){limpiarSitraFormulario(form);form.data('sitra-estado','pendiente');actualizarEstadoSitra(form,'text-muted','Complete gestion para validar SITRA.');return;}
            var valGestion=parseInt(gestion,10);
            if(isNaN(valGestion)||valGestion<1832){limpiarSitraFormulario(form);form.data('sitra-estado','2');actualizarEstadoSitra(form,'text-danger','No existe en SITRA/SID.');return;}
        }
        if(codTipo===''&&buscarEn===''){limpiarSitraFormulario(form);form.data('sitra-estado','pendiente');actualizarEstadoSitra(form,'text-muted','Seleccione tipo para validar SITRA.');return;}
        actualizarEstadoSitra(form,'text-muted','Validando en SITRA/SID...');
        $.ajax({
            url:"{{url('validar sitra legalizacion/'.$tramite->cod_tra)}}",type:'POST',
            data:{_token:form.find('input[name="_token"]').val(),numero:numero,gestion:gestion,tipo:codTipo,buscar_en:buscarEn,supletorio:supletorioFlag},
            success:function(resp){
                if((form.data('sitra-req-seq')||0)!==secuencia)return;
                if(!resp||resp.aplica===false){limpiarSitraFormulario(form);form.data('sitra-estado','no-aplica');actualizarEstadoSitra(form,'text-muted',resp&&resp.message?resp.message:'No aplica para este tipo.');return;}
                var estadoResp=(resp&&resp.estado!==undefined&&resp.estado!==null)?String(resp.estado).trim():'';
                var fuenteResp=(resp&&resp.fuente)?String(resp.fuente).toLowerCase():'sitra';
                var mensajeResp=(resp&&resp.message)?String(resp.message).toLowerCase():'';
                if((estadoResp===''||estadoResp==='null'||estadoResp==='undefined')&&fuenteResp==='sitra_sid')estadoResp='2';
                if((estadoResp===''||estadoResp==='null'||estadoResp==='undefined')&&mensajeResp.indexOf('no existe')!==-1)estadoResp='2';
                if((estadoResp===''||estadoResp==='null'||estadoResp==='undefined')&&mensajeResp.indexOf('no coincide')!==-1)estadoResp='1';
                form.data('sitra-response',resp).data('sitra-estado',estadoResp).data('sitra-fuente',fuenteResp);
                
                // VALIDAR AÑO DE FECHA DE IMPRESIÓN
                if(resp && resp.fecha_impresion && estadoResp==='0'){
                    var partesFecha = resp.fecha_impresion.split('/'); // "20/02/2024" → ["20", "02", "2024"]
                    var annoImpresion = partesFecha[2]; // Extraer 2024
                    var annoIngresado = gestion; // El año que ingresó el usuario
                    
                    // Comparar años
                    if(annoImpresion !== annoIngresado){
                        form.data('sitra-estado','1'); // Marcar como no coincide
                        estadoResp='1';
                    }
                }
                
                actualizarFuenteSitra(form,fuenteResp);
                if(estadoResp==='0')actualizarEstadoSitra(form,'text-success','Coincide en SITRA/SID.');
                else if(estadoResp==='2')actualizarEstadoSitra(form,'text-danger','No existe en SITRA/SID.');
                else if(estadoResp==='1'){
                    // Mensajes específicos para no coincidencia
                    var mensajeError='Existe, pero no coincide.';
                    if(resp && resp.fecha_impresion){
                        var partesFecha = resp.fecha_impresion.split('/');
                        var annoImpresion = partesFecha[2];
                        var annoIngresado = gestion;
                        if(annoImpresion !== annoIngresado){
                            mensajeError='Año de impresión no coincide. Documento: '+annoImpresion+', ingresado: '+annoIngresado;
                        }
                    }
                    actualizarEstadoSitra(form,'text-danger',mensajeError);
                }
                else actualizarEstadoSitra(form,'text-muted',resp.message||'Sin datos para validar.');
            },
            error:function(xhr){
                if((form.data('sitra-req-seq')||0)!==secuencia)return;
                limpiarSitraFormulario(form);
                actualizarEstadoSitra(form,'text-danger',(xhr.responseJSON&&xhr.responseJSON.message)?xhr.responseJSON.message:'SITRA/SID no disponible.');
            }
        });
    }
    function programarValidacionSitra(formulario){
        var form=$(formulario);if(!form.length)return;
        var timer=form.data('timer-sitra');if(timer)clearTimeout(timer);
        timer=setTimeout(function(){validarSitraEnFormulario(form);},400);
        form.data('timer-sitra',timer);
    }

    function normalizarTexto(texto){if(!texto)return '';return texto.toString().toUpperCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'').replace(/\s+/g,' ').trim();}
    function obtenerTiposPermitidosDesdeRespuesta(resp){if(!resp||!Array.isArray(resp.tipos_permitidos))return [];return resp.tipos_permitidos.filter(function(item){return item&&item.cod_tre!==undefined&&item.cod_tre!==null&&String(item.cod_tre)!==''});}
    function prepararOpcionesTipoLegalizacion(formulario){var select=formulario.find('select[data-campo="tipo-legalizacion"]');if(!select.length||select.data('tipos-preparados')===1)return;select.find('option').each(function(){$(this).attr('data-visible-original','1');});select.data('tipos-preparados',1);}
    function restaurarOpcionesTipoLegalizacion(formulario){var select=formulario.find('select[data-campo="tipo-legalizacion"]');if(!select.length)return;prepararOpcionesTipoLegalizacion(formulario);select.find('option').each(function(){$(this).prop('disabled',false).show();});}
    function aplicarTiposPermitidosPorMonto(formulario,resp){
        var select=formulario.find('select[data-campo="tipo-legalizacion"]');if(!select.length)return;
        if(!(resp&&resp.aplicar_filtro_por_monto)){restaurarOpcionesTipoLegalizacion(formulario);var ca=(resp&&resp.tipo_legalizacion_sugerido)?String(resp.tipo_legalizacion_sugerido):'';if(ca!==''&&select.find('option[value="'+ca+'"]').length){select.val(ca);select.prop('disabled',true);}else select.prop('disabled',false);sincronizarTipoLegalizacion(formulario);return;}
        prepararOpcionesTipoLegalizacion(formulario);
        var permitidos=obtenerTiposPermitidosDesdeRespuesta(resp);
        if(permitidos.length===0){restaurarOpcionesTipoLegalizacion(formulario);select.val('').prop('disabled',true);sincronizarTipoLegalizacion(formulario);return;}
        var mapaPermitidos={};permitidos.forEach(function(item){mapaPermitidos[String(item.cod_tre)]=item;});
        select.find('option').each(function(){var opcion=$(this),valor=(opcion.val()||'').toString();if(valor===''){opcion.prop('disabled',false).show();return;}if(mapaPermitidos[valor])opcion.prop('disabled',false).show();else opcion.prop('disabled',true).hide();});
        var seleccionActual=(select.val()||'').toString();
        if(permitidos.length===1)select.val(String(permitidos[0].cod_tre));else if(seleccionActual===''||!mapaPermitidos[seleccionActual])select.val('');
        select.prop('disabled',false);sincronizarTipoLegalizacion(formulario);
    }
    function autoseleccionarTipoLegalizacion(formulario,resp){
        var select=formulario.find('select[data-campo="tipo-legalizacion"]');if(!select.length)return;
        var permitidos=obtenerTiposPermitidosDesdeRespuesta(resp);
        if(permitidos.length>1||(resp&&resp.requiere_seleccion_manual))return;
        if(permitidos.length===1){select.val(String(permitidos[0].cod_tre));return;}
        var codigo=(resp&&resp.tipo_legalizacion_sugerido)?String(resp.tipo_legalizacion_sugerido):'';
        if(codigo!==''&&select.find('option[value="'+codigo+'"]').length){select.val(codigo);return;}
        var nombreSugerido=normalizarTexto(resp&&resp.nombre_tipo_legalizacion_sugerido?resp.nombre_tipo_legalizacion_sugerido:'');
        var cuentaApi=normalizarTexto(resp&&resp.cuenta?resp.cuenta:'');
        if(nombreSugerido===''&&cuentaApi==='')return;
        var encontrado=false;
        select.find('option').each(function(){var texto=normalizarTexto($(this).text());if((nombreSugerido!==''&&(texto===nombreSugerido||texto.indexOf(nombreSugerido)!==-1||nombreSugerido.indexOf(texto)!==-1))||(cuentaApi!==''&&(texto===cuentaApi||texto.indexOf(cuentaApi)!==-1||cuentaApi.indexOf(texto)!==-1))){select.val($(this).val());encontrado=true;return false;}});
        if(!encontrado)select.val('');
    }
    function sincronizarCamposObligatorios(formulario){
        var form=$(formulario);if(!form.length)return;
        var esBusqueda=form.find('select[name="buscar_en"]').length>0||form.find('textarea[name="documentos"]').length>0;
        var esCuadis=form.find('input[name="cuadis"]').is(':checked');
        form.find('input[name="control"]').prop('required',!esCuadis);form.find('input[name="gestion"]').prop('required',true);form.find('input[name="numero"]').prop('required',esBusqueda);form.find('input[name="reintegro"]').prop('required',false);form.find('select[name="buscar_en"]').prop('required',esBusqueda);form.find('textarea[name="documentos"]').prop('required',esBusqueda);
        actualizarVisibilidadPagoCuadis(form,esCuadis);actualizarSelectorTipoSegunCuadis(form);
    }
    function abrirDropdownTipoLegalizacion(formulario){
        var form=$(formulario);if(!form.length)return;
        var select=form.find('select[data-campo="tipo-legalizacion"]').first();if(!select.length)return;
        if(select.prop('disabled')||select.prop('readonly'))return;
        select.trigger('focus');
        try{select.trigger('mousedown');}catch(e){}
        setTimeout(function(){
            try{select.trigger($.Event('keydown',{key:'ArrowDown',keyCode:40,which:40}));}catch(e){}
        },30);
    }
    function actualizarVisibilidadPagoCuadis(formulario,esCuadis){
        var form=$(formulario);if(!form.length)return;
        var filasPrincipal=form.find('[data-campo="fila-pago-principal"]'),filasComplementarias=form.find('[data-campo="fila-pago-complementario"]');
        if(esCuadis){filasPrincipal.hide();filasComplementarias.hide();form.find('input[name="control"]').val('');form.find('input[name="reintegro"]').val('');form.find('input[name="valorado_bus"]').val('');form.find('input[name="reimpresion"]').val('');form.find('input[data-campo="preimpreso-api"]').val('');form.find('input[data-campo="validacion-recaudacion-ok"]').val('0');limpiarPtagSugerido(form);return;}
        filasPrincipal.show();filasComplementarias.show();
    }
    function actualizarSelectorTipoSegunCuadis(formulario){
        var form=$(formulario);if(!form.length)return;
        var select=form.find('select[data-campo="tipo-legalizacion"]');if(!select.length)return;
        var cuadis=form.find('input[name="cuadis"]').is(':checked'),validado=form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';
        if(cuadis){restaurarOpcionesTipoLegalizacion(form);select.prop('disabled',false);sincronizarTipoLegalizacion(form);return;}
        if(!validado)limpiarTipoLegalizacion(form);
    }
    function sincronizarTipoLegalizacion(formulario){
        var select=formulario.find('select[data-campo="tipo-legalizacion"]');
        if(select.length){
            var opcionSeleccionada=select.find('option:selected'),valorSeleccionado='';
            var textoSeleccionado='';
            if(opcionSeleccionada.length&&!opcionSeleccionada.prop('disabled')) {
                valorSeleccionado=opcionSeleccionada.val()||'';
                textoSeleccionado=opcionSeleccionada.text()||'';
            }
            select.val(valorSeleccionado);
            formulario.find('input[data-campo="tipo-legalizacion-hidden"]').val(valorSeleccionado);
            
            formulario.find('[data-campo="columna-carrera"]').hide(300);
            formulario.find('#select_carrera_interesado').val('');
            formulario.find('#cod_tit_seleccionado').val('');
            formulario.find('input[name="numero"]').prop('readonly', false).removeClass('readonly');
            formulario.find('input[name="gestion"]').prop('readonly', false).removeClass('readonly');

            var textoMinusculas = textoSeleccionado.toLowerCase();
            var esExtranjero = (textoMinusculas.indexOf('extrajero') !== -1 || textoMinusculas.indexOf('extranjero') !== -1);
            if(esExtranjero) {
                formulario.find('[data-campo="estado-sitra-icon"]').hide();
                formulario.find('[data-campo="sitra-fuente"]').hide();
                formulario.find('[data-campo="estado-sitra"]').hide();
            } else {
                formulario.find('[data-campo="estado-sitra-icon"]').show();
                formulario.find('[data-campo="sitra-fuente"]').show();
                formulario.find('[data-campo="estado-sitra"]').show();
            }
        }
    }
    function limpiarTipoLegalizacion(formulario){
        var select=formulario.find('select[data-campo="tipo-legalizacion"]');
        if(select.length){restaurarOpcionesTipoLegalizacion(formulario);select.val('').prop('disabled',true);}
        formulario.find('input[data-campo="tipo-legalizacion-hidden"]').val('');
        
        formulario.find('[data-campo="columna-carrera"]').hide(300);
        formulario.find('#select_carrera_interesado').val('');
        formulario.find('#cod_tit_seleccionado').val('');
        formulario.find('input[name="numero"]').prop('readonly', false).removeClass('readonly');
        formulario.find('input[name="gestion"]').prop('readonly', false).removeClass('readonly');

        formulario.find('[data-campo="estado-sitra-icon"]').show();
        formulario.find('[data-campo="sitra-fuente"]').show();
        formulario.find('[data-campo="estado-sitra"]').show();
    }
    function aplicarPtagSugerido(formulario,resp){
        var check=formulario.find('input[name="ptaang"]'),wrap=formulario.find('[data-campo="ptag-wrap"]');if(!check.length)return;
        var sugerido=!!(resp&&resp.ptag_auto);
        if(sugerido){wrap.removeClass('d-none');check.prop('checked',true).attr('data-ptag-lock','1').attr('title','PTAG detectado desde la cuenta de recaudación');actualizarIndicadorPtag(formulario,true);return;}
        check.prop('checked',false).removeAttr('data-ptag-lock').removeAttr('title');actualizarIndicadorPtag(formulario,false);wrap.addClass('d-none');
    }
    function limpiarPtagSugerido(formulario){
        var check=formulario.find('input[name="ptaang"]'),wrap=formulario.find('[data-campo="ptag-wrap"]');if(!check.length)return;
        check.removeAttr('data-ptag-lock').removeAttr('title').prop('checked',false);actualizarIndicadorPtag(formulario,false);wrap.addClass('d-none');
    }
    function programarValidacionControl(inputControl){
        var form=$(inputControl).closest('form');if(!form.length)return;
        limpiarReintentoValidacionControl(form);
        var control=($.trim(form.find('input[name="control"]').val())||''),reintegro=($.trim(form.find('input[name="reintegro"]').val())||''),valoradoBusqueda=($.trim(form.find('input[name="valorado_bus"]').val())||'');
        var controlOk=form.data('control-validado-ok')===1,controlPrevio=(form.data('control-validado-valor')||'').toString();
        var reintegroPrevio=(form.data('reintegro-validado-valor')||'').toString(),busquedaPrevia=(form.data('busqueda-validado-valor')||'').toString();
        if(control!==''&&controlOk&&controlPrevio===control&&reintegroPrevio===reintegro&&busquedaPrevia===valoradoBusqueda)return;
        if(controlPrevio!==''&&control!==controlPrevio)form.find('input[name="reimpresion"]').val('');
        var timer=form.data('timer-control');if(timer)clearTimeout(timer);
        if(control===''){validarControlRecaudaciones(inputControl);return;}
        timer=setTimeout(function(){validarControlRecaudaciones(inputControl);},350);
        form.data('timer-control',timer);
    }

    function enfocarCampoInicialNuevoTramiteServicios(contenedor,preferirTipo){
        var scope=$(contenedor);
        if(!scope.length)return;
        var formulario=scope.find('form#form_docleg,form#form_docleg_f').first();
        if(!formulario.length)return;
        var esCuadis=formulario.find('input[name="cuadis"]').is(':checked');
        var campoTipo=formulario.find('select[data-campo="tipo-legalizacion"]').first();
        var campoControl=formulario.find('input[name="control"]').first();

        if((preferirTipo||esCuadis)&&campoTipo.length&&!campoTipo.prop('disabled')&&!campoTipo.prop('readonly')){
            abrirDropdownTipoLegalizacion(formulario);
            return;
        }

        if(campoControl.length && !campoControl.prop('disabled') && !campoControl.prop('readonly')){
            campoControl.trigger('focus');
            campoControl.trigger('select');
            return;
        }

        if(campoTipo.length&&!campoTipo.prop('disabled')&&!campoTipo.prop('readonly')){
            campoTipo.trigger('focus');
        }
    }

    function abrirAutoNuevoTramiteServicios(preferirTipo){
        var contenedor=$('#divNueTram');
        if(!contenedor.length)return;
        var tieneFormulario=contenedor.find('form#form_docleg,form#form_docleg_f').length>0;
        if(!tieneFormulario)return;
        if(contenedor.is(':visible')){
            setTimeout(function(){
                enfocarCampoInicialNuevoTramiteServicios(contenedor,!!preferirTipo);
            },80);
            return;
        }
        contenedor.stop(true,true).show(0);
        setTimeout(function(){
            enfocarCampoInicialNuevoTramiteServicios(contenedor,!!preferirTipo);
        },120);
    }

    $(function(){
        $('form').each(function(){
            var formActual=$(this);
            if($(this).find('select[data-campo="tipo-legalizacion"]').length){prepararOpcionesTipoLegalizacion($(this));sincronizarTipoLegalizacion($(this));}
            if($(this).find('input[name="control"]').length){sincronizarCamposObligatorios($(this));aplicarEstadoPagosFormulario($(this),construirEstadoPagosBase($(this)));}
            if($(this).find('[data-campo="estado-sitra"]').length){
                var numeroInicial=($.trim(formActual.find('input[name="numero"]').val())||'');
                if(numeroInicial!==''&&numeroInicial!=='-')programarValidacionSitra(formActual);
                else{limpiarSitraFormulario(formActual);actualizarEstadoSitra(formActual,'text-muted','SITRA pendiente.');}
            }
        });

        var ns='.tralegValidaciones';

        $(document).off('click'+ns+' change'+ns,'form input[name="cuadis"]').on('click'+ns+' change'+ns,'form input[name="cuadis"]',function(e){e.preventDefault();var check=$(this);check.prop('checked',check.attr('data-cuadis-auto')==='1');sincronizarCamposObligatorios(check.closest('form'));return false;});
        $(document).off('blur'+ns+' change'+ns,'#form_traleg input[name="ci"]').on('blur'+ns+' change'+ns,'#form_traleg input[name="ci"]',function(){var valor=($.trim($(this).val())||'');if(valor!=='')consultarEstadoCuadisPorCi(valor);else limpiarEstadoCuadisEnFormularios();});
        $(document).off('change'+ns,'form select[data-campo="tipo-legalizacion"]').on('change'+ns,'form select[data-campo="tipo-legalizacion"]',function(){
            var form=$(this).closest('form');
            sincronizarTipoLegalizacion(form);

            if(form.find('[data-campo="estado-sitra"]').length) programarValidacionSitra(form);
        });

        $(document).on('change', '#select_carrera_interesado', function(){
            var opt = $(this).find('option:selected');
            var val = $(this).val();
            var num = opt.attr('data-num');
            var ges = opt.attr('data-ges');
            var form = $(this).closest('form');
            
            form.find('#cod_tit_seleccionado').val(val);
            
            if(num && ges){
                form.find('input[name="numero"]').val(num);
                form.find('input[name="gestion"]').val(ges).trigger('input');
            }
        });

        // Validacion de limite de documentos a seleccionar
        $(document).off('change'+ns, '#form_docleg_f input[type="checkbox"]').on('change'+ns, '#form_docleg_f input[type="checkbox"]', function(){
            var form = $(this).closest('form');
            var hiddenTipo = form.find('input[data-campo="tipo-legalizacion-hidden"]').val();
            var select = form.find('select[data-campo="tipo-legalizacion"]');
            var optText = select.find('option:selected').text();
            if(!optText || optText.trim() === ''){
                optText = select.find('option[value="'+hiddenTipo+'"]').text();
            }
            var textoTipo = (optText || '').toUpperCase();
            
            var maxPermitidos = 1;
            var match = textoTipo.match(/(\d+)\s*EJEMPLAR/);
            if (match) {
                maxPermitidos = parseInt(match[1], 10);
            } else if (textoTipo.indexOf('VARIOS') !== -1) {
                maxPermitidos = 99; // Si solo dice varios sin limite de copias
            }
            
            var checkboxesChequeados = form.find('input[type="checkbox"]:checked');
            if (checkboxesChequeados.length > maxPermitidos) {
                $(this).prop('checked', false);
                var errorDiv = form.closest('.e-add-body').find('#error_datos');
                var errorSpan = form.closest('.e-add-body').find('#error_datos_span');
                if (errorDiv.length) {
                    errorSpan.text('El tipo de legalización permite seleccionar un máximo de ' + maxPermitidos + ' documento(s).');
                    errorDiv.show();
                    setTimeout(function(){ errorDiv.fadeOut(); }, 4000);
                } else {
                    alert('Solo puede seleccionar un máximo de ' + maxPermitidos + ' documento(s).');
                }
            }
        });

        $(document).off('input'+ns+' change'+ns,'form input[name="numero"], form input[name="gestion"], form select[name="buscar_en"], form input[data-campo="tipo-legalizacion-hidden"], form input[name="supletorio"]').on('input'+ns+' change'+ns,'form input[name="numero"], form input[name="gestion"], form select[name="buscar_en"], form input[data-campo="tipo-legalizacion-hidden"], form input[name="supletorio"]',function(){var form=$(this).closest('form');if(form.find('[data-campo="estado-sitra"]').length)programarValidacionSitra(form);});
        $(document).off('click'+ns+' change'+ns,'form input[name="ptaang"]').on('click'+ns+' change'+ns,'form input[name="ptaang"]',function(e){e.preventDefault();var check=$(this);check.prop('checked',check.attr('data-ptag-lock')==='1');return false;});
        $(document).off('click'+ns,selectorIconosValidacion()).on('click'+ns,selectorIconosValidacion(),function(e){e.stopPropagation();});
        $(document).off('click'+ns).on('click'+ns,function(e){if($(e.target).closest('.popover').length||$(e.target).closest(selectorIconosValidacion()).length)return;cerrarPopoversValidacion();});
        $(document).off('keydown'+ns).on('keydown'+ns,function(e){if(e.key==='Escape'||e.keyCode===27)cerrarPopoversValidacion();});
        $(document).off('hidden.bs.modal'+ns,'.modal').on('hidden.bs.modal'+ns,'.modal',function(){cerrarPopoversValidacion();});

        var idPerPrincipal=parseInt(($.trim($('#form_traleg input[name="ip"]').first().val()) || '0'),10)||0;
        if(!consultarEstadoCuadisPorPersona(idPerPrincipal)){
            var ciInicial=obtenerCiPrincipalTramite();
            if(ciInicial!=='')consultarEstadoCuadisPorCi(ciInicial);else limpiarEstadoCuadisEnFormularios();
        }

        abrirAutoNuevoTramiteServicios();
    });
</script>
