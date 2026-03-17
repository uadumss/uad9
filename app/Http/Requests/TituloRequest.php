<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TituloRequest extends FormRequest
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
            'nro'=>'required|numeric',            
            'apellido'=>'required',
            'nombre'=>'required',
            'pdf'  => 'max:2048',
            'pdf_ant'  => 'max:25000'

        ];
    }
    public function attributes()
    {
        return [
            'nro' => 'Número de título',
            
            'ci'    => 'Cédula de Identidad',
            'apellido' => 'Apellido',
            'nombre' =>'Nombre',
            'pdf'=>'Título',
            'pdf_ant'=>'Antecedentes',
        ];
    }
    public function messages()
    {
        return [
            'pdf.max' =>  'El título en PDF debe ser menor de 2048 KB',
            'pdf_ant.max' =>  'Los antecedentes en PDF deben ser menor de 15360 KB',
        ];
    }
}
