<?php

namespace App\Http\Controllers;

use App\AccountInformation;
use App\RawMaterial;
use Illuminate\Http\Request;
use App\Repositories\RawMaterialRepository;
use App\Http\Requests\RMaterialStoreRequest;
use App\Http\Requests\RMaterialUpdateRequest;
use Message;

class RawMaterialController extends Controller
{

    protected $rmRepo;

    public function __construct(RawMaterialRepository $rmRepo){
        $this->rmRepo = $rmRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $raw_materials = $this->rmRepo->index();
        return view('admin.raw_materials.index', compact('raw_materials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.raw_materials.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, RMaterialStoreRequest $rmRequest)
    {   
        $validated = $rmRequest->validated();
        $rmStore = $this->rmRepo->store($request);

        if($rmStore){
            return redirect('production_settings/raw_materials')->with(['message' => Message::created('raw_material')]);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */
    public function show(RawMaterial $rawMaterial)
    {
        return view('admin.raw_materials.show', compact('rawMaterial'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */
    public function edit(RawMaterial $rawMaterial)
    {
        return view('admin.raw_materials.edit', compact('rawMaterial'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RawMaterial $rawMaterial)
    {
        $rawMaterial = $this->rmRepo->update($request, $rawMaterial);

        if($rawMaterial){
            return \redirect('production_settings/raw_materials')->with(['message' => Message::updated('raw_material')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RawMaterial  $rawMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(RawMaterial $rawMaterial)
    {
        $rawMaterial = $this->rmRepo->destroy($rawMaterial);
        
        if($rawMaterial){
            return \redirect('production_settings/raw_materials')->with(['message' => Message::deleted('raw_material')]);
        }
    }
}
