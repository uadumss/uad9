<div class="row">
    <?php

    $nuevo=explode(',',$accion['eve_nuevo']);
    $antiguo=explode(',',$accion['eve_antiguo']);
    $tam=sizeof($nuevo);
    $tam_antiguo=sizeof($antiguo);
    $i=0;
    ?>
    <div class="col-md-12 text-justify"><span>Usuario : </span><span class="text-dark font-weight-bold font-italic"> <?php echo e($conexion['bit_usuario']); ?></span> |
         <span> Objeto : </span><span class="text-dark font-weight-bold font-italic"> <?php echo e($accion['eve_tabla']); ?></span> |
         <span> Acción : </span><span class="text-dark font-weight-bold font-italic"> <?php echo e($accion['eve_operacion']); ?></span> |
        <span> Tamaños : </span><span class="text-dark font-weight-bold font-italic"><?php echo e(sizeof($nuevo). ' - '.sizeof($antiguo)); ?></span>
    </div>

    <hr class="sidebar-divider"/>
    <div class="col-md-6">
        <h4 class="text-danger text-center"> DATOS ACTUALES</h4>
        <div class="alert-primary p-2">
            <?php if($accion['eve_operacion']=='UPDATE'): ?>
                <?php for($i=0;$i<$tam;$i++): ?>
                <?php
                    if($i<$tam_antiguo && $nuevo[$i]==$antiguo[$i] ){
                        echo $nuevo[$i]."<br/>";
                    }else{
                        echo "<span class='bg-danger text-white'> $nuevo[$i]</span> <br/>";
                    }
                    ?>
                <?php endfor; ?>
            <?php else: ?>
                    <?php $__currentLoopData = $nuevo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php echo e($n); ?><br/>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-6 pl-3">
        <h4 class="text-danger text-center"> DATOS ANTIGUOS</h4>
        <div class="alert-danger p-2">
            <?php ?>
            <?php $__currentLoopData = $antiguo; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($n); ?><br/>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
    <?php if($accion['eve_tabla']=='Titulos' || $accion['eve_tabla']=='Resolucion'): ?>
        <hr class="sidebar-divider"/>
    <div class="col-md-12">
        <?php
            $objeto=json_decode($accion['eve_antiguo']);
        ?>
        <br/>
            <br/>

        <?php if(isset($objeto->pdf) && $objeto->pdf!=''): ?>
                <h5 class="text-white"> <span class="bg-danger rounded"> &nbsp;Documento eliminado&nbsp;</span></h5>
            <embed src="<?php echo e(url('titulos eliminados/'.$objeto->pdf.'/'.$accion['eve_sistema'])); ?>#toolbar=false" class="col-md-12" height="300"/>
        <?php endif; ?>
        <br/>
        <br/>
        <?php if(isset($objeto->pdf_ant) && $objeto->pdf_ant!=''): ?>

            <h5 class="text-white"> <span class="bg-danger rounded"> &nbsp;Antecedentes eliminado&nbsp;</span> </h5>
            <embed src="<?php echo e(url('titulos eliminados/'.$objeto->pdf_ant.'/'.$accion['eve_sistema'])); ?>#toolbar=0" height="300" class="col-md-12"/>


        <?php endif; ?>

    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/ver_accion.blade.php ENDPATH**/ ?>