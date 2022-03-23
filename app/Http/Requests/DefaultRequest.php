<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DefaultRequest extends FormRequest
{
    protected $id = 0;
    public function validateMethod()
    {
        if (
            $this->method() == 'PUT'
            || $this->method() == 'PATCH'
        ) {
            //Recuperar o id
            foreach($this->route()->parameters as $value) {
                $this->id = $value;
                break;
            }
        }
    }
}
