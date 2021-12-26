<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SheetSizeColor extends Model
{
    protected $guarded =['id'];

    public function sheet_size(){
        return $this->belongsTo('App\SheetSize');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }

    public function sheetproductiondetails_stocks(){
        return $this->hasMany('App\SheetproductiondetailsStock');
    }

    public function sheet_production_details(){
        return $this->belongsTo('App\SheetProductionDetails');
    }

    public function sheets(){
        return $this->belongsTo('App\Sheet', 'sheet_production_details_id', 'sheet_production_details_id');
    }

    public function daily_production_details(){
        return $this->hasMany('App\DailyProductionDetails');
    }

    public function raw_material(){
        return $this->belongsTo('App\RawMaterial');
    }


    public function spd_stock_available(){
        $this->hasOne('App\SheetproductiondetailsStock')->selectRaw('sum(available_quantity_kg) available, sheet_size_color_id')->groupBy('sheet_size_color_id')->get();
    }
}
