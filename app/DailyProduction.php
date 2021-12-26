<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Formatter;

class DailyProduction extends Model
{
    protected $guarded = ['id'];

    public static function boot(){
        parent::boot();

        static::creating(function($daily_production){
            $currentUser = Auth::user();
            $max_daily_production_id = DailyProduction::max('id') + 1;

            $daily_production->fill([
                'company_id' => $currentUser->company_id,
                'created_by' => $currentUser->id,
                'updated_by' => $currentUser->id,
                'daily_production_reference' => Formatter::code('prod', $max_daily_production_id)
            ]);
        });

        static::updating(function($daily_production){
            $currentUser = Auth::user();

            $daily_production->fill([
                'company_id' => $currentUser->company_id,
                'updated_by' => $currentUser->id
            ]);
        });
    }


    public function machine(){
        return $this->belongsTo('App\Machine');
    }

    public function daily_production_details(){
        return $this->hasMany('App\DailyProductionDetail');
    }

    public function status(){
        return $this->belongsTo('App\Status');
    }

    public function company(){
        return $this->belongsTo('App\Company');
    }

    public function total_sheet_kutchas(){
        return $this->hasOne('App\DailyProductionDetail')
            ->selectRaw('sum(todays_weight) as sum_sheet_kg, sum(wastage_out) as sum_kutcha_kg, sum(net_weight) as sum_product_weight, daily_production_id')
            ->groupBy('daily_production_id');
    }

}
