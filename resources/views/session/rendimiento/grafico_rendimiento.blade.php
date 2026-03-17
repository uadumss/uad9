<?php
use App\Models\Funciones;
$fun=new Funciones();
?>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	 @if(sizeof($diario)>0)
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Fecha', 'Línea de Rendimiento'],
                @foreach($diario as $d)
                ["{{date('d/m/Y',strtotime($d->bit_inicio))}}",{{$d->cantidad}}],
            @endforeach
        ]);
        var options = {
            title: "Funcionario : {{$usuario->name}} ",
            colors:['#00FF00'],
            legend: { position: 'bottom' },
            height:400,
            hAxis:{title:'Rendimiento del mes de {{$fun->mes($mes)}} del {{$año}}'},
            vAxis:{title:'Rendimiento',
                minValue:0,
                maxValue:10
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById('line_chart'));
        chart.draw(data, options);
    }
    @endif
});
$(document).ready(function(){
	@if(sizeof($diario1)>0)
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Fecha', 'Línea de Rendimiento'],
                @foreach($diario1 as $d)
            ["{{date('d/m/Y',strtotime($d->bit_inicio))}}",{{$d->cantidad}}],
            @endforeach
        ]);
        var options = {
            title: "Funcionario : {{$usuario->name}} ",
            colors:['#0000FF','#00FF00'],
            height:400,
            legend: { position: 'bottom' },
            hAxis:{title:'Rendimiento del mes de {{$fun->mes($mes)}} del {{$año}}'},
            vAxis:{title:'Rendimiento',
                minValue:0,
                maxValue:10
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById('line_chart2'));
        chart.draw(data, options);
    }
    @endif
});
$(document).ready(function(){
	@if(sizeof($diario2)>0)
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Fecha', 'Línea de Rendimiento'],
                @foreach($diario2 as $d)
            ["{{date('d/m/Y',strtotime($d->bit_inicio))}}",{{$d->cantidad}}],
            @endforeach
        ]);
        var options = {
            title: "Funcionario : {{$usuario->name}} ",
            colors:['#ff0000'],
            height:400,
            legend: { position: 'bottom' },
            hAxis:{title:'Rendimiento del mes de {{$fun->mes($mes)}} del {{$año}}'},
            vAxis:{title:'Rendimiento',
                minValue:0,
                maxValue:10
            }
        };
        var chart = new google.visualization.LineChart(document.getElementById('line_chart3'));
        chart.draw(data, options);
    }
    @endif
});
//para dias
</script>
<div>
    <?php $titulo="";
    if($mi==$mf){
        $titulo="Rendimiento del mes de ".$fun->mes($mes)." del $año";
    }else{
        $titulo="Rendimiento de ".$fun->mes($mi)." a ".$fun->mes($mf)." del $año";
    }
    ?>
    <div class="alert-primary centrar_bloque col-md-5 p-2 mb-4 rounded shadow">
        <h5 class="text-dark text-center font-weight-bolder">{{$titulo}} </h5>
    </div>


    <div class="card border rounded border-left-success">
            <div class="card-body">
                <span class="font-italic font-weight-bold text-success"> Creación de datos</span>
                <div id="line_chart" class="col-md-12 border mt-2">
                    @if(sizeof($diario)==0)
                        <span class="font-italic text-dark"> * No hay datos para mostrar</span>
                    @endif
                </div>
            </div>
    </div>

<br/>
        <div class="card border rounded border-left-primary">
            <div class="card-body">
                <span class="font-italic font-weight-bold text-primary"> Edición de datos</span>
                    <div id="line_chart2" class="col-md-12 border mt-2">
                        @if(sizeof($diario1)==0)
                            <span class="font-italic text-dark"> * No hay datos para mostrar</span>
                        @endif
                    </div>
            </div>
        </div>
    <br/>
        <div class="card border rounded border-left-danger-primary">
            <div class="card-body">
                <span class="font-italic font-weight-bold text-danger"> Eliminación de datos</span>
                <div id="line_chart3" class="col-md-12 border mt-2">
                    @if(sizeof($diario2)==0)
                        <span class="font-italic text-dark"> * No hay datos para mostrar</span>
                    @endif
                </div>
            </div>
        </div>
</div>
