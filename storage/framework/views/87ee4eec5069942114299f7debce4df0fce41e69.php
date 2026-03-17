<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('corregir datos personales  ci - srv')): ?>
<div class="">
    <?php if(Session::has('exito persona')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo session('exito persona'); ?>

        </div>
    <?php endif; ?>
    <?php if(Session::has('error persona')): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <?php echo session('error persona'); ?>

        </div>
    <?php endif; ?>
    <?php if($persona): ?>
        <div class="rounded p-2 shadow">
            <form id="form_datos_personales">
                <?php echo csrf_field(); ?>
                <br/>
                <h4 class="text-primary font-weight-bold text-center">DATOS PERSONALES</h4>
                <br/>

                <table class="col-md-10">
                    <tr>
                        <th class="text-right font-italic">Nº CI:</th>
                        <td class="border-bottom border-dark">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm border-0" name="ci" id="e_ci"
                                       value="<?php echo e($persona->per_ci); ?>"/>
                                <span class="text-danger font-weight-bold" style="font-size: 0.9em">Exp. </span>&nbsp;&nbsp;
                                <select name="expedido" class="custom-select-sm custom-select col-md-4" id="expedido">
                                    <option value="<?php echo e($persona->per_ci_exp); ?>"><?php echo e($persona->per_ci_exp); ?></option>
                                    <option value=""></option>
                                    <option value="CB">CB</option>
                                    <option value="LP">LP</option>
                                    <option value="SC">SC</option>
                                    <option value="PT">PT</option>
                                    <option value="OR">OR</option>
                                    <option value="TA">TA</option>
                                    <option value="BE">BE</option>
                                    <option value="PA">PA</option>
                                    <option value="CH">CH</option>
                                </select>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Nº passaporte:</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="pass" id="e_pas"
                                   value="<?php echo e($persona->per_pasaporte); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Apellidos:</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="apellido" id="e_ape"
                                   value="<?php echo e($persona->per_apellido); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Nombres:</th>
                        <td class="border-bottom border-dark">
                            <input type="text" class="form-control form-control-sm border-0" name="nombre" id="e_nom"
                                   value="<?php echo e($persona->per_nombre); ?>"/>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Sexo:</th>
                        <td class="border-bottom border-dark">
                            <select class="form-control border-0 form-control-sm" name="sexo" id="e_sex">
                                    <?php if($persona->per_sexo=='M'){?>
                                <option value="M">MASCULINO</option>
                                <option value="F">FEMENINO</option>
                                <?php }else{?>
                                <option value="F">FEMENINO</option>
                                <option value="M">MASCULINO</option>
                                <?php }?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th class="text-right font-italic">Nacionalidad:</th>
                        <td class="border-bottom border-dark">
                                <?php
                                $pais="";
                                foreach ($nacionalidad as $n):
                                    if($persona->cod_nac==$n->cod_nac){
                                        $pais=$n->nac_nombre;
                                    }
                                endforeach;
                                ?>
                            <select class="form-control border-0 form-control-sm" name="nac" id="e_nac">
                                <?php if($persona->cod_nac!=''): ?>
                                    <option value="<?php echo e($persona->cod_nac); ?>"><?php echo e($pais); ?></option>
                                <?php else: ?>
                                    <option value="29">Bolivia</option>
                                <?php endif; ?>
                                <?php $__currentLoopData = $nacionalidad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($n['cod_nac']); ?>"><?php echo e($n['nac_nombre']); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="hidden" name="ip" value="<?php echo e($persona->id_per); ?>">
            </form>
            <br/><br/>
            <a href="#" onclick="enviar('form_datos_personales','<?php echo e(url("g_persona")); ?>','panel_correccion')" class="btn btn-primary btn-sm float-right">Guardar</a>
            <br/>
        </div>


    <?php else: ?>
        <div class="centrar_bloque alert-danger p-2 border-danger border rounded">
            <img src="<?php echo e(url('img/icon/eliminar.png')); ?>">&nbsp;&nbsp; No se encontró ninguna persona con el CI
        </div>
    <?php endif; ?>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/persona/fe_persona.blade.php ENDPATH**/ ?>