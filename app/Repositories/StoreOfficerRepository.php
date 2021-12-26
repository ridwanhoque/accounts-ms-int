<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\StoreOfficer;

class StoreOfficerRepository implements CrudInterface{

    public function index(){
        return StoreOfficer::paginate(25);
    }

    public function create(){

    }

    public function store($request){
        return StoreOfficer::create($request->all());
    }

    public function show($storeOfficer){

    }

    public function edit($storeOfficer){

    }

    public function update($request, $storeOfficer){
        return $storeOfficer::update($request->all());
    }

    public function destroy($storeOfficer){
        return $storeOfficer->delete();
    }

}