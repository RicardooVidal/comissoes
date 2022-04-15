<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comissao extends Model
{
    use HasFactory;
    protected $table = 'comissoes';
    protected $fillable = [
        'revendedor_id',
        'venda_id',
        'descricao',
        'vendas_lucros_id',
        'forma_pagamento_id',
        'pago',
        'data_gerado',
        'data_pagamento'
    ];
    public $timestamps = false;

    public function revendedor()
    {
        return $this->belongsTo('App\Models\Revendedor');
    }

    public function calculos()
    {
        return $this->hasOne('App\Models\VendaLucro', 'id', 'vendas_lucros_id');
    }

    public function forma_pagamento()
    {
        return $this->hasOne('App\Models\FormaPagamento', 'id', 'forma_pagamento_id');
    }
}
