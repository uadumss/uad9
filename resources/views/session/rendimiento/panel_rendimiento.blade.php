




                    <div id="panel_graf">
                        <a data-toggle="collapse" href="#rendimiento" role="button" aria-expanded="false" aria-controls="collapseExample">
                            <i class="fas fa-chart-line"> </i> Establecer rango</a>
                        <div class="collapse col-md-6" id="rendimiento" >
                            <div class="card card-body">
                                <form id="form_ren">
                                    <div class="">
                                        @csrf
                                        <span class="pt-2 text-dark font-weight-bolder">Año :</span>&nbsp; <select class="custom-select" placeholder="seleccione un año" name="a" id="a">
                                            <option value="<?php echo date('Y')?>"><?php echo date('Y')?></option>
                                            <?php $año=date('Y')?>
                                            @for($i=2021;$i<=$año;$i++)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>&nbsp;&nbsp;&nbsp;
                                        <br/>
                                        <span class="pt-2 text-dark font-weight-bolder">Mes Inicial : </span>&nbsp;<select class="custom-select" name="mi" id="mi">
                                            <option value="1">Enero</option><option value="2">Febrero</option>
                                            <option value="3">Marzo</option><option value="4">Abril</option>
                                            <option value="5">Mayo</option><option value="6">Junio</option>
                                            <option value="7">Julio</option><option value="8">Agosto</option>
                                            <option value="9">Septiembre</option><option value="10">Octubre</option>
                                            <option value="11">Noviembre</option><option value="12">Diciembre</option>
                                        </select>&nbsp;&nbsp;&nbsp;
                                        <br/>
                                        <span class="pt-2 text-dark font-weight-bolder">Mes Final :</span>&nbsp; <select class="custom-select" name="mf" id="mf">
                                            <option value="1">Enero</option><option value="2">Febrero</option>
                                            <option value="3">Marzo</option><option value="4">Abril</option>
                                            <option value="5">Mayo</option><option value="6">Junio</option>
                                            <option value="7">Julio</option><option value="8">Agosto</option>
                                            <option value="9">Septiembre</option><option value="10">Octubre</option>
                                            <option value="11">Noviembre</option><option value="12">Diciembre</option>
                                        </select>&nbsp;&nbsp;&nbsp;
                                        <br/>
                                        <input type="radio" name="tipo" checked value="sis"> Por registro en el sistema <br/>
                                        <input type="radio" name="tipo" value="cal"> Por calificación del responsable
                                            <br/>
                                        <br/>
                                        <input type="hidden" name="id_per" value="{{$usu['id']}}"/>
                                            <a type="button" class="btn btn-sm btn-primary text-white" onclick="rend()">Ver rendimiento</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

    <div class="modal fade" id="personal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" >
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content border-bottom-primary">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title font-weight-bolder text-white" id="exampleModalLabel"><i class="fas fa-edit"></i> RENDIMIENTO</h5>
                    <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="panelRen">
                        <div class="d-flex justify-content-center">
                            <div class="spinner-border text-danger" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" type="button" data-dismiss="modal" onclick="enviarForm()" >Enviar</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        function rend(){
            //alert("entro");
            var url = "{{url('rendimiento_per')}}"; // El script a dónde se realizará la petición.
            var mi=parseInt($('#mi').val());
            var mf=parseInt($('#mf').val());
            if(mi>mf) {
                $('#errorMesFinal').modal('show');
            }else{
                $('#personal').modal('show');

                $.ajax({
                    type: "POST",
                    url: url,
                    data: $("#form_ren").serialize(), // Adjuntar los campos del formulario enviado.
                    success: function(data)
                    {
                        $('#panelRen').html(data);
                    }
                });
            }
        }
    </script>
