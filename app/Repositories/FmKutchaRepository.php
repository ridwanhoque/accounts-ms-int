<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\FmKutcha;

class FmKutchaRepository implements CrudInterface{
    public function index(){
        return FmKutcha::with('raw_material')->paginate(25);
    }

    public function create(){

    }

    public function store($request){
        return FmKutcha::create($request->all());
    }

    public function show($fm_kutcha){

    }

    public function edit($fm_kutcha){

    }

    public function update($request, $fm_kutcha){
        return $fm_kutcha->update($request->all());

    }

    public function destroy($fm_kutcha){
        return $fm_kutcha->delete();
    }
}