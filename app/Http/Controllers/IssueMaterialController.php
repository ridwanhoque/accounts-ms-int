<?php

namespace App\Http\Controllers;

use App\IssueMaterial;
use Illuminate\Http\Request;
use Message;

class IssueMaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $issue_materials = IssueMaterial::paginate(25);
        return view('admin.issue_materials.index', compact('issue_materials'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.issue_materials.create');
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
     * @param  \App\IssueMaterial  $issueMaterial
     * @return \Illuminate\Http\Response
     */
    public function show(IssueMaterial $issueMaterial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\IssueMaterial  $issueMaterial
     * @return \Illuminate\Http\Response
     */
    public function edit(IssueMaterial $issueMaterial)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\IssueMaterial  $issueMaterial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, IssueMaterial $issueMaterial)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\IssueMaterial  $issueMaterial
     * @return \Illuminate\Http\Response
     */
    public function destroy(IssueMaterial $issueMaterial)
    {
        //
    }
}
