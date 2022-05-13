<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'nombres'=>'required|min:2|max:30',
            'apellidos'=>'required|min:2|max:40',
            'celular'=>'required|min:8|max:10',
            'direccion'=>'required|min:2|max:100',
            'usuario'=>'required|min:4|max:15',
            'rol'=>'required|max:15',
            'estado'=>'required',
            'password'=>'required'
        ];
    }
}
