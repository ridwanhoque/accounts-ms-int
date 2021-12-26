<?php

namespace App\Http\Controllers;

use App\Machine;
use Illuminate\Http\Request;
use App\Repositories\MachineRepository;
use Message;
use App\Http\Requests\MachineStoreRequest;
use App\Http\Requests\MachineUpdateRequest;

class MachineController extends Controller
{
    private $machineRepo;

    public function __construct(MachineRepository $machineRepository){
        $this->machineRepo = $machineRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $machines = $this->machineRepo->index();

        return view('admin.machines.index', compact('machines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.machines.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, MachineStoreRequest $valid)
    {
        $valid->validated();
        $machine = $this->machineRepo->store($request);

        if($machine){
            return \redirect('production_settings/machines')->with(['message'=>Message::created('machine')]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function show(Machine $machine)
    {
        return view('admin.machines.show', compact('machine'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function edit(Machine $machine)
    {
        return view('admin.machines.edit', compact('machine'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Machine $machine, MachineUpdateRequest $valid)
    {
        $valid->validated();

        $machine = $this->machineRepo->update($request, $machine);

        if($machine){
            return \redirect('production_settings/machines')->with(['message' => Message::updated('machine')]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Machine  $machine
     * @return \Illuminate\Http\Response
     */
    public function destroy(Machine $machine)
    {
        $machine = $this->machineRepo->destroy($machine);

        if($machine){
            return redirect('production_settings/machines')->with(['message' => Message::deleted('machine')]);
        }
    }
}
