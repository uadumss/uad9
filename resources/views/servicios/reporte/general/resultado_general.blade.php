<div>
    <span class="font-weight-bold text-danger">RESULTADO CON EL CRITERIO DE BÚSQUEDA</span><br/><br/>
    <span class="font-weight-bold alert-success p-1 ml-5 rounded">
        @if($form['tramite']!='')
            <span class="text-dark">Trámite : </span><span class="text-primary">{{$tramite->tre_nombre}}</span> |
        @endif
            @if($form['tipo']!='')
                <span class="text-dark">Tipo de Trámite : </span><span class="text-primary">{{mb_strtoupper(\App\Models\Funciones::tipo_tramite($form['tipo']))}}</span> |
            @endif
            @if($form['fecha_inicial']!='')
                <span class="text-dark">Fecha Inicio : </span><span class="text-primary">{{date('d/m/Y', strtotime($form['fecha_inicial']))}}</span> |
            @endif
            @if($form['fecha_final']!='')
                <span class="text-dark">Fecha final: </span><span class="text-primary">{{date('d/m/Y', strtotime($form['fecha_final']))}}</span>
            @endif
    </span>
    <hr class="sidebar-divider"/>
    <div style="height: 400px;" class="overflow-auto">
        @if(sizeof($resultado)==0)
            <span class="text-danger font-weight-bold font-italic">* No se encontraron resultados</span>
        @endif
            <div>
                <table class=" table table-sm col-md-11 m-3">
                    <tr class="bg-dark text-white">
                        <th>#</th>
                        <th>Trámite</th>
                        <th>Cantidad trámites</th>

                    </tr>
                    <?php $i=1; $contador=0;?>
                    @foreach($resultado as $r)
                        <tr>
                            <td>{{$i}}</td>
                            <td class="">{{$r->tre_nombre}}</td>
                            <td class="">{{$r->cantidad}}</td>
                        </tr>
                        <?php $i++; $contador+=$r->cantidad;?>
                    @endforeach
                </table>
            </div>
    </div>
    <br/>
    <div class="col-md-12">
        <div class="float-right">
            <span class="font-weight-bold text-dark"> TOTAL TRÁMITES: </span><span class="font-weight-bold text-danger">{{$contador}}</span>
        </div>

    </div>
</div>

