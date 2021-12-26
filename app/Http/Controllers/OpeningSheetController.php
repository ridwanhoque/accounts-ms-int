<?php

namespace App\Http\Controllers;

use App\Color;
use App\SheetproductiondetailsStock;
use App\SheetSize;
use App\SheetSizeColor;
use App\SheetStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Message;

class OpeningSheetController extends Controller
{
    public function index(){
        $currentUser = Auth::user();
        $company_id = $currentUser->company_id;
        $created_by = $currentUser->id;
        $updated_by = $currentUser->id;
        $sheet_sizes = SheetSize::sheetSizes();
        $colors = Color::where('company_id', $currentUser->company_id)->whereNotIn('id', ['2'])->pluck('name', 'id');
        
        $spdetails_stocks = SheetproductiondetailsStock::get(['id']);

        return view('admin.opening_stocks.sheets', compact('sheet_sizes', 'colors', 
                'spdetails_stocks', 'company_id', 'created_by', 'updated_by'));
    }

    public function store(Request $request){
        return redirect('production_settings/opening_sheets')->with(['message' => Message::created('sheet_stock')]);
    }

    public function ajax_qty_save(Request $request){
        $qty_kg = $request->qty_kg;
        $qty_roll = $request->qty_roll;
        $sheet_size_id = $request->sheet_size_id;
        $color_id = $request->color_id;
        $raw_material_id = $request->raw_material_id;

        $currentUser = Auth::user();

        $sheet_size_color_exists = SheetSizeColor::where([
            'sheet_size_id' => $sheet_size_id,
            'color_id' => $request->color_id,
            'raw_material_id' => $request->raw_material_id
            ]);

            if($sheet_size_color_exists->count() > 0){
                DB::transaction(function() use($request, $sheet_size_color_exists, $qty_kg, $qty_roll){
                    $spdetails_stock = SheetproductiondetailsStock::where('sheet_size_color_id',
                    $sheet_size_color_exists->first()->id);
    
                    if($spdetails_stock->count() <= 0){
                        $sheet_stock = SheetproductiondetailsStock::create([
                            'company_id' => $request->company_id,
                            'created_by' => $request->created_by,
                            'updated_by' => $request->updated_by,
                            'sheet_size_color_id' => $sheet_size_color_exists->first()->id,
                            'opening_quantity_kg' => $qty_kg,
                            'available_quantity_kg' => $qty_kg,
                            'opening_quantity_roll' => $qty_roll,
                            'available_quantity_roll' => $qty_roll
                        ]);    
                    }else{
                        $sheet_stock = SheetproductiondetailsStock::where('sheet_size_color_id',
                        $sheet_size_color_exists->first()->id)->first();
    
                        if($sheet_stock->opening_quantity_kg <= 0){
                            $sheet_stock->opening_quantity_kg += $qty_kg;
                            $sheet_stock->available_quantity_kg += $qty_kg;
                        }
    
                        if($sheet_stock->opening_quantity_roll <= 0){
                            $sheet_stock->opening_quantity_roll += $qty_roll;
                            $sheet_stock->available_quantity_roll += $qty_roll;
                        }
    
                        $sheet_stock->save();
                    }
     
                });
 
            }else{
                DB::transaction(function() use($request, $qty_kg, $qty_roll){
                    $sheet_size_color = SheetSizeColor::create([
                        'sheet_size_id' => $request->sheet_size_id,
                        'color_id' => $request->color_id,
                        'raw_material_id' => $request->raw_material_id
                    ]);
                    $sheet_stock = SheetproductiondetailsStock::create([
                        'company_id' => $request->company_id,
                        'created_by' => $request->created_by,
                        'updated_by' => $request->updated_by,
                        'sheet_size_color_id' => $sheet_size_color->id,
                        'opening_quantity_kg' => $qty_kg,
                        'available_quantity_kg' => $qty_kg,
                        'opening_quantity_roll' => $qty_roll,
                        'available_quantity_roll' => $qty_roll
                    ]); 
                });
            }

            return $request->all();
    }

}
