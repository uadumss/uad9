<div>
    <table >
        <tr>
            <th class="text-right">Nro de código :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$codigo->carch_numero}}</th>
        </tr>
        <tr>
            <th class="text-right">Título :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3">{{$codigo->carch_titulo}}</th>
        </tr>
        <tr>
            <th class="text-right">Descripcion :</th>
            <th class="text-dark text-left border-bottom border-danger pl-3"><?php echo $codigo->carch_desc?></th>
        </tr>

    </table>
    <input type="hidden" name="cc" value="{{$codigo->cod_carch}}">
</div>
