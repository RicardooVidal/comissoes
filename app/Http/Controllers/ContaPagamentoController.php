<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContaPagamentoRequest;
use App\Models\ContaPagamento;
use App\Models\Revendedor;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ContaPagamentoController extends Controller
{
    /**
     * @param \App\Models\ContaPagamento
     */
    private $contaPagamento;

    public function __construct(ContaPagamento $contaPagamento)
    {
        $this->contaPagamento = $contaPagamento;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(
            $this->contaPagamento
                ->with('revendedor')
                ->with('banco')
                ->get()
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\ContaPagamentoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContaPagamentoRequest $request)
    {
        //Inserir informações de conta de pagamento
        $contaPagamento = $this->contaPagamento->create($request->all());

        //Cadastrar id da conta de pagamento no revendedor
        Revendedor::where('id', $request->revendedor_id)
            ->update(['conta_pagamento_id' => $contaPagamento->id]);

        return $this->success($contaPagamento, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success(
            $this->contaPagamento
                ->with('revendedor')
                ->with('banco')
                ->findOrFail($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ContaPagamento  $contaPagamento
     * @return \Illuminate\Http\Response
     */
    public function edit(ContaPagamento $contaPagamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\ContaPagamentoRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContaPagamentoRequest $request, $id)
    {
        $contaPagamento = $this->contaPagamento->findOrFail($id);
        if ($request->revendedor_id) {
            return $this->error('Não é possível alterar o revendedor');
        }

        $contaPagamento->fill($request->all());
        $contaPagamento->save();
        return $this->success($contaPagamento, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contaPagamento = $this->contaPagamento->findOrFail($id);
        return $this->success($contaPagamento->delete());
    }
}
