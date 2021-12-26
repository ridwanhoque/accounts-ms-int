<?php
namespace App\Repositories\Color;

use App\Interfaces\CrudInterface;
use App\Color;

class ColorRepository implements CrudInterface{


    public function index(){
        return Color::paginate(25);
    }

    public function create(){
        
    }

    public function store($request){
        return Color::create($request->all());
    }

    public function show($color){
        
    }
    
    public function edit($color){
        
    }

    public function update($request, $color){
        return Color::findOrFail($color->id)->update($request->all());
    }

    public function destroy($color){
        return Color::destroy($color->id);
    }

}