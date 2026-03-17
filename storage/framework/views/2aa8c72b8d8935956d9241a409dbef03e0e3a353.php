<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content border-bottom-primary shadow-lg">
        <div class="modal-header bg-verde-oscuro">
            <h5 class="modal-title text-white" id="exampleModalLabel"> <i class="fas fa-folder-open"></i>&nbsp;&nbsp;<?php echo e(strtoupper($tipo)); ?></h5>
            <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body" style="font-size: 12px;">
            <?php if(Session::has('exitof')): ?>
                <div class="alert alert-success alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('exitof'); ?>

                </div>
            <?php endif; ?>
            <?php if(Session::has('errorf')): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <?php echo session('errorf'); ?>

                </div>
            <?php endif; ?>
            <div id="panel_frente">
                <span class="text-dark font-italic">
                    <br/>
                                <span>CONSEJO : </span>
                                <span class="font-weight-bold"><?php echo e(strtoupper($tipo)); ?></span> &nbsp; | &nbsp;
                                <span>Facultad : </span>
                                <span class="font-weight-bold"><?php echo e($facultad->fac_nombre); ?></span> &nbsp; | &nbsp;
                                <?php if($carrera): ?>
                                    <span>Carrera : </span>
                                    <span class="font-weight-bold text-primary"><?php echo e($carrera->car_nombre); ?></span>
                                <?php endif; ?>

                </span>
                <br/>
                <br/>
                <div class="row">
                    <div class="col-md-4">
                        <form id="form_frente">
                            <?php echo csrf_field(); ?>
                            <?php if($cod_fre==0): ?>
                                <span class="font-italic text-primary font-weight-bold">* Datos del nuevo Frente</span>
                                <br/>
                                <br/>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="text-dark">Nombre:</th>
                                        <td class="">
                                            <input type="text" name="nombre" class="form-control form-control-sm border-0"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Fecha de inicio:</th>
                                        <td class="">
                                            <input type="date" name="inicio" class="form-control form-control-sm"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Fecha de Conclusión:</th>
                                        <td class="">
                                            <input type="date" name="fin" class="form-control form-control-sm"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Estamento :</th>
                                        <td class="">
                                            <input type="radio" name="estamento" value="t" checked> <span class="font-italic text-primary">Docente</span> <br/>
                                            <input type="radio" name="estamento" value="f"> <span class="font-italic text-primary">Estudiantil</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Vigente:</th>
                                        <td class="">
                                            &nbsp;<input type="checkbox" name="vigente"/>
                                        </td>
                                    </tr>
                                </table>
                            <?php else: ?>
                                <span class="font-italic text-primary font-weight-bold">* Datos del Frente</span>
                                <br/>
                                <br/>
                                <table class="table table-sm">
                                    <tr>
                                        <th class="text-dark">Nombre:</th>
                                        <td class="">
                                            <input type="text" name="nombre" class="form-control form-control-sm border-0" value="<?php echo e($frente->fre_nombre); ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Fecha de inicio:</th>
                                        <td class="">
                                            <input type="date" name="inicio" class="form-control form-control-sm" value="<?php echo e($frente->fre_fecha_inicio); ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Fecha de Conclusión:</th>
                                        <td class="">
                                            <input type="date" name="fin" class="form-control form-control-sm"value="<?php echo e($frente->fre_fecha_fin); ?>"/>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Estamento :</th>
                                        <td class="">
                                            <?php if($frente->fre_docente=='t'): ?>
                                                <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docente</span>
                                            <?php else: ?>
                                                <span class="bg-danger rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiantil</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-dark">Vigente:</th>
                                        <td class="">
                                            <?php if($frente->fre_vigente=='t'): ?>
                                                &nbsp;<input type="checkbox" name="vigente" checked/>
                                            <?php else: ?>
                                                &nbsp;<input type="checkbox" name="vigente"/>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" name="cfre" value="<?php echo e($cod_fre); ?>">

                            <?php endif; ?>
                            <?php $cod=0;?>
                            <?php if($tipo=='hcu'): ?>
                                <input type="hidden" name="cf" value="<?php echo e($facultad->cod_fac); ?>">
                                <input type="hidden" name="tipo" value="hcu">
                                <?php $cod=$facultad->cod_fac;?>
                            <?php else: ?>
                                <?php if($tipo=='hcf'): ?>
                                <input type="hidden" name="cc" value="<?php echo e($carrera->cod_car); ?>">
                                <input type="hidden" name="tipo" value="hcf">
                                    <?php $cod=$carrera->cod_car;?>
                                <?php else: ?>
                                    <?php if($tipo=='hcc'): ?>
                                        <input type="hidden" name="cc" value="<?php echo e($carrera->cod_car); ?>">
                                        <input type="hidden" name="tipo" value="hcc">
                                            <?php $cod=$carrera->cod_car;?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            <?php endif; ?>
                        </form>

                            <button class="btn btn-primary btn-sm float-right" type="button" data-dismiss="modal"
                                onclick="enviar('form_frente','<?php echo e(url("g_frente")); ?>','panel_consejeros');
                                cargarDatos('<?php echo e(url("fu_consejo/".$tipo."/".$cod)); ?>','panel_frente')
                                ">Guardar</button>

                    </div>
<!-- LISTA DE SONSEJEROS-->
                    <div class="col-md-8 shadow-lg p-3" id="panel_lista">
                        <?php if($cod_fre!=0): ?>
                            <span class="font-italic text-primary font-weight-bold">* Datos de consejeros
                                <?php if($frente->fre_docente=='t'): ?>
                                    <span class="bg-success rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Docentes</span>
                                <?php else: ?>
                                    <span class="bg-danger rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Estudiantiles</span>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                        <br/>
                        <br/>

                        <?php if($cod_fre!=0): ?>
                                <div class="overflow-auto" style="height: 300px">
                                    <table class="table table-sm">
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>CI</th>
                                            <th>Participacion</th>
                                            <th>Renuncia</th>
                                            <th>Opciones</th>
                                        </tr>
                                            <?php $i=0;?>

                                        <?php $__currentLoopData = $consejeros; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($i+=1); ?></td>
                                                <td><?php echo e($c->per_apellido." ".$c->per_nombre); ?></td>
                                                <td><?php echo e($c->per_ci); ?></td>
                                                <td>
                                                    <?php if($c->ele_titular=='t'): ?>
                                                        <span class="bg-dark rounded pr-1 pl-1 text-white font-weight-bold font-italic" style="font-size: 14px;">Titular</span>
                                                    <?php else: ?>
                                                        <span class="bg-light rounded pr-1 pl-1 text-dark font-weight-bold font-italic" style="font-size: 14px;">Suplente</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($c->ele_fecha_renuncia!=''): ?>
                                                        <?php echo e(date('d/m/Y',strtotime($c->ele_fecha_renuncia))); ?>

                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('eliminar consejero - cla')): ?>
                                                    <form id="el<?php echo e($i); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <input type="hidden" name="cc" value="<?php echo e($c->cod_ele); ?>">
                                                        <input type="hidden" name="cf" value="<?php echo e($frente->cod_fre); ?>">
                                                    </form>
                                                    <a class="btn btn-light btn-circle btn-sm"
                                                       onclick="if(confirm('Esta seguro de eliminar este consejero ?')){enviar('el<?php echo e($i); ?>','<?php echo e(url('e_consejero')); ?>','panel_consejeros')}">
                                                            <i class="fas fa-trash-alt text-danger"></i>
                                                    </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </table>
                                </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('crear consejero - cla')): ?>
                            <button id="btnNuevoTra" class="btn btn-sm btn-primary float-right" onclick="$('#divNueTram').show(500); $('#btnNuevoTra').hide(500);"> + Consejero</button><br/>

                            <div class="shadow border" id="divNueTram" style="display: none">
                                <a onclick="$('#divNueTram').hide(500);$('#btnNuevoTra').show(500); " id="ocultar" style="float:right" class="mr-2">
                                    <i class="fas fa-minus-circle text-danger"></i></a>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div id="error_datos" style="display: none" class="alert alert-danger alert-dismissible">
                                            <span id="error_datos_span"></span>
                                        </div>
                                        <div>
                                            <span class="text-primary font-italic font-weight-bold">* Formulario para nuevo consejero</span>
                                            <br/>
                                            <form id="form_nuevo_consejero">
                                                <?php echo csrf_field(); ?>
                                                <table class="table-hover col-md-12 text-dark">
                                                    <tr>
                                                        <th class="text-right font-italic">CI : </th>
                                                        <td class="border-bottom border-dark">

                                                            <input class="form-control form-control-sm border-0" placeholder=""
                                                                   name="ci" onchange="cargarDatosPersonales(this.value)" /></td>

                                                    </tr>
                                                    <tr>
                                                        <th class="text-right font-italic">Apellidos : </th>
                                                        <td class="border-bottom border-dark">
                                                            <div class="input-group">
                                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                                       required name="apellido" id="apellido" /> &nbsp;&nbsp;
                                                                <span class="font-weight-bold text-dark font-italic mt-2"> Nombre : </span>&nbsp;
                                                                <input class="form-control form-control-sm border-0" placeholder=""
                                                                       required name="nombre" id="nombre" />
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-right text-dark">Tipo de participación :</th>
                                                        <td class="">
                                                            <input type="radio" name="titular" value="t" checked> <span class="font-italic text-primary">Titular</span> &nbsp;&nbsp;&nbsp;
                                                            <input type="radio" name="titular" value="f"> <span class="font-italic text-primary">Suplente</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input type="hidden" name="cfre" value="<?php echo e($frente->cod_fre); ?>">
                                                <input type="hidden" name="tipo" value="">
                                            </form>
                                            <br/>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div style="height: 70px"></div>
                                        <a href="#" class="btn btn-sm btn-primary ml-4 mt-2"  onclick="enviar('form_nuevo_consejero','<?php echo e(url('g_consejero')); ?>','panel_consejeros')"
                                           title="Guardar consejero"> Guardar </a>
                                    </div>
                                </div>

                            </div>
                        <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        </div>

    </div>
</div>
<script>
    function cargarDatosPersonales(ci){
        var link="<?php echo e(url('datos_per/')); ?>"+"/"+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp=="No"){
                    $('#apellido').val('');
                    $('#nombre').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido').val(res['per_apellido']);
                    $('#nombre').val(res['per_nombre']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }

</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/claustro/hcu/fe_frente.blade.php ENDPATH**/ ?>