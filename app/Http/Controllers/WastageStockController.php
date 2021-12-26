<?php

namespace App\Http\Controllers;

use App\WastageStock;
use Illuminate\Http\Request;

class WastageStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WastageStock  $wastageStock
     * @return \Illuminate\Http\Response
     */
    public function show(WastageStock $wastageStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WastageStock  $wastageStock
     * @return \Illuminate\Http\Response
     */
    public function edit(WastageStock $wastageStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WastageStock  $wastageStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WastageStock $wastageStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WastageStock  $wastageStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(WastageStock $wastageStock)
    {
        //
    }

    public function report(){
        $wastage_stocks = WastageStock::paginate(10);
        return view('admin.reports.wastage_stock', compact('wastage_stocks'));
    }
}
