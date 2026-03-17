<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TomoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'n_tomo'=>'required|numeric',
            'gestion'=>'numeric',
            'r_menor'=>'numeric',
            'r_mayor'=>'numeric',
            'tipo'=>'required'
        ];
    }
    public function attributes()
    {
        return [
            'n_tomo' => 'Número de tomo',
            'gestion'    => 'Gestión',
            'r_menor'    => 'De',
            'r_mayor' => 'Hasta',
            'tipo' =>'Tipo',
        ];
    }
}
