<div class="card-body cuadis-ui">
    @php
        $resultadoLista = $resultadoLista ?? null;
        $resolucionNumero = (string)($resolucionNumero ?? '');
        $resolucionAnio = (string)($resolucionAnio ?? '');
        $personasTabla = is_array($personasTabla ?? null) ? $personasTabla : [];
        if (sizeof($personasTabla) === 0) {
            $personasTabla = [
                ['apellido' => '', 'nombre' => '', 'ci' => ''],
                ['apellido' => '', 'nombre' => '', 'ci' => ''],
                ['apellido' => '', 'nombre' => '', 'ci' => ''],
                ['apellido' => '', 'nombre' => '', 'ci' => ''],
            ];
        }
        $habilitadoSeleccion = (string)($habilitado ?? '1');
        $procesados = is_array($resultadoLista['procesados'] ?? null) ? $resultadoLista['procesados'] : [];
        $totalProcesados = sizeof($procesados);
    @endphp

    <style>
        .cuadis-ui {
            --cu-primary: #123a66;
            --cu-primary-soft: #edf3fb;
            --cu-border: #dce4ef;
            --cu-muted: #6b7280;
            --cu-shadow: 0 8px 20px rgba(16, 24, 40, 0.06);
        }

        .cuadis-ui .cuadis-shell {
            border: 1px solid var(--cu-border);
            border-radius: 14px;
            background: #fff;
            box-shadow: var(--cu-shadow);
            overflow: hidden;
        }

        .cuadis-ui .cuadis-header {
            padding: 14px 18px;
            background: linear-gradient(135deg, #f7fafc 0%, #eff5ff 100%);
            border-bottom: 1px solid var(--cu-border);
        }

        .cuadis-ui .cuadis-title {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--cu-primary);
            letter-spacing: 0.2px;
        }

        .cuadis-ui .cuadis-subtitle {
            margin: 3px 0 0;
            color: var(--cu-muted);
            font-size: 0.86rem;
        }

        .cuadis-ui .cuadis-body {
            padding: 16px;
            background: #f8fafc;
        }

        .cuadis-ui .cuadis-panel {
            border: 1px solid var(--cu-border);
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 2px 8px rgba(16, 24, 40, 0.04);
            padding: 14px;
            height: 100%;
        }

        .cuadis-ui .cuadis-panel-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--cu-primary);
            margin-bottom: 10px;
        }

        .cuadis-ui .cuadis-label {
            font-size: 0.77rem;
            font-weight: 700;
            color: #1f2937;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 4px;
        }

        .cuadis-ui .cuadis-form-control,
        .cuadis-ui .cuadis-form-select,
        .cuadis-ui .cuadis-form-addon {
            border-color: #cfd8e3;
            font-size: 0.9rem;
        }

        .cuadis-ui .cuadis-toolbar .btn {
            margin-left: 0.35rem;
        }

        .cuadis-ui .cuadis-toolbar .btn:first-child {
            margin-left: 0;
        }

        .cuadis-ui .cuadis-table-wrap {
            border: 1px solid var(--cu-border);
            border-radius: 10px;
            overflow: auto;
            max-height: 430px;
        }

        .cuadis-ui #tabla_cuadis_personas,
        .cuadis-ui #tabla_resultado_cuadis {
            margin-bottom: 0;
            background: #fff;
        }

        .cuadis-ui #tabla_cuadis_personas thead th,
        .cuadis-ui #tabla_resultado_cuadis thead th {
            position: sticky;
            top: 0;
            z-index: 2;
            background: var(--cu-primary-soft);
            color: #1f2937;
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #d6e0ef;
        }

        .cuadis-ui #tabla_cuadis_personas .fila-indice {
            width: 46px;
            text-align: center;
            font-weight: 700;
            color: #6b7280;
        }

        .cuadis-ui #tabla_cuadis_personas .fila-cuadis-persona.fila-incompleta td {
            background: #fff8e8;
        }

        .cuadis-ui .cuadis-help {
            margin-top: 8px;
            font-size: 0.82rem;
            color: var(--cu-muted);
        }

        .cuadis-ui .cuadis-empty {
            text-align: center;
            color: var(--cu-muted);
            font-style: italic;
            padding: 20px 8px;
        }

        @media (max-width: 991.98px) {
            .cuadis-ui .cuadis-toolbar {
                margin-top: 10px;
            }

            .cuadis-ui .cuadis-toolbar .btn {
                margin-bottom: 6px;
            }
        }
    </style>

    @if(Session::has('exito cuadis'))
        <div class="alert alert-success alert-dismissible mb-3">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('exito cuadis') !!}</span>
        </div>
    @endif

    @if(Session::has('error cuadis'))
        <div class="alert alert-danger alert-dismissible mb-3">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('error cuadis') !!}</span>
        </div>
    @endif

    <div class="cuadis-shell">
        <div class="cuadis-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="cuadis-title">Registro CUADIS</h4>
                <p class="cuadis-subtitle">Carga estructurada de personas por resolución</p>
            </div>
            <span class="badge badge-light border" id="cuadis_total_procesados">Procesados: {{$totalProcesados}}</span>
        </div>

        <div class="cuadis-body">
            <div class="row">
                <div class="col-lg-7 mb-3 mb-lg-0">
                    <div class="cuadis-panel">
                        <div class="cuadis-panel-title">Carga de Personas</div>

                        <form id="form_registro_cuadis_lista">
                            @csrf

                            <div class="form-row align-items-end">
                                <div class="col-md-4 mb-2">
                                    <label class="cuadis-label">Estado CUADIS</label>
                                    <select class="form-control form-control-sm cuadis-form-select" name="habilitado">
                                        <option value="1" @if($habilitadoSeleccion==='1') selected @endif>ACTIVO</option>
                                        <option value="0" @if($habilitadoSeleccion==='0') selected @endif>INACTIVO</option>
                                    </select>
                                </div>

                                <div class="col-md-8 mb-2">
                                    <label class="cuadis-label">Resolución</label>
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text cuadis-form-addon">N°</span>
                                        </div>
                                        <input type="text"
                                               class="form-control cuadis-form-control"
                                               name="resolucion_numero"
                                               maxlength="40"
                                               required
                                               value="{{$resolucionNumero}}"
                                               placeholder="Ej: 123" />
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text cuadis-form-addon">/</span>
                                        </div>
                                        <input type="text"
                                               class="form-control cuadis-form-control"
                                               name="resolucion_anio"
                                               maxlength="4"
                                               pattern="[0-9]{4}"
                                               required
                                               value="{{$resolucionAnio}}"
                                               placeholder="AAAA" />
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-2">
                                <label class="cuadis-label mb-0">Tabla de Personas</label>
                                <div class="cuadis-toolbar text-right">
                                    <a href="#" class="btn btn-outline-primary btn-sm" onclick="return agregarFilaCuadis();">
                                        <i class="fas fa-plus"></i> Fila
                                    </a>
                                </div>
                            </div>

                            <div class="cuadis-table-wrap">
                                <table class="table table-sm table-bordered" id="tabla_cuadis_personas">
                                    <thead>
                                    <tr>
                                        <th class="fila-indice">#</th>
                                        <th style="width: 33%;">Apellidos</th>
                                        <th style="width: 33%;">Nombres</th>
                                        <th style="width: 22%;">CI</th>
                                        <th style="width: 8%;"></th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbody_cuadis_personas">
                                    @foreach($personasTabla as $i=>$fila)
                                        <tr class="fila-cuadis-persona">
                                            <td class="fila-indice">{{$i+1}}</td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm cuadis-form-control" data-campo="apellido" value="{{trim((string)($fila['apellido'] ?? ''))}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm cuadis-form-control" data-campo="nombre" value="{{trim((string)($fila['nombre'] ?? ''))}}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm cuadis-form-control" data-campo="ci" maxlength="20" value="{{trim((string)($fila['ci'] ?? ''))}}">
                                            </td>
                                            <td class="text-center align-middle">
                                                <a href="#" class="btn btn-outline-danger btn-sm" onclick="return eliminarFilaCuadis(this);" title="Eliminar fila">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="cuadis-help">Atajo: Ctrl + Enter para procesar. Si la persona no existe, se crea automáticamente en el padrón.</div>
                        </form>

                        <div id="alerta_lista_cuadis" class="mt-2"></div>

                        <div class="text-right mt-3">
                            <a href="#" id="btn_procesar_cuadis" class="btn btn-primary btn-sm px-4" onclick="return guardarListaCuadis();">
                                <i class="fas fa-save"></i> Procesar lista CUADIS
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="cuadis-panel" id="panel_resultado_cuadis_lista">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="cuadis-panel-title mb-0">Lista Procesada</div>
                            <span class="badge badge-light border" id="cuadis_total_resultados">{{$totalProcesados}} registros</span>
                        </div>

                        <div class="cuadis-table-wrap" style="max-height: 470px;">
                            <table class="table table-sm table-striped table-bordered" id="tabla_resultado_cuadis">
                                <thead>
                                <tr>
                                    <th style="width: 24%;">CI</th>
                                    <th>Nombre</th>
                                    <th style="width: 20%;">Resultado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($procesados as $item)
                                    @php
                                        $accion = strtoupper((string)($item['accion'] ?? ''));
                                        $claseAccion = $accion === 'ACTUALIZADO' ? 'badge-warning' : 'badge-success';
                                    @endphp
                                    <tr>
                                        <td>{{$item['ci'] ?? ''}}</td>
                                        <td>{{$item['nombre'] ?? ''}}</td>
                                        <td><span class="badge {{$claseAccion}}">{{$accion}}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="cuadis-empty">Sin registros procesados.</td>
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
</div>

<script>
    function guardarListaCuadis(){
        var form = $('#form_registro_cuadis_lista');
        if(!form.length){
            return false;
        }

        var numero = ($.trim(form.find('input[name="resolucion_numero"]').val()) || '');
        var anio = ($.trim(form.find('input[name="resolucion_anio"]').val()) || '');
        var panelError = $('#alerta_lista_cuadis');
        var filas = $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona');
        var totalFilasValidas = 0;
        var filaIncompleta = 0;

        limpiarMarcasErrorCuadis();

        if(numero === ''){
            panelError.html('<div class="alert alert-danger py-2 mb-0">Debe ingresar el número de resolución CUADIS.</div>');
            return false;
        }
        if(!/^\d{4}$/.test(anio)){
            panelError.html('<div class="alert alert-danger py-2 mb-0">Debe ingresar un año válido de 4 dígitos.</div>');
            return false;
        }

        filas.each(function(indice){
            var fila = $(this);
            var apellido = ($.trim(fila.find('input[data-campo="apellido"]').val()) || '');
            var nombre = ($.trim(fila.find('input[data-campo="nombre"]').val()) || '');
            var ci = ($.trim(fila.find('input[data-campo="ci"]').val()) || '');

            if(apellido === '' && nombre === '' && ci === ''){
                return;
            }

            if(apellido === '' || nombre === '' || ci === ''){
                if(filaIncompleta === 0){
                    filaIncompleta = indice + 1;
                }
                fila.addClass('fila-incompleta');
                if(apellido === ''){
                    fila.find('input[data-campo="apellido"]').addClass('is-invalid');
                }
                if(nombre === ''){
                    fila.find('input[data-campo="nombre"]').addClass('is-invalid');
                }
                if(ci === ''){
                    fila.find('input[data-campo="ci"]').addClass('is-invalid');
                }
                return;
            }

            totalFilasValidas++;
        });

        if(filaIncompleta > 0){
            panelError.html('<div class="alert alert-danger py-2 mb-0">La fila ' + filaIncompleta + ' está incompleta. Debe llenar Apellidos, Nombres y CI.</div>');
            return false;
        }

        if(totalFilasValidas === 0){
            panelError.html('<div class="alert alert-danger py-2 mb-0">Debe completar al menos una fila de la tabla de personas.</div>');
            return false;
        }

        reindexarFilasCuadis();
        panelError.html('<div class="alert alert-info py-2 mb-0">Procesando lista CUADIS...</div>');
        enviarFormularioCuadis(form, panelError);
        return false;
    }

    function obtenerMensajeErrorCuadis(xhr){
        if(xhr && xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors){
            var errores = [];
            Object.keys(xhr.responseJSON.errors).forEach(function(campo){
                var lista = xhr.responseJSON.errors[campo] || [];
                if(Array.isArray(lista)){
                    errores = errores.concat(lista);
                }
            });
            if(errores.length){
                return errores.join(' ');
            }
        }

        if(xhr && xhr.responseJSON && xhr.responseJSON.message){
            return xhr.responseJSON.message;
        }

        if(xhr && xhr.status === 419){
            return 'La sesión expiró. Recargue la página e intente nuevamente.';
        }
        if(xhr && xhr.status === 403){
            return 'No tiene permisos para esta acción.';
        }
        if(xhr && xhr.status === 404){
            return 'No se encontró la ruta solicitada.';
        }

        return 'No se pudo procesar la lista CUADIS. Corrija los datos e intente nuevamente.';
    }

    function enviarFormularioCuadis(form, panelError){
        var btnProcesar = $('#btn_procesar_cuadis');
        btnProcesar.addClass('disabled').attr('aria-disabled', 'true');

        $.ajax({
            type: 'POST',
            url: '{{url("g_cuadis")}}',
            data: form.serialize(),
            success: function(resp){
                $('#panel_persona').html(resp);
            },
            error: function(xhr){
                var mensaje = obtenerMensajeErrorCuadis(xhr);
                panelError.html('<div class="alert alert-danger py-2 mb-0">' + mensaje + '</div>');
            },
            complete: function(){
                btnProcesar.removeClass('disabled').removeAttr('aria-disabled');
            }
        });
    }

    function limpiarMarcasErrorCuadis(){
        $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').removeClass('fila-incompleta');
        $('#tbody_cuadis_personas').find('input').removeClass('is-invalid');
    }

    function reindexarFilasCuadis(){
        $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').each(function(index){
            var fila = $(this);
            fila.find('.fila-indice').text(index + 1);
            fila.find('input[data-campo="apellido"]').attr('name', 'personas[' + index + '][apellido]');
            fila.find('input[data-campo="nombre"]').attr('name', 'personas[' + index + '][nombre]');
            fila.find('input[data-campo="ci"]').attr('name', 'personas[' + index + '][ci]');
        });

        actualizarContadoresCuadis();
    }

    function agregarFilaCuadis(){
        var nuevaFila = $('<tr class="fila-cuadis-persona">' +
            '<td class="fila-indice"></td>' +
            '<td><input type="text" class="form-control form-control-sm cuadis-form-control" data-campo="apellido"></td>' +
            '<td><input type="text" class="form-control form-control-sm cuadis-form-control" data-campo="nombre"></td>' +
            '<td><input type="text" class="form-control form-control-sm cuadis-form-control" data-campo="ci" maxlength="20"></td>' +
            '<td class="text-center align-middle">' +
                '<a href="#" class="btn btn-outline-danger btn-sm" onclick="return eliminarFilaCuadis(this);" title="Eliminar fila">' +
                    '<i class="fas fa-trash"></i>' +
                '</a>' +
            '</td>' +
        '</tr>');
        $('#tbody_cuadis_personas').append(nuevaFila);
        nuevaFila.find('input[data-campo="apellido"]').trigger('focus');

        reindexarFilasCuadis();
        return false;
    }

    function normalizarTextoCuadis(valor){
        return valor.toString().trim().replace(/\s+/g, ' ').toUpperCase();
    }

    function eliminarFilaCuadis(btn){
        var filas = $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona');
        if(filas.length <= 1){
            var fila = $(btn).closest('tr');
            fila.find('input[data-campo="apellido"]').val('');
            fila.find('input[data-campo="nombre"]').val('');
            fila.find('input[data-campo="ci"]').val('');
            reindexarFilasCuadis();
            return false;
        }

        $(btn).closest('tr').remove();
        reindexarFilasCuadis();
        return false;
    }

    function actualizarContadoresCuadis(){
        var totalFilasConDatos = 0;
        $('#tbody_cuadis_personas').find('tr.fila-cuadis-persona').each(function(){
            var fila = $(this);
            var apellido = ($.trim(fila.find('input[data-campo="apellido"]').val()) || '');
            var nombre = ($.trim(fila.find('input[data-campo="nombre"]').val()) || '');
            var ci = ($.trim(fila.find('input[data-campo="ci"]').val()) || '');
            if(apellido !== '' || nombre !== '' || ci !== ''){
                totalFilasConDatos++;
            }
        });

        $('#cuadis_total_procesados').text('Filas cargadas: ' + totalFilasConDatos);
        $('#cuadis_total_resultados').text($('#tabla_resultado_cuadis tbody tr').not(':has(td[colspan])').length + ' registros');
    }

    $(function(){
        reindexarFilasCuadis();

        $('#tbody_cuadis_personas').on('keydown', 'input', function(evento){
            if(evento.ctrlKey === true && evento.key === 'Enter'){
                evento.preventDefault();
                guardarListaCuadis();
                return false;
            }

            if(evento.key === 'Enter' && $(evento.target).attr('data-campo') === 'ci'){
                evento.preventDefault();
                var filaActual = $(evento.target).closest('tr');
                var filaSiguiente = filaActual.next('.fila-cuadis-persona');
                if(!filaSiguiente.length){
                    agregarFilaCuadis();
                    return false;
                }
                filaSiguiente.find('input[data-campo="apellido"]').trigger('focus');
                return false;
            }
        });

        $('#tbody_cuadis_personas').on('input', 'input', function(){
            actualizarContadoresCuadis();
        });

        $('#tbody_cuadis_personas').on('blur', 'input[data-campo="apellido"], input[data-campo="nombre"]', function(){
            $(this).val(normalizarTextoCuadis($(this).val()));
        });

        $('#tbody_cuadis_personas').on('blur', 'input[data-campo="ci"]', function(){
            $(this).val(normalizarTextoCuadis($(this).val()));
        });
    });
</script>
