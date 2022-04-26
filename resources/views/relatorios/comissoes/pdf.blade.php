@extends('relatorios.layout')
@section('title', 'Relatório de Comissões')
    @section('header-left')
        Comissão Fácil
    @endsection

    @section('header-center')
        Relatório de Comissões<br>
        Data: {{date('d/m/Y', strtotime($data['periodo']['de']))}} 
            até {{date('d/m/Y', strtotime($data['periodo']['ate']))}}
    @endsection

    @section('header-right')
        Data: {{date('d/m/Y')}}
    @endsection
@section('content')
    @if(!empty($data['revendedores']))
        @foreach ($data['revendedores'] as $key => $revendedor)
            <strong><p>Revendedor: {{$revendedor['nome']}}</p></strong>
            <table style="width: 100%; font-size: 85%">
                <thead>
                    <th style="text-align: center">ID</th>
                    <th style="text-align: center">Venda ID</th>
                    <th style="text-align: left">Tipo</th>
                    <th style="text-align: right">Valor Comissão</th>
                    <th style="text-align: center">Data Gerado</th>
                    <th style="text-align: center">Forma de Pagamento</th>
                    <th style="text-align: center">Data de Pagamento</th>
                    <th style="text-align: center">Pago</th>
                </thead>
                <tbody>
                    @foreach ($revendedor['comissoes'] as $comissao)
                        <tr>
                            <td style="text-align: center">{{$comissao['comissao_id']}}</td>
                            <td style="text-align: center">{{$comissao['venda_id']}}</td>
                            <td style="text-align: left">{{$comissao['descricao']}}</td>
                            <td style="text-align: right">R${{$comissao['comissao_calculado']}}</td>
                            <td style="text-align: center">{{$comissao['data_gerado']}}</td>
                            <td style="text-align: center">{{$comissao['forma_pagamento']}}</td>
                            <td style="text-align: center">{{$comissao['data_pagamento']}}</td>
                            <td style="text-align: center">{{$comissao['pago'] ? 'SIM' : 'NÃO'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="text-align: right;"><strong>Total Revendedor: R${{number_format($revendedor['total_revendedor'], 2, ',', '.')}}</strong></p>
            <hr>
        @endforeach
        <p style="text-align: right;"><strong>Total Geral Período: R${{number_format($data['total_geral'], 2, ',', '.')}}</strong></p>
    @else
        Nada encontrado.
    @endif
@endsection