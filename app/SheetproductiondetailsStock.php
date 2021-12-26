<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\SheetSize;
use App;
use Auth;

class SheetproductiondetailsStock extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();
        if(!App::runningInConsole() && !request()->ajax()){
            static::creating(function($sheet_production_details_stock){

                $currentUser = Auth::user();
    
                $sheet_production_details_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'opening_quantity_kg' => 0,
                    'opening_quantity_roll' => 0
                ]);
            });

            static::updating(function($sheet_production_details_stock){

                $currentUser = Auth::user();

                $sheet_production_details_stock->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id
                ]);

            });
        }
        
    }

    public function openingSheetQty($sheet_size_id, $color_id){
       
        if($sheet_size_id > 0 && $color_id > 0){
            $sheet_size_color = SheetSizeColor::where([
                'sheet_size_id' => $sheet_size_id,
                'color_id' => $color_id,
                'raw_material_id' => SheetSize::find($sheet_size_id)->raw_material_id
            ]);
            
            if($sheet_size_color->count() > 0){
                $spdetails_stock_db = $this->where('sheet_size_color_id', $sheet_size_color->first()->id)->first();
                $opening_sheet_qty['opening_kg'] = $spdetails_stock_db->opening_quantity_kg;
                $opening_sheet_qty['opening_roll'] = $spdetails_stock_db->opening_quantity_roll;
            }else{
                $opening_sheet_qty['opening_kg'] = 0;
                $opening_sheet_qty['opening_roll'] = 0;
            }
        }else{
            $opening_sheet_qty['opening_kg'] = 0;
            $opening_sheet_qty['opening_roll'] = 0;
        }
        
        return $opening_sheet_qty;
    }

    public function sheet_production_details(){
        return $this->belongsTo('App\SheetProductionDetails');
    }

    public function sheet_size_color_stock(){
        return $this->join('sheet_production_details', 'sheet_production_details.id', '=', 'sheetproductiondetails_stocks.sheet_production_details_id')
            ->selectRaw('sum(total_quantity_roll) as total_roll, sum(used_quantity_roll) as used_roll, sum(available_quantity_roll) as available_roll,
            sum(total_quantity_kg) as total_kg, sum(used_quantity_kg) as used_kg, sum(available_quantity_kg) as available_kg, 
            sheetproductiondetails_stocks.sheet_production_details_id')    
            ->groupBy('sheet_production_details.sheet_size_id', 'sheet_production_details.color_id')
            ;
    }

    public function sheet_size_color_stock1(){
        return $this->belongsTo('App\SheetProductionDetails', 'sheet_production_details_id')
            ->selectRaw('sum(sheet_production_details_stocks.total_quantity_roll) as total_roll')
            ->groupBy('sheet_production_details.sheet_size_id')->get();
    }



    public function sheet_material_stock(){
        return $this->groupBy('sheet_size_color_id')
            ->selectRaw('sheet_size_color_id, sum(total_quantity_kg) as total_kg, sum(used_quantity_kg) 
			as used_kg, sum(available_quantity_kg )as available_kg, sum(total_quantity_roll) as total_roll,
            sum(used_quantity_roll) as used_roll, sum(available_quantity_roll) as available_roll,
            sum(opening_quantity_kg) as opening_kg, sum(opening_quantity_roll) as opening_roll
            ');
    }

    public function sheet_size_color(){
        return $this->belongsTo('App\SheetSizeColor');
    }
      
}
