<?php

namespace App\Repositories;

use App\Interfaces\CrudInterface;
use App\TemporaryDirectProduction;
use App\TemporaryDirectProductionDetails;

class TemporaryDirectProductionRepository implements CrudInterface{
    
    public function index()
    {
        
    }

    public function create()
    {
        
    }

    public function temp_direct_production_save($request){
        $direct_production = TemporaryDirectProduction::create([
            'direct_production_date' => $request->direct_production_date,
            'total_input' => $request->total_input,
            'total_todays_weight' => $request->total_todays_weight,
        ]);

        return $direct_production;
    }
    
    public function temp_material_save($request, $key, $direct_production){
        $direct_production->temporary_direct_production_details()->create([
            'production_type' => 1,
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
            'batch_id' => $request->batch_id[$key],
            'qty_kgs' => $request->qty_kgs[$key]
        ]);

        return true;

    }

    public function temp_assign_kutcha_save($request, $key, $direct_production){
        $direct_production->temporary_direct_production_details()->create([
            'production_type' => 2,
            'fm_kutcha_id' => $request->fm_kutcha_in_ids[$key],
            'qty_kgs' => $request->fm_kutcha_in_kgs[$key]
        ]);

        return true;
    }

    public function temp_product_save($request, $key, $direct_production){
        $direct_production->temporary_direct_production_details()->create([
            'production_type' => 3,
            'product_id' => $request->product_ids[$key],
            'machine_id' => $request->machine_id[$key],
            'todays_weight' => $request->todays_weight[$key],
            'finish_quantity' => $request->finish_quantity[$key],
            'pack' => $request->pack[$key],
            'net_weight' => $request->net_weight[$key],
            'fm_kutcha_id' => $request->fm_kutcha_ids[$key],
            'qty_kgs' => $request->kutcha_qty[$key]
        ]);

        return true;
    }   


    public function store($request)
    {
        $direct_production = $this->temp_direct_production_save($request);

        foreach ($request->sub_raw_material_ids as $srm_key => $sub_raw_material) {

            if ($sub_raw_material > 0 && $request->qty_kgs[$srm_key] > 0) {
                $this->temp_material_save($request, $srm_key, $direct_production);
            }
        }

        foreach ($request->fm_kutcha_in_ids as $fk_in_key => $fm_kutcha_in_id) {
            if ($fm_kutcha_in_id > 0 && $request->fm_kutcha_in_kgs[$fk_in_key] > 0) {
                $this->temp_assign_kutcha_save($request, $fk_in_key, $direct_production);
            }
        }
        

        foreach ($request->product_ids as $dp_key => $product_id) {
            $this->temp_product_save($request, $dp_key, $direct_production);
        }

        return true;
    }

    public function show($model)
    {
        
    }

    public function edit($model)
    {
        
    }

    public function update($request, $model)
    {
        
    }

    public function destroy($id)
    {
        $temporary_direct_production = TemporaryDirectProduction::find($id);
        $temporary_direct_production->temporary_direct_production_details->each->delete();
        $temporary_direct_production->delete();

        return true;
    }

}