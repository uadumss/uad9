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
                    <?php foreach ($resultado as $r):
                        if($mes==1){?>
                        "{{\App\Models\Funciones::mes($r->titulo)}}",
                    <?php  }else{ ?>
                        "{{$r->titulo}}",
                    <?php
                        }
                        endforeach;?>
                    ],
            datasets: [{
                label: "Titulos",
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

<div class="row col-md-12">

    <div class="col-md-8">
        <div>
            <span class="text-danger font-weight-bold">* CRITERIO DE BUSQUEDA</span><br/><br/>
            <div class="rounded border alert-success ml-5 font-italic text-dark p-3">
                <span class="font-weight-bold">Documentos : </span><span>
                    @if($tipo=='todos')
                        Todos los documentos
                    @else
                        {{\App\Models\Funciones::nombre_titulo($tipo)}}
                    @endif
                </span><br/>
                @if($inicio!='')
                    <span class="font-weight-bold">Gestión inicial: </span><span>{{$inicio}}</span><br/>
                @endif
                @if($fin!='')
                    <span class="font-weight-bold">Gestión final: </span><span>{{$fin}}</span><br/>
                @endif
                @if($grado!='')
                    <span class="font-weight-bold">Grado : </span><span>{{$grado}}</span><br/>
                @endif
                @if($datosCarrera)
                    <span class="font-weight-bold">Carrera : </span><span>{{$datosCarrera->car_nombre}}</span><br/>
                @endif
                @if($datosFacultad)
                    <span class="font-weight-bold">Facultad : </span><span>{{$datosFacultad->fac_nombre}}</span><br/>
                @endif
            </div>

        </div><br/>
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Gráfico estadistico</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="myAreaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Datos</h6>
            </div>
            <div class="card-body">
                <hr class="sidebar-divider"/>
                <div class="table-responsive" id="panel_grafico">
                    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr class="bg-gradient-secondary text-white text-center" style="font-size: 0.9em">
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
                                <td>{{$r->titulo}}</td>
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

</div>
