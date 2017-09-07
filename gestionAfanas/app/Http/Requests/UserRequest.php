<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
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
            $email_rule = 'min:4|max:250|required';
            $password_rule = 'min:4|max:120';
        }
        else
        {
            //crear
            $email_rule = 'min:4|max:250|required|unique:users';
            $password_rule = 'min:4|max:120|required';
        }

        return [
            'name'      => 'min:4|max:120|required',
            'email'     => $email_rule, //users -> nombre de la tabla
            'password'  => $password_rule
        ];
    }
}
