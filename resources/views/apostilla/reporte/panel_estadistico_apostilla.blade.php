<script src="{{url("vendor/chart.js/Chart.min.js")}}"></script>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';


    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
@php

@endphp
    // Area Chart Example
    var ctx = document.getElementById("myAreaChart");
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels:[

                <?php foreach ($resultado as $r): ?>
                    "{{$r->nombre}}",
                    <?php
                endforeach;?>
                    ],
            datasets: [{
                label: "Documentos",
                lineTension: 0.3,
                backgroundColor: "rgba(78, 115, 223, 0.05)",
                borderColor: "rgba(78, 115, 223, 1)",
                pointRadius: 3,
                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                pointBorderColor: "rgba(78, 115, 223, 1)",
                pointHoverRadius: 3,
                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                pointHitRadius: 10,
                pointBorderWidth: 2,
                data: [
                    <?php foreach ($resultado as $r):?>
                        {{$r->cantidad}},
                    <?php endforeach;?>
                ],
            }],
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                xAxes: [{
                    time: {
                        unit: 'date'
                    },
                    gridLines: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        maxTicksLimit: 0
                    }
                }],
                yAxes: [{
                    ticks: {
                        maxTicksLimit: 5,
                        padding: 10,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                            return number_format(value);
                        }
                    },
                    gridLines: {
                        color: "rgb(234, 236, 244)",
                        zeroLineColor: "rgb(234, 236, 244)",
                        drawBorder: false,
                        borderDash: [2],
                        zeroLineBorderDash: [2]
                    }
                }],
            },
            legend: {
                display: false
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, chart) {
                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                        return datasetLabel + ': ' + number_format(tooltipItem.yLabel);
                    }
                }
            }
        }
    });
</script>
@if(Session::has('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-label="close">
            <span aria-hidden="true">&times;</span>
        </button>
        <span class="font-weight-bold text-dark">{!! session('error') !!}</span>
    </div>
@endif

<div>
    <span class="text-danger font-weight-bold">* CRITERIO DE BUSQUEDA</span><br/><br/>
    <div class="rounded border alert-success ml-5 font-italic text-dark p-2">
        <span class="font-weight-bold">Documentos : </span><span>
            @if($documento=='')
                Todos los documentos
            @else
                @if($documento=='tramites')
                    Trámites ingresado
                @else
                    {{\App\Models\Lista_doc_apostilla::find($documento)->lis_alias}}
                @endif
            @endif
                </span><br/>
            <?php
                $fechainicial="";
                $fechainicial=($form['dia']!='')?$form['dia'].'/':'';
                $fechainicial.=($form['mes']!='')?$form['mes'].'/':'';
                $fechainicial.=($form['gestion']!='')?$form['gestion'].'':'';

                $fechafinal="";
                if($form['dia']) {
                    $fechafinal=($form['dia_final']!='')?$form['dia_final'].'/':'';
                }
                if($form['mes']) {
                    $fechafinal .= ($form['mes_final'] != '') ? $form['mes_final'] . '/' : '';
                }
                $fechafinal.=($form['gestion_final']!='')?$form['gestion_final'].'':'';
            ?>
            @if($fechainicial)
                <span class="font-weight-bold">Fecha inicial: </span><span>{{$fechainicial}}</span><br/>
                @if($fechainicial)
                    <span class="font-weight-bold">Fecha final: </span><span>{{$fechafinal}}</span><br/>
                @endif
            @endif
    </div>
</div>
</br>
<div class="row col-md-12">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header py-3 bg-primary  text-white">
                <h6 class="m-0 font-weight-bold">Datos</h6>
            </div>
            <div class="card-body">
                <hr class="sidebar-divider"/>
                <div class="table-responsive" id="panel_grafico" style="font-size: 14px">
                    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-gradient-info text-white text-center" style="font-size: 0.9em">
                            <th>Nº</th>
                            <th>Documento</th>
                            <th class="text-right">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; $total=0;?>
                        @foreach($resultado as $r)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$r->nombre}}</td>
                                <td class="text-right">{{$r->cantidad}}</td>
                            </tr>
                                <?php $i++; $total+=$r->cantidad;?>
                        @endforeach
                        <tr class="bg-light">
                            <th colspan="2" class="text-center">TOTAL</th>
                            <th class="text-right">{{$total}}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">Gráfico estadistico</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
