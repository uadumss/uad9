
<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-folder-open"></i>&nbsp;&nbsp;Consejo</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            @if(Session::has('exitof'))
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('exitof') !!}
                </div>
            @endif
            @if(Session::has('errorf'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {!! session('errorf') !!}
                </div>
            @endif
            <div>
                <div class="bg-info border border-white centrar_bloque p-1 mt-2 col-md-5 rounded shadow">
                    <h5 class="text-white text-center">Editar datos de consejero</h5>
                </div>
                <hr class="sidebar-divider"/>
                <div>
                    <table class="col-md-12 text-dark table table-sm">
                        <tr>
                            <th class="text-right font-italic">CI : </th>
                            <td class="border-bottom border-dark">{{$persona->per_ci}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Nombre : </th>
                            <td class="border-bottom border-dark">{{$persona->per_nombre." ".$persona->per_apellido}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic"> Frente : </th>
                            <td class="border-bottom border-dark">{{$frente->fre_nombre}}</td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Periodo : </th>
                            <td class="text-dark font-weight-bold">
                                {{date('d/m/Y',strtotime($electo->ele_fecha_inicio))." - ".date('d/m/Y',strtotime($electo->ele_fecha_fin))}}
                                <?php if(strtotime($electo->ele_fecha_fin)>strtotime(date("d-m-Y H:i:00",time()))){ ?>
                                    <i class='fas fa-check-circle text-success'></i>
                                <?php } ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic">Tipo consejo: </th>
                            <td class="border-bottom border-dark">
                                @if($electo->ele_tipo=='u')
                                    HCU
                                @else
                                    @if($electo->ele_tipo=='f')
                                        HCF
                                    @else
                                        @if($electo->ele_tipo=='c')
                                            HCC
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic"> Unidad : </th>
                            <td class="border-bottom border-dark">
                                @if($electo->ele_tipo=='u')
                                    <?php $facultad=\App\Models\Facultad::find($electo->cod_fac);echo $facultad->fac_nombre ?>
                                @else
                                    @if($electo->ele_tipo=='f')
                                        <?php $carrera=\App\Models\Carrera::find($electo->cod_car);echo $carrera->car_nombre ?>
                                    @else
                                        @if($electo->ele_tipo=='c')
                                                <?php $carrera=\App\Models\Carrera::find($electo->cod_car);echo $carrera->car_nombre ?>
                                        @endif
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-right font-italic"> Fecha renuncia : </th>
                            <td>
                                @if($electo->ele_fecha_renuncia)
                                    {{date('d/m/Y',strtotime($electo->ele_fecha_renuncia))}}
                                @endif

                                <form id="form_renuncia">
                                    @csrf
                                    <input type="date" name="renuncia" class="form-control-sm form-control border border-danger border-left-danger">
                                    <input type="hidden" name="ce" value="{{$electo->cod_ele}}">
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            @can('ver datos consejero - cla')
                <button class="btn btn-primary btn-sm" type="button" data-dismiss="modal" onclick="enviar('form_renuncia','{{"g_renuncia"}}','panel_frente')">Guardar</button>
            @endcan
        </div>

    </div>
</div>
