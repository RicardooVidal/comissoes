<?php

namespace App\Http\Controllers;

use App\Domains\Services\VendaService;
use App\Http\Requests\VendaRequest;
use App\Models\Comissao;
use App\Models\Revendedor;
use App\Models\Venda;
use App\Repositories\VendaFactory;
use Illuminate\Http\Request;

class VendaController extends Controller
{
    private $venda;

    public function __construct(Venda $venda)
    {
        $this->venda = $venda;    
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $vendaRepository = new VendaFactory($this->venda);

        $vendaRepository->model = $vendaRepository->model
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
            ->orderBy('id', 'desc');

        if($request->has('filter')) {
            $vendaRepository->filter($request->filter);
        }

        return $this->success(
            $vendaRepository->getResultPaginated(10)
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
     * @param  \App\Http\Requests\VendaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VendaRequest $request)
    {
        // Parâmetros
        $params = [
            'revendedor_id' => $request->revendedor_id,
            'taxa_parametro_id' => $request->taxa_parametro_id,
            'comissao_parametro_id' => $request->comissao_parametro_id,
            'outras_despesas_valor' => $request->outras_despesas_valor,
            'outras_despesas_descricao' => $request->outras_despesas_descricao,
            'descricao' => $request->descricao,
            'preco_unitario' => $request->preco_unitario,
            'quantidade' => $request->quantidade,
            'preco_total' => $request->preco_unitario * $request->quantidade,
            'link_venda' => $request->link_venda,
            'data_venda' => date('Y-m-d')
        ];

        if ($request->quantidade == 0) {
            return $this->error('Quantidade não informada'. 422);
        }

        if ($request->preco_unitario == 0) {
            return $this->error('Preço não informado'. 422);
        }

        $revendedor = Revendedor::find($params['revendedor_id']);

        if ($revendedor->ativo === false) {
            return $this->error('Revendedor desativado');
        }

        //Chamar venda service
        $venda = (new VendaService($params))->run();
        return $this->success($venda, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success($this->venda
            ->with([
                'revendedor',
                'taxa_parametro',
                'comissao_paramero'
            ])
            ->findOrFail($id)
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Venda  $venda
     * @return \Illuminate\Http\Response
     */
    public function edit(Venda $venda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->error('Não é possível editar uma venda');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $venda = $this->venda->findOrFail($id);

        $comissoes = Comissao::where('venda_id', $venda->id)->get();

        foreach($comissoes as $comissao) {
            $comissao_id = $comissao->id;
            if ($comissao->pago == true) {
                return $this->error("Não é possível deletar a venda. A comissão nº $comissao_id está marcado como PAGO");
            }
        }
        return $this->success($venda->delete());
    }
}
