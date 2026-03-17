<html lang="en">
<head>
	<meta charset="utf-8">
    <style>
        .pagenum:before {
            content: counter(page);
        }
        /** Define the margins of your page **/
        @page {
            margin-top: 125px;
            margin-left: 95px;
            margin-right: 15px;
            margin-bottom: 70px;
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

        <table style="width: 100%" border="0">
            <tr>
                <td><img src="img/icon/logoArchivos2.jpg" width="30" height="40"/></td>
                <td style="font-weight: bold; font-size: 1.2em"><?php echo e(mb_strtoupper($tipo_completo)); ?> - <?php echo e($gestion); ?>&nbsp;</td>
                <td>
                    <div style="font-size: 1.3em;border: #757d87 solid 2px; text-align: center; padding-bottom: 5px;" >
                    TOMO : <?php echo e($tomo->tom_numero); ?>

                    </div>
                </td>
                <td style="text-align: center;">
                    <img src="img/icon/logoUmss.png" width="30" height="40"/>
                </td>
            </tr>
        </table>
    <span style="font-size: 0.8em;float: left">
            <span style="font-weight: bold">Usuario : </span><span><?php echo e(Auth::user()->name); ?></span>&nbsp;&nbsp;&nbsp;&nbsp;
            <span style="font-weight: bold">Fecha : </span><span><?php echo e(date('d/m/Y H:i:s')); ?></span>
            <span style="font-weight: bold">Página : <span class="pagenum" style=""></span></span>
    </span>
    <div style="clear:both"></div>
    <hr style="color: #757d87"/>
</header>

<main>
    <table style="font-size: 15px" border="0" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr>
            <th>Nº</th>
            <th style="width: 300px">Nombre</th>
            <th>C.I.</th>
            <th>País</th>
            <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){ ?>
            <th>Facultad</th>
            <th>Carrera</th>
            <?php } ?>
            <?php if($tipo=='tpos' || $tipo=='di'){ ?>
            <th>Mención</th>
            <?php } ?>
            <th>Fecha</th>
            <th style="text-align: center">Número</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        <?php $__currentLoopData = $titulo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="fila<?php echo e($i); ?>">
                <td style="font-size: 0.8em" valign="top" ><?php echo e($i); ?>.<br/></td>
                <td style="font-size: 0.8em"><?php echo e($t->per_apellido." , ".$t->per_nombre); ?>

		<?php if($tipo=='tp'){?>
                    <?php if($t->tit_revalida=='t'){?>
                        <span class="bg-danger text-white p-1 rounded font-weight-bolder lead" style="font-size: 0.7em;" > (*R)</span>
                    <?php }?>
		<?php }?>
                </td>
                <td style="font-size: 0.7em;text-align: center;">
                    <?php if($t->per_ci!=''): ?>
                        <?php echo e($t->per_ci); ?>

                    <?php else: ?>
                        <?php if($t->per_pasaporte!=''): ?>
                            P:<?php echo e($t->per_pasaporte); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </td>
                <td style="font-size: 0.7em;text-align: center"><?php echo e($t->nac_codigo); ?></td>
                <?php if($tipo=='ca' || $tipo=='da' || $tipo=='tp' || $tipo=='tpa'){ ?>
                <td style="font-size: 0.7em;text-align: center;"><?php echo e($t->fac_abreviacion); ?></td>
                <td style="font-size: 0.7em;text-align: center;"><?php echo e($t->car_abreviacion); ?></td>
                <?php }?>
                <?php if($tipo=='tpos' || $tipo=='di'){ ?>
                <td style="font-size: 0.7em;text-align:left"><?php echo e($t->tit_titulo); ?></td>
                <?php } ?>
                <td style="font-size: 0.7em; text-align: center"><?php echo e(date('d/m/Y',strtotime($t->tit_fecha_emision))); ?></td>
                <td style="font-size: 0.7em; text-align: center "><?php echo e($t->tit_nro_titulo); ?></td>
            </tr>
            <?php $i++;?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

</main>
<footer>
    <hr style="color: #757d87"/>
</footer>
</body>
</html>
<?php /**PATH C:\Users\Pc\Desktop\V2\uad9\resources\views/diplomas/tomo/listaPDF.blade.php ENDPATH**/ ?>