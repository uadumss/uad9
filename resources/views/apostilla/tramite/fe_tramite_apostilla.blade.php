    <?php //$fecha=date('Y-m-d',strtotime($apostilla->apos_fecha_ingreso))?>
    <div class="modal-content border-bottom-primary">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Apostilla </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            @if(Session::has('exitoagregar'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold">{!! session('exitoagregar') !!}</span>
                </div>
            @endif
                @if(Session::has('erroragregar'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <span class="font-weight-bold">{!! session('erroragregar') !!}</span>
                    </div>
                @endif

            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar tramite de apostilla</h6>
            </div>
            <hr class="sidebar-divider"/>
            @php
                $urlGuardarTramiteApostilla = url('guardar tramite apostilla');
                $urlGuardarApoderadoTramiteApostilla = url('guardar apoderado tramite apostilla');
                $urlTablaTramiteApostilla = url('listar tramite apostilla tabla/' . date('Y-m-d'));
                $urlMostrarObservacionApostilla = url('mostrar observacion tramite apostilla/' . ($tramite_apostilla->cod_apos ?? ''));
            @endphp
            <div class="row apo-edit-layout {{ $cod_apos!=0 ? 'apo-edit-layout--quick-two' : '' }}">
                <div class="col-md-3 apo-col-left">

                        <div class="shadow-sm p-2 col-md-7 centrar_bloque">
                            <span class="text-primary font-weight-bold"> TRÁMITE</span>
                            @if($cod_apos!=0)
                                <h1 class="text-danger pr-3 text-center">UAD{{$tramite_apostilla->apos_numero}}</h1>
                                <span class="font-italic text-dark font-weight-bold centrar_bloque">
                                        <?php if($tramite_apostilla->apos_fecha_ingreso!=''){echo date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso));} ?>
                                </span>
                            @endif
                        </div>
                    @if($cod_apos==0)
                    <form id="form_tramite_apostilla">
                        @csrf
                            <table class="table-hover col-md-12 text-dark">
                                <tr>
                                    <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">

                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci" onchange="cargarDatosPersonales(this.value)" /></td>

                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre" id="nombre" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido" id="apellido" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Teléfono celular : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="celular" id="celular" pattern="[0-8]{1-8}" /></td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-right text-primary"><br/>* DATOS DEL APODERADO</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI apoderado: </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci_apoderado" onchange="cargarDatosApoderado(this.value)" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido_apoderado" id="apellido_apoderado" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre_apoderado" id="nombre_apoderado" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                    <td class="border-bottom border-dark">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                    </td>
                                </tr>
                            </table>

                            <br/>
                            <input type="hidden" name="ca" value="{{$cod_apos}}">
                        </form>
                            @can('crear trámite - apo')
                                <button type="button" class="btn btn-primary btn-sm float-md-right" onclick="enviar('form_tramite_apostilla','{{$urlGuardarTramiteApostilla}}','panel_apostilla');cargarDatos('{{$urlTablaTramiteApostilla}}','panel_tabla_tramites');return false;"> Guardar </button>
                            @endcan
                    @else
                        <form id="form_tramite_apostilla">
                            @csrf
                            <table class="col-md-12 text-dark table table-sm">
                                <tr>
                                    <th colspan="2" class="text-right text-primary"><br/>* DATOS PERSONALES</th>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">{{$persona->per_ci}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombre : </th>
                                    <td class="border-bottom border-dark">{{$persona->per_nombre." ".$persona->per_apellido}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Telefono celular : </th>
                                    <td class="border-bottom border-dark">{{$persona->per_celular}}</td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Fecha de ingreso : </th>
                                    <td class="border-bottom border-dark">{{date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso))}}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-right text-primary">* DATOS DEL APODERADO</th>
                                </tr>
                                @if($apoderado)
                                    <tr>
                                        <th class="text-right font-italic">CI apoderado: </th>
                                        <td class="border-bottom border-dark">{{$apoderado->apo_ci}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Nombre : </th>
                                        <td class="border-bottom border-dark">{{$apoderado->apo_nombre." ".$apoderado->apo_apellido}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                        <td class="border-bottom border-dark">

                                            @if($tramite_apostilla->apos_apoderado=='d')
                                                &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                            @else
                                                @if($tramite_apostilla->apos_apoderado=='p')
                                                    &nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                        @endif
                                        @endif
                                            </td>
                                    </tr>
                                @else
                                    @can('editar apoderado - apo')
                                    <tr>
                                        <th class="text-right font-italic">CI apoderado: </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   name="ci_apoderado" onchange="cargarDatosApoderado(this.value)" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Apellidos : </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="apellido_apoderado" id="apellido_apoderado" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Nombres : </th>
                                        <td class="border-bottom border-dark">
                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                   required name="nombre_apoderado" id="nombre_apoderado" /></td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                        <td class="border-bottom border-dark">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    <input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                        </td>
                                    </tr>
                                    @endcan
                            @endif
                            </table>
                            <input type="hidden" name="ca" value="{{$tramite_apostilla->cod_apos}}">
                        </form>
                            @can('editar apoderado - apo')
                                @if(!$apoderado)
                                    <button type="button" class="btn btn-primary btn-sm float-md-right" onclick="enviar('form_tramite_apostilla','{{$urlGuardarApoderadoTramiteApostilla}}','panel_apostilla');return false;"> Guardar Apoderado </button>
                                @endif
                            @endcan
                    @endif
                    <br/>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                @if($cod_apos!=0)
                <div class="col-md-9 pl-3 apo-col-main">
                    @can('agregar documento - apo')
                        @if($tramite_apostilla->apos_estado<2)
                            <div class="border shadow rounded p-3 mb-3 bg-white apo-quick-card">
                                <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                                    <div>
                                        <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">Registro de apostillas</span>
                                    </div>
                                </div>
                                <hr class="my-2"/>

                                <form id="form_agregar_tramite_rapido" class="apo-quick-form">
                                    @csrf
                                    <div class="apo-quick-step apo-quick-step-pago">
                                        <div class="apo-quick-step-title">
                                            <span class="apo-step-badge">1</span>
                                            <span>Validación de pago</span>
                                        </div>

                                        <div class="form-row align-items-end apo-quick-row apo-quick-row-top">
                                            <div class="col-md-4 mb-2 apo-quick-col apo-quick-col-control">
                                                <label class="font-italic text-dark mb-1">N° control del pago</label>
                                                <div class="d-flex align-items-center">
                                                    <input type="text" class="form-control form-control-sm" name="nro_control" id="nro_control_rapido" autocomplete="off">
                                                    <span class="btn btn-light btn-circle btn-sm text-secondary ml-2 apo-estado-pago-icon"
                                                          data-campo="estado-pago-icon"
                                                        title="Ver detalle de validacion de pago"
                                                          tabindex="0"
                                                          onclick="mostrarDetallePagoRapido(event,this);">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="col-md-8 mb-2 apo-quick-col apo-quick-col-detectado">
                                                <label class="font-italic text-dark mb-1">Trámite detectado</label>
                                                <input type="text" class="form-control form-control-sm" data-campo="tramite-detectado" readonly placeholder="Pendiente">
                                                <small class="text-muted d-block mt-1 apo-quick-help">Se completa automáticamente cuando el pago es válido.</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="apo-quick-step apo-quick-step-doc">
                                        <div class="apo-quick-step-title">
                                            <span class="apo-step-badge">2</span>
                                            <span>Datos del documento</span>
                                        </div>

                                        <div class="form-row align-items-end apo-quick-row apo-quick-row-bottom">
                                            <div class="col-md-6 mb-2 apo-quick-col apo-quick-col-numero">
                                                <label class="font-italic text-dark mb-1" data-campo="label-documento">N° título / resolución</label>
                                                <input type="text" class="form-control form-control-sm" name="numero" autocomplete="off">
                                            </div>
                                            <div class="col-md-3 mb-2 apo-quick-col apo-quick-col-gestion">
                                                <label class="font-italic text-dark mb-1">Gestión documento</label>
                                                <input type="text" class="form-control form-control-sm" name="gestion" pattern="[0-9]{4}" autocomplete="off">
                                            </div>
                                            <div class="col-md-3 mb-2 apo-quick-col apo-quick-col-boton">
                                                <button type="button" class="btn btn-sm btn-primary btn-block" onclick="return submitAgregarApostillaRapida();">+ Agregar trámite</button>
                                            </div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="cl" value="" data-campo="tipo-apostilla-hidden">
                                    <input type="hidden" name="ca" value="{{$cod_apos}}">
                                    <input type="hidden" name="gestion_valorado" value="" data-campo="gestion-api">
                                    <input type="hidden" value="0" data-campo="validacion-recaudacion-ok">
                                    <input type="hidden" value="" data-campo="preimpreso-api">
                                </form>

                            </div>
                        @else
                            <div class="alert alert-secondary py-2">
                                Este trámite ya fue firmado/entregado. No se pueden agregar más documentos.
                            </div>
                        @endif
                    @endcan

                    <div class="border shadow rounded p-3 bg-white apo-selected-card">
                        <span class="text-danger font-italic font-weight-bold" style="font-size: 16px">* Trámites seleccionados</span>

                        <div id="panel_lista_tramites_apostilla" class="overflow-auto" style="height: 360px;">
                            <table class="table table-sm table-hover table-info rounded apo-table-responsive-text">
                                <tr class="bg-gradient-info text-white p-2">
                                    <th>Nº</th>
                                    <th>Nombre</th>
                                    <th>N° trámite</th>
                                    <th>N° Documento</th>
                                    <th>Valorado</th>
                                    <th>SITRA</th>
                                    <th>Opciones</th>
                                </tr>
                                <?php $i=1?>
                                @foreach($detalle_apostilla as $d)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$d->lis_nombre}}</td>
                                        <td>{{$d->dapo_numero}}</td>
                                        <td><span class="font-weight-bolder">{{$d->dapo_numero_documento}}</span>{{" / ".$d->dapo_gestion_documento}}</td>
                                        <td class="bg-gray-200 text-right"><span class="font-weight-bolder">{{$d->dapo_valorado_preimpreso}}</span>{{" / ".$d->dapo_valorado_gestion}}</td>

                                        <td>
                                            @if(($d->dapo_verificacion_sitra ?? '') === '0')
                                                                <a href="#" class="btn btn-light btn-circle btn-sm text-success"
                                                                    data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                                                                    onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                                                   title="Coincide en SITRA"><i class="fas fa-check-circle"></i></a>
                                            @elseif(($d->dapo_verificacion_sitra ?? '') === '1' || ($d->dapo_verificacion_sitra ?? '') === '2')
                                                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger"
                                                                    data-url="{{ url('verificacion sitra apostilla/' . $d->cod_dapo) }}"
                                                                    onclick="cargarDatos(this.dataset.url,'panel_docleg');$('#docleg').modal('show');return false;"
                                                   title="No coincide o no existe en SITRA"><i class="fas fa-times-circle"></i></a>
                                            @else
                                                <span class="btn btn-light btn-circle btn-sm text-secondary" title="Sin verificación SITRA"><i class="fas fa-minus-circle"></i></span>
                                            @endif
                                        </td>

                                        <td>
                                            @can('quitar doumento - apo')
                                                @if($tramite_apostilla->apos_estado<=1)
                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-dark"
                                                                         onclick="cargarDatos('{{ url('eliminar tramite agregado apostilla/' . $d->cod_dapo) }}','panel_lista_tramites_apostilla');cargarDatos('{{ url('listar tramite apostilla tabla/' . date('Y-m-d',strtotime($tramite_apostilla->apos_fecha_ingreso))) }}','panel_tabla_tramites')"
                                                       title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @else
                                                    <i class="fas fa"></i>
                                                @endif
                                            @endcan
                                        </td>
                                    </tr>
                                    <?php $i+=1?>
                                @endforeach
                            </table>

                        </div>
                        <div>
                            <span class="text-danger font-italic font-weight-bold" style="font-size: 12px">* Observaciones</span>
                            <div id="panel_observacion" class="overflow-auto rounded border" style="height: 70px" >
                                <div>{{$tramite_apostilla->apos_obs}}</div>
                            </div>
                        </div>
                        <br/>
                        <a href="#tramite_apostilla" class="btn btn-sm btn-primary text-white" data-toggle="modal"
                                    onclick="cargarDatos('{{$urlMostrarObservacionApostilla}}','panel_tramite_apostilla');"
                           title="Observaciones">Observar
                        </a>
                        @can('generar pdf - apo')
                            <a href="{{url('generar pdf tramites apostilla/'.$cod_apos)}}" class="btn btn-sm btn-danger float-right" onclick="$('#apostilla').modal('hide');" target="otro">
                                <i class="fas fa-file-pdf"></i> Generar
                            </a>
                        @endcan
                    </div>
                </div>
                @endif
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
@php
    $fechaListadoApostilla=data_get($tramite_apostilla ?? null,'apos_fecha_ingreso');
    $fechaListadoApostilla=$fechaListadoApostilla ? date('Y-m-d',strtotime($fechaListadoApostilla)) : date('Y-m-d');
@endphp
<script>
    function cargarDatosPersonales(ci){
        var link="{{url('datos_per/')}}"+"/"+ci;
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
                    $('#celular').val(res['per_celular']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }
    function cargarDatosApoderado(ci){
        var link="{{url('datos_apo/')}}"+"/"+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp=="No"){
                    $('#apellido_apoderado').val('');
                    $('#nombre_apoderado').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido_apoderado').val(res['apo_apellido']);
                    $('#nombre_apoderado').val(res['apo_nombre']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }

    let apostillaRapidaValidacionOk=false;
    let apostillaRapidaControlValidado='';
    let apostillaRapidaCodLisDetectado='';
    let apostillaRapidaTimer=null;
    let apostillaRapidaValidacionSeq=0;
    let apostillaRapidaRetryTimer=null;
    let apostillaRapidaDetallePago='Pendiente de validacion.';

    function compactarMensajeUxPagoApostilla(mensaje,respaldo){
        const texto=(mensaje || '').toString().trim();
        const fallback=(respaldo || '').toString().trim();
        if(texto===''){
            return fallback;
        }

        const normal=texto.toLowerCase();
        if(normal.indexOf('no esta configurado')!==-1 || normal.indexOf('no esta configurada')!==-1){
            return 'Recaudaciones no configurado.';
        }
        if(normal.indexOf('no se pudo conectar')!==-1 || normal.indexOf('no hay conexion')!==-1 || normal.indexOf('api_no_disponible')!==-1){
            return 'Sin conexion con recaudaciones.';
        }
        if(normal.indexOf('no se encontro')!==-1 || normal.indexOf('no se encontró')!==-1){
            return 'Boleta no encontrada.';
        }
        if(normal.indexOf('ya fue utilizado')!==-1 || normal.indexOf('ya fue registrada')!==-1 || normal.indexOf('ya esta registrada')!==-1 || normal.indexOf('ya está registrada')!==-1){
            return 'Boleta ya registrada.';
        }
        if(normal.indexOf('no corresponde')!==-1){
            return 'Boleta no corresponde.';
        }

        if(texto.length>110){
            return fallback!=='' ? fallback : texto.substring(0,107)+'...';
        }
        return texto;
    }

    function limpiarTextoUxApostilla(texto){
        return (texto || '').toString().replace(/\s+/g,' ').trim();
    }

    function limitarTextoUxApostilla(texto,maximo){
        const txt=limpiarTextoUxApostilla(texto);
        const max=(typeof maximo==='number' && maximo>10) ? maximo : 240;
        if(txt.length<=max){
            return txt;
        }
        return txt.substring(0,max-3)+'...';
    }

    function normalizarClaveUxApostilla(texto){
        return limpiarTextoUxApostilla(texto)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g,'');
    }

    function detectarCategoriaPagoUxApostilla(tipo,mensaje){
        if(tipo==='loading') return 'loading';
        if(tipo==='ok') return 'ok';
        if(tipo==='pending') return 'pending';

        const normal=normalizarClaveUxApostilla(mensaje || '');
        if(normal.indexOf('too many')!==-1 || normal.indexOf('demasiadas solicitudes')!==-1 || normal.indexOf('429')!==-1 || normal.indexOf('rate limit')!==-1){
            return 'rate_limit';
        }
        if(normal.indexOf('no esta configurado')!==-1 || normal.indexOf('no esta configurada')!==-1 || normal.indexOf('sistema_no_configurado')!==-1){
            return 'not_configured';
        }
        if(normal.indexOf('sin conexion')!==-1 || normal.indexOf('no hay conexion')!==-1 || normal.indexOf('no se pudo conectar')!==-1 || normal.indexOf('api_no_disponible')!==-1 || normal.indexOf('timeout')!==-1){
            return 'connection';
        }
        if(normal.indexOf('ya fue utilizado')!==-1 || normal.indexOf('ya fue registrada')!==-1 || normal.indexOf('ya esta registrada')!==-1 || normal.indexOf('no se puede usar nuevamente')!==-1){
            return 'used';
        }
        if(normal.indexOf('no se encontro')!==-1){
            return 'not_found';
        }
        if(normal.indexOf('no corresponde')!==-1 || normal.indexOf('no pertenece')!==-1){
            return 'not_match';
        }
        return 'error';
    }

    function visualizarCategoriaPagoIconoApostilla(icono,categoria){
        icono.removeClass('text-success text-danger text-secondary text-muted text-info text-warning');

        if(categoria==='ok'){
            icono.addClass('text-success').html('<i class="fas fa-check-circle"></i>');
            return;
        }
        if(categoria==='loading'){
            icono.addClass('text-info').html('<i class="fas fa-spinner fa-spin"></i>');
            return;
        }
        if(categoria==='rate_limit'){
            icono.addClass('text-warning').html('<i class="fas fa-clock"></i>');
            return;
        }
        if(categoria==='used'){
            icono.addClass('text-warning').html('<i class="fas fa-ban"></i>');
            return;
        }
        if(categoria==='connection'){
            icono.addClass('text-warning').html('<i class="fas fa-plug"></i>');
            return;
        }
        if(categoria==='not_configured'){
            icono.addClass('text-muted').html('<i class="fas fa-cog"></i>');
            return;
        }
        if(categoria==='pending'){
            icono.addClass('text-secondary').html('<i class="fas fa-minus-circle"></i>');
            return;
        }
        if(categoria==='na'){
            icono.addClass('text-muted').html('<i class="fas fa-minus-circle"></i>');
            return;
        }
        if(categoria==='not_match'){
            icono.addClass('text-warning').html('<i class="fas fa-exclamation-circle"></i>');
            return;
        }
        if(categoria==='not_found'){
            icono.addClass('text-warning').html('<i class="fas fa-exclamation-circle"></i>');
            return;
        }
        icono.addClass('text-danger').html('<i class="fas fa-times-circle"></i>');
    }

    function resumenCategoriaPagoUxApostilla(categoria,resumenFallback){
        if(categoria==='ok') return 'Pago validado.';
        if(categoria==='loading') return 'Validando pago...';
        if(categoria==='pending') return 'Pendiente de validacion.';
        if(categoria==='rate_limit') return 'Demasiadas solicitudes.';
        if(categoria==='not_configured') return 'API no configurada.';
        if(categoria==='connection') return 'Sin conexion.';
        if(categoria==='used') return 'Ya utilizado.';
        if(categoria==='not_found') return 'Boleta no encontrada.';
        if(categoria==='not_match') return 'Boleta no corresponde.';
        return (resumenFallback || 'Pago no valido.').toString();
    }

    function deduplicarDetalleApostilla(resumen,detalle){
        let resumenTxt=limpiarTextoUxApostilla(resumen || '');
        let detalleTxt=limpiarTextoUxApostilla(detalle || '');
        if(detalleTxt===''){
            return '';
        }
        const resumenNorm=normalizarClaveUxApostilla(resumenTxt);
        const detalleNorm=normalizarClaveUxApostilla(detalleTxt);
        if(resumenNorm!=='' && (detalleNorm===resumenNorm || detalleNorm.indexOf(resumenNorm+' ')===0 || detalleNorm.indexOf(resumenNorm+':')===0 || detalleNorm.indexOf(resumenNorm+'.')===0)){
            const re=new RegExp('^'+resumenTxt.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+'[\\s:\\.-]*','i');
            detalleTxt=detalleTxt.replace(re,'').trim();
        }
        return detalleTxt;
    }

    function limpiarReintentoRapidoApostilla(){
        if(apostillaRapidaRetryTimer!==null){
            clearTimeout(apostillaRapidaRetryTimer);
            apostillaRapidaRetryTimer=null;
        }
    }

    function construirDetalleUxPagoApostilla(tipo,mensajeOriginal,resumenCorto){
        const resumen=limpiarTextoUxApostilla(resumenCorto || 'Pendiente de validacion.');
        const original=limpiarTextoUxApostilla(mensajeOriginal || '');
        const categoria=detectarCategoriaPagoUxApostilla(tipo,mensajeOriginal || resumenCorto);

        if(original==='' || original.toLowerCase()===resumen.toLowerCase()){
            return resumen;
        }

        if(tipo==='error'){
            if(categoria==='rate_limit'){
                const limpioRate=deduplicarDetalleApostilla(resumen,original);
                if(limpioRate===''){
                    return 'Reintentando en 15 segundos.';
                }
                return limitarTextoUxApostilla(limpioRate+' Reintentando en 15 segundos.',300);
            }
            const limpio=deduplicarDetalleApostilla(resumen,original).replace(/^detalle\s*:\s*/i,'').trim();
            if(limpio===''){
                return resumen;
            }
            return limitarTextoUxApostilla('Detalle: '+limpio,280);
        }

        if(tipo==='ok' && original.length<=140){
            const limpioOk=deduplicarDetalleApostilla(resumen,original).replace(/^detalle\s*:\s*/i,'').trim();
            if(limpioOk===''){
                return resumen;
            }
            return limitarTextoUxApostilla(resumen+' '+limpioOk,240);
        }

        return resumen;
    }

    function formApostillaRapida(){
        return $('#form_agregar_tramite_rapido');
    }

    function setIconoPagoRapido(tipo,mensaje,categoriaForzada){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        const icono=form.find('[data-campo="estado-pago-icon"]');
        if(!icono.length){ return; }

        let title=(mensaje || 'Pendiente de validacion.').toString();
        const categoria=(categoriaForzada || detectarCategoriaPagoUxApostilla(tipo,title)).toString();
        title=compactarMensajeUxPagoApostilla(title,resumenCategoriaPagoUxApostilla(categoria,title));

        icono
            .attr('title','Ver detalle de validación de pago')
            .attr('data-detalle-pago',title)
            .attr('aria-label',title)
            .removeAttr('data-popover-visible');
        icono.popover('hide');
        visualizarCategoriaPagoIconoApostilla(icono,categoria);
    }

    function estadoRegistroRapido(tipo,mensaje){
        const mensajeOriginal=(mensaje || '').toString().trim();
        const categoria=detectarCategoriaPagoUxApostilla(tipo,mensajeOriginal);
        let texto=compactarMensajeUxPagoApostilla(mensajeOriginal,resumenCategoriaPagoUxApostilla(categoria,'')).toString().trim();
        if(texto===''){
            if(tipo==='loading'){
                texto='Validando pago...';
            }else if(tipo==='ok'){
                texto='Pago validado.';
            }else if(tipo==='error'){
                texto='Pago no valido.';
            }else if(tipo==='warn'){
                texto='Revise el dato.';
            }else{
                texto='Pendiente de validacion.';
            }
        }
        apostillaRapidaDetallePago=construirDetalleUxPagoApostilla(tipo,mensajeOriginal,texto);
        setIconoPagoRapido(tipo,texto,categoria);

        const form=formApostillaRapida();
        if(form.length){
            const icono=form.find('[data-campo="estado-pago-icon"]');
            if(icono.length){
                icono.attr('data-detalle-pago',apostillaRapidaDetallePago);
            }
        }
    }

    function mostrarDetallePagoRapido(evento,elemento){
        if(evento){
            evento.preventDefault();
            evento.stopPropagation();
        }

        const icono=$(elemento);
        if(!icono.length){ return false; }

        const detalle=(icono.attr('data-detalle-pago') || apostillaRapidaDetallePago || 'Pendiente de validacion.').toString();
        const visible=icono.attr('data-popover-visible')==='1';

        icono.popover('dispose');
        if(visible){
            icono.removeAttr('data-popover-visible');
            return false;
        }

        icono.popover({
            container:'body',
            trigger:'manual',
            placement:'top',
            content:detalle,
            html:false,
        }).popover('show');
        icono.attr('data-popover-visible','1');
        return false;
    }

    function obtenerControlRapidoApostilla(){
        const form=formApostillaRapida();
        if(!form.length){ return ''; }
        return (form.find('input[name="nro_control"]').val() || '').toString().trim();
    }

    function aplicarEtiquetaDocumentoRapida(label){
        const form=formApostillaRapida();
        if(!form.length){ return; }
        const etiqueta=(label || 'N° título / resolución').toString();
        form.find('[data-campo="label-documento"]').text(etiqueta+':');
    }

    function extraerAnioDesdeFechaPagoApostilla(fechaPago){
        const valor=(fechaPago || '').toString().trim();
        if(valor===''){ return ''; }
        const match=valor.match(/(19|20)\d{2}/);
        return match ? match[0] : '';
    }

    function setGestionValoradoApostilla(anio){
        const form=formApostillaRapida();
        if(!form.length){ return; }
        form.find('input[data-campo="gestion-api"]').val((anio || '').toString());
    }

    function limpiarEstadoValidacionRapida(){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        form.find('[data-campo="validacion-recaudacion-ok"]').val('0');
        form.find('input[data-campo="preimpreso-api"]').val('');
        form.find('input[data-campo="gestion-api"]').val('');

        apostillaRapidaValidacionOk=false;
        apostillaRapidaControlValidado='';
        apostillaRapidaCodLisDetectado='';

        form.find('input[name="cl"]').val('');
        form.find('[data-campo="tramite-detectado"]').val('');
        aplicarEtiquetaDocumentoRapida('N° título / resolución');
        setIconoPagoRapido('pending','Pendiente de validacion.');
    }

    function solicitarValidacionRapidaApostilla(callbackOk,callbackError){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        const nroControl=obtenerControlRapidoApostilla();
        const requestSeq=++apostillaRapidaValidacionSeq;

        if(nroControl===''){
            limpiarReintentoRapidoApostilla();
            limpiarEstadoValidacionRapida();
            estadoRegistroRapido('pending','Ingrese N° de control.');
            if(typeof callbackError==='function'){ callbackError('Ingrese N° de control.'); }
            return;
        }

        estadoRegistroRapido('loading','');

        $.ajax({
            url:'{{url("validar valorado apostilla/".$cod_apos)}}',
            type:'POST',
            dataType:'json',
            data:{
                _token:form.find('input[name="_token"]').val(),
                nro_control:parseInt(nroControl,10) || 0,
                ca:(form.find('input[name="ca"]').val() || '').toString().trim()
            },
            success:function(resp){
                if(requestSeq!==apostillaRapidaValidacionSeq){ return; }
                if(obtenerControlRapidoApostilla()!==nroControl){ return; }

                if(!(resp && resp.ok)){
                    const msg=(resp && resp.message)
                        ? resp.message
                        : 'No se pudo validar el pago. Revise el N° de control e intente nuevamente.';
                    limpiarEstadoValidacionRapida();
                    const esRate=detectarCategoriaPagoUxApostilla('error',msg)==='rate_limit';
                    if(esRate){
                        estadoRegistroRapido('error','Demasiadas solicitudes. Reintentando en 15 segundos.');
                        limpiarReintentoRapidoApostilla();
                        apostillaRapidaRetryTimer=setTimeout(function(){
                            if(obtenerControlRapidoApostilla()!==nroControl){ return; }
                            solicitarValidacionRapidaApostilla();
                        },15000);
                    }else{
                        estadoRegistroRapido('error',msg);
                    }
                    if(typeof callbackError==='function'){ callbackError(msg); }
                    return;
                }

                limpiarReintentoRapidoApostilla();

                const codSugerido=(resp.cod_lis_sugerido || '').toString().trim();
                if(codSugerido!==''){
                    form.find('input[name="cl"]').val(codSugerido);
                    form.find('[data-campo="tramite-detectado"]').val((resp.lis_alias_sugerido || resp.lis_nombre_sugerido || '').toString());
                    aplicarEtiquetaDocumentoRapida((resp.documento_label_sugerido || 'N° título / resolución').toString());
                }else{
                    limpiarEstadoValidacionRapida();
                    estadoRegistroRapido('error','Boleta sin tramite valido.');
                    if(typeof callbackError==='function'){ callbackError('Boleta sin tramite valido.'); }
                    return;
                }

                const anio=extraerAnioDesdeFechaPagoApostilla(resp.fecha_pago || '');
                if(anio!==''){
                    setGestionValoradoApostilla(anio);
                }

                form.find('input[data-campo="preimpreso-api"]').val(resp.preimpreso || '');
                form.find('[data-campo="validacion-recaudacion-ok"]').val('1');

                apostillaRapidaValidacionOk=true;
                apostillaRapidaControlValidado=nroControl;
                apostillaRapidaCodLisDetectado=codSugerido;

                let resumen='Pago validado.';
                if(resp.lis_alias_sugerido){
                    resumen='Pago validado. Tramite: '+resp.lis_alias_sugerido+'.';
                }
                estadoRegistroRapido('ok',resumen);

                if(typeof callbackOk==='function'){ callbackOk(resp); }
            },
            error:function(xhr){
                if(requestSeq!==apostillaRapidaValidacionSeq){ return; }
                if(obtenerControlRapidoApostilla()!==nroControl){ return; }

                const msg=(xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'Sin conexion. Intente nuevamente.';
                limpiarEstadoValidacionRapida();
                const esRate=(xhr.status===429) || detectarCategoriaPagoUxApostilla('error',msg)==='rate_limit';
                if(esRate){
                    estadoRegistroRapido('error','Demasiadas solicitudes. Reintentando en 15 segundos.');
                    limpiarReintentoRapidoApostilla();
                    apostillaRapidaRetryTimer=setTimeout(function(){
                        if(obtenerControlRapidoApostilla()!==nroControl){ return; }
                        solicitarValidacionRapidaApostilla();
                    },15000);
                }else{
                    limpiarReintentoRapidoApostilla();
                    estadoRegistroRapido('error',msg);
                }
                if(typeof callbackError==='function'){ callbackError(msg); }
            }
        });
    }

    function programarValidacionRapidaApostilla(){
        limpiarReintentoRapidoApostilla();
        if(apostillaRapidaTimer!==null){
            clearTimeout(apostillaRapidaTimer);
        }
        apostillaRapidaTimer=setTimeout(function(){
            solicitarValidacionRapidaApostilla();
        },400);
    }

    function guardarAgregarApostillaRapida(){
        const form=formApostillaRapida();
        if(!form.length){ return; }

        const codApos=(form.find('input[name="ca"]').val() || '').toString();
        $.ajax({
            url:'{{url("guardar agregar tramite apostilla")}}',
            type:'POST',
            dataType:'json',
            headers:{ 'Accept':'application/json' },
            data:form.serialize(),
            success:function(resp){
                if(resp && resp.ok){
                    cargarDatos('{{url("ajax tabla agregar")}}/'+codApos,'panel_lista_tramites_apostilla');
                    cargarDatos('{{url("listar tramite apostilla tabla/$fechaListadoApostilla")}}','panel_tabla_tramites');

                    form.find('input[name="nro_control"]').val('');
                    form.find('input[name="numero"]').val('');
                    form.find('input[name="gestion"]').val('');

                    limpiarEstadoValidacionRapida();
                    estadoRegistroRapido('ok','Tramite agregado.');
                    form.find('input[name="nro_control"]').trigger('focus');
                    return;
                }

                const msg=(resp && resp.message)
                    ? resp.message
                    : 'No se pudo registrar el tramite.';
                estadoRegistroRapido('error',msg);
            },
            error:function(xhr){
                const msg=(xhr.responseJSON && xhr.responseJSON.message)
                    ? xhr.responseJSON.message
                    : 'No se pudo registrar el tramite.';
                estadoRegistroRapido('error',msg);
            }
        });
    }

    function submitAgregarApostillaRapida(){
        const form=formApostillaRapida();
        if(!form.length){
            return false;
        }

        const nroControl=obtenerControlRapidoApostilla();

        if(nroControl===''){
            estadoRegistroRapido('pending','Ingrese N° de control.');
            return false;
        }

        const listoParaGuardar=
            apostillaRapidaValidacionOk &&
            apostillaRapidaControlValidado===nroControl &&
            apostillaRapidaCodLisDetectado!=='' &&
            (form.find('input[name="cl"]').val() || '').toString().trim()===apostillaRapidaCodLisDetectado &&
            form.find('[data-campo="validacion-recaudacion-ok"]').val()==='1';

        const intentarGuardar=function(){
            const codLis=(form.find('input[name="cl"]').val() || '').toString().trim();
            if(codLis==='' || codLis!==apostillaRapidaCodLisDetectado){
                estadoRegistroRapido('error','No se detecto tramite valido.');
                return;
            }

            const gestionValorado=(form.find('input[data-campo="gestion-api"]').val() || '').toString().trim();
            if(gestionValorado===''){
                estadoRegistroRapido('error','No se obtuvo gestion del pago.');
                return;
            }

            guardarAgregarApostillaRapida();
        };

        if(listoParaGuardar){
            intentarGuardar();
            return false;
        }

        solicitarValidacionRapidaApostilla(function(){
            intentarGuardar();
        });
        return false;
    }

    $(document)
        .off('input.apoControlRapido','#nro_control_rapido')
        .on('input.apoControlRapido','#nro_control_rapido',function(){
            limpiarEstadoValidacionRapida();
            limpiarReintentoRapidoApostilla();
            if((this.value || '').toString().trim()===''){
                apostillaRapidaValidacionSeq+=1;
                estadoRegistroRapido('pending','Ingrese N° de control.');
                return;
            }
            programarValidacionRapidaApostilla();
        });

    $(document)
        .off('click.apoEstadoPagoDetalle')
        .on('click.apoEstadoPagoDetalle',function(e){
            if($(e.target).closest('[data-campo="estado-pago-icon"], .popover').length===0){
                const icono=$('[data-campo="estado-pago-icon"]');
                if(icono.length){
                    icono.popover('hide').removeAttr('data-popover-visible');
                }
            }
        });

    $(function(){
        const form=formApostillaRapida();
        if(form.length){
            estadoRegistroRapido('pending','Pendiente de validacion.');
        }
    });
</script>
