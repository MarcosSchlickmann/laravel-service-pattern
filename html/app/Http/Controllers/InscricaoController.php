<?php

namespace App\Http\Controllers;

use App\Core\CoreController;
use App\Renault\Requests\InscricaoRequest;
use App\Renault\Services\InscricaoService;

class InscricaoController extends CoreController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $inscricoes = InscricaoService::all();
        return $this->api->responseOk($inscricoes);
    }

    public function show($id)
    {
        $inscricao = InscricaoService::specific($id);
        return $this->api->responseOk($inscricao);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InscricaoRequest $request)
    {
        $inscricao = InscricaoService::create($request->all());
        return $this->api->responseOk($inscricao);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(InscricaoRequest $request, $id)
    {
        $inscricao = InscricaoService::edit($request->all());
        return $this->api->responseOk($inscricao);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd('remover');
    }
}
