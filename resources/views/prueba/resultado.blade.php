<div>
    <table>
        <tr>
            <th>Nro.</th>
            <th>CI.</th>
            <th>NOMBRE</th>
            <th>CARGO ACTUAL</th>
            <th>CARGO ANTERIOR</th>
        </tr>
        <?php $i=1;?>
        @foreach($lista as $l)
            <tr>
                <td>{{$i}}</td>
                <td>{{$l['ci']}}</td>
                <td>{{$l['nombre']}}</td>
                <td>{{$l['cargo1']}}</td>
                <td>{{$l['cargo2']}}</td>
            </tr>
            <?php $i++;?>
        @endforeach
    </table>
</div>
