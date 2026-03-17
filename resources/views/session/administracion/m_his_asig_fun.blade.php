<div>
    <div class="row">
        <div class="col-md-2">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-info shadow-sm" onclick="atras('a_o_listaFun',{{$ip}})">
                <i class="fas fa-arrow-left fa-sm"></i> Atras</a>
        </div>
        <div class="col-md-6 alert-info p-2 centrar_bloque rounded shadow">
            <h5 class="text-dark text-center">Historial de asignación de funcionarios</h5>
        </div>
    </div>
    <div>
        <span class="mensaje">* Lista histórica de asignación de funcionarios</span>
        <table class="table table-sm shadow-sm rounded">
            <tr class="alert-info">
                <th>Nº</th>
                <th>Nombre</th>
                <th>Fecha Asignación</th>
                <th>Fecha conclusión</th>
                <th>Estado</th>
            </tr>
            <?php $i=1;?>
            @foreach($fun as $f)
                <tr>
                    <td>{{$i}}</td>
                    @if($f->per_fot!='')
                        <td>    <img src="{{url('img/foto/'.$f->per_fot)}}" width="40" height="40" class="imgRedonda"/>
                            {{$f->per_ape." ".$f->per_nom}}
                        </td>
                    @else
                        <td class="pl-5">
                            {{$f->per_ape." ".$f->per_nom}}
                        </td>
                    @endif

                    <td>@if($f->car_fi!='')
                            {{date('d/m/Y',strtotime($f->car_fi))}}
                        @endif
                    </td>
                    <td>@if($f->car_ff!='')
                            {{date('d/m/Y',strtotime($f->car_ff))}}
                        @endif
                    </td>
                    <td>@if($f->car_ff!='')
                            <span class="text-dark">Inactivo</span>
                        @else
                            <span class="text-primary">Activo</span>
                        @endif
                    </td>
                </tr>
                <?php $i++;?>
            @endforeach
        </table>
    </div>
</div>
<script>
    function atras(opcion,id_per){
        var ruta="{{url('')}}"+"/"+opcion+"/"+id_per;
            $.ajax({
                url: ruta,
                type: 'GET',
                data:'',
                success: function (resp) {
                    $('#panel').html(resp);
                },
                error: function () {
                    alert('No se puede ejecutar la petición');
                }
            });
    }
</script>
