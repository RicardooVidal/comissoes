<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revendedor extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    protected $table = 'revendedores';
    protected $fillable = [
        'indicador_id',
        'conta_pagamento_id',
        'cpf',
        'nome',
        'email',
        'celular',
        'data_indicacao',
        'validade_indicacao',
        'ativo'
    ];

    public function vendas()
    {
        return $this->hasMany('App\Models\Venda');
    }

    public function indicador()
    {
        return $this->belongsTo('\App\Models\Indicador');
    }

    public function conta_pagamento()
    {
        return $this->hasOne('\App\Models\ContaPagamento', 'revendedor_id', 'id');
    }
}
