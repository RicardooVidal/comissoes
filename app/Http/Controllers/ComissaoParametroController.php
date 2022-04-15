<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComissaoParametroRequest;
use App\Models\ComissaoParametro;
use App\Repositories\ComissaoParametroRepository;
use Illuminate\Http\Request;

class ComissaoParametroController extends Controller
{
    private $comissaoParametro;

    public function __construct(ComissaoParametro $comissaoParametro)
    {
        $this->comissaoParametro = $comissaoParametro;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $comissaoParametroRepository = new ComissaoParametroRepository($this->comissaoParametro);

        $comissaoParametroRepository->model = $comissaoParametroRepository->model->orderBy('id', 'desc');

        if($request->has('filter')) {
            $comissaoParametroRepository->filter($request->filter);
        }

        return $this->success(
            $comissaoParametroRepository->getResultPaginated(10)
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
     * @param  \App\Http\ComissaoParametroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ComissaoParametroRequest $request)
    {
        return $this->success($this->comissaoParametro->create($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->success($this->comissaoParametro->findOrFail($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ComissaoParametro  $comissaoParametro
     * @return \Illuminate\Http\Response
     */
    public function edit(ComissaoParametro $comissaoParametro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\ComissaoParametroRequest  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ComissaoParametroRequest $request, $id)
    {
        $comissaoParametro = $this->comissaoParametro->findOrFail($id);
        $comissaoParametro->fill($request->all());
        $comissaoParametro->save();
        return $this->success($comissaoParametro);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comissaoParametro = $this->comissaoParametro->findOrFail($id);
        return $this->success($comissaoParametro->delete());
    }
}
