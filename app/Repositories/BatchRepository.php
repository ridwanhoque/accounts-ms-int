<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\Batch;
use DB;

class BatchRepository implements CrudInterface{

    public function index(){
        return Batch::orderByDesc('id')->paginate(25);
    }

    public function create(){

    }

    public function batch_save($request, $name){
        $batch = Batch::create([
            'name' => $name,
            'description' => $request->description
        ]);

        return $batch;
    }

    public function store($request){
        $batch_exist = Batch::where('name', 'REGEXP', $request->batch_prefix.'[0-9]');
        $j = 0;
        if($batch_exist->count() > 0){
            $j += $batch_exist->count();
        }        

        for($i = 1; $i<= $request->total_batches; $i++) {
            $name = $request->batch_prefix.($j+$i);
            
            $this->batch_save($request, $name);
        }

        return true;
    }

    public function show($batch){

    }

    public function edit($batch){

    }

    public function update($request, $batch){
        return Batch::findOrFail($batch->id)->update($request->all());
    }

    public function destroy($batch){
        return $batch->delete();
    }

}