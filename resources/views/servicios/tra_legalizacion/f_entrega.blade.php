
<div class="modal-content border-bottom-primary">
    @php
        $requiereBoletaDj = (bool) config('apoderado.requiere_boleta_dj', false);
        $tipoApoderado = $tramita->tra_tipo_apoderado ?: 'd';
        $mostrarBoleta = ($tipoApoderado === 'd' && $requiereBoletaDj);
        $apoderadoHabilitado = (bool) config('apoderado.habilitado', true);
    @endphp
    <script>window.GLOB_REQUIERE_BOLETA_DJ = {{ $requiereBoletaDj ? 'true' : 'false' }};</script>
    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-user"></i> Apoderado </h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">

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
             <div class="col-md-4">
                 <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del trámite</span>
                 <br/>
                 <br/>

                 <table class="table table-sm" style="font-size: 0.9em">
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
                         <th class="text-right font-italic text-dark font-italic">CI.: </th>
                         <td class="border-bottom border-dark">
                             {{$persona->per_ci}}
                         </td>
                     </tr>

                     <tr>
                         <th class="text-right font-italic text-dark font-italic">Fecha de solicitud : </th>
                         <td class="border-bottom border-dark">
                             {{date('d/m/Y', strtotime($tramita->tra_fecha_solicitud))}}
                         </td>
                     </tr>
                     <tr>
                         <th class="text-right font-italic text-dark font-italic">Titular : </th>
                         <td class="border-bottom border-dark">
                             {{$apellido." ".$nombre}}
                         </td>

                     </tr>
                 </table>


                 @if($apoderadoHabilitado)
                     <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                     <br/>
                     <br/>
                         <div class="" id="apoderadoEntrega">
                             @if($apoderado)
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
                             @endif
                             @if(!$apoderado)
                                 <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderadoEntrega').show(500); $('#apoderadoEntrega').hide(500);"> Registrar apoderado</button>
                             @endif
                         </div>
                         <div id="editarApoderadoEntrega" class="border rounded shadow" style="display: none;">
                             <div class="p-3">
                                 <a onclick="$('#editarApoderadoEntrega').hide(500);$('#apoderadoEntrega').show(500); " id="ocultar" style="float:right">
                                     <i class="fas fa-minus-circle text-danger"></i></a>
                                 <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                 <br><br>

                                 <form id="form_apoderado_ent">
                                    @csrf
                                     @php
                                         $nombre='';    $apellido='';  $ci="";
                                         if($apoderado){   $ci=$apoderado->apo_ci;       $apellido=$apoderado->apo_apellido;     $nombre=$apoderado->apo_nombre;  }
                                     @endphp

                                     <table class="table-hover col-md-12">
                                         <tr>
                                             <th class="text-right font-italic">CI : </th>
                                             <td class="border-bottom border-dark">
                                                 <input class="form-control form-control-sm border-0" placeholder=""
                                                        id="ci_entrega_apoderado" name="ci" value="{{$ci}}" oninput="verificarBoletaApoderadoEntrega();"/></td>
                                         </tr>
                                         <tr id="fila_boleta_apoderado_entrega">
                                            <th class="text-right font-italic">N° control boleta : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder="Ingrese número de control"
                                                       id="control_boleta_entrega" name="control_boleta" oninput="verificarBoletaApoderadoEntrega()"/>
                                                <div style="margin-top:6px;"><span id="estado_pago_apoderado_entrega" class="badge badge-secondary">Sin validar</span></div>
                                                <input type="hidden" id="control_boleta_valido_entrega" name="control_boleta_valido" value="{{ $mostrarBoleta ? '0' : '1' }}">
                                                <input type="hidden" name="monto_boleta" id="monto_boleta_entrega" value="0">
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
                                                 @if($tramita->tra_tipo_apoderado=='d')
                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked onchange="actualizarModoApoderadoEntrega()"> Declaración jurada<br/>
                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoEntrega()"> Poder notariado
                                                 @else
                                                     @if($tramita->tra_tipo_apoderado=='p')
                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoEntrega()"> Declaración jurada<br/>
                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked onchange="actualizarModoApoderadoEntrega()"> Poder notariado
                                                     @else
                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" onchange="actualizarModoApoderadoEntrega()"> Declaración jurada<br/>
                                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" onchange="actualizarModoApoderadoEntrega()"> Poder notariado
                                             @endif
                                             @endif

                                         </tr>
                                     </table>
                                     <br/>
                                     <input type="hidden" name="ctra" value="{{$tramita->cod_tra}}">
                                     <input type="hidden" name="pan" value="ent">
                                 </form>
                                 <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_ent','{{url("guardar apoderado")}}','panel_traleg');" >Guardar</a><br/>
                                 <br/>
                             </div>
                         </div>
                 @endif
             </div>


             <div class="col-md-8">
                 <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Documentos del trámite</span>

                 @if($tramita->cod_apo!='')
                     <a href="#" class="btn btn-outline-danger btn-sm font-weight-bold shadow float-right" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("datos legalizado/2/".$tramita->cod_tra)}}','panel_docleg')"
                        title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar todo</a>
                 @else
                     <form id="form_g_entrega">
                         @csrf
                         <input type="hidden" name="ctra" value="{{$tramita->cod_tra}}">
                         <input type="hidden" name="tipo" value="t">
                         <input type="hidden" name="todo" value="t">
                     </form>
                     <a href="#" class="btn btn-outline-danger btn-sm font-weight-bold shadow float-right" onclick="guardarDatos('{{url("g_entrega")}}','panel_traleg','form_g_entrega')"
                        title="Entregar todos los documentos"><i class="fas fa-angle-right"></i> Entregar todo</a>
                 @endif

                 <div>
                     <table class="col-md-12 table table-sm table-hover border">
                         <tr class="bg-gradient-secondary text-white p-2">
                             <th>Nº</th>
                             <th>Nombre</th>
                             @if($tramita->tra_tipo_tramite=='B')
                                 <th>Documentos</th>
                             @endif
                             <th>Nº Título</th>
                             <th>Opciones</th>
                             <th>Entregar</th>
                         </tr>
                         <?php $i=1;?>
                         @foreach($documentos as $d)
                                     <tr style="font-size: 0.80em" class="alert-light">
                                         <td>{{$i}}</td>
                                         <td class="text-left">{{$d->tre_nombre}}<br/>
                                             <span style="font-size: 0.85em">
                                                @if($d->dtra_interno=='t') <span class="font-weight-bold text-dark font-italic">Trámite : </span><span class="text-danger font-weight-bold">Interno</span> | @endif
                                                 <span class="font-weight-bold text-dark font-italic">Valorado: </span> <span> {{$d->dtra_valorado}}</span> |
                                                 @if($d->dtra_entregado=='t' || $d->dtra_entregado=='a' )<span class="font-weight-bold text-dark font-italic">Fecha entrega: </span> <span class="text-primary font-weight-bold"> {{date('d/m/Y H:i:s', strtotime($d->dtra_fecha_recojo))}}</span> |@endif
                                             </span>
                                         </td>
                                         @if($tramita->tra_tipo_tramite=='B')
                                             <td>{{$d->dcon_doc}}</td>
                                         @endif
                                         <td class="text-left">{{$d->dtra_numero."/".substr($d->dtra_gestion,-2)}}</td>
                                         <td class="text-right">
                                             @if($d->dtra_generado=='t')
                                                 @if($d->dtra_estado_doc==0)
                                                     <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('ver documento pdf legalizado/{{$d->cod_dtra}}','panel_docleg')"
                                                        title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>
                                                 @endif
                                                     @if($tramita->tra_tipo_tramite=='C')
                                                        <a class="btn btn-light btn-sm btn-circle" href="{{url('generar pdf/'.$d->cod_dtra)}}" target="pdf{{rand(1,1000)}}"><i class="text-dark fas fa-file-pdf"></i></a>
                                                     @endif

                                             @endif
                                         </td>
                                         <td class="text-right">
                                             @if($d->dtra_entregado!='a' && $d->dtra_entregado!='t')
                                                 @can('entregar legalizacion docleg - srv')
                                                    @if($tramita->cod_apo!='')
                                                        <a href="#" class="btn btn-primary btn-sm" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("datos legalizado/1/".$d->cod_dtra)}}','panel_docleg')"
                                                            title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar +</a>
                                                     @else
                                                        <form id="form_g_entrega{{$i}}">
                                                            @csrf
                                                            <input type="hidden" name="cdtra" value="{{$d->cod_dtra}}">
                                                            <input type="hidden" name="ctra" value="{{$d->cod_tra}}">
                                                            <input type="hidden" name="tipo" value="t">
                                                        </form>
                                                         <a href="#" class="btn btn-primary btn-sm" onclick="guardarDatos('{{url("g_entrega")}}','panel_traleg','form_g_entrega{{$i}}')"
                                                            title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar</a>
                                                     @endif

                                                 @endcan
                                             @else
                                                 <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                 @if($d->dtra_entregado=='a') <span class="font-weight-bold text-success font-italic">Apoderado </span> @endif
                                             @endif
                                         </td>
                                     </tr>
                                     <?php $i++;?>
                                     @endforeach
                     </table>
                 </div>
             </div>
        </div>
        <div class="modal-footer">
             <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
<script>
    function cargarDatosApoderado(ci){
        var link="{{url('datos_apo/')}}"+"/"+encodeURIComponent((ci||'').toString().trim());
        $.ajax({
            url:link, type:'GET',
            success:function(resp){
                if(resp=="No"){
                    // Solo limpiar si NO estamos en modo manual (es decir, si el campo NO es editable)
                    if ($('#nombre_apoderado').prop('readonly')) {
                        $('#apellido_apoderado').val('');
                        $('#nombre_apoderado').val('');
                    }
                }
                else{
                    var res=JSON.parse(resp);
                    $('#apellido_apoderado').val(res['apo_apellido']);
                    $('#nombre_apoderado').val(res['apo_nombre']);
                }
            }
        });
    }

    var verificarBoletaApoderadoEntregaTimer = null;
    var verificarBoletaApoderadoEntregaXHR = null;

    function actualizarModoApoderadoEntrega() {
        var tipo = $('input[name="tipo"]:checked').val() || 'd';

        // El campo N.º de control siempre debe mostrarse.
        $('#fila_boleta_apoderado_entrega').show();

        // Al ser opcional, vacío significa válido para guardar.
        var control = ($('#control_boleta_entrega').val() || '').toString().trim();

        if (control === '') {
            $('#control_boleta_valido_entrega').val('1');
            $('#estado_pago_apoderado_entrega')
                .removeClass()
                .addClass('badge badge-secondary')
                .text('Opcional');
        }

        // Los datos del apoderado quedan editables si no existe
        // una boleta validada que los haya completado.
        if ($('#control_boleta_valido_entrega').val() !== '1' || control === '') {
            $('#nombre_apoderado').removeAttr('readonly');
            $('#apellido_apoderado').removeAttr('readonly');

            var ci = ($('#ci_entrega_apoderado').val() || '').toString().trim();

            if (ci !== '') {
                cargarDatosApoderado(ci);
            }
        }
    }

    $(function(){
        actualizarModoApoderadoEntrega();
    });

    function verificarBoletaApoderadoEntrega(){
        if(verificarBoletaApoderadoEntregaTimer) clearTimeout(verificarBoletaApoderadoEntregaTimer);

        verificarBoletaApoderadoEntregaTimer = setTimeout(function(){

            var control=($('#control_boleta_entrega').val()||'').toString().trim();
            var ci=($('#ci_entrega_apoderado').val()||'').toString().trim();
            if(control===''){
                $('#nombre_apoderado').val('');
                $('#apellido_apoderado').val('');
                $('#estado_pago_apoderado_entrega')
                    .removeClass()
                    .addClass('badge badge-secondary')
                    .text('Sin validar');

                $('#control_boleta_valido_entrega').val('0');
                return;
            }
            if(ci===''){
                $('#nombre_apoderado').val('');
                $('#apellido_apoderado').val('');
                $('#estado_pago_apoderado_entrega').removeClass().addClass('badge badge-warning').text('Complete CI');
                $('#control_boleta_valido_entrega').val('0');
                return;
            }
            var link="{{ url('verificar_boleta') }}"+"/"+encodeURIComponent(control)+'?documento='+encodeURIComponent(ci);
            $('#estado_pago_apoderado_entrega').removeClass().addClass('badge badge-info').text('Validando...');
            $('#control_boleta_valido_entrega').val('0');

            if(verificarBoletaApoderadoEntregaXHR && verificarBoletaApoderadoEntregaXHR.readyState !== 4){
                verificarBoletaApoderadoEntregaXHR.abort();
            }

            verificarBoletaApoderadoEntregaXHR = $.ajax({
                url:link,
                type:'GET',
                success:function(resp){
                    if(resp=="No" || resp===null || resp===''){
                        $('#nombre_apoderado').val('');
                        $('#apellido_apoderado').val('');
                        $('#estado_pago_apoderado_entrega').removeClass().addClass('badge badge-danger').text('No encontrado');
                        $('#control_boleta_valido_entrega').val('0');
                        return;
                    }
                    try{
                        var res = (typeof resp === 'string') ? JSON.parse(resp) : resp;
                        $('#apellido_apoderado').val(res['apellido_apoderado'] || '');
                        $('#nombre_apoderado').val(res['nombre_apoderado'] || '');
                        $('#estado_pago_apoderado_entrega').removeClass().addClass('badge badge-success').text('Pago validado');
                        $('#control_boleta_valido_entrega').val('1');
                    }catch(e){
                        $('#nombre_apoderado').val('');
                        $('#apellido_apoderado').val('');
                        $('#estado_pago_apoderado_entrega').removeClass().addClass('badge badge-warning').text('Respuesta inválida');
                        $('#control_boleta_valido_entrega').val('0');
                    }
                },
                error:function(xhr, textStatus){
                    if(textStatus === 'abort') return;
                    $('#nombre_apoderado').val('');
                    $('#apellido_apoderado').val('');
                    $('#estado_pago_apoderado_entrega').removeClass().addClass('badge badge-warning').text('Error API');
                    $('#control_boleta_valido_entrega').val('0');
                }
            });
        }, 500);
    }
</script>
