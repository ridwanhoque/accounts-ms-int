<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Branch;

class BranchRepository implements CrudInterface{

    public function index(){
        return Branch::paginate(25);
    }

    public function create(){

    }

    public function store($request){
        return Branch::create($request->all());
    }

    public function show($branch){

    }

    public function edit($branch){

    }

    public function update($request, $branch){
        return Branch::findOrFail($branch->id)->update($request->all());
    }

    public function destroy($branch){
        return $branch->delete();
    }

}