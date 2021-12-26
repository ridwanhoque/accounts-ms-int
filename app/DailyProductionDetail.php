<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\DailyProductionDetails;
use Auth;
use Formatter;

class DailyProductionDetail extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($daily_production_details){
            $currentUser = Auth::user();
            $max_daily_production_details_id = DailyProductionDetail::max('id') + 1;

            $daily_production_details->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id,
                'daily_production_details_code' => Formatter::code('lot', $max_daily_production_details_id)
            ]);
        });

        static::updating(function($daily_production_details){
            $currentUser = Auth::user();

            $daily_production_details->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });
    }

    public function sheetproductiondetails_color(){
        return $this->belongsTo('App\SheetproductiondetailsColor');
    }

    public function daily_production(){
        return $this->belongsTo('App\DailyProduction');
    }

    public function product(){
        return $this->belongsTo('App\Product');
    }

    public function machine(){
        return $this->belongsTo('App\Machine');
    }

    public function product_delivery_details(){
        return $this->hasMany('App\ProductDeliveryDetails');
    }

    public function sheet_size_color(){
        return $this->belongsTo('App\SheetSizeColor');
    }

    public function setStandardWeightAttribute($value){
       return $this->attributes['standard_weight'] = $value>0 ? $value:0;
    }

    public function fm_kutcha(){
        return $this->belongsTo('App\FmKutcha');
    }


}
