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
                <h5 class=""><i class="fas fa-book"></i>&nbsp;&nbsp;BÚSQUEDA DE RESOLUCIONES</h5>
            </div>
        </div>
        <div class="card-body">
            <div class=" input-group">
                @can('buscar - rr')
                    <a class="btn btn-outline-info btn-sm text-dark shadow-sm " data-toggle="modal" data-target="#simple"><i class="fas fa-search"></i> Busqueda . . .</a>
                @endcan

                @can('busqueda avanzada - rr')
                     <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <a class="btn btn-outline-info btn-sm text-dark shadow-sm " data-toggle="modal" data-target="#avanzado"><i class="fas fa-search-plus"></i> Avanzado . . .</a>
                @endcan
            </div>

            <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                <h5 class="text-white text-center">Resultado de la búsqueda </h5>
            </div><br/>
            <?php if(!isset($primeraBusqueda)){?>
            <div class="alert alert-warning">
                <span class="text-primary font-weight-bold">
                    CRITERIO DE BÚSQUEDA :
                </span>
                <span class="text-dark font-italic" style="font-size: 0.8em;">
                    <?php $tam=sizeof($criterio);
                    for($i=0;$i<$tam;$i++){
                        echo "<span class='font-weight-bold'> ".$criterio[$i][0]."</span> ".$criterio[$i][1]." | ";
                    }
                    ?>
                </span>
            </div>
            <?php }?>
            <hr class="sidebar-divider"/>
            <div class="table-responsive">

                <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: 0.9em;">
                    <thead>
                    <tr class="bg-gray-600 text-white">
                        <th>Nº</th>
                        <th class="text-right">Nº Resolución</th>
                        <th>Fecha</th>
                        <th class="text-right">Tipo</th>
                        <th>Descripción</th>
                        <th class="text-right">Objeto</th>
                        <th class="text-right">Tema</th>
                        <th>Opciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $i=1;?>
                    @foreach($resultado as $t)
                        <tr>
                            <td>{{$i}}</td>
                            <td class="text-right">{{$t->res_numero}}</td>
                            <td class="text-right"><?php if($t->res_fecha!=''){echo date('d/m/Y',strtotime($t->res_fecha));} ?></td>
                            <td class="text-right">{{strtoupper($t->res_tipo)}}</td>
                            <td><?php
                                if($clave!=''){
                                    $text =preg_replace('#'. preg_quote($clave) .'#i', '<span style="background-color:#f1fa8c">\0</span>',$t->res_desc);
                                        echo $text;
                                    }else{
                                        echo $t->res_desc;
                                }
                                ?>

                            </td>
                            <td><?php
                                if($clave!=''){
                                    $text =preg_replace('#'. preg_quote($clave) .'#i', '<span style="background-color:#f1fa8c">\0</span>',$t->res_objeto);
                                    echo $text;
                                }else{
                                    echo $t->res_objeto;
                                }
                                ?></td>
                            <td><?php
                                if($clave!=''){
                                    $text =preg_replace('#'. preg_quote($clave) .'#i', '<span style="background-color:#f1fa8c">\0</span>',$t->res_tema);
                                    echo $text;
                                }else{
                                    echo $t->res_tema;
                                }
                                ?></td>
                            <td class="text-right">
                                <a href="" class="btn btn-circle btn-light btn-sm text-primary" data-toggle="modal" data-target="#verDatos"
                                onclick="verDatos({{$t->cod_res}})"> <i class="fas fa-eye"></i></a>
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
        <div class="modal-dialog modal-lg" role="document">
            <form action="{{url('buscar resolucion')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-search-location"></i>&nbsp;&nbsp; Búsqueda Avanzada</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="justify-content-center">
                            <div class="card shadow">
                                <div class="card-body">
                                    <div class="bg-primary centrar_bloque p-1 col-md-8 rounded shadow-sm">
                                        <h6 class="text-white text-center">Formulario de búsqueda avanzada</h6>
                                    </div>
                                    <hr class="sidebar-divider"/>
                                    <div class="row">
                                        <div class="col-md-12 m-5">
                                            <table class="col-md-10">
                                                <tr>
                                                    <th class="text-right font-italic">Palabra clave:</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="text" class="form-control form-control-sm border-0" required name="clave" />
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th class="text-right font-italic">Tipo :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="custom-select custom-select-sm"  name="tipo">
                                                            <option value="rcu">RCU</option>
                                                            <option value="rr">RR</option>
                                                            <option value="rvr">RVR</option>
                                                            <option value="rs">RS</option>
                                                            <option value="rc">RC</option>
                                                            <option value="rcf">RCF</option>
                                                            <option value="rcc">RCC</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Fecha :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="date" class="form-control form-control-sm border-0" name="gestion_i" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Fecha Final :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="date" class="form-control form-control-sm border-0" name="gestion_f" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Tema :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="custom-select custom-select-sm border-0" name="tema">
                                                            <option></option>
                                                            @foreach($tema as $t)
                                                                <option value="{{$t->res_tema}}" class="col-md-10">{{$t->res_tema}}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-primary text-right font-italic"><br/>INCLUIR</th>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Vistos :</th>
                                                    <td class="text-left">
                                                        &nbsp;&nbsp;<input type="checkbox" class="" name="vistos" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Considerando :</th>
                                                    <td class="text-left">
                                                        &nbsp;&nbsp;<input type="checkbox" class="" name="considerando" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Resuelve :</th>
                                                    <td class="text-left">
                                                        &nbsp;&nbsp;<input type="checkbox" class="" name="resuelve" />
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
    @can('buscar - rr')
    <!--=================================MODAL BUSQUEDA SIMPLE============================-->
    <div class="modal fade" id="simple" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog" role="document">
            <form action="{{url('buscar resolucion')}}" method="POST" enctype="multipart/form-data">
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
                                                    <th class="text-right font-italic">Nº Resolución :</th>
                                                    <td class="border-bottom border-dark">
                                                        <input type="text" class="form-control form-control-sm border-0" name="numero" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Tipo :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="custom-select custom-select-sm"  name="tipo">
                                                            <option value="rcu">RCU</option>
                                                            <option value="rr">RR</option>
                                                            <option value="rvr">RVR</option>
                                                            <option value="rs">RS</option>
                                                            <option value="rc">RC</option>
                                                            <option value="rcf">RCF</option>
                                                            <option value="rcc">RCC</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="text-right font-italic">Año :</th>
                                                    <td class="border-bottom border-dark">
                                                        <select class="custom-select custom-select-sm" name="gestion">
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
    @endcan
    <!--================================ END?===============================-->
    <!--=================================MODAL VER TITULO ============================-->
    @can('busqueda avanzada - rr')
    <div class="modal fade" id="verDatos" style="z-index:1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="true">
        <div class="modal-dialog modal-xl" role="document" id="p_detalle">

        </div>
    </div>
    @endcan
    <!--================================ END?===============================-->

    <script>
        @if(isset($primeraBusqueda))
        window.onload = function () {
            $('#simple').modal('show');
        }
        @endif
        function verDatos(id){
            var url="{{url('ver datos resolucion/')}}"+"/"+id;
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
