<table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0" style="font-size: smaller">
    <thead>
    <tr class="bg-gray-600 text-white" style="font-size: 0.9em">
        <th>Nº</th>
        <th class="text-left">Número</th>
        <th class="text-left">CI</th>
        <th class="text-left">Nombre</th>
        <th class="text-left">Fecha solicitud</th>
        <th class="text-left">Fecha firma</th>
        <th class="text-right">Fecha recojo</th>
        <th class="text-center">Opciones</th>
        <th class="text-center">Entrega</th>
    </tr>
    </thead>
    <tbody id="cuerpo">
    <?php $i=1;?>
    @foreach($tramites as $t)
        <tr>
            <th class="border-right font-weight-bolder">
                <span class="text-primary">{{$i}}</span>
            </th>
            <td class="text-left">UAD{{$t->apos_numero}}</td>
            <td class="text-left">{{$t->per_ci}}</td>
            <td class="text-left">{{$t->per_apellido." ".$t->per_nombre}}
                @if($t->apos_apoderado=='p')
                    <span class="text-white bg-danger rounded" style="font-size: 0.7em"> &nbsp;Pod&nbsp; </span>
                @else
                    @if($t->apos_apoderado=='d')
                        <span class="text-white bg-success rounded" style="font-size: 0.7em"> &nbsp;Dec&nbsp; </span>
                    @endif
                @endif
            </td>
            <td class="text-right">{{date('d/m/Y',strtotime($t->apos_fecha_ingreso))}}</td>
            <td class="text-right">@php if($t->apos_fecha_firma!=''){echo date('d/m/Y',strtotime($t->apos_fecha_firma));} @endphp </td>
            <td class="text-right">@php if($t->apos_fecha_recojo!=''){echo date('d/m/Y',strtotime($t->apos_fecha_recojo));} @endphp </td>
            <td class="text-right">
                @can('editar trámite - apo')
                    <a href="#apostilla" class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal"
                       onclick="cargarDatos('{{url("editar tramite apostilla/$t->cod_apos")}}','panel_apostilla')"
                       title="Insertar datos al trámite"><i class="fas fa-edit"></i>
                    </a>
                @endcan

                @if($t->apos_estado==1)
                   @can('firma trámite - apo')
                        <a href="{{url("firmar tramite apostilla/$t->cod_apos")}}" class="btn btn-light btn-circle btn-sm text-primary"
                           title="Firmar trámite"> <i class="fas fa-pen-alt"></i>
                        </a>
                   @endcan
                @else
                    @if($t->apos_estado==2)
                        &nbsp;&nbsp;<i class="fas fa-pen-alt text-success"></i>&nbsp;&nbsp;
                    @else
                        &nbsp;&nbsp;<i class="fas fa-pen-alt text-dark"></i>&nbsp;&nbsp;
                    @endif

                @endif
                @can('eliminar trámite - apo')
                    <a class="btn btn-light btn-circle btn-sm text-danger" data-target="#apostilla" data-toggle="modal" onclick="cargarDatos('{{url("formulario eliminar tramite apostilla/$t->cod_apos")}}','panel_apostilla')"
                       title="Eliminar trámite"> <i class="fas fa-trash-alt"></i>
                    </a>
                @endcan

            </td>
            <td class="text-right">
                @if($t->apos_estado==2)
                    @can('entregar trámite - apo')
                        @if($t->cod_apo=='')
                            <form method="POST" action="{{url("entrega tramite apostilla")}}" id="{{$t->apos_numero}}">
                                @csrf
                                <input type="hidden" name="ca" value="{{$t->cod_apos}}">
                                <input type="hidden" name="apo" value="T">

                            </form>
                            <a class="btn btn-light btn-circle btn-sm text-success" onclick="$('#{{$t->apos_numero}}').submit();">
                                <i class="fas fa-hand-point-right"></i>
                            </a>
                        @else
                            <a class="btn btn-light btn-circle btn-sm text-success" data-target="#apostilla" data-toggle="modal" onclick="cargarDatos('{{url("formulario entrega tramite apostilla/$t->cod_apos")}}','panel_apostilla')"
                               title="Entregar tramite de apostilla"> <i class="fas fa-hand-point-right"></i></a>
                        @endif
                    @endcan
                @else
                    @if($t->apos_estado==3)
                        <span class="border rounded border-info p-1 text-success">Entregado...</span>
                    @endif
                @endif
            </td>

        </tr>
            <?php $i++;?>
    @endforeach
    </tbody>
</table>
<script>
    $('#dataTable').dataTable( {
        "pageLength": 500
    });
</script>
