<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContaPagamentoRequest extends DefaultRequest
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
            'revendedor_id' => 'required|exists:revendedores,id',
            'banco_id' => 'exists:bancos,id',
            'agencia' => 'digits_between:2,5',
            'digito_agencia' => 'digits:1',
            'conta' => 'digits_between:4,8',
            'digito_conta' => 'digits:1',
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
            'digits_between' => 'O campo :attribute não é válido',
            'digits' => 'O campo :attribute não é válido',
            'revendedor_id.exists' => 'O revendedor informado não existe',
            'banco_id.exists' => 'Banco não cadastrado'
        ];
    }
}
