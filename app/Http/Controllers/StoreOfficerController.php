<?php

namespace App\Http\Controllers;

use App\StoreOfficer;
use Illuminate\Http\Request;
use App\Repositories\StoreOfficerRepository;

class StoreOfficerController extends Controller
{
    protected $storeOfficerRepo;

    public function __construct(StoreOfficerRepository $storeOfficerRepository){
        $this->storeOfficerRepo = $storeOfficerRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storeOfficer = $this->storeOfficerRepo->index();

        return view('admin.store_officers.index', compact('storeOfficer'));
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
     * @param  \App\StoreOfficer  $storeOfficer
     * @return \Illuminate\Http\Response
     */
    public function show(StoreOfficer $storeOfficer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StoreOfficer  $storeOfficer
     * @return \Illuminate\Http\Response
     */
    public function edit(StoreOfficer $storeOfficer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StoreOfficer  $storeOfficer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StoreOfficer $storeOfficer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StoreOfficer  $storeOfficer
     * @return \Illuminate\Http\Response
     */
    public function destroy(StoreOfficer $storeOfficer)
    {
        //
    }
}
