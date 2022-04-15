<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ComissaoRequest extends FormRequest
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
            'id' => 'required',
            'forma_pagamento_id' => 'sometimes|exists:forma_pagamentos,id'
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
            'id.required' => 'Não foi possível prosseguir, falta o ID da comissão',
            'forma_pagamento_id.exists' => 'Forma de pagamento inexistente'
        ];
    }
}
