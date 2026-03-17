<div>
    <table>
        <tr>
            <th class="text-right">Identificador:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$importacion['imp_identificador']}}</th>
        </tr>
        <tr>
            <th class="text-right">Usuario:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$usuario['name']}}</th>
        </tr>
        <tr>
            <th class="text-right">Fecha:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{date('d/m/Y H:i:s', strtotime($importacion['imp_fecha']))}}</th>
        </tr>
        <tr>
            <th class="text-right">Tipo de documento:</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$importacion['imp_tipo']}}</th>
        </tr>
        <tr>
            <th class="text-right">Gestión :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$importacion['imp_gestion']}}</th>
        </tr>

    </table>
    <input type="hidden" name="ci" value="{{$importacion->cod_imp}}">
</div>
