<div class="modal-dialog modal-xl" role="document">
    <form action="{{url('g_diario')}}" method="post">
        @csrf
                    <div class="modal-content border-bottom-primary">
                            <div class="modal-header bg-primary">
                                <h5 class="modal-title text-white font-weight-bolder" id="exampleModalLabel">Nuevo reporte diario</h5>
                                <button class="close text-white" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                                <div class="modal-body">
                                    <div class="bg-primary centrar_bloque col-md-6 p-1 rounded shadow">
                                        <h5 class="text-white text-center">Formulario de reporte diario</h5>
                                    </div>
                                    <br/>
                                    <div class="rounded alert-warning" style="font-size: 0.85em">
                                        @if($cod_dia==0)
                                          <div class="p-2">
                                              <span class="text-dark font-italic font-weight-bold"> Tarea : </span><span>{{$tarea['tar_nombre']}}</span> &nbsp;&nbsp;&nbsp;
                                              <span class="text-dark font-italic font-weight-bold"> Fecha inicio : </span><span>@if($tarea['tar_fi']!='')
                                                      {{$tarea['tar_fi']}}
                                                  @endif
                                              </span> &nbsp;&nbsp;&nbsp;
                                              <span class="text-dark font-italic font-weight-bold"> Fecha conclusión :</span><span>@if($tarea['tar_ff']!='')
                                                      {{$tarea['tar_ff']}}
                                                  @endif
                                              </span> &nbsp;&nbsp;&nbsp;
                                              @if($tarea['tar_con']=='t')
                                                  <span class="text-danger font-weight-bolder font-italic">Esta tarea ya esta concluida</span>
                                              @endif
                                          </div>
                                    </div>
                                    <hr/>
                                    <div class="rounded pl-3 pr-4"><br/>


                                        <table class="table-sm col-md-12">
                                            <tr>
                                                <th class="text-dark font-italic text-right">Fecha :</th>
                                                <td class="border-bottom border-dark"><input type="date" name="fecha" required class="form-control form-control-sm border-0 form-control-user"/>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-dark font-italic text-right">Reporte :</th>
                                                <td class="border-bottom border-dark"><textarea name="desc" class="form-control border-0" rows="10"></textarea>
                                                </td>
                                            </tr>
                                        </table>
                                        <input type="hidden" name="ct" value="{{$tarea['cod_tar']}}">
                                        @else

                                        @endif
                                    </div>

                                </div>
                            <div class="modal-footer bg-gray-200">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                                <input class="btn btn-primary" type="submit" value="Aceptar"/>
                            </div>
                    </div>
    </form>
</div>
<script>
    tinymce.init({
        selector: '#desc',
    });
</script>
