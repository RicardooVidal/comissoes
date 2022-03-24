<?php

namespace App\Http\Controllers;

use App\Helpers\Document;
use App\Http\Requests\RevendedorRequest;
use App\Models\Configuracao;
use App\Models\Indicador;
use App\Models\Revendedor;
use App\Traits\ResponseTrait;

class RevendedorController extends Controller
{
    private $revendedor;
    public function __construct(Revendedor $revendedor)
    {
        $this->revendedor = $revendedor;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success(
            $this->revendedor
                ->with('indicador')
                ->paginate(10)
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
     * @param  \App\Http\Request\RevendedorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RevendedorRequest $request)
    {
        // Validar CPF
        if (!Document::validateCPF($request->cpf)) {
            return $this->error('CPF Inválido', 422);
        }

        $indicador_dados = [];

        //Verificar se possui indicador
        if ($request->indicador_id) {
            
            $indicador_id = $request->indicador_id;
            $revendedor = $this->revendedor->find($indicador_id);

            if (empty($revendedor)) {
                return $this->error('Indicador não existe', 404);
            }

            if (!$revendedor->ativo) {
                return $this->error('Revendedor indicador desativado');
            }

            $indicador_dados = $this->indicadorProcess($indicador_id);            
        }

        //Cadastrar o revendedor
        $params = [
            'cpf' => $request->get('cpf'),
            'nome' => $request->get('nome'),
            'email' => $request->get('email'),
            'celular' => $request->get('celular'),
            'ativo' => $request->get('ativo')
        ];

        $params = array_merge($params, $indicador_dados);
        $revendedor = $this->revendedor->create($params);

        return $this->success($revendedor, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success(
            $this->revendedor
                ->with('indicador')
                ->with('conta_pagamento')
                ->with('vendas')
                ->findOrFail($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Revendedor  $revendedor
     * @return \Illuminate\Http\Response
     */
    public function edit(Revendedor $revendedor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Request\RevendedorRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RevendedorRequest $request, $id)
    {
        // Validar CPF
        if (!Document::validateCPF($request->cpf)) {
            return $this->error('CPF Inválido', 422);
        }

        //Verificar se tentou alterar o indicador
        if ($request->indicador_id) {
            return $this->error('Não é possível alterar o indicador');
        }

        //Verificar se tentou alterar o id das informações de pagamento
        if ($request->conta_pagamento_id) {
            return $this->error('Não é possível alterar o id das informações de pagamento');
        }

        $revendedor = $this->revendedor->find($id);

        if (empty($revendedor)) {
            return $this->error('Revendedor não existe', 404);
        }

        $revendedor->fill($request->all());

        $revendedor->save();
        
        return $this->success($revendedor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $revendedor = $this->revendedor->find($id);
        
        if (empty($revendedor)) {
            return $this->error('Revendedor não existe', 404);
        }

        //Verificar se revendedor possui indicadores
        $indicador = Indicador::where('revendedor_id', $id)->get()->first();
        
        if (!empty($indicador)) {
            $indicados = Revendedor::where('indicador_id', $indicador->id)->get()->first();
            if (!empty($indicados)) {
                return $this->error('Não foi possível deletar o revendedor por que este possui outros revendedores indicados');
            }
        }
        
        return $this->success($revendedor->delete());
    }

    /**
     * Processo para inclusão de indicador
     *
     * @param int $id
     * @return array
     */
    public function indicadorProcess($indicador_id)
    {
        $indicador = Indicador::where('revendedor_id', $indicador_id)->get()->first();

        //Se vazio, cadastrar
        if (empty($indicador)) {
            $indicador = Indicador::create(['revendedor_id' => $indicador_id]);
        }

        if ($indicador->id) {
            //Recuperar dias válidos na configuração
            $dataIndicacao = date('Y-m-d');
            $diasValido = Indicador::getDiasIndicacaoValido();

            return [
                'indicador_id' => $indicador->id,
                'data_indicacao' => $dataIndicacao,
                'validade_indicacao' => $diasValido
            ];
        }

        return [];
    }
}
