<?php

namespace App\Http\Controllers;

use App\SubRawMaterial;
use App\RawMaterialStock;
use Auth;
use Illuminate\Http\Request;
use Message;

class OpeningSubRawMaterialController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        $sub_raw_materials = SubRawMaterial::where('company_id', $currentUser->company_id)->get();
        return view('admin.opening_stocks.sub_raw_materials', compact('sub_raw_materials'));
    }

    public function store(Request $request)
    {
    
        foreach ($request->sub_raw_material_ids as $key => $sub_raw_material_id) {
            $raw_material_stock_exists = RawMaterialStock::where('sub_raw_material_id', $sub_raw_material_id);

            $qty_kg = $request->qty_kgs[$key];
            $bags = $request->bags[$key];
            $opening_price = $request->price[$key];
            $opening_price_total = $request->opening_price_total[$key];

            if ($qty_kg > 0 || $bags > 0 || $opening_price > 0) {
                if ($raw_material_stock_exists->count() <= 0) {
                    $raw_material_stock = RawMaterialStock::create([
                        'sub_raw_material_id' => $sub_raw_material_id,
                        'opening_quantity' => $qty_kg,
                        'available_opening_quantity' => $qty_kg,
                        'available_quantity' => $qty_kg,
                        'opening_bags' => $bags,
                        'available_bags' => $bags,
                        'opening_price' => $opening_price,
                        'opening_price_total' => $opening_price_total
                    ]);
                }else{
                    $raw_material_stock = $raw_material_stock_exists->first();
                    
                    if($raw_material_stock->opening_quantity <= 0){
                        $raw_material_stock->opening_quantity += $qty_kg;
                        $raw_material_stock->available_quantity += $qty_kg;
                    }

                    if($raw_material_stock->opening_price_total <= 0){
                        $raw_material_stock->opening_price_total += $opening_price_total;
                    }

                    if($raw_material_stock->opening_price <= 0){
                        $raw_material_stock->opening_price += $opening_price;
                    }

                    if($raw_material_stock->opening_bags <= 0){
                        $raw_material_stock->opening_bags += $bags;
                        $raw_material_stock->available_bags += $bags;
                    }
                    
                    $raw_material_stock->save();
                }

            }

        }

        return \redirect('production_settings/opening_sub_raw_materials')->with(['message' => Message::created('raw_material_stock')]);
    }
}
