

            <div class="card-body">

                        <div class="bg-primary centrar_bloque p-1 col-md-3 rounded shadow">
                            <h5 class="text-white text-center">Corrección de datos personales</h5>
                        </div>
                        <hr class="sidebar-divider">
                        <div class="row">
                            <div class="col-md-4 m-5">
                                <div>
                                    <table>
                                        <tr>
                                            <td class="p-2"><span class="text-primary font-italic font-weight-bold pb-2">* Ingrese el numero de C.I.</span></td>
                                        </tr>
                                        <tr>
                                            <td class="p-2">
                                                <form id="form_corregir">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="input-group">
                                                        CI&nbsp;:&nbsp;<input type="text" class="form-control form-control-sm" required name="ci" id="ci">
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="p-2">
                                                <button onclick="enviar('form_corregir','<?php echo e(url("fe_persona")); ?>','panel_correccion')" type="button"  class="focus btn btn-primary btn-sm text-white"><i class="fas fa-search"></i> Buscar</button>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <span class="text-danger font-italic font-weight-bold pb-2">* Resultado de la búsqueda</span>
                                <hr class="sidebar-divider"/>
                                <div id="panel_correccion">

                                </div>
                            </div>
                        </div>
            </div>

<script>
    const nombre = document.querySelector("#ci");
    // Escuchamos el keydown y prevenimos el evento
    nombre.addEventListener("keydown", (evento) => {
        if (evento.key == "Enter") {
            // Prevenir
            evento.preventDefault();
            enviar('form_corregir','<?php echo e(url("fe_persona")); ?>','panel_correccion');
            return false;
        }
    });
</script>
<?php /**PATH C:\xampp\htdocs\uad9\resources\views/session/administracion/persona/correccion/l_persona.blade.php ENDPATH**/ ?>