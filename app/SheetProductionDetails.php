<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Formatter;
use Auth;
use App;

class SheetProductionDetails extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        if(!App::runningInConsole()){
            static::creating(function($sheet_production_details){
                $currentUser = Auth::user();
                $sheet_production_details_max_id = SheetProductionDetails::max('id')+1;

                $sheet_production_details->fill([
                    'company_id' => $currentUser->company_id,
                    'created_by' => $currentUser->id,
                    'updated_by' => $currentUser->id,
                    'sheet_production_details_code' => Formatter::code('sheet', $sheet_production_details_max_id)
                ]);
            });


            static::updating(function($sheet_production_details){
                $currentUser = Auth::user();

                $sheet_production_details->fill([
                    'company_id' => $currentUser->company_id,
                    'updated_by' => $currentUser->id
                ]);
            });

        }

    }

    public function sheet_production(){
        return $this->belongsTo('App\SheetProduction');
    }

    public function sheets(){
        return $this->hasMany('App\Sheet');
    }

    public function color(){
        return $this->belongsTo('App\Color');
    }

    public function sheet_size(){
        return $this->belongsTo('App\SheetSize');
    }

    public function daily_production_details(){
        return $this->hasMany('App\DailyProductionDetail');
    }

    public function sheetproductiondetails_colors(){
        return $this->hasMany('App\SheetproductiondetailsColor');
    }

    public function sheetproductiondetails_stock(){
        return $this->hasOne('App\SheetproductiondetailsStock');
    }

    public function sheet_size_colors(){
        return $this->hasMany('App\SheetSizeColor');
    }

    public function forming_wastage_stocks(){
        return $this->hasMany('App\FormingWastageStock');
    }

}
