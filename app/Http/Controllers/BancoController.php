<?php

namespace App\Http\Controllers;

use App\Http\Requests\BancoRequest;
use App\Models\Banco;
use App\Models\Comissao;
use App\Models\ContaPagamento;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    private $banco;
    public function __construct(Banco $banco)
    {
        $this->banco = $banco;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(
            $this->banco
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
     * @param  App\Http\Requests\BancoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BancoRequest $request)
    {
        return $this->success($this->banco->create([
            'id' => $request->id,
            'descricao' => $request->descricao,
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  Integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banco = $this->banco->findOrFail($id);
        return $this->success($banco);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Banco  $banco
     * @return \Illuminate\Http\Response
     */
    public function edit(Banco $banco)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\BancoRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(BancoRequest $request, $id)
    {
        $banco = $this->banco->findOrFail($id);
        $banco->fill($request->all());

        $banco->save();

        return $this->success($banco);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id == 999) {
            return $this->error('Parâmetro padrão. Não pode ser deletado');
        }

        $banco = $this->banco->findOrFail($id);

        // Verificar se o id do banco foi utilizado em cadastro de revendedores
        $contas_pagamentos = ContaPagamento::where('banco_id', $id)->get();

        foreach($contas_pagamentos as $contas_pagamentos) {
            $revendedor_id = $contas_pagamentos->revendedor_id; 
            if ($contas_pagamentos->banco_id == $id) {
                return $this->error("Não foi possível deletar o banco. O banco é utilizado pelo revendedor nº$revendedor_id");
            }
        }

        return $this->success($banco->delete());
    }
}
