
<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-primary">
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

            <div id="panel_frente" style="font-size: 12px;">
                <div class="bg-info border border-white centrar_bloque p-1 mt-2 col-md-5 rounded shadow">
                    <h5 class="text-white text-center">Datos de Consejero</h5>
                </div>
                <hr class="sidebar-divider"/>
                <div class="row">
                    <div class="col-md-3" id="">
                        <div>
                            <table class="table table-sm">
                                <tr>
                                    <td>Buscar consejero</td>
                                    <td>
                                        <div class="input-group">
                                            <input class="form-control form-control-sm" type="text" placeholder="Nro. ci" id="ver_consejero"
                                                   onchange="if(+this.value!=''){cargarDatos('{{url('editar consejero/')}}'+'/'+this.value,'panel_frente');}"/>
                                            <span class="btn btn-sm btn-primary" onclick="if($('#ver_consejero').val()!=''){cargarDatos('{{url('editar consejero/')}}'+'/'+$('#ver_consejero').val(),'panel_frente');}"><i class="fas fa-search"></i></span>&nbsp;&nbsp;
                                        </div>
                                    </td>
                                </tr>
                            </table>
                            @if($persona)
                                <span class="font-weight-bold font-italic text-primary">* Datos personales</span>
                                <br/>
                                <br/>
                                <table class="col-md-12 text-dark table table-sm">
                                    <tr>
                                        <th class="text-right font-italic">CI : </th>
                                        <td class="border-bottom border-dark">{{$persona->per_ci}}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Nombre : </th>
                                        <td class="border-bottom border-dark">{{$persona->per_nombre." ".$persona->per_apellido}}</td>
                                    </tr>

                                </table>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9 shadow-lg p-3" id="panel_lista">
                        <span class="font-italic text-danger font-weight-bold">* Datos de consejero</span>
                        <br/>
                        <br/>
                        <?php //dd($electos)?>
                        <div class="overflow-auto" style="height: 400px;">
                            @if(sizeof($electos)>0)
                                <table class="table table-sm">
                                    <tr>
                                        <th>N°</th>
                                        <th>Consejo</th>
                                        <th>Unidad</th>
                                        <th>Participac&oacuten</th>
                                        <th>Periodo</th>
                                        <th>Renuncia</th>
                                        <th>Estamento</th>
                                        <th>Opciones</th>
                                    </tr>
                                        <?php $i=0;?>

                                    @foreach($electos as $c)
                                        <tr>
                                            <td>{{$i+=1}}</td>
                                            <td>
                                                @if($c->ele_tipo=='u')
                                                    HCU
                                                @else
                                                    @if($c->ele_tipo=='f')
                                                        HCF
                                                    @else
                                                        @if($c->ele_tipo=='c')
                                                            HCC
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($c->ele_tipo=='u')
                                                    {{$c->fac_nombre}}
                                                @else
                                                    @if($c->ele_tipo=='f')
                                                        {{$c->car_nombre}}
                                                    @else
                                                        @if($c->ele_tipo=='c')
                                                            {{$c->car_nombre}}
                                                        @endif
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($c->ele_titular=='t')
                                                    <span class="bg-info rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Titular</span>
                                                @else
                                                    <span class="bg-light rounded pr-1 pl-1 text-dark font-weight-bold font-italic" style="font-size: 14px;">Suplente</span>
                                                @endif
                                            </td>
                                            <td class="text-dark font-weight-bold">
                                                {{date('d/m/Y',strtotime($c->ele_fecha_inicio))." - ".date('d/m/Y',strtotime($c->ele_fecha_fin))}}
                                                    <?php if(strtotime($c->ele_fecha_fin)>strtotime(date("d-m-Y H:i:00",time()))){ ?>
                                                <i class='fas fa-check-circle text-success'></i>
                                                <?php } ?>
                                            </td>
                                            <td class="text-dark font-weight-bold">
                                                @if($c->ele_fecha_renuncia!='')
                                                {{date('d/m/Y',strtotime($c->ele_fecha_renuncia))}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($c->ele_docente=='t')
                                                    <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docente</span>
                                                @else
                                                    <span class="bg-danger rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiantil</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-sm btn-primary btn-circle btn-light text-primary" title="editar"
                                                   data-toggle="modal" data-target="#consejeros" onclick="cargarDatos('{{url("editar datos consejero/".$c->cod_ele)}}','panel_consejeros')">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <div class="p-1 alert-danger rounded border">No existe datos</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cancelar</button>
            <button class="btn btn-primary btn-sm" type="button" data-dismiss="modal" onclick="enviar('form_frente','{{"g_frente"}}','panel_frente')">Guardar</button>
        </div>

    </div>
</div>
