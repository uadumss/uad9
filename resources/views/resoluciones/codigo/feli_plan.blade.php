<div>
    <table >
        <tr>
            <th class="text-right">Nro plan :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$plan->plan_numero}}</th>
        </tr>
        <tr>
            <th class="text-right">Título :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$plan->plan_titulo}}</th>
        </tr>
    </table>
    <input type="hidden" name="cp" value="{{$plan->cod_plan}}">
</div>
