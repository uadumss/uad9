<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarreraCineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return; // Desactivado temporalmente a petición

        // Como cada carrera es diferente, NECESITAMOS un "diccionario" o mapa
        // que le diga a Laravel qué valores corresponden a qué carrera.

        foreach ($datosCine as $nombreCarrera => $valores) {
            \App\Models\Carrera::where('car_nombre', $nombreCarrera)->update($valores);
        }

        /* 
        // Forma 2: LEER DESDE UN EXCEL/CSV (Ideal si son cientos de carreras)
        // Si tienes un archivo CSV con las columnas: nombre_carrera, campo_amplio, campo_especifico, campo_detallado
        // puedes hacer que Laravel lo lea automáticamente así:
        
        $csvFile = fopen(base_path("database/data/carreras_cine.csv"), "r");
        fgetcsv($csvFile); // saltar la cabecera
        while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
            \App\Models\Carrera::where('car_nombre', $data[0])->update([
                'car_campo_amplio' => $data[1],
                'car_campo_especifico' => $data[2],
                'car_campo_detallado' => $data[3]
            ]);
        }
        fclose($csvFile);
        */
    }
}
