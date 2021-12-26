<?php

namespace App\Http\Controllers;

use App\Wastage;
use Illuminate\Http\Request;
use App\Repositories\WastageRepository;
use App\Http\WastageStoreRequest;
use App\Http\WastageUpdateRequest;
use Message;

class WastageController extends Controller
{
    protected $wastageRepo;

    public function __construct(WastageRepository $wastageRepository){
        $this->wastageRepo = $wastageRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wastages = Wastage::paginate(25);

        return view('admin.wastages.index', compact('wastages'));

     
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $wastage_types = config('app.wastage_types');
        return view('admin.wastages.create', compact('wastage_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, WastageStoreRequest $valid)
    {
        $valid->validated;

        $wastage = $this->wastageRepo->store($request);

        if($wastage){
            return \redirect('wastages')->with('message', Message::created('wastage'));
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function show(Wastage $wastage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function edit(Wastage $wastage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Wastage $wastage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Wastage  $wastage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Wastage $wastage)
    {
        //
    }
}
