<table border="1">
    <tr>
        <th>usuario</th>
        <th>id</th>
        <th>fecha inicio</th>
        <th>fecha fin</th>
        <th>host</th>
    </tr>
    @foreach($session as $s)
        <tr>
            <td>{{$s['ses_usuario']}}</td>
            <td>{{$s['ses_id']}}</td>
            <td>{{$s['ses_inicio']}}</td>
            <td>{{$s['ses_fin']}}</td>
            <td>{{$s['ses_host']}}</td>
        </tr>
    @endforeach
</table>
sssssssssssss
