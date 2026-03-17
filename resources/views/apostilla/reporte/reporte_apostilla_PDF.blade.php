<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family:Helvetica,Futura,Arial,Verdana,sans-serif;
        }
        .pagenum:before {

        }
        /** Define the margins of your page **/
        @page {
            margin-top: 150px;
            margin-left: 100px;
            margin-right: 80px;
            margin-bottom: 125px;
        }
        #watermark {
            position: fixed;
            top: 200px;
            bottom: 100px;
            left:     150px;
            /** El ancho y la altura pueden cambiar
                según las dimensiones de su membrete
            **/
            width:    7.8cm;
            height:   10cm;

            /** Tu marca de agua debe estar detrás de cada contenido **/
            z-index:  -1000;
        }
        #imagen{
            filter:alpha(opacity=90);
            opacity:.90;
        }
        header {
            position: fixed;
            top: -100px;
            left: 0px;
            right: 0px;
            height: 65px;
            /** Extra personal styles **/
            line-height: 40px;
        }
        footer {
            position: fixed;
            bottom: -50px;
            left: 0px;
            right: 0px;
            height: 40px;
            text-align: center;
        }
    </style>
</head>
<body>
<header>

    <table style="width: 100%" border="0" cellpadding="4" cellspacing="0">
        <tr>
            <td style="text-align: center;">
                <img src="img/icon/logoUmss.png" width="55" height="75"/>
            </td>
            <td style="font-size: 11px; line-height: 150%;" valign="top">
                <h2>TRÁMITE DE APOSTILLA - UMSS</h2>
                <span style="">
                    <span style="font-weight: bold">Usuario : </span><span>{{Auth::user()->name}}</span> |
                    <span style="font-weight: bold">Fecha reporte : </span><span>{{date('d/m/Y H:i:s')}}</span>
                </span>
            </td>
            <td><img src="img/icon/logoArchivos2.jpg" width="55" height="75"/></td>
        </tr>
    </table>


    <div style="clear:both"></div>

    <hr/>
</header>

<main>
    <div id="content" class="contenido" style="font-size: 16px">
        <div id="watermark ">
            <img id="imagen" src="../public/img/icon//logoArchivosMarcaAgua.jpg" style="width: 320px; height: 360px"/>
        </div>

        <div style="font-size: 11px;">
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
                </span> |
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
                        <span class="font-weight-bold">Fecha inicial: </span><span>{{$fechainicial}}</span> |
                        @if($fechainicial)
                            <span class="font-weight-bold">Fecha final: </span><span>{{$fechafinal}}</span>
                        @endif
                    @endif
                </div>
            </div>
            @if($mensaje!="")
                <br/>
                <br/>
                <span style="color: red;">
                    {{$mensaje}}
                </span>
            @else
                <br/>
                <table width="100%" cellspacing="0" >
                    <thead>
                    <tr class="bg-gradient-info text-white text-center" style="border: #000 solid 1px;background-color: #ddd" >
                        <th>Nº</th>
                        <th>Documento</th>
                        <th class="text-right">Cantidad</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; $total=0;?>
                    @foreach($resultado as $r)
                        <tr>
                            <td style="border: #000 solid 1px;">{{$i}}</td>
                            <td style="border: #000 solid 1px;">{{$r->nombre}}</td>
                            <td style="border: #000 solid 1px;text-align: right">{{$r->cantidad}}</td>
                        </tr>
                            <?php $i++; $total+=$r->cantidad;?>
                    @endforeach
                    <tr class="bg-light"  style="border: #000 solid 1px; background-color: #ddd">
                        <th colspan="2" class="text-center">TOTAL</th>
                        <th class="text-right">{{$total}}</th>
                    </tr>
                    </tbody>
                </table>
            @endif

        </div>
    </div>
</main>
<footer>
    <hr />
    <table>
        <tr>
            <td VALIGN="TOP" style="font-size: 12px">
                Av.Oquendo y Jordan - Casilla 992 - Telefonos 4232541 - 44 (Int.358) Fax 4-4115900 Dir.4544098 e-mail:archivos@umss.edu.bo
            </td>
        </tr>
    </table>
</footer>
</body>
</html>
