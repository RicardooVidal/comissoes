<?php

namespace App\Http\Controllers;

use App\Models\Indicador;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class IndicadorController extends Controller
{
    private $indicador;
    
    public function __construct(Indicador $indicador)
    {
        $this->indicador = $indicador;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success($this->indicador->with('revendedores')->get());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->success($this->indicador->create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $indicador = $this->indicador->with('revendedores')->findOrFail($id);
        return $this->success($indicador);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function edit(Indicador $indicador)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Indicador  $indicador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Indicador $indicador)
    {
        return $this->error('Não é possível editar um indicador');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $indicador = $this->indicador->findOrFail($id);
        return $this->success($indicador->delete());
    }
}
