<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-user"></i> Apoderado </h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body" >

        @if(Session::has('exito'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold">{!! session('exito') !!}</span>
            </div>
        @endif
        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow-sm">
            <h6 class="text-white font-weight-bold text-center">Datos del apoderado</h6>
        </div>

            <hr class="sidebar-divider"/>
            <div class="row">

                <div class="col-md-6">
                    <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del trámite</span>
                    <br/>
                    <br/>

                    <table class="table table-sm">
                        @php
                            $nombre='';    $apellido='';  $ci="";
                            if($persona){   $apellido=$persona->per_apellido;     $nombre=$persona->per_nombre;  }
                        @endphp
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Nro Trámite : </th>
                            <td class="border-bottom border-dark">
                                    {{$tramita->tra_numero}}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Fecha de solicitud : </th>
                            <td class="border-bottom border-dark">
                                    {{date('y/m/Y', strtotime($tramita->tra_fecha_solicitud))}}
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic text-dark font-italic">Titular : </th>
                            <td class="border-bottom border-dark">
                                {{$apellido." ".$nombre}}
                            </td>

                        </tr>
                    </table>

                <hr class="sidebar-divider"/>
                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                <br/>
                <br/>

                <table class=" table table-sm">
                    <tr>
                        <th class="text-right font-italic text-dark">CI : </th>
                        <td class="border-bottom border-dark">
                            @if($apoderado)
                                {{$apoderado['apo_ci']}}
                            @else
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark font-italic">Nombre apoderado : </th>
                        <td class="border-bottom border-dark">
                            @if($apoderado)
                                {{$apoderado['apo_apellido']." ".$apoderado['apo_nombre']}}
                            @else
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic text-dark">Tipo de apoderado : </th>
                        <td class="border-bottom border-dark">
                            @if($tramita->tra_tipo_apoderado=='d')
                                Declaración jurada
                            @else
                                @if($tramita->tra_tipo_apoderado=='p')
                                    Poder notariado
                                @else
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                @endif
                            @endif
                        </td>
                    </tr>

                </table>

                @if(!$apoderado)
                    <button id="otros" class="btn btn-sm btn-primary" onclick="$('#otrosDiv').show(500); $('#otros').hide(500);"> Registrar datos</button>
                @endif
                </div>
                <div class="col-md-6">
                    <div>
                        <div id="otrosDiv" class="border rounded shadow" style="display: none;">
                            <div class="p-3">
                                <a onclick="$('#otrosDiv').hide(500);$('#otros').show(500); " id="ocultar" style="float:right">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                <br><br>

                                <form id="form_apoderado">

                                    @php
                                        $nombre='';
                                        $apellido='';
                                        $ci='';
                                        if($apoderado){
                                            $ci=$apoderado->apo_ci;
                                            $apellido=$apoderado->apo_apellido;
                                            $nombre=$apoderado->apo_nombre;
                                        }
                                        $requiereBoletaDj = (bool) config('apoderado.requiere_boleta_dj', false);
                                        $tipoApoderado = $tramita->tra_tipo_apoderado ?: 'd';
                                        $mostrarBoleta = ($tipoApoderado === 'd' && $requiereBoletaDj);
                                    @endphp

                                    <table class="table-hover col-md-12">
                                        <tr id="fila_boleta_apoderado_modal" data-requiere-boleta="{{ $requiereBoletaDj ? 1 : 0 }}" style="{{ $mostrarBoleta ? '' : 'display:none;' }}">
                                            <th class="text-right font-italic">N° control boleta : </th>
                                            <td class="border-bottom border-dark">
                                                    <input class="form-control form-control-sm border-0" placeholder="Ingrese número de control"
                                                              name="control_boleta" id="control_boleta_apoderado"
                                                              oninput="verificarBoletaApoderado()" />
                                                <div style="margin-top:6px;"><span id="estado_pago_apoderado_modal" class="badge badge-secondary">Sin validar</span></div>
                                                <input type="hidden" id="control_boleta_valido_modal" name="control_boleta_valido_modal" value="{{ $mostrarBoleta ? '0' : '1' }}">
                                                <input type="hidden" name="monto_boleta" id="monto_boleta_modal" value="0">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">CI : </th>
                                            <td class="border-bottom border-dark">
                                                    <input class="form-control form-control-sm border-0" placeholder=""
                                                              name="ci" id="ci_apoderado_form" value="{{$ci}}"
                                                              oninput="verificarBoletaApoderado()"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Apellidos : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="apellido" id="apellido" value="{{$apellido}}" {{ $mostrarBoleta ? 'readonly' : '' }} /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Nombres : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" id="nombre" value="{{$nombre}}" {{ $mostrarBoleta ? 'readonly' : '' }} /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                            <td class="border-bottom border-dark">
                                                @if($tramita->tra_tipo_apoderado=='d')
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked onchange="actualizarModoApoderadoModal()"> Declaración jurada<br/>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoModal()"> Poder notariado
                                                @else
                                                    @if($tramita->tra_tipo_apoderado=='p')
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoModal()"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked onchange="actualizarModoApoderadoModal()"> Poder notariado
                                                    @else
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoModal()"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoModal()"> Poder notariado
                                                    @endif
                                                @endif

                                        </tr>
                                    </table>
                                    <br>
                                    <input type="hidden" name="ctra" value="{{$tramita->cod_tra}}">
                                </form>
                                <a class="btn btn-primary btn-sm text-white" onclick="guardarDatos('guardar apoderado','panel_apoderado','form_apoderado');" >Guardar</a>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- ================================GLOSA====================================-->

            </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
    </div>
</div>

    <script>
        var verificarBoletaApoderadoTimer = null;
        var verificarBoletaApoderadoXHR = null;

        function cargarDatosApoderadoGlobal(ci){
            var link="{{url('datos_apo/')}}"+"/"+encodeURIComponent((ci||'').toString().trim());
            $.ajax({
                url:link, type:'GET',
                success:function(resp){
                    if(resp=="No"){
                        if ($('#nombre').prop('readonly')) {
                            $('#apellido').val('');
                            $('#nombre').val('');
                        }
                    }
                    else{var res=JSON.parse(resp);$('#apellido').val(res['apo_apellido']);$('#nombre').val(res['apo_nombre']);}
                }
            });
        }

        function requiereBoletaDjModal() {
            if (typeof window.GLOB_REQUIERE_BOLETA_DJ === 'boolean') {
                return window.GLOB_REQUIERE_BOLETA_DJ;
            }
            return $('#fila_boleta_apoderado_modal').data('requiere-boleta') === 1;
        }

        function actualizarModoApoderadoModal() {
            var tipo = $('#form_apoderado input[name="tipo"]:checked').val() || 'd';
            var requiereBoleta = requiereBoletaDjModal();
            if (tipo === 'p' || (tipo === 'd' && !requiereBoleta)) {
                $('#fila_boleta_apoderado_modal').hide();
                $('#control_boleta_apoderado').val('');
                $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-secondary').text('Sin validar');
                $('#control_boleta_valido_modal').val('1'); // Permitir guardar
                
                $('#nombre').removeAttr('readonly');
                $('#apellido').removeAttr('readonly');
                
                var ci = ($('#ci_apoderado_form').val()||'').toString().trim();
                if(ci !== '') {
                    cargarDatosApoderadoGlobal(ci);
                }
            } else {
                $('#fila_boleta_apoderado_modal').show();
                $('#nombre').prop('readonly', true).val('');
                $('#apellido').prop('readonly', true).val('');
                $('#control_boleta_valido_modal').val('0');
                verificarBoletaApoderado();
            }
        }

        $(function(){
            actualizarModoApoderadoModal();
        });

        function verificarBoletaApoderado(){
            if(verificarBoletaApoderadoTimer) clearTimeout(verificarBoletaApoderadoTimer);

            verificarBoletaApoderadoTimer = setTimeout(function(){
                var tipo = $('#form_apoderado input[name="tipo"]:checked').val() || 'd';
                var requiereBoleta = requiereBoletaDjModal();
                if (tipo === 'p' || (tipo === 'd' && !requiereBoleta)) {
                    var ci = ($('#ci_apoderado_form').val()||'').toString().trim();
                    if(ci !== '') {
                        cargarDatosApoderadoGlobal(ci);
                    }
                    return;
                }

                var control = ($('#control_boleta_apoderado').val()||'').toString().trim();
                var ci = ($('#ci_apoderado_form').val()||'').toString().trim();
                if(control===''){
                    $('#nombre').val('');
                    $('#apellido').val('');
                    $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-secondary').text('Sin validar');
                    $('#control_boleta_valido_modal').val('0');
                    return;
                }
                if(ci===''){
                    $('#nombre').val('');
                    $('#apellido').val('');
                    $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-warning').text('Complete CI');
                    $('#control_boleta_valido_modal').val('0');
                    return;
                }

                var link = "{{ url('verificar_boleta') }}" + "/" + encodeURIComponent(control) + '?documento=' + encodeURIComponent(ci) + '&modulo=servicios';

                $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-info').text('Validando...');
                $('#control_boleta_valido_modal').val('0');

                if(verificarBoletaApoderadoXHR && verificarBoletaApoderadoXHR.readyState !== 4){
                    verificarBoletaApoderadoXHR.abort();
                }

                verificarBoletaApoderadoXHR = $.ajax({
                    url: link,
                    type: 'GET',
                    success: function(resp){
                        if(resp==="No" || resp===null || resp===''){
                            $('#nombre').val('');
                            $('#apellido').val('');
                            $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-danger').text('No encontrado');
                            $('#control_boleta_valido_modal').val('0');
                        }else{
                            try{
                                var res = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                                if (res.error) {
                                    $('#nombre').val('');
                                    $('#apellido').val('');
                                    $('#monto_boleta_modal').val('0');
                                    $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-danger').text(res.error);
                                    $('#control_boleta_valido_modal').val('0');
                                } else {
                                    $('#apellido').val(res['apellido_apoderado'] || '');
                                    $('#nombre').val(res['nombre_apoderado'] || '');
                                    $('#monto_boleta_modal').val(res['monto'] || '0');
                                    $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-success').text('Boleta válida');
                                    $('#control_boleta_valido_modal').val('1');
                                }
                            }catch(e){
                                $('#nombre').val('');
                                $('#apellido').val('');
                                $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-warning').text('Respuesta inválida');
                                $('#control_boleta_valido_modal').val('0');
                            }
                        }
                    },
                    error: function(xhr, textStatus){
                        if(textStatus === 'abort') return;
                        var msg = 'Error API';
                        if(xhr.status === 404) msg = 'No encontrado';
                        $('#nombre').val('');
                        $('#apellido').val('');
                        $('#estado_pago_apoderado_modal').removeClass().addClass('badge badge-warning').text(msg);
                        $('#control_boleta_valido_modal').val('0');
                    }
                });
            }, 500);
        }
    </script>


