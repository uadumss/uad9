<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Feature Flags Apoderado
    |--------------------------------------------------------------------------
    |
    | Aquí se definen los flags generales para la funcionalidad de Apoderado.
    | requiere_boleta_dj: Si es true, exige validar la boleta en tipo "Declaración Jurada".
    |
    */

    'habilitado' => env('APODERADO_HABILITADO', true),
    'requiere_boleta_dj' => env('REQUIERE_BOLETA_DJ', false),

];
