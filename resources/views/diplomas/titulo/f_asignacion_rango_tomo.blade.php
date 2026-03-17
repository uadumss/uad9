<div class="modal-dialog modal-lg" role="document" id="panel_docleg">
    <div class="modal-content border-bottom-danger shadow-lg">
        <div class="modal-header bg-danger">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-book"></i>&nbsp;&nbsp;Asignar a tomo</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">

            @if(Session::has('errorf'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <span class="font-weight-bold text-dark">{!! session('errorf') !!}</span>
                </div>
            @endif
            @if($exito==0)
                @csrf
                <div class="p-2 alert-danger shadow">
                    <p> No se puede asignar los títulos al tomo</p>
                </div>
                @else
                <span class="font-italic">Esta seguro de asignar {{$form['final']}} titulos al tomo {{$form['tomo']}} : </span><br/><br/>
                    <div>
                    <span style="font-size: 14px;" class="font-italic">
                        @if($tomoAsignado)
                            <span>N° Tomo : </span><span class="font-weight-bold">{{$tomoAsignado->tom_numero}}</span> &nbsp; | &nbsp;
                            <span>Tipo : </span><span  class="font-weight-bold">{{$tomoAsignado->tom_tipo}}</span> &nbsp; | &nbsp;
                            <span>Gestión : </span><span class="font-weight-bold">{{$tomoAsignado->tom_gestion}}</span> &nbsp; | &nbsp;
                        @else
                            <span>N° Tomo : </span><span class="font-weight-bold">{{$form['tomo']}}</span> &nbsp; | &nbsp;
                            <span>Tipo : </span><span class="font-weight-bold">{{$tomo->tom_tipo}}</span> &nbsp; | &nbsp;
                            <span>Gestión : </span><span class="font-weight-bold">{{$tomo->tom_gestion}}</span> &nbsp; | &nbsp;
                            <span class="bg-success rounded text-white p-1 font-weight-bold"> Se creará el Tomo</span>
                        @endif
                    </span>
                    </div>
                    <br/>
                <div class="row">
                    <div class="shadow text-center centrar_bloque col-md-9 p-2" >
                        <div style="height: 400px" class="overflow-auto">
                                <table class="col-md-10 table-sm table text-dark">
                                    <tr class="bg-light">
                                        <th>N°</th>
                                        <th>Nombre</th>
                                        <th>Nro. Título</th>
                                    </tr>
                                    <?php $i=0?>
                                   @foreach($titulos as $t)
                                       <tr>
                                           <td class="text-right">{{$i+=1}}</td>
                                           <td class="text-left">{{$t->per_apellido.", ".$t->per_nombre}}</td>
                                           <td class="text-right">{{$t->tit_nro_titulo}}</td>
                                       </tr>
                                    @endforeach
                                </table>

                        </div>
                    </div>
                    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h1><i class="fas fa-question-circle"></i></h1></div>
                </div>
                <br/>
                <div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
            </form>
            @endif
        </div>

        <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
            @if($exito==1)
                <form action="{{url('asignar rango tomo')}}" method="post" id="form_rango">
                    @csrf
                    <input type="hidden" name="ct" value="{{$tomo->cod_tom}}">
                    <input type="hidden" name="nt" value="{{$form['tomo']}}">
                    <input type="hidden" name="final" value="{{$form['final']}}">
                    @if($tomoAsignado)
                        <input type="hidden" name="ctn" value="{{$tomoAsignado->cot_tom}}">
                    @else
                        <input type="hidden" name="ctn" value="0">
                    @endif
                </form>
                <button class="btn btn-danger" data-dismiss="modal" onclick="$('#form_rango').submit()"> Asignar</button>
            @endif
        </div>
    </div>
</div>
