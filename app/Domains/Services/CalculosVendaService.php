<?php

namespace App\Domains\Services;

use App\Models\ComissaoParametro;
use App\Models\TaxaParametro;

class CalculosVendaService
{
    private $taxa_parametro_id;
    private $comissao_parametro_id;
    private $outras_despesas_valor;
    private $total_bruto;
    private $total_liquido;

    private $methods = [
        'taxa',
        'comissoes',
        'outras_despesas',
        'total_liquido'
    ];

    public function __construct(
        $taxa_parametro_id,
        $comissao_parametro_id,
        $outras_despesas_valor,
        $total_bruto
    )
    {
        $this->taxa_parametro_id = $taxa_parametro_id;
        $this->comissao_parametro_id = $comissao_parametro_id;
        $this->outras_despesas_valor = $outras_despesas_valor;
        $this->total_bruto = $total_bruto;
        $this->total_liquido = $total_bruto;
    }

    public function run(): array
    {
        $results = [];
        foreach ($this->methods as $method) {
            $results[$method] = $this->$method();
        }

        return $results;
    }

    /**
     * Recuperar taxa calculada
     * 
     * @return array
     */
    private function taxa(): array
    {
        // Recuperar taxa parâmetro
        $id = $this->taxa_parametro_id;
        $percentual = TaxaParametro::find($id)->taxa_percentual;

        // Calcular a taxa
        $taxa = $this->total_bruto * ($percentual / 100);

        $this->setTotalLiquido($this->total_liquido - $taxa);

        return ['valor' => $taxa];
    }

    /**
     * Recuperar comissões calculadas
     * 
     * @return array
     */
    private function comissoes(): array
    {
        // Recuperar parâmetro de comissões
        $id = $this->comissao_parametro_id;

        $parametro = ComissaoParametro::find($id);

        // Recuperar percentuais
        $percentual_revendedor = $parametro->comissao_percentual;
        $percentual_indicador = $parametro->comissao_indicado_percentual;

        // Calcular comissões sobre o valor bruto
        $total_bruto = $this->total_bruto;
        $comissao_revendedor = $total_bruto * ($percentual_revendedor / 100);
        $comissao_indicador = $total_bruto * ($percentual_indicador / 100);

        $this->setTotalLiquido(
            $this->total_liquido - ($comissao_revendedor + $comissao_indicador)
        );

        return [
            'valor_revendedor' => $comissao_revendedor,
            'valor_indicado' => $comissao_indicador
        ];
    }

    /**
     * Recuperar outras despesas calculadas
     * 
     * @return array
     */
    private function outras_despesas(): array
    {
        $this->setTotalLiquido($this->total_liquido - $this->outras_despesas_valor);

        return ['valor' => $this->outras_despesas_valor];
    }

    /**
     * @return array
     */
    private function total_liquido(): array
    {
        return ['valor' => $this->getTotalLiquido()];
    }

    /**
     * Get valor liquido
     */
    private function getTotalLiquido(): float
    {
        return $this->total_liquido;
    }

    /**
     * Set total liquido
     */
    private function setTotalLiquido($valor): void
    {
        $this->total_liquido = $valor;
    }
}