<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActividadCreateRequest extends FormRequest
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
            'nombre'=>'required',
            'fi'=>'required|date',
        ];
    }
    public function attributes()
    {
        return [
            'nombre'        => 'nombre de la actividad',
            'fi'    => 'Fecha de inicio',
            'ff'    => 'Fecha de conclusión',
        ];
    }
}
