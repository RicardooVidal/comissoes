<?php

namespace App\Domains\Services;

use App\Models\Comissao;
use App\Models\ComissaoParametro;
use App\Models\Revendedor;
use App\Models\TaxaParametro;
use App\Models\Venda;
use App\Models\VendaLucro;

class VendaService
{
    private const DESCRICAO_COMISSAO = 'COMISSÃO POR VENDA';
    private const DESCRICAO_INDICADOR_COMISSAO = 'COMISSÃO POR INDICAÇÃO';
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
        $this->createComissoes($venda->id, $vendaLucro);

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
        // Recuperar taxa parametro
        $taxa = TaxaParametro::find($this->params['taxa_parametro_id']);

        // Recuperar comissao parametro
        $comissao = ComissaoParametro::find($this->params['taxa_parametro_id']);

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
            'taxa_percentual' => $taxa->taxa_percentual,
            'comissao_percentual' => $comissao->comissao_percentual,
            'taxa_calculado' => $calculos['taxa']['valor'],
            'comissao_calculado' => $calculos['comissoes']['valor_revendedor'],
            'comissao_indicado_percentual' => $comissao->comissao_indicado_percentual,
            'comissao_indicado_calculado' => $comissao_indicado,
            'outras_despesas_bruto' => $calculos['outras_despesas']['valor'],
            'lucro_geral_calculado' => $total_liquido
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
    public function createComissoes($vendaId, VendaLucro $vendaLucro): Comissao
    {
        $indicadorTemComissao = false;

        if($vendaLucro->comissao_indicado_calculado != 0) {
            $indicadorTemComissao = true;
        }

        // Recuperar revendedor
        $revendedor = Revendedor::with('indicador')
            ->findOrFail($this->params['revendedor_id']);

        $params = [
            'revendedor_id' => $revendedor->id,
            'venda_id' => $vendaId,
            'descricao' => self::DESCRICAO_COMISSAO,
            'vendas_lucros_id' => $vendaLucro->id,
            'data_gerado' => date('Y-m-d')
        ];

        // Inserir comissão do revendedor
        $comissao = Comissao::create($params);

        // Verificar indicador e inserir comissão
        if ($comissao && $revendedor->indicador != null) {
            $params = [
                'revendedor_id' => $revendedor->indicador['revendedor_id'],
                'venda_id' => $vendaId,
                'descricao' => self::DESCRICAO_INDICADOR_COMISSAO,
                'vendas_lucros_id' => $vendaLucro->id,
                'data_gerado' => date('Y-m-d')
            ];

            $indicadorTemComissao ? Comissao::create($params) : '';
        }

        return $comissao;
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