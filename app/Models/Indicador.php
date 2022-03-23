<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicador extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'indicadores';
    protected $fillable = ['revendedor_id'];
    

    public function revendedores()
    {
        return $this->hasMany('App\Models\Revendedor', 'indicador_id', 'id');
    }

    /**
     * Retorna número de dias válido para um indicador sobre um revendedor
     */
    public static function getDiasIndicacaoValido()
    {
        $format = 'Y-m-d';
        $numeroDias = Configuracao::findOrFail(1)->validade_comissao_indicado;
        $agora = date($format);

        return date($format, strtotime($agora. " + $numeroDias days"));
    }
}
