<?php
namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\PettycashChart;

class PettycashChartRepository implements CrudInterface{
    public function index(){

    }

    public function create(){

    }

    public function store($request){
        return PettycashChart::create($request->all());

    }

    public function show($pettycash_chart){

    }

    public function edit($pettycash_chart){

    }

    public function update($request, $pettycash_chart){
        return $pettycash_chart->update($request->all());
    }

    public function destroy($pettycash_chart){
        return $pettycash_chart->delete();
    }

}