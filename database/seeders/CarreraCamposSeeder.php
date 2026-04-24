<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CarreraCamposImport;
use Illuminate\Support\Facades\Storage;

class CarreraCamposSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Asumimos que el archivo excel está en storage/app/carreras.xlsx
        $filePath = storage_path('app/carreras.xlsx');

        if (file_exists($filePath)) {
            $this->command->info('Iniciando importación de carreras desde storage/app/carreras.xlsx...');
            Excel::import(new CarreraCamposImport, $filePath);
            $this->command->info('¡Importación completada con éxito!');
        } else {
            $this->command->error("No se encontró el archivo Excel en: {$filePath}");
            $this->command->error('Por favor, asegúrate de colocar el archivo carreras.xlsx en la carpeta storage/app/ antes de correr el seeder.');
        }
    }
}
