<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaxaParametroRequest extends DefaultRequest
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
            'descricao' => 'required|unique:taxas_parametros,descricao,' . $this->id,
            'taxa_percentual' => 'numeric',
            'ativo' => 'required|boolean'
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
            'taxa_percentual.numeric' => 'Taxa Percentual inválida',
            'unique' => 'A descrição já existe'
        ];
    }
}
