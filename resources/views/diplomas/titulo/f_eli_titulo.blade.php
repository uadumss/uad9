<div>
    <table >
        <tr>
            <th class="text-right">Nro. Titulo:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$titulo[0]->tit_nro_titulo}}</th>
        </tr>
        <tr>
            <th class="text-right">Perteneciente a:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$titulo[0]->per_apellido.", ".$titulo[0]->per_nombre}}</th>
        </tr>
        <tr>
            <th class="text-right">Tipo de documento:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$tipoUnitario}}</th>
        </tr>
    </table>
    <input type="hidden" name="ctit" value="{{$titulo[0]->cod_tit}}">
    <input type="hidden" name="ct" value="{{$titulo[0]->cod_tom}}">
</div>
