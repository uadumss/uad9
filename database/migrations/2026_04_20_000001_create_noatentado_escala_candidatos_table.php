<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if(!Schema::hasTable('noatentado.escala_candidatos')){
            Schema::create('noatentado.escala_candidatos', function (Blueprint $table) {
                $table->bigIncrements('cod_esc_noa');
                $table->unsignedInteger('cantidad_min');
                $table->unsignedInteger('cantidad_max');
                $table->decimal('costo',10,2)->default(0);
                $table->decimal('aporte_umss',10,2)->default(0);
                $table->decimal('monto_total',10,2);
                $table->unsignedInteger('orden')->default(0);
                $table->boolean('habilitado')->default(true);
                $table->timestamps();

                $table->unique(['cantidad_min','cantidad_max'],'uniq_noa_escala_rango');
                $table->index(['habilitado','orden'],'idx_noa_escala_hab_orden');
            });
        }

        if(!Schema::hasTable('noatentado.escala_candidatos')){
            return;
        }

        $existeData=(int)DB::table('noatentado.escala_candidatos')->count()>0;
        if($existeData){
            return;
        }

        $filas=[];
        $ahora=now();
        foreach($this->escalaBase() as $index=>$fila){
            $filas[]=[
                'cantidad_min'=>(int)$fila['cantidad_min'],
                'cantidad_max'=>(int)$fila['cantidad_max'],
                'costo'=>(float)$fila['costo'],
                'aporte_umss'=>(float)$fila['aporte_umss'],
                'monto_total'=>(float)$fila['monto_total'],
                'orden'=>$index+1,
                'habilitado'=>true,
                'created_at'=>$ahora,
                'updated_at'=>$ahora,
            ];
        }

        if(sizeof($filas)>0){
            DB::table('noatentado.escala_candidatos')->insert($filas);
        }
    }

    public function down(): void
    {
        if(Schema::hasTable('noatentado.escala_candidatos')){
            Schema::drop('noatentado.escala_candidatos');
        }
    }

    private function escalaBase(): array
    {
        return [
            ['cantidad_min'=>1,'cantidad_max'=>1,'costo'=>5,'aporte_umss'=>4,'monto_total'=>9],
            ['cantidad_min'=>2,'cantidad_max'=>3,'costo'=>10,'aporte_umss'=>4,'monto_total'=>14],
            ['cantidad_min'=>4,'cantidad_max'=>10,'costo'=>20,'aporte_umss'=>4,'monto_total'=>24],
            ['cantidad_min'=>11,'cantidad_max'=>30,'costo'=>30,'aporte_umss'=>4,'monto_total'=>34],
            ['cantidad_min'=>31,'cantidad_max'=>35,'costo'=>35,'aporte_umss'=>4,'monto_total'=>39],
            ['cantidad_min'=>36,'cantidad_max'=>40,'costo'=>40,'aporte_umss'=>4,'monto_total'=>44],
            ['cantidad_min'=>41,'cantidad_max'=>45,'costo'=>45,'aporte_umss'=>4,'monto_total'=>49],
            ['cantidad_min'=>46,'cantidad_max'=>50,'costo'=>50,'aporte_umss'=>4,'monto_total'=>54],
            ['cantidad_min'=>51,'cantidad_max'=>55,'costo'=>55,'aporte_umss'=>4,'monto_total'=>59],
            ['cantidad_min'=>56,'cantidad_max'=>60,'costo'=>60,'aporte_umss'=>4,'monto_total'=>64],
            ['cantidad_min'=>61,'cantidad_max'=>65,'costo'=>65,'aporte_umss'=>4,'monto_total'=>69],
            ['cantidad_min'=>66,'cantidad_max'=>70,'costo'=>70,'aporte_umss'=>4,'monto_total'=>74],
            ['cantidad_min'=>71,'cantidad_max'=>75,'costo'=>75,'aporte_umss'=>4,'monto_total'=>79],
            ['cantidad_min'=>76,'cantidad_max'=>80,'costo'=>80,'aporte_umss'=>4,'monto_total'=>84],
            ['cantidad_min'=>81,'cantidad_max'=>85,'costo'=>85,'aporte_umss'=>4,'monto_total'=>89],
            ['cantidad_min'=>86,'cantidad_max'=>90,'costo'=>90,'aporte_umss'=>4,'monto_total'=>94],
            ['cantidad_min'=>91,'cantidad_max'=>95,'costo'=>95,'aporte_umss'=>4,'monto_total'=>99],
            ['cantidad_min'=>96,'cantidad_max'=>100,'costo'=>100,'aporte_umss'=>4,'monto_total'=>104],
            ['cantidad_min'=>101,'cantidad_max'=>105,'costo'=>105,'aporte_umss'=>4,'monto_total'=>109],
            ['cantidad_min'=>106,'cantidad_max'=>110,'costo'=>110,'aporte_umss'=>4,'monto_total'=>114],
            ['cantidad_min'=>111,'cantidad_max'=>115,'costo'=>115,'aporte_umss'=>4,'monto_total'=>119],
            ['cantidad_min'=>116,'cantidad_max'=>120,'costo'=>120,'aporte_umss'=>4,'monto_total'=>124],
            ['cantidad_min'=>121,'cantidad_max'=>125,'costo'=>125,'aporte_umss'=>4,'monto_total'=>129],
            ['cantidad_min'=>126,'cantidad_max'=>130,'costo'=>130,'aporte_umss'=>4,'monto_total'=>134],
            ['cantidad_min'=>131,'cantidad_max'=>135,'costo'=>135,'aporte_umss'=>4,'monto_total'=>139],
            ['cantidad_min'=>136,'cantidad_max'=>140,'costo'=>140,'aporte_umss'=>4,'monto_total'=>144],
            ['cantidad_min'=>141,'cantidad_max'=>145,'costo'=>145,'aporte_umss'=>4,'monto_total'=>149],
            ['cantidad_min'=>146,'cantidad_max'=>150,'costo'=>150,'aporte_umss'=>4,'monto_total'=>154],
            ['cantidad_min'=>151,'cantidad_max'=>155,'costo'=>155,'aporte_umss'=>4,'monto_total'=>159],
            ['cantidad_min'=>156,'cantidad_max'=>160,'costo'=>160,'aporte_umss'=>4,'monto_total'=>164],
            ['cantidad_min'=>161,'cantidad_max'=>165,'costo'=>165,'aporte_umss'=>4,'monto_total'=>169],
            ['cantidad_min'=>166,'cantidad_max'=>170,'costo'=>170,'aporte_umss'=>4,'monto_total'=>174],
            ['cantidad_min'=>171,'cantidad_max'=>175,'costo'=>175,'aporte_umss'=>4,'monto_total'=>179],
            ['cantidad_min'=>176,'cantidad_max'=>180,'costo'=>180,'aporte_umss'=>4,'monto_total'=>184],
            ['cantidad_min'=>181,'cantidad_max'=>185,'costo'=>185,'aporte_umss'=>4,'monto_total'=>189],
            ['cantidad_min'=>186,'cantidad_max'=>190,'costo'=>190,'aporte_umss'=>4,'monto_total'=>194],
            ['cantidad_min'=>191,'cantidad_max'=>195,'costo'=>195,'aporte_umss'=>4,'monto_total'=>199],
            ['cantidad_min'=>196,'cantidad_max'=>200,'costo'=>200,'aporte_umss'=>4,'monto_total'=>204],
        ];
    }
};
