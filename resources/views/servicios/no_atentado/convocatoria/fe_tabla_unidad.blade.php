<select name="unidad" class="custom-control custom-select">
    <option></option>
    @foreach($unidad as $u)
        <option value="{{$u->cod}}">{{$u->nombre}}</option>
    @endforeach
</select>
<input type="hidden" name="tipo" value="{{$nombreUnidad}}">
