<?php

namespace App\Domains\Services;

use App\Models\Comissao;
use App\Models\Revendedor;
use App\Models\Venda;
use App\Models\VendaLucro;

class VendaService
{
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function run()
    {
        $calculos = (new CalculosVendaService(
            $this->params['taxa_parametro_id'],
            $this->params['comissao_parametro_id'],
            $this->params['outras_despesas_valor'],
            $this->params['preco_total']
        ))->run();

        $venda = $this->createVenda();
        $vendaLucro = $this->createVendaLucro($venda->id, $calculos);
        $this->createComissoes($vendaLucro->id);

        return $venda;
    }

    /**
     * Cria a venda
     * 
     * @return \App\Models\Venda
     */
    public function createVenda(): Venda
    {
        return Venda::create($this->params);
    }

    /**
     * Popula tabela de lucros
     * 
     * @param int $vendaId
     * @param array $calculos
     * 
     * @return \App\Models\VendaLucro
     */
    public function createVendaLucro($vendaId, $calculos): VendaLucro
    {
        $indicadorDeveReceber = $this->validateDataIndicacao($this->params['revendedor_id']);

        // Verificar comissão do indicado
        $comissao_indicado = $calculos['comissoes']['valor_indicado'];

        // Total Liquido
        $total_liquido = $calculos['total_liquido']['valor'];

        // Se indicador não receber, retirar o valor dele total liquido
        if (!$indicadorDeveReceber) {
            $total_liquido += $comissao_indicado;
            $comissao_indicado = 0;
        }

        $params = [
            'venda_id' => $vendaId,
            'taxa' => $calculos['taxa']['valor'],
            'comissao' => $calculos['comissoes']['valor_revendedor'],
            'comissao_indicado' => $comissao_indicado,
            'outras_despesas' => $calculos['outras_despesas']['valor'],
            'lucro_geral' => $total_liquido
        ];

        return VendaLucro::create($params);
    }

    /**
     * Cria comissão
     * 
     * @param int $vendaLucroId
     * 
     * @return \App\Models\Comissao
     */
    public function createComissoes($vendaLucroId): Comissao
    {
        $params = [
            'vendas_lucros_id' => $vendaLucroId,
            'data_gerado' => date('Y-m-d')
        ];

        return Comissao::create($params);
    }

    /**
     * Verifica se indicador pode receber comissão através da data de indicação
     * 
     * @param int $revendedor_id
     */
    public function validateDataIndicacao($revendedor_id): bool
    {
        $revendedor = Revendedor::findOrFail($revendedor_id);
        $agora = date('Y-m-d');

        // Verifica se existe indicador e se já passou da validade de indicação.
        if (
            $revendedor->indicador_id 
            && $revendedor->validade_indicacao > $agora
        ) {
            return true;
        }

        return false;
    }

}