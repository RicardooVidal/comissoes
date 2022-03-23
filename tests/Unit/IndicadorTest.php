<?php

namespace Tests\Unit;

use App\Models\Configuracao;
use App\Models\Indicador;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndicadorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testa o calculo de dias para uma indicação
     *
     * @return void
     */
    public function test_calcular_quantidade_de_dias_para_indicacao()
    {
        // Alterar a quantidade de dias para 5
        $numeroDias = 5;

        $agora = date('Y-m-d');
        $hojeSomadoComDias = date('Y-m-d', strtotime($agora. " + $numeroDias days"));

        $params = [
            'validade_comissao_indicado' => $numeroDias, // Quantidade de dias para um indicador receber comissão de venda de seus revendedores.
            'recuperar_descricao_compra' => 1  // Desconsiderar este parâmetro para esse teste.
        ];

        Configuracao::find(1)
            ->fill($params)
            ->save();

        $dataValidadeCalculado = Indicador::getDiasIndicacaoValido();

        $this->assertEquals($hojeSomadoComDias, $dataValidadeCalculado);
    }
}
