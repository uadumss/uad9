<?php

namespace Database\Seeders;

use App\Models\Universidad;
use Illuminate\Database\Seeder;

class UniversidadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $universidades = [
            // Públicas
            ['nombre' => 'UNIVERSIDAD MAYOR DE SAN SIMÓN', 'sigla' => 'UMSS', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD MAYOR DE SAN ANDRÉS', 'sigla' => 'UMSA', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD AUTÓNOMA GABRIEL RENÉ MORENO', 'sigla' => 'UAGRM', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD AUTÓNOMA JUAN MISAEL SARACHO', 'sigla' => 'UAJMS', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD TÉCNICA DE ORURO', 'sigla' => 'UTO', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD AUTÓNOMA TOMÁS FRÍAS', 'sigla' => 'UATF', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD PÚBLICA DE EL ALTO', 'sigla' => 'UPEA', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD AUTÓNOMA DEL BENI JOSÉ BALLIVIÁN', 'sigla' => 'UABJB', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD MAYOR REAL Y PONTIFICIA SAN FRANCISCO XAVIER DE CHUQUISACA', 'sigla' => 'USFX', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD ANDINA SIMÓN BOLÍVAR', 'sigla' => 'UASB', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD NACIONAL SIGLO XX', 'sigla' => 'UNSXX', 'tipo' => 'Pública'],
            ['nombre' => 'UNIVERSIDAD AMAZÓNICA DE PANDO', 'sigla' => 'UAP', 'tipo' => 'Pública'],
            
            // Privadas
            ['nombre' => 'UNIVERSIDAD CATÓLICA BOLIVIANA SAN PABLO', 'sigla' => 'UCB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA DEL VALLE', 'sigla' => 'UNIVALLE', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA FRANZ TAMAYO', 'sigla' => 'UNIFRANZ', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD AUTÓNOMA DEL BENI', 'sigla' => 'UAB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA SANTA CRUZ DE LA SIERRA', 'sigla' => 'PRUCR', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA DOMINGO SAVIO', 'sigla' => 'UPDS', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD ESPECIALIZADA DE FULL TIEMPO', 'sigla' => 'UNEFT', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD TECNOLÓGICA LATINOAMERICANA', 'sigla' => 'UTLAT', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD DE AQUINO BOLIVIA', 'sigla' => 'UDABOL', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD DE LOS ANDES', 'sigla' => 'UDELOSANDES', 'tipo' => 'Privada'],
            ['nombre' => 'ESCUELA MILITAR DE INGENIERÍA', 'sigla' => 'EMI', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD SIMÓN I. PATIÑO', 'sigla' => 'USIP', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD CENTRAL', 'sigla' => 'UNICEN', 'tipo' => 'Privada'],
            ['nombre' => 'UNI. PRIV. DE CS. ADMINISTRATIVAS Y TECNOLÓGICAS', 'sigla' => 'UCATEC', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PEDAGÓGICA', 'sigla' => 'UP', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA ABIERTA LATINOAMERICANA', 'sigla' => 'UPAL', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA BOLIVIANA', 'sigla' => 'UPB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD TÉCNICA PRIVADA COSMOS', 'sigla' => 'UNITEPC', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD LATINOAMERICANA', 'sigla' => 'ULAT', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD INDÍGENA QUECHUA CASIMIRO HUANCA', 'sigla' => 'UNIBOL QUECHUA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD LA SALLE', 'sigla' => 'ULS', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD BOLIVIANA DE INFORMÁTICA', 'sigla' => 'UBI', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD MILITAR MARISCAL BERNARDINO BILBAO RIOJA', 'sigla' => 'UMBBBR', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD NUR', 'sigla' => 'NUR', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD LOYOLA', 'sigla' => 'LOYOLA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD NUESTRA SEÑORA DE LA PAZ', 'sigla' => 'UNSLP', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA SAN FRANCISCO DE ASIS', 'sigla' => 'USFA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD REAL', 'sigla' => 'UREAL', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD SALESIANA DE BOLIVIA', 'sigla' => 'USALESIANA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PARA LA INVESTIG. ESTRATÉGICA EN BOLIVIA', 'sigla' => 'UPIEB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD TECNOLÓGICA BOLIVIANA', 'sigla' => 'UTB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD UNIÓN BOLIVARIANA', 'sigla' => 'UB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD SAINT PAUL', 'sigla' => 'USP', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD DE LA CORDILLERA', 'sigla' => 'UCORDILLERA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD INDÍGENA BOLIVIANA AYMARA TÚPAC KATARI', 'sigla' => 'UTUPAKKATARI', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD INDÍGENA TAWANTINSUYU', 'sigla' => 'UINTAN', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA DE ORURO', 'sigla' => 'UNIOR', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD NACIONAL DEL ORIENTE', 'sigla' => 'UNO', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD NACIONAL ECOLÓGICA', 'sigla' => 'UECOLOGICA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD CRISTIANA DE BOLIVIA', 'sigla' => 'UCEBOL', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD EMPRESARIAL MATEO KULJIS', 'sigla' => 'UNIKULJIS', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD EVANGÉLICA BOLIVIANA', 'sigla' => 'UEB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD DE LA AMAZONÍA BOLIVIANA', 'sigla' => 'UNAB', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PARA EL DESARROLLO Y LA INNOVACIÓN', 'sigla' => 'UDI', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA CUMBRE', 'sigla' => 'CUMBRE', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD BETHESDA', 'sigla' => 'UNIBETH', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD PRIVADA DE SANTA CRUZ DE LA SIERRA', 'sigla' => 'UPSA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD TECNOLÓGICA PRIVADA DE SANTA CRUZ', 'sigla' => 'UTEPSA', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD INDÍGENA GUARANÍ APIAGUAIKI TUPA', 'sigla' => 'UNIBOLGUARANI', 'tipo' => 'Privada'],
            ['nombre' => 'UNIVERSIDAD UNIDAD', 'sigla' => 'UUNIDAD', 'tipo' => 'Privada'],
            ['nombre' => 'HARDVARD', 'sigla' => 'HARDVARD', 'tipo' => 'Extranjera'],
        ];

        foreach ($universidades as $universidad) {
            Universidad::firstOrCreate(
                ['sigla' => $universidad['sigla']],
                $universidad
            );
        }

        echo "✓ Universidades importadas exitosamente!\n";
    }
}
