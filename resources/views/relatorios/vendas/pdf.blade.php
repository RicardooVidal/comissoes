@extends('relatorios.layout')
@section('title', 'Relatório de Vendas')
    @section('header-left')
        Comissão Fácil
    @endsection

    @section('header-center')
        Relatório de Vendas<br>
        Data: {{date('d/m/Y', strtotime($data['periodo']['de']))}} 
            até {{date('d/m/Y', strtotime($data['periodo']['ate']))}}
    @endsection

    @section('header-right')
        Data: {{date('d/m/Y')}}
    @endsection
@section('content')
    @if(!empty($data['vendas']))
        @foreach ($data['vendas'] as $key => $venda)
            <strong>Revendedor: {{$venda['nome']}}</strong><br>
            <strong>Indicador: {{$venda['nome_indicador']}}</strong>
            <br><br>
            <table style="width: 100%; font-size: 75%">
                <thead>
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Data</th>
                    <th style="text-align: left">Descrição</th>
                    <th style="text-align: right">Taxa Calculado</th>
                    <th style="text-align: right">Comissão</th>
                    <th style="text-align: right">Comissão Indicador</th>
                    <th style="text-align: right">Outras Desp. Bruto</th>
                    <th style="text-align: right">Total Bruto</th>
                    <th style="text-align: right">Total Líquido</th>
                </thead>
                <tbody>
                    @foreach ($venda['vendas'] as $v)
                        <tr>
                            <td style="text-align: center">{{$v['venda_id']}}</td>
                            <td style="text-align: center">{{$v['data_venda']}}</td>
                            <td style="text-align: left">{{$v['descricao']}}</td>
                            <td style="text-align: right">R${{$v['taxa_calculado']}} ({{$v['taxa_percentual'] * 100}}%)</td>
                            <td style="text-align: right">R${{$v['comissao_calculado']}} ({{$v['comissao_percentual'] * 100}}%)</td>
                            <td style="text-align: right">R${{$v['comissao_indicado_calculado']}} ({{$v['comissao_indicado_percentual'] * 100}}%)</td>
                            <td style="text-align: right">R${{$v['outras_despesas_bruto']}}</td>
                            <td style="text-align: right">R${{$v['total_bruto']}}</td>
                            <td style="text-align: right">R${{$v['lucro_geral_calculado']}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="width: 100%">
                <p style="text-align: right;"><strong>Total Revendedor: R${{number_format($venda['total_revendedor'], 2, ',', '.')}}</strong></p>
                <p style="text-align: right;"><strong>Total Indicador Revendedor: R${{number_format($venda['total_indicador_revendedor'], 2, ',', '.')}}</strong></p>
            </div>
            <hr>
        @endforeach
        <div class="width: 100%">
            <p style="text-align: right;"><strong>Total Bruto Período: R${{number_format($data['total_geral_bruto'], 2, ',', '.')}}</strong></p>
            <p style="text-align: right;"><strong>Total Líquido Período: R${{number_format($data['total_geral_liquido'], 2, ',', '.')}}</strong></p>
        </div>
    @else
        Nada encontrado.
    @endif
@endsection