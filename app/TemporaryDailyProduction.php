<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemporaryDailyProduction extends Model
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
}
