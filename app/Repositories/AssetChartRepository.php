<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\AssetChart;

class AssetChartRepository implements CrudInterface{

    public function index(){}

    public function create(){}

    public function store($request){
        return AssetChart::create($request);
    }

    public function show($asset_chart){}

    public function edit($asset_chart){}

    public function update($request, $asset_chart)
    {
        return $asset_chart->update($request);
    }

    public function destroy($asset_chart)
    {   
       return $asset_chart->delete();
    }
}