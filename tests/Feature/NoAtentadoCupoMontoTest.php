<?php

namespace Tests\Feature;

use App\Http\Controllers\Noatentado\TramiteNoAtentadoController;
use App\Models\Tramite;
use ReflectionClass;
use Tests\TestCase;

class NoAtentadoCupoMontoTest extends TestCase
{
    private TramiteNoAtentadoController $controller;
    private ReflectionClass $reflection;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller=new TramiteNoAtentadoController();
        $this->reflection=new ReflectionClass($this->controller);
    }

    private function callPrivate(string $method,array $args=[]): mixed
    {
        $target=$this->reflection->getMethod($method);
        $target->setAccessible(true);
        return $target->invokeArgs($this->controller,$args);
    }

    public function test_scale_is_sorted_and_has_expected_boundaries(): void
    {
        $escala=$this->callPrivate('escalaCandidatosMontoNoAtentado');

        $this->assertIsArray($escala);
        $this->assertNotEmpty($escala);

        $primera=$escala[0];
        $this->assertSame(1,(int)$primera['cantidad_min']);
        $this->assertGreaterThanOrEqual(1,(int)$primera['cantidad_max']);
        $this->assertGreaterThan(0.0,(float)$primera['monto_total']);

        $ultima=$escala[sizeof($escala)-1];
        $this->assertGreaterThanOrEqual((int)$primera['cantidad_max'],(int)$ultima['cantidad_max']);
        $this->assertGreaterThanOrEqual((float)$primera['monto_total'],(float)$ultima['monto_total']);
        $this->assertGreaterThanOrEqual(0.01,(float)$primera['monto_total']);

        $montoAnterior=0.0;
        foreach($escala as $fila){
            $this->assertGreaterThanOrEqual(1,(int)$fila['cantidad_min']);
            $this->assertGreaterThanOrEqual((int)$fila['cantidad_min'],(int)$fila['cantidad_max']);
            $this->assertGreaterThanOrEqual($montoAnterior,(float)$fila['monto_total']);
            $montoAnterior=(float)$fila['monto_total'];
        }
    }

    public function test_resolver_cupo_returns_expected_limits_for_boundary_amounts(): void
    {
        $escala=$this->callPrivate('escalaCandidatosMontoNoAtentado');
        $this->assertNotEmpty($escala);

        $resMontoCero=$this->callPrivate('resolverCupoCandidatosPorMontoNoAtentado',[0.0]);
        $this->assertFalse((bool)$resMontoCero['ok']);
        $this->assertSame('MONTO_VALIDADO_NO_DISPONIBLE',(string)$resMontoCero['code']);

        $montoMinimo=(float)($escala[0]['monto_total'] ?? 0);
        $resMontoInsuficiente=$this->callPrivate('resolverCupoCandidatosPorMontoNoAtentado',[max(0.0,$montoMinimo-0.02)]);
        $this->assertFalse((bool)$resMontoInsuficiente['ok']);
        $this->assertSame('MONTO_INSUFICIENTE_PARA_ESCALA',(string)$resMontoInsuficiente['code']);

        $indices=[0,(int)floor((sizeof($escala)-1)/2),sizeof($escala)-1];
        $indices=array_values(array_unique($indices));

        $casos=[];
        foreach($indices as $index){
            $fila=$escala[$index];
            $casos[]=[(float)$fila['monto_total'],(int)$fila['cantidad_max']];
        }

        foreach($casos as [$monto,$esperado]){
            $res=$this->callPrivate('resolverCupoCandidatosPorMontoNoAtentado',[$monto]);
            $this->assertTrue((bool)$res['ok'],'Monto '.$monto.' debería ser válido.');
            $this->assertSame($esperado,(int)$res['max_permitidos'],'Cupo inesperado para monto '.$monto.'.');
        }
    }

    public function test_validar_cantidad_candidatos_por_monto_blocks_and_allows_expected_cases(): void
    {
        $escala=$this->callPrivate('escalaCandidatosMontoNoAtentado');
        $this->assertNotEmpty($escala);

        $montoBase=(float)($escala[0]['monto_total'] ?? 0);
        $sinCandidatos=$this->callPrivate('validarCantidadCandidatosPorMontoNoAtentado',[0,$montoBase]);
        $this->assertFalse((bool)$sinCandidatos['ok']);
        $this->assertSame('SIN_CANDIDATOS',(string)$sinCandidatos['code']);

        $indices=[0,(int)floor((sizeof($escala)-1)/2),sizeof($escala)-1];
        $indices=array_values(array_unique($indices));

        foreach($indices as $index){
            $monto=(float)($escala[$index]['monto_total'] ?? 0);
            $cupo=$this->callPrivate('resolverCupoCandidatosPorMontoNoAtentado',[$monto]);
            $this->assertTrue((bool)$cupo['ok']);

            $max=(int)($cupo['max_permitidos'] ?? 0);
            $this->assertGreaterThan(0,$max);

            $resPermitido=$this->callPrivate('validarCantidadCandidatosPorMontoNoAtentado',[$max,$monto]);
            $this->assertTrue((bool)$resPermitido['ok']);
            $this->assertSame($max,(int)$resPermitido['max_permitidos']);

            $resExceso=$this->callPrivate('validarCantidadCandidatosPorMontoNoAtentado',[$max+1,$monto]);
            $this->assertFalse((bool)$resExceso['ok']);
            $this->assertSame('CANTIDAD_CANDIDATOS_SUPERA_MONTO',(string)$resExceso['code']);
            $this->assertSame($max,(int)$resExceso['max_permitidos']);
        }
    }

    public function test_non_plancha_tramite_allows_only_one_candidate(): void
    {
        $codPlancha=(int)$this->callPrivate('obtenerCodTramitePlanchaNoAtentado');
        $this->assertGreaterThan(0,$codPlancha,'Debe existir un trámite de plancha de estudiantes configurado.');

        $otroTramite=Tramite::query()
            ->where('tre_tipo','=','A')
            ->where('cod_tre','!=',$codPlancha)
            ->orderBy('cod_tre','ASC')
            ->first(['cod_tre']);

        $this->assertNotNull($otroTramite,'Debe existir al menos un trámite No Atentado distinto a plancha.');

        $resUno=$this->callPrivate('validarCantidadCandidatosPorTipoNoAtentado',[(int)$otroTramite->cod_tre,1,24.0]);
        $this->assertTrue((bool)$resUno['ok']);

        $resDos=$this->callPrivate('validarCantidadCandidatosPorTipoNoAtentado',[(int)$otroTramite->cod_tre,2,24.0]);
        $this->assertFalse((bool)$resDos['ok']);
        $this->assertSame('CANTIDAD_CANDIDATOS_SOLO_UNO',(string)$resDos['code']);
    }

    public function test_reintegro_resolves_tramites_by_total_amount_and_manual_selection_when_multiple(): void
    {
        $validacionPrincipal=[
            'ok'=>true,
            'tipo_noatentado_sugerido'=>0,
            'nombre_tipo_noatentado_sugerido'=>'',
            'tipos_noatentado_permitidos'=>[],
            'requiere_seleccion_manual'=>false,
        ];

        $validacionReintegro=[
            'ok'=>true,
            'aplica'=>true,
        ];

        $resMultiple=$this->callPrivate('resolverTiposTramitePagoNoAtentado',[
            $validacionPrincipal,
            $validacionReintegro,
            ['monto_total_validado'=>24.0],
        ]);

        $this->assertTrue((bool)$resMultiple['ok']);
        $this->assertTrue((bool)$resMultiple['requiere_seleccion_manual']);
        $this->assertIsArray($resMultiple['tipos_noatentado_permitidos']);
        $this->assertGreaterThan(1,sizeof($resMultiple['tipos_noatentado_permitidos']));

        $resUnico=$this->callPrivate('resolverTiposTramitePagoNoAtentado',[
            $validacionPrincipal,
            $validacionReintegro,
            ['monto_total_validado'=>5.0],
        ]);

        $this->assertTrue((bool)$resUnico['ok']);
        $this->assertFalse((bool)$resUnico['requiere_seleccion_manual']);
        $this->assertGreaterThan(0,(int)$resUnico['tipo_noatentado_sugerido']);
        $this->assertSame(1,sizeof($resUnico['tipos_noatentado_permitidos']));
    }
}
