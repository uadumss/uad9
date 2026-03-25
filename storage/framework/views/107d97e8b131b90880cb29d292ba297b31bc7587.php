

<?php $__env->startSection('contenido'); ?>
<style>
    /* Eliminar backdrop completamente */
    .modal-backdrop {
        display: none !important;
        opacity: 0 !important;
        z-index: -9999 !important;
    }
    
    /* Asegurar que el modal se vea correctamente */
    .modal.show {
        background-color: rgba(0, 0, 0, 0.5);
    }
    
    .modal-dialog {
        z-index: 9999 !important;
    }
</style>

<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-graduation-cap"></i> Universidades</h1>
        <button type="button" class="btn btn-primary" onclick="abrirModalNueva()">
            <i class="fas fa-plus"></i> Nueva Universidad
        </button>
    </div>

    <?php if(Session::has('exito')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>¡Éxito!</strong> <?php echo session('exito'); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(Session::has('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?php echo session('error'); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <div class="row">
        <!-- UNIVERSIDADES PÚBLICAS -->
        <div class="col-lg-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-university text-primary"></i> Universidades Públicas
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $universidadesPublicas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-dark"><?php echo e($uni->nombre); ?></td>
                                        <td>
                                            <span class="badge badge-primary"><?php echo e($uni->sigla); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" type="button" 
                                                onclick="editarUniversidad(<?php echo e($uni->id); ?>, '<?php echo e($uni->nombre); ?>', '<?php echo e($uni->sigla); ?>', '<?php echo e($uni->tipo); ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo e($uni->id); ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay universidades públicas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- UNIVERSIDADES PRIVADAS -->
        <div class="col-lg-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-building text-success"></i> Universidades Privadas
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $universidadesPrivadas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-dark"><?php echo e($uni->nombre); ?></td>
                                        <td>
                                            <span class="badge badge-success"><?php echo e($uni->sigla); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" type="button" 
                                                onclick="editarUniversidad(<?php echo e($uni->id); ?>, '<?php echo e($uni->nombre); ?>', '<?php echo e($uni->sigla); ?>', '<?php echo e($uni->tipo); ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo e($uni->id); ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay universidades privadas</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- UNIVERSIDADES EXTRANJERAS -->
        <div class="col-lg-4 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-3">
                        <i class="fas fa-globe text-warning"></i> Universidades Extranjeras
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Sigla</th>
                                    <th style="width: 70px;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $universidadesExtranjeras; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $uni): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td class="text-dark"><?php echo e($uni->nombre); ?></td>
                                        <td>
                                            <span class="badge badge-warning"><?php echo e($uni->sigla); ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-info" type="button" 
                                                onclick="editarUniversidad(<?php echo e($uni->id); ?>, '<?php echo e($uni->nombre); ?>', '<?php echo e($uni->sigla); ?>', '<?php echo e($uni->tipo); ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo e($uni->id); ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No hay universidades extranjeras</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL NUEVA UNIVERSIDAD -->
<div class="modal fade" id="modalNuevaUniversidad" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="modalLabel"><i class="fas fa-plus"></i> Nueva Universidad</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(url('crear universidad')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="nombre" name="nombre" required>
                        <?php $__errorArgs = ['nombre'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="sigla">Sigla:</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['sigla'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="sigla" name="sigla" required>
                        <?php $__errorArgs = ['sigla'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="tipo">Tipo:</label>
                        <select class="custom-select <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tipo" name="tipo" required>
                            <option value="">Seleccionar tipo...</option>
                            <option value="Pública">Pública</option>
                            <option value="Privada">Privada</option>
                            <option value="Extranjera">Extranjera</option>
                        </select>
                        <?php $__errorArgs = ['tipo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDITAR UNIVERSIDAD -->
<div class="modal fade" id="modalEditarUniversidad" tabindex="-1" role="dialog" aria-labelledby="modalLabel2" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info">
                <h5 class="modal-title text-white" id="modalLabel2"><i class="fas fa-edit"></i> Editar Universidad</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditar" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreEdit">Nombre:</label>
                        <input type="text" class="form-control" id="nombreEdit" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="siglaEdit">Sigla:</label>
                        <input type="text" class="form-control" id="siglaEdit" name="sigla" required>
                    </div>
                    <div class="form-group">
                        <label for="tipoEdit">Tipo:</label>
                        <select class="custom-select" id="tipoEdit" name="tipo" required>
                            <option value="Pública">Pública</option>
                            <option value="Privada">Privada</option>
                            <option value="Extranjera">Extranjera</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Limpiar completamente al cargar
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
});

function abrirModalNueva() {
    // Limpiar
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
    $('.modal').modal('hide');
    
    // Limpiar campos
    document.getElementById('nombre').value = '';
    document.getElementById('sigla').value = '';
    document.getElementById('tipo').value = '';
    
    // Abrir
    setTimeout(function() {
        $('#modalNuevaUniversidad').modal('show');
    }, 50);
}

function editarUniversidad(id, nombre, sigla, tipo) {
    // Limpiar
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
    $('.modal').modal('hide');
    
    // Rellenar formulario
    document.getElementById('nombreEdit').value = nombre;
    document.getElementById('siglaEdit').value = sigla;
    document.getElementById('tipoEdit').value = tipo;
    document.getElementById('formEditar').action = "<?php echo e(url('actualizar universidad')); ?>/" + id;
    
    // Abrir
    setTimeout(function() {
        $('#modalEditarUniversidad').modal('show');
    }, 50);
}

function confirmarEliminar(id) {
    if(confirm('¿Estás seguro de que deseas eliminar esta universidad?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = "<?php echo e(url('eliminar universidad')); ?>/" + id;
        
        var csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrf);
        
        var method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        form.appendChild(method);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Limpiar cuando se cierre un modal
$(document).on('hidden.bs.modal', '.modal', function() {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('overflow', 'auto');
});

// Prevenir que se agregue backdrop
$(document).on('show.bs.modal', '.modal', function() {
    $('.modal-backdrop').remove();
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('marco.pagina', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\uad9\resources\views/funcionario/l_universidades.blade.php ENDPATH**/ ?>