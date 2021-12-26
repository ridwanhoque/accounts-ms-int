<?php

namespace App\Http\Controllers;

use App\PurchaseReceiveDetails;
use Illuminate\Http\Request;
use App\RawMaterialBatchStock;
use App\SubRawMaterial;
use Carbon\Carbon;

class RmBatchStockController extends Controller
{
    public function report(){
        $now = Carbon::now();
        $today = $now->tomorrow()->toDateString();
        // dd($today);
        $first_of_month = $now->firstOfMonth()->toDateString();
// dd($first_of_month);
        $sub_raw_material_stocks = SubRawMaterial::with('purchase_receive_details')->paginate(25);
        // dd($sub_raw_material_stocks);
        $sub_raw_materials = SubRawMaterial::subRawMaterials();
        return view('admin.reports.rm_batch_stock', compact([
            'sub_raw_material_stocks', 'sub_raw_materials'
        ]));
    }
}
