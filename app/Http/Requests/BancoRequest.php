<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BancoRequest extends DefaultRequest
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
            'id' => 'integer|min:3|unique:bancos,id,' . $this->id,
            'descricao' => 'required|min:2|unique:bancos,descricao,' . $this->id,
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
            'integer' => 'O campo :attribute deve ser um número',
            'id.min' => 'O campo :attribute deve ter no mínimo 3 números',
            'id.unique' => 'Banco já cadastrado',
            'descricao.required' => 'O campo :attribute é obrigatório',
            'descricao.min' => 'O campo :attribute deve ter no mínimo 2 caracteres',
            'descricao.unique' => 'Descrição já cadastrada'
        ];
    }
}
