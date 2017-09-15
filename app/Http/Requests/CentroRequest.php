<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CentroRequest extends Request
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
        if($this->method() == 'PUT')
        {
            //editar
            $direccion_rule = 'min:4|max:255|required';
        }
        else
        {
            //crear
            $direccion_rule = 'min:4|max:255|required|unique:centro';
        }

        return [
            'nombre'    =>  'min:1|max:60|required',
            'direccion' =>  $direccion_rule,
            'poblacion' =>  'min:4|max:120|required',
        ];
    }
}
