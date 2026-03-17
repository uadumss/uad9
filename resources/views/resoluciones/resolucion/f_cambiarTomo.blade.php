<div class="modal-content border-bottom-danger">
    <div class="modal-header bg-danger">
        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-eye"></i>&nbsp;&nbsp;Cambiar de tomo</h5>
        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="bg-danger centrar_bloque p-1 col-md-8 rounded shadow-sm">
            <h6 class="text-white text-center">Cambiar resoluciones de tomo</h6>
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
                        <th colspan="2"><span class="text-primary font-weight-bold text-right font-italic">Datos de la resolucion</span><br/><br/></th>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Número de resolución :</th>
                        <td>{{$resolucion->res_numero}} <span class="font-weight-bold text-danger"> &nbsp;&nbsp;&nbsp;&nbsp; Tomo : </span> {{$tomo->tom_numero}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Tema : </th>
                        <td>{{$resolucion->res_tema}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Objeto : </th>
                        <td>{{$resolucion->res_objeto}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic">Descripción : </th>
                        <td>{{$resolucion->res_desc}}</td>
                    </tr>
                    <tr class="border-bottom border-dark">
                        <th class="text-dark font-italic ">Tipo de documento :</th>
                        <td>{{\App\Models\Funciones::tipo_resolucion($resolucion->res_tipo)}}</td>
                    </tr>
                </table>
            </div>
            <div class="col-md-5 shadow-lg">
                    <span class="font-italic text-primary font-weight-bold"> * Lista de gestiones y tomos</span><br/><br/>
                    <table class="col-md-10 pl-lg-5">
                        <tr class="border-bottom border-light">
                            <th class="font-weight-bold text-right">Año : </th>
                            <td>
                                <?php $gestion=$resolucion->res_gestion;?>
                                <select class="form-control form-control-sm border border-info"  name="gestion" onchange="cargarDatos('{{url("o_tomos resolucion/")}}/'+this.value+'/'+'{{$tomo->tom_tipo}}','Tomo')">
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
                                    <form action="{{url("cambiar_tomo resolucion")}}" method="POST" id="form_cambiar_tomo" >
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
                                        <input type="hidden" name="resolucion" value="{{$resolucion->cod_res}}"/>
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
