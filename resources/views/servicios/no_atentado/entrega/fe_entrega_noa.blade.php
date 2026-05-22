<div class="modal-content border-bottom-primary">
    @php
        $apoderadoHabilitado = (bool) config('apoderado.habilitado', true);
    @endphp
    <div class="modal-header bg-primary">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-file-alt"></i> TRAMITE CONVOCATORIA</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>

    <!-- Formulario Convocatoria -->
    <div class="card shadow">
        <div class="modal-body">
            @if(Session::has('exitoModal'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('exitoModal') !!}
                </div>
            @endif
            @if(Session::has('errorModal'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('errorModal') !!}
                </div>
            @endif

            <div class="d-flex justify-content-center">
                <div class="card-body" style="font-size: 14px;">

                        <div class="text-center">
                            <h4 class="text-primary font-weight-bold">Convocatoria</h4>
                        </div>
                        <hr class="sidebar-divider text-bg-dark">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="shadow-sm p-2 col-md-5 float-md-right">
                                    <h1 class="text-danger pr-3 text-center">{{$tramite_noatentado->dtra_numero_tramite}}</h1>
                                    <span class="font-italic text-dark text-center"><?php if($tramite_noatentado->dtra_fecha_registro!=''){echo date('d/m/Y',strtotime($tramite_noatentado->dtra_fecha_registro));} ?></span>
                                </div>
                                <span class="text-primary font-weight-bold text-primary font-italic" style="font-size: 14px">* Datos de la convocatoria</span>
                                    <table class="col-md-12 text-dark table table-sm">
                                        <tbody>
                                        <tr>
                                            <th class="text-right font-italic" style=" padding-top: 7px">Convocatoria :</th>
                                            <td class="border-bottom border-dark" style=" padding-top: 7px">
                                                <span class="text-secondary font-italic font-weight-bold">{{$convocatoria->con_nombre}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <span class="font-weight-bold">{{$tramite_noatentado->tre_nombre}}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de trámite :</th>
                                            <td class="border-bottom border-dark">
                                                @if($tramite_noatentado->dtra_interno=='t')
                                                    <input type="radio" name="tipo_tramite" checked value="t">  INTERNO&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                @else
                                                    <input type="radio" name="tipo_tramite" checked value="f">  EXTERNO
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic text-primary">Nro. Control:</th>
                                            <td class="border-bottom  input-group">
                                                <div class="input-group">
                                                    {{$tramite_noatentado->dtra_control}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    @if($tramite_noatentado->dtra_valorado_reintegro!='')
                                                        <span class="text-primary font-weight-bold font-italic"> Nro. Control Reintegro : &nbsp;</span>
                                                        {{$tramite_noatentado->dtra_valorado_reintegro}}
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                @if($apoderadoHabilitado)
                                    <div class="" id="apoderadoEntrega">
                                        <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                                        <br/>
                                        <br/>
                                        @if($apoderado)
                                            <table class="table table-sm">
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
                                                        <span class="text-primary font-weight-bold">
                                                            @if($tramite_noatentado->dtra_tipo_apoderado=='d')
                                                                Declaración jurada
                                                            @else
                                                                @if($tramite_noatentado->dtra_tipo_apoderado=='p')
                                                                    Poder notariado
                                                                @else
                                                                    @if($tramite_noatentado->dtra_tipo_apoderado=='c')
                                                                        Carta de representación
                                                                    @else
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </span>
                                                    </td>
                                                </tr>
                                            </table>
                                        @endif
                                         @if(!$apoderado)
                                             <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderadoEntrega').show(500); $('#apoderadoEntrega').hide(500);"> Registrar datos del Apoderado</button>
                                         @endif
                                    </div>
                                    <div id="editarApoderadoEntrega" class="border rounded shadow" style="display: none;">
                                        <div class="p-3">
                                            <a onclick="$('#editarApoderadoEntrega').hide(500);$('#apoderadoEntrega').show(500); " id="ocultar" style="float:right">
                                                <i class="fas fa-minus-circle text-danger"></i></a>
                                            <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                            <br><br>
                                            <form id="form_apoderado_noa">
                                                @csrf
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
                                                    $tipoApoderado = $tramite_noatentado->dtra_tipo_apoderado ?: 'd';
                                                    $mostrarBoleta = ($tipoApoderado === 'd' && $requiereBoletaDj);
                                                @endphp

                                                <table class="table-hover col-md-12">
                                                    <tr>
                                                        <th class="text-right font-italic">CI : </th>
                                                        <td class="border-bottom border-dark">
                                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                                   id="ci_noa_apoderado" name="ci" value="{{$ci}}" oninput="verificarBoletaApoderadoNoa();"/></td>
                                                    </tr>
                                                    <tr id="fila_boleta_apoderado_noa" data-requiere-boleta="{{ $requiereBoletaDj ? 1 : 0 }}" style="{{ $mostrarBoleta ? '' : 'display:none;' }}">
                                                        <th class="text-right font-italic">N° control boleta : </th>
                                                        <td class="border-bottom border-dark">
                                                            <input class="form-control form-control-sm border-0" placeholder="Ingrese número de control"
                                                                   id="control_boleta_noa" name="control_boleta" oninput="verificarBoletaApoderadoNoa()"/>
                                                            <div style="margin-top:6px;"><span id="estado_pago_apoderado_noa" class="badge badge-secondary">Sin validar</span></div>
                                                             <input type="hidden" id="control_boleta_valido_noa" name="control_boleta_valido" value="{{ $mostrarBoleta ? '0' : '1' }}">
                                                             <input type="hidden" name="monto_boleta" id="monto_boleta_noa" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic">Apellidos : </th>
                                                        <td class="border-bottom border-dark">
                                                             <input class="form-control form-control-sm border-0" placeholder=""
                                                                    required name="apellido" id="apellido_apoderado" value="{{$apellido}}" {{ $mostrarBoleta ? 'readonly' : '' }} /></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic">Nombres : </th>
                                                        <td class="border-bottom border-dark">
                                                             <input class="form-control form-control-sm border-0" placeholder=""
                                                                    required name="nombre" id="nombre_apoderado" value="{{$nombre}}" {{ $mostrarBoleta ? 'readonly' : '' }} /></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                                        <td class="border-bottom border-dark">
                                                            @if($tramite_noatentado->dtra_tipo_apoderado=='d')
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked onchange="actualizarModoApoderadoNoa()"> Declaración jurada<br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoNoa()"> Poder notariado<br/>
                                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c" onchange="actualizarModoApoderadoNoa()"> Carta de representación
                                                            @else
                                                                @if($tramite_noatentado->dtra_tipo_apoderado=='p')
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoNoa()"> Declaración jurada<br/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked onchange="actualizarModoApoderadoNoa()"> Poder notariado<br/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c" onchange="actualizarModoApoderadoNoa()"> Carta de representación
                                                                @else
                                                                    @if($tramite_noatentado->dtra_tipo_apoderado=='c')
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoNoa()"> Declaración jurada<br/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoNoa()"> Poder notariado<br/>
                                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c" checked onchange="actualizarModoApoderadoNoa()"> Carta de representación
                                                                    @else
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoNoa()"> Declaración jurada<br/>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoNoa()"> Poder notariado<br/>
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="c" onchange="actualizarModoApoderadoNoa()"> Carta de representación
                                                                    @endif
                                                                @endif
                                                            @endif
                                                    </tr>
                                                </table>
                                                <br/>
                                                <input type="hidden" name="cdtra" value="{{$tramite_noatentado->cod_dtra}}">
                                                <input type="hidden" name="pan" value="ent">
                                            </form>
                                            <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_noa','{{url("guardar apoderado noatentado")}}','panel_traleg');" >Guardar</a><br/>
                                            <br/>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-7 shadow border rounded p-2" >
                                <div>
                                    <div>
                                        <br/><br/>
                                        <span class="font-weight-bold text-primary font-italic">* Lista de candidatos</span>
                                        <div class="table-responsive overflow-auto" style="height: 200px">
                                            <table class="table table-sm table-hover" id="lista" width="100%" cellspacing="0" style="font-size: 12px">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Nombre</th>
                                                    <th>CI</th>
                                                    <th>COD SIS</th>
                                                    <th>Cargo</th>
                                                    <th>Unidad</th>
                                                </tr>
                                                <?php $i=1;?>
                                                @foreach($noatentados as $n)
                                                        <?php $sancionado=App\Http\Controllers\Noatentado\SancionadosController::verificarSancionado($n->id_per);?>
                                                    @if($sancionado)
                                                        <tr class="alert-danger">
                                                    @else
                                                        <tr>
                                                            @endif
                                                            <td>{{$i++}}</td>
                                                            <td>{{$n->per_nombre." ".$n->per_apellido}}</td>
                                                            <td>{{$n->per_ci}}</td>
                                                            <td>{{$n->per_cod_sis}}</td>
                                                            <td>{{$n->carg_nombre}}</td>
                                                            <td>{{$n->noa_unidad}}</td>
                                                        </tr>
                                                        @endforeach
                                            </table>

                                        </div>
                                    </div>
                                </div>
                                <hr class="sidebar-divider"/>
                                <div>
                                    <br/>
                                    <span class="text-primary font-italic font-weight-bold" >*Datos del trámite</span>
                                    <br/>
                                    <table class="col-md-12 table table-sm table-hover border">
                                        <tr class="bg-gradient-secondary text-white p-2">
                                            <th>Nº</th>
                                            <th>Nombre</th>
                                            <th>Entregar</th>
                                        </tr>
                                        <?php $i=1;?>

                                        <tr style="font-size: 12px" class="alert-light">
                                            <td>{{$i}}</td>
                                            <td class="text-left">{{$tramite_noatentado->tre_nombre}} <br/>
                                                <span style="font-size: 0.85em">
                                                @if($tramite_noatentado->dtra_interno=='t') <span class="font-weight-bold text-dark font-italic">Trámite : </span><span class="text-danger font-weight-bold">Interno</span> | @endif
                                                 <span class="font-weight-bold text-dark font-italic">Valorado: </span> <span> {{$tramite_noatentado->dtra_control}}</span> |
                                                 @if($tramite_noatentado->dtra_entregado=='t' || $tramite_noatentado->dtra_entregado=='a' )<span class="font-weight-bold text-dark font-italic">Fecha entrega: </span> <span class="text-primary font-weight-bold"> {{date('d/m/Y H:i:s', strtotime($tramite_noatentado->dtra_fecha_recojo))}}</span> |@endif
                                                </span>
                                            </td>

                                            <td class="text-right">
                                                @if($tramite_noatentado->dtra_entregado!='a' && $tramite_noatentado->dtra_entregado!='t')
                                                    @can('entregar tramite - noa')
                                                        @if(sizeof($noatentados)>1 || $tramite_noatentado->cod_apo!='')
                                                            <a href="#" class="btn btn-primary btn-sm" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("datos tramite noa/".$tramite_noatentado->cod_dtra)}}','panel_docleg')"
                                                               title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar</a>
                                                        @else
                                                            <form id="form_g_entrega">
                                                                @csrf
                                                                <input type="hidden" name="cdtra" value="{{$tramite_noatentado->cod_dtra}}">
                                                                <input type="hidden" name="tipo" value="{{$noatentados[0]->id_per}}">
                                                            </form>

                                                                <a href="#" class="btn btn-primary btn-sm" onclick="enviar('form_g_entrega','{{url("g_entrega_noa")}}','panel_traleg');
                                                                        cargarDatos('{{url('actualizar lista entrega noatentado')}}','panel_tabla_no-atentado');$('#traleg').modal('hide');"
                                                                title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar</a>

                                                        @endif

                                                    @endcan
                                                @else
                                                    <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                    @if($tramite_noatentado->dtra_entregado=='a') <span class="font-weight-bold text-success font-italic">Apoderado </span> @endif
                                                @endif
                                            </td>
                                        </tr>

                                    </table>
                                </div>
                            </div>

                        </div>
                </div>
            </div>
        </div><!-- End Formulario Convocatoria -->
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>

<script>

    function requiereBoletaDjNoa() {
        if (typeof window.GLOB_REQUIERE_BOLETA_DJ === 'boolean') {
            return window.GLOB_REQUIERE_BOLETA_DJ;
        }
        return $('#fila_boleta_apoderado_noa').data('requiere-boleta') === 1;
    }

    function actualizarModoApoderadoNoa() {
        var tipo = $('input[name="tipo"]:checked').val() || 'd';
        var requiereBoleta = requiereBoletaDjNoa();
        if (tipo === 'p' || tipo === 'c' || (tipo === 'd' && !requiereBoleta)) {
            $('#fila_boleta_apoderado_noa').hide();
            $('#control_boleta_noa').val('');
            $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-secondary').text('Sin validar');
            $('#control_boleta_valido_noa').val('1');
            
            $('#nombre_apoderado').removeAttr('readonly');
            $('#apellido_apoderado').removeAttr('readonly');
            
            var ci = ($('#ci_noa_apoderado').val()||'').toString().trim();
            if(ci !== '') {
                cargarDatosApoderadoGlobal(ci);
            }
        } else {
            $('#fila_boleta_apoderado_noa').show();
            $('#nombre_apoderado').prop('readonly', true).val('');
            $('#apellido_apoderado').prop('readonly', true).val('');
            $('#control_boleta_valido_noa').val('0');
            verificarBoletaApoderadoNoa();
        }
    }

    $(function(){
        actualizarModoApoderadoNoa();
    });

    function cargarDatosApoderadoGlobal(ci){
        var link="{{url('datos_apo/')}}"+"/"+encodeURIComponent((ci||'').toString().trim());
        $.ajax({
            url:link, type:'GET',
            success:function(resp){
                if(resp=="No"){
                    // do nothing, let them type
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido_apoderado').val(res['apo_apellido']);
                    $('#nombre_apoderado').val(res['apo_nombre']);
                }
            }
        });
    }

    var verificarBoletaApoderadoNoaTimer = null;
    var verificarBoletaApoderadoNoaXHR = null;

    function verificarBoletaApoderadoNoa(){
        if(verificarBoletaApoderadoNoaTimer) clearTimeout(verificarBoletaApoderadoNoaTimer);

        verificarBoletaApoderadoNoaTimer = setTimeout(function(){
            var tipo = $('input[name="tipo"]:checked').val() || 'd';
            var requiereBoleta = requiereBoletaDjNoa();
            if (tipo === 'p' || tipo === 'c' || (tipo === 'd' && !requiereBoleta)) {
                var ci = ($('#ci_noa_apoderado').val()||'').toString().trim();
                if(ci !== '') {
                    cargarDatosApoderadoGlobal(ci);
                }
                return;
            }

            var control=($('#control_boleta_noa').val()||'').toString().trim();
            var ci=($('#ci_noa_apoderado').val()||'').toString().trim();
            if(control===''){
                $('#nombre_apoderado').val('');
                $('#apellido_apoderado').val('');
                $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-secondary').text('Sin validar');
                $('#control_boleta_valido_noa').val('0');
                return;
            }
            if(ci===''){
                $('#nombre_apoderado').val('');
                $('#apellido_apoderado').val('');
                $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-warning').text('Complete CI');
                $('#control_boleta_valido_noa').val('0');
                return;
            }
            var link="{{ url('verificar_boleta') }}"+"/"+encodeURIComponent(control)+'?documento='+encodeURIComponent(ci);
            $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-info').text('Validando...');
            $('#control_boleta_valido_noa').val('0');

            if(verificarBoletaApoderadoNoaXHR && verificarBoletaApoderadoNoaXHR.readyState !== 4){
                verificarBoletaApoderadoNoaXHR.abort();
            }

            verificarBoletaApoderadoNoaXHR = $.ajax({
                url:link,
                type:'GET',
                success:function(resp){
                    if(resp=="No" || resp===null || resp===''){
                        $('#nombre_apoderado').val('');
                        $('#apellido_apoderado').val('');
                        $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-danger').text('No encontrado');
                        $('#control_boleta_valido_noa').val('0');
                        return;
                    }
                    try{
                        var res = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                        $('#apellido_apoderado').val(res['apellido_apoderado'] || '');
                        $('#nombre_apoderado').val(res['nombre_apoderado'] || '');
                        $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-success').text('Pago validado');
                        $('#control_boleta_valido_noa').val('1');
                    }catch(e){
                        $('#nombre_apoderado').val('');
                        $('#apellido_apoderado').val('');
                        $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-warning').text('Respuesta inválida');
                        $('#control_boleta_valido_noa').val('0');
                    }
                },
                error:function(xhr, textStatus){
                    if(textStatus === 'abort') return;
                    $('#nombre_apoderado').val('');
                    $('#apellido_apoderado').val('');
                    $('#estado_pago_apoderado_noa').removeClass().addClass('badge badge-warning').text('Error API');
                    $('#control_boleta_valido_noa').val('0');
                }
            });
        }, 500);
    }
</script>

