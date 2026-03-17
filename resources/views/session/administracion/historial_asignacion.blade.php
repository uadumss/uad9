<div>
    <div class="row">
        <div class="col-md-2">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" onclick="cargarDatos('{{url("lista a cargos/".$id)}}','panel')">
                <i class="fas fa-arrow-left fa-sm"></i> Atras</a>
        </div>
        <div class="col-md-6 bg-primary p-2 centrar_bloque rounded shadow">
            <h5 class="text-white text-center">Historial de asignación de funcionarios</h5>
        </div>
    </div>
    <div>
        <span class="mensaje">* Lista histórica de asignación de funcionarios</span>
        <table class="table table-sm shadow-sm rounded">
            <tr class="bg-gradient-light text-dark">
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
                    @if($f->foto!='')
                        <td>    <img src="{{url('img/foto/'.$f->foto)}}" width="40" height="40" class="imgRedonda"/>
                            {{$f->name}}
                        </td>
                    @else
                        <td class="pl-5">
                            {{$f->name}}
                        </td>
                    @endif

                    <td>@if($f->ac_inicio!='')
                            {{date('d/m/Y',strtotime($f->ac_inicio))}}
                        @endif
                    </td>
                    <td>@if($f->ac_fin!='')
                            {{date('d/m/Y',strtotime($f->ac_fin))}}
                        @endif
                    </td>
                    <td>@if($f->ac_fin!='')
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
