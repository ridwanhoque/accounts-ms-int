<?php

namespace App\Repositories;

use App\DirectProduction;
use App\Interfaces\CrudInterface;
use App\RawMaterialStock;
use App\ProductStock;
use App\KutchaWastageStock;
use App\RawMaterialBatchStock;
use App\TemporaryDirectProduction;
use DB;

class DirectProductionRepository implements CrudInterface
{
    public function index()
    { }

    public function create()
    { }

    public function store($request)
    {

// dd($request->all());
        $direct_production = $this->save_direct_production($request);

        foreach ($request->product_ids as $dp_key => $product_id) {
            $direct_production_details = $this->save_direct_production_details($request, $dp_key, $direct_production);

            $product_stock = $this->save_product_stock($request, $dp_key, $product_id);

            foreach ($request->fm_kutcha_ids as $fk_key => $fm_kutcha_id) {
                if ($fm_kutcha_id > 0 && $request->kutcha_qty[$fk_key] > 0) {
                    $this->save_kutcha_wastage_stock_add($request, $fk_key);
                }
            }
        }

        foreach ($request->sub_raw_material_ids as $srm_key => $sub_raw_material) {

            if ($sub_raw_material > 0 && $request->qty_kgs[$srm_key] > 0) {
                $direct_production_sheet = $this->save_direct_production_sheet($request, $srm_key, $direct_production);

                $raw_material_stock = $this->deduct_from_raw_material_stock($request, $srm_key);

                //deduct from raw material stock batches
                $raw_material_stock = $this->deduct_from_raw_material_batch_stock($request, $srm_key);
            }
        }

        if (!empty($request->fm_kutcha_in_ids)) {
            foreach ($request->fm_kutcha_in_ids as $fk_in_key => $fm_kutcha_in_id) {
                if ($fm_kutcha_in_id > 0 && $request->fm_kutcha_in_kgs[$fk_in_key] > 0) {
                    $fm_kutcha_in = $this->save_fm_kutcha_in($request, $fk_in_key, $direct_production);
                    $this->save_kutcha_wastage_stock_deduct($request, $fk_in_key);
                }
            }
        }

        $temporary_direct_production = TemporaryDirectProduction::find($request->temporary_direct_production_id);
        $temporary_direct_production->temporary_direct_production_details->each->delete();
        $temporary_direct_production->delete();


        return true;
    }

    public function save_kutcha_wastage_stock_deduct($request, $key)
    {
        //store into kutcha_wastage stocks table
        $kutcha_wastage_stock_exist = KutchaWastageStock::where([
            'fm_kutcha_id' => $request->fm_kutcha_in_ids[$key],
        ]);
        if ($kutcha_wastage_stock_exist->count() > 0) {
            $kutcha_wastage_stock = $kutcha_wastage_stock_exist->first();
            $kutcha_wastage_stock->used_kg += $request->fm_kutcha_in_kgs[$key];
            $kutcha_wastage_stock->available_kg -= $request->fm_kutcha_in_kgs[$key];
            $kutcha_wastage_stock->save();
        }

        return $kutcha_wastage_stock;
    }

    public function save_kutcha_wastage_stock_add($request, $key)
    {
        $kutcha_wastage_stock_exists = KutchaWastageStock::where('fm_kutcha_id', $request->fm_kutcha_ids[$key]);
        if ($kutcha_wastage_stock_exists->count() > 0) {
            $kutcha_wastage_stock = $kutcha_wastage_stock_exists->first();
            $kutcha_wastage_stock->total_kg += $request->kutcha_qty[$key];
            $kutcha_wastage_stock->available_kg += $request->kutcha_qty[$key];
            $kutcha_wastage_stock->save();
        } else {
            $kutcha_wastage_stock = KutchaWastageStock::create([
                'fm_kutcha_id' => $request->fm_kutcha_ids[$key],
                'total_kg' => $request->kutcha_qty[$key],
                'available_kg' => $request->kutcha_qty[$key],
            ]);
        }

        return $kutcha_wastage_stock;
    }

    public function save_product_stock($request, $key, $product_id)
    {
        $product_stock_exist = ProductStock::where('product_id', $product_id);
        if ($product_stock_exist->count() > 0) {
            $product_stock = $product_stock_exist->first();
        } else {
            $product_stock = new ProductStock;
            $product_stock->product_id = $product_id;
        }
        $product_stock->produced_quantity += $request->finish_quantity[$key];
        $product_stock->available_quantity += $request->finish_quantity[$key];
        $product_stock->produced_pack += $request->pack[$key];
        $product_stock->available_pack += $request->pack[$key];
        $product_stock->produced_weight += $request->net_weight[$key];
        $product_stock->available_weight += $request->net_weight[$key];
        $product_stock->save();

        return $product_stock;
    }

    public function deduct_from_raw_material_stock($request, $key)
    {
        $raw_material_stock_exist = RawMaterialStock::where([
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
        ]);
        if ($raw_material_stock_exist->count() > 0) {
            $raw_material_stock = $raw_material_stock_exist->first();
            $raw_material_stock->used_quantity += $request->qty_kgs[$key];
            $raw_material_stock->available_quantity -= $request->qty_kgs[$key];
            if($request->batch_id[$key] == ''){
                $raw_material_stock->used_opening_quantity += $request->qty_kgs[$key];
                $raw_material_stock->available_opening_quantity -= $request->qty_kgs[$key];        
            }
            $raw_material_stock->save();
        }

        return $raw_material_stock;
    }

    public function deduct_from_raw_material_batch_stock($request, $key)
    {
        $raw_material_batch_stock_exist = RawMaterialBatchStock::where([
            'sub_raw_material_id' => $request->sub_raw_material_ids[$key],
            'batch_id' => $request->batch_id[$key]
        ]);
        if ($raw_material_batch_stock_exist->count() > 0) {
            $raw_material_batch_stock = $raw_material_batch_stock_exist->first();
            $raw_material_batch_stock->used_quantity += $request->qty_kgs[$key];
            $raw_material_batch_stock->available_quantity -= $request->qty_kgs[$key];
            $raw_material_batch_stock->save();
        }

        return 1;
    }

    public function save_direct_production($request)
    {
        $direct_production = DirectProduction::create([
            'direct_production_date' => $request->direct_production_date,
            'total_input' => $request->total_input,
            'total_todays_weight' => $request->total_todays_weight,
        ]);

        return $direct_production;
    }

    public function save_direct_production_sheet($request, $key, $direct_production)
    {
        $direct_production_sheet = $direct_production->direct_production_sheets()->create([
            'qty_kg' => $request->qty_kgs[$key],
            'direct_production_sheetable_type' => 'App\SubRawMaterial',
            'direct_production_sheetable_id' => $request->sub_raw_material_ids[$key],
            'batch_id' => $request->batch_id[$key]
        ]);

        return $direct_production_sheet;
    }

    public function save_fm_kutcha_in($request, $key, $direct_production)
    {

        $fm_kutcha_sheet = $direct_production->direct_production_sheets()->create([
            'qty_kg' => $request->fm_kutcha_in_kgs[$key],
            'direct_production_sheetable_type' => 'App\FmKutcha',
            'direct_production_sheetable_id' => $request->fm_kutcha_in_ids[$key]
        ]);

        return $fm_kutcha_sheet;
    }

    public function save_direct_production_details($request, $key, $direct_production)
    {

        $direct_production_details = $direct_production->direct_production_details()->create([
            'product_id' => $request->product_ids[$key],
            'todays_weight' => $request->todays_weight[$key],
            'finish_quantity' => $request->finish_quantity[$key],
            'pack' => $request->pack[$key],
            'net_weight' => $request->net_weight[$key],
            'fm_kutcha_id' => $request->fm_kutcha_ids[$key],
            'kutcha_qty' => $request->kutcha_qty[$key],
            'machine_id' => $request->machine_id[$key]
        ]);

        return $direct_production_details;
    }

    public function show($directProduction)
    { }

    public function edit($directProduction)
    { }

    public function update($request, $directProduction)
    { }

    public function deduct_from_product_stock($product_id, $finish_quantity, $pack, $weight)
    {
        $product_stock = ProductStock::where('product_id', $product_id)->first();
        $product_stock->produced_quantity -= $finish_quantity;
        $product_stock->available_quantity -= $finish_quantity;
        $product_stock->produced_pack -= $pack;
        $product_stock->available_pack -= $pack;
        $product_stock->produced_weight -= $weight;
        $product_stock->available_weight -= $weight;
        $product_stock->save();

        return $product_stock;
    }

    public function deduct_from_kutcha_stock($fm_kutcha_id, $kutcha_quantity)
    {
        $kutcha_wastage_stock = KutchaWastageStock::where('fm_kutcha_id', $fm_kutcha_id)->first();
        $kutcha_wastage_stock->total_kg -= $kutcha_quantity;
        $kutcha_wastage_stock->available_kg -= $kutcha_quantity;
        $kutcha_wastage_stock->save();

        return $kutcha_wastage_stock;
    }

    public function add_raw_material_qty($sub_raw_material_id, $qty_kg, $batch_id)
    {
        $raw_material_stock = RawMaterialStock::where('sub_raw_material_id', $sub_raw_material_id)->first();
        $raw_material_stock->used_quantity -= $qty_kg;
        $raw_material_stock->available_quantity += $qty_kg;
        if($batch_id == NULL){
            $raw_material_stock->used_opening_quantity -= $qty_kg;
            $raw_material_stock->available_opening_quantity += $qty_kg;
        }
        $raw_material_stock->save();

        return $raw_material_stock;
    }

    public function add_raw_material_batch_qty($sub_raw_material_id, $qty_kg, $batch_id)
    {
        $raw_material_batch_exist = RawMaterialBatchStock::where([
            'sub_raw_material_id' => $sub_raw_material_id,
            'batch_id' => $batch_id
            ]);
            if($raw_material_batch_exist->count() > 0){
                $raw_material_batch_stock = $raw_material_batch_exist->first();
                $raw_material_batch_stock->used_quantity -= $qty_kg;
                $raw_material_batch_stock->available_quantity += $qty_kg;
                $raw_material_batch_stock->save();
            }

        return 1;
    }

    public function add_fm_kutcha_qty($fm_kutcha_id, $qty_kg)
    {
        $kutcha_wastage_stock = KutchaWastageStock::where('fm_kutcha_id', $fm_kutcha_id)->first();
        $kutcha_wastage_stock->used_kg -= $qty_kg;
        $kutcha_wastage_stock->available_kg += $qty_kg;
        $kutcha_wastage_stock->save();

        return $kutcha_wastage_stock;
    }

    public function destroy($directProduction)
    {
        foreach ($directProduction->direct_production_details as $details) {
            //deduct from product stock
            $this->deduct_from_product_stock($details->product_id, $details->finish_quantity, $details->pack, $details->weight);

            $fm_kutcha_id = $details->fm_kutcha_id;
            $kutcha_quantity = $details->kutcha_qty;
            if ($fm_kutcha_id > 0 && $kutcha_quantity > 0) {
                //deduct from kutcha wastage stock
                $this->deduct_from_kutcha_stock($fm_kutcha_id, $kutcha_quantity);
            }

            foreach ($directProduction->direct_production_sheets as $direct_production_sheet) {
                $id = $direct_production_sheet->direct_production_sheetable_id;
                $qty_kg = $direct_production_sheet->qty_kg;
                $batch_id = $direct_production_sheet->batch_id;

                //add qty with raw material stock
                if ($direct_production_sheet->direct_production_sheetable_type == 'App\SubRawMaterial') {
                    $this->add_raw_material_qty($id, $qty_kg, $batch_id);
                }

                //add qty with raw material batch stock
                if ($direct_production_sheet->direct_production_sheetable_type == 'App\SubRawMaterial') {
                    $this->add_raw_material_batch_qty($id, $qty_kg, $batch_id);
                }

                if ($direct_production_sheet->direct_production_sheetable_type == 'App\FmKutcha') {
                    //add with kutcha wastage stock    
                    $this->add_fm_kutcha_qty($id, $qty_kg);
                }

                //delete from direct production details
                $details->delete();
            }

            DB::statement("SET foreign_key_checks = 0");
            //delete from direct production sheet either raw material or kutcha
            $direct_production_sheet->delete();
        }

        //delete from direct production
        $directProduction->delete();

        return true;
    }
}
