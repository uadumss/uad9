<div>
    <?php

    ?>
    <table class="col-md-12">
        <tr>
            <th class="text-right">Tomo :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$tomo->tom_numero}}</th>
        </tr>
        <tr>
            <th class="text-right">Tipo :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">Resoluciones</th>
        </tr>
    </table>
    <input type="hidden" name="ct" value="{{$tomo->cod_tom}}">
</div>
