<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FmKutcha;
use App\KutchaWastageStock;
use Auth;
use Message;

class OpeningFmKutchaController extends Controller
{
    public function index(){
        $currentUser = Auth::user();
        $fm_kutchas = FmKutcha::where('company_id', $currentUser->company_id)->get();
        return view('admin.opening_stocks.fm_kutchas', compact('fm_kutchas'));
    }

    public function store(Request $request){
        foreach($request->fm_kutcha_ids as $key => $fm_kutcha_id){
            $qty_kg = $request->qty_kgs[$key];

            if($qty_kg > 0){
                $kutcha_wastage_stock_exists = KutchaWastageStock::where('fm_kutcha_id', $fm_kutcha_id);

                if($kutcha_wastage_stock_exists->count() <= 0){
                    $kutcha_wastage_stock = KutchaWastageStock::create([
                        'fm_kutcha_id' => $fm_kutcha_id,
                        'opening_kg' => $qty_kg,
                        'available_kg' => $qty_kg
                    ]);
                }else{
                    $kutcha_wastage_stock = $kutcha_wastage_stock_exists->first();
                    if($kutcha_wastage_stock->opening_kg <= 0){
                        $kutcha_wastage_stock->opening_kg += $qty_kg;
                        $kutcha_wastage_stock->available_kg += $qty_kg;
                        $kutcha_wastage_stock->save();
                    }
                }
            }
        }

        return redirect('production_settings/opening_fm_kutchas')->with(['message' => Message::created('kutcha_wastage_stock')]);
    }
}
