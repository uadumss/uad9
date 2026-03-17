<div>
    <br/>
    <table class="table table-sm table-hover" id="dataTable" width="100%" cellspacing="0">
        <thead>
        <tr class="bg-gradient-secondary text-white text-center">
            <th>Nº</th>
            <th>Resolución</th>
            <th>Descripción</th>
            <th>Objeto</th>
            <th>Tema</th>
            <th>Opciones</th>
        </tr>
        </thead>
        <tbody>
        <?php $i=1;?>
        <?php $__currentLoopData = $resoluciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr id="fila<?php echo e($i); ?>" style="font-size: 0.9em">
                <td class="text-primary border-right"><?php echo e($i); ?></td>
                <td id="num<?php echo e($i); ?>" class="text-right"><?php echo e($r->res_numero."/".$r->res_gestion); ?><br/></td>
                <td id="desc<?php echo e($i); ?>">
                    <div class="text-dark border-bottom "><?php echo e($r->res_desc); ?></div>
                    <span style="font-size: 0.9em">
                            <span class="font-weight-bold text-dark font-italic">Fecha: </span> <span><?php if($r->res_fecha!=''){?>
                            <?php echo e(date('d/m/Y',strtotime($r->res_fecha))); ?>

                            <?php }?></span>
                            <span class="text-danger font-weight-bold"> | </span>
                            <span class="font-weight-bold text-dark font-italic">Tomo: </span> <span><?php echo e(mb_strtoupper($r->res_tipo)); ?></span>
                            <span class="text-danger font-weight-bold"> | </span>
                            <span class="font-weight-bold text-dark font-italic">Tomo: </span> <span><?php echo e($r->tom_numero); ?></span>
                            <span class="text-danger font-weight-bold"> | </span>
                            <?php if($r->res_pdf!=''): ?>
                                <span class="font-weight-bold text-dark font-italic">Resolución: </span><img src="<?php echo e(url('img/icon/tit.gif')); ?>" width="15" height="15">
                                <span class="text-danger font-weight-bold"> | </span>
                            <?php endif; ?>
                            <?php if($r->res_ant!=''): ?>
                                <span class="font-weight-bold text-dark font-italic">Antecedentes: </span><img src="<?php echo e(url('img/icon/antecedente.gif')); ?>" width="15" height="15">
                            <?php endif; ?>
                        </span>
                </td>

                <td id="obj<?php echo e($i); ?>"><?php echo e($r->res_objeto); ?></td>
                <td id="tem<?php echo e($i); ?>"><?php echo e($r->res_tema); ?></td>
                <td class="text-right">
                    <form id="resolucion<?php echo e($i); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="cr" value="<?php echo e($r->cod_res); ?>">
                        <input type="hidden" name="cs" value="<?php echo e($cod_san); ?>">
                    </form>
                    <button href="" class="btn btn-danger btn-sm btn-circle" title="Asignar resolucion" title="Ver detalle de la resolución"
                       onclick="enviar('resolucion<?php echo e($i); ?>','<?php echo e(url('asignar resolucion sancionado')); ?>','panel_documento');$('#Modal2').modal('hide')"> <i class="fas fa-arrow-circle-right"></i>
                    </button>
                </td>
            </tr>
                <?php $i++;?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/no_atentado/sancionado/lista_resolucion.blade.php ENDPATH**/ ?>