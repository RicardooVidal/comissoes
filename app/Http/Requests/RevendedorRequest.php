<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'id' => 'required_if:id,!=,' . $this->id . '|integer|unique:revendedores,id, ' . $this->id . '|digits:11',
            'rg' => 'nullable|unique:revendedores,rg, ' . $this->id . '|min:9|max:9',
            // 'nome' => 'required|unique:revendedores,nome, ' . $this->id,
            'email' => 'email|unique:revendedores,email,' . $this->id,
            'celular' => 'required|unique:revendedores,celular,' . $this->id,
            'banco_id' => 'nullable|exists:bancos,id',
            'agencia' => 'nullable|min:4',
            // 'conta' => ['nullable', 'required_unless:agencia,null', Rule::unique('contas_pagamentos', 'conta')->ignore($this->id, 'revendedor_id')],
            'digito_conta' => 'nullable|required_unless:conta,null|min:1',
            // 'pix' => ['nullable', 'required_unless:agencia,null', Rule::unique('contas_pagamentos', 'pix')->ignore($this->id, 'revendedor_id')],
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
            'id.required' => 'O campo CPF é obrigatório',
            'rg.min' => 'RG inválido',
            'required' => 'O campo :attribute é obrigatório',
            'id.integer' => 'O campo CPF não é válido',
            'integer' => 'O campo :attribute não é válido',
            'id.digits' => 'O campo CPF não é válido',
            'digits' => 'O campo :attribute não é válido',
            'nome.unique' => 'Nome já cadastrado. DICA: adicione uma abreviação ou apelido para diferenciar.',
            'id.unique' => 'CPF já cadastrado',
            'rg.unique' => 'RG já cadastrado',
            'email.unique' => 'Email já cadastrado',
            'celular.unique' => 'Celular já cadastrado',
            'conta.unique' => 'Conta já cadastrada',
            'pix.unique' => 'Pix já cadastrado',
            'email.email' => 'O campo :attribute não é um e-mail válido',
            'banco_id.exists' => 'Banco não cadastrado',
            'agencia.min' => 'A agência deve ter 4 digitos',
            'agencia.max' => 'A agência deve ter 4 digitos',
            'conta.required_unless' => 'Favor, informe a conta',
            'digito_conta.required_unless' => 'Favor, informe o dígito da conta',
            'conta.min' => 'A conta deve ter no mínimo 4 dígitos',
            'conta.required_with' => 'Favor especificar a conta',
            'ativo.boolean' => 'O campo :attribute deve ser do tipo boolean'
        ];
    }
}
