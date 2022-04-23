<?php

namespace App\Http\Controllers;

use App\Helpers\PDF;
use App\Models\Comissao;
use App\Models\Venda;
use Illuminate\Http\Request;

class RelatorioController extends Controller
{
    private const COMISSAO_VENDA = 'COMISSÃO POR VENDA';
    private const COMISSAO_INDICACAO = 'COMISSÃO POR INDICAÇÃO';
    
    public function comissoes(Request $request)
    {
        $totalRevendedor = 0;
        $totalGeral = 0;
        $comissoesTreated = [
            'periodo' => [],
            'revendedores' => [],
            'total_geral' => $totalGeral
        ];

        $request->validate( 
            [
                'tipo' => 'required',
                'de_dias_comissoes' => 'required|date',
                'ate_dias_comissoes' => 'required|date',
                'pago' => 'required',
            ],
            [
                'tipo.required' => 'Favor informar o tipo de comissão',
                'de_dias_comissoes.required' => 'Favor, informar a data inicial',
                'ate_dias_comissoes.required' => 'Favor, informar a data final',
                'pago.required' => 'Favor informar o status pago'
            ]
        );

        $tipo = 'COMISSÃO POR VENDA';

        if ($request->tipo == 2) {
            $tipo = 'COMISSÃO POR INDICAÇÃO';
        }

        if ($request->tipo != 'todos') {
            $comissoes = Comissao::where('descricao', $tipo)
            ->whereBetween('data_gerado', [$request->de_dias_comissoes, $request->ate_dias_comissoes])
            ->with('revendedor')
            ->with('calculos')
            ->with('forma_pagamento')
            ->orderBy('data_gerado');
        } else {
            $comissoes = Comissao::whereBetween('data_gerado', [$request->de_dias_comissoes, $request->ate_dias_comissoes])
            ->with('revendedor')
            ->with('calculos')
            ->with('forma_pagamento')
            ->orderBy('venda_id')
            ->orderBy('data_gerado');
        }

        if (isset($request->revendedor) && $request->revendedor != 'todos') {
            $comissoes = $comissoes->where('revendedor_id', $request->revendedor);
        }

        if ($request->pago != 'todos') {
            $comissoes = $comissoes->where('pago', $request->pago);
        }

        // Períodos
        $comissoesTreated['periodo'] = [
            'de' => $request->de_dias_comissoes,
            'ate' => $request->ate_dias_comissoes
        ];

        // Get
        $comissoes = $comissoes->get();

        if (empty($comissoes->first())) {
            return PDF::generatePdf($comissoesTreated, 'relatorios.comissoes.pdf', $request->stream);
        }

        foreach ($comissoes as $comissao) {
            $comissoesTreated['revendedores'][$comissao->revendedor_id]['nome'] = $comissao->revendedor['nome'];
            $comissoesTreated['revendedores'][$comissao->revendedor_id]['comissoes'][] = [
                'comissao_id' => $comissao->id,
                'venda_id' => $comissao->calculos['venda_id'],
                'descricao' => $comissao->descricao,
                'comissao_calculado' => $comissao->descricao == 
                    self::COMISSAO_VENDA ? $comissao->calculos['comissao_calculado'] 
                    : $comissao->calculos['comissao_indicado_calculado'],
                'data_gerado' => date('d/m/Y', strtotime($comissao->data_gerado)),
                'forma_pagamento' => $comissao->forma_pagamento != null ? $comissao->forma_pagamento['descricao'] : null,
                'data_pagamento' => $comissao->data_pagamento ? date('d/m/Y', strtotime($comissao->data_pagamento)) : null,
                'pago' => $comissao->pago
            ];
        }

        // Calcular total por revendedor (depois vejo outra maneira de fazer isso mais perfomática)
        foreach($comissoesTreated['revendedores'] as &$comissao) {
            $totalRevendedor = 0;
            ksort($comissao);
            foreach($comissao['comissoes'] as $valores) {
                $totalRevendedor += $valores['comissao_calculado'];
            }
            $comissao['total_revendedor'] = $totalRevendedor;
            $totalGeral += $totalRevendedor;
        }

        // Setar o total geral
        $comissoesTreated['total_geral'] = $totalGeral;

        return PDF::generatePdf($comissoesTreated, 'relatorios.comissoes.pdf', $request->stream);
    }

    public function vendas(Request $request)
    {
        $totalRevendedor = 0;
        $totalRevendedorIndicado = 0;
        $totalGeralLiquido = 0;
        $totalGeralBruto = 0;
        $vendasTreated = [
            'periodo' => [],
            'vendas' => [],
            'total_geral_liquido' => $totalGeralLiquido,
            'total_geral_bruto' => $totalGeralBruto
        ];

        $request->validate( 
            [
                'de_dias_vendas' => 'required|date',
                'ate_dias_vendas' => 'required|date',
            ],
            [
                'de_dias_vendas.required' => 'Favor, informar a data inicial',
                'ate_dias_vendas.required' => 'Favor, informar a data final',
            ]
        );

        // Períodos
        $vendasTreated['periodo'] = [
            'de' => $request->de_dias_vendas,
            'ate' => $request->ate_dias_vendas
        ];

        $vendas = Venda::whereBetween('data_venda', [$request->de_dias_vendas, $request->ate_dias_vendas])
            ->with([
                'taxa_parametro',
                'comissao_parametro',
                'calculos'
            ])
            ->with('revendedor', function($q) {
                $q->with('indicador', function($q) {
                    $q->select(['indicadores.id', 'revendedores.id AS id_revendedor', 'revendedores.nome'])
                        ->join('revendedores', 'indicadores.revendedor_id', '=', 'revendedores.id');
                });
            })
            ->orderBy('data_venda');

        if (isset($request->revendedor) && $request->revendedor != 'todos') {
            $vendas = $vendas->where('revendedor_id', $request->revendedor);
        }

        // Get
        $vendas = $vendas->get();

        if (empty($vendas->first())) {
            return PDF::generatePdf($vendasTreated, 'relatorios.comissoes.pdf', $request->stream);
        }
        // dd($vendas);
        foreach ($vendas as $venda) {
            $vendasTreated['vendas'][$venda->revendedor_id]['nome'] = 
                $venda->revendedor['id'] . ' - '
                . $venda->revendedor['nome'];
            $vendasTreated['vendas'][$venda->revendedor_id]['nome_indicador'] = 
                $venda->revendedor->indicador['id_revendedor'] . ' - '
                . $venda->revendedor->indicador['nome'];
            $vendasTreated['vendas'][$venda->revendedor_id]['vendas'][] = [
                'venda_id' => $venda->id,
                'descricao' => strlen($venda->descricao) > 20 ? substr($venda->descricao, 0, 20) . '...' : $venda->descricao,
                'taxa_percentual' => $venda->calculos->taxa_percentual,
                'taxa_calculado' => $venda->calculos->taxa_calculado,
                'comissao_percentual' => $venda->calculos->comissao_percentual,
                'comissao_calculado' => $venda->calculos->comissao_calculado,
                'comissao_indicado_percentual' => $venda->calculos->comissao_indicado_percentual,
                'comissao_indicado_calculado' => $venda->calculos->comissao_indicado_calculado,
                'outras_despesas_bruto' => $venda->calculos->outras_despesas_bruto,
                'total_bruto' => $venda->quantidade * $venda->preco_unitario,
                'lucro_geral_calculado' => $venda->calculos->lucro_geral_calculado
            ];
        }

        // Calcular total por revendedor (depois vejo outra maneira de fazer isso mais perfomática)
        foreach($vendasTreated['vendas'] as &$venda) {
            $totalRevendedor = 0;
            $totalRevendedorIndicado = 0;
            ksort($venda);
            foreach($venda['vendas'] as $valores) {
                $totalGeralLiquido += $valores['lucro_geral_calculado'];
                $totalGeralBruto += $valores['total_bruto'];
                $totalRevendedor += $valores['comissao_calculado'];
                $totalRevendedorIndicado += $valores['comissao_indicado_calculado'];
            }
            $venda['total_revendedor'] = $totalRevendedor;
            $venda['total_indicador_revendedor'] = $totalRevendedorIndicado;
        }

        // Setar o total geral
        $vendasTreated['total_geral_bruto'] = $totalGeralBruto;
        $vendasTreated['total_geral_liquido'] = $totalGeralLiquido;

        return PDF::generatePdf($vendasTreated, 'relatorios.vendas.pdf', $request->stream);

    }
}