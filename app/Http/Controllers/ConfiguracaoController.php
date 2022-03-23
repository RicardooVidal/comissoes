<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracaoRequest;
use App\Models\Configuracao;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $configuracao = Configuracao::findOrFail(1);
        return $this->success($configuracao);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConfiguracaoRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Configuracoes  $configuracoes
     * @return \Illuminate\Http\Response
     */
    public function show(Configuracao $configuracoes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Configuracoes  $configuracoes
     * @return \Illuminate\Http\Response
     */
    public function edit(Configuracao $configuracoes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Configuracoes  $configuracoes
     * @return \Illuminate\Http\Response
     */
    public function update(ConfiguracaoRequest $request)
    {
        $configuracao = Configuracao::findOrFail(1);

        $configuracao->update([
            'validade_comissao_indicado' => $request->input('validade_comissao_indicado'),
            'recuperar_descricao_compra' => $request->input('recuperar_descricao_compra')
        ]);

        $configuracao->save();
        return response()->json([$configuracao], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Configuracoes  $configuracoes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Configuracao $configuracoes)
    {
        //
    }
}
