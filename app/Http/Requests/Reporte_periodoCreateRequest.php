<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Reporte_periodoCreateRequest extends FormRequest
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
            'fi'=>'required|date',
            'ff'=>'required|date',
            'desc'=>'required',
        ];
    }
    public function attributes()
    {
        return [
            'desc'        => 'Reporte',
            'fi'    => 'Fecha de inicio',
            'ff'    => 'Fecha de conclusión',
        ];
    }
}
