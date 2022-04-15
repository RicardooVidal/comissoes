<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComissaoRequest;
use App\Models\Comissao;
use App\Repositories\ComissaoRepository;
use Illuminate\Http\Request;

class ComissaoController extends Controller
{
    private $comissao;

    public function __construct(Comissao $comissao)
    {
        $this->comissao = $comissao;    
    }

    /**
     * Recuperar comissão de uma venda
     */
    public function index(Request $request)
    {
        $comissaoRepository = new ComissaoRepository($this->comissao);

        $comissaoRepository->model = $comissaoRepository->model
            ->with('revendedor')
            ->with('calculos')
            ->with('forma_pagamento')
            ->orderBy('id', 'DESC');

        if($request->has('filter')) {
            $comissaoRepository->filter($request->filter);
        }

        return $this->success(
            $comissaoRepository->getResultPaginated(10)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    public function baixar(ComissaoRequest $request)
    {
        $comissao = $this->comissao->find($request->id);

        $comissao->pago = true;
        $comissao->forma_pagamento_id = $request->forma_pagamento_id;
        $comissao->data_pagamento = date('Y-m-d');
        $comissao->save();

        return $this->success($comissao);
    }

    public function estornar(ComissaoRequest $request)
    {
        $comissao = $this->comissao->find($request->id);

        $comissao->pago = false;
        $comissao->forma_pagamento_id = null;
        $comissao->data_pagamento = null;
        $comissao->save();

        return $this->success($comissao);
    }


    /**
     * Recuperar comissão de uma venda
     */
    public function getComissoesByVenda(Request $request)
    {
        // Recuperar comissão por venda_lucro
        $comissao = Comissao::where('vendas_lucros_id', $request->venda_lucro_id)
            ->with('revendedor')->get();

        return $this->success($comissao);
    }

    /**
     * Recuperar comissões de um revendedor por id
     */
    public function getAllComissoesByRevendedor(Request $request)
    {
        $comissaoRepository = new ComissaoRepository($this->comissao);

        $comissaoRepository->model = $comissaoRepository->model
            ->where('revendedor_id', $request->revendedor_id)
            ->with('revendedor');

        if($request->has('filter')) {
            $comissaoRepository->filter($request->filter);
        }

        return $this->success(
            $comissaoRepository->getResultPaginated(5)
        );
    }
}
