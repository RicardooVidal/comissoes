<?php

namespace App\Http\Controllers;

use App\Helpers\Document;
use App\Http\Requests\RevendedorRequest;
use App\Models\ContaPagamento;
use App\Models\Indicador;
use App\Models\Revendedor;
use App\Repositories\RevendedorRepository;
use Illuminate\Http\Request;

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
    public function index(Request $request)
    {
        $revendedorRepository = new RevendedorRepository($this->revendedor);

        $revendedorRepository->model = $revendedorRepository->model
            ->orderBy('nome')
            ->with(['indicador' => function($q) {
                $q->select(['indicadores.id', 'revendedor_id', 'nome'])
                    ->join('revendedores', 'indicadores.revendedor_id', '=', 'revendedores.id');
                }])
            ->with('conta_pagamento');

        if($request->has('filter')) {
            $revendedorRepository->filter($request->filter);
        }

        return $this->success(
            $revendedorRepository->getResultPaginated(10)
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
        if (!Document::validateCPF($request->id)) {
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

        // Parâmetros do revendedor
        $params = [
            'id' => $request->get('id'),
            'rg' => $request->get('rg'),
            'nome' => $request->get('nome'),
            'email' => $request->get('email'),
            'celular' => $request->get('celular'),
            'ativo' => $request->get('ativo')
        ];

        // Cadastrada o revendedor
        $params = array_merge($params, $indicador_dados);
        $revendedor = $this->revendedor->create($params);

        if ($revendedor) {
            // Parâmetros da conta
            $paramsConta = [
                'revendedor_id' => $revendedor->id,
                'banco_id' => $request->get('banco_id'),
                'agencia' => $request->get('agencia'),
                'conta' => $request->get('conta'),
                'digito_conta' => $request->get('digito_conta'),
                'tipo' => $request->get('tipo'),
                'pix' => $request->get('pix')
            ];

            // Criar conta
            $contaPagamento = ContaPagamento::create($paramsConta);

            //Cadastrar id da conta de pagamento no revendedor
            $this->revendedor->where('id', $revendedor->id,)
                ->update(['conta_pagamento_id' => $contaPagamento->id]);
        }

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
        if ($request->id) {
            if (!Document::validateCPF($request->id)) {
                return $this->error('CPF Inválido', 422);
            }
        }

        //Verificar se tentou alterar o indicador
        if ($request->indicador_id) {
            return $this->error('Não é possível alterar o indicador');
        }

        //Verificar se tentou alterar o id das informações de pagamento
        if ($request->conta_pagamento_id) {
            return $this->error('Não é possível alterar o id das informações de pagamento');
        }

        $revendedor = $this->revendedor->findOrFail($id);

        if (empty($revendedor)) {
            return $this->error('Revendedor não existe', 404);
        }

        // Parâmetros do revendedor
        $params = [
            'nome' => $request->get('nome'),
            'email' => $request->get('email'),
            'celular' => $request->get('celular'),
            'ativo' => $request->get('ativo')
        ];

        // Alterar o revendedor
        $revendedor->fill($params);
        
        if ($revendedor->save()) {
            $paramsConta = [
                'banco_id' => $request->get('banco_id'),
                'agencia' => $request->get('agencia'),
                'conta' => $request->get('conta'),
                'digito_conta' => $request->get('digito_conta'),
                'tipo' => $request->get('tipo'),
                'pix' => $request->get('pix')
            ];
            
            ContaPagamento::where('revendedor_id', $id)
                ->update($paramsConta);

            return $this->success($revendedor);
        }

        return $this->error('Não foi possível atualizar o revendedor.');
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

    /**
     * Recuperar revendedores indicados por id
     */
    public function getRevendedoresIndicados(Request $request, $revendedor_id)
    {
        // Recuperar o id de indicador do revendedor
        $indicador = Indicador::where('revendedor_id', $revendedor_id)->first();

        if (empty($indicador)) {
            return $this->error('Revendedor não possui indicados');
        }

        // Recuperar revendedores indicados
        $revendedorRepository = new RevendedorRepository($this->revendedor);
        $revendedorRepository->model = $revendedorRepository->model->where('indicador_id', $indicador->id);
        
        if ($revendedorRepository->model->get()->isEmpty()) {
            return $this->error('Revendedor não possui indicados');
        }

        if($request->has('filter')) {
            $revendedorRepository->filter($request->filter);
        }

        return $this->success(
            $revendedorRepository->getResultPaginated(5)
        );
    }

    /**
     * Retorna somente os dados bancários do revendedor
     */
    public function getDadosBancarios($revendedor_id)
    {
        $dados_bancarios = ContaPagamento::where('revendedor_id', $revendedor_id)
            ->select([
                'bancos.id AS banco_id',
                'bancos.descricao AS banco_nome',
                'agencia',
                'conta',
                'digito_conta',
                'tipo',
                'pix'
            ])
            ->join('bancos', 'contas_pagamentos.banco_id', '=', 'bancos.id')
            ->get();
        
        return $this->success($dados_bancarios);
    }
}
