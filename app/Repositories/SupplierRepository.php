<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Supplier;

class SupplierRepository implements CrudInterface{

    public function index(){
        return Supplier::paginate(25);
    }

    public function create(){

    }

    public function store($request){
        return Supplier::create($request->all());
    }

    public function show($supplier){

    }

    public function edit($supplier){

    }

    public function update($request, $supplier){
        return $supplier->update($request->all());
    }

    public function destroy($supplier){
        return $supplier->delete();
    }

}