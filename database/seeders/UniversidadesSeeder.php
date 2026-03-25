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
            ['nombre' => 'Universidad Mayor de San Simón', 'sigla' => 'UMSS', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Mayor de San Andrés', 'sigla' => 'UMSA', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Autónoma Gabriel René Moreno', 'sigla' => 'UAGRM', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Autónoma Juan Misael Saracho', 'sigla' => 'UAJMS', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Técnica de Oruro', 'sigla' => 'UTO', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Autónoma Tomás Frías', 'sigla' => 'UATF', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Pública de El Alto', 'sigla' => 'UPEA', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Autónoma del Beni José Ballivián', 'sigla' => 'UABJB', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Mayor Real y Pontificia San Francisco Xavier de Chuquisaca', 'sigla' => 'USFX', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Andina Simón Bolívar', 'sigla' => 'UASB', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Nacional Siglo XX', 'sigla' => 'UNSXX', 'tipo' => 'Pública'],
            ['nombre' => 'Universidad Amazónica de Pando', 'sigla' => 'UAP', 'tipo' => 'Pública'],
            
            // Privadas
            ['nombre' => 'Universidad Católica Boliviana San Pablo', 'sigla' => 'UCB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada del Valle', 'sigla' => 'UNIVALLE', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada Franz Tamayo', 'sigla' => 'UNIFRANZ', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Autónoma del Beni', 'sigla' => 'UAB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada Santa Cruz de la Sierra', 'sigla' => 'PRUCR', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada Domingo Savio', 'sigla' => 'UPDS', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Especializada de Full Tiempo', 'sigla' => 'UNEFT', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Tecnológica Latinoamericana', 'sigla' => 'UTLAT', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad de Aquino Bolivia', 'sigla' => 'UDABOL', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad de los Andes', 'sigla' => 'UDELOSANDES', 'tipo' => 'Privada'],
            ['nombre' => 'Escuela Militar de Ingeniería', 'sigla' => 'EMI', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Simón I. Patiño', 'sigla' => 'USIP', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Central', 'sigla' => 'UNICEN', 'tipo' => 'Privada'],
            ['nombre' => 'Uni. Priv. de Cs. Administrativas y Tecnológicas', 'sigla' => 'UCATEC', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Pedagógica', 'sigla' => 'UP', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada Abierta Latinoamericana', 'sigla' => 'UPAL', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada Boliviana', 'sigla' => 'UPB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Técnica Privada Cosmos', 'sigla' => 'UNITEPC', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Latinoamericana', 'sigla' => 'ULAT', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Indígena Quechua Casimiro Huanca', 'sigla' => 'UNIBOL QUECHUA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad La Salle', 'sigla' => 'ULS', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Boliviana de Informática', 'sigla' => 'UBI', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Militar Mariscal Bernardino Bilbao Rioja', 'sigla' => 'UMBBBR', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Nur', 'sigla' => 'NUR', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Loyola', 'sigla' => 'LOYOLA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Nuestra Señora de La Paz', 'sigla' => 'UNSLP', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada San Francisco de Asis', 'sigla' => 'USFA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Real', 'sigla' => 'UREAL', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Salesiana de Bolivia', 'sigla' => 'USALESIANA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad para la Investig. Estratégica en Bolivia', 'sigla' => 'UPIEB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Tecnológica Boliviana', 'sigla' => 'UTB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Unión Bolivariana', 'sigla' => 'UB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Saint Paul', 'sigla' => 'USP', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad de la Cordillera', 'sigla' => 'UCORDILLERA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Indígena Boliviana Aymara Túpac Katari', 'sigla' => 'UTUPAKKATARI', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Indígena Tawantinsuyu', 'sigla' => 'UINTAN', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada De Oruro', 'sigla' => 'UNIOR', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Nacional del Oriente', 'sigla' => 'UNO', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Nacional Ecológica', 'sigla' => 'UECOLOGICA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Cristiana de Bolivia', 'sigla' => 'UCEBOL', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Empresarial Mateo Kuljis', 'sigla' => 'UNIKULJIS', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Evangélica Boliviana', 'sigla' => 'UEB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad de la Amazonía Boliviana', 'sigla' => 'UNAB', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad para el Desarrollo y la Innovación', 'sigla' => 'UDI', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada Cumbre', 'sigla' => 'CUMBRE', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Bethesda', 'sigla' => 'UNIBETH', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Privada de Santa Cruz de la Sierra', 'sigla' => 'UPSA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Tecnológica Privada de Santa Cruz', 'sigla' => 'UTEPSA', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Indígena Guaraní Apiaguaiki Tupa', 'sigla' => 'UNIBOLGUARANI', 'tipo' => 'Privada'],
            ['nombre' => 'Universidad Unidad', 'sigla' => 'UUNIDAD', 'tipo' => 'Privada'],
            ['nombre' => 'Hardvard', 'sigla' => 'HARDVARD', 'tipo' => 'Extranjera'],
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
