<select class="form-control form-control-sm border border-info"  name="tomo">
    @foreach($tomos as $t)
        @if($t->tom_numero=='0')
            <option value="{{$t->cod_tom}}">Sin tomo</option>
        @else
            <option value="{{$t->cod_tom}}">{{$t->tom_numero}}</option>
        @endif
    @endforeach

</select>
