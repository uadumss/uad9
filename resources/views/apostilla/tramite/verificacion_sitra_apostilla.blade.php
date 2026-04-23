<div class="modal-dialog modal-lg" role="document" id="panel_docleg">

    @php $estado = $docleg->dapo_verificacion_sitra ?? ''; @endphp

    @if($estado === '0')
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title text-white"><i class="fas fa-check-circle"></i>&nbsp;&nbsp;Verificación en SITRA/SID</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
    @else
        <div class="modal-content border-bottom-danger shadow-lg">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="fas fa-times-circle"></i>&nbsp;&nbsp;Verificación en SITRA/SID</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
    @endif

    <div class="modal-body">
        <span class="font-italic">Verificación de apostilla:</span><br/><br/>

        <span class="text-dark font-weight-bold">Datos:</span><br/>
        <span class="text-dark font-italic" style="font-size: 0.8em">
            <span class="font-weight-bold">Nombre:</span>
            <span>{{ ($persona->per_apellido ?? '') . ' ' . ($persona->per_nombre ?? '') }}</span> |
            <span class="font-weight-bold">N° Documento:</span>
            <span>{{ $docleg->dapo_numero_documento ?? '-' }}</span> |
            <span class="font-weight-bold">Tipo:</span>
            <span>{{ $buscarEnNombre }}</span> |
            <span class="font-weight-bold">Trámite:</span>
            <span>{{ $apostilla->lis_nombre ?? '-' }}</span>
        </span>
        <br/>
        <span class="text-info font-italic" style="font-size: 0.85em">
            Fuente:
            @if(($fuente ?? 'sitra')==='sid')
                SID
            @elseif(($fuente ?? 'sitra')==='sitra_sid')
                SITRA y SID
            @else
                SITRA
            @endif
        </span>
        <br/><br/>

        <div class="row">
            @if($estado === '0')
                <div class="font-weight-bold alert-success shadow text-center centrar_bloque col-md-9 p-2">
                    <table class="col-md-12">
                        <tr>
                            <th class="text-right">Nombre:</th>
                            <th class="text-dark text-left border-bottom border-success pl-3">{{ $respuesta->nombre ?? '-' }}</th>
                        </tr>
                        <tr>
                            <th class="text-right">Tipo documento:</th>
                            <th class="text-dark text-left border-bottom border-success pl-3">{{ $respuesta->tipo ?? '-' }}</th>
                        </tr>
                        <tr>
                            <th class="text-right">Número:</th>
                            <th class="text-dark text-left border-bottom border-success pl-3">{{ $respuesta->numero ?? '-' }}</th>
                        </tr>
                        <tr>
                            <th class="text-right">Título:</th>
                            <th class="text-dark text-left border-bottom border-success pl-3">{{ $respuesta->titulo ?? '-' }}</th>
                        </tr>
                        <tr>
                            <th class="text-right">Gestión:</th>
                            <th class="text-dark text-left border-bottom border-success pl-3">{{ $respuesta->gestion ?? '-' }}</th>
                        </tr>
                    </table>
                </div>
                <div class="pt-2 col-md-2 text-success font-weight-bolder text-left"><h1><i class="fas fa-check-circle"></i></h1></div>
            @elseif($estado === '')
                <div class="font-weight-bold alert-secondary shadow text-center centrar_bloque col-md-9 p-2">
                    <p>Este tipo de trámite no requiere verificación en SITRA.</p>
                </div>
                <div class="pt-2 col-md-2 text-secondary font-weight-bolder text-left"><h1><i class="fas fa-minus-circle"></i></h1></div>
            @else
                <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-2">
                    @if($estado === '1')
                        <p>El documento existe en SITRA, pero los datos no coinciden.</p>
                    @else
                        <p>No se encontró el documento en SITRA ni en SID.</p>
                    @endif
                </div>
                <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-times-circle"></i></h1></div>
            @endif
        </div>

        <br/>
        @if($estado === '0')
            <div class="text-success font-italic font-weight-bold border border-success rounded col-md-5" style="font-size: 1.2em">Verificación Correcta {{($fuente ?? 'sitra')==='sid' ? '(SID)' : '(SITRA)'}}</div>
        @elseif($estado === '')
            <div class="text-secondary font-italic font-weight-bold border border-secondary rounded col-md-4" style="font-size: 1.1em">Sin verificación SITRA</div>
        @else
            <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-3" style="font-size: 1.2em">INCORRECTO</div>
        @endif
    </div>

    <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>
</div>
