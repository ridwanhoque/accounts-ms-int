<?php

namespace App\Http\Controllers;

use App\FmKutcha;
use Illuminate\Http\Request;
use App\Repositories\FmKutchaRepository;
use App\Http\Requests\FmKutchaStoreRequest;
use App\Http\Requests\FmKutchaUpdateRequest;
use App\RawMaterial;
use Message;

class FmKutchaController extends Controller
{
    protected $FmKutchaRepo;

    public function __construct(FmKutchaRepository $FmKutchaRepository){
        $this->FmKutchaRepo = $FmKutchaRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fm_kutchas = $this->FmKutchaRepo->index();

        return view('admin.fm_kutchas.index', compact('fm_kutchas'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raw_materials = RawMaterial::rawMaterials();
        return \view('admin.fm_kutchas.create', compact([
            'raw_materials'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, FmKutchaStoreRequest $valid)
    {
        $valid->validated();

        $fm_kutcha = $this->FmKutchaRepo->store($request);

        if($fm_kutcha){
            return \redirect('production_settings/fm_kutchas')->with('message', Message::created('fm_kutcha'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FmKutcha  $FmKutcha
     * @return \Illuminate\Http\Response
     */
    public function show(FmKutcha $FmKutcha)
    {
        return view('admin.fm_kutchas.show', compact('FmKutcha'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FmKutcha  $FmKutcha
     * @return \Illuminate\Http\Response
     */
    public function edit(FmKutcha $FmKutcha)
    {
        $raw_materials = RawMaterial::rawMaterials();
        return view('admin.fm_kutchas.edit', compact([
            'FmKutcha', 'raw_materials'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FmKutcha  $FmKutcha
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FmKutcha $FmKutcha, FmKutchaUpdateRequest $valid)
    {
        $valid->validated();

        $fm_kutcha = $this->FmKutchaRepo->update($request, $FmKutcha);

        if($fm_kutcha){
            return \redirect('production_settings/fm_kutchas')->with('message', Message::updated('fm_kutcha'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FmKutcha  $FmKutcha
     * @return \Illuminate\Http\Response
     */
    public function destroy(FmKutcha $FmKutcha)
    {
        $fm_kutcha = $this->FmKutchaRepo->destroy($FmKutcha);

        if($fm_kutcha){
            return \redirect('production_settings/fm_kutchas')->with('message', Message::deleted('fm_kutcha'));
        }
    }
}
