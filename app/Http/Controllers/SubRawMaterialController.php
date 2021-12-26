<?php

namespace App\Http\Controllers;

use App\SubRawMaterial;
use Illuminate\Http\Request;
use App\RawMaterial;
use Auth;
use Message;

class SubRawMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $currentUser = Auth::user();
        $sub_raw_materials = SubRawMaterial::with('raw_material')
                                ->where('company_id', $currentUser->company_id)->paginate(25);

        return view('admin.sub_raw_materials.index', compact([
            'sub_raw_materials'
        ]));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raw_materials = RawMaterial::rawMaterials();
        return view('admin.sub_raw_materials.create', compact([
            'raw_materials'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $sub_raw_materials = SubRawMaterial::create($request->all());

        if($sub_raw_materials){
            return redirect('production_settings/sub_raw_materials')->with('message', Message::created('sub_raw_material'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SubRawMaterial  $subRawMaterial
     * @return \Illuminate\Http\Response
     */
    public function show(SubRawMaterial $subRawMaterial)
    {
        return view('admin.sub_raw_materials.show', compact([
            'subRawMaterial'
        ]));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SubRawMaterial  $subRawMaterial
     * @return \Illuminate\Http\Response
     */
    public function edit(SubRawMaterial $subRawMaterial)
    {
        $raw_materials = RawMaterial::RawMaterials();
        return \view('admin.sub_raw_materials.edit', compact([
            'subRawMaterial', 'raw_materials'
        ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SubRawMaterial  $subRawMaterial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SubRawMaterial $subRawMaterial)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $sub_raw_material = $subRawMaterial->update($request->all());

        if($sub_raw_material){
            return \redirect('production_settings/sub_raw_materials')->with('message', Message::updated('sub_raw_material'));
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SubRawMaterial  $subRawMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(SubRawMaterial $subRawMaterial)
    {
        $sub_raw_material = $subRawMaterial->delete();

        if($sub_raw_material){
            return \redirect('production_settings/sub_raw_materials')->with('message', Message::deleted('sub_raw_material'));
        }
    }
}
