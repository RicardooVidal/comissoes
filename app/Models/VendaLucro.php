<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendaLucro extends Model
{
    use HasFactory;
    protected $table = 'vendas_lucros';
    protected $fillable = [
        'venda_id',
        'taxa_percentual',
        'comissao_percentual',
        'comissao_indicado_calculado',
        'taxa_calculado',
        'comissao_calculado',
        'comissao_indicado_calculado',
        'outras_despesas_bruto',
        'lucro_geral_calculado'
    ];
    public $timestamps = false;
}
