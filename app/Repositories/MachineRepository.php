<?php
namespace App\Repositories;

use App\Machine;

class MachineRepository{
    public function index(){
        return Machine::paginate(25);
    }

    public function create(){
        
    }

    public function store($request){
        return Machine::create($request->all());
    }

    public function show($machine){

    }

    public function edit($machine){

    }

    public function update($request, $machine){
        return $machine::findOrFail($machine->id)->update($request->all());
    }

    public function destroy($machine){
        return $machine->delete();
    }
}