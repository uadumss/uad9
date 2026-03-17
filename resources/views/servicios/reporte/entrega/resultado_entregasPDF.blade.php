<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        .pagenum:before {
            content: "Page " counter(page) " of " counters(pages);
        }
        /** Define the margins of your page **/
        @page {
            margin-top: 150px;
            margin-left: 100px;
            margin-right: 80px;
            margin-bottom: 125px;
        }
        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 50px;
            /** Extra personal styles **/
            line-height: 35px;
        }
        footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            text-align: center;
            line-height: 35px;
        }
    </style>
</head>
<body>
<header>

    <table style="width: 100%" border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td><img src="img/icon/logoArchivos2.jpg" width="40" height="50"/></td>
            <td style="font-size: 11px; line-height: 150%;" valign="top">
                <span style="font-weight: bold">REPORTE DE TRÁMITES ENTREGADOS</span><br/>
                <span style="font-weight: bold">Trámite : </span>
                @if($tramite)
                    {{$tramite->tre_nombre}}
                @else
                    TODOS LOS TRÁMITES
                @endif
                |
                <span style="font-weight: bold">Solicitud : </span>{{$tipo_solicitud}} <br/>
                @if($final!='')
                    <span style="font-weight: bold">Rango : </span>{{date('d/m/Y',strtotime($inicial))." - ".date('d/m/Y',strtotime($final))}}
                @else
                    <span style="font-weight: bold">Fecha : </span>{{date('d/m/Y',strtotime($inicial))}}
                @endif

            </td>
            <td style="text-align: center;">
                <img src="img/icon/logoUmss.png" width="40" height="50"/>
            </td>
        </tr>
    </table>
    <span style="font-size: 0.8em;float: left">
            <span style="font-weight: bold">Usuario : </span><span>{{Auth::user()->name}}</span> |
            <span style="font-weight: bold">Fecha reporte : </span><span>{{date('d/m/Y H:i:s')}}</span>
    </span>

    <div style="clear:both"></div>

</header>

<main>
    @if(sizeof($resultado)>0)
    <table style="font-size: 15px" border="1" id="dataTable" width="100%" cellspacing="0" style="font-size: 10px">
        <thead>
        <tr style="background-color: #DDDDDD">
            <th>Nº</th>
            <th style="width: 200px">Nombre</th>
            <th>CI</th>
            <th>N° atención</th>
            <th>Tipo de solicitud</th>
            <th>Fecha de solicitud</th>
            <th>Trámite</th>
            <th>Fecha recojo</th>
            <th>Entregado A</th>

        </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        @foreach($resultado as $r)
            <tr id="fila{{$i}}" style="font-size: 10px">
                <td>{{$i}}.<br/></td>
                <td>{{$r->per_apellido." , ".$r->per_nombre}}</td>
                <td>{{$r->per_ci}}</td>
                <td>{{$r->tra_numero}}</td>
                <td style="text-align: right">
                    @if($r->dtra_interno=='f')
                        Externo
                    @else
                        Interno
                    @endif
                </td>
                <td style="text-align: right">@if($r->tra_fecha_solicitud!='')
                        {{date('d/m/Y',strtotime($r->tra_fecha_solicitud))}}
                    @endif
                </td>
                <td>
                    {{$r->tre_nombre}}
                </td>
                <td style="text-align: right">@if($r->dtra_fecha_recojo!='')
                        {{date('d/m/Y H:i:s',strtotime($r->dtra_fecha_recojo))}}
                    @endif
                </td>
                <td style="text-align: right">
                    @if($r->dtra_entregado=='t')
                        TITULAR
                    @else
                        APODERADO
                    @endif
                </td>
            </tr>
                <?php $i++;?>
        @endforeach
        </tbody>
    </table>
    @else
        <hr style="color: #757d87"/>
        <br/>
        <div style="height: 30px; color: #FF0000; text-align: center; padding-top: 5px">
            No existen datos
        </div>
    @endif
</main>
<footer>
    <hr style="color: #757d87"/>
</footer>
</body>
</html>
