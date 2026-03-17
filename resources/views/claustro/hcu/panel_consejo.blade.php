<br/>
<div>
    <span class="text-dark">
        <span class="font-italic"> Tipo : </span>
        <span class="font-weight-bold font-italic  text-primary">{{$tipoConsejo}}</span><br/>
        <span class="font-italic"> Unidad : </span>
        <span class="font-weight-bold font-italic  text-primary">{{$nombreUnidad}}</span>
    </span>
    <hr class="sidebar-divider"/>
    @if($tipo=='hcu')
        <hr class="sidebar-divider"/>
            <div class="row" style="font-size: 12px;">
                <div class="col-md-4">
                    <div class="alert-info border border-white centrar_bloque p-1 mt-2 col-md-10 rounded shadow">
                        <h5 class="text-dark text-center">Facultades</h5>
                    </div>
                    <br/>
                    <table class="table table-hover table-sm">
                        @foreach($facultades as $f)
                            <tr>
                                <td><span class="text-dark">{{$f->fac_nombre}}</span></td>
                                <td>
                                    <a class="btn btn-sm btn-light btn-circle text-primary" id="list-home-list" data-toggle="modal" data-target="#frente" role="tab"
                                       aria-controls="home" onclick="cargarDatos('{{url("fu_consejo/hcu/".$f->cod_fac)}}','panel_frente')"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-sm btn-light btn-circle text-primary" id="list-home-list" role="tab"
                                       aria-controls="home" onclick="cargarDatos('{{url('lista consejeros/hcu/'.$f->cod_fac)}}','lista_consejeros')"><i class="fas fa-arrow-circle-right"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-8 border rounded shadow">
                    <div class="bg-info centrar_bloque border border-white mt-2 p-1 col-md-10 rounded shadow-lg">
                        <h5 class="text-white text-center">Lista de consejeros universitarios</h5>
                    </div>
                    <br/>
                    <div id="lista_consejeros" >
                        @include('claustro.hcu.l_consejeros');
                    </div>
                </div>
            </div>
    @else
        @if($tipo=='hcf')
            <hr class="sidebar-divider"/>
            <div class="row" style="font-size: 12px;">
                <div class="col-md-4">
                    <div class="alert-info border border-white centrar_bloque p-1 mt-2 col-md-10 rounded shadow">
                        <h5 class="text-dark text-center">Carreras</h5>
                    </div>
                    <br/>
                    <table class="table table-hover table-sm">
                        @foreach($carreras as $c)
                            <tr>
                                <td><span class="text-dark">{{$c->car_nombre}}</span></td>
                                <td>
                                    <a class="btn btn-sm btn-light btn-circle text-primary" id="list-home-list" data-toggle="modal" data-target="#frente" role="tab"
                                       aria-controls="home" onclick="cargarDatos('{{url("fu_consejo/hcf/".$c->cod_car)}}','panel_frente')"><i class="fas fa-edit"></i></a>
                                    <a class="btn btn-sm btn-light btn-circle text-primary" id="list-home-list" role="tab"
                                       aria-controls="home" onclick="cargarDatos('{{url('lista consejeros/hcf/'.$c->cod_car)}}','lista_consejeros')"><i class="fas fa-arrow-circle-right"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="col-md-8 border rounded shadow">
                    <div class="bg-info centrar_bloque border border-white mt-2 p-1 col-md-10 rounded shadow-lg">
                        <h5 class="text-white text-center">Lista de consejeros facultativos</h5>
                    </div>
                    <br/>
                    <div id="lista_consejeros" >
                        @include('claustro.hcu.l_consejeros');
                    </div>
                </div>
            </div>
        @else
            @if($tipo=='hcc')
                <hr class="sidebar-divider"/>
                <div class="row" style="font-size: 12px;">
                    <div class="col-md-4">
                        <div class="alert-info border border-white centrar_bloque p-1 mt-2 col-md-10 rounded shadow">
                            <h5 class="text-dark text-center">Carreras</h5>
                        </div>
                        <br/>
                        <table class="table table-hover table-sm">
                                <tr>
                                    <td><span class="text-dark">{{$unidad->car_nombre}}</span></td>
                                    <td>
                                        <a class="btn btn-sm btn-light btn-circle text-primary" id="list-home-list" data-toggle="modal" data-target="#frente" role="tab"
                                           aria-controls="home" onclick="cargarDatos('{{url("fu_consejo/hcc/".$unidad->cod_car)}}','panel_frente')"><i class="fas fa-edit"></i></a>
                                        <a class="btn btn-sm btn-light btn-circle text-primary" id="list-home-list" role="tab"
                                           aria-controls="home" onclick="cargarDatos('{{url('lista consejeros/hcc/'.$unidad->cod_car)}}','lista_consejeros')"><i class="fas fa-arrow-circle-right"></i></a>
                                    </td>
                                </tr>
                        </table>
                    </div>
                    <div class="col-md-8 border rounded shadow">
                        <div class="bg-info centrar_bloque border border-white mt-2 p-1 col-md-10 rounded shadow-lg">
                            <h5 class="text-white text-center">Lista de consejeros de carrera</h5>
                        </div>
                        <br/>
                        <div id="lista_consejeros" >
                            @include('claustro.hcu.l_consejeros');
                        </div>
                    </div>
                </div>
            @else

            @endif
        @endif

    @endif
</div>
