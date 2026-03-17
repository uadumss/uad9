<div class="p-1">
    <div class="bg-info centrar_bloque p-1 col-md-3 rounded shadow">
        <h6 class="text-white text-center">Lista de Duplicados</h6>
    </div>
    @if(Session::has('exitoDuplicado'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            {!! session('exitoDuplicado') !!}
        </div>
    @endif
    @if($tipo=='total')
        <button class="btn btn-sm btn-danger float-right" onclick="enviar('form_bloque_duplicado','{{url('corregir bloque duplicado')}}','panel_duplicados')"><i class="fas fa-arrow-circle-right"></i> Corregir 500 duplicados</button>
        <form id="form_bloque_duplicado">
            @csrf
        </form>
    @endif
    <br/>
    <div style="clear: both"></div>
    <div>
        <div style="font-size: 12px;height: 500px;" class="overflow-auto">
            <table class="table table-sm">
                <tr>
                    <th>N°</th>
                    <th>CI</th>
                    <th>Nombre</th>
                    <th>Opciones</th>
                </tr>
                <?php $i=1;?>
                @foreach($lista as $l)
                    <tr id="fila{{$i}}">
                        <td>{{$i}}</td>
                        <td>{{$l->per_ci}}</td>
                        <td id="nombre{{$i}}">
                        @if($tipo=='total')
                            {{$l->per_apellido." ".$l->per_nombre}}
                        @else
                            <?php echo \App\Models\Persona::getDatosPersonales($l->per_ci)?>
                        @endif
                        </td>
                        <td>
                            <a class="btn btn-sm btn-light btn-circle text-primary" onclick="cargarDatos('{{url('listar duplicado/'.$tipo.'/'.$l->per_ci)}}','panel_modal');$('#fila').val({{$i}});"
                                data-toggle="modal" data-target="#Modal"><i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </td>
                    </tr>
                    <?php $i++;?>
                @endforeach
            </table>
            <input type="hidden" name="fila" id="fila" value="0">
        </div>
    </div>
</div>
