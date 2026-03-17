<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'login'=>'required|email',
            'ci'=>'required',
            'contacto'=>'required',
            'direccion'=>'required',
            'rol'=>'required',
        ];
    }
    public function attributes()
    {
        return [
            'nombre'=>'Nombre',
            'login'=>'Login',
            'ci'=>'Cédula de Identidad',
            'contacto'=>'Contacto',
            'direccion'=>'Dirección'
        ];
    }
}
