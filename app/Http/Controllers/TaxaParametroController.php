<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaxaParametroRequest;
use App\Models\TaxaParametro;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class TaxaParametroController extends Controller
{
    private $taxaParametro;

    public function __construct(TaxaParametro $taxaParametro)
    {
        $this->taxaParametro = $taxaParametro;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success($this->taxaParametro->paginate(10));
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
     * @param  \App\Http\TaxaParametroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaxaParametroRequest $request)
    {
        $taxaParametro = $this->taxaParametro->create($request->all());
        return $this->success($taxaParametro, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $taxaParametro = $this->taxaParametro->find($id);
        return $this->success($taxaParametro);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaxaParametro  $taxaParametro
     * @return \Illuminate\Http\Response
     */
    public function edit(TaxaParametro $taxaParametro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\TaxaParametroRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(TaxaParametroRequest $request, $id)
    {
        $taxaParametro = $this->taxaParametro->findOrFail($id);
        $taxaParametro->fill($request->all());
        $taxaParametro->save();

        return $this->success($taxaParametro);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $taxaParametro = $this->taxaParametro->findOrFail($id);
        return $this->success($taxaParametro->delete());
    }
}
