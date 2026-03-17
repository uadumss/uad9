<div class="modal-content border-bottom-danger">
    <div class="modal-header bg-danger">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Cambiar de tomo</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-danger centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Cambiar a diploma o título de tomo</h6>
        </div>
        <hr class="sidebar-divider"/>

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
        <div class="row">
            <div class="col-md-6">
                <table class="col-md-11">
                    <tr class="border-bottom border-dark">
                        <th colspan="2"><span class="text-primary font-weight-bold text-right font-italic">Datos del título</span><br/><br/></th>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Nombre</th>
                        <td>{{$persona->per_apellido." ".$persona->per_nombre}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Número de título</th>
                        <td>{{$titulo->tit_nro_titulo}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Número de tomo</th>
                        <td>{{$tomo->tom_numero}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Tipo de documento</th>
                        <td>{{\App\Models\Funciones::nombre_titulo($titulo->tit_tipo)}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5 shadow-lg">
                    <span class="font-italic text-primary font-weight-bold"> * Lista de gestiones y tomos</span><br/><br/>
                    <table class="col-md-10 pl-lg-5">
                        <tr class="border-bottom border-light">
                            <th class="font-weight-bold text-right">Año : </th>
                            <td>
                                <?php $gestion=$titulo->tit_gestion;?>
                                <select class="form-control form-control-sm border border-info"  name="gestion" onchange="cargarDatos('{{url("o_tomos/")}}/'+this.value+'/'+'{{$tomo->tom_tipo}}','Tomo')">
                                    <option value="{{$gestion}}">{{$gestion}}</option>
                                    <?php $año=date('Y');?>
                                    @for($i=$año;$i>1927;$i--)
                                        <option value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </td>
                        </tr>
                        <tr class="border-bottom border-light">
                            <th class="font-weight-bold text-right">Numero de tomo : </th>
                            <td>
                                    <form action="{{url("cambiar_tomo")}}" method="POST" id="form_cambiar_tomo" >
                                        @csrf
                                        <div id="Tomo">
                                            <select class="form-control form-control-sm border border-info"  name="tomo">
                                                @foreach($tomos as $t)
                                                    @if($t->tom_numero=='0')
                                                        <option value="{{$t->cod_tom}}">Sin tomo</option>
                                                    @else
                                                        <option value="{{$t->cod_tom}}">{{$t->tom_numero}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="hidden" name="titulo" value="{{$titulo->cod_tit}}"/>
                                    </form>
                            </td>
                        </tr>
                    </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
        <button class="btn btn-danger btm-sm" onclick="cambiar_tomo('form_cambiar_tomo')" type="button">Cambiar</button>
    </div>
</div>
<script>
    function cambiar_tomo(form){
        if(confirm("esta seguro de cambiar el documento de tomo?")){
            $('#'+form).submit();
        }
    }
</script>
