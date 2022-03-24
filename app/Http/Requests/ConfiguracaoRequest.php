<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfiguracaoRequest extends DefaultRequest
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
            'validade_comissao_indicado' => 'required|integer|numeric',
            'recuperar_descricao_compra' => 'required|boolean',
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
            'validade_comissao_indicado.required' => 'O campo :attribute é obrigatório',
            'validade_comissao_indicado.integer' => 'O campo :attribute deve ser um número',
            'validade_comissao_indicado.max' => 'O campo :attribute deve ter no máximo 3 números',
            'recuperar_descricao_compra.required' => 'O campo :attribute é obrigatório',
            'recuperar_descricao_compra.boolean' => 'O campo :attribute deve ser true ou false'
        ];
    }
}
