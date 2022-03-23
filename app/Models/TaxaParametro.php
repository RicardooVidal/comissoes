<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxaParametro extends Model
{
    use HasFactory;
    protected $table = 'taxas_parametros';
    protected $fillable = ['descricao', 'taxa_percentual', 'ativo'];
    public $timestamps = false;
}
