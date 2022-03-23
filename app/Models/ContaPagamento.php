<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContaPagamento extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'contas_pagamentos';
    protected $fillable = [
        'revendedor_id',
        'banco_id',
        'agencia',
        'digito_agencia',
        'conta',
        'digito_conta',
        'tipo',
        'pix'
    ];

    public function revendedor()
    {
        return $this->belongsTo('App\Models\Revendedor');
    }

    public function banco()
    {
        return $this->belongsTo('App\Models\Banco');
    }
}
