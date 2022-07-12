<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use \Illuminate\Contracts\Validation\Validator;

class UserLoginRequest extends FormRequest
{
    public $validator = null;
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
            'email' => 'required|string|email|',
            'password' => 'required|string|min:8'
        ];
    }

    public function messages(){
        return [
            'email.required' => 'O campo "Email" é obrigatório.',
            'password.required' => 'O campo "Senha" é obrigatório.',

            'name.max' => 'O campo "Nome" pode ter no máximo 255 caracteres.',

            'password.min' => 'O campo "Senha" tem que ter no minimo 8 caracteres.',
        ];
    }

    protected function failedValidation(Validator $validator) {
        $this->validator = $validator;
    }
}
