<?php

namespace App\Http\Controllers;

use App\ProductionUnit;
use Illuminate\Http\Request;
use Message;

class ProductionUnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $production_units = ProductionUnit::paginate(25);
        return view('admin.production_units.index', compact('production_units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.production_units.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductionUnit  $productionUnit
     * @return \Illuminate\Http\Response
     */
    public function show(ProductionUnit $productionUnit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ProductionUnit  $productionUnit
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductionUnit $productionUnit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductionUnit  $productionUnit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductionUnit $productionUnit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductionUnit  $productionUnit
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductionUnit $productionUnit)
    {
        //
    }
}
