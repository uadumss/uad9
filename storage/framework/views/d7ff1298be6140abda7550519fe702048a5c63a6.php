    <?php if(Session::has('exito')): ?>
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="font-weight-bold"><?php echo session('exito'); ?></span>
        </div>
    <?php endif; ?>
    <?php if(Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>
            <span class="text-dark"><?php echo session('error'); ?></span>
        </div>
    <?php endif; ?>
    <?php if(count($errors)>0): ?>
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

            <ul>
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="font-weight-bold te"><?php echo e($e); ?> - </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

        <?php if($num<9 && $num>=0 && is_numeric($num)): ?>
            <div class="d-sm-flex align-items-center justify-content-between">
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/0')); ?>','panel')" class="d-sm-inline-block btn  <?php echo $vista[0];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-book"></i> </h6>
                    <strong>D y T</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/1')); ?>','panel')"  class="d-sm-inline-block btn <?php echo $vista[1];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-list-ul"></i> </h6>
                    <strong>RR - RCU</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/6')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[6];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-list-alt"></i> </h6>
                    <strong>RCF - RCC</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/2')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[2];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-university"></i> </h6>
                    <strong>SERVICIOS</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/3')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[3];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-file-import"></i> </h6>
                    <strong>APOSTILLA</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/4')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[4];?> btn-secondary shadow-lg"  >
                    <h5><i class="fas fa-user-friends"></i> </h5>
                    <strong>D y A</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/7')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[7];?> btn-secondary shadow-lg"  >
                    <h5><i class="fas fa-user-lock"></i> </h5>
                    <strong>NoA</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/8')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[8];?> btn-secondary shadow-lg"  >
                    <h5><i class="fas fa-user-check"></i> </h5>
                    <strong>CLAUSTROS</strong>
                </a>
                <a href="#" onclick="cargarDatos('<?php echo e(url('listar permisos/'.$usuario['id'].'/5')); ?>','panel')" class="d-sm-inline-block btn <?php echo $vista[5];?> btn-secondary shadow-lg"  >
                    <h6><i class="fas fa-fw fa-cog"></i> </h6>
                    <strong>ADM</strong>
                </a>

            </div>
            <?php if($num<9 && $num>=0 && is_numeric($num)): ?>
                <a href="#" class="btn btn-sm btn-outline-info text-dark" data-target="#nuevoObjeto" data-toggle="modal">
                    <i class="fas fa-box"></i> Nuevo objeto</a>&nbsp;&nbsp;
                <a href="#" class="btn btn-sm btn-outline-info text-dark" data-target="#nuevoPermiso" data-toggle="modal">
                    <i class="fas fa-key"></i> Nuevo permiso</a>&nbsp;&nbsp;
            <?php endif; ?>

            <span style="font-size: 1.5em" class="text-gray-500">&nbsp;&nbsp;|&nbsp;&nbsp;</span>
            <span class="text-right"><span class="font-italic font-weight-bold text-dark">Usuario : </span><a href="#" class="text-primary"> <?php echo e($usuario['name']); ?> </a>
                </span>
            <hr class="sidebar-divider"/>
            <div>
                <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                    <h5 class="text-white text-center">Lista de permisos</h5>
                </div>
                <div class="row">
                    <?php $__currentLoopData = $objetos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-3 m-2 shadow rounded">
                            <span class="text-danger font-weight-bold" colspan="2"><?php echo e($o->obj_nombre); ?></span><br/><br/>
                            <table>
                                <?php $__currentLoopData = $totalPermisos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($o->cod_obj==$t->objeto): ?>
                                        <tr>
                                            <?php $existe=false?>
                                            <?php $__currentLoopData = $permisosUsuario; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($pu->permission_id==$t->id){
                                                    $existe=true;
                                                }
                                                ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($existe): ?>
                                                    <td valign="top"><input type="checkbox" name="permiso" value="<?php echo e($t->name); ?>" checked onchange="procesar($(this))" /></td>
                                            <?php else: ?>
                                                    <td valign="top"><input type="checkbox" name="permiso" value="<?php echo e($t->name); ?>" onchange="procesar($(this))" /></td>
                                            <?php endif; ?>
                                            <td class="font-italic" valign="top"><?php echo e($t->leyenda); ?></span></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </table>
                            <br/>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <br/>

                </div>
            </div>
        <?php else: ?>
            <div class="alert-danger">
                 Error de ruta
            </div>
        <?php endif; ?>

    <!--===========================NUEVO PERMISO===================-->
    <div class="modal fade" id="nuevoPermiso" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-key"></i> Permisos</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                            <h5 class="text-white text-center">Formulario para nuevo permiso</h5>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div>
                            <form action="<?php echo e(url('guardar permiso')); ?>" method="POST" id="form_permiso">
                                <?php echo csrf_field(); ?>

                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic">Permiso :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" required name="permiso" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Leyenda :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" required name="leyenda" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Objeto : </th>
                                        <td class="border-bottom border-dark">
                                            <select class="custom-select custom-select-sm" name="objeto" required>
                                                <option disabled selected hidden></option>
                                                <?php $__currentLoopData = $objetos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $o): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($o['cod_obj']); ?>"><?php echo e($o['obj_nombre']); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Subsistema : </th>
                                        <td class="border-bottom border-dark">
                                            <span class="text-primary font-weight-bold"><?php echo e($subsistema); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <input type="hidden" name="id" value="<?php echo e($usuario['id']); ?>">
                                <input type="hidden" name="num" value="<?php echo e($num); ?>">
                                <input type="hidden" name="subsistema" value="<?php echo e($subsistema); ?>">
                            </form>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a href="#" class="btn btn-primary" onclick="enviar('form_permiso','<?php echo e(url("guardar permiso")); ?>','panel');$('#nuevoPermiso').modal('hide')"> Guardar</a>
                    </div>
                </div>

        </div>
    </div>
    <!--===========================END ==============================-->
    <!--===========================NUEVO OBJETO===================-->
    <div class="modal fade" id="nuevoObjeto" role="dialog" aria-hidden="false">
        <div class="modal-dialog" role="document">
                <div class="modal-content border-bottom-primary">
                    <div class="modal-header bg-primary">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-box"></i> Objetos</h5>
                        <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bg-primary centrar_bloque p-1 col-md-10 rounded shadow">
                            <h5 class="text-white text-center">Formulario para nuevo objeto</h5>
                        </div>
                        <hr class="sidebar-divider"/>
                        <div>
                            <form id="form_objeto">
                                <table class="table-hover col-md-12">
                                    <tr>
                                        <th class="text-right font-italic">Objeto :</th>
                                        <td class="border-bottom border-dark">
                                            <input type="text" class="form-control form-control-sm border-0 col-md-12" required name="objeto" />
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-right font-italic">Subsistema : </th>
                                        <td class="border-bottom border-dark">
                                            <span class="text-primary font-weight-bold"><?php echo e($subsistema); ?></span>
                                        </td>
                                    </tr>
                                </table>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="id" value="<?php echo e($usuario['id']); ?>">
                                <input type="hidden" name="num" value="<?php echo e($num); ?>">
                                <input type="hidden" name="subsistema" value="<?php echo e($subsistema); ?>">
                            </form>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                        <a href="#" class="btn btn-primary" data-toggle="modal" onclick="$('#nuevoObjeto').modal('hide');enviar('form_objeto','<?php echo e(url("guardar objeto")); ?>','panel');"> Guardar</a>
                    </div>
                </div>

        </div>
    </div>
    <!--===========================END ==============================-->
    <!--===========================Procesar permiso===================-->
    <div class="modal" id="procesarPermiso" style="z-index: 1500;" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog " role="document">
                <div class="modal-content border-bottom-danger">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-key"></i> Asignar permiso</h5>
                        <button class="close text-dark" type="button" data-dismiss="modal" aria-label="Close">
                            <span class="text-white" aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body centrar_bloque" id="panel_permisos">
                        <div class="spinner-border text-warning text-center col-md-12" role="status" >
                            <span class="sr-only text-center">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
        </div>
    </div>
    <!--===========================END ==============================-->
    <script>
        function procesar(check){
            $('#procesarPermiso').modal('show');
            var data="check="+check.prop('checked')+"&val="+check.val()+"&id=<?php echo e($usuario->id); ?>";
            var link = "<?php echo e(url('asignar permiso/')); ?>";
            var token = "<?php echo e(csrf_token()); ?>";

            $.ajaxSetup({ headers: {'X-CSRF-TOKEN': token}});
            $.ajax({
                url: link,
                type: 'POST',
                data:data,
                //data:$('#form_editar').serialize(),
                success: function (resp) {
                   $('#procesarPermiso').modal('hide');
                },
                error: function (data) {
                    $('#panel_permisos').html('<span class="text-danger font-weight-bold"> Ocurrió un error, permiso no asignado</span>');
                }
            });
        }
    </script>


<?php /**PATH C:\xampp\htdocs\uad9\resources\views/permisos/l_permisos.blade.php ENDPATH**/ ?>