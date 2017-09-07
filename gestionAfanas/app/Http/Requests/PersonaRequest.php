<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PersonaRequest extends Request
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
            $nif_rule = 'min:9|max:9|required';
            $numSeguridadSocial_rule = 'min:1|max:10|required';
        }
        else
        {
            //crear
            $nif_rule = 'min:9|max:9|required|unique:persona';
            $numSeguridadSocial_rule = 'min:1|max:10|required|unique:persona';
        }

        return [
            'nombre'                =>  'min:1|max:60|required',
            'apellidos'             =>  'min:1|max:255|required',
            'fechaIngreso'          =>  'required',
            'fechaNacimiento'       =>  'required',
            'orienta'               =>  'min:1|max:255|required',
            'nif'                   => $nif_rule,
            'personaContacto'       =>  'max:255',
            'telContacto'           =>  'numeric|min:1|required',
            'numSeguridadSocial'    => $numSeguridadSocial_rule,
            'centroSalud'           =>  'min:1|max:255|required',
            'medicoCabecera'        =>  'max:255',
            'medicacion'            =>  'max:255',
            'vacunacion'            =>  'max:255',
            //'id_centro'           => 'required',
            //'id_vivienda'         => 'required',
        ]; 
    }
}
