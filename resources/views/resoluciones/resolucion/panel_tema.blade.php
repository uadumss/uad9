<div>
    @if($cod_res==0)

            <input type="hidden" name="temas" value="{{$detalle->cod_det}}">
            <span>{{$plan->plan_numero."/".$codigo->carch_numero." - ".$detalle->det_nombre}}</span>

    @else

        <span class="font-weight-bold">
           <?php $codificacion="";?>
            @foreach($archivado as $a)
                <a class="btn btn-light btn-circle btn-sm text-danger"onclick="cargarPlan('eliminar plan resolucion/{{$a->cod_arc}}','archivados')"><i class="fas fa-trash-alt"></i></a>
                {{$a->plan_numero.'/'.$a->carch_numero.'-'.$a->det_nombre}}<br/>
                    <?php //$codificacion.=$a->plan_numero.'/'.$a->carch_numero."<br/>"?>
            @endforeach
                                                    </span>
    @endif
</div>
