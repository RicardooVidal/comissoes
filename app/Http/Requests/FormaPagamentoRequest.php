<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormaPagamentoRequest extends DefaultRequest
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
        return [
            'descricao' => 'required|unique:forma_pagamento,descricao,' . $this->id
        ];
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
            'unique' => 'Forma de Pagamento já cadastrada'
        ];
    }
}
