<div>
    <div class=" ml-5">
        <span class="font-italic text-dark">
            <table class="col-md-12">
                <tr>
                    <td><span>Frente : </span><span class="font-weight-bold">{{$frente->fre_nombre}}</span></td>
                    <td>
                        <span> Estamento : </span>
                        @if($frente->fre_docente=='t')
                            <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docente</span>
                        @else
                            <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiantil</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Fechas : </span><span class="font-weight-bold">
                            @if($frente->fre_fecha_inicio!='')
                                {{date('d/m/Y',strtotime($frente->fre_fecha_inicio))}}
                            @endif
                            @if($frente->fre_fecha_fin!='')
                                {{" - ".date('d/m/Y',strtotime($frente->fre_fecha_fin))}}
                            @endif
                        </span>
                    </td>
                    <td>
                        <span>Periodo : </span><span class="font-weight-bold">
                            @if($frente->fre_fecha_inicio!='')
                                {{date('Y',strtotime($frente->fre_fecha_inicio))}}
                            @endif
                            @if($frente->fre_fecha_fin!='')
                                {{" - ".date('Y',strtotime($frente->fre_fecha_fin))}}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span> Tipo de consejo :
                            @if($frente->fre_tipo=='u')
                                <span class="font-weight-bold text-primary">HCU</span>
                            @else
                                @if($frente->fre_tipo=='f')
                                    <span class="font-weight-bold text-primary">HCF</span>
                                @else
                                    <span class="font-weight-bold text-primary">HCC</span>
                                @endif
                            @endif
                        </span>
                    </td>
                </tr>
            </table>

    </span>
    </div>

    <br/>
    <br/>
    <div class="bg-info centrar_bloque p-1 col-md-8 rounded shadow">
        <h5 class="text-white text-center">Lista de consejeros</h5>
    </div>
    <hr class="sidebar-divider"/>
    <div class="overflow-auto" style="height: 400px">
        <table class="table table-sm">
            <tr>
                <th>N°</th>
                <th>Nombre</th>
                <th>CI</th>
                <th>Participacion</th>
                <th>Periodo</th>
                <th>Renuncia</th>
            </tr>
            <?php $i=0;?>
            @foreach($consejeros as $c)
                <tr>
                    <td>{{$i+=1}}</td>
                    <td>{{$c->per_apellido." ".$c->per_nombre}}</td>
                    <td>{{$c->per_ci}}</td>
                    <td>
                        @if($c->ele_titular=='t')
                            <span class="bg-dark rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Titular</span>
                        @else
                            <span class="bg-light rounded pr-1 pl-1 text-dark font-weight-bold font-italic" style="font-size: 14px;">Suplente</span>
                        @endif
                    </td>
                    <td class="text-dark font-weight-bold">
                        {{date('d/m/Y',strtotime($c->ele_fecha_inicio))." - ".date('d/m/Y',strtotime($c->ele_fecha_fin))}}
                    </td>
                    <td>
                        @if($c->ele_fecha_renuncia!='')
                            {{date('d/m/Y',strtotime($c->ele_fecha_renuncia))}}
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
