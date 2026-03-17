<br/>
<div class="shadow border rounded col-md-10 m-4">
    <span class="text-primary font-italic">* Datos para el reporte</span>
    <br/>
    <br/>
    <table class="col-md-10 text-dark table table-sm">
        @if($tipo!='todos')
        <tr>
            <th class="text-right font-italic">Grado :</th>
            <td class="border-bottom">
                <select class="custom-select custom-select-sm border-0" name="grado">
                    <option></option>
                    @foreach($grado as $g)
                        <option value="{{$g}}">{{$g}}</option>
                    @endforeach
                </select>
            </td>
        </tr>
        @endif
        @if($tipo=='tp' || $tipo=='da' || $tipo=='ca')
            <tr>
                <th class="text-right font-italic">Carreras :</th>
                <td>
                    <select class="custom-select custom-select-sm border-0"  name="carrera" id="carrera" onchange="$('#facultad').val('')">
                        <option value=""></option>
                        @foreach($carreras as $c)
                            <option value="{{$c->cod_car}}">{{$c->fac_abreviacion." - ".$c->car_nombre}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th class=" text-right font-italic">Facultad :</th>
                <td>
                    <select class="custom-select custom-select-sm border-0"  name="facultad" id="facultad" onchange="$('#carrera').val('')">
                        <option value=""></option>
                        @foreach($facultades as $f)
                            <option value="{{$f->cod_fac}}">{{$f->fac_nombre}}</option>
                        @endforeach
                    </select>
                </td>
            </tr>
        @endif
    </table>

</div>
