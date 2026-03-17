<script type="text/javascript">
$(document).ready(function(){
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['Fecha', 'Tramites ingresados'],
    @foreach($resultado as $d)
            @if($mes==1)
                ["{{strtoupper(\App\Models\Funciones::mes($d->mes))}}",{{$d->cantidad}}],
            @else
                ["{{$d->dtra_gestion_tramite}}",{{$d->cantidad}}],
            @endif
    @endforeach
    ]);
    var options = {
        @if($tipo=='tramite')
            title: "Trámite : {{$tramite->tre_nombre}}",
        @else
            @if($tipo=='tipo')
                title: "Tipo de trámite : {{\App\Models\Funciones::tipo_tramite($form['tipo'])}}",
            @else
                title: "Tipo de tràmite : General",
            @endif
        @endif
            colors:['#0000FF','#FF0000'],
            legend: { position: 'right' },
            /*curveType: 'function',*/
            height:470,
            hAxis:{title:"Periodos"},
            vAxis:{title:'Cantidad de trámites',
            minValue:0,
        }
    };
    var chart = new google.visualization.LineChart(document.getElementById('panel_grafico'));
    chart.draw(data, options);
    }

});
</script>
<div>
    @if(Session::has('error_modal'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold text-dark">{!! session('error_modal') !!}</span>
        </div>
    @endif

    <span class="font-weight-bold text-danger">RESULTADO CON EL CRITERIO DE BÚSQUEDA</span><br/><br/>
    <span class="font-weight-bold alert-success p-1 ml-5 rounded">
        @if($form['tramite']!='')
            <span class="text-dark">Trámite : </span><span class="text-primary">{{$tramite->tre_nombre}}</span> |
        @endif
            @if($form['tipo']!='')
                <span class="text-dark">Tipo de Trámite : </span><span class="text-primary">{{mb_strtoupper(\App\Models\Funciones::tipo_tramite($form['tipo']))}}</span> |
            @endif
            @if($form['gestion_inicial']!='')
                <span class="text-dark">Gestión Inicio : </span><span class="text-primary">{{$form['gestion_inicial']}}</span> |
            @endif
            @if($form['gestion_final']!='')
                <span class="text-dark">Gestión final: </span><span class="text-primary">{{$form['gestion_final']}}</span>
            @endif
    </span>
    <hr class="sidebar-divider"/>
        @if($no_valido!='t')
            <div style="height: 470px; " class="overflow-auto" id="panel_grafico">

            </div>

        @endif

</div>

