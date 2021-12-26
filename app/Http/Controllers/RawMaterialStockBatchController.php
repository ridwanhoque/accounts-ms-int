<?php

namespace App\Http\Controllers;

use App\PurchaseReceiveDetails;
use App\RawMaterialBatchStock;
use App\SubRawMaterial;
use Illuminate\Http\Request;

class RawMaterialStockBatchController extends Controller
{
    public function report(Request $request){
        $sub_raw_material_stocks = RawMaterialBatchStock::paginate(25);
        $sub_raw_materials = SubRawMaterial::subRawMaterials();
        return view('admin.reports.raw_material_stock_batch', compact([
            'sub_raw_material_stocks', 'sub_raw_materials']));
    }
}
