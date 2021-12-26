<?php

namespace App\Http\Controllers;

use App\Batch;
use Illuminate\Http\Request;
use App\Http\Requests\BatchStoreRequest;
use App\Http\Requests\BatchUpdateRequest;
use App\Repositories\BatchRepository;

use Message;

class BatchController extends Controller
{

    protected $batchRepo;


    public function __construct(BatchRepository $batchRepository){
        $this->batchRepo = $batchRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batches = $this->batchRepo->index();

        return view('admin.batches.index', compact(['batches']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.batches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BatchStoreRequest $valid)
    {
        $valid->validated();

        $batch = $this->batchRepo->store($request);

        if($batch){
            return \redirect('production_settings/batches')->with(['message' => Message::created('batch')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function show(Batch $batch)
    {
        return view('admin.batches.show', compact('batch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function edit(Batch $batch)
    {
        return view('admin.batches.edit', compact('batch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Batch $batch, BatchUpdateRequest $valid)
    {
        $valid->validated();

        $batch = $this->batchRepo->update($request, $batch);

        if($batch){
            return \redirect('production_settings/batches')->with(['message' => Message::updated('batch')]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Batch  $batch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Batch $batch)
    {
        $batch = $this->batchRepo->destroy($batch);

        if($batch){
            return \redirect('production_settings/batches')->with(['message' => Message::deleted('batch')]);
        }
    }
}
