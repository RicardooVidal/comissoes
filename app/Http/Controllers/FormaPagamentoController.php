<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaPagamentoRequest;
use App\Models\Comissao;
use App\Models\FormaPagamento;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class FormaPagamentoController extends Controller
{
    private $formaPagamento;

    public function __construct(FormaPagamento $formaPagamento)
    {
        $this->formaPagamento = $formaPagamento;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(
            $this->formaPagamento
                ->orderBy('id')
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
     * @param  \App\Http\Requests\FormaPagamentoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FormaPagamentoRequest $request)
    {
        return $this->success($this->formaPagamento->create($request->all()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success($this->formaPagamento->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormaPagamento  $formaPagamento
     * @return \Illuminate\Http\Response
     */
    public function edit(FormaPagamento $formaPagamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\FormaPagamentoRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(FormaPagamentoRequest $request, $id)
    {
        $formaPagamento = $this->formaPagamento->findOrFail($id);
        $formaPagamento->fill($request->all());
        $formaPagamento->save();
        return $this->success($formaPagamento);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $formaPagamento = $this->formaPagamento->findOrFail($id);

       // Verificar se o id do banco foi utilizado em cadastro de revendedores
       $comissoes = Comissao::where('forma_pagamento_id', $id)->get();

       foreach($comissoes as $comissao) {
           $comissao_id = $comissao->id; 
           if ($comissao->forma_pagamento_id == $id) {
               return $this->error("Não foi possível deletar a forma de pagamento. Foi utilizado para registrar o pagamento da comissão nº$comissao_id");
           }
       }

        return $this->success($formaPagamento->delete());
    }
}
