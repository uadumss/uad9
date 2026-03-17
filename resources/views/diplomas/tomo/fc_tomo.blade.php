<div>
    <table class="col-md-12">
        <tr>
            <th class="text-right">N° Tomo :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$tomo->tom_numero}}</th>
        </tr>
        <tr>
            <th class="text-right">Tipo :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{\App\Http\Controllers\TomoController::tipoTomoUnitario($tomo->tom_tipo)}}</th>
        </tr>
        <tr>
            <th class="text-right">Gestión :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$tomo->tom_gestion}}</th>
        </tr>
    </table>
    <input type="hidden" name="ct" value="{{$tomo->cod_tom}}">
</div>
