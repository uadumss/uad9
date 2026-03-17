@if($usuario->bloqueado=='t')
    <span class="font-italic"> Esta seguro de habilitar al usuario :</span>  <br/><br/>
@else
    <span class="font-italic"> Esta seguro de bloquear al usuario :</span>  <br/><br/>
@endif

<div class="row">
    <div class="font-weight-bold alert-danger shadow text-center centrar_bloque col-md-8 p-2" id="eliTomo">
        <table >
            <tr>
                <th class="text-right">Nombre :</th>
                <th class="text-dark text-left border-bottom border-danger pl-3">{{$usuario->name}}</th>
            </tr>
            <tr>
                <th class="text-right">Login :</th>
                <th class="text-dark text-left border-bottom border-danger pl-3">{{$usuario->email}}</th>
            </tr>
            <tr>
                <th class="text-right">Rol :</th>
                <th class="text-dark text-left border-bottom border-danger pl-3">{{$usuario->rol}}</th>
            </tr>
        </table>
        <input type="hidden" name="id" value="{{$usuario->id}}">
    </div>
    <div class="pt-2 col-md-2 text-danger font-weight-bolder text-left"><h2>?</h2></div>
</div>
<br/>
<div class="text-danger font-italic font-weight-bold border border-danger rounded col-md-8" style="font-size: 0.7em">* Esta acción se quedará registrado en el sistema</div>
