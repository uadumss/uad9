@extends('marco/pagina')
@section('contenido')
    @if(Session::has('exito'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exito') !!}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('error') !!}
        </div>
    @endif
    @if(count($errors)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($errors->all() as $e)
                    <li>{{$e}} - </li>
                @endforeach
            </ul>
        </div>
    @endif
    <?php $gestion=$tomo['tom_gestion'];
        $tipo=$tomo['tom_tipo'];?>
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="row">
                <div class="col-md-6">
                    <h5 class=""><i class="fas fa-file"></i>&nbsp;&nbsp;{{$tipo_completo}}  {{$gestion}}
                    @if($tomo->tom_numero==0)
                            <span class="text-danger border border-danger rounded font-italic p-2 mr-3 font-weight-bold" style="font-size: 0.8em">* Títulos sin tomo</span> &nbsp; | &nbsp;
                    @endif
                    </h5>
                </div>
                <div class="col-md-6">
                    @can('crear titulo - dyt')
                        @if($tomo['tom_cerrado']!='t')
                            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm float-right" data-target="#nuevoTomo" data-toggle="modal">
                                <i class="fas fa-plus"></i> Título</a>&nbsp;&nbsp;
                        @endif
                    @endcan
                        @if($tomo['tom_numero']!=0 && $tomo['tom_cerrado']!='t')
                        <a href="#" class="btn btn-sm btn-primary float-right mr-2" data-target="#verObs" data-toggle="modal" onclick="cargarDatos('{{url("l_sintomos/".$gestion."/".$tipo."/".$tomo['cod_tom'])}}','p_observacion','0')">
                            Títulos sin tomos</a>&nbsp;&nbsp;
                        @endif

                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="input-group col-md-12">
                <a href="{{url('tomo/'.$tipo."/".$tomo['tom_gestion'])}}" class="btn btn-outline-info btn-sm text-dark mt-1 shadow-sm"><i class="fas fa-arrow-alt-circle-left"></i> Atrás</a>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>

                <div class="topbar-divider d-none">&nbsp;</div>
                    <div class="col-md-2 input-group shadow-sm p-1" style="font-size: 0.9em; ">
                        <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Buscar Gestión :&nbsp; &nbsp;</span>
                        <select class="form-control form-control-sm col-md-4 border border-info"  name="gestion" onchange="$(location).attr('href','{{url("tomo/$tipo")}}'+'/'+this.value);">
                            <option value="{{$gestion}}">{{$gestion}}</option>
                            <?php $año=date('Y');?>
                            @for($i=$año;$i>1927;$i--)
                                <option value="{{$i}}">{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <div class="float-left shadow-sm p-1">
                    <div class="input-group">
                        <span class="text-dark pt-1 font-weight-bold" style="font-size: .9em;"> Buscar tomo :&nbsp; &nbsp;</span>
                        <select class="form-control form-control-sm boder border-info"  name="gestion" onchange="$(location).attr('href','{{url("l_titulo")}}'+'/'+this.value);">
                            <option value="" disabled selected hidden></option>
                            @foreach($listaTomo as $lt)
                                @if($lt['tom_numero']!=0)
                                    <option value="{{$lt['cod_tom']}}">{{$lt['tom_numero']}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>

                <div class="float-right pt-1">
                    @if($tomo->tom_cerrado=='t')
                        <span class="text-danger p-2 font-italic font-weight-bold" style="font-size: 0.9em">* Consolidado</span>
                    @endif
                    @if($tomo->tom_numero!=0)
                        <span class="text-dark font-weight-bold pt-1" style="font-size: .9em;">Nro. Tomo : </span> <span class="text-dark">
                            {{$tomo['tom_numero']}} </span>
                        <a href="#" class="btn btn-sm btn-outline-info text-dark" data-target="#datosTomo" data-toggle="modal">
                            Mas detalles ...
                        </a>

                    @else
                            @can('imprimir listado tomo - dyt')
                                <a href="{{url('imprimir lista/'.$tomo->cod_tom)}}" target="lista{{$tomo->tom_numero}}" class="btn btn-outline-info btn-sm text-dark font-weight-bold" title="Imprimir lista">
                                    <i class="fas fa-file-excel text-success">  </i> Importar Excel
                                </a>
                            @endcan
                                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                                <a href="" class="btn btn-sm btn-outline-danger" id="tomo-a" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Asignar tomo</a>
                                <div class="shadow-lg shadow">
                                    <div class="dropdown-menu shadow-lg" aria-labelledby="tomo-a" >
                                        <div class="bg-primary centrar_bloque p-1 m-3 rounded">
                                            <h6 class="text-white text-center">Asignación de títulos a tomo </h6>
                                        </div>
                                        <hr class="sidebar-divider"/>
                                        <div class="p-2">
                                            <form id="form-rango">
                                                @csrf
                                                <table class="">
                                                    <tr class="border-white">
                                                        <td><span class="font-italic text-dark font-weight-bold">Hasta N°</span></td>
                                                    </tr>
                                                    <tr class="border-white">
                                                            <td><input class="form-control-sm form-control" pattern="[0-9]{5}" type="text" name="final"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="font-italic text-dark font-weight-bold">Número de tomo</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td><input class="form-control-sm form-control" pattern="[0-9]{5}" type="text" name="tomo"></td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="ct" value="{{$tomo['cod_tom']}}">
                                            </form>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#verObs" onclick="enviar('form-rango','{{url("f_asignar rango tomo")}}','p_observacion')">Asignar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                    @endif
                </div>
            </div>
            <hr class="sidebar-divider"/>
            <div>
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                            <h6 class="text-white text-center">{{$tipo_completo}}</h6>
                        </div>
                        <span class="font-weight-bold text-dark" style="font-size: 0.9em"> Cantidad de títulos : </span><span style="font-size: 0.9em">{{sizeof($titulo)}} </span>
                        <hr class="sidebar-divider"/>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover border border-right" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
                                    <th class="border border-right">Nº</th>
                                    <th>Nombre</th>
                                    <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){ ?>
                                        <th>Facultad</th>
                                        <th>Carrera</th>
                                    <?php } ?>
                                    <?php if($tipo=='tpos' || $tipo=='di'){ ?>
                                        <th>Mención</th>
                                    <?php } ?>
                                    <th>Número</th>
                                    <th>Fecha</th>
                                    <th>Folio</th>
                                    <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){ ?>
                                        <th>Modalidad</th>
                                    <?php }?>
                                    <th>Título</th>
                                    <th>Antecedente</th>
                                    <th>Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $i=1;?>
                                @foreach($titulo as $t)
                                    <tr id="fila{{$i}}" style="font-size: 0.9em" class="text-dark">
                                        <td class="border border-right">{{$i}}
                                        </td>
                                        <td class="border border-right" id="ape{{$i}}">
                                            
                                            @if(\Illuminate\Support\Facades\Auth::user()->id==$t->tit_usr)
                                                <span style="font-size: 0.8em" class="border border-success rounded text-success font-weight-bold">&nbsp;Tú&nbsp;</span>
                                            @endif
                                            {{$t->per_apellido." , ".$t->per_nombre}}<br/>
                                            <?php if($tipo=='tp'){?>
                                                <?php if($t->tit_revalida=='t'){?>
                                                <span class="bg-danger text-white p-1 rounded font-weight-bolder lead" style="font-size: 0.8em;" >Reválida</span>
                                                <?php }?>
                                            <?php }?>

                                            <?php if($tipo=='re'){?>
                                                <?php if($t->tit_reconocimiento=='t'){?>
                                                <span class="border border-danger text-danger p-1 rounded font-italic font-weight-bolder lead" style="font-size: 0.7em;" >Reconocimiento.</span>
                                                <?php }?>
                                            <?php }?>
                                        </td>
                                        <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){ ?>
                                            <td class="border border-right" id="fac{{$i}}">{{$t->fac_abreviacion}}</td>
                                            <td class="border border-right" id="carr{{$i}}">{{$t->car_abreviacion}}</td>
                                        <?php }?>
                                        <?php if($tipo=='tpos' || $tipo=='di'){ ?>
                                            <td class="border border-right" id="men{{$i}}">{{$t->tit_titulo}}</td>
                                        <?php } ?>
                                        <td class="border border-right" id="nro{{$i}}" class="text-right">{{$t->tit_nro_titulo}}</td>
                                        <td class="border border-right" id="fec{{$i}}" class="text-right">{{date('d/m/Y',strtotime($t->tit_fecha_emision))}}</td>
                                        <td class="border border-right" id="fol{{$i}}" class="text-right">{{$t->tit_nro_folio}}</td>
                                        <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){ ?>
                                            <td class="border border-right" id="mod{{$i}}" class="text-right">{{$t->mod_nombre}}</td>
                                        <?php }?>

                                        <td class="border border-right" id="doc{{$i}}" class="text-right text-danger">
                                            @if($t->tit_pdf!='')
                                                <img src="{{url('img/icon/tit.gif')}}" width="30" height="30">
                                            @endif
                                        </td>
                                        <td class="border border-right" id="ant{{$i}}" class="text-right text-danger">
                                            @if($t->tit_antecedentes!='')
                                                    <img src="{{url('img/icon/antecedente.gif')}}" width="30" height="30">
                                            @endif
                                        </td>
                                        <td class="text-right" class="border border-right">
                                            @can('editar titulo - dyt')
                                                <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#editarTitulo" data-toggle="modal" onclick="cargarDatosTitulo('{{url("fe_titulo/".$t->cod_tit)}}','panel_editar',{{$i}})"
                                                    title="Editar titulo">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                                @can('cambiar titulo a tomo - dyt')
                                                    <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verObs"
                                                    onclick="cargarDatos('{{url('f_cambiar a tomo/'.$t->cod_tit)}}','p_observacion')" title="Cambiar de tomo"> <i class="fas fa-book"></i></a>
                                                @endcan
                                            <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verDatos"
                                               onclick="verDatos('{{url('ver datos/'.$t->cod_tit)}}','p_detalle')" title="Ver detalle del titulo"> <i class="fas fa-arrow-right"></i></a>
                                            <!--===================================PREGUNTA OBSERVACIONES-->

                                            @if(($t->tit_obs)>0)
                                                <a id='obs{{$i}}' href="" class="btn btn-circle btn-light btn-sm text-danger" data-toggle="modal" data-target="#verObs"
                                                   onclick="verDatos('{{url('ver obs/'.$t->cod_tit)}}','p_observacion',{{$i}})" title="Ver observaciones"> <i class="fas fa-eye"></i></a>
                                            @else
                                                <a id='obs{{$i}}' href="" class="btn btn-circle btn-light btn-sm text-primary font-weight-bold" data-toggle="modal" data-target="#verObs"
                                                   onclick="verDatos('{{url('ver obs/'.$t->cod_tit)}}','p_observacion',{{$i}})" title="Ver observaciones"><i class="fas fa-eye-slash"> </i></a>
                                            @endif

                                            @can('eliminar titulo - dyt')
                                                <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#eliminarTitulo" data-toggle="modal" onclick="obtenerTitulo({{$t->cod_tit}})"
                                                   title="Eliminar Titulo"><i class="fas fa-trash-alt"></i>
                                                </a>
                                            @endcan
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
    <!--========================================MODAL NUEVO TITULO================-->
    <div class="modal fade" id="nuevoTomo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-xl" role="document">
            <form action="{{url('g_titulo')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i>&nbsp;&nbsp;{{$tipo_completo." - ".$gestion}}</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="justify-content-center">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="bg-primary centrar_bloque p-1 col-md-5 rounded shadow">
                                        <h6 class="text-white text-center">Formulario para nuevo título</h6>
                                    </div>
                                    <hr class="sidebar-divider"/>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <span class="text-primary font-weight-bold float-right">DATOS DEL TITULO</span>
                                            <table class="col-md-12">
                                                <tr>
                                                    <th class="text-right font-italic">Nº título:</th>
                                                    <td class="border-bottom border-dark">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}" required name="nro" />
                                                            @if($tipo=='re')
                                                                <span class="text-danger font-weight-bold pt-1" style="font-size: 0.8em">Reconocimiento</span>&nbsp;&nbsp;
                                                                <input type="checkbox" name="reconocimiento" class="" />
                                                            @endif
                                                        </div>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Fecha:</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="date" class="form-control form-control-sm border-0" required name="fecha" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Grado :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="form-control border-0 form-control-sm" name="grado">
                                                            @foreach($grado as $g)
                                                                <option value="{{$g}}">{{$g}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                                                <tr>
                                                    <th class="text-right font-italic">Carrera: </th>
                                                    <td class="border-bottom border-dark">
                                                        <div class="row">
                                                            <div id="fila_car" class="col-md-11">
                                                                <select class="custom-select custom-select-sm" name="car" id="car">
                                                                    @foreach($carrera as $c)
                                                                        <option value="{{$c->cod_car}}">{{$c->fac_abreviacion." - ".$c->car_nombre}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){?>
                                                                <a href='#' class="btn btn-sm btn-info btn-circle btn-info ml-1" data-toggle="modal" data-target="#verObs"
                                                                   onclick="verDatos('{{url('añadir carrera tomo/'.$tomo["cod_tom"].'/fila_car')}}','p_observacion','')" title="Añadir Carrera">
                                                                    <i class="fas fa-plus"></i>
                                                                </a>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <tr>
                                                    <th class="text-right font-italic">Nº folio:</th>
                                                    <td class="border-bottom border-dark">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}" name="folio" />
                                                            <span class="text-primary font-weight-bold" style="font-size: 0.9em">Fecha Folio. </span>&nbsp;&nbsp;
                                                            <input type="date" class="form-control form-control-sm border-0" name="fecha_folio" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Título en PDF:</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Antecedentes en PDF:</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="file" class="form-control form-control-sm border-0" accept=".pdf" name="pdf_ant" />
                                                    </td>
                                                </tr>
                                                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa' || $tipo=='tpos' || $tipo=='di' || $tipo=='re' || $tipo=='db' ){?>
                                                <tr>
                                                    <th class="text-right font-italic">Título:</th>
                                                    <td class="border-bottom border-dark">
                                                        <textarea rows="2" class="form-control-sm form-control border-0" name="titulo"></textarea>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <?php if($tipo=='su'){?>
                                                <tr>
                                                    <th class="text-right font-italic">Referencia A:</th>
                                                    <td class="border-bottom border-dark">
                                                        <textarea rows="2" class="form-control-sm form-control border-0" name="ref"></textarea>
                                                    </td>
                                                </tr>
                                                <?php }?>
                                                <tr>
                                                    <th class="text-right font-italic">Observación :</th>
                                                    <td class="border-bottom border-dark">
                                                        <textarea rows="2" class="form-control-sm form-control border-0" name="obs"></textarea>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    <div class="col-md-6">
                                        <span class="text-primary font-weight-bold float-right">DATOS PERSONALES</span>
                                        <table class="col-md-12">
                                            <tr>
                                                <th class="text-right font-italic">Nº CI:</th>
                                                <td class="border-bottom border-dark">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control form-control-sm border-0" name="ci" onchange="cargarDatosPersonales(this.value)" />

                                                        <span class="text-danger font-weight-bold" style="font-size: 0.9em">Exp. </span>&nbsp;&nbsp;
                                                        <select name="expedido" class="custom-select-sm custom-select col-md-4" id="expedido">
                                                            <option value=""></option>
                                                            <option value="CB">CB</option>
                                                            <option value="LP">LP</option>
                                                            <option value="SC">SC</option>
                                                            <option value="PT">PT</option>
                                                            <option value="OR">OR</option>
                                                            <option value="TA">TA</option>
                                                            <option value="BE">BE</option>
                                                            <option value="PA">PA</option>
                                                            <option value="CH">CH</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Nº passaporte:</th>
                                                <td class="border-bottom border-dark">
                                                    <input type="text" class="form-control form-control-sm border-0" name="pass" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Apellidos:</th>
                                                <td class="border-bottom border-dark">
                                                    <input type="text" class="form-control form-control-sm border-0" name="apellido" id="apellido"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Nombres:</th>
                                                <td class="border-bottom border-dark">
                                                    <input type="text" class="form-control form-control-sm border-0" name="nombre" id="nombre" />
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Sexo:</th>
                                                <td class="border-bottom border-dark">
                                                    <select class="form-control border-0 form-control-sm" name="sexo" id="sexo">
                                                        <option value="M">MASCULINO</option>
                                                        <option value="F">FEMENINO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-right font-italic">Nacionalidad:</th>
                                                <td class="border-bottom border-dark">
                                                    <select class="form-control border-0 form-control-sm" name="nac">
                                                        <option value="29">Bolivia</option>
                                                        @foreach($nacionalidad as $n)
                                                            <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa' || $tipo=='tpos' || $tipo=='di'){?>
                                            <tr>
                                                <th class="text-right font-italic">Modalidad:</th>
                                                <td class="border-bottom border-dark">
                                                    <select class="form-control border-0 form-control-sm" name="mod" id="mod" onchange="habilitarMod($('#mod option:selected').text())">
                                                        @foreach($modalidad as $m)
                                                            <option value="{{$m['cod_mod']}}">{{$m['mod_nombre']}}</option>
                                                        @endforeach
                                                    </select>

                                                    <div id="otraMod" style="display: none">
                                                        <input type="text" class="form-control-sm form-control border border-primary text-danger" name="otra_modalidad" placeholder="Ingrese la modalidad">
                                                    </div>
                                                    <script>
                                                        function habilitarMod(valor){
                                                            if(valor=='Otro...'){
                                                                $('#otraMod').show(250);
                                                            }else{
                                                                $('#otraMod input').val("");
                                                                $('#otraMod').hide(250);
                                                            }
                                                        }
                                                    </script>
                                                </td>
                                            </tr>

                                            <?php }?>
                                        </table>

                                        <?php if($tipo=='re' || $tipo=='tp'){?>
                                        <hr class="sidebar-divider"/>
                                        <?php if($tipo=='tp'){?>
                                        <input type="hidden" name="revalida" id="revalida" value="f">
                                        <a onclick="verRevalida()" class="btn btn-primary" data-toggle="collapse" href="#div_revalida" id="btn_revalida" role="button" aria-expanded="false" aria-controls="collapseExample">
                                            Reválida
                                        </a>
                                        <div class="collapse" id="div_revalida">
                                            <?php } else {?>
                                            <input type="hidden" name="revalida" id="revalida" value="t">
                                            <div class="" id="div_revalida">
                                                <?php }?>
                                                <span class="text-primary font-weight-bold float-right">DATOS DE REVÁLIDA</span>
                                                <br/>
                                                <table class="col-md-12">
                                                    <tr>
                                                        <th class="text-right font-italic">País de origen:</th>
                                                        <td class="border-bottom border-dark">
                                                            <select class="form-control border-0 form-control-sm" name="pais_origen">
                                                                <option value="0"></option>
                                                                @foreach($nacionalidad as $n)
                                                                    <option value="{{$n['cod_nac']}}">{{$n['nac_nombre']}}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic">Universidad:</th>
                                                        <td class="border-bottom border-dark">
                                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" name="universidad" />
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic">Otorgado el:</th>
                                                        <td class="border-bottom border-dark">
                                                            <input type="date" class="form-control form-control-sm border-0 col-md-12" name="fecha_revalida" />
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                        <input class="btn btn-primary" type="submit" value="Guardar"/>
                    </div>
                    </div>
                    </div>
                        <input type="hidden" name="tipo" value="{{$tipo}}">
                        <input type="hidden" name="ct" value="{{$tomo['cod_tom']}}"/>
                </div>
            </form>
        </div>
    </div>
    <!--================================ END?===============================-->

    <!--=================================EDITAR TITULO========================-->
    <div class="modal fade" id="editarTitulo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-book"></i>&nbsp;&nbsp;{{$tipo_completo ." - ".$gestion}}</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-danger alert-dismissible" id="panel_error_archivo" style="display: none">
                            <div id="error_archivo">
                                * Ocurrio un error desconocido, revise todos los datos y asegurece que :
                                <ul>
                                    <li>El título en PDF deben ser menor a 2048 KB</li>
                                    <li>Los antecedentes en PDF deben ser menor a 20500 KB</li>
                                </ul>
                            </div>
                        </div>
                        <form action="{{url('g_titulo')}}" method="POST" id="form_editar" enctype="multipart/form-data">
                            @csrf
                            <div id="panel_editar">

                            </div>
                        </form>
                        <input type="hidden" name="fila" id="fila" value="0">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                        <button class="btn btn-primary" type="button" id="g_tit" onclick="enviarTitulo()">Guardar</button>
                    </div>

                </div>

        </div>
    </div>
    <!--=============================END==================================-->

    <!--=============================MODAL DATOS DE TOMO==================================-->
    <div class="modal fade" id="datosTomo" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" id="panel_editar">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white" id="exampleModalLabel"> &nbsp;<i class="fas fa-book"></i> &nbsp;Datos del tomo </h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if($tomo->tom_cerrado=='t')
                                <span class="text-danger p-2 font-italic font-weight-bold float-right" style="font-size: 0.9em">* Este tomo ya está consolidado, no se puede modificar</span>
                            @endif
                            <table class="col-md-12">
                                <tr>
                                    <th class="text-primary font-italic col-md-2">Nº de tomo :</th>
                                    <td class="border-bottom border-dark">
                                        {{$tomo['tom_numero']}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary font-italic">Gestión :</th>
                                    <td class="border-bottom border-dark">
                                        {{$tomo['tom_gestion']}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary font-italic">Tipo de tomo :</th>
                                    <td class="border-bottom border-dark">
                                        {{$tipo_completo}}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-primary font-italic">Rango :</th>
                                    <td class="border-bottom border-dark">
                                        {{$tomo['tom_rango']}}
                                    </td>
                                </tr>

                            </table>
                            <br/>
                            <div>

                                @if($tomo->tom_obs!='')
                                    <span class="font-italic font-weight-bold text-primary"> Observaciones :</span><br/>
                                    <div class="font-weight-bold alert-danger rounded p-2">
                                        {{$tomo['tom_obs']}}
                                    </div>
                                @endif
                            </div>
                                <br/>
                            <div>
                                <span class="font-italic font-weight-bold text-primary"> Carreras :</span>
                                @if(sizeof($tomo_carrera)>0)
                                <table class="table table-sm">
                                    <tr>
                                        <th>Facultad :</th>
                                        <th>Carrera</th>
                                    </tr>
                                    @foreach($tomo_carrera as $tc)
                                        <tr>
                                            <td>{{$tc->fac_nombre}}</td>
                                            <td>{{$tc->car_nombre}}</td>
                                        </tr>
                                    @endforeach
                                </table>
                                @else
                                    <span class="font-italic font-weight-bold text-danger" style="font-size: 0.8em;">No hay carreras asignadas</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--=================================MODAL VER TITULO ============================-->
    <div class="modal fade" id="verDatos" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Título</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span class="text-white" aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
                        <h6 class="text-white text-center">Detalle del título</h6>
                    </div>
                    <div id="p_detalle">

                    </div>
                </div>
                <input type="hidden" name="fila_obs" id="fila_obs" value="0">
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--================================ END?===============================-->
    <!--=================================MODAL VER OBSERVACIONES ============================-->
    <div class="modal fade" id="verObs" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-xl" role="document" id="p_observacion">

        </div>
    </div>
    <!--================================ END?===============================-->

    <!-- ================== MODAL ELIMINAR TITULO-->
    <div class="modal fade" id="eliminarTitulo"  style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-bottom-danger">
                <form action="{{url('e_titulo')}}" method="post">
                    @csrf
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title text-white" id="exampleModalLabel"> <img src="{{url('img/icon/eliminar.png')}}">&nbsp;&nbsp;Eliminar título</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <span class="font-italic">Esta seguro de eliminar el titulo :</span> <br/><br/>
                        <div class="row">
                            <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-9 p-3" id="panel_e_titulo">

                            </div>
                            <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
                        </div>
                        <br/>
                        <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                        <input class="btn btn-danger" type="submit" value="Aceptar" />
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- =============================== ====================-->
    <script>
        function enviarTitulo(){
            var link = "{{url('g_titulo/')}}";
            var token = "{{csrf_token()}}";
            var form = new FormData($('#form_editar').get(0));
            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                contentType: false,
                processData: false,
                data:form,
                //data:$('#form_editar').serialize(),
                success: function (resp) {
                    $('#panel_editar').html(resp);

                    var fila=$('#fila').val();
                    var tipo="{{$tipo}}";
                    @if($tipo=='re')
                        if($('#form_editar #reconocimiento').prop('checked')==true){
                            $('#ape'+fila).html($('#form_editar #e_ape').val()+" , "+$('#form_editar #e_nom').val()+
                                "<br/><span class=\"border border-danger text-danger p-1 rounded font-italic font-weight-bolder lead\" style=\"font-size: 0.7em;\" >Reconocimiento.</span>");
                        }else{
                            $('#ape'+fila).html($('#form_editar #e_ape').val()+" , "+$('#form_editar #e_nom').val());
                        }
                    @else
                        $('#ape'+fila).html($('#form_editar #e_ape').val()+" , "+$('#form_editar #e_nom').val());
                    @endif
                    $('#nro'+fila).html($('#form_editar #e_nro').val());
                    var fecha=$('#form_editar #e_fec').val().split('-');
                    $('#fec'+fila).html(fecha[2]+'/'+fecha[1]+'/'+fecha[0]);
                    $('#fol'+fila).html($('#form_editar #e_fol').val());

                    if(tipo=='ca' || tipo=='da' || tipo=='tp' || tipo=='tpa'){
                        $('#carr'+fila).html($('#form_editar #e_car option:selected').text());
                        $('#fac'+fila).html($('#form_editar #e_fac').val());
                        $('#mod'+fila).html($('#form_editar #e_mod option:selected').text());
                    }
                    if($('#form_editar #pdf_val').val()=='1'){
                        $('#doc'+fila).html("<img src='{{url('img/icon/tit.gif')}}' width='30' height='30'/>");
                    }
                    if($('#form_editar #pdf_val_ant').val()=='1'){
                        $('#ant'+fila).html("<img src='{{url('img/icon/antecedente.gif')}}' width='30' height='30'/>");
                    }
                    if(tipo=='di' || tipo=='tpos'){
                        $('#men'+fila).html($('#form_editar #e_tit').val());
                    }
                },
                error: function (data) {
                    $('#panel_error_archivo').show();
                }
            });
        }

        function verRevalida(){
            if($("#revalida").val()=='t'){
                $("#revalida").val('f');
                $("#btn_revalida").removeAttr('class');
                $("#btn_revalida").addClass('btn btn-primary');
                $("#btn_revalida").html('Reválida');
            }else{
                $("#revalida").val('t');
                $("#btn_revalida").removeAttr('class');
                $("#btn_revalida").addClass('btn btn-danger');
                $("#btn_revalida").html('Cancelar');
            }
        }
        function obtenerTitulo(id){
            var link="{{url('f_eli_titulo/')}}"+"/"+id;
            $('#panel_e_titulo').html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
            $.ajax({
                url: link,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#panel_e_titulo').html(resp);
                },
                error: function (resp) {
                    $('#panel_e_titulo').html("Ocurrio un error, probablemente no tenga permisos para esta acción");
                }
            });
        }
        function cargarDatosPersonales(ci){
            var link="{{url('datos_per/')}}"+"/"+ci;
            $.ajax({
                url: link,
                type: 'GET',
                success: function (resp) {
                    if(resp=="No"){
                        $('#apellido').val('');
                        $('#nombre').val('');
                        $('#expedido').val('');
                    }else{
                        var res=JSON.parse(resp);
                        $('#apellido').val(res['per_apellido']);
                        $('#nombre').val(res['per_nombre']);
                        $('#expedido').val(res['per_ci_exp']);
                        $('#sexo').val(res['per_sexo']);
                    }
                },
                error: function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
        function verDatos(url,panel,fila){
            $('#'+panel).html("<br/><br/><div class='d-flex justify-content-center text-danger'><div class='spinner-border' role='status'> <span class='visually-hidden'></span></div></div>");
            $.ajax({
                url:url,
                type:'GET',
                data:'',
                success:function (resp) {
                    $('#'+panel).html(resp);
                    $('#fila_obs').val(fila);
                },
                error:function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
    </script>
@endsection
