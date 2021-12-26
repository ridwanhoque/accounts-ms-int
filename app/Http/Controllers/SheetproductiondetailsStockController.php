<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SheetproductiondetailsStock;

class SheetproductiondetailsStockController extends Controller
{
    public function report(){
        $sheetproductiondetails_stocks_exist = SheetproductiondetailsStock::first();
        // if($sheetproductiondetails_stocks_exist != null)
        {
            $sheetproductiondetails_stocks = [];
            if(SheetproductiondetailsStock::count() > 0){
                $sheetproductiondetails_stocks = SheetproductiondetailsStock::first()->sheet_material_stock()->get();
            }
  
            return view('admin.reports.sheet_material_stock', \compact('sheetproductiondetails_stocks'));
        }
        // else{
        //     return \redirect('sheet_productions')->with('error_message', 'no stock');
        // }
    }

    public function sheet_size_color_stocks(){
        $sheet_size_color_stocks = SheetproductiondetailsStock::query()
            ->whereHas('sheet_production_details', function($q){
                $q->selectRaw('sum(total_quantity_roll) as total_roll')
                ->groupBy('sheet_production_details.sheet_size_id','sheet_production_details.color_id');
            })->get();

            return $sheet_size_color_stocks;
    }

    public function ajax_sheet_kg_roll(Request $request){
        if($request->ajax()){
            $spdetails_stock = SheetproductiondetailsStock::where('sheet_size_color_id', $request->id)->first();

            $data = [
                'qty_roll' => $spdetails_stock->available_quantity_roll,
                'qty_kg' => $spdetails_stock->available_quantity_kg
            ];
    
            return response()->json($data);
    
        }
    }
}
