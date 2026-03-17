    <?php $fecha=date('Y-m-d',strtotime($tramite->tra_fecha_solicitud))?>
    <div class="modal-content border-bottom-primary" xmlns="http://www.w3.org/1999/html">
        <div class="modal-header bg-primary">
            <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i> Legalización </h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span class="text-white" aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: smaller">
            @if(Session::has('exito'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold">{!! session('exito') !!}</span>
                </div>
            @endif
            <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow">
                <h6 class="text-white text-center">Formulario para editar legalización</h6>
            </div>
            {{$tipos_array}}
            <hr class="sidebar-divider"/>
            <div class="row">
                <div class="col-md-4">
                    <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Datos personales</span>
                        <div class="shadow-sm p-2 col-md-5 float-md-right">
                            <h1 class="text-danger pr-3 text-center">{{$tramite->tra_numero}}</h1>
                            <span class="font-italic text-dark text-center"><?php if($tramite->tra_fecha_solicitud!=''){echo date('d/m/Y',strtotime($tramite->tra_fecha_solicitud));} ?></span>
                        </div>
                    @if($tramite->per_ci=='')
                    <form id="form_traleg">
                        @csrf
                            <table class="table-hover col-md-12 text-dark">
                                <tr>
                                    <th class="text-right font-italic">CI : </th>
                                    <td class="border-bottom border-dark">

                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="ci" value="{{$tramite->per_ci}}" onchange="cargarDatosPersonales(this.value)" /></td>

                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Passaporte : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               name="pasaporte" value="{{$tramite->per_pasaporte}}" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Apellidos : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="apellido" id="apellido" value="{{$tramite->per_apellido}}" /></td>
                                </tr>
                                <tr>
                                    <th class="text-right font-italic">Nombres : </th>
                                    <td class="border-bottom border-dark">
                                        <input class="form-control form-control-sm border-0" placeholder=""
                                               required name="nombre" id="nombre" value="{{$tramite->per_nombre}}" /></td>
                                </tr>
                            </table>
                            <br/>
                            <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                            <input type="hidden" name="ip" value="{{$tramite->id_per}}">

                        </form>
                            @can('editar datos traleg - srv')
                                <button type="submit" class="btn btn-primary btn-sm float-md-right" onclick="guardarDatos('{{url("g_traleg")}}','panel_traleg','form_traleg')"> Guardar </button>
                            @endcan
                        @else
                        <table class="col-md-12 text-dark table table-sm">
                            <tr>
                                <th class="text-right font-italic">CI : </th>
                                <td class="border-bottom border-dark">{{$tramite->per_ci}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Passaporte : </th>
                                <td class="border-bottom border-dark">{{$tramite->per_pasaporte}}</td>
                            </tr>
                            <tr>
                                <th class="text-right font-italic">Nombre : </th>
                                <td class="border-bottom border-dark">{{$tramite->per_nombre." ".$tramite->per_apellido}}</td>
                            </tr>

                        </table>
                        @endif
                    <div>
                        <ul class="list-group-item-danger rounded">
                            @if(sizeof($ptaang)>0)
                                @foreach($ptaang as $p)
                                    <li class="text-darkr">Ya tiene
                                        @php echo \App\Models\Funciones::tipo_ptaang($p->dtra_ptaang)." Nº " @endphp
                                        <span class="font-weight-bold">{{$p->dtra_numero."/".$p->dtra_gestion}} </span> por PTAANG</li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                    <br/>
                    <div>
                        <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                        <div class="" id="apoderado">
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
                                        @if($tramite->tra_tipo_apoderado=='d')
                                            Declaración jurada
                                        @else
                                            @if($tramite->tra_tipo_apoderado=='p')
                                                Poder notariado
                                            @else
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            @endif
                                        @endif
                                    </td>
                                </tr>

                            </table>
                            @can('editar apoderado traleg - srv')
                                <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderado').show(500); $('#apoderado').hide(500);"> Editar datos</button>
                            @endcan
                        </div>
                        @can('editar apoderado traleg - srv')
                        <div id="editarApoderado" class="border rounded shadow" style="display: none;">
                            <div class="p-3">
                                <a onclick="$('#editarApoderado').hide(500);$('#apoderado').show(500); " id="ocultar" style="float:right">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                                <form id="form_apoderado_edi">
                                <br/><br/>
                                    @php
                                        $nombre='';    $apellido='';  $ci="";
                                        if($apoderado){   $ci=$apoderado->apo_ci;       $apellido=$apoderado->apo_apellido;     $nombre=$apoderado->apo_nombre;  }
                                    @endphp

                                    <table class="table-hover col-md-12">
                                        <tr>
                                            <th class="text-right font-italic">CI : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       name="ci" value="{{$ci}}" onchange="cargarDatosApoderado(this.value)"/></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Apellidos : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="apellido" id="apellido_apoderado" value="{{$apellido}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Nombres : </th>
                                            <td class="border-bottom border-dark">
                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                       required name="nombre" id="nombre_apoderado" value="{{$nombre}}" /></td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                            <td class="border-bottom border-dark">
                                                @if($tramite->tra_tipo_apoderado=='d')
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                @else
                                                    @if($tramite->tra_tipo_apoderado=='p')
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                                    @else
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                                    @endif
                                            @endif

                                        </tr>
                                    </table>
                                    <br/>
                                    <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                                    @csrf
                                </form>

                                    <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_edi','{{url("guardar apoderado")}}','panel_traleg');" >Guardar</a><br/>
                            </div>
                        </div>
                        @endcan
                    </div>
                </div>
                <!-- ================================LISTA DE DOCUMENTOS====================================-->
                <div class="col-md-8 pl-3">
                    <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Documentos del trámite</span>
                    <div>
                        @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <span class="font-weight-bold">{!! session('error') !!}</span>
                            </div>
                        @endif
                        <table class="col-md-12 table table-sm table-hover table-dark">
                            <tr class="bg-gradient-secondary text-white p-2">
                                <th>Nº</th>
                                <th>Sitra</th>
                                <!--<th>Estado</th>-->
                                <th>Nombre</th>

                                <th>Número trámite</th>
                                @if($tramite->tra_tipo_tramite=='B')
                                    <th>Documentos</th>
                                @endif
                                @if($tramite->tra_tipo_tramite=='F')
                                    <th>Documentos</th>
                                @else
                                    <th>Nº Título</th>
                                    <th colspan="4">Opciones</th>
                                @endif
                            </tr>
                            <?php $i=1;?>
                            @foreach($documentos as $d)
                                @if($d->dtra_falso=='t')
                                        <tr style="font-size: 10px" class="alert-danger border">
                                    @else
                                        @if($d->dtra_generado=='t')
                                        <tr style="font-size: 10px" class="alert-success border">
                                        @else
                                        <tr style="font-size: 10px" class="alert-light">
                                        @endif
                                    @endif
                                    <td>{{$i}}</td>
                                    <td>@if($d->dtra_verificacion_sitra=='0')
                                            <a href="#" class="btn btn-light btn-circle btn-sm text-success" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("verificacion sitra/".$d->cod_dtra)}}','panel_docleg')"
                                               title="Verificado en el sitra"><i class="fas fa-check-circle"></i>
                                            </a>
                                        @else
                                            @if($d->dtra_verificacion_sitra=='2')
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-danger" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("verificacion sitra/".$d->cod_dtra)}}','panel_docleg')"
                                                   title="No existe en el sitra"><i class="fas fa-minus-circle"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </td>
                                    <!--<td>if($d->dtra_estado_doc==0 || $d->dtra_estado_doc==4 )
                                            <div class="border border-success font-weight-bold text-success rounded pl-2" ><?php echo \App\Http\Controllers\TramiteLegalizacionController::estado($d->dtra_estado_doc)?></div>
                                        else
                                            <div class="border border-danger font-weight-bold text-danger rounded pl-2" ><?php echo \App\Http\Controllers\TramiteLegalizacionController::estado($d->dtra_estado_doc)?></div>
                                        endif
                                    </td>-->
                                    <td class="text-left">{{$d->tre_nombre}} @if($d->dtra_interno=='t') <span class="text-danger font-weight-bold">(Int.)</span> @endif</td>
                                    <td>

                                            {{$d->dtra_numero_tramite." / ".$d->dtra_gestion_tramite}}

                                    </td>

                                    @if($tramite->tra_tipo_tramite=='B')
                                                <td>
                                                    @foreach($confrontacion as $c)
                                                        @if($c->cod_dtra==$d->cod_dtra)
                                                            <span class="font-weight-bold font-italic"><?php echo  $c->dcon_doc; ?> </span><br/>
                                                        @endif
                                                    @endforeach
                                                </td>
                                    @endif
                                    @if($tramite->tra_tipo_tramite=='F')
                                        <td>
                                            @foreach($confrontacion as $c)
                                                <span class="font-weight-bold font-italic"><?php echo  \App\Http\Controllers\ConfrontacionController::nombreDocumento($c->dcon_doc); ?> </span><br/>
                                            @endforeach
                                        </td>
                                    @else
                                                <td class="text-left">
                                                    @if($d->dtra_numero==0)
                                                        {{"-/".substr($d->dtra_gestion,-2)}}</td>
                                                    @else
                                                        {{$d->dtra_numero."/".substr($d->dtra_gestion,-2)}}</td>
                                                    @endif

                                                <td class="text-right">
                                                    @if($d->dtra_generado=='t')
                                                        @can('deshacer generado glosa - srv')
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("fe_corregir_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Corregir tramite"><i class="fas fa-arrow-circle-left"></i> </a>
                                                        @endcan
                                                            @if($tramite->tra_tipo_tramite!='B')
                                                                @can('imprimir legalizacion docleg - srv')
                                                                    <a class="btn btn-light btn-sm btn-circle" data-target='#docleg' data-toggle="modal" onclick="cargarDatos('{{url("configurar impresion pdf leg/".$d->cod_dtra)}}','panel_docleg')"
                                                                       title="Ver Glosa"><i class="text-dark fas fa-file-pdf" ></i></a>
                                                                @endcan
                                                            @endif
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("ver documento pdf legalizado/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>

                                                    @else
                                                        @can('deshacer generado glosa - srv')
                                                            @if($tramite->tra_tipo_tramite=='L' ||$tramite->tra_tipo_tramite=='C')
                                                                <a href="#traleg" class="btn btn-light btn-circle btn-sm font-weight-bold"  onclick="cargarDatos('{{url("cambiar interno docleg/".$d->cod_dtra)}}','panel_traleg')"
                                                                   title="Cambiar destino de trámite">
                                                                    @if($d->dtra_interno=='t')
                                                                        <span class="text-danger">Int</span>
                                                                    @else
                                                                        <span class="text-primary">Ext</span>
                                                                    @endif
                                                                </a>
                                                            @endif
                                                        @endcan

                                                        @if($d->dtra_obs!='' || $d->dtra_falso=='t')
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("obs_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Observado"> <i class="fas fa-eye text-danger"></i></a>
                                                        @else
                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("obs_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                               title="Observado"> <i class="fas fa-eye"></i></a>
                                                        @endif
                                                            </a>
                                                            @if($d->dtra_falso!='t')
                                                                @can('generar glosa docleg - srv')
                                                                    @if($tramite->tra_tipo_tramite=='B' || $d->dtra_solo_sello=='t')

                                                                        <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("busqueda doc encontrado/".$d->cod_dtra)}}','panel_docleg')"
                                                                           title="Registrar verificación"><i class="fas fa-file-signature"></i>
                                                                        </a>
                                                                    @else

                                                                            <a href="#" class="btn btn-light btn-circle btn-sm text-dark" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("generar glosa_leg/".$d->cod_dtra)}}','panel_docleg')"
                                                                                title="Generar glosa"><i class="fas fa-file-signature"></i>
                                                                            </a>

                                                                   @endif
                                                                @endcan
                                                                @if($d->dtra_tipo!='E')
                                                                    <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("ver documento pdf legalizado/".$d->cod_dtra)}}','panel_docleg')"
                                                                       title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>
                                                                @endif
                                                                @can('eliminar docleg - srv')
                                                                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('{{url("f_eli_docleg/".$d->cod_dtra)}}','panel_docleg')"
                                                                       title="Eliminar"> <i class="fas fa-trash-alt"></i>
                                                                    </a>
                                                               @endcan
                                                            @endif
                                                    @endif
                                                </td>
                                            @endif
                                </tr>
                                <?php $i++;?>
                            @endforeach
                        </table>
                    </div>
                    @can('crear docleg - srv')
                    <!--Solo cuando es BUSQUEDA SE MUESTRA EL FORMULARIO-->
                    @if($tramite->id_per!='' && $tramite->tra_tipo_tramite=='B')
                        <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Trámite</button><br/>
                        <div class="shadow-sm border col-md-10 float-right" id="divNueTram" style="display: none">
                            <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                                <i class="fas fa-minus-circle text-danger"></i></a>
                            <br/>
                            <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                                    <span id="error_datos_span"></span>
                            </div>
                                <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                    <h6 class="text-dark text-center font-weight-bold">Añadir documento para Búsqueda</h6>
                                </div>
                            <br/>
                            <div class="col-md-11 float-right">
                                <form id="form_docleg">
                                    @csrf
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic">Trámite : </th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                    @foreach($lista_tramites as $l)
                                                        <option value="{{$l->cod_tre}}">{{$l->tre_nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic"> Nº control valorado: </th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group">
                                                    <input type="text" class=" form-control form-control-sm" name="control" required>
                                                    &nbsp;&nbsp;<span class="font-italic font-weight-bold"> Nro. control Reimpresión : </span>&nbsp;&nbsp;
                                                    <input class="form-control form-control-sm" name="reimpresion" />
                                                    &nbsp;&nbsp;&nbsp;&nbsp;<span class="font-italic text-dark font-weight-bold"> CUADIS :
                                                            <input type="checkbox" name="cuadis" />
                                                        </span>&nbsp;&nbsp;
                                                </div>
                                            </td>
                                        </tr>
                                        <tr><th class="text-right font-italic">Nro. Título:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group ">
                                                    <input name="numero" required class="form-control col-md-2 form-control-sm border " pattern="[0-9]{1,6}"> &nbsp;&nbsp;
                                                    / &nbsp;&nbsp;<input name="gestion" required class="form-control col-md-2 form-control-sm border" pattern="[0-9]{1,4}"> &nbsp;&nbsp;(e.j. 1999)
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic">Buscar en :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0" required name="buscar_en">
                                                    <option value="db">DB</option>
                                                    <option value="ca">CA</option>
                                                    <option value="da">DA</option>
                                                    <option value="tp">TP</option>
                                                    <option value="di">DI</option>
                                                    <option value="tpos">TPOS</option>
                                                    <option value="re">RE</option>
                                                    <option value="su">SU</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr><th class="text-right font-italic">
                                                <span class="text-dark font-weight-bold font-italic" style="font-size: 0.9em"> Documentos : </span>
                                            </th>
                                            <td>
                                                <textarea name="documentos" class="form-control form-control-sm" required></textarea>
                                            </td>
                                        </tr>
                                    </table>
                                    <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                                    <input type="hidden" name="tipo_tramite" value="t">
                                </form>
                                <a href="#" class="btn btn-sm btn-primary float-right mr-4" onclick="enviar1('form_docleg','{{url('g_docleg')}}','panel_traleg')"
                                   title="Editar legalización">+ Crear </a>
                                <br/><br/>
                            </div>
                        </div>
                    @endif

                    @if($tramite->id_per!='' && ($tramite->tra_tipo_tramite=='L' || $tramite->tra_tipo_tramite=='C' || $tramite->tra_tipo_tramite=='E' ))
                        <br/>
                    <hr class="sidebar-divider"/>
                        <!--==============================Añadir Documentos=================-->
                    <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Trámite</button><br/>
                    <div class="shadow-sm" id="divNueTram" style="display: none">

                        <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                            <i class="fas fa-minus-circle text-danger"></i></a>
                        <br/>
                        <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                            <span id="error_datos_span"></span>
                        </div>
                            <div>
                                <div class="alert-primary centrar_bloque p-1 col-md-7 rounded shadow">
                                    <h6 class="text-dark text-center font-weight-bold">Añadir documento</h6>
                                </div>

                                <form id="form_docleg">
                                    @csrf
                                    <table>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de legalización :</th>
                                            <td class="border-bottom border-dark">
                                                <select class="custom-select custom-select-sm border-0 " name="tipo">
                                                    @foreach($lista_tramites as $l)
                                                        <option value="{{$l->cod_tre}}">{{$l->tre_nombre}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Tipo de trámite :</th>
                                            <td class="border-bottom border-dark">
                                                <input type="radio" name="tipo_tramite" checked value="f"> EXTERNO  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="tipo_tramite" value="t"> INTERNO
                                                &nbsp;&nbsp;
                                                <span class="font-weight-bold text-danger" style="font-size: 20px">|</span>
                                                &nbsp;&nbsp;
                                                @if($tramite->tra_tipo_tramite=='L')
                                                    <span class="font-weight-bold text-dark font-italic">&nbsp;&nbsp;PTAG : &nbsp;&nbsp;
                                                            <input type="checkbox" name="ptaang">
                                                        </span>
                                                @endif
                                                &nbsp;&nbsp;<span class="font-italic text-dark font-weight-bold"> CUADIS :
                                                            <input type="checkbox" name="cuadis" />
                                                        </span>&nbsp;&nbsp;
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Título o Resolución:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group ">
                                                    &nbsp;&nbsp;  &nbsp;&nbsp;<input name="numero" class="form-control col-md-2 form-control-sm border "> &nbsp;&nbsp;
                                                    / &nbsp;&nbsp;<input name="gestion" required class="form-control col-md-2 form-control-sm border" pattern="[0-9]{1,4}"> &nbsp;&nbsp;(e.j. 1999)
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                                    <span class="font-weight-bold text-dark float-right">
                                                        Supletorio : <input type="checkbox" name="supletorio">
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">Nro. Control:</th>
                                            <td class="border-bottom border-dark input-group">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm border-0" required name="control" />
                                                    <span class="text-primary font-weight-bold font-italic"> Reintegro : &nbsp;</span>
                                                    <input class="form-control form-control-sm border" required name="reintegro" />
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-right font-italic ">N° control Búsqueda:</th>
                                            <td class="border-bottom border-dark">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm" name="valorado_bus" />
                                                    &nbsp;&nbsp;<span class="font-italic font-weight-bold"> Nro. control Reimpresión : </span>&nbsp;&nbsp;
                                                    <input class="form-control form-control-sm" name="reimpresion" />
                                                </div>
                                            </td>

                                        </tr>
                                    </table>
                                    <input type="hidden" name="ctra" value="{{$tramite->cod_tra}}">
                                </form>
                                <br/>
                                <a href="#" class="btn btn-sm btn-primary float-right mr-4" onclick="enviar1('form_docleg','{{url('g_docleg')}}','panel_traleg')"
                                   title="Editar legalización">+ Crear </a>
                                <br/><br/>
                            </div>
                    </div>
                    @endif
                    @endcan
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>


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

    </script>

