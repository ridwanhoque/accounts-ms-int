<?php

namespace App\Http\Controllers;

use App\SheetSize;
use Illuminate\Http\Request;
use App\Repositories\SheetSizeRepository;
use App\Http\Requests\SheetSizeStoreRequest;
use App\Http\Requests\SheetSizeUpdateRequest;
use App\RawMaterial;
use Message;

class SheetSizeController extends Controller
{
    protected $sheetSizeRepo;

    public function __construct(SheetSizeRepository $sheetSizeRepository){
        $this->sheetSizeRepo = $sheetSizeRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sheet_sizes = $this->sheetSizeRepo->index();

        return view('admin.sheet_sizes.index', compact('sheet_sizes'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $raw_materials = RawMaterial::rawMaterials();
        return \view('admin.sheet_sizes.create', compact([
            'raw_materials'
        ]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, SheetSizeStoreRequest $valid)
    {
        $valid->validated();

        $sheet_size = $this->sheetSizeRepo->store($request);

        if($sheet_size){
            return \redirect('production_settings/sheet_sizes')->with('message', Message::created('sheet_size'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SheetSize  $sheetSize
     * @return \Illuminate\Http\Response
     */
    public function show(SheetSize $sheetSize)
    {
        return view('admin.sheet_sizes.show', compact('sheetSize'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SheetSize  $sheetSize
     * @return \Illuminate\Http\Response
     */
    public function edit(SheetSize $sheetSize)
    {
        $raw_materials = RawMaterial::rawMaterials();
        return view('admin.sheet_sizes.edit', compact([
            'sheetSize', 'raw_materials'
            ]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SheetSize  $sheetSize
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SheetSize $sheetSize, SheetSizeUpdateRequest $valid)
    {
        $valid->validated();

        $sheet_size = $this->sheetSizeRepo->update($request, $sheetSize);

        if($sheet_size){
            return \redirect('production_settings/sheet_sizes')->with('message', Message::updated('sheet_size'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SheetSize  $sheetSize
     * @return \Illuminate\Http\Response
     */
    public function destroy(SheetSize $sheetSize)
    {
        $sheet_size = $this->sheetSizeRepo->destroy($sheetSize);

        if($sheet_size){
            return \redirect('production_settings/sheet_sizes')->with('message', Message::deleted('sheet_size'));
        }
    }
}
