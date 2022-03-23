<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    use HasFactory;
    protected $table = 'comissoes';
    protected $fillable = [
        'vendas_lucros_id',
        'forma_pagamento_id',
        'pago',
        'data_gerado',
        'data_pagamento'
    ];
    public $timestamps = false;
}
