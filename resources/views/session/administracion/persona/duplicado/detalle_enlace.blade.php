<div>
        @if(sizeof($titulos)>0)
            <div>
                @if($respuesta==1)
                    <i class="fas fa-check-circle text-success"></i>
                @else
                    <i class="fas fa-minus-circle text-danger"></i>
                @endif
                <span class="text-primary font-weight-bold">Títulos</span>

                <table>
                    @foreach($titulos as $t)
                        <tr>
                            <td>{{$t->tit_tipo." - ".$t->tit_nro_titulo."/".$t->tit_gestion}}</td>
                            <td>{{date('d/m/Y H:i:s',strtotime($t->created_at))}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
        @if(sizeof($apostilla)>0)
            <div>
                <span class="text-primary font-weight-bold">Trámites de apostilla</span>
                <table>
                    @foreach($apostilla as $t)
                        <tr>
                            <td>{{$t->apos_numero."/".$t->apos_gestion}}</td>
                            <td>{{date('d/m/Y H:i:s',strtotime($t->created_at))}}</td>

                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
        @if(sizeof($tramitas)>0)
            <div>
                <span class="text-primary font-weight-bold">Trámites de legalización</span>
                <table>
                    @foreach($tramitas as $t)
                        <tr>
                            <td>{{$t->tra_tipo_tramite.'-'.$t->tra_numero."/".$t->tra_gestion}}</td>
                            <td>{{date('d/m/Y H:i:s',strtotime($t->created_at))}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        @endif
        @if(sizeof($sancionados)>0)
                <div>
                    <span class="text-primary font-weight-bold">Sancionados</span>
                    <table>
                        @foreach($sancionados as $t)
                            <tr>
                                <td>{{$t->san_sentencia}}</td>
                                <td>{{date('d/m/Y H:i:s',strtotime($t->created_at))}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
        @endif
        @if(sizeof($noatentado)>0)
                <div>
                    <span class="text-primary font-weight-bold">Trámites de noatentado</span>
                    <table>
                        @foreach($noatentado as $t)
                            <tr>
                                <td>{{$t->noa_cargo}}</td>
                                <td>{{date('d/m/Y H:i:s',strtotime($t->created_at))}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
        @endif
</div>
