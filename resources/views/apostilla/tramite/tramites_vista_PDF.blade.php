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
            height: 50px;
            /** Extra personal styles **/
            line-height: 35px;
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
                <img src="img/icon/logoUmss.png" width="60" height="80"/>
            </td>
            <td style="font-size: 11px; line-height: 150%;" valign="top">
                <h2>TRÁMITE DE APOSTILLA - UMSS</h2>
                <span style="">
                    <span style="font-weight: bold">Usuario : </span><span>{{Auth::user()->name}}</span> |
                    <span style="font-weight: bold">Fecha reporte : </span><span>{{date('d/m/Y H:i:s')}}</span>
                </span>
            </td>
            <td><img src="img/icon/logoArchivos2.jpg" width="60" height="80"/></td>
        </tr>
    </table>


    <div style="clear:both"></div>

</header>

<main>
    <div id="content" class="contenido" style="font-size: 16px">
        <div id="watermark ">
            <img id="imagen" src="../public/img/icon//logoArchivosMarcaAgua.jpg" style="width: 320px; height: 360px"/>
        </div>
        <hr/>

        <table>
            <tr>
                <td>
                    <table style="text-align: left;" cellspacing="0" >
                        <tr><td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%">Número de Trámite:</td>
                            <td style="border-bottom: #888888 solid 1px;">UAD{{$tramite_apostilla->apos_numero }}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%">Cedula de Identidad:</td>
                            <td style="border-bottom: #888888 solid 1px;">{{ $persona->per_ci }}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%">Nombre y Apellidos:</td>
                            <td style="border-bottom: #888888 solid 1px;">{{ $persona->per_nombre." ".$persona->per_apellido }}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%">Fecha de trámite:</td>
                            <td style="border-bottom: #888888 solid 1px;">{{ date('d/m/Y',strtotime($tramite_apostilla->apos_fecha_ingreso)) }}</td>
                        </tr>
                        <tr>
                            <td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%" valign="TOP">Detalle:</td>
                            <td style="border-bottom: #888888 solid 1px; font-size: 14px">
                                <?php $i=0;?>
                                @foreach($detalle_apostilla as $d)
                                        <?php $i++;?>
                                    {{$i.".- ".$d->lis_alias}}<br/>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%" valign="top">Cantidad de documetos: </td>
                            <td style="border-bottom: #888888 solid 1px;">
                                @if($i>0)
                                    @if($i==1)
                                        DEJO {{ $i }} DOCUMENTO
                                    @else
                                        DEJO {{ $i }} DOCUMENTOS
                                    @endif
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom: #888888 solid 1px;font-weight: bold; width: 30%" valign="top">Observaciones: </td>
                            <td style="border-bottom: #888888 solid 1px;">{{$tramite_apostilla->apos_obs}}</td>
                        </tr>
                    </table>
                </td>
                <td valign="top" style="width: 110px;"><div style="background-color: #FFFFFF;padding: 5px; border: #AAA solid 1px">
                        <img width="100" height="100" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('http://www.archivos.umss.edu.bo/verificar_apostilla/index.php?q='.$tramite_apostilla->apos_qr)) !!}">
                    </div>
                </td>
            </tr>
        </table>

    </div>
    <br/><br/>
    <div style="border: 1px solid #000; padding:5px 5px 5px 20px;width:350px">

        <table>
            <tr>
                <th>
                    <span style="font-size:1.4em">RECIBI CONFORME</span>
                </th>
            </tr>
            <tr>
                <td><br/>
                    <span style="">Nombre completo:</span> <font style="font-size:0.7em">(a quien pertenece el tr&aacute;mite)</font><br/><br/>
                    _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                </td>
            </tr>

            <tr>

                <td><br/>
                    <span style="">Fecha:</span><br/>
                    _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ _
                </td>
            </tr>

            <tr>
                <td align="center"><br/>
                    <br/>
                    <br/>
                    _ _ _ _ _ _ _ _ _ _ _ _ _ _ _ <br/>
                    <span style="">Firma</span>
                </td>
            </tr>

        </table>
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
