<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RevendedorRequest extends DefaultRequest
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
        $this->validateMethod();

        $rules = [
            'cpf' => 'integer|unique:revendedores,cpf, ' . $this->id . '|digits:11',
            'nome' => 'required|unique:revendedores,nome, ' . $this->id,
            'email' => 'email|required|unique:revendedores,email,' . $this->id,
            'celular' => 'required|unique:revendedores,celular,' . $this->id,
            'ativo' => 'required|boolean'
        ];

       return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'integer' => 'O campo :attribute não é válido',
            'digits' => 'O campo :attribute não é válido',
            'nome.unique' => 'Nome já cadastrado. DICA: adicione uma abreviação ou apelido para diferenciar.',
            'cpf.unique' => 'Cpf já cadastrado',
            'email.unique' => 'Email já cadastrado',
            'celular.unique' => 'Celular já cadastrado',
            'email.email' => 'O campo :attribute não é um e-mail válido',
            'ativo.boolean' => 'O campo :attribute deve ser do tipo boolean'
        ];
    }
}
