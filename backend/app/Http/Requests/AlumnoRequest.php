<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

use Illuminate\Support\Facades\Response;

class AlumnoRequest extends Request
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
             'nombre' => 'required|regex:/^[a-zA-Z ]{3,30}$/',
             'apellido_paterno' => 'required|regex:/^[a-zA-Z ]{3,30}$/'
        ];
    }

    public function messages(){
        return [
            'nombre.regex' => 'El NOMBRE esta conformado por letras solamente con un maximo de 30 caracteres',
            'apellido_paterno.regex' => 'El Apellido Paterno esta conformado por letras solamente con un maximo de 30 caracteres'

        ];
    }

    public function response(array $errors)
    {

        return Response::json($errors, 400);
    }
}
