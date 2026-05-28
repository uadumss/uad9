@extends('marco/pagina')
@section('contenido')
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">{!! session('exito') !!}</span>
        </div>
    @endif
    @if(sizeof($tramitas)==0)
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold">No existe registros</span>
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
        </div>
    @endif
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $e)
                    <li class="font-weight-bold te">{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card shadow mb-4 ui-listado-servicios-principal">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp; LEGALIZACIONES</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <div class="input-group">
                        <div class="float-left ">
                            <div class="input-group">
                                <span class="text-dark font-weight-bold pt-1" style="font-size: .9em;"> Buscar fecha :&nbsp; &nbsp;</span>
                                <input class="form-control form-control-sm" type="date" name="fecha" onchange="$(location).attr('href','{{url("listar tramite legalizacion/")}}'+'/'+this.value);" />
                            </div>
                        </div>&nbsp;&nbsp;|&nbsp;&nbsp;
                        @can('crear traleg - srv')
                            @if($fecha==(date('Y-m-d')))
                                <a class="btn btn-outline-info btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#traleg" data-toggle="modal"
                                    onclick="generarNumero('L','generar numero','panel_traleg', this);">
                                    <i class="fas fa-plus"></i> Legalización
                                </a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-warning btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#traleg" data-toggle="modal"
                                    onclick="generarNumero('C','generar numero','panel_traleg', this);">
                                    <i class="fas fa-plus"></i> Certificación
                                </a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-danger btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#traleg" data-toggle="modal"
                                    onclick="generarNumero('F','generar numero','panel_traleg', this);">
                                    <i class="fas fa-plus"></i> Confrontación
                                </a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-success btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#traleg" data-toggle="modal"
                                    onclick="generarNumero('B','generar numero busqueda/','panel_traleg', this);">
                                    <i class="fas fa-plus"></i> Búsqueda
                                </a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-secondary btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#traleg" data-toggle="modal"
                                    onclick="generarNumero('E','generar numero/','panel_traleg', this);">
                                    <i class="fas fa-plus"></i> Consejo
                                </a>
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a class="btn btn-outline-info btn-sm text-dark m-1 pt-1 shadow-sm" data-target="#traleg" data-toggle="modal"
                                    onclick="cargarDatos('{{url('fe_importar Legalizacion')}}','panel_traleg')">
                                    <i class="fas fa-plus"></i> Importar Legalización
                                </a>
                            @endif
                        @endcan
                    </div>
                </div>
                <div class="col-md-3">

                    <div class="input-group float-left">
                        <input class="form-control form-control-sm" type="text" placeholder="Nro. valorado" id="ver_valorado"
                               onchange="if(+this.value!=''){cargarDatos('{{url('buscar valorado/')}}'+'/'+this.value,'panel_docleg');$('#docleg').modal('show');}"/>
                        <span class="btn btn-sm btn-primary" onclick="if($('#ver_valorado').val()!=''){cargarDatos('{{url('buscar valorado/')}}'+'/'+$('#ver_valorado').val(),'panel_docleg');$('#docleg').modal('show');}"><i class="fas fa-check-circle"></i></span>&nbsp;&nbsp;

                            <input class="form-control form-control-sm" type="text" placeholder="Nro. tramite"
                                   onchange="$(location).attr('href','{{url("buscar tramite legalizacion/")}}'+'/'+this.value);"/>
                                <span class="btn btn-sm btn-primary"><i class="fas fa-search"></i></span>
                            <span class="text-danger font-weight-bold pt-1" style="font-size: .8em;">&nbsp;Ejm: 123-2022</span>
                        </div>
                </div>
            </div>

            <hr class="sidebar-divider"/>
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                        <h5 class="text-white text-center">Trámites de Legalización</h5>
                    </div>
                    <span style="font-size: 0.8em">
                        <span class="font-weight-bold font-italic text-primary">Fecha: </span><span class="font-italic text-dark">{{date('d/m/Y',strtotime($fecha))}}</span>
                    </span>
                    <hr class="sidebar-divider">
                        <div class="table-responsive">
                            <div id="panel_tabla_tramites">
                            <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
                                <thead>
                                <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
                                    <th>Nº</th>
                                    <th class="text-left">Tipo</th>
                                    <th class="text-left">Número</th>
                                    <th class="text-left">CI</th>
                                    <th class="text-left">Nombre</th>
                                    <th class="text-left">Fecha solicitud</th>
                                    <th class="text-left">Fecha firma</th>
                                    <th class="text-right">Fecha recojo</th>
                                    <th class="text-center">Opciones</th>
                                    <th class="text-center">Entrega</th>
                                </tr>
                                </thead>
                                <tbody id="cuerpo">
                                <?php $i=1;?>
                                @foreach($tramitas as $t)
                                    @if($t->tra_anulado=='t')
                                        <tr class="alert-danger">
                                    @else
                                        <tr class="">
                                    @endif
                                            <th class="border-right font-weight-bolder">
                                                <span class="text-primary">{{$i}}</span>
                                            </th>
                                            <td>
                                                @php    $tipo_tramite['L']='LEGALIZACIÓN'; $tipo_tramite['LC']='bg-info text-white';
                                                        $tipo_tramite['F']='CONFRONTACIÓN';$tipo_tramite['FC']='bg-danger text-white';
                                                        $tipo_tramite['C']='CERTIFICACIÓN';$tipo_tramite['CC']='bg-warning text-dark';
                                                        $tipo_tramite['B']='BÚSQUEDA';$tipo_tramite['BC']='bg-success text-white';
                                                        $tipo_tramite['E']='CONSEJO';$tipo_tramite['EC']='bg-secondary text-white';
                                                @endphp
                                                <span class="font-weight-bold rounded pl-2 pr-2 {{$tipo_tramite[$t->tra_tipo_tramite.'C']}}" style="font-size: 0.75em">
                                                {{$tipo_tramite[$t->tra_tipo_tramite]}}
                                            </span>
                                                @if($t->tra_obs=='t')
                                                    &nbsp;<i class="fas fa-info-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td class="text-left">{{$t->tra_numero}}</td>
                                            <td class="text-left">{{$t->per_ci}}</td>
                                            <td class="text-left">{{$t->per_apellido." ".$t->per_nombre}}
                                                @if($t->tra_tipo_apoderado=='p')
                                                    <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                                                @else
                                                    @if($t->tra_tipo_apoderado=='d')
                                                        <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td class="text-right">{{date('d/m/Y',strtotime($t->tra_fecha_solicitud))}}</td>
                                            <td class="text-right">@php if($t->tra_fecha_firma!=''){echo date('d/m/Y',strtotime($t->tra_fecha_firma));} @endphp </td>
                                            <td class="text-right">@php if($t->tra_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->tra_fecha_recojo));} @endphp </td>
                                            <td class="text-right">

                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" onclick="cargarDatos('{{url("datos tramite legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                   title="Insertar datos al trámite"><i class="fas fa-pen-alt"></i>
                                                </a>

                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" onclick="cargarDatos('{{url("f_cambiar_tipo_tramite/$t->cod_tra")}}','panel_traleg')"
                                                   title="Cambiar tipo de trámite"><i class="fas fa-arrows-alt"></i>
                                                </a>

                                                @can('eliminar traleg - srv')
                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("f_eli_tra_legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                        title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                                                    </a>
                                                @endcan
                                            </td>
                                            <td class="text-right">
                                                @if($t->id_per!='')
                                                    <a class="btn btn-light btn-circle btn-sm text-success" data-target="#traleg" data-toggle="modal" onclick="cargarDatos('{{url("panel entrega legalizacion/$t->cod_tra")}}','panel_traleg')"
                                                       title="Entregar legalizaciones"> <i class="fas fa-hand-point-right"></i></a>
                                                @endif
                                            </td>

                                        </tr>
                                        <?php $i++;?>
                                        @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--===========================MODAL TRALEG===================-->
    <div class="modal fade" id="traleg" role="dialog" style="z-index: 1500"  aria-hidden="false" data-backdrop="false">
        <div class="modal-dialog modal-xl" role="document" id="panel_traleg">

        </div>
    </div>
    <!--===========================END===================-->

    <!-- ================== MODAL DOCLEG ====================-->
        <div class="modal fade" id="docleg" role="dialog" style="z-index: 3000" data-backdrop="false">
            <div class="modal-dialog modal-xl" role="document" id="panel_docleg">

            </div>
        </div>
    <!--===========================END ==============================-->

    <script>
        /**
         * Reemplaza el contenido de un panel con transicion suave (fade 140ms).
         * Evita el corte visual abrupto al cambiar el contenido del modal.
         */
        function _reemplazarPanel(panel, html, callback){
            var panelEl=$('#'+panel);
            panelEl.html(html);
            var inner = panelEl.children().first();
            if(inner.length){
                inner.css('opacity', 0).animate({opacity: 1}, 200, function(){
                    if(typeof callback==='function') callback();
                });
            } else {
                if(typeof callback==='function') callback();
            }
        }

        function enviar1(formulario,ruta,panel,btn){
            if(btn && btn.dataset.loading==='1'){
                return;
            }
            setBotonCargandoServicios(btn, true, 'Registrando...');
            var finalizar = function(){ setBotonCargandoServicios(btn, false); };
            $.ajax({
                type: "POST",
                url: ruta,
                data: $("#"+formulario).serialize(),
                success: function(resp) {
                    _reemplazarPanel(panel, resp, function(){
                        cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                        mostrarToastServiciosLeg('Documento registrado correctamente.','ok');
                        finalizar();
                    });
                },
                error: function(resp) {
                    var texto='';
                    try{
                        var obj=resp.responseJSON;
                        if(obj && obj.errors){
                            $.each(obj.errors,function(k,v){texto+=v+'<br/>';});
                        } else if(obj && obj.message){
                            texto=obj.message;
                        }
                    }catch(e){}
                    if(!texto) texto='Error al registrar el documento.';
                    $('#error_datos_span').html(texto);
                    $('#error_datos').show();
                    setTimeout(function(){$('#error_datos').hide(500);},5000);
                    finalizar();
                }
            });
        }

        /**
         * Toast flotante independiente del modal. tipo: ok | error
         */
        function mostrarToastServiciosLeg(mensaje, tipo){
            var id='srv-toast-leg';
            $('#'+id).remove();
            var cfg=(tipo==='ok')
                ?{bg:'#ecfdf5',brd:'#047857',col:'#065f46',icon:'fa-check-circle'}
                :{bg:'#fef2f2',brd:'#b91c1c',col:'#7f1d1d',icon:'fa-exclamation-circle'};
            var wrap=$('<div>').attr('id',id).css({
                position:'fixed',bottom:'28px',right:'28px','z-index':99999,
                display:'flex','align-items':'center',gap:'8px',
                padding:'10px 16px','border-radius':'8px',
                background:cfg.bg,'border-left':'4px solid '+cfg.brd,
                color:cfg.col,'font-size':'12.5px','font-weight':600,
                'box-shadow':'0 8px 24px rgba(0,0,0,.14)',
                opacity:0,transition:'opacity .25s'
            });
            $('<i>').addClass('fas '+cfg.icon).css('flex-shrink',0).appendTo(wrap);
            $('<span>').text(mensaje).css('margin-left','4px').appendTo(wrap);
            $('<button>').html('×').css({
                'margin-left':'10px',background:'none',border:'none',cursor:'pointer',
                'font-size':'16px','line-height':1,color:'inherit',opacity:.6
            }).on('click',function(){wrap.remove();}).appendTo(wrap);
            $('body').append(wrap);
            setTimeout(function(){wrap.css('opacity','1');},20);
            setTimeout(function(){
                wrap.css('opacity','0');
                setTimeout(function(){wrap.remove();},300);
            },3500);
        }


        function enfocarCampoValoradoServicios(panel){
            var scope = $('#'+panel);
            if(!scope.length)return;
            var campoValorado = scope.find('#form_docleg input[name="control"]').first();
            if(!campoValorado.length){
                campoValorado = scope.find('#form_docleg_f input[name="control"]').first();
            }
            if(!campoValorado.length){
                campoValorado = scope.find('input[name="valorado"]').first();
            }
            if(campoValorado.length && !campoValorado.prop('disabled') && !campoValorado.prop('readonly')){
                setTimeout(function(){
                    campoValorado.trigger('focus');
                    campoValorado.trigger('select');
                },80);
            }
        }

        function setBotonCargandoServicios(btn,activo,texto){
            if(!btn){return;}
            if(activo){
                if(btn.dataset.loading==='1'){return;}
                btn.dataset.loading='1';
                btn.dataset.originalHtml=btn.innerHTML;
                btn.classList.add('disabled');
                btn.setAttribute('aria-busy','true');
                btn.setAttribute('aria-disabled','true');
                btn.style.pointerEvents='none';
                btn.innerHTML='<i class="fas fa-spinner fa-spin"></i>'+(texto ? ' '+texto : ' Cargando...');
                return;
            }
            if(btn.dataset.loading!=='1'){return;}
            if(btn.dataset.originalHtml){btn.innerHTML=btn.dataset.originalHtml;}
            btn.classList.remove('disabled');
            btn.removeAttribute('aria-busy');
            btn.removeAttribute('aria-disabled');
            btn.style.pointerEvents='';
            btn.dataset.loading='0';
        }

        function generarNumero(tipo,url,panel,btn){
            if(btn && btn.dataset.loading==='1'){
                return;
            }
            setBotonCargandoServicios(btn,true,'Cargando...');
            var finalizar=function(){setBotonCargandoServicios(btn,false);};
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-warning'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div><span class='text-white font-weight-bold'>&nbsp;  Cargando ...</span></div>");
            var link = "{{url('/')}}"+"/"+url;
            var token = "{{csrf_token()}}";
            var form ='fecha={{$fecha}}&tipo='+tipo;
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                data:form,
                //data:$('#form_editar').serialize(),
                success: function (resp) {
                    var contenedorTemporal = $('<div>').html(resp);
                    var codTra = $.trim(contenedorTemporal.find('[data-campo="nuevo-cod-tra"]').first().val() || '');
                    if(codTra !== ''){
                        cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                        var rutaEdicion = "{{url('datos tramite legalizacion')}}" + "/" + codTra;
                        $.ajax({
                            url: rutaEdicion,
                            type: 'GET',
                            success: function (vistaEdicion) {
                                _reemplazarPanel(panel, vistaEdicion, function(){
                                    var campoCi = $('#'+panel).find('input[name="ci"]').first();
                                    if(campoCi.length && !campoCi.prop('disabled') && !campoCi.prop('readonly')){
                                        setTimeout(function(){campoCi.trigger('focus');campoCi.trigger('select');},60);
                                    }
                                    finalizar();
                                });
                            },
                            error: function(){
                                cargarDatos(rutaEdicion,panel);
                                finalizar();
                            }
                        });
                        return;
                    }
                    _reemplazarPanel(panel, resp, function(){
                        cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                        finalizar();
                    });
                },
                error: function (data) {
                    $('#'+panel).html("<span class='text-white font-weight-bold bg-danger rounded p-1'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                    finalizar();
                }
            });
        }
        
        function guardarDatos(ruta,panel,form,btn){
            if(btn && btn.dataset.loading==='1'){
                return;
            }
            setBotonCargandoServicios(btn, true, 'Guardando...');
            var finalizar = function(){ setBotonCargandoServicios(btn, false); };

            $.ajax({
                url: ruta,
                type: 'POST',
                data:$('#'+form).serialize(),
                success: function (resp) {
                    if(resp && typeof resp === 'object' && resp.ok===true && resp.redirect){
                        $.ajax({
                            url: resp.redirect,
                            type: 'GET',
                            success: function(vista){
                                _reemplazarPanel(panel, vista, function(){
                                    cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                                    if(form==='form_traleg'){
                                        var areaTramite=$('#'+panel).find('#divNueTram');
                                        if(areaTramite.length){
                                            areaTramite.css({opacity: 0, marginTop: '30px', display: 'block'});
                                            setTimeout(function(){
                                                areaTramite.animate({opacity: 1, marginTop: '0px'}, 450, 'swing', function(){
                                                    var campoValorado=areaTramite.find('input[name="control"]').first();
                                                    if(!campoValorado.length){campoValorado=areaTramite.find('input[name="valorado"]:visible').first();}
                                                    if(campoValorado.length && !campoValorado.prop('disabled') && !campoValorado.prop('readonly')){
                                                        campoValorado.trigger('focus');campoValorado.trigger('select');
                                                    }
                                                    areaTramite.css('margin-top', ''); // Clean up inline styles
                                                });
                                            }, 80);
                                        }
                                        mostrarToastServiciosLeg('Trámite guardado correctamente.', 'ok');
                                    } else {
                                        mostrarToastServiciosLeg('Datos guardados correctamente.', 'ok');
                                    }
                                    finalizar();
                                });
                            },
                            error: function(){
                                $('#'+panel).html(resp.redirect ? '<span class="text-danger">No se pudo abrir el trámite recién creado.</span>' : '');
                                finalizar();
                            }
                        });
                        return;
                    }
                    _reemplazarPanel(panel, resp, function(){
                        cargarDatosTabla('{{url("ltl_ajax/".$fecha)}}','panel_tabla_tramites');
                        if(form==='form_traleg'){
                            enfocarCampoValoradoServicios(panel);
                            mostrarToastServiciosLeg('Trámite guardado correctamente.', 'ok');
                        } else if(form === 'form_apoderado_edi' || form === 'form_apoderado' || form === 'form_editar' || form === 'form_corregir_docleg' || form === 'form_g_obs_docleg') {
                            mostrarToastServiciosLeg('Datos guardados exitosamente.', 'ok');
                        }
                        finalizar();
                    });
                },
                error: function (resp) {
                    var texto='';
                    try{
                        var obj=resp.responseJSON;
                        if(obj && obj.errors){
                            $.each(obj.errors,function(k,v){texto+=v+'<br/>';});
                        } else if(obj && obj.message){
                            texto=obj.message;
                        }
                    }catch(e){}
                    if(!texto) texto='Error al guardar los datos.';
                    $('#error_datos_span').html(texto);
                    $('#error_datos').show();
                    setTimeout(function(){
                        $('#error_datos').hide(500);
                    },5000);
                    finalizar();
                }
            });
        }
        function cargarDatosTabla(ruta,panel){
            $.ajax({
                url: ruta,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#'+panel).html(resp);
                },
                error: function () {
                    $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
                }
            });
        }
    </script>
    <script>
        $('#dataTable').dataTable( {
            "pageLength": 500
        });
    </script>
@endsection
