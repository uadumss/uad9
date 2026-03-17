<div style="height: 300px" class="overflow-auto">
    <div class="bg-verde-oscuro centrar_bloque p-1 col-md-8 rounded shadow">
        <h6 class="text-white text-center">Resultado de la busqueda resolución</h6>
    </div>

    <table class="col-md-12 table table-sm ml-5">
        <tr>
            <th>Nº Resolucion</th>
            <th>Fecha</th>
            <th>Gestión</th>
            <th>Tipo</th>
            <th>Opciones</th>
        </tr>
        @foreach($resoluciones as $r)
            <tr class="text-dark">
                <td>{{$r->res_numero}}</td>
                <td>{{$r->res_fecha}}</td>
                <td>{{$r->res_gestion}}</td>
                <td>{{$r->res_tipo." -- ".$r->res_enlace}}</td>
                <td>
                    <a href="#" class="btn btn-circle btn-light btn-sm text-primary"
                       onclick="cargarDatos('{{url('ver datos resolucion/'.$cod_tom.'/'.$r->cod_res)}}','ver_resolucion','0')" title="Ver detalle de la resolución"> <i class="fas fa-arrow-right"></i></a>
                </td>
            </tr>
        @endforeach
    </table>
</div>
