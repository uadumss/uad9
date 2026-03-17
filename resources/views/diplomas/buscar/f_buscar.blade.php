@extends('marco/pagina')
@section('contenido')
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('error') !!}
        </div>
    @endif
    <div class="card shadow mb-4">
        <div class="card-header py-3 alert-primary">
            <div class="d-sm-flex align-items-center justify-content-between">
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp;DIPLOMAS Y TITULOS</h5>
            </div>
        </div>
        <div class="card-body">
            <div class=" input-group">
                @can('busqueda - dyt')
                    <a class="btn btn-outline-info btn-sm text-dark shadow-sm " data-toggle="modal" data-target="#simple"><i class="fas fa-search"></i> Busqueda . . .</a>

                @endcan

                @can('busqueda avanzada - dyt')
                     <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <a class="btn btn-outline-info btn-sm text-dark shadow-sm " data-toggle="modal" data-target="#avanzado"><i class="fas fa-search-plus"></i> Avanzado . . .</a>
                @endcan
            </div>

            <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                <h5 class="text-white text-center">Formulario de búsquedas </h5>
            </div>
            <hr class="sidebar-divider"/>
            <div class="table-responsive">
                <?php $tipo['db']="DIPLOMA DE BACHILLER";?><?php $tipo['ca']="CERTIFICADO ACADÉMICO";?>
                <?php $tipo['da']="DIPLOMA ACADÉMICO";?><?php $tipo['tp']="TÍTULO PROFESIONAL";?>
                <?php $tipo['tpos']="TÍTULO POSGRADO";?><?php $tipo['di']="DIPLOMADO";?>
                <?php $tipo['re']="REVÁLIDA";?><?php $tipo['su']="CERTIFICADO SUPLETORIO";?>

                <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                    <tr class="bg-gray-600 text-white">
                        <th>Nº</th>
                        <th class="text-right">CI</th>
                        <th>Nombre</th>
                        <th class="text-right">Nº de título</th>
                        <th>Tipo de documento</th>
                        <th class="text-right"> Gestión</th>
                        <th class="text-right">Tomo</th>
                        <th class="text-right">Fecha</th>
                        <th class="text-right">Archivos</th>
                        <th class="text-right">Documentos</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1;?>
                    @foreach($resultado as $t)
                        <tr>
                            <td>{{$i}}</td>
                            <td class="text-right">{{$t->per_ci}}</td>
                            <td>{{$t->per_apellido." ".$t->per_nombre}}</td>
                            <td class="text-right">{{$t->tit_nro_titulo}}</td>
                            <td>{{$tipo[$t->tom_tipo]}}</td>
                            <td class="text-right">{{$t->tom_gestion}}</td>
                            <td class="text-right">{{$t->tom_numero}}</td>
                            <td class="text-right">{{date('d/m/Y',strtotime($t->tit_fecha_emision))}}</td>
                            <td class="text-right text-danger">
                                @if($t->tit_pdf!='')
                                    <i class="fas fa-file-pdf"></i>
                                @endif
                                    @if($t->tit_antecedentes!='')
                                        <i class="fas fa-file-archive"></i>
                                    @endif
                            </td>
                            <td class="text-right">
                                <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verDatos"
                                onclick="verDatos({{$t->cod_tit}})"> <i class="fas fa-eye"></i></a>
                            </td>
                        </tr>
                        <?php $i++;?>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<!--=================================MODAL BUSQUEDA AVANZADA============================-->
    <div class="modal fade" id="avanzado" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('buscar_t')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-search-location"></i>&nbsp;&nbsp; Búsqueda</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="justify-content-center">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
                                        <h6 class="text-white text-center">Formulario de búsqueda</h6>
                                    </div>
                                    <hr class="sidebar-divider"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="col-md-10">
                                                <tr>
                                                    <th class="text-right font-italic">Nº título :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}" name="nro" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Tipo :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="form-control form-control-sm"  name="tipo">
                                                            <option value=""></option>
                                                            <option value="db">Diplomas de bachiller</option>
                                                            <option value="ca">Certificado académico</option>
                                                            <option value="da">Diploma académico</option></a>
                                                            <option value="tp">Título profesional</option></a>
                                                            <option value="di">Diplomado</option></a>
                                                            <option value="tpos">Títulos de posgrado</option></a>
                                                            <option value="re">Reválida</option></a>
                                                            <option value="su">Certificado supletorio</option></a>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">CI :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="text" class="form-control form-control-sm border-0" name="ci" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Fecha :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="date" class="form-control form-control-sm border-0" name="fecha" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Apellido :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type=" text" class="form-control form-control-sm border-0" name="apellido" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Nombre :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="text" class="form-control form-control-sm border-0" name="nombre" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-right font-italic">Gestión :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="form-control form-control-sm"  name="gestion">
                                                            <option value=""></option>
                                                            <?php $año=date('Y');?>
                                                            @for($i=$año;$i>1927;$i--)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-primary" type="submit" value="Buscar"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--================================ END?===============================-->
    <!--=================================MODAL BUSQUEDA SIMPLE============================-->
    <div class="modal fade" id="simple" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('buscar_t')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-search-location"></i>&nbsp;&nbsp; Búsqueda</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="justify-content-center">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
                                        <h6 class="text-white text-center">Formulario de búsqueda</h6>
                                    </div>
                                    <hr class="sidebar-divider"/>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="col-md-10">
                                                <tr>
                                                    <th class="text-right font-italic">Nº título :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="text" class="form-control form-control-sm border-0" pattern="[0-9]{1,5}" name="nro" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Tipo :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="form-control form-control-sm"  name="tipo">
                                                            <option value=""></option>
                                                            <option value="db">Diplomas de bachiller</option>
                                                            <option value="ca">Certificado académico</option>
                                                            <option value="da">Diploma académico</option></a>
                                                            <option value="tp">Título profesional</option></a>
                                                            <option value="di">Diplomado</option></a>
                                                            <option value="tpos">Títulos de posgrado</option></a>
                                                            <option value="re">Reválida</option></a>
                                                            <option value="su">Certificado supletorio</option></a>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Gestión :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="form-control form-control-sm"  name="gestion">
                                                            <option value=""></option>
                                                            <?php $año=date('Y');?>
                                                            @for($i=$año;$i>1927;$i--)
                                                                <option value="{{$i}}">{{$i}}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                            <input class="btn btn-primary" type="submit" value="Buscar"/>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--================================ END?===============================-->
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
                    <div class="modal-footer">
                            <button class="btn btn-secondary" onclick="" data-dismiss="modal">Cerrar</button>
                    </div>
            </div>
        </div>
    </div>
    <!--================================ END?===============================-->

    <script>
        @if(isset($primeraBusqueda))
        window.onload = function () {
            $('#simple').modal('show');
        }
        @endif
        function verDatos(id){

            var url="{{url('ver datos/')}}"+"/"+id;
            $.ajax({
                url:url,
                type:'GET',
                data:'',
                success:function (resp) {
                    $('#p_detalle').html(resp);
                },
                error:function () {
                    alert('No se puede ejecutar la petición');
                }
            });
        }
    </script>
@endsection
