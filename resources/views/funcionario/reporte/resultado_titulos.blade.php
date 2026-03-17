<br/>
<div class="alert-success centrar_bloque p-1 col-md-3 rounded shadow">
    <h5 class="text-dark text-center">Resultado de la consulta</h5>
</div>
<span class="text-dark">
    <span>Cantidad encontrada : </span><span class="font-weight-bold text-primary">{{sizeof($resultado)}}</span> | 

    <?php
        if($tipo_funcionario!=''){
            if($tipo_funcionario=='A'){
                $tipo_funcionario='ADMINISTRATIVO';
            }else{
                if($tipo_funcionario=='D'){
                    $tipo_funcionario='DOCENTE';
            }else{
                    $tipo_funcionario='DOCENTE Y ADMINISTRATIVO';
                }
            }
    }?>
    @if($tipo_funcionario!='')
        <span> Tipo de funcionario : </span><span class="font-weight-bold text-primary">{{$tipo_funcionario}}</span>
    @endif
</span>

<hr class="sidebar-divider">
<table class="table table-sm table-hover"  width="100%" cellspacing="0" style="font-size: 0.8em">
    <thead>
    <tr class="bg-gray-600 text-white">
        <th>Nº</th>
        <th class="">Nombre</th>
        <th class="">CI</th>
        <th>Facultad</th>
        <th>Carrera</th>
    </tr>
    </thead>
    <tbody>
    <?php $j=1;?>
    @foreach($resultado as $f)
        <tr class="">
                    <td>
                        {{$j}}
                    </td>
                    <td>{{$f->fun_nombre}} </td>
                    <td>{{$f->fun_ci}}</td>
                    <td>{{$f->fun_facultad}}</td>
                    <td>{{$f->fun_carrera}}</td>
                </tr>
                <?php $j++;?>
                @endforeach
    </tbody>
</table>

