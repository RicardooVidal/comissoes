<?php

namespace App\Http\Controllers;

use App\Http\Requests\BancoRequest;
use App\Models\Banco;
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
        $bancos = Banco::all();
        return $this->success($bancos);
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
        $banco = $this->banco->findOrFail($id);
        return $this->success($banco->delete());
    }
}
