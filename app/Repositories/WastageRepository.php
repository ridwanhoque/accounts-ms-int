<?php
namespace App\Repositories;

class WastageRepository{
    public function index(){

    }

    public function create(){

    }

    public function store($request){
        return Wastage::create($request->all());
    }

    public function show($wastage){

    }

    public function edit($wastage){

    }
    
    public function update($request, $wastage){

    }

    public function destroy($wastage){

    }

}