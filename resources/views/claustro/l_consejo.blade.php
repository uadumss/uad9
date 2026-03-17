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
    @if(Session::has('errores'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('errores') !!}
        </div>
    @endif

    @if(isset($fallas) && count($fallas)>0)
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <ul>
                @foreach($fallas as $f)
                    <li>
                        <?php echo "Fila: ".$f->row()." - ";?>
                        <?php $errores=(array) $f->errors();
                        foreach ($errores as $e):
                            echo $e;
                        endforeach;
                        ?>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <div class="card shadow mb-4">
            <div class="card-header py-3 alert-primary">
                <div class="d-sm-flex align-items-center justify-content-between">
                    <h5 class=""><i class="fas fa-user-graduate"></i>&nbsp;Honorables Consejo</h5>
                </div>
            </div>
        <div class="card-body">
            <div class=" input-group shadow-sm p-2">
                <a href="#" class="btn btn-outline-info text-dark btn-sm font-weight-bold" onclick="cargarDatos('{{url("l_consejeros/0/hcu")}}','panel_consejo')"> UMSS</a>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Facultad :&nbsp; &nbsp;</span>
                <select class="custom-select custom-select-sm border col-md-1 border-info" name="facultad" onchange="cargarDatos('{{url("l_consejeros/")}}/'+this.value+'{{"/hcf"}}','panel_consejo')">
                    <option></option>
                    @foreach($facultad as $f)
                        <option value="{{$f->cod_fac}}">{{$f->fac_abreviacion}}</option>
                    @endforeach
                </select>
                <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                <span class="text-dark font-weight-bold pt-2" style="font-size: 0.9em;"> Carrera :&nbsp; &nbsp;</span>
                <select class="custom-select custom-select-sm border  col-md-3 border-info" name="facultad" onchange="cargarDatos('{{url("l_consejeros/")}}/'+this.value+'{{"/hcc"}}','panel_consejo')">
                    <option></option>
                    @foreach($carrera as $c)
                        <option value="{{$c->cod_car}}">{{$c->fac_abreviacion." - ".$c->car_nombre}}</option>
                    @endforeach
                </select>
                @can('ver datos consejero - cla')
                    <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
                    <a href="#" class="float-right btn btn-sm btn-primary" data-toggle="modal" data-target="#frente" onclick="cargarDatos('{{url("editar consejero/0/")}}','panel_frente')"><i class="fas fa-search"></i> Consejeros</a>
                @endcan
            </div>
            <div id="panel_consejo">

            </div>
        </div>
    </div>
        <div class="modal fade" id="frente" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_frente">

            </div>
        </div>
        <!--===========================MODAL NUEVA FACULTAD===================-->
        <div class="modal fade" id="consejeros" style="z-index: 1500" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document" id="panel_consejeros">

            </div>
        </div>

@endsection
