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
                <?php if($tramite): ?>
                    <?php echo e($tramite->tre_nombre); ?>

                <?php else: ?>
                    TODOS LOS TRÁMITES
                <?php endif; ?>
                |
                <span style="font-weight: bold">Solicitud : </span><?php echo e($tipo_solicitud); ?> <br/>
                <?php if($final!=''): ?>
                    <span style="font-weight: bold">Rango : </span><?php echo e(date('d/m/Y',strtotime($inicial))." - ".date('d/m/Y',strtotime($final))); ?>

                <?php else: ?>
                    <span style="font-weight: bold">Fecha : </span><?php echo e(date('d/m/Y',strtotime($inicial))); ?>

                <?php endif; ?>

            </td>
            <td style="text-align: center;">
                <img src="img/icon/logoUmss.png" width="40" height="50"/>
            </td>
        </tr>
    </table>
    <span style="font-size: 0.8em;float: left">
            <span style="font-weight: bold">Usuario : </span><span><?php echo e(Auth::user()->name); ?></span> |
            <span style="font-weight: bold">Fecha reporte : </span><span><?php echo e(date('d/m/Y H:i:s')); ?></span>
    </span>

    <div style="clear:both"></div>

</header>

<main>
    <?php if(sizeof($resultado)>0): ?>
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
        <?php $__currentLoopData = $resultado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="fila<?php echo e($i); ?>" style="font-size: 10px">
                <td><?php echo e($i); ?>.<br/></td>
                <td><?php echo e($r->per_apellido." , ".$r->per_nombre); ?></td>
                <td><?php echo e($r->per_ci); ?></td>
                <td><?php echo e($r->tra_numero); ?></td>
                <td style="text-align: right">
                    <?php if($r->dtra_interno=='f'): ?>
                        Externo
                    <?php else: ?>
                        Interno
                    <?php endif; ?>
                </td>
                <td style="text-align: right"><?php if($r->tra_fecha_solicitud!=''): ?>
                        <?php echo e(date('d/m/Y',strtotime($r->tra_fecha_solicitud))); ?>

                    <?php endif; ?>
                </td>
                <td>
                    <?php echo e($r->tre_nombre); ?>

                </td>
                <td style="text-align: right"><?php if($r->dtra_fecha_recojo!=''): ?>
                        <?php echo e(date('d/m/Y H:i:s',strtotime($r->dtra_fecha_recojo))); ?>

                    <?php endif; ?>
                </td>
                <td style="text-align: right">
                    <?php if($r->dtra_entregado=='t'): ?>
                        TITULAR
                    <?php else: ?>
                        APODERADO
                    <?php endif; ?>
                </td>
            </tr>
                <?php $i++;?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
        <hr style="color: #757d87"/>
        <br/>
        <div style="height: 30px; color: #FF0000; text-align: center; padding-top: 5px">
            No existen datos
        </div>
    <?php endif; ?>
</main>
<footer>
    <hr style="color: #757d87"/>
</footer>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/reporte/entrega/resultado_entregasPDF.blade.php ENDPATH**/ ?>