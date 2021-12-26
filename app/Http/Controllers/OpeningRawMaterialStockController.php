<?php

namespace App\Http\Controllers;

use App\PurchaseReceiveDetails;
use App\RawMaterialStock;
use App\SubRawMaterial;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OpeningRawMaterialStockController extends Controller
{

    public function report(Request $request)
    {


        $test = PurchaseReceiveDetails::join('sheets', 'purchase_receive_details.sub_raw_material_id', '');


        $currentUser = Auth::user();

        $flag = 0;
        foreach ($request->all() as $key => $value) {
            if (\is_null($value)) {
                continue;
            } else {
                $flag = 1;
                break;
            }
        }

        if ($flag > 0) {

            //    $start_date = Carbon::parse($request->start_date);
            //    $end_date = Carbon::parse($request->end_date);

            //    $end_date_is_greater = $end_date->gte($start_date);
            //    if(!$end_date_is_greater){
            //     //    return redirect('reports/raw_material_stocks')->with('error_message', 'Start date must be less than end date!');
            //     // return back()->withInput();
            //    }
            $sub_raw_material_stocks_exist = RawMaterialStock::where('company_id', $currentUser->company_id);
            if ($sub_raw_material_stocks_exist->count() < 1) {
                return \redirect('purchase_receives')->with('error_message', 'Please purchase raw materials!');
            }

            $data['sub_raw_material_id'] = $request->sub_raw_material_id;
            $data['start_date'] = $request->filled('start_date') ? Carbon::parse($request->start_date)->subDays(1) : '';
            $data['end_date'] = $request->filled('end_date') ? Carbon::parse($request->end_date)->addDays(1) : '';
            // dd(Carbon::now()->yesterday());
            $sub_raw_material_stocks = PurchaseReceiveDetails::join('sheets', 'purchase_receive_details.sub_raw_material_id', 'sheets.sub_raw_material_id')
                ->whereHas('purchase_receive', function ($pr) use ($data) {
                    if($data['start_date'] != ''){
                        $pr->whereDate('purchase_receive_date', '>', $data['start_date']);
                    }
                    if($data['end_date'] != ''){
                        $pr->whereDate('purchase_receive_date', '<', $data['end_date']);
                    }
                })
                ->whereHas('sub_raw_material', function ($srm) use ($data) {
                    if ($data['sub_raw_material_id'] > 0) {
                        $srm->whereId($data['sub_raw_material_id']);
                    }
                })
                ->whereHas('sub_raw_material.sheets.sheet_production', function ($sp) use ($data) {
                    if($data['start_date'] != ''){
                        $sp->whereDate('sheet_production_date', '>', $data['start_date']);
                    }

                    if($data['end_date'] != ''){
                        $sp->whereDate('sheet_production_date', '<', $data['end_date']);
                    }
                })
                ->selectRaw('sum(sheets.qty_kg) as sheet_kg, sheets.sub_raw_material_id, sum(quantity) as purchased_quantity, sum(quantity_bag) as purchased_bags')
                ->groupBy('sheets.sub_raw_material_id')
                ->paginate(25);

        } else {
            $sub_raw_material_stocks = RawMaterialStock::where('company_id', $currentUser->company_id)
                ->paginate(25);

        }

        // dd($sub_raw_material_stocks);

        $sub_raw_materials = SubRawMaterial::where('company_id', $currentUser->company_id)->get();

        return view('admin.reports.opening_raw_material_stock', compact([
            'sub_raw_material_stocks', 'sub_raw_materials',
        ]));

    }

}