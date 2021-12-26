<?php

namespace App\Http\Controllers;

use App\Branch;
use Illuminate\Http\Request;
use App\Http\Requests\BranchStoreRequest;
use App\Http\Requests\BranchUpdateRequest;
use App\Repositories\BranchRepository;

use Message;

class BranchController extends Controller
{
    protected $branchRepo;


    public function __construct(BranchRepository $branchRepository){
        $this->branchRepo = $branchRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = $this->branchRepo->index();

        return view('admin.branches.index', compact(['branches']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, BranchStoreRequest $valid)
    {
        $valid->validated();

        $branch = $this->branchRepo->store($request);

        if($branch){
            return \redirect('production_settings/branches')->with(['message' => Message::created('branch')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        return view('admin.branches.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch, BranchUpdateRequest $valid)
    {
        $valid->validated();

        $branch = $this->branchRepo->update($request, $branch);

        if($branch){
            return \redirect('production_settings/branches')->with(['message' => Message::updated('branch')]);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        $branch = $this->branchRepo->destroy($branch);

        if($branch){
            return \redirect('production_settings/branches')->with(['message' => Message::deleted('branch')]);
        }
    }
}
