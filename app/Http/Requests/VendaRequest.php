<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VendaRequest extends DefaultRequest
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
            'revendedor_id' => 'required|exists:revendedores,id',
            'taxa_parametro_id' => 'required|exists:taxas_parametros,id',
            'comissao_parametro_id' => 'required|exists:comissoes_parametros,id',
            'outras_despesas_valor' => 'numeric',
            'descricao' => 'required',
            'preco_unitario' => 'required|numeric',
            'quantidade' => 'required|numeric'
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
            'numeric' => 'O campo :attribute é inválido',
            'revendedor_id.exists' => 'Revendedor não existe',
            'taxa_parametro_id.exists' => 'Parâmetro de taxa referenciada não existe',
            'comissao_parametro_id.exists' => 'Parâmetro de comissões referenciada não existe',
        ];
    }
}
