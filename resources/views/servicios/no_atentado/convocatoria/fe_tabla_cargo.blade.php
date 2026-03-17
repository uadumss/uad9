@if(Session::has('exitoCargo'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session('exitoCargo') !!}
    </div>
@endif
@if(Session::has('errorCargo'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        {!! session('errorCargo') !!}
    </div>
@endif
<table class="col-md-12 " >
    <tr class="bg-secondary text-white">
        <th>No.</th>
        <th>Nombre cargo</th>
        <th>Opciones</th>
    </tr>
    <?php $i=1;?>
    @foreach($cargos as $c)
        <tr class="border-bottom">
            <td>{{$i}}</td>
            <td>{{$c->carg_nombre}}</td>
            <td>
                <form id="form_eliminar{{$i}}">
                    @csrf
                    <input type="hidden" name="cc" value="{{$c->cod_con}}">
                    <input type="hidden" name="ca" value="{{$c->cod_carg}}">
                </form>
                <a class="btn btn-light btn-circle btn-sm text-primary" data-toggle="modal" data-target="#modal_agregar" onclick="cargarDatos('{{url('cargos convocatoria noatentado/'.$c->cod_carg.'/'.$convocatoria->cod_con)}}','panel_agregar')"><i class="fas fa-edit"></i></a>
                <a class="btn btn-light btn-circle btn-sm text-danger" onclick="enviar('form_eliminar{{$i}}','{{url('eliminar cargo convocatoria noatentado')}}','panel_cargos')"><i class="fas fa-trash-alt"></i></a>
            </td>
        </tr>
        <?php $i++;?>
    @endforeach
</table>
