<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;
    protected $table = 'vendas';
    protected $fillable = [
        'revendedor_id',
        'taxa_parametro_id',
        'comissao_parametro_id',
        'outras_despesas_valor',
        'outras_despesas_descricao',
        'descricao',
        'preco_unitario',
        'quantidade',
        'preco_total',
        'link_venda',
        'data_venda'
    ];
    public $timestamps = false;

    public function revendedor()
    {
        return $this->belongsTo('App\Models\Revendedor');
    }

    public function taxa_parametro()
    {
        return $this->hasOne('App\Models\TaxaParametro', 'id', 'taxa_parametro_id');
    }

    public function comissao_paramero()
    {
        return $this->hasOne('App\Models\ComissaoParametro', 'id', 'comissao_parametro_id');
    }
}
