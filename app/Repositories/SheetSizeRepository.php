<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\SheetSize;

class SheetSizeRepository implements CrudInterface{
    public function index(){
        return SheetSize::paginate(25);
    }

    public function create(){

    }

    public function store($request){
        return SheetSize::create($request->all());
    }

    public function show($sheet_size){

    }

    public function edit($sheet_size){

    }

    public function update($request, $sheet_size){
        return $sheet_size->update($request->all());

    }

    public function destroy($sheet_size){
        return $sheet_size->delete();
    }
}