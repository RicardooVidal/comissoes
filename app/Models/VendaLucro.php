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
        'taxa',
        'comissao',
        'comissao_indicado',
        'outras_despesas',
        'lucro_geral'
    ];
    public $timestamps = false;
}
