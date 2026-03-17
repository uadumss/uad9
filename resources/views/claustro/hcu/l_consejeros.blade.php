<div>
    @if($tipo=='hcu')
        <span class="font-weight-bold font-italic text-dark">UNIVERSIDAD MAYOR DE SAN SIMON </span><br/>
        @if($unidad)
            <span class="font-weight-bold font-italic text-dark">Facultad :</span>
            <span class="text-primary font-weight-bolder">{{$unidad->fac_nombre}}</span>
        @endif
    @else
        @if($tipo=='hcf')
                <span class="font-weight-bold font-italic text-dark">Carrera :</span>
                <span class="text-primary font-weight-bolder">{{$unidad->car_nombre}}</span>
        @endif

    @endif
    <br/><br/>
</div>
<div class="overflow-auto col-md-12 p-2 border rounded" style="height: 600px">
    <table class="table table-sm">
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>CI</th>
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
                <td>{{$c->per_apellido." ".$c->per_nombre}}</td>
                <td>{{$c->per_ci}}</td>
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
                <td>
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

                </td>
            </tr>
        @endforeach
    </table>
</div>
