<?php

namespace App\Http\Controllers;

use App\Color;
use Illuminate\Http\Request;
use App\Repositories\Color\ColorRepository;
use App\Http\Requests\ColorStoreRequest;
use App\Http\Requests\ColorUpdateRequest;
use Message;

class ColorController extends Controller
{
    protected $color;

    public function __construct(ColorRepository $color){
        $this->color = $color;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $colors = $this->color->index();
        return view('admin.colors.index', compact('colors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.colors.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ColorStoreRequest $valid)
    {
        $validated = $valid->validated(); 
        $color = $this->color->store($request);
        if($color && $validated){
            return redirect('production_settings/colors')->with(['message' => Message::created('color')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function show(Color $color)
    {
        return view('admin.colors.show', compact('color'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Color $color, ColorUpdateRequest $valid)
    {
        $validated = $valid->validated();
        $color = $this->color->update($request, $color);
        
        if($color && $validated){
            return \redirect('production_settings/colors')->with(['message' => Message::updated('color')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Color  $color
     * @return \Illuminate\Http\Response
     */
    public function destroy(Color $color)
    {
        $color_delete = $this->color->destroy($color);
        if($color_delete){
            return redirect('production_settings/colors')->with(['message' => Message::deleted('color')]);
        }
    }
}
