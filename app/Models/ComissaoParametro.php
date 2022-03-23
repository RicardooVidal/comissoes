<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComissaoParametro extends Model
{
    use HasFactory;
    protected $table = 'comissoes_parametros';
    protected $fillable = ['descricao', 'comissao_percentual', 'comissao_indicado_percentual', 'ativo'];
    public $timestamps = false;
}
