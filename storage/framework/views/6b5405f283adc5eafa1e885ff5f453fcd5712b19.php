<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        #watermark {
            position: fixed;
            <?php switch ($tramite_noatentado->dtra_glosa_posicion){
                case (0): echo  "top: 100px";break;
                case (1): echo  "top: 300px";break;
                case (2): echo  "top: 500px";break;
                case (3): echo  "top: 700px";break;
                case (4): echo  "top: 900px";break;
            }
            ?>
            bottom: 100px;
            left:     250px;
            /** El ancho y la altura pueden cambiar
                según las dimensiones de su membrete
            **/
            width:    7.8cm;
            height:   10cm;

            /** Tu marca de agua debe estar detrás de cada contenido **/
            z-index:  -1000;
        }
                     /** Define the margins of your page **/
        @page {
	    margin-top: 170px;
            margin-left: 90px;
            margin-right: 75px;
            margin-bottom: 100px;
        }

        header {
            position: fixed;
            top: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** Extra personal styles **/
            background-color: #03a9f4;
            color: white;
            text-align: center;
            line-height: 35px;
        }

        footer {
            position: fixed;
            bottom: 0px;
            left: 20px;
            right: 0px;
            height: 10px;
            /*line-height: 35px;*/
        }

    </style>
</head>
<body id="page-top">
<?php switch($tramite_noatentado->dtra_glosa_posicion):
    case (0): ?>

    <?php break; ?>
    <?php case (1): ?>
        <br/><br/><br/><br/><br/><br/><br/>
    <?php break; ?>
    <?php case (2): ?>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/>
    <?php break; ?>
    <?php case (3): ?>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

    <?php break; ?>
    <?php case (4): ?>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <br/>

    <?php break; ?>
<?php endswitch; ?>

<div class="row">
    <footer>
        <?php
            $nombre=explode(' ',Auth::user()->name);
            $iniciales='';
            foreach ($nombre as $n){
                $iniciales.=substr($n,0,1);
            }
        ?>
        <span style="font-size: 0.7em;">
            <?php echo e($iniciales." - ".date('d/m/Y H:i:s')); ?><br/>
            IMPORTANTE. Para que este trámite tenga validez legal, necesariamente debe ser refrendado con la firma del (la) Secretario(a) General (Av. Ballivian esq. Reza, EDIFICIO RECTORADO).
        </span>
    </footer>

    <div>
        <div id="watermark ">
            <img src="../public/img/icon/logo-umss-fondo.jpg" style="width: 220px; height: 260px"/>
        </div>
        <table>
            <tr>
                <td style="padding: 5px;"><img width="130" height="130" src="data:image/png;base64, <?php echo base64_encode(QrCode::format('png')->size(100)->generate('http://www.archivos.umss.edu.bo/verificar_tramite/index.php?q='.$tramite_noatentado->dtra_qr)); ?>"></td>
                <td style="padding-left: 5px;">
                    <span style="font-weight: bold; text-align: justify-all"><?php echo e($tramite_noatentado->dtra_titulo); ?></span><br/><br/>
                    <span style="font-style: italic; font-weight: bold"><?php echo e("ARCH ".$tramite_noatentado->dtra_numero_tramite."/".$tramite_noatentado->dtra_gestion_tramite); ?></span><br/>
                </td>
            </tr>
        </table>

        <div>
            <div style="text-align: justify-all;font-size: 14px;"> <?php echo $tramite_noatentado->dtra_glosa ?></div>
        </div>
        <div style="text-align: center">
            <?php echo e($tramite_noatentado->dtra_fecha_literal); ?>

        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/tramite/pdf_noatentado.blade.php ENDPATH**/ ?>
