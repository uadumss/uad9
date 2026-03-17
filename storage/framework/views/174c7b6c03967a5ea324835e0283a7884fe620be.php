
<div class="modal-content border-bottom-primary">
    <div class="modal-header bg-primary">
        <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel"><i class="fas fa-user"></i> Apoderado </h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span class="text-white" aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">

        <?php if(Session::has('exito')): ?>
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <span class="font-weight-bold"><?php echo session('exito'); ?></span>
            </div>
        <?php endif; ?>
        <div class="bg-primary centrar_bloque p-1 col-md-7 rounded shadow-sm">
            <h6 class="text-white font-weight-bold text-center">Datos del apoderado</h6>
        </div>

        <hr class="sidebar-divider"/>
        <div class="row">
             <div class="col-md-4">
                 <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del trámite</span>
                 <br/>
                 <br/>

                 <table class="table table-sm" style="font-size: 0.9em">
                     <?php
                         $nombre='';    $apellido='';  $ci="";
                         if($persona){   $apellido=$persona->per_apellido;     $nombre=$persona->per_nombre;  }
                     ?>
                     <tr>
                         <th class="text-right font-italic text-dark font-italic">Nro Trámite : </th>
                         <td class="border-bottom border-dark">
                             <?php echo e($tramita->tra_numero); ?>

                         </td>
                     </tr>
			<tr>
                         <th class="text-right font-italic text-dark font-italic">CI.: </th>
                         <td class="border-bottom border-dark">
                             <?php echo e($persona->per_ci); ?>

                         </td>
                     </tr>

                     <tr>
                         <th class="text-right font-italic text-dark font-italic">Fecha de solicitud : </th>
                         <td class="border-bottom border-dark">
                             <?php echo e(date('d/m/Y', strtotime($tramita->tra_fecha_solicitud))); ?>

                         </td>
                     </tr>
                     <tr>
                         <th class="text-right font-italic text-dark font-italic">Titular : </th>
                         <td class="border-bottom border-dark">
                             <?php echo e($apellido." ".$nombre); ?>

                         </td>

                     </tr>
                 </table>


                 <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Datos del apoderado</span>
                 <br/>
                 <br/>
                     <div class="" id="apoderadoEntrega">
                         <?php if($apoderado): ?>
                         <table class=" table table-sm">
                             <tr>
                                 <th class="text-right font-italic text-dark">CI : </th>
                                 <td class="border-bottom border-dark">
                                     <?php if($apoderado): ?>
                                         <?php echo e($apoderado['apo_ci']); ?>

                                     <?php else: ?>
                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                     <?php endif; ?>
                                 </td>
                             </tr>
                             <tr>
                                 <th class="text-right font-italic text-dark font-italic">Nombre apoderado : </th>
                                 <td class="border-bottom border-dark">
                                     <?php if($apoderado): ?>
                                         <?php echo e($apoderado['apo_apellido']." ".$apoderado['apo_nombre']); ?>

                                     <?php else: ?>
                                         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                     <?php endif; ?>
                                 </td>
                             </tr>
                             <tr>
                                 <th class="text-right font-italic text-dark">Tipo de apoderado : </th>
                                 <td class="border-bottom border-dark">
                                     <?php if($tramita->tra_tipo_apoderado=='d'): ?>
                                         Declaración jurada
                                     <?php else: ?>
                                         <?php if($tramita->tra_tipo_apoderado=='p'): ?>
                                             Poder notariado
                                         <?php else: ?>
                                             &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                         <?php endif; ?>
                                     <?php endif; ?>
                                 </td>
                             </tr>
                         </table>
                         <?php endif; ?>
                         <button id="otros" class="btn btn-sm btn-primary float-right" onclick="$('#editarApoderadoEntrega').show(500); $('#apoderadoEntrega').hide(500);"> Editar datos</button>
                     </div>
                     <div id="editarApoderadoEntrega" class="border rounded shadow" style="display: none;">
                         <div class="p-3">
                             <a onclick="$('#editarApoderadoEntrega').hide(500);$('#apoderadoEntrega').show(500); " id="ocultar" style="float:right">
                                 <i class="fas fa-minus-circle text-danger"></i></a>
                             <span class="text-primary font-weight-bold font-italic" style="font-size: 0.85em">* Editar datos del apoderado</span>
                             <br><br>

                             <form id="form_apoderado_ent">
                                <?php echo csrf_field(); ?>
                                 <?php
                                     $nombre='';    $apellido='';  $ci="";
                                     if($apoderado){   $ci=$apoderado->apo_ci;       $apellido=$apoderado->apo_apellido;     $nombre=$apoderado->apo_nombre;  }
                                 ?>

                                 <table class="table-hover col-md-12">
                                     <tr>
                                         <th class="text-right font-italic">CI : </th>
                                         <td class="border-bottom border-dark">
                                             <input class="form-control form-control-sm border-0" placeholder=""
                                                    id="ci" name="ci" value="<?php echo e($ci); ?>" onchange="cargarDatosApoderado(this.value)"/></td>
                                     </tr>
                                     <tr>
                                         <th class="text-right font-italic">Apellidos : </th>
                                         <td class="border-bottom border-dark">
                                             <input class="form-control form-control-sm border-0" placeholder=""
                                                    required name="apellido" id="apellido_apoderado" value="<?php echo e($apellido); ?>" /></td>
                                     </tr>
                                     <tr>
                                         <th class="text-right font-italic">Nombres : </th>
                                         <td class="border-bottom border-dark">
                                             <input class="form-control form-control-sm border-0" placeholder=""
                                                    required name="nombre" id="nombre_apoderado" value="<?php echo e($nombre); ?>" /></td>
                                     </tr>
                                     <tr>
                                         <th class="text-right font-italic" valign="top">Tipo de apoderado : </th>
                                         <td class="border-bottom border-dark">
                                             <?php if($tramita->tra_tipo_apoderado=='d'): ?>
                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d" checked> Declaración jurada<br/>
                                                 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                             <?php else: ?>
                                                 <?php if($tramita->tra_tipo_apoderado=='p'): ?>
                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p" checked> Poder notariado
                                                 <?php else: ?>
                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="d"> Declaración jurada<br/>
                                                     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="tipo" value="p"> Poder notariado
                                         <?php endif; ?>
                                         <?php endif; ?>

                                     </tr>
                                 </table>
                                 <br/>
                                 <input type="hidden" name="ctra" value="<?php echo e($tramita->cod_tra); ?>">
                                 <input type="hidden" name="pan" value="ent">
                             </form>
                             <a class="btn btn-primary btn-sm text-white float-right" onclick="enviar('form_apoderado_ent','<?php echo e(url("guardar apoderado")); ?>','panel_traleg');" >Guardar</a><br/>
                             <br/>
                         </div>
                     </div>
             </div>


             <div class="col-md-8">
                 <span class="text-primary font-italic font-weight-bold" style="font-size: 0.8em">* Documentos del trámite</span>

                 <?php if($tramita->cod_apo!=''): ?>
                     <a href="#" class="btn btn-outline-danger btn-sm font-weight-bold shadow float-right" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("datos legalizado/2/".$tramita->cod_tra)); ?>','panel_docleg')"
                        title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar todo</a>
                 <?php else: ?>
                     <form id="form_g_entrega">
                         <?php echo csrf_field(); ?>
                         <input type="hidden" name="ctra" value="<?php echo e($tramita->cod_tra); ?>">
                         <input type="hidden" name="tipo" value="t">
                         <input type="hidden" name="todo" value="t">
                     </form>
                     <a href="#" class="btn btn-outline-danger btn-sm font-weight-bold shadow float-right" onclick="guardarDatos('<?php echo e(url("g_entrega")); ?>','panel_traleg','form_g_entrega')"
                        title="Entregar todos los documentos"><i class="fas fa-angle-right"></i> Entregar todo</a>
                 <?php endif; ?>

                 <div>
                     <table class="col-md-12 table table-sm table-hover border">
                         <tr class="bg-gradient-secondary text-white p-2">
                             <th>Nº</th>
                             <th>Nombre</th>
                             <?php if($tramita->tra_tipo_tramite=='B'): ?>
                                 <th>Documentos</th>
                             <?php endif; ?>
                             <th>Nº Título</th>
                             <th>Opciones</th>
                             <th>Entregar</th>
                         </tr>
                         <?php $i=1;?>
                         <?php $__currentLoopData = $documentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $d): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                     <tr style="font-size: 0.80em" class="alert-light">
                                         <td><?php echo e($i); ?></td>
                                         <td class="text-left"><?php echo e($d->tre_nombre); ?><br/>
                                             <span style="font-size: 0.85em">
                                                <?php if($d->dtra_interno=='t'): ?> <span class="font-weight-bold text-dark font-italic">Trámite : </span><span class="text-danger font-weight-bold">Interno</span> | <?php endif; ?>
                                                 <span class="font-weight-bold text-dark font-italic">Valorado: </span> <span> <?php echo e($d->dtra_valorado); ?></span> |
                                                 <?php if($d->dtra_entregado=='t' || $d->dtra_entregado=='a' ): ?><span class="font-weight-bold text-dark font-italic">Fecha entrega: </span> <span class="text-primary font-weight-bold"> <?php echo e(date('d/m/Y H:i:s', strtotime($d->dtra_fecha_recojo))); ?></span> |<?php endif; ?>
                                             </span>
                                         </td>
                                         <?php if($tramita->tra_tipo_tramite=='B'): ?>
                                             <td><?php echo e($d->dcon_doc); ?></td>
                                         <?php endif; ?>
                                         <td class="text-left"><?php echo e($d->dtra_numero."/".substr($d->dtra_gestion,-2)); ?></td>
                                         <td class="text-right">
                                             <?php if($d->dtra_generado=='t'): ?>
                                                 <?php if($d->dtra_estado_doc==0): ?>
                                                     <a href="#" class="btn btn-light btn-circle btn-sm text-primary" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('ver documento pdf legalizado/<?php echo e($d->cod_dtra); ?>','panel_docleg')"
                                                        title="Ver documento PDF"><i class="fas fa-file-code"></i> </a>
                                                 <?php endif; ?>
                                                     <?php if($tramita->tra_tipo_tramite=='C'): ?>
                                                        <a class="btn btn-light btn-sm btn-circle" href="<?php echo e(url('generar pdf/'.$d->cod_dtra)); ?>" target="pdf<?php echo e(rand(1,1000)); ?>"><i class="text-dark fas fa-file-pdf"></i></a>
                                                     <?php endif; ?>

                                             <?php endif; ?>
                                         </td>
                                         <td class="text-right">
                                             <?php if($d->dtra_entregado!='a' && $d->dtra_entregado!='t'): ?>
                                                 <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('entregar legalizacion docleg - srv')): ?>
                                                    <?php if($tramita->cod_apo!=''): ?>
                                                        <a href="#" class="btn btn-primary btn-sm" data-target="#docleg" data-toggle="modal" onclick="cargarDatos('<?php echo e(url("datos legalizado/1/".$d->cod_dtra)); ?>','panel_docleg')"
                                                            title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar +</a>
                                                     <?php else: ?>
                                                        <form id="form_g_entrega<?php echo e($i); ?>">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="hidden" name="cdtra" value="<?php echo e($d->cod_dtra); ?>">
                                                            <input type="hidden" name="ctra" value="<?php echo e($d->cod_tra); ?>">
                                                            <input type="hidden" name="tipo" value="t">
                                                        </form>
                                                         <a href="#" class="btn btn-primary btn-sm" onclick="guardarDatos('<?php echo e(url("g_entrega")); ?>','panel_traleg','form_g_entrega<?php echo e($i); ?>')"
                                                            title="Ver documento PDF"><i class="fas fa-angle-right"></i> Entregar</a>
                                                     <?php endif; ?>

                                                 <?php endif; ?>
                                             <?php else: ?>
                                                 <span class="border-danger rounded text-success"><i class="fas fa-check"></i></span>
                                                 <?php if($d->dtra_entregado=='a'): ?> <span class="font-weight-bold text-success font-italic">Apoderado </span> <?php endif; ?>
                                             <?php endif; ?>
                                         </td>
                                     </tr>
                                     <?php $i++;?>
                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </table>
                 </div>
             </div>
        </div>
        <div class="modal-footer">
             <button class="btn btn-secondary btn-sm" type="button" data-dismiss="modal">Cerrar</button>
        </div>
    </div>
</div>
<script>
    function cargarDatosApoderado(ci){
        var link="<?php echo e(url('datos_apo/')); ?>"+"/"+ci;
        $.ajax({
            url: link,
            type: 'GET',
            success: function (resp) {
                if(resp=="No"){
                    $('#apellido_apoderado').val('');
                    $('#nombre_apoderado').val('');
                }else{
                    var res=JSON.parse(resp);
                    $('#apellido_apoderado').val(res['apo_apellido']);
                    $('#nombre_apoderado').val(res['apo_nombre']);
                }
            },
            error: function () {
                $('#'+panel).html("<span class='text-danger'>Ocurrio un error, probablemente no tenga permisos para esta acción</span>");
            }
        });
    }
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/servicios/tra_legalizacion/f_entrega.blade.php ENDPATH**/ ?>