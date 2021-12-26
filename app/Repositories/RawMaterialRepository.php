<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\RawMaterial;

class RawMaterialRepository implements CrudInterface{


    public function index(){
        return RawMaterial::paginate(25);
    }

    public function create(){
        
    }

    public function store($request){
        return RawMaterial::create($request->all());
    }

    public function show($raw_material){
        
    }
    
    public function edit($raw_material){
        
    }

    public function update($request, $raw_material){
        return RawMaterial::findOrFail($raw_material->id)->update($request->all());
    }

    public function destroy($raw_material){
        return RawMaterial::destroy($raw_material->id);
    }

}