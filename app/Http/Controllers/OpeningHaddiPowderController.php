<?php

namespace App\Http\Controllers;

use App\HaddiPowderStock;
use App\SubRawMaterial;
use Illuminate\Http\Request;
use Message;

class OpeningHaddiPowderController extends Controller
{
    public function index()
    {
        $sub_raw_materials = SubRawMaterial::subRawMaterials();
        return view('admin.opening_stocks.haddi_powders', compact('sub_raw_materials'));
    }

    public function store(Request $request)
    {
        foreach ($request->sub_raw_material_ids as $key => $sub_raw_material) {
            $haddi = $request->opening_haddi[$key];
            $powder = $request->opening_powder[$key];

            $haddi_powder_stock_exist = HaddiPowderStock::where('sub_raw_material_id', $sub_raw_material);
            if ($haddi_powder_stock_exist->count() > 0) {
                $haddi_powder_stock = $haddi_powder_stock_exist->first();

                if ($haddi_powder_stock->opening_haddi <= 0) {
                    $haddi_powder_stock->opening_haddi += $haddi;
                }
                if ($haddi_powder_stock->opening_powder <= 0) {
                    $haddi_powder_stock->opening_powder += $powder;
                }

                $haddi_powder_stock->save();
            } else {
                $haddi_powder_stock = HaddiPowderStock::create([
                    'sub_raw_material_id' => $sub_raw_material,
                    'opening_haddi' => $haddi,
                    'haddi' => $haddi,
                    'opening_powder' => $powder,
                    'powder' => $powder
                ]);
            }
        }

        if ($haddi_powder_stock) {
            return redirect()->back()->with('message', Message::created('haddi_powder_stock'));
        }

    }
}
